<?php

namespace App\Http\Controllers;

use App\Models\P_Config_Handlings;
use App\Models\P_Configs;
use App\Models\P_Recaps;
use App\Models\P_Viol_Action;
use App\Models\RefStudentAcademicYear;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SiswaController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $student = $user->student;

        if (!$student) {
            abort(404, 'Data siswa tidak ditemukan.');
        }

        $activeConfig = P_Configs::where('is_active', true)->first();
        $academicYearStr = $activeConfig ? str_replace('-', '/', $activeConfig->academic_year) : null;

        // Ambil data kelas tahun akademik aktif
        $studentAcademicYear = RefStudentAcademicYear::where('student_id', $student->id)
            ->when($academicYearStr, function ($q) use ($academicYearStr) {
                $q->where('academic_year', $academicYearStr);
            })
            ->with(['class'])
            ->first();

        // Jika tidak terdaftar di tahun akademik aktif, ambil yang paling terakhir
        if (!$studentAcademicYear) {
            $studentAcademicYear = RefStudentAcademicYear::where('student_id', $student->id)
                ->with(['class'])
                ->orderByDesc('created_at')
                ->first();
        }

        // Ambil semua recaps pelanggaran siswa
        $allRecaps = P_Recaps::where('ref_student_id', $student->id)
            ->with(['violation.category'])
            ->get();

        // Poin terverifikasi
        $verifiedPoints = $allRecaps->where('status', 'verified')->sum(fn($r) => $r->violation->point ?? 0);
        // Poin pending
        $pendingPoints = $allRecaps->where('status', 'pending')->sum(fn($r) => $r->violation->point ?? 0);
        // Total poin gabungan
        $totalPoints = $verifiedPoints + $pendingPoints;

        // Distribusi kategori pelanggaran terverifikasi
        $categoryDistribution = [
            'Ringan' => 0,
            'Sedang' => 0,
            'Berat' => 0,
        ];

        foreach ($allRecaps->where('status', 'verified') as $recap) {
            $categoryName = $recap->violation->category->name ?? null;
            if (isset($categoryDistribution[$categoryName])) {
                $categoryDistribution[$categoryName]++;
            }
        }

        // Cari tindakan terakhir yang diberikan ke siswa
        $studentAcademicYearIds = RefStudentAcademicYear::where('student_id', $student->id)->pluck('id');
        $latestAction = P_Viol_Action::whereIn('p_student_academic_year_id', $studentAcademicYearIds)
            ->with(['handling', 'handle'])
            ->orderByDesc('created_at')
            ->first();

        // Dapatkan batas tindak lanjut berikutnya berdasarkan poin terverifikasi saat ini
        $nextHandling = null;
        if ($activeConfig) {
            $nextHandling = P_Config_Handlings::where('p_config_id', $activeConfig->id)
                ->where('handling_point', '>', $verifiedPoints)
                ->orderBy('handling_point', 'asc')
                ->first();
        }

        return view('siswa.dashboard.index', compact(
            'student',
            'studentAcademicYear',
            'verifiedPoints',
            'pendingPoints',
            'totalPoints',
            'categoryDistribution',
            'latestAction',
            'nextHandling',
            'activeConfig'
        ));
    }

    public function violations()
    {
        $user = Auth::user();
        $student = $user->student;

        if (!$student) {
            abort(404, 'Data siswa tidak ditemukan.');
        }

        $violations = P_Recaps::where('ref_student_id', $student->id)
            ->with(['violation.category', 'createdBy', 'verifiedBy'])
            ->orderByDesc('created_at')
            ->paginate(15);

        return view('siswa.violations.index', compact('student', 'violations'));
    }

    public function actions()
    {
        $user = Auth::user();
        $student = $user->student;

        if (!$student) {
            abort(404, 'Data siswa tidak ditemukan.');
        }

        $studentAcademicYearIds = RefStudentAcademicYear::where('student_id', $student->id)->pluck('id');
        
        $actions = P_Viol_Action::whereIn('p_student_academic_year_id', $studentAcademicYearIds)
            ->with(['handling', 'handle', 'detail'])
            ->orderByDesc('created_at')
            ->paginate(15);

        return view('siswa.actions.index', compact('student', 'actions'));
    }
}
