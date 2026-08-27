<?php

namespace App\Http\Controllers;

use App\Models\P_Config_Handlings;
use App\Models\P_Configs;
use Illuminate\Http\Request;
use App\Models\RefStudentAcademicYear;
use App\Models\P_Violations;
use App\Models\P_Recaps;
use App\Models\RefClass;
use App\Models\RefClassAcademicYear;
use App\Models\P_Viol_Action;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use App\Models\P_PointReduction;

class GuruController extends Controller
{
    public function index()
    {
        $activeAcademicYear = P_Configs::getActiveAcademicYear();

        if (!$activeAcademicYear) {
            return view('guru.dashboard.index', [
                'totalViolations' => 0,
                'studentsWithoutViolations' => 0,
                'topClass' => null,
                'topStudent' => null,
                'mostFrequentViolation' => null,
                'totalActiveStudents' => 0,
                'pendingViolationsCount' => 0,
                'categoryDistribution' => ['Ringan' => 0, 'Sedang' => 0, 'Berat' => 0],
                'classesToEvaluate' => []
            ]);
        }

        $academicYear = str_replace('-', '/', $activeAcademicYear->academic_year);

        // Get all students in academic year
        $allStudents = RefStudentAcademicYear::where('academic_year', $academicYear)
            ->with([
                'student',
                'class',
                'recaps' => function ($query) {
                    $query->where('status', 'verified')->with('violation.category');
                },
                'pointReductions'
            ])
            ->get();

        $totalViolations = 0;
        $studentsWithViolations = 0;
        $classPoints = [];
        $studentPoints = [];

        foreach ($allStudents as $studentAcademic) {
            $verifiedRecaps = $studentAcademic->recaps;
            $studentTotalPoints = max(0, $verifiedRecaps->sum(fn($r) => $r->violation->point ?? 0) - $studentAcademic->pointReductions->sum('points_reduced'));

            if ($verifiedRecaps->count() > 0) {
                $studentsWithViolations++;
                $totalViolations += $verifiedRecaps->count();
            }

            // For top class
            $className = isset($studentAcademic->class) ? ($studentAcademic->class->academic_level . ' ' . $studentAcademic->class->name) : 'Unknown';
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

        // Classes to Evaluate (Top 5)
        $classesToEvaluate = array_slice($classPoints, 0, 5, true);

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
        // Optimasi: FlatMap recaps siswa yang sudah ter-load di memory dan ter-filter tahun ajaran aktif
        $allRecaps = $allStudents->flatMap(fn($student) => $student->recaps);

        $categoryDistribution = [
            'Ringan' => 0,
            'Sedang' => 0,
            'Berat' => 0,
        ];

        foreach ($allRecaps as $recap) {
            $violationId = $recap->violation->id ?? null;
            $categoryName = $recap->violation->category->name ?? 'Lainnya';
            
            if (isset($categoryDistribution[$categoryName])) {
                $categoryDistribution[$categoryName]++;
            } else {
                $categoryDistribution[$categoryName] = 1;
            }

            if ($violationId) {
                if (!isset($violationCounts[$violationId])) {
                    $violationCounts[$violationId] = [
                        'name' => $recap->violation->name,
                        'point' => $recap->violation->point,
                        'category' => $categoryName,
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

        $totalActiveStudents = $allStudents->count();
        $pendingViolationsCount = P_Recaps::where('status', 'pending')->count();

        return view('guru.dashboard.index', compact(
            'totalViolations',
            'studentsWithoutViolations',
            'topClass',
            'topStudent',
            'mostFrequentViolation',
            'totalActiveStudents',
            'pendingViolationsCount',
            'categoryDistribution',
            'classesToEvaluate'
        ));
    }

    public function studentData(Request $request)
    {
        $activeAcademicYear = P_Configs::where('is_active', true)->first();
        $academicYear = $activeAcademicYear ? str_replace('-', '/', $activeAcademicYear->academic_year) : null;

        $classes = RefClassAcademicYear::with('class')
            ->when($academicYear, function ($q) use ($academicYear) {
                return $q->where('academic_year', $academicYear);
            })
            ->get()
            ->map(function ($cay) {
                if ($cay->class) {
                    $cay->class->academic_year = $cay->academic_year;
                    return $cay->class;
                }
                return null;
            })
            ->filter()
            ->sortBy('academic_level')
            ->values();

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
                ->get()
                ->sortBy(fn($say) => $say->student->full_name ?? '')
                ->values();
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
        $studentAcademicYear = RefStudentAcademicYear::where('id', $studentId)
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

        $totalReductions = P_PointReduction::where('ref_student_id', $studentAcademicYear->student_id)
            ->where('academic_year', $activeAcademicYear)
            ->sum('points_reduced');

        $currentVerifiedPoints = max(0, $existingRecaps->where('status', 'verified')->sum(fn($r) => $r->violation->point ?? 0) - $totalReductions);
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
                'total_all_points' => $verifiedPoints + $pendingPoints,
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

        $allStudents = RefStudentAcademicYear::activeAcademicYear()
            ->with([
                'student',
                'class',
                'recaps' => function ($query) {
                    $query->whereIn('status', ['pending', 'verified', 'not_verified'])
                        ->with(['violation.category'])
                        ->orderByDesc('created_at');
                },
                'pointReductions'
            ])
            ->get()
            ->filter(function ($studentAcademicYear) {
                return $studentAcademicYear->recaps->count() > 0;
            })
            ->map(function ($studentAcademicYear) use ($handlingOptions) {
                $studentAcademicYear->action_detail = P_Viol_Action::where('p_student_academic_year_id', $studentAcademicYear->id)
                    ->with(['detail', 'handling', 'handle'])
                    ->latest()
                    ->first();

                $lastActionDate = $studentAcademicYear->action_detail ? $studentAcademicYear->action_detail->created_at : null;

                $totalVerifiedPoints = max(0, $studentAcademicYear->recaps
                    ->where('status', 'verified')
                    ->sum(fn($r) => $r->violation->point ?? 0) - $studentAcademicYear->pointReductions->sum('points_reduced'));
                $studentAcademicYear->violations_sum_point = $totalVerifiedPoints;

                $hasPending = $studentAcademicYear->recaps->where('status', 'pending')->count() > 0;
                if ($hasPending) {
                    $studentAcademicYear->has_new_violations = true;
                } elseif ($lastActionDate) {
                    $newVerifiedCount = $studentAcademicYear->recaps
                        ->where('status', 'verified')
                        ->filter(function($r) use ($lastActionDate) {
                            return $r->created_at > $lastActionDate;
                        })
                        ->count();
                    $studentAcademicYear->has_new_violations = $newVerifiedCount > 0;
                } else {
                    // Siswa aktif jika belum ada tindakan dan memiliki setidaknya satu pelanggaran pending/verified
                    $hasViolations = $studentAcademicYear->recaps->whereIn('status', ['pending', 'verified'])->count() > 0;
                    $studentAcademicYear->has_new_violations = $hasViolations;
                }

                $studentAcademicYear->current_handling = $handlingOptions
                    ->where('handling_point', '<=', $totalVerifiedPoints)
                    ->sortByDesc('handling_point')
                    ->first();

                return $studentAcademicYear;
            });

        $activeStudents = $allStudents->filter(function ($student) {
            return $student->has_new_violations;
        })->sortBy(fn($student) => $student->student->full_name ?? '')->values();

        $historyStudents = $allStudents->filter(function ($student) {
            return $student->action_detail && !$student->has_new_violations;
        })->sortBy(fn($student) => $student->student->full_name ?? '')->values();

        return view('guru.dashboard.recaps', compact('activeStudents', 'historyStudents', 'activeAcademicYear', 'handlingOptions'));
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
            },
            'pointReductions'
        ])
            ->findOrFail($studentAcademicYearId);

        $pointReductions = P_PointReduction::where('ref_student_id', $studentAcademicYear->student_id)
            ->where('academic_year', $studentAcademicYear->academic_year)
            ->with('creator')
            ->orderByDesc('created_at')
            ->get();

        $totalReductions = $pointReductions->sum('points_reduced');

        $totalVerifiedPoints = max(0, $studentAcademicYear->recaps
            ->where('status', 'verified')
            ->sum(fn($recap) => $recap->violation->point ?? 0) - $totalReductions);

        $applicableHandling = null;
        foreach ($handlingPointOptions as $handling) {
            if ($totalVerifiedPoints >= $handling->handling_point) {
                $applicableHandling = $handling;
            } else {
                break;
            }
        }

        $handlingHistory = P_Viol_Action::where('p_student_academic_year_id', $studentAcademicYear->id)
            ->with(['detail', 'handling', 'handle'])
            ->orderByDesc('created_at')
            ->get();

        return view('guru.dashboard.detail', compact(
            'studentAcademicYear',
            'handlingPointOptions',
            'totalVerifiedPoints',
            'applicableHandling',
            'handlingHistory',
            'pointReductions'
        ));
    }

    public function massCreate()
    {
        $activeConfig = P_Configs::getActiveAcademicYear();
        if (!$activeConfig) {
            return back()->withErrors(['error' => 'Tidak ada konfigurasi tahun akademik yang aktif.']);
        }

        $violations = P_Violations::with('category')->orderBy('point', 'asc')->get();

        $studentAcademicYears = RefStudentAcademicYear::activeAcademicYear()
            ->with(['student', 'class'])
            ->get()
            ->sortBy(fn($say) => $say->student->full_name ?? '')
            ->values();

        return view('guru.violations.mass', compact('violations', 'studentAcademicYears', 'activeConfig'));
    }

    public function massStore(Request $request)
    {
        $request->validate([
            'violation_id' => 'required|exists:p_violations,id',
            'student_ids'  => 'required|array',
            'student_ids.*'=> 'exists:ref_student_academic_years,id',
        ]);

        $activeConfig = P_Configs::getActiveAcademicYear();
        if (!$activeConfig) {
            return back()->withErrors(['error' => 'Tidak ada konfigurasi tahun akademik yang aktif.']);
        }

        $activeAcademicYear = str_replace('-', '/', $activeConfig->academic_year);
        $violation = P_Violations::findOrFail($request->violation_id);

        $savedCount = 0;
        $skippedCount = 0;
        $skippedNames = [];

        try {
            DB::beginTransaction();

            foreach ($request->student_ids as $sayId) {
                $studentAcademicYear = RefStudentAcademicYear::where('id', $sayId)
                    ->where('academic_year', $activeAcademicYear)
                    ->with('student')
                    ->first();

                if (!$studentAcademicYear) {
                    $skippedCount++;
                    continue;
                }

                // Check points limit
                $existingRecaps = P_Recaps::where('ref_student_id', $studentAcademicYear->student_id)
                    ->with('violation')
                    ->get();

                $totalReductions = P_PointReduction::where('ref_student_id', $studentAcademicYear->student_id)
                    ->where('academic_year', $activeAcademicYear)
                    ->sum('points_reduced');

                $currentVerifiedPoints = max(0, $existingRecaps->where('status', 'verified')->sum(fn($r) => $r->violation->point ?? 0) - $totalReductions);
                $currentPendingPoints = $existingRecaps->where('status', 'pending')->sum(fn($r) => $r->violation->point ?? 0);
                $currentTotalPoints = $currentVerifiedPoints + $currentPendingPoints;

                if ($currentTotalPoints >= 100 || ($currentTotalPoints + $violation->point) > 100) {
                    $skippedCount++;
                    $skippedNames[] = $studentAcademicYear->student->full_name . " (Poin melebihi batas)";
                    continue;
                }

                $createdAt = \Carbon\Carbon::now();
                if ($request->date_mode === 'custom' && $request->violation_date) {
                    try {
                        $createdAt = \Carbon\Carbon::parse($request->violation_date)->setTimeFrom(\Carbon\Carbon::now());
                    } catch (\Exception $e) {
                        // Fallback to now
                    }
                }

                // Create recap
                P_Recaps::create([
                    'ref_student_id'  => $studentAcademicYear->student_id,
                    'p_violation_id'  => $violation->id,
                    'status'          => 'pending',
                    'created_by'      => Auth::id(),
                    'updated_by'      => Auth::id(),
                    'created_at'      => $createdAt,
                    'updated_at'      => $createdAt,
                ]);

                $savedCount++;
            }

            DB::commit();

            $msg = "Berhasil menyimpan {$savedCount} laporan pelanggaran secara massal.";
            if ($skippedCount > 0) {
                $msg .= " Dilewati: {$skippedCount} siswa (" . implode(', ', $skippedNames) . ").";
                return back()->with('warning', $msg);
            }

            return back()->with('success', $msg);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Gagal menyimpan laporan massal: ' . $e->getMessage()]);
        }
    }
}
