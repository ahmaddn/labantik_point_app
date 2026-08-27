<?php

namespace App\Http\Controllers;

use App\Models\P_Config_Handlings;
use App\Models\P_Configs;
use App\Models\P_Recaps;
use App\Models\P_Viol_Action;
use App\Models\RefStudentAcademicYear;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\P_PointReduction;

class SiswaController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $student = $this->getOrLinkStudent($user);

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

        $academicYearVal = $studentAcademicYear ? $studentAcademicYear->academic_year : $academicYearStr;

        // Ambil semua recaps pelanggaran siswa
        $allRecaps = P_Recaps::where('ref_student_id', $student->id)
            ->with(['violation.category'])
            ->get();

        $totalReductions = P_PointReduction::where('ref_student_id', $student->id)
            ->when($academicYearVal, function ($q) use ($academicYearVal) {
                $q->where('academic_year', $academicYearVal);
            })
            ->sum('points_reduced');

        // Poin terverifikasi
        $verifiedPoints = max(0, $allRecaps->where('status', 'verified')->sum(fn($r) => $r->violation->point ?? 0) - $totalReductions);
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
        $student = $this->getOrLinkStudent($user);

        $violations = P_Recaps::where('ref_student_id', $student->id)
            ->with(['violation.category', 'createdBy', 'verifiedBy'])
            ->orderByDesc('created_at')
            ->paginate(15);

        return view('siswa.violations.index', compact('student', 'violations'));
    }

    public function actions()
    {
        $user = Auth::user();
        $student = $this->getOrLinkStudent($user);

        $studentAcademicYearIds = RefStudentAcademicYear::where('student_id', $student->id)->pluck('id');
        
        $actions = P_Viol_Action::whereIn('p_student_academic_year_id', $studentAcademicYearIds)
            ->with(['handling', 'handle', 'detail'])
            ->orderByDesc('created_at')
            ->paginate(15);

        return view('siswa.actions.index', compact('student', 'actions'));
    }

    private function getOrLinkStudent($user)
    {
        $student = $user->student;
        if (!$student) {
            // Auto-link student by matching student_number/NISN prefix in email, or name
            $emailPrefix = explode('@', $user->email)[0];
            $student = \App\Models\RefStudent::where('student_number', $emailPrefix)
                ->orWhere('national_student_number', $emailPrefix)
                ->orWhere('full_name', 'like', $user->name)
                ->first();

            if ($student) {
                $student->user_id = $user->id;
                $student->save();
                $user->load('student');
                $student = $user->student;
            } else {
                // Fallback creation for testing/development accounts
                $student = \App\Models\RefStudent::create([
                    'id' => (string) \Illuminate\Support\Str::uuid(),
                    'user_id' => $user->id,
                    'full_name' => $user->name,
                    'student_number' => is_numeric($emailPrefix) ? $emailPrefix : '99999999',
                    'national_student_number' => '9999999999',
                    'gender' => 'L',
                    'religion' => 'Islam',
                    'address' => 'Tidak diketahui',
                ]);
                $user->load('student');
                $student = $user->student;
            }
        }
        return $student;
    }
}
