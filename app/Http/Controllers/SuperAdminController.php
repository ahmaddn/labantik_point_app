<?php

namespace App\Http\Controllers;

use App\Models\P_Config_Handlings;
use App\Models\P_Configs;
use Illuminate\Http\Request;
use App\Models\RefStudentAcademicYear;
use App\Models\P_Violations;
use App\Models\P_Recaps;
use App\Models\RefClass;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use App\Models\P_Viol_Action;
use App\Models\P_Viol_Action_Detail;

class SuperAdminController extends Controller
{
    public function index()
    {
        // Ambil tahun akademik aktif
        $activeAcademicYear = P_Configs::getActiveAcademicYear();

        if (!$activeAcademicYear) {
            return view('superadmin.dashboard.index', [
                'totalViolations' => 0,
                'studentsWithoutViolations' => 0,
                'topClass' => null,
                'topStudent' => null,
                'mostFrequentViolation' => null
            ]);
        }

        // Normalisasi format academic_year
        $academicYear = str_replace('-', '/', $activeAcademicYear->academic_year);

        // 1. Total Pelanggaran (hanya yang verified)
        $totalViolations = P_Recaps::where('status', 'verified')->count();

        // 2. Siswa Tanpa Pelanggaran
        // Ambil semua siswa aktif di tahun akademik ini
        $totalActiveStudents = RefStudentAcademicYear::where('ref_student_academic_years.academic_year', $academicYear)->count();

        // Ambil jumlah siswa yang memiliki pelanggaran verified
        $studentsWithViolations = P_Recaps::where('status', 'verified')
            ->distinct('ref_student_id')
            ->count('ref_student_id');

        $studentsWithoutViolations = $totalActiveStudents - $studentsWithViolations;

        // 3. Kelas dengan Poin Terbanyak
        $topClass = RefStudentAcademicYear::where('ref_student_academic_years.academic_year', $academicYear)
            ->join('ref_classes', 'ref_student_academic_years.class_id', '=', 'ref_classes.id')
            ->join('ref_students', 'ref_student_academic_years.student_id', '=', 'ref_students.id')
            ->leftJoin('p_recaps', function ($join) {
                $join->on('ref_students.id', '=', 'p_recaps.ref_student_id')
                    ->where('p_recaps.status', '=', 'verified');
            })
            ->leftJoin('p_violations', 'p_recaps.p_violation_id', '=', 'p_violations.id')
            ->select(
                'ref_classes.name as class_name',
                DB::raw('COALESCE(SUM(p_violations.point), 0) as total_points')
            )
            ->groupBy('ref_classes.id', 'ref_classes.name')
            ->orderBy('total_points', 'desc')
            ->first();

        // 4. Siswa dengan Poin Terbanyak
        $topStudent = RefStudentAcademicYear::where('ref_student_academic_years.academic_year', $academicYear)
            ->join('ref_students', 'ref_student_academic_years.student_id', '=', 'ref_students.id')
            ->join('ref_classes', 'ref_student_academic_years.class_id', '=', 'ref_classes.id')
            ->leftJoin('p_recaps', function ($join) {
                $join->on('ref_students.id', '=', 'p_recaps.ref_student_id')
                    ->where('p_recaps.status', '=', 'verified');
            })
            ->leftJoin('p_violations', 'p_recaps.p_violation_id', '=', 'p_violations.id')
            ->select(
                'ref_students.full_name as student_name',
                'ref_students.student_number as nis',
                'ref_classes.name as class_name',
                DB::raw('COALESCE(SUM(p_violations.point), 0) as total_points')
            )
            ->groupBy('ref_students.id', 'ref_students.full_name', 'ref_students.student_number', 'ref_classes.name')
            ->orderBy('total_points', 'desc')
            ->first();

        // 5. Pelanggaran Paling Sering
        $mostFrequentViolation = P_Recaps::where('status', 'verified')
            ->join('p_violations', 'p_recaps.p_violation_id', '=', 'p_violations.id')
            ->join('p_categories', 'p_violations.p_category_id', '=', 'p_categories.id')
            ->select(
                'p_violations.name as violation_name',
                'p_violations.point',
                'p_categories.name as category_name',
                DB::raw('COUNT(p_recaps.id) as violation_count')
            )
            ->groupBy(
                'p_violations.id',
                'p_violations.name',
                'p_violations.point',
                'p_categories.name'
            )
            ->orderBy('violation_count', 'desc')
            ->first();

        return view('superadmin.dashboard.index', compact(
            'totalViolations',
            'studentsWithoutViolations',
            'topClass',
            'topStudent',
            'mostFrequentViolation'
        ));
    }
    public function studentData(Request $request)
    {
        // Ambil tahun akademik aktif
        $activeAcademicYear = P_Configs::getActiveAcademicYear();

        // Ambil semua kelas untuk filter
        $classes = RefClass::orderBy('academic_level', 'asc')->get();

        // Ambil semua violations dengan sorting
        $vals = P_Violations::with('category')->orderBy('point', 'asc')->get();

        // Jika ada filter kelas, ambil data siswa
        $studentAcademicYears = collect();
        $selectedClassId = $request->input('class_id');

        if ($selectedClassId) {
            $studentAcademicYears = RefStudentAcademicYear::activeAcademicYear()
                ->where('class_id', $selectedClassId)
                ->with([
                    'student',
                    'recaps' => function ($query) {
                        $query->orderBy('created_at', 'desc');
                    },
                    'class'
                ])
                ->get();
        }

        return view('superadmin.student-data.index', compact(
            'studentAcademicYears',
            'vals',
            'activeAcademicYear',
            'classes',
            'selectedClassId'
        ));
    }

