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

        // Gunakan query yang sama dengan SuperAdminController (sudah dioptimasi)
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
        // Sama dengan SuperAdminController::store (sudah dioptimasi)
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
                'error' => 'Data siswa tidak ditemukan untuk tahun akademik aktif'
            ]);
        }

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

            $updatedPoints = DB::table('p_recaps')
                ->where('ref_student_id', $studentAcademicYear->student_id)
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

    public function updateViolationStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,verified,not_verified'
        ]);

        try {
            $updated = P_Recaps::where('id', $id)
                ->update([
                    'status' => $request->status,
                    'verified_by' => Auth::id(),
                    'updated_by' => Auth::id(),
                    'updated_at' => now()
                ]);

            if ($updated) {
                return redirect()->back()->with('success', 'Status berhasil diperbarui!');
            }

            return redirect()->back()->with('error', 'Data tidak ditemukan!');
        } catch (\Exception $e) {
            Log::error('updateViolationStatus error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error: ' . $e->getMessage());
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

        // Optimasi dengan agregasi di database
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

        return view('BK.dashboard.recaps', compact('recaps', 'activeAcademicYear', 'handlingOptions'));
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

        $studentAcademicYear = RefStudentAcademicYear::select('id', 'student_id', 'class_id')
            ->with([
                'student:id,full_name,student_number',
                'class:id,name,academic_level',
                'recaps' => function ($query) {
                    $query->where('status', 'verified')
                        ->with('violation:id,name,point,p_category_id')
                        ->with('violation.category:id,name');
                }
            ])
            ->findOrFail($id);

        $handling = P_Config_Handlings::select('id', 'handling_action')->findOrFail($request->handling_id);

        $totalPoints = $studentAcademicYear->recaps->sum(fn($recap) => $recap->violation->point ?? 0);

        $data = [
            'student' => $studentAcademicYear->student,
            'class' => $studentAcademicYear->class,
            'handling' => $handling,
            'description' => $request->description,
            'total_points' => $totalPoints,
            'date' => Carbon::now()->format('d F Y'),
            'violations' => $studentAcademicYear->recaps
        ];

        $pdf = Pdf::loadView('pdf.handling-action', $data);

        return $pdf->download('Surat-Tindakan-' . $studentAcademicYear->student->full_name . '-' . Carbon::now()->format('YmdHis') . '.pdf');
    }

    public function actions()
    {
        $actions = P_Viol_Action::select('id', 'p_student_academic_year_id', 'handling_id', 'handled_by', 'created_at')
            ->with([
                'academicYear:id,student_id,class_id',
                'academicYear.student:id,full_name,student_number',
                'academicYear.class:id,name,academic_level',
                'handling:id,handling_action,handling_point',
                'handle:id,name',
                'detail:id,p_viol_action_id,parent_name,action_date'
            ])
            ->orderByDesc('created_at')
            ->paginate(20);

        return view('bk.actions.index', compact('actions'));
    }
}
