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

        // Get all students in academic year
        $allStudents = RefStudentAcademicYear::where('academic_year', $academicYear)
            ->with(['student.recaps' => function ($query) {
                $query->where('status', 'verified')->with('violation');
            }, 'class'])
            ->get();

        $totalViolations = 0;
        $studentsWithViolations = 0;
        $classPoints = [];
        $studentPoints = [];

        foreach ($allStudents as $studentAcademic) {
            $verifiedRecaps = $studentAcademic->student->recaps;
            $studentTotalPoints = $verifiedRecaps->sum(fn($r) => $r->violation->point ?? 0);

            if ($verifiedRecaps->count() > 0) {
                $studentsWithViolations++;
                $totalViolations += $verifiedRecaps->count();
            }

            // For top class
            $className = $studentAcademic->class->name ?? 'Unknown';
            if (!isset($classPoints[$className])) {
                $classPoints[$className] = 0;
            }
            $classPoints[$className] += $studentTotalPoints;

            // For top student
            $studentPoints[] = [
                'name' => $studentAcademic->student->full_name ?? '',
                'nis' => $studentAcademic->student->student_number ?? '',
                'class' => $className,
                'points' => $studentTotalPoints
            ];
        }

        $studentsWithoutViolations = $allStudents->count() - $studentsWithViolations;

        // Top Class
        arsort($classPoints);
        $topClass = null;
        if (count($classPoints) > 0) {
            $topClassName = array_key_first($classPoints);
            $topClass = (object)[
                'class_name' => $topClassName,
                'total_points' => $classPoints[$topClassName]
            ];
        }

        // Top Student
        usort($studentPoints, fn($a, $b) => $b['points'] <=> $a['points']);
        $topStudent = null;
        if (count($studentPoints) > 0 && $studentPoints[0]['points'] > 0) {
            $topStudent = (object)[
                'student_name' => $studentPoints[0]['name'],
                'nis' => $studentPoints[0]['nis'],
                'class_name' => $studentPoints[0]['class'],
                'total_points' => $studentPoints[0]['points']
            ];
        }

        // Most Frequent Violation
        $violationCounts = [];
        $allRecaps = P_Recaps::where('status', 'verified')
            ->with(['violation.category'])
            ->get();

        foreach ($allRecaps as $recap) {
            $violationId = $recap->violation->id ?? null;
            if ($violationId) {
                if (!isset($violationCounts[$violationId])) {
                    $violationCounts[$violationId] = [
                        'name' => $recap->violation->name,
                        'point' => $recap->violation->point,
                        'category' => $recap->violation->category->name ?? '',
                        'count' => 0
                    ];
                }
                $violationCounts[$violationId]['count']++;
            }
        }

        $mostFrequentViolation = null;
        if (count($violationCounts) > 0) {
            uasort($violationCounts, fn($a, $b) => $b['count'] <=> $a['count']);
            $topViolation = reset($violationCounts);
            $mostFrequentViolation = (object)[
                'violation_name' => $topViolation['name'],
                'point' => $topViolation['point'],
                'category_name' => $topViolation['category'],
                'violation_count' => $topViolation['count']
            ];
        }

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
        $activeAcademicYear = P_Configs::where('is_active', true)->first();

        $classes = RefClass::orderBy('academic_level', 'asc')->get();

        $vals = P_Violations::with('category')->orderBy('point', 'asc')->get();

        $studentAcademicYears = collect();
        $selectedClassId = $request->input('class_id');

        if ($selectedClassId) {
            $studentAcademicYears = RefStudentAcademicYear::activeAcademicYear()
                ->where('class_id', $selectedClassId)
                ->with([
                    'student',
                    'class',
                    'recaps' => function ($query) {
                        $query->with('violation')->orderByDesc('created_at');
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

        $activeConfig = P_Configs::getActiveAcademicYear();

        if (!$activeConfig) {
            return back()->withErrors(['error' => 'Tidak ada konfigurasi tahun akademik yang aktif.']);
        }

        $activeAcademicYear = str_replace('-', '/', $activeConfig->academic_year);

        // PERBAIKAN: studentId adalah ID dari ref_student (bukan ref_student_academic_years)
        $studentAcademicYear = RefStudentAcademicYear::where('student_id', $studentId)
            ->where('academic_year', $activeAcademicYear)
            ->with('student')
            ->first();

        if (!$studentAcademicYear) {
            return back()->withErrors(['error' => 'Siswa tidak terdaftar pada tahun akademik aktif']);
        }

        // Hitung poin dari recaps yang ada
        $existingRecaps = P_Recaps::where('ref_student_id', $studentAcademicYear->student_id)
            ->with('violation')
            ->get();

        $currentVerifiedPoints = $existingRecaps->where('status', 'verified')->sum(fn($r) => $r->violation->point ?? 0);
        $currentPendingPoints = $existingRecaps->where('status', 'pending')->sum(fn($r) => $r->violation->point ?? 0);
        $currentTotalPoints = $currentVerifiedPoints + $currentPendingPoints;

        // Hitung poin baru
        $newViolations = P_Violations::whereIn('id', $request->violations)->get();
        $newPoints = $newViolations->sum('point');

        $totalPointsAfterAdd = $currentTotalPoints + $newPoints;

        if ($currentTotalPoints >= 100) {
            return back()->withErrors(['error' => 'Siswa sudah mencapai batas maksimal 100 poin.']);
        }

        if ($totalPointsAfterAdd > 100) {
            return back()->withErrors(['error' => 'Penambahan akan melebihi batas maksimal 100 poin.']);
        }

        try {
            DB::beginTransaction();

            foreach ($request->violations as $violationId) {
                P_Recaps::create([
                    'ref_student_id'  => $studentAcademicYear->student_id,
                    'p_violation_id'  => $violationId,
                    'status'          => 'pending',
                    'created_by'      => Auth::id(),
                    'updated_by'      => Auth::id(),
                ]);
            }

            DB::commit();

            // Recalculate points
            $updatedRecaps = P_Recaps::where('ref_student_id', $studentAcademicYear->student_id)
                ->with('violation')
                ->get();

            $verifiedPoints = $updatedRecaps->where('status', 'verified')->sum(fn($r) => $r->violation->point ?? 0);
            $pendingPoints = $updatedRecaps->where('status', 'pending')->sum(fn($r) => $r->violation->point ?? 0);

            return back()->with([
                'success' => 'Pelanggaran berhasil ditambahkan',
                'verified_points' => $verifiedPoints,
                'pending_points' => $pendingPoints,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }

    public function recaps(Request $request)
    {
        $activeAcademicYear = P_Configs::where('is_active', true)->first();

        $handlingOptions = P_Config_Handlings::where('p_config_id', $activeAcademicYear->id)
            ->orderBy('handling_point', 'asc')
            ->get();

        $recaps = RefStudentAcademicYear::activeAcademicYear()
            ->with([
                'student',
                'class',
                'recaps' => function ($query) {
                    $query->whereIn('status', ['pending', 'verified', 'not_verified'])
                        ->with(['violation.category'])
                        ->orderByDesc('created_at');
                }
            ])
            ->get()
            ->filter(function ($studentAcademicYear) {
                return $studentAcademicYear->recaps->count() > 0;
            })
            ->map(function ($studentAcademicYear) use ($handlingOptions) {
                $verifiedPoints = $studentAcademicYear->recaps
                    ->whereIn('status', ['pending', 'verified'])
                    ->sum(fn($r) => $r->violation->point ?? 0);

                $studentAcademicYear->violations_sum_point = $verifiedPoints;

                $studentAcademicYear->current_handling = $handlingOptions
                    ->where('handling_point', '<=', $verifiedPoints)
                    ->sortByDesc('handling_point')
                    ->first();

                return $studentAcademicYear;
            });

        return view('guru.dashboard.recaps', compact('recaps', 'activeAcademicYear', 'handlingOptions'));
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
                    'createdBy',
                    'updatedBy',
                    'verifiedBy'
                ])->orderByDesc('created_at');
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
