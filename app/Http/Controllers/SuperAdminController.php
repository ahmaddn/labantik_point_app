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
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use App\Models\P_Viol_Action;
use App\Models\P_Viol_Action_Detail;
use App\Models\User;
use App\Models\P_PointReduction;

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

        return view('superadmin.dashboard.index', compact(
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

        $activeConfig = P_Configs::getActiveAcademicYear();

        if (!$activeConfig) {
            return back()->withErrors(['error' => 'Tidak ada konfigurasi tahun akademik yang aktif.']);
        }

        $activeAcademicYear = str_replace('-', '/', $activeConfig->academic_year);

        $studentAcademicYear = RefStudentAcademicYear::where('id', $studentId)
            ->where('academic_year', $activeAcademicYear)
            ->with('student')
            ->first();

        if (!$studentAcademicYear) {
            return back()->withErrors([
                'error' => 'Data siswa tidak ditemukan untuk tahun akademik aktif (' . $activeAcademicYear . ')'
            ]);
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
                'success' => 'Pelanggaran berhasil ditambahkan untuk ' . $studentAcademicYear->student->full_name,
                'verified_points' => $verifiedPoints,
                'pending_points' => $pendingPoints,
                'total_all_points' => $verifiedPoints + $pendingPoints,
                'added_points' => $newPoints,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }

    public function confirmRecaps()
    {
        $activeAcademicYear = P_Configs::where('is_active', true)->first();

        if (!$activeAcademicYear) {
            $activeAcademicYear = P_Configs::first();
        }

        if ($activeAcademicYear) {
            $handlingOptions = P_Config_Handlings::where('p_config_id', $activeAcademicYear->id)
                ->orderBy('handling_point', 'asc')
                ->get();

            $allStudents = RefStudentAcademicYear::activeAcademicYear()
                ->with([
                    'student',
                    'class',
                    'recaps' => function ($query) {
                        $query->with([
                            'violation.category',
                            'createdBy',
                            'updatedBy',
                            'verifiedBy'
                        ])->orderBy('created_at', 'asc');
                    },
                    'pointReductions'
                ])
                ->get()
                ->filter(function ($student) {
                    return $student->recaps->count() > 0;
                })
                ->map(function ($student) use ($handlingOptions) {
                    $student->action_detail = P_Viol_Action::where('p_student_academic_year_id', $student->id)
                        ->with(['detail', 'handling', 'handle'])
                        ->latest()
                        ->first();

                    $lastActionDate = $student->action_detail ? $student->action_detail->created_at : null;

                    $totalVerifiedPoints = max(0, $student->recaps
                        ->where('status', 'verified')
                        ->sum(fn($r) => $r->violation->point ?? 0) - $student->pointReductions->sum('points_reduced'));
                    $student->total_points_verified = $totalVerifiedPoints;

                    $hasPending = $student->recaps->where('status', 'pending')->count() > 0;
                    if ($hasPending) {
                        $student->has_new_violations = true;
                    } elseif ($lastActionDate) {
                        $newVerifiedCount = $student->recaps
                            ->where('status', 'verified')
                            ->filter(function($r) use ($lastActionDate) {
                                return $r->created_at > $lastActionDate;
                            })
                            ->count();
                        $student->has_new_violations = $newVerifiedCount > 0;
                    } else {
                        // Siswa aktif jika belum ada tindakan dan memiliki setidaknya satu pelanggaran pending/verified
                        $hasViolations = $student->recaps->whereIn('status', ['pending', 'verified'])->count() > 0;
                        $student->has_new_violations = $hasViolations;
                    }

                    $student->available_handlings = $handlingOptions->filter(function ($handling) use ($totalVerifiedPoints) {
                        return $handling->handling_point <= $totalVerifiedPoints;
                    });

                    return $student;
                });

            $activeStudents = $allStudents->filter(function ($student) {
                return $student->has_new_violations;
            })->sortBy(fn($student) => $student->student->full_name ?? '')->values();

            $historyStudents = $allStudents->filter(function ($student) {
                return $student->action_detail && !$student->has_new_violations;
            })->sortBy(fn($student) => $student->student->full_name ?? '')->values();
        } else {
            $handlingOptions = collect();
            $activeStudents = collect();
            $historyStudents = collect();
        }

        $kepalaSekolahList = User::whereHas('roles', function($query) {
                $query->where('code', 'kepala-sekolah');
            })
            ->whereHas('employee', function($query) {
                $query->whereNotNull('nip')
                      ->where('nip', '!=', '')
                      ->where('nip', '!=', '-');
            })
            ->with('employee')
            ->get();

        return view('superadmin.confirm-recaps.index', compact('activeStudents', 'historyStudents', 'handlingOptions', 'activeAcademicYear', 'kepalaSekolahList'));
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

        return view('superadmin.confirm-recaps.detail', compact(
            'studentAcademicYear',
            'handlingPointOptions',
            'totalVerifiedPoints',
            'applicableHandling',
            'handlingHistory',
            'pointReductions'
        ));
    }

    public function approveConfirmRecaps($studentAcademicYearId)
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

        $studentAcademicYear->action_detail = P_Viol_Action::where('p_student_academic_year_id', $studentAcademicYear->id)
            ->with(['handling', 'detail', 'handle'])
            ->first();

        $totalReductions = $studentAcademicYear->pointReductions->sum('points_reduced');

        $totalVerifiedPoints = max(0, $studentAcademicYear->recaps
            ->where('status', 'verified')
            ->sum(fn($recap) => $recap->violation->point ?? 0) - $totalReductions);

        return view('superadmin.confirm-recaps.approve', compact(
            'studentAcademicYear',
            'handlingPointOptions',
            'totalVerifiedPoints'
        ));
    }

    public function updateViolationStatus(Request $request, $id)
    {
        try {
            $request->validate([
                'status' => 'required|in:verified,not_verified,pending'
            ]);

            $recap = P_Recaps::findOrFail($id);
            $recap->update([
                'status' => $request->status,
                'verified_by' => Auth::id(),
                'updated_by' => Auth::id(),
            ]);

            return redirect()->back()->with('success', 'Status pelanggaran berhasil diperbarui!');
        } catch (\Exception $e) {
            Log::error('updateViolationStatus error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function destroyRecap($id)
    {
        try {
            $recap = P_Recaps::findOrFail($id);
            $recap->delete();

            return redirect()->back()->with('success', 'Rekap pelanggaran berhasil dihapus!');
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
            'kepala_sekolah_id' => 'nullable|exists:core_users,id',
        ]);

        $studentAcademicYear = RefStudentAcademicYear::with([
            'student',
            'class',
            'recaps' => function ($query) {
                $query->where('status', 'verified')
                    ->with('violation.category');
            }
        ])
            ->find($id);

        if (!$studentAcademicYear || !$studentAcademicYear->student) {
            return back()->withErrors(['error' => 'Data siswa tidak ditemukan.']);
        }

        $handling = P_Config_Handlings::findOrFail($request->handling_id);
        $isLisan = ($handling->letter_type === 'lisan') || (empty($handling->letter_type) && stripos($handling->handling_action, 'lisan') !== false);

        if (!$isLisan && empty($request->parent_name)) {
            return back()->withErrors(['error' => 'Mohon isi nama wali.']);
        }

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
                'parent_name' => $request->parent_name ?? '-',
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

            if ($isLisan) {
                return redirect()->route('superadmin.confirm-recaps')->with('success', 'Tindakan penanganan Peringatan Lisan berhasil disimpan.');
            }

            $totalReductions = P_PointReduction::where('ref_student_id', $studentAcademicYear->student_id)
                ->where('academic_year', $studentAcademicYear->academic_year)
                ->sum('points_reduced');
            $totalPoints = max(0, $studentAcademicYear->recaps->sum(fn($recap) => $recap->violation->point ?? 0) - $totalReductions);
            $actionDay = '';
            try {
                $preyDate = $request->prey ? Carbon::parse($request->prey)->locale('id')->translatedFormat('j F Y') : Carbon::now()->locale('id')->translatedFormat('j F Y');
            } catch (\Exception $e) {
                $preyDate = $request->prey;
            }

            try {
                $actionDateFormatted = $request->action_date ? Carbon::parse($request->action_date)->locale('id')->translatedFormat('j F Y') : '';
                if ($request->action_date) {
                    $actionDay = Carbon::parse($request->action_date)->locale('id')->translatedFormat('l');
                }
            } catch (\Exception $e) {
                $actionDateFormatted = $request->action_date;
                $actionDay = '';
            }
            $kelasString = trim(($studentAcademicYear->class->academic_level ?? '') . ' ' . ($studentAcademicYear->class->name ?? ''));

            if ($request->filled('kepala_sekolah_id')) {
                $kepalaSekolah = User::where('id', $request->kepala_sekolah_id)
                    ->with('employee')
                    ->first();
            } else {
                $kepalaSekolah = User::whereHas('roles', function($query) {
                        $query->where('code', 'kepala-sekolah');
                    })
                    ->with('employee')
                    ->first();
            }

            if (!$kepalaSekolah) {
                $kepalaSekolah = User::where('email', 'kepsek@gmail.com')
                    ->with('employee')
                    ->first();
            }

            if ($kepalaSekolah && $kepalaSekolah->employee) {
                $kepalaSekolah->name = $kepalaSekolah->employee->full_name ?? $kepalaSekolah->name;
                $kepalaSekolah->nip = 'NIP. ' . ($kepalaSekolah->employee->nip ?? '-');
            }

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
                'student_nisn' => $studentAcademicYear->student->national_student_number ?? '',
                'parent_name' => $request->parent_name ?? '',
                'action_date' => $actionDateFormatted,
                'action_day' => $actionDay,
                'time' => $request->time ?? '',
                'room' => $request->room ?? '',
                'facing' => $request->facing ?? '',
                'kelas' => $kelasString,
                'kepala_sekolah' => $kepalaSekolah,
                'violation_list' => array_values($violations),
            ];

            $actionType = $handling->letter_type;
            if (empty($actionType)) {
                $actionName = strtolower($handling->handling_action ?? '');
                if (str_contains($actionName, 'perjanjian')) $actionType = 'perjanjian';
                elseif (str_contains($actionName, 'pernyataan') || str_contains($actionName, 'diri') || str_contains($actionName, 'mundur')) $actionType = 'pernyataan';
                elseif (str_contains($actionName, 'pengembalian')) $actionType = 'pengembalian';
                elseif (str_contains($actionName, 'lisan')) $actionType = 'lisan';
                else $actionType = 'panggilan';
            }

            if ($actionType === 'perjanjian') {
                return view('pdf.perjanjian', $data);
            } elseif ($actionType === 'pernyataan') {
                return view('pdf.pernyataan', $data);
            } elseif ($actionType === 'pengembalian') {
                return view('pdf.pengembalian', $data);
            } else {
                return view('pdf.panggilan', $data);
            }
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('storeHandlingAction error: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }

    public function actions()
    {
        $activeAcademicYear = P_Configs::getActiveAcademicYear();
        $academicYear = $activeAcademicYear ? str_replace('-', '/', $activeAcademicYear->academic_year) : null;

        $query = P_Viol_Action::with([
            'academicYear.student',
            'academicYear.class',
            'academicYear.pRecaps' => function ($query) {
                $query->where('status', 'verified')
                    ->with('violation');
            },
            'handling',
            'handle',
            'detail'
        ]);

        if ($academicYear) {
            $query->whereHas('academicYear', function($q) use ($academicYear) {
                $q->where('academic_year', $academicYear);
            });
        }

        $actions = $query->orderByDesc('created_at')->get();

        // Group actions by class ID
        $groupedActions = $actions->groupBy(function($act) {
            return $act->academicYear->class->id ?? 0;
        });

        // Get class details with grouped actions
        $classes = [];
        foreach ($groupedActions as $classId => $classActions) {
            if ($classId > 0 && isset($classActions[0]->academicYear->class)) {
                $classObj = $classActions[0]->academicYear->class;
                $classes[] = (object)[
                    'id' => $classId,
                    'name' => $classObj->academic_level . ' ' . $classObj->name,
                    'level' => $classObj->academic_level,
                    'major_id' => $classObj->expertise_concentration_id ?? '',
                    'actions' => $classActions
                ];
            }
        }

        // Sort classes by name
        usort($classes, fn($a, $b) => strcmp($a->name, $b->name));

        $levels = \App\Models\RefClass::select('academic_level')->distinct()->pluck('academic_level')->sort()->toArray();
        $majors = \App\Models\CoreExpertiseConcentration::select('id', 'name')->get();

        return view('superadmin.actions.index', compact('classes', 'actions', 'levels', 'majors'));
    }

    public function classActions($classId)
    {
        $activeAcademicYear = P_Configs::getActiveAcademicYear();
        $academicYear = $activeAcademicYear ? str_replace('-', '/', $activeAcademicYear->academic_year) : null;

        $class = RefClass::findOrFail($classId);

        $students = RefStudentAcademicYear::where('class_id', $classId)
            ->where('academic_year', $academicYear)
            ->whereHas('actions')
            ->with([
                'student',
                'actions.handling',
                'recaps' => function($q) {
                    $q->where('status', 'verified')->with('violation');
                },
                'pointReductions'
            ])
            ->get()
            ->map(function($student) {
                $student->total_points_verified = max(0, $student->recaps->sum(fn($r) => $r->violation->point ?? 0) - $student->pointReductions->sum('points_reduced'));
                return $student;
            })
            ->sortBy(fn($student) => $student->student->full_name ?? '')
            ->values();

        return view('superadmin.actions.class', compact('class', 'students'));
    }

    public function resetPoints(Request $request, $id)
    {
        $request->validate([
            'points_reduced' => 'required|integer|min:1',
            'reason' => 'nullable|string|max:255',
        ]);

        try {
            DB::beginTransaction();

            $studentAcademicYear = RefStudentAcademicYear::findOrFail($id);

            P_PointReduction::create([
                'ref_student_id' => $studentAcademicYear->student_id,
                'academic_year' => $studentAcademicYear->academic_year,
                'points_reduced' => $request->points_reduced,
                'reason' => $request->reason,
                'created_by' => Auth::id(),
            ]);

            DB::commit();

            return redirect()->back()->with('success', 'Pemotongan poin sebesar ' . $request->points_reduced . ' poin berhasil dicatat.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('resetPoints error: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Terjadi kesalahan saat mereset poin: ' . $e->getMessage()]);
        }
    }

    public function resetStudentData($id)
    {
        try {
            DB::beginTransaction();

            $studentAcademicYear = RefStudentAcademicYear::findOrFail($id);
            $studentId = $studentAcademicYear->student_id;

            // Delete recaps
            P_Recaps::where('ref_student_id', $studentId)->delete();

            // Find actions to delete details first
            $actionIds = P_Viol_Action::where('p_student_academic_year_id', $studentAcademicYear->id)->pluck('id');
            P_Viol_Action_Detail::whereIn('p_viol_action_id', $actionIds)->delete();
            P_Viol_Action::where('p_student_academic_year_id', $studentAcademicYear->id)->delete();

            // Delete point reductions
            P_PointReduction::where('ref_student_id', $studentId)->delete();

            DB::commit();

            return redirect()->route('superadmin.confirm-recaps')->with('success', 'Seluruh data simulasi siswa ini berhasil dikosongkan.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('resetStudentData error: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Terjadi kesalahan saat menghapus data: ' . $e->getMessage()]);
        }
    }

    public function resetAllTestingData()
    {
        try {
            DB::beginTransaction();

            // Delete all recaps, actions, details, reductions
            DB::table('p_recaps')->delete();
            DB::table('p_viol_action_details')->delete();
            DB::table('p_viol_actions')->delete();
            DB::table('p_point_reductions')->delete();

            DB::commit();

            return redirect()->route('superadmin.confirm-recaps')->with('success', 'Seluruh data uji coba sistem berhasil dikosongkan.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('resetAllTestingData error: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Terjadi kesalahan saat mengosongkan data uji coba: ' . $e->getMessage()]);
        }
    }

    public function destroyAction($id)
    {
        try {
            DB::beginTransaction();
            $action = P_Viol_Action::findOrFail($id);
            if ($action->detail) {
                $action->detail->delete();
            }
            $action->delete();
            DB::commit();
             return redirect()->back()->with('success', 'Tindakan penanganan berhasil dibatalkan.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('destroyAction error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    public function printAction($id)
    {
        $action = P_Viol_Action::with(['detail', 'handling', 'academicYear.student', 'academicYear.class', 'academicYear.recaps.violation'])->findOrFail($id);
        $studentAcademicYear = $action->academicYear;
        $detail = $action->detail;

        $totalReductions = P_PointReduction::where('ref_student_id', $studentAcademicYear->student_id)
            ->where('academic_year', $studentAcademicYear->academic_year)
            ->sum('points_reduced');
        $totalPoints = max(0, $studentAcademicYear->recaps->sum(fn($recap) => $recap->violation->point ?? 0) - $totalReductions);
        $actionDay = '';
        try {
            $preyDate = ($detail && $detail->prey) ? Carbon::parse($detail->prey)->locale('id')->translatedFormat('j F Y') : Carbon::now()->locale('id')->translatedFormat('j F Y');
        } catch (\Exception $e) {
            $preyDate = $detail ? $detail->prey : '';
        }

        try {
            $actionDateFormatted = ($detail && $detail->action_date) ? Carbon::parse($detail->action_date)->locale('id')->translatedFormat('j F Y') : '';
            if ($detail && $detail->action_date) {
                $actionDay = Carbon::parse($detail->action_date)->locale('id')->translatedFormat('l');
            }
        } catch (\Exception $e) {
            $actionDateFormatted = $detail ? $detail->action_date : '';
            $actionDay = '';
        }
        $kelasString = trim(($studentAcademicYear->class->academic_level ?? '') . ' ' . ($studentAcademicYear->class->name ?? ''));

        $kepalaSekolah = null;
        if ($detail && $detail->kepala_sekolah_id) {
            $kepalaSekolah = User::where('id', $detail->kepala_sekolah_id)
                ->with('employee')
                ->first();
        }
        if (!$kepalaSekolah) {
            $kepalaSekolah = User::whereHas('roles', function ($query) {
                $query->where('code', 'kepala-sekolah');
            })->with('employee')->first();
        }
        if (!$kepalaSekolah) {
            $kepalaSekolah = User::where('email', 'kepsek@gmail.com')
                ->with('employee')
                ->first();
        }

        if ($kepalaSekolah) {
            if ($kepalaSekolah->employee) {
                $kepalaSekolah->name = $kepalaSekolah->employee->full_name ?? $kepalaSekolah->name;
                $kepalaSekolah->nip = 'NIP. ' . ($kepalaSekolah->employee->nip ?? '-');
            } else {
                $kepalaSekolah->nip = 'NIP. -';
            }
        } else {
            $kepalaSekolah = (object)[
                'name' => '-',
                'nip' => 'NIP. -'
            ];
        }

        $violations = $detail ? ($detail->violations ?? []) : [];

        $data = [
            'student' => $studentAcademicYear->student,
            'class' => $studentAcademicYear->class,
            'handling' => $action->handling,
            'description' => $action->description,
            'total_points' => $totalPoints,
            'date' => $preyDate,
            'violations' => $studentAcademicYear->recaps,
            'prey' => $preyDate,
            'reference_number' => $detail->reference_number ?? '',
            'student_name' => $detail->student_name ?? ($studentAcademicYear->student->full_name ?? ''),
            'student_nis' => $studentAcademicYear->student->student_number ?? '',
            'student_nisn' => $studentAcademicYear->student->national_student_number ?? '',
            'parent_name' => $detail->parent_name ?? '',
            'action_date' => $actionDateFormatted,
            'action_day' => $actionDay,
            'time' => $detail->time ?? '',
            'room' => $detail->room ?? '',
            'facing' => $detail->facing ?? '',
            'kelas' => $kelasString,
            'kepala_sekolah' => $kepalaSekolah,
            'violation_list' => array_values($violations),
        ];

        $actionType = $action->handling ? $action->handling->letter_type : null;
        if (empty($actionType)) {
            $actionName = strtolower($action->handling ? ($action->handling->handling_action ?? '') : '');
            if (str_contains($actionName, 'perjanjian')) $actionType = 'perjanjian';
            elseif (str_contains($actionName, 'pernyataan') || str_contains($actionName, 'diri') || str_contains($actionName, 'mundur')) $actionType = 'pernyataan';
            elseif (str_contains($actionName, 'pengembalian')) $actionType = 'pengembalian';
            elseif (str_contains($actionName, 'lisan')) $actionType = 'lisan';
            else $actionType = 'panggilan';
        }

        if ($actionType === 'perjanjian') {
            return view('pdf.perjanjian', $data);
        } elseif ($actionType === 'pernyataan') {
            return view('pdf.pernyataan', $data);
        } elseif ($actionType === 'pengembalian') {
            return view('pdf.pengembalian', $data);
        } else {
            return view('pdf.panggilan', $data);
        }
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

        return view('superadmin.violations.mass', compact('violations', 'studentAcademicYears', 'activeConfig'));
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
