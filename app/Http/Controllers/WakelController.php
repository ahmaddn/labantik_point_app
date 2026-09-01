<?php

namespace App\Http\Controllers;

use App\Models\P_Config_Handlings;
use App\Models\P_Configs;
use App\Models\P_Recaps;
use App\Models\P_Violations;
use App\Models\P_Viol_Action;
use App\Models\P_Viol_Action_Detail;
use App\Models\RefStudentAcademicYear;
use App\Models\RefClass;
use App\Models\P_PointReduction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class WakelController extends Controller
{
    private function getClassId()
    {
        $classId = Auth::user()->class_id;
        if (!$classId) {
            abort(403, 'Anda tidak terdaftar sebagai wali kelas untuk kelas manapun.');
        }
        return $classId;
    }

    public function index()
    {
        $classId = $this->getClassId();
        $class = RefClass::find($classId);

        $activeAcademicYear = P_Configs::getActiveAcademicYear();

        if (!$activeAcademicYear) {
            return view('wakel.dashboard.index', [
                'class' => $class,
                'totalViolations' => 0,
                'studentsWithoutViolations' => 0,
                'topStudent' => null,
                'mostFrequentViolation' => null,
                'totalActiveStudents' => 0,
                'pendingViolationsCount' => 0,
                'categoryDistribution' => ['Ringan' => 0, 'Sedang' => 0, 'Berat' => 0],
            ]);
        }

        $academicYear = str_replace('-', '/', $activeAcademicYear->academic_year);

        // Ambil semua siswa di kelas wali kelas pada tahun akademik aktif
        $allStudents = RefStudentAcademicYear::where('academic_year', $academicYear)
            ->where('class_id', $classId)
            ->with([
                'student',
                'recaps' => function ($query) {
                    $query->where('status', 'verified')->with('violation.category');
                },
                'pointReductions'
            ])
            ->get();

        $totalViolations = 0;
        $studentsWithViolations = 0;
        $studentPoints = [];

        foreach ($allStudents as $studentAcademic) {
            $verifiedRecaps = $studentAcademic->recaps;
            $studentTotalPoints = max(0, $verifiedRecaps->sum(fn($r) => $r->violation->point ?? 0) - $studentAcademic->pointReductions->sum('points_reduced'));

            if ($verifiedRecaps->count() > 0) {
                $studentsWithViolations++;
                $totalViolations += $verifiedRecaps->count();
            }

            $studentPoints[] = [
                'name' => $studentAcademic->student->full_name ?? '',
                'nis' => $studentAcademic->student->student_number ?? '',
                'points' => $studentTotalPoints
            ];
        }

        $studentsWithoutViolations = $allStudents->count() - $studentsWithViolations;

        // Murid dengan poin terbanyak
        usort($studentPoints, fn($a, $b) => $b['points'] <=> $a['points']);
        $topStudent = null;
        if (count($studentPoints) > 0 && $studentPoints[0]['points'] > 0) {
            $topStudent = (object)[
                'student_name' => $studentPoints[0]['name'],
                'nis' => $studentPoints[0]['nis'],
                'total_points' => $studentPoints[0]['points']
            ];
        }

        // Distribusi Kategori Pelanggaran di kelas ini
        $allStudentIds = $allStudents->pluck('student_id');
        // Optimasi: Gunakan flatMap dari collection in-memory
        $allRecaps = $allStudents->flatMap(fn($student) => $student->recaps);

        $categoryDistribution = [
            'Ringan' => 0,
            'Sedang' => 0,
            'Berat' => 0,
        ];

        $violationCounts = [];
        foreach ($allRecaps as $recap) {
            $categoryName = $recap->violation->category->name ?? 'Lainnya';
            if (isset($categoryDistribution[$categoryName])) {
                $categoryDistribution[$categoryName]++;
            }

            $violationId = $recap->violation->id ?? null;
            if ($violationId) {
                if (!isset($violationCounts[$violationId])) {
                    $violationCounts[$violationId] = [
                        'name' => $recap->violation->name,
                        'point' => $recap->violation->point,
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
                'violation_count' => $topViolation['count']
            ];
        }

        $totalActiveStudents = $allStudents->count();
        $pendingViolationsCount = P_Recaps::whereIn('ref_student_id', $allStudentIds)
            ->where('status', 'pending')
            ->count();

        return view('wakel.dashboard.index', compact(
            'class',
            'totalViolations',
            'studentsWithoutViolations',
            'topStudent',
            'mostFrequentViolation',
            'totalActiveStudents',
            'pendingViolationsCount',
            'categoryDistribution'
        ));
    }

    public function studentData()
    {
        $classId = $this->getClassId();
        $class = RefClass::find($classId);

        $activeAcademicYear = P_Configs::where('is_active', true)->first();
        $academicYear = $activeAcademicYear ? str_replace('-', '/', $activeAcademicYear->academic_year) : null;

        $vals = P_Violations::with('category')->orderBy('point', 'asc')->get();

        $studentAcademicYears = RefStudentAcademicYear::activeAcademicYear()
            ->where('class_id', $classId)
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

        return view('wakel.student-data.index', compact(
            'studentAcademicYears',
            'vals',
            'activeAcademicYear',
            'class'
        ));
    }

    public function store(Request $request, $studentId)
    {
        $classId = $this->getClassId();
        $request->validate([
            'violations'   => 'required|array',
            'violations.*' => 'exists:p_violations,id',
        ]);

        $activeConfig = P_Configs::getActiveAcademicYear();

        if (!$activeConfig) {
            return back()->withErrors(['error' => 'Tidak ada konfigurasi tahun akademik yang aktif.']);
        }

        $activeAcademicYear = str_replace('-', '/', $activeConfig->academic_year);

        // Pastikan siswa ini memang terdaftar di kelas milik wali kelas ini
        $studentAcademicYear = RefStudentAcademicYear::where('id', $studentId)
            ->where('academic_year', $activeAcademicYear)
            ->where('class_id', $classId)
            ->first();

        if (!$studentAcademicYear) {
            return back()->withErrors(['error' => 'Siswa tidak ditemukan atau bukan merupakan bagian dari kelas Anda.']);
        }

        $existingRecaps = P_Recaps::where('ref_student_id', $studentAcademicYear->student_id)
            ->with('violation')
            ->get();

        $totalReductions = P_PointReduction::where('ref_student_id', $studentAcademicYear->student_id)
            ->where('academic_year', $activeAcademicYear)
            ->sum('points_reduced');

        $currentVerifiedPoints = max(0, $existingRecaps->where('status', 'verified')->sum(fn($r) => $r->violation->point ?? 0) - $totalReductions);
        $currentPendingPoints = $existingRecaps->where('status', 'pending')->sum(fn($r) => $r->violation->point ?? 0);
        $currentTotalPoints = $currentVerifiedPoints + $currentPendingPoints;

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

            return back()->with('success', 'Pelanggaran berhasil ditambahkan dan menunggu verifikasi.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }

    public function recaps()
    {
        $classId = $this->getClassId();
        $class = RefClass::find($classId);

        $activeAcademicYear = P_Configs::where('is_active', true)->first();
        if (!$activeAcademicYear) {
            abort(404, 'Konfigurasi tahun akademik aktif tidak ditemukan.');
        }

        $handlingOptions = P_Config_Handlings::where('p_config_id', $activeAcademicYear->id)
            ->orderBy('handling_point', 'asc')
            ->get();

        $allStudents = RefStudentAcademicYear::activeAcademicYear()
            ->where('class_id', $classId)
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

        return view('wakel.dashboard.recaps', compact('activeStudents', 'historyStudents', 'activeAcademicYear', 'handlingOptions', 'class'));
    }

    public function detailRecaps($studentAcademicYearId)
    {
        $classId = $this->getClassId();
        $activeAcademicYear = P_Configs::where('is_active', true)->first();

        $handlingPointOptions = P_Config_Handlings::where('p_config_id', $activeAcademicYear->id)
            ->orderBy('handling_point', 'asc')
            ->get();

        // Ambil data siswa pembinaan dengan verifikasi kelas
        $studentAcademicYear = RefStudentAcademicYear::where('class_id', $classId)
            ->with([
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

        // Ambil tindakan penanganan yang sudah dilakukan
        $studentAcademicYear->action_detail = P_Viol_Action::where('p_student_academic_year_id', $studentAcademicYear->id)
            ->with(['handling', 'detail', 'handle'])
            ->first();

        $handlingHistory = P_Viol_Action::where('p_student_academic_year_id', $studentAcademicYear->id)
            ->with(['detail', 'handling', 'handle'])
            ->orderByDesc('created_at')
            ->get();

        return view('wakel.dashboard.detail', compact(
            'studentAcademicYear',
            'handlingPointOptions',
            'totalVerifiedPoints',
            'applicableHandling',
            'handlingHistory',
            'pointReductions'
        ));
    }

    public function storeHandlingAction(Request $request, $studentAcademicYearId)
    {
        $classId = $this->getClassId();
        
        $request->validate([
            'handling_id' => 'required|exists:p_config_handlings,id',
            'activity' => 'required|string|max:255',
            'description' => 'required|string',
            'violation_details' => 'nullable|array',
            'violation_details.*' => 'string|max:255',
        ]);

        // Verifikasi bahwa siswa merupakan bagian dari kelas wali kelas
        $studentAcademicYear = RefStudentAcademicYear::where('class_id', $classId)
            ->findOrFail($studentAcademicYearId);

        try {
            DB::beginTransaction();

            $action = P_Viol_Action::create([
                'p_student_academic_year_id' => $studentAcademicYear->id,
                'handling_id' => $request->handling_id,
                'handled_by' => Auth::id(),
                'activity' => $request->activity,
                'description' => $request->description,
            ]);

            P_Viol_Action_Detail::create([
                'p_viol_action_id' => $action->id,
                'violations' => $request->violation_details ? implode(', ', $request->violation_details) : '',
            ]);

            DB::commit();

            return redirect()->route('wakel.recaps')->with('success', 'Tindakan penanganan berhasil disimpan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Gagal menyimpan penanganan: ' . $e->getMessage()]);
        }
    }

    public function actions()
    {
        $classId = $this->getClassId();
        $class = RefClass::find($classId);

        // Dapatkan semua ID murid kelas bimbingan wali kelas ini
        $studentAcademicYearIds = RefStudentAcademicYear::where('class_id', $classId)->pluck('id');

        $actions = P_Viol_Action::whereIn('p_student_academic_year_id', $studentAcademicYearIds)
            ->with([
                'academicYear.student',
                'academicYear.class',
                'handling',
                'handle',
                'detail'
            ])
            ->orderByDesc('created_at')
            ->paginate(20);

        return view('wakel.actions.index', compact('actions', 'class'));
    }

    public function approveConfirmRecaps($studentAcademicYearId)
    {
        $classId = $this->getClassId();
        $activeAcademicYear = P_Configs::where('is_active', true)->first();

        $handlingPointOptions = P_Config_Handlings::where('p_config_id', $activeAcademicYear->id)
            ->orderBy('handling_point', 'asc')
            ->get();

        $studentAcademicYear = RefStudentAcademicYear::where('class_id', $classId)
            ->with([
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

        return view('wakel.dashboard.approve', compact(
            'studentAcademicYear',
            'handlingPointOptions',
            'totalVerifiedPoints'
        ));
    }

    public function updateViolationStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,verified,not_verified'
        ]);

        try {
            $recap = P_Recaps::findOrFail($id);
            $recap->update([
                'status' => $request->status,
                'verified_by' => Auth::id(),
                'updated_by' => Auth::id(),
            ]);

            return redirect()->back()->with('success', 'Status berhasil diperbarui!');
        } catch (\Exception $e) {
            Log::error('updateViolationStatus error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    public function massCreate()
    {
        $classId = $this->getClassId();

        $activeConfig = P_Configs::getActiveAcademicYear();
        if (!$activeConfig) {
            return back()->withErrors(['error' => 'Tidak ada konfigurasi tahun akademik yang aktif.']);
        }

        $violations = P_Violations::with('category')->orderBy('point', 'asc')->get();

        // Hanya siswa di kelas wakel ini
        $studentAcademicYears = RefStudentAcademicYear::activeAcademicYear()
            ->where('class_id', $classId)
            ->with(['student', 'class'])
            ->get()
            ->sortBy(fn($say) => $say->student->full_name ?? '')
            ->values();

        return view('wakel.violations.mass', compact('violations', 'studentAcademicYears', 'activeConfig'));
    }

    public function massStore(Request $request)
    {
        $classId = $this->getClassId();
        
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
                    ->where('class_id', $classId) // Pastikan siswa memang di kelas wakel ini
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
