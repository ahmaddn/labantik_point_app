<?php

namespace App\Http\Controllers;

use App\Models\P_Config_Handlings;
use App\Models\P_Configs;
use App\Models\P_Violations;
use App\Models\RefClass;
use Illuminate\Http\Request;
use App\Models\RefStudentAcademicYear;
use App\Models\P_Recaps;
use App\Models\P_Viol_Action;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class BKController extends Controller
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

        return view('bk.student-data.index', compact(
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

    public function updateViolationStatus(Request $request, $id)
    {
        // Validasi input
        $request->validate([
            'status' => 'required|in:pending,verified,not_verified'
        ]);

        try {
            Log::info('Searching for P_Recaps record', ['id' => $id]);

            $recap = P_Recaps::find($id);

            if (!$recap) {
                Log::error('P_Recaps not found with ID', ['id' => $id]);

                $recap = P_Recaps::where('p_violation_id', $id)->first();

                if ($recap) {
                    Log::info('Found P_Recaps with p_violation_id', [
                        'p_violation_id' => $id,
                        'actual_id' => $recap->id
                    ]);
                } else {
                    Log::error('P_Recaps not found with any ID field', ['searched_id' => $id]);
                    return redirect()->back()->with('error', 'Data pelanggaran tidak ditemukan (ID: ' . $id . ')');
                }
            }

            $originalStatus = $recap->status;
            Log::info('Found P_Recaps record', [
                'id' => $recap->id,
                'p_violation_id' => $recap->p_violation_id ?? 'not_set',
                'current_status' => $originalStatus,
                'new_status' => $request->status,
                'primary_key' => $recap->getKeyName(),
                'key_value' => $recap->getKey()
            ]);

            $recap->status = $request->status;

            if (Auth::check()) {
                $recap->verified_by = Auth::id();
                $recap->updated_by = Auth::id();
            }

            $recap->updated_at = now();

            Log::info('Attempting to save P_Recaps', [
                'changes' => $recap->getDirty()
            ]);

            $saved = $recap->save();

            $recap->refresh();
            Log::info('Save completed', [
                'save_result' => $saved,
                'new_status' => $recap->status,
                'status_changed' => $originalStatus !== $recap->status
            ]);

            if ($saved && $originalStatus !== $recap->status) {
                return redirect()->back()->with(
                    'success',
                    "Status berhasil diubah dari '{$originalStatus}' ke '{$recap->status}' "
                );
            } else {
                return redirect()->back()->with(
                    'warning',
                    'Data disimpan tetapi status tidak berubah. Original: ' . $originalStatus . ', Current: ' . $recap->status
                );
            }
        } catch (\Exception $e) {
            Log::error('Exception in updateViolationStatus', [
                'id' => $id,
                'error_message' => $e->getMessage(),
                'error_line' => $e->getLine(),
                'error_file' => $e->getFile(),
                'stack_trace' => $e->getTraceAsString()
            ]);

            return redirect()->back()->with(
                'error',
                'Error: ' . $e->getMessage() . ' (Line: ' . $e->getLine() . ')'
            );
        }
    }

    public function debugRecap($id)
    {
        $recap = P_Recaps::find($id);

        return response()->json([
            'found' => $recap ? true : false,
            'data' => $recap ? $recap->toArray() : null,
            'model_info' => [
                'table' => (new P_Recaps)->getTable(),
                'primary_key' => (new P_Recaps)->getKeyName(),
                'fillable' => (new P_Recaps)->getFillable(),
            ]
        ]);
    }

    public function recaps(Request $request)
    {
        // Ambil tahun akademik aktif
        $activeAcademicYear = P_Configs::getActiveAcademicYear();
        // Ambil handling options
        $handlingOptions = P_Config_Handlings::where('p_config_id', $activeAcademicYear->id)
            ->orderBy('handling_point', 'asc')
            ->get();
        // Query dari RefStudentAcademicYear sebagai base
        $recaps = RefStudentAcademicYear::activeAcademicYear()
            ->whereHas('recaps') // Filter hanya yang punya recaps
            ->with([
                'recaps' => function ($query) {
                    $query->with([
                        'violation.category',
                        'verifiedBy',
                        'createdBy',
                        'updatedBy',
                    ]);
                },
                'student',
                'class'
            ])
            ->get()
            ->map(function ($studentAcademicYear) use ($handlingOptions) {
                // Hitung total poin violations yang verified
                $verifiedPoints = $studentAcademicYear->recaps
                    ->where('status', 'verified')
                    ->whereNotNull('violation')
                    ->sum(function ($recap) {
                        return $recap->violation->point ?? 0;
                    });

                $studentAcademicYear->violations_sum_point = $verifiedPoints;
                $studentAcademicYear->available_handlings = $handlingOptions->filter(function ($handling) use ($verifiedPoints) {
                    return $verifiedPoints >= $handling->handling_point;
                });
                return $studentAcademicYear;
            });

        return view('BK.dashboard.recaps', compact('recaps', 'activeAcademicYear', 'handlingOptions'));
    }

    public function detailRecaps($studentAcademicYearId)
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

        return view('bk.dashboard.detail', compact(
            'studentAcademicYear',
            'handlingPointOptions',
            'totalVerifiedPoints',
            'applicableHandling'
        ));
    }

    public function storeHandlingAction(Request $request, $id)
    {
        $request->validate([
            'handling_id' => 'required|exists:p_config_handlings,id',
            'description' => 'nullable|string',
        ]);

        // Ambil data student
        $studentAcademicYear = RefStudentAcademicYear::with([
            'student',
            'class',
            'recaps.violation.category'
        ])->findOrFail($id);

        // Ambil data handling
        $handling = P_Config_Handlings::findOrFail($request->handling_id);

        // Hitung total poin verified
        $totalPoints = $studentAcademicYear->recaps
            ->where('status', 'verified')
            ->sum(fn($recap) => $recap->violation->point ?? 0);

        // Data untuk PDF
        $data = [
            'student' => $studentAcademicYear->student,
            'class' => $studentAcademicYear->class,
            'handling' => $handling,
            'description' => $request->description,
            'total_points' => $totalPoints,
            'date' => Carbon::now()->format('d F Y'),
            'violations' => $studentAcademicYear->recaps->where('status', 'verified')
        ];

        // Generate PDF
        $pdf = Pdf::loadView('pdf.handling-action', $data);

        // Optional: Simpan data ke database jika perlu
        // HandlingRecord::create([...]);

        // Download PDF
        return $pdf->download('Surat-Tindakan-' . $studentAcademicYear->student->full_name . '-' . Carbon::now()->format('YmdHis') . '.pdf');
    }

    public function actions()
    {
        $actions = P_Viol_Action::with([
            'recap.student',
            'handling',
            'handle',
            'detail'
        ])->orderBy('created_at', 'desc')->get();

        return view('bk.actions.index', compact('actions'));
    }
}
