<?php

namespace App\Http\Controllers;

use App\Models\P_Config_Handlings;
use App\Models\P_Configs;
use App\Models\RefStudentAcademicYear;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ConfigController extends Controller
{
    public function index()
    {
        // Optimasi: Select hanya field yang diperlukan dan gunakan pagination
        $configs = P_Configs::select('id', 'academic_year', 'is_active', 'created_by', 'created_at')
            ->with([
                'handlings:id,p_config_id,handling_point,handling_action',
                'createdBy:id,name'
            ])
            ->orderByDesc('created_at')
            ->paginate(10);

        // Optimasi: Distinct dengan select spesifik
        $academicYears = RefStudentAcademicYear::select('academic_year')
            ->distinct()
            ->orderByDesc('academic_year')
            ->get();

        $activeAcademicYear = P_Configs::select('id', 'academic_year', 'is_active')
            ->where('is_active', true)
            ->first();

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

            if ($request->has('is_active')) {
                P_Configs::where('is_active', true)->update(['is_active' => false]);
            }

            $config = P_Configs::create([
                'id' => Str::uuid(),
                'academic_year' => $request->academic_year,
                'is_active'     => $request->has('is_active'),
                'created_by'    => Auth::id(),
            ]);

            // Optimasi: Bulk insert untuk handlings
            if ($request->handling_points) {
                $handlingsData = [];
                foreach ($request->handling_points as $index => $point) {
                    if ($point && isset($request->handling_actions[$index])) {
                        $handlingsData[] = [
                            'id' => Str::uuid(),
                            'p_config_id'    => $config->id,
                            'handling_point' => $point,
                            'handling_action' => $request->handling_actions[$index],
                            'created_at' => now(),
                            'updated_at' => now(),
                        ];
                    }
                }

                if (!empty($handlingsData)) {
                    P_Config_Handlings::insert($handlingsData);
                }
            }

            DB::commit();
            return redirect()->back()->with('success', 'Konfigurasi berhasil ditambahkan.');
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

            P_Configs::where('id', $id)->update([
                'academic_year' => $request->academic_year,
                'updated_by'    => Auth::id(),
                'updated_at'    => now(),
            ]);

            // Hapus handling lama
            P_Config_Handlings::where('p_config_id', $id)->delete();

            // Bulk insert handling baru
            $handlingsData = [];
            foreach ($request->handling_points as $index => $point) {
                if ($point && isset($request->handling_actions[$index])) {
                    $handlingsData[] = [
                        'id' => Str::uuid(),
                        'p_config_id'    => $id,
                        'handling_point' => $point,
                        'handling_action' => $request->handling_actions[$index],
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
            }

            if (!empty($handlingsData)) {
                P_Config_Handlings::insert($handlingsData);
            }

            DB::commit();
            return redirect()->back()->with('success', 'Konfigurasi berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal memperbarui: ' . $e->getMessage());
        }
    }

    public function activate($id)
    {
        try {
            DB::beginTransaction();

            P_Configs::where('is_active', true)->update(['is_active' => false]);
            P_Configs::where('id', $id)->update([
                'is_active' => true,
                'updated_at' => now()
            ]);

            DB::commit();
            return redirect()->back()->with('success', 'Konfigurasi berhasil diaktifkan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal mengaktifkan: ' . $e->getMessage());
        }
    }

    public function deactivate($id)
    {
        try {
            P_Configs::where('id', $id)->update([
                'is_active' => false,
                'updated_at' => now()
            ]);

            return redirect()->back()->with('success', 'Konfigurasi berhasil dinonaktifkan.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menonaktifkan: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            DB::beginTransaction();

            P_Config_Handlings::where('p_config_id', $id)->delete();
            P_Configs::where('id', $id)->delete();

            DB::commit();
            return redirect()->back()->with('success', 'Konfigurasi berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal menghapus: ' . $e->getMessage());
        }
    }
}
