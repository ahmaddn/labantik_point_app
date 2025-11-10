<?php

namespace App\Http\Controllers;

use App\Models\P_Config_Handlings;
use App\Models\P_Configs;
use App\Models\RefStudent;
use App\Models\RefStudentAcademicYear;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ConfigController extends Controller
{
    public function index()
    {
        $configs = P_Configs::with(['handlings', 'createdBy'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $academicYears = RefStudentAcademicYear::select('academic_year')
            ->distinct()
            ->orderBy('academic_year', 'desc')
            ->get();

        $activeAcademicYear = P_Configs::where('is_active', true)->first();

        return view('superadmin.configs.index', compact('configs', 'academicYears', 'activeAcademicYear'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'academic_year' => 'required|exists:ref_student_academic_years,academic_year',
            'handling_points.*' => 'nullable|numeric',
            'handling_actions.*' => 'nullable|string',
        ]);

        try {
            DB::beginTransaction();

            // Jika diset aktif, nonaktifkan semua config lain
            if ($request->has('is_active')) {
                P_Configs::where('is_active', true)->update(['is_active' => false]);
            }

            $config = P_Configs::create([
                'id' => Str::uuid(),
                'academic_year' => $request->academic_year,
                'is_active'     => $request->has('is_active'),
                'created_by'    => Auth::id(),
            ]);

            // Tambah handling points jika ada
            if ($request->handling_points) {
                foreach ($request->handling_points as $index => $point) {
                    if ($point && isset($request->handling_actions[$index])) {
                        P_Config_Handlings::create([
                            'id' => Str::uuid(),
                            'p_config_id'    => $config->id,
                            'handling_point' => $point,
                            'handling_action' => $request->handling_actions[$index],
                        ]);
                    }
                }
            }

            DB::commit();
            return redirect()->back()->with('success', 'Konfigurasi tahun akademik berhasil ditambahkan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal menambahkan konfigurasi: ' . $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'academic_year' => 'required|exists:ref_student_academic_years,academic_year',
            'handling_points.*' => 'required|numeric',
            'handling_actions.*' => 'required|string',
        ]);

        try {
            DB::beginTransaction();

            $config = P_Configs::findOrFail($id);

            $config->update([
                'academic_year' => $request->academic_year,
                'updated_by'    => Auth::id(),
            ]);

            // Hapus handling lama dan tambah yang baru
            $config->handlings()->delete();

            foreach ($request->handling_points as $index => $point) {
                if ($point && isset($request->handling_actions[$index])) {
                    P_Config_Handlings::create([
                        'p_config_id'    => $config->id,
                        'handling_point' => $point,
                        'handling_action' => $request->handling_actions[$index],
                    ]);
                }
            }

            DB::commit();
            return redirect()->back()->with('success', 'Konfigurasi berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal memperbarui konfigurasi: ' . $e->getMessage());
        }
    }

    public function activate($id)
    {
        try {
            DB::beginTransaction();

            // Nonaktifkan semua config
            P_Configs::where('is_active', true)->update(['is_active' => false]);

            // Aktifkan config yang dipilih
            $config = P_Configs::findOrFail($id);
            $config->update(['is_active' => true]);

            DB::commit();
            return redirect()->back()->with('success', 'Konfigurasi berhasil diaktifkan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal mengaktifkan konfigurasi: ' . $e->getMessage());
        }
    }

    public function deactivate($id)
    {
        try {
            $config = P_Configs::findOrFail($id);
            $config->update(['is_active' => false]);

            return redirect()->back()->with('success', 'Konfigurasi berhasil dinonaktifkan.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menonaktifkan konfigurasi: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $config = P_Configs::findOrFail($id);

            // Hapus handlings terkait
            $config->handlings()->delete();

            // Hapus config
            $config->delete();

            return redirect()->back()->with('success', 'Konfigurasi berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menghapus konfigurasi: ' . $e->getMessage());
        }
    }
}
