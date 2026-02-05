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

        // Get all students in academic year
        $allStudents = RefStudentAcademicYear::where('academic_year', $academicYear)
            ->with(['student.recaps' => function ($query) {
                $query->where('status', 'verified')->with('violation');
            }])
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

        $handlingOptions = P_Config_Handlings::where('p_config_id', $activeAcademicYear->id)
            ->orderBy('handling_point', 'asc')
            ->get();

        $studentAcademicYears = RefStudentAcademicYear::activeAcademicYear()
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
                }
            ])
            ->get()
            ->filter(function ($student) {
                return $student->recaps->count() > 0;
            })
            ->map(function ($student) use ($handlingOptions) {
                $totalVerifiedPoints = $student->recaps
                    ->whereIn('status', ['pending', 'verified'])
                    ->sum(fn($r) => $r->violation->point ?? 0);

                $student->total_points_verified = $totalVerifiedPoints;

                $student->available_handlings = $handlingOptions->filter(function ($handling) use ($totalVerifiedPoints) {
                    return $handling->handling_point <= $totalVerifiedPoints;
                });

                $student->action_detail = P_Viol_Action::where('p_student_academic_year_id', $student->id)
                    ->with('detail')
                    ->latest()
                    ->first();

                return $student;
            })
            ->filter(function ($student) {
                return $student->total_points_verified > 0;
            });

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

        if (empty($request->parent_name)) {
            return back()->withErrors(['error' => 'Mohon isi nama wali.']);
        }

        $handling = P_Config_Handlings::findOrFail($request->handling_id);

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

            $kepalaSekolah = User::where('email', 'kepsek@gmail.com')
                ->with('employee')
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
        $actions = P_Viol_Action::with([
            'academicYear.student',
            'academicYear.class',
            'academicYear.pRecaps' => function ($query) {
                $query->where('status', 'verified')
                    ->with('violation');
            },
            'handling',
            'handle',
            'detail'
        ])
            ->orderByDesc('created_at')
            ->paginate(20);

        return view('superadmin.actions.index', compact('actions'));
    }
}
