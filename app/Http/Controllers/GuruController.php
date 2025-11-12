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

    public function store(Request $request, $studentId)
    {
        $request->validate([
            'violations'   => 'required|array',
            'violations.*' => 'exists:p_violations,id',
        ]);

        // Cek apakah siswa aktif di tahun akademik yang sedang berjalan
        $activeAcademicYear = P_Configs::getActiveAcademicYear();

        $studentAcademicYear = RefStudentAcademicYear::where('student_id', $studentId)
            ->where('academic_year', $activeAcademicYear)
            ->first();

        if (!$studentAcademicYear) {
            return back()->withErrors([
                'error' => 'Siswa tidak terdaftar pada tahun akademik aktif (' . $activeAcademicYear . ')'
            ]);
        }

        // Hitung total poin verified saat ini (untuk tahun akademik aktif)
        $currentVerifiedPoints = P_Recaps::where('ref_student_id', $studentAcademicYear->id)
            ->where('status', 'verified')
            ->join('p_violations', 'p_recaps.p_violation_id', '=', 'p_violations.id')
            ->sum('p_violations.point');

        // Hitung total poin pending saat ini (untuk tahun akademik aktif)
        $currentPendingPoints = P_Recaps::where('ref_student_id', $studentAcademicYear->id)
            ->where('status', 'pending')
            ->join('p_violations', 'p_recaps.p_violation_id', '=', 'p_violations.id')
            ->sum('p_violations.point');

        // Total poin saat ini (verified + pending)
        $currentTotalPoints = $currentVerifiedPoints + $currentPendingPoints;

        // Hitung poin baru yang akan ditambah
        $newPoints = 0;
        $violationIds = $request->violations;

        if (!empty($violationIds)) {
            $newPoints = P_Violations::whereIn('id', $violationIds)->sum('point');
        }

        // Hitung total poin setelah penambahan
        $totalPointsAfterAdd = $currentTotalPoints + $newPoints;

        // VALIDASI UTAMA: Cek apakah total poin saat ini sudah mencapai atau melebihi 100
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

        // VALIDASI KEDUA: Cek apakah penambahan poin baru akan melebihi 100
        if ($totalPointsAfterAdd > 100) {
            $excessPoints = $totalPointsAfterAdd - 100;
            return back()->withErrors([
                'error' => 'Penambahan pelanggaran ini akan melebihi batas maksimal 100 poin.'
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

        // Jika validasi lolos, simpan pelanggaran ke database
        try {
            DB::beginTransaction();

            foreach ($violationIds as $violationId) {
                P_Recaps::create([
                    'ref_student_id'  => $studentAcademicYear->id,
                    'p_violation_id'  => $violationId,
                    'status'          => 'pending',
                    'created_by'      => Auth::id(),
                    'updated_by'      => Auth::id(),
                ]);
            }

            DB::commit();

            // Hitung ulang poin setelah penyimpanan untuk success message
            $verifiedPoints = P_Recaps::where('ref_student_id', $studentAcademicYear->id)
                ->where('status', 'verified')
                ->join('p_violations', 'p_recaps.p_violation_id', '=', 'p_violations.id')
                ->sum('p_violations.point');

            $pendingPoints = P_Recaps::where('ref_student_id', $studentAcademicYear->id)
                ->where('status', 'pending')
                ->join('p_violations', 'p_recaps.p_violation_id', '=', 'p_violations.id')
                ->sum('p_violations.point');

            $totalAllPoints = $verifiedPoints + $pendingPoints;

            return back()->with([
                'success' => 'Pelanggaran berhasil ditambahkan untuk tahun akademik ' . $activeAcademicYear . '!',
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

        return view('guru.student-data.index', compact(
            'studentAcademicYears',
            'vals',
            'activeAcademicYear',
            'classes',
            'selectedClassId'
        ));
    }


    public function recaps(Request $request)
    {
        // Ambil tahun akademik aktif
        $activeAcademicYear = P_Configs::where('is_active', true)->first();

        // Ambil handling options
        $handlingOptions = P_Config_Handlings::where('p_config_id', $activeAcademicYear->id)
            ->orderBy('handling_point', 'asc')
            ->get();

        $recaps = RefStudentAcademicYear::activeAcademicYear()
            ->whereHas('recaps', function ($q) {
                $q->whereIn('status', ['pending', 'verified', 'not verified']);
            })
            ->with([
                'student',
                'class',
                'recaps' => function ($query) {
                    $query->where('status', 'verified')
                        ->with([
                            'violation.category',
                            'verifiedBy',
                            'createdBy',
                            'updatedBy',
                        ])
                        ->orderBy('created_at', 'desc');
                }
            ])
            ->get()
            ->map(function ($studentAcademicYear) use ($handlingOptions) {
                $verifiedPoints = $studentAcademicYear->recaps->sum(function ($recap) {
                    return $recap->violation->point ?? 0;
                });

                $studentAcademicYear->violations_sum_point = $verifiedPoints;

                // Tentukan handling yang sesuai berdasarkan total poin
                $studentAcademicYear->current_handling = $handlingOptions
                    ->where('handling_point', '<=', $verifiedPoints)
                    ->sortByDesc('handling_point')
                    ->first();

                return $studentAcademicYear;
            });

        return view('guru.dashboard.recaps', compact('recaps', 'activeAcademicYear', 'handlingOptions'));
    }
}
