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

class GuruController extends Controller
{
    public function index()
    {
        // Sama dengan BKController::index (sudah dioptimasi)
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

        return view('guru.student-data.index', compact(
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

        $activeAcademicYear = P_Configs::getActiveAcademicYear();

        // PERBAIKAN: Gunakan student_id bukan ID ref_student_academic_years
        $studentAcademicYear = RefStudentAcademicYear::select('id', 'student_id', 'academic_year')
            ->where('student_id', $studentId)
            ->where('academic_year', $activeAcademicYear)
            ->with('student:id,full_name')
            ->first();

        if (!$studentAcademicYear) {
            return back()->withErrors(['error' => 'Siswa tidak terdaftar pada tahun akademik aktif']);
        }

        // Hitung poin dengan query optimized
        $points = DB::table('p_recaps')
            ->where('ref_student_id', $studentAcademicYear->id) // Gunakan ID ref_student_academic_years
            ->join('p_violations', 'p_recaps.p_violation_id', '=', 'p_violations.id')
            ->selectRaw('
                SUM(CASE WHEN status = "verified" THEN p_violations.point ELSE 0 END) as verified_points,
                SUM(CASE WHEN status = "pending" THEN p_violations.point ELSE 0 END) as pending_points
            ')
            ->first();

        $currentVerifiedPoints = $points->verified_points ?? 0;
        $currentPendingPoints = $points->pending_points ?? 0;
        $currentTotalPoints = $currentVerifiedPoints + $currentPendingPoints;

        $newPoints = P_Violations::whereIn('id', $request->violations)->sum('point');
        $totalPointsAfterAdd = $currentTotalPoints + $newPoints;

        if ($currentTotalPoints >= 100) {
            return back()->withErrors(['error' => 'Siswa sudah mencapai batas maksimal 100 poin.']);
        }

        if ($totalPointsAfterAdd > 100) {
            return back()->withErrors(['error' => 'Penambahan akan melebihi batas maksimal 100 poin.']);
        }

        try {
            DB::beginTransaction();

            $recapsData = collect($request->violations)->map(function ($violationId) use ($studentAcademicYear) {
                return [
                    'ref_student_id'  => $studentAcademicYear->id, // ID ref_student_academic_years
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

            $updatedPoints = DB::table('p_recaps')
                ->where('ref_student_id', $studentAcademicYear->id)
                ->join('p_violations', 'p_recaps.p_violation_id', '=', 'p_violations.id')
                ->selectRaw('
                    SUM(CASE WHEN status = "verified" THEN p_violations.point ELSE 0 END) as verified_points,
                    SUM(CASE WHEN status = "pending" THEN p_violations.point ELSE 0 END) as pending_points
                ')
                ->first();

            return back()->with([
                'success' => 'Pelanggaran berhasil ditambahkan',
                'verified_points' => $updatedPoints->verified_points ?? 0,
                'pending_points' => $updatedPoints->pending_points ?? 0,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }

    public function recaps(Request $request)
    {
        $activeAcademicYear = P_Configs::select('id', 'academic_year', 'is_active')
            ->where('is_active', true)
            ->first();

        $handlingOptions = P_Config_Handlings::select('id', 'p_config_id', 'handling_point', 'handling_action')
            ->where('p_config_id', $activeAcademicYear->id)
            ->orderBy('handling_point', 'asc')
            ->get();

        $recaps = RefStudentAcademicYear::select([
            'ref_student_academic_years.id',
            'ref_student_academic_years.student_id',
            'ref_student_academic_years.class_id',
            DB::raw('COALESCE(SUM(CASE WHEN p_recaps.status = "verified" THEN p_violations.point ELSE 0 END), 0) as violations_sum_point')
        ])
            ->activeAcademicYear()
            ->join('ref_students', 'ref_student_academic_years.student_id', '=', 'ref_students.id')
            ->leftJoin('p_recaps', 'ref_students.id', '=', 'p_recaps.ref_student_id')
            ->leftJoin('p_violations', 'p_recaps.p_violation_id', '=', 'p_violations.id')
            ->with([
                'student:id,full_name,student_number',
                'class:id,name,academic_level',
                'recaps' => function ($query) {
                    $query->where('status', 'verified')
                        ->select('id', 'ref_student_id', 'p_violation_id', 'status', 'created_at')
                        ->with('violation:id,name,point,p_category_id')
                        ->with('violation.category:id,name')
                        ->orderByDesc('created_at');
                }
            ])
            ->whereExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('p_recaps')
                    ->whereColumn('p_recaps.ref_student_id', 'ref_students.id')
                    ->whereIn('status', ['pending', 'verified', 'not_verified']);
            })
            ->groupBy('ref_student_academic_years.id', 'ref_student_academic_years.student_id', 'ref_student_academic_years.class_id')
            ->get()
            ->map(function ($studentAcademicYear) use ($handlingOptions) {
                $studentAcademicYear->current_handling = $handlingOptions
                    ->where('handling_point', '<=', $studentAcademicYear->violations_sum_point)
                    ->sortByDesc('handling_point')
                    ->first();

                return $studentAcademicYear;
            });

        return view('guru.dashboard.recaps', compact('recaps', 'activeAcademicYear', 'handlingOptions'));
    }

    public function detailRecaps($studentAcademicYearId)
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
                'student:id,full_name,student_number',
                'class:id,name,academic_level',
                'recaps' => function ($query) {
                    $query->select('id', 'ref_student_id', 'p_violation_id', 'status', 'created_at', 'verified_by', 'created_by', 'updated_by')
                        ->with('violation:id,name,point,p_category_id')
                        ->with('violation.category:id,name')
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

        return view('guru.dashboard.detail', compact(
            'studentAcademicYear',
            'handlingPointOptions',
            'totalVerifiedPoints',
            'applicableHandling'
        ));
    }
}