    public function store(Request $request, $studentId)
    {
        $request->validate([
            'violations'   => 'required|array',
            'violations.*' => 'exists:p_violations,id',
        ]);

        // Cek apakah siswa aktif di tahun akademik yang sedang berjalan
        $activeConfig = P_Configs::getActiveAcademicYear();

        if (!$activeConfig) {
            return back()->withErrors([
                'error' => 'Tidak ada konfigurasi tahun akademik yang aktif.'
            ]);
        }

        // Ambil academic_year dan normalisasi format
        $activeAcademicYear = $activeConfig->academic_year;
        $activeAcademicYear = str_replace('-', '/', $activeAcademicYear);

        // PERBAIKAN PENTING:
        // Parameter $studentId yang masuk adalah ID dari tabel ref_student_academic_years
        // Bukan student_id dari tabel students

        // Langsung ambil data dari ref_student_academic_years berdasarkan ID (primary key)
        $studentAcademicYear = RefStudentAcademicYear::where('id', $studentId)
            ->with('student')
            ->where('academic_year', $activeAcademicYear)
            ->first();

        if (!$studentAcademicYear) {
            return back()->withErrors([
                'error' => 'Data siswa tidak ditemukan untuk tahun akademik aktif (' . $activeAcademicYear . ')'
            ]);
        }

        // Ambil data student untuk informasi tambahan
        $student = $studentAcademicYear->student;

        if (!$student) {
            return back()->withErrors([
                'error' => 'Data siswa tidak ditemukan dalam sistem.'
            ]);
        }

        // Hitung total poin verified saat ini
        // PENTING: Filter berdasarkan student_id (FK ke ref_students)
        $currentVerifiedPoints = P_Recaps::where('ref_student_id', $studentAcademicYear->student_id)
            ->where('status', 'verified')
            ->join('p_violations', 'p_recaps.p_violation_id', '=', 'p_violations.id')
            ->sum('p_violations.point');

        // Hitung total poin pending saat ini
        $currentPendingPoints = P_Recaps::where('ref_student_id', $studentAcademicYear->student_id)
            ->where('status', 'pending')
            ->join('p_violations', 'p_recaps.p_violation_id', '=', 'p_violations.id')
            ->sum('p_violations.point');

        // Total poin saat ini
        $currentTotalPoints = $currentVerifiedPoints + $currentPendingPoints;

        // Hitung poin baru yang akan ditambah
        $newPoints = 0;
        $violationIds = $request->violations;

        if (!empty($violationIds)) {
            $newPoints = P_Violations::whereIn('id', $violationIds)->sum('point');
        }

        // Hitung total poin setelah penambahan
        $totalPointsAfterAdd = $currentTotalPoints + $newPoints;

        // VALIDASI: Cek apakah total poin saat ini sudah mencapai 100
        if ($currentTotalPoints >= 100) {
            return back()->withErrors([
                'error' => 'Siswa sudah mencapai batas maksimal 100 poin. Tidak dapat menambah pelanggaran lagi.'
            ])->with([
                'current_verified_points' => $currentVerifiedPoints,
                'current_pending_points' => $currentPendingPoints,
                'current_total_points' => $currentTotalPoints,
                'new_points' => $newPoints,
                'academic_year' => $activeAcademicYear
            ]);
        }

        // VALIDASI: Cek apakah penambahan akan melebihi 100
        if ($totalPointsAfterAdd > 100) {
            $excessPoints = $totalPointsAfterAdd - 100;
            return back()->withErrors([
                'error' => 'Penambahan pelanggaran ini akan melebihi batas maksimal 100 poin. Kelebihan: ' . $excessPoints . ' poin.'
            ])->with([
                'current_verified_points' => $currentVerifiedPoints,
                'current_pending_points' => $currentPendingPoints,
                'current_total_points' => $currentTotalPoints,
                'new_points' => $newPoints,
                'total_points_after' => $totalPointsAfterAdd,
                'excess_points' => $excessPoints,
                'academic_year' => $activeAcademicYear
            ]);
        }

        // Simpan pelanggaran ke database
        try {
            DB::beginTransaction();

            foreach ($violationIds as $violationId) {
                P_Recaps::create([
                    // PENTING: Gunakan student_id (FK ke ref_students), bukan id dari ref_student_academic_years
                    'ref_student_id'  => $studentAcademicYear->student_id,
                    'p_violation_id'  => $violationId,
                    'status'          => 'pending',
                    'created_by'      => Auth::id(),
                    'updated_by'      => Auth::id(),
                ]);
            }

            DB::commit();

            // Hitung ulang poin setelah penyimpanan
            // CATATAN: Sekarang filter berdasarkan student_id, bukan ref_student_academic_years.id
            $verifiedPoints = P_Recaps::where('ref_student_id', $studentAcademicYear->student_id)
                ->where('status', 'verified')
                ->join('p_violations', 'p_recaps.p_violation_id', '=', 'p_violations.id')
                ->sum('p_violations.point');

            $pendingPoints = P_Recaps::where('ref_student_id', $studentAcademicYear->student_id)
                ->where('status', 'pending')
                ->join('p_violations', 'p_recaps.p_violation_id', '=', 'p_violations.id')
                ->sum('p_violations.point');

            $totalAllPoints = $verifiedPoints + $pendingPoints;

            return back()->with([
                'success' => 'Pelanggaran berhasil ditambahkan untuk ' . $student->name . ' (Tahun Akademik: ' . $activeAcademicYear . ')!',
                'verified_points' => $verifiedPoints,
                'pending_points' => $pendingPoints,
                'total_all_points' => $totalAllPoints,
                'added_points' => $newPoints,
                'academic_year' => $activeAcademicYear
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors([
                'error' => 'Terjadi kesalahan saat menyimpan data: ' . $e->getMessage()
            ]);
        }
    }
    public function confirmRecaps()
    {
        $activeAcademicYear = P_Configs::where('is_active', true)->first();
        $handlingOptions = P_Config_Handlings::where('p_config_id', $activeAcademicYear->id)
            ->orderBy('handling_point', 'asc')
            ->get();

        // Filter siswa yang memiliki recaps pending di controller
        $studentAcademicYears = RefStudentAcademicYear::activeAcademicYear()
            ->with([
                'student',
                'class',
                'recaps' => function ($query) {
                    $query->with([
                        'violation.category',
                        'verifiedBy',
                        'createdBy',
                        'updatedBy',
                    ])
                        ->orderBy('created_at', 'asc');
                }
            ])
            ->has('recaps')
            ->get()
            ->filter(function ($student) {
                return $student->recaps->count() > 0;
            })
            ->map(function ($student) use ($handlingOptions) {
                $student->total_points_verified = $student->recaps
                    ->where('status', 'verified')
                    ->sum(function ($recap) {
                        return $recap->violation->point ?? 0;
                    });

                // Filter handling options berdasarkan total poin siswa (kurang dari)
                $student->available_handlings = $handlingOptions->filter(function ($handling) use ($student) {
                    return $handling->handling_point <= $student->total_points_verified;
                });

                return $student;
            })
            ->values();

        return view('superadmin.confirm-recaps.index', compact('studentAcademicYears', 'handlingOptions', 'activeAcademicYear'));
    }

    public function detailConfirmRecaps($studentAcademicYearId)
    {
        $activeAcademicYear = P_Configs::where('is_active', true)->first();
        $handlingPointOptions = P_Config_Handlings::where('p_config_id', $activeAcademicYear->id)
            ->orderBy('handling_point', 'asc')
            ->get();

        $studentAcademicYear = RefStudentAcademicYear::with([
            'student',
            'class',
            'recaps' => function ($query) {
                $query->with([
                    'violation.category',
                    'verifiedBy',
                    'createdBy',
                    'updatedBy',
                ])->orderBy('created_at', 'desc');
            }
        ])->findOrFail($studentAcademicYearId);

        $totalVerifiedPoints = $studentAcademicYear->recaps
            ->where('status', 'verified')
            ->sum(function ($recap) {
                return $recap->violation->point ?? 0;
            });

        $applicableHandling  = null;
        foreach ($handlingPointOptions as $handling) {
            if ($totalVerifiedPoints >= $handling->handling_point) {
                $applicableHandling = $handling;
            } else {
                break;
            }
        }

        return view('superadmin.confirm-recaps.detail', compact(
            'studentAcademicYear',
            'handlingPointOptions',
            'totalVerifiedPoints',
            'applicableHandling'
        ));
    }
    public function updateViolationStatus(Request $request, $id)
    {
        try {
            $recap = P_Recaps::findOrFail($id);

            $request->validate([
                'status' => 'required|in:verified,not_verified,pending'
            ]);

            $recap->update([
                'status' => $request->status,
                'verified_by' => Auth::id(),
                'updated_by' => Auth::id()
            ]);

            return redirect()->back()->with('success', 'Status pelanggaran berhasil diperbarui!');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect()->back()->with('error', 'Data pelanggaran tidak ditemukan!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan saat memperbarui status: ' . $e->getMessage());
        }
    }

    public function destroyRecap($id)
    {
        try {
            $recap = P_Recaps::findOrFail($id);

            $recap->delete();

            return redirect()->back()->with('success', 'Rekap pelanggaran berhasil dihapus!');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect()->back()->with('error', 'Data rekap pelanggaran tidak ditemukan!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menghapus rekap pelanggaran: ' . $e->getMessage());
        }
    }


    public function storeHandlingAction(Request $request, $id)
    {
        $request->validate([
            'handling_id' => 'required|exists:p_config_handlings,id',
            'description' => 'nullable|string',
            'prey' => 'nullable|date',
            'action_date' => 'nullable|date',
            'reference_number' => 'nullable|string|max:191',
            'time' => 'nullable|string|max:50',
            'room' => 'nullable|string|max:100',
            'facing' => 'nullable|string|max:100',
        ]);

        // Ambil data student (ref_student_academic_years id)
        $studentAcademicYear = RefStudentAcademicYear::with([
            'student',
            'class',
            'recaps.violation.category'
        ])->find($id);

        if (!$studentAcademicYear) {
            return back()->withErrors(['error' => 'Data tahun akademik siswa tidak ditemukan.']);
        }

        $student = $studentAcademicYear->student;

        if (!$student) {
            return back()->withErrors(['error' => 'Data siswa tidak ditemukan dalam sistem.']);
        }

        // Pastikan data wali (guardian) tersedia
        if (empty($student->guardian_name)) {
            return back()->withErrors(['error' => 'Data wali siswa belum lengkap. Mohon lengkapi data wali terlebih dahulu.']);
        }

        // Ambil data handling
        $handling = P_Config_Handlings::findOrFail($request->handling_id);

        // Tentukan rekap yang akan dihubungkan ke tindakan
        // Preferensi: latest verified -> latest pending -> any latest

        // Fallback: jika collection recaps kosong atau id tidak tersedia, cari langsung dari tabel p_recaps


        // Mulai transaksi untuk menyimpan action dan detail
        try {
            DB::beginTransaction();

            Log::info('Creating P_Viol_Action', [
                'p_student_academic_year_id' => $studentAcademicYear->id,
                'handling_id' => $request->handling_id,
                'handled_by' => Auth::id(),
                'student_academic_year_id' => $studentAcademicYear->id,
                'student_id' => $studentAcademicYear->student_id,
            ]);

            $action = P_Viol_Action::create([
                'p_student_academic_year_id' => $studentAcademicYear->id,
                'handling_id' => $request->handling_id,
                'handled_by' => Auth::id(),
                'activity' => $handling->handling_action ?? null,
                'description' => $request->description,
            ]);

            P_Viol_Action_Detail::create([
                'p_viol_action_id' => $action->id,
                'parent_name' => $student->guardian_name ?? null,
                'student_name' => $student->full_name ?? null,
                'prey' => $request->prey,
                'action_date' => $request->action_date,
                'reference_number' => $request->reference_number,
                'time' => $request->time,
                'room' => $request->room,
                'facing' => $request->facing,
            ]);

            DB::commit();

            // Hitung total poin verified (untuk PDF dan info)
            $totalPoints = $studentAcademicYear->recaps
                ->where('status', 'verified')
                ->sum(fn($recap) => $recap->violation->point ?? 0);

            // Data untuk PDF — include lowercase variable names and kelas
            $preyDate = $request->prey ? Carbon::parse($request->prey)->format('d F Y') : Carbon::now()->format('d F Y');
            $actionDateFormatted = $request->action_date ? Carbon::parse($request->action_date)->format('d F Y') : '';
            $kelasString = trim((($studentAcademicYear->class->academic_level ?? '') . ' ' . ($studentAcademicYear->class->name ?? '')));

            $data = [
                'student' => $student,
                'class' => $studentAcademicYear->class,
                'handling' => $handling,
                'description' => $request->description,
                'total_points' => $totalPoints,
                'date' => $preyDate,
                'violations' => $studentAcademicYear->recaps->where('status', 'verified'),

                // Lowercase keys for blade
                'prey' => $preyDate,
                'reference_number' => $request->reference_number ?? '',
                'student_name' => $student->full_name ?? '',
                'parent_name' => $student->guardian_name ?? '',
                'action_date' => $actionDateFormatted,
                'time' => $request->time ?? '',
                'room' => $request->room ?? '',
                'facing' => $request->facing ?? '',
                'kelas' => $kelasString,
            ];

            // Render the panggilan view in browser so user can preview and print manually
            return view('pdf.panggilan', $data);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('storeHandlingAction error: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Terjadi kesalahan saat menyimpan tindakan: ' . $e->getMessage()]);
        }
    }

    public function actions()
    {
        $actions = P_Viol_Action::with([
            'academicYear.pRecaps.violation', // Load semua recaps beserta violation
            'academicYear.student',            // Load student info
            'academicYear.class',              // Load class info
            'handling',
            'handle',
            'detail'
        ])->orderBy('created_at', 'desc')->get();

        return view('superadmin.actions.index', compact('actions'));
    }
}
