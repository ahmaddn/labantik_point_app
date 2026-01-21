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
use Carbon\Carbon;
use App\Models\P_Viol_Action;
use App\Models\P_Viol_Action_Detail;
use App\Models\User;

class SuperAdminController extends Controller
{
    public function index()
    {
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

        $academicYear = str_replace('-', '/', $activeAcademicYear->academic_year);

        // Optimasi: Single query dengan aggregasi
        $stats = DB::table('ref_student_academic_years')
            ->where('academic_year', $academicYear)
            ->leftJoin('ref_students', 'ref_student_academic_years.student_id', '=', 'ref_students.id')
            ->leftJoin('p_recaps', function ($join) {
                $join->on('ref_students.id', '=', 'p_recaps.ref_student_id')
                    ->where('p_recaps.status', '=', 'verified');
            })
            ->selectRaw('
                COUNT(DISTINCT ref_student_academic_years.id) as total_students,
                COUNT(DISTINCT CASE WHEN p_recaps.id IS NOT NULL THEN ref_students.id END) as students_with_violations,
                COUNT(p_recaps.id) as total_violations
            ')
            ->first();

        $totalViolations = $stats->total_violations;
        $studentsWithoutViolations = $stats->total_students - $stats->students_with_violations;

        // Optimasi: Top Class dengan single query
        $topClass = DB::table('ref_student_academic_years')
            ->where('ref_student_academic_years.academic_year', $academicYear)
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
            ->orderByDesc('total_points')
            ->first();

        // Optimasi: Top Student dengan single query
        $topStudent = DB::table('ref_student_academic_years')
            ->where('ref_student_academic_years.academic_year', $academicYear)
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
            ->orderByDesc('total_points')
            ->first();

        // Optimasi: Most Frequent Violation dengan single query
        $mostFrequentViolation = DB::table('p_recaps')
            ->where('p_recaps.status', 'verified')
            ->join('p_violations', 'p_recaps.p_violation_id', '=', 'p_violations.id')
            ->join('p_categories', 'p_violations.p_category_id', '=', 'p_categories.id')
            ->select(
                'p_violations.name as violation_name',
                'p_violations.point',
                'p_categories.name as category_name',
                DB::raw('COUNT(p_recaps.id) as violation_count')
            )
            ->groupBy('p_violations.id', 'p_violations.name', 'p_violations.point', 'p_categories.name')
            ->orderByDesc('violation_count')
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
        $activeAcademicYear = P_Configs::select('id', 'academic_year', 'is_active')
            ->where('is_active', true)
            ->first();

        // Optimasi: Hanya load data yang diperlukan
        $classes = RefClass::select('id', 'name', 'academic_level')
            ->orderBy('academic_level', 'asc')
            ->get();

        $vals = P_Violations::select('id', 'p_category_id', 'name', 'point')
            ->with('category:id,name')
            ->orderBy('point', 'asc')
            ->get();

        $studentAcademicYears = collect();
        $selectedClassId = $request->input('class_id');

        if ($selectedClassId) {
            // Optimasi: Gunakan lazy loading dan select spesifik
            $studentAcademicYears = RefStudentAcademicYear::select([
                'id',
                'student_id',
                'class_id',
                'academic_year'
            ])
                ->activeAcademicYear()
                ->where('class_id', $selectedClassId)
                ->with([
                    'student:id,full_name,student_number',
                    'class:id,name,academic_level',
                    'recaps' => function ($query) {
                        $query->select('id', 'ref_student_id', 'p_violation_id', 'status', 'created_at')
                            ->with('violation:id,name,point')
                            ->orderByDesc('created_at');
                    }
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

        $activeConfig = P_Configs::select('id', 'academic_year')->getActiveAcademicYear();

        if (!$activeConfig) {
            return back()->withErrors(['error' => 'Tidak ada konfigurasi tahun akademik yang aktif.']);
        }

        $activeAcademicYear = str_replace('-', '/', $activeConfig->academic_year);

        $studentAcademicYear = RefStudentAcademicYear::select('id', 'student_id', 'academic_year')
            ->where('id', $studentId)
            ->where('academic_year', $activeAcademicYear)
            ->with('student:id,full_name')
            ->first();

        if (!$studentAcademicYear) {
            return back()->withErrors([
                'error' => 'Data siswa tidak ditemukan untuk tahun akademik aktif (' . $activeAcademicYear . ')'
            ]);
        }

        // Optimasi: Single query untuk hitung total poin
        $points = DB::table('p_recaps')
            ->where('ref_student_id', $studentAcademicYear->student_id)
            ->join('p_violations', 'p_recaps.p_violation_id', '=', 'p_violations.id')
            ->selectRaw('
                SUM(CASE WHEN status = "verified" THEN p_violations.point ELSE 0 END) as verified_points,
                SUM(CASE WHEN status = "pending" THEN p_violations.point ELSE 0 END) as pending_points
            ')
            ->first();

        $currentVerifiedPoints = $points->verified_points ?? 0;
        $currentPendingPoints = $points->pending_points ?? 0;
        $currentTotalPoints = $currentVerifiedPoints + $currentPendingPoints;

        // Optimasi: Single query untuk hitung poin baru
        $newPoints = P_Violations::whereIn('id', $request->violations)
            ->sum('point');

        $totalPointsAfterAdd = $currentTotalPoints + $newPoints;

        if ($currentTotalPoints >= 100) {
            return back()->withErrors([
                'error' => 'Siswa sudah mencapai batas maksimal 100 poin. Tidak dapat menambah pelanggaran lagi.'
            ]);
        }

        if ($totalPointsAfterAdd > 100) {
            $excessPoints = $totalPointsAfterAdd - 100;
            return back()->withErrors([
                'error' => 'Penambahan pelanggaran ini akan melebihi batas maksimal 100 poin. Kelebihan: ' . $excessPoints . ' poin.'
            ]);
        }

        try {
            DB::beginTransaction();

            // Optimasi: Bulk insert
            $recapsData = collect($request->violations)->map(function ($violationId) use ($studentAcademicYear) {
                return [
                    'ref_student_id'  => $studentAcademicYear->student_id,
                    'p_violation_id'  => $violationId,
                    'status'          => 'pending',
                    'created_by'      => Auth::id(),
                    'updated_by'      => Auth::id(),
                    'created_at'      => now(),
                    'updated_at'      => now(),
                ];
            })->toArray();

            P_Recaps::insert($recapsData);

            DB::commit();

            // Recalculate dengan query optimized
            $updatedPoints = DB::table('p_recaps')
                ->where('ref_student_id', $studentAcademicYear->student_id)
                ->join('p_violations', 'p_recaps.p_violation_id', '=', 'p_violations.id')
                ->selectRaw('
                    SUM(CASE WHEN status = "verified" THEN p_violations.point ELSE 0 END) as verified_points,
                    SUM(CASE WHEN status = "pending" THEN p_violations.point ELSE 0 END) as pending_points
                ')
                ->first();

            return back()->with([
                'success' => 'Pelanggaran berhasil ditambahkan untuk ' . $studentAcademicYear->student->full_name,
                'verified_points' => $updatedPoints->verified_points ?? 0,
                'pending_points' => $updatedPoints->pending_points ?? 0,
                'total_all_points' => ($updatedPoints->verified_points ?? 0) + ($updatedPoints->pending_points ?? 0),
                'added_points' => $newPoints,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }

    public function confirmRecaps()
    {
        $activeAcademicYear = P_Configs::select('id', 'academic_year', 'is_active')
            ->where('is_active', true)
            ->first();

        $handlingOptions = P_Config_Handlings::select('id', 'p_config_id', 'handling_point', 'handling_action')
            ->where('p_config_id', $activeAcademicYear->id)
            ->orderBy('handling_point', 'asc')
            ->get();

        // Optimasi: Gunakan raw SQL untuk perhitungan agregat
        $studentAcademicYears = RefStudentAcademicYear::select([
            'ref_student_academic_years.id',
            'ref_student_academic_years.student_id',
            'ref_student_academic_years.class_id',
            DB::raw('COALESCE(SUM(CASE WHEN p_recaps.status = "verified" THEN p_violations.point ELSE 0 END), 0) as total_points_verified')
        ])
            ->activeAcademicYear()
            ->join('ref_students', 'ref_student_academic_years.student_id', '=', 'ref_students.id')
            ->leftJoin('p_recaps', 'ref_students.id', '=', 'p_recaps.ref_student_id')
            ->leftJoin('p_violations', 'p_recaps.p_violation_id', '=', 'p_violations.id')
            ->with([
                'student:id,full_name,student_number',
                'class:id,name,academic_level',
                'recaps' => function ($query) {
                    $query->select('id', 'ref_student_id', 'p_violation_id', 'status', 'created_at', 'verified_by', 'created_by', 'updated_by')
                        ->with([
                            'violation:id,name,point,p_category_id',
                            'violation.category:id,name'
                        ])
                        ->orderBy('created_at', 'asc');
                }
            ])
            ->whereExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('p_recaps')
                    ->whereColumn('p_recaps.ref_student_id', 'ref_students.id');
            })
            ->groupBy('ref_student_academic_years.id', 'ref_student_academic_years.student_id', 'ref_student_academic_years.class_id')
            ->having('total_points_verified', '>', 0)
            ->get()
            ->map(function ($student) use ($handlingOptions) {
                $student->available_handlings = $handlingOptions->filter(function ($handling) use ($student) {
                    return $handling->handling_point <= $student->total_points_verified;
                });

                $student->action_detail = P_Viol_Action::select('id', 'p_student_academic_year_id', 'handling_id', 'created_at')
                    ->where('p_student_academic_year_id', $student->id)
                    ->with('detail:id,p_viol_action_id,parent_name,action_date')
                    ->latest()
                    ->first();

                return $student;
            });

        return view('superadmin.confirm-recaps.index', compact('studentAcademicYears', 'handlingOptions', 'activeAcademicYear'));
    }

    public function detailConfirmRecaps($studentAcademicYearId)
    {
        $activeAcademicYear = P_Configs::select('id', 'academic_year', 'is_active')
            ->where('is_active', true)
            ->first();

        $handlingPointOptions = P_Config_Handlings::select('id', 'p_config_id', 'handling_point', 'handling_action')
            ->where('p_config_id', $activeAcademicYear->id)
            ->orderBy('handling_point', 'asc')
            ->get();

        $studentAcademicYear = RefStudentAcademicYear::select('id', 'student_id', 'class_id', 'academic_year')
            ->with([
                'student:id,full_name,student_number,national_identification_number',
                'class:id,name,academic_level',
                'recaps' => function ($query) {
                    $query->select('id', 'ref_student_id', 'p_violation_id', 'status', 'created_at', 'verified_by', 'created_by', 'updated_by')
                        ->with([
                            'violation:id,name,point,p_category_id',
                            'violation.category:id,name'
                        ])
                        ->orderByDesc('created_at');
                }
            ])
            ->findOrFail($studentAcademicYearId);

        $totalVerifiedPoints = $studentAcademicYear->recaps
            ->where('status', 'verified')
            ->sum(fn($recap) => $recap->violation->point ?? 0);

        $applicableHandling = null;
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
            $request->validate([
                'status' => 'required|in:verified,not_verified,pending'
            ]);

            $updated = P_Recaps::where('id', $id)
                ->update([
                    'status' => $request->status,
                    'verified_by' => Auth::id(),
                    'updated_by' => Auth::id(),
                    'updated_at' => now()
                ]);

            if ($updated) {
                return redirect()->back()->with('success', 'Status pelanggaran berhasil diperbarui!');
            }

            return redirect()->back()->with('error', 'Data pelanggaran tidak ditemukan!');
        } catch (\Exception $e) {
            Log::error('updateViolationStatus error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function destroyRecap($id)
    {
        try {
            $deleted = P_Recaps::where('id', $id)->delete();

            if ($deleted) {
                return redirect()->back()->with('success', 'Rekap pelanggaran berhasil dihapus!');
            }

            return redirect()->back()->with('error', 'Data rekap tidak ditemukan!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function storeHandlingAction(Request $request, $id)
    {
        $request->validate([
            'handling_id' => 'required|exists:p_config_handlings,id',
            'student_name' => 'nullable|string|max:191',
            'parent_name' => 'nullable|string|max:191',
            'description' => 'nullable|string',
            'prey' => 'nullable|date',
            'action_date' => 'nullable|date',
            'reference_number' => 'nullable|string|max:191',
            'time' => 'nullable|string|max:50',
            'room' => 'nullable|string|max:100',
            'facing' => 'nullable|string|max:100',
            'violation_count' => 'nullable|integer|min:0|max:10',
            'violations' => 'nullable|array',
            'violations.*' => 'nullable|string|max:500',
        ]);

        $studentAcademicYear = RefStudentAcademicYear::select('id', 'student_id', 'class_id')
            ->with([
                'student:id,full_name,student_number,national_identification_number',
                'class:id,name,academic_level',
                'recaps' => function ($query) {
                    $query->where('status', 'verified')
                        ->with('violation:id,name,point,p_category_id')
                        ->with('violation.category:id,name');
                }
            ])
            ->find($id);

        if (!$studentAcademicYear || !$studentAcademicYear->student) {
            return back()->withErrors(['error' => 'Data siswa tidak ditemukan.']);
        }

        if (empty($request->parent_name)) {
            return back()->withErrors(['error' => 'Mohon isi nama wali.']);
        }

        $handling = P_Config_Handlings::select('id', 'handling_action')->findOrFail($request->handling_id);

        try {
            DB::beginTransaction();

            $action = P_Viol_Action::create([
                'p_student_academic_year_id' => $studentAcademicYear->id,
                'handling_id' => $request->handling_id,
                'handled_by' => Auth::id(),
                'activity' => $handling->handling_action,
                'description' => $request->description,
            ]);

            $violations = array_filter($request->violations ?? [], fn($v) => !empty($v));

            P_Viol_Action_Detail::create([
                'p_viol_action_id' => $action->id,
                'parent_name' => $request->parent_name,
                'student_name' => $request->student_name,
                'prey' => $request->prey,
                'action_date' => $request->action_date,
                'reference_number' => $request->reference_number,
                'time' => $request->time,
                'room' => $request->room,
                'facing' => $request->facing,
                'violation_count' => count($violations),
                'violations' => count($violations) > 0 ? array_values($violations) : null,
            ]);

            DB::commit();

            $totalPoints = $studentAcademicYear->recaps->sum(fn($recap) => $recap->violation->point ?? 0);
            $preyDate = $request->prey ? Carbon::parse($request->prey)->format('d F Y') : Carbon::now()->format('d F Y');
            $actionDateFormatted = $request->action_date ? Carbon::parse($request->action_date)->format('d F Y') : '';
            $kelasString = trim(($studentAcademicYear->class->academic_level ?? '') . ' ' . ($studentAcademicYear->class->name ?? ''));

            $kepalaSekolah = User::select('id', 'name', 'email')
                ->with('employee:id,user_id,full_name')
                ->where('email', 'kepsek@gmail.com')
                ->first();

            $data = [
                'student' => $studentAcademicYear->student,
                'class' => $studentAcademicYear->class,
                'handling' => $handling,
                'description' => $request->description,
                'total_points' => $totalPoints,
                'date' => $preyDate,
                'violations' => $studentAcademicYear->recaps,
                'prey' => $preyDate,
                'reference_number' => $request->reference_number ?? '',
                'student_name' => $request->student_name ?? '',
                'student_nis' => $studentAcademicYear->student->student_number ?? '',
                'student_nisn' => $studentAcademicYear->student->national_identification_number ?? '',
                'parent_name' => $request->parent_name ?? '',
                'action_date' => $actionDateFormatted,
                'time' => $request->time ?? '',
                'room' => $request->room ?? '',
                'facing' => $request->facing ?? '',
                'kelas' => $kelasString,
                'kepala_sekolah' => $kepalaSekolah,
                'violation_list' => array_values($violations),
            ];

            return view('pdf.panggilan', $data);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('storeHandlingAction error: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }

    public function actions()
    {
        $actions = P_Viol_Action::select('id', 'p_student_academic_year_id', 'handling_id', 'handled_by', 'activity', 'description', 'created_at')
            ->with([
                'academicYear:id,student_id,class_id',
                'academicYear.student:id,full_name,student_number',
                'academicYear.class:id,name,academic_level',
                'academicYear.pRecaps' => function ($query) {
                    $query->select('id', 'ref_student_id', 'p_violation_id', 'status')
                        ->where('status', 'verified')
                        ->with('violation:id,name,point');
                },
                'handling:id,handling_action,handling_point',
                'handle:id,name',
                'detail:id,p_viol_action_id,parent_name,action_date'
            ])
            ->orderByDesc('created_at')
            ->paginate(20);

        return view('superadmin.actions.index', compact('actions'));
    }
}
