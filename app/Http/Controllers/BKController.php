<?php

namespace App\Http\Controllers;

use App\Models\P_Config_Handlings;
use App\Models\P_Configs;
use Illuminate\Http\Request;
use App\Models\RefStudentAcademicYear;
use App\Models\P_Recaps;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class BKController extends Controller
{
    public function index()
    {
        // Ambil tahun akademik aktif
        $activeAcademicYear = P_Configs::getActiveAcademicYear();

        // Ambil data dari RefStudentAcademicYear sebagai base
        $studentAcademicYears = RefStudentAcademicYear::activeAcademicYear()
            ->with([
                'student',
                'class',
                'recaps' => function ($query) {
                    $query->with('violation');
                }
            ])
            ->whereHas('recaps') // Only students with violations
            ->withCount(['recaps as recaps_count'])
            ->get();


        return view('BK.dashboard.index', compact('studentAcademicYears',  'activeAcademicYear'));
    }

    public function updateViolationStatus(Request $request, $id)
    {
        // Validasi input
        $request->validate([
            'status' => 'required|in:pending,verified,not_verified'
        ]);

        try {
            Log::info('Searching for P_Recaps record', ['id' => $id]);

            $recap = P_Recaps::find($id);

            if (!$recap) {
                Log::error('P_Recaps not found with ID', ['id' => $id]);

                $recap = P_Recaps::where('p_violation_id', $id)->first();

                if ($recap) {
                    Log::info('Found P_Recaps with p_violation_id', [
                        'p_violation_id' => $id,
                        'actual_id' => $recap->id
                    ]);
                } else {
                    Log::error('P_Recaps not found with any ID field', ['searched_id' => $id]);
                    return redirect()->back()->with('error', 'Data pelanggaran tidak ditemukan (ID: ' . $id . ')');
                }
            }

            $originalStatus = $recap->status;
            Log::info('Found P_Recaps record', [
                'id' => $recap->id,
                'p_violation_id' => $recap->p_violation_id ?? 'not_set',
                'current_status' => $originalStatus,
                'new_status' => $request->status,
                'primary_key' => $recap->getKeyName(),
                'key_value' => $recap->getKey()
            ]);

            $recap->status = $request->status;

            if (Auth::check()) {
                $recap->verified_by = Auth::id();
                $recap->updated_by = Auth::id();
            }

            $recap->updated_at = now();

            Log::info('Attempting to save P_Recaps', [
                'changes' => $recap->getDirty()
            ]);

            $saved = $recap->save();

            $recap->refresh();
            Log::info('Save completed', [
                'save_result' => $saved,
                'new_status' => $recap->status,
                'status_changed' => $originalStatus !== $recap->status
            ]);

            if ($saved && $originalStatus !== $recap->status) {
                return redirect()->back()->with(
                    'success',
                    "Status berhasil diubah dari '{$originalStatus}' ke '{$recap->status}' "
                );
            } else {
                return redirect()->back()->with(
                    'warning',
                    'Data disimpan tetapi status tidak berubah. Original: ' . $originalStatus . ', Current: ' . $recap->status
                );
            }
        } catch (\Exception $e) {
            Log::error('Exception in updateViolationStatus', [
                'id' => $id,
                'error_message' => $e->getMessage(),
                'error_line' => $e->getLine(),
                'error_file' => $e->getFile(),
                'stack_trace' => $e->getTraceAsString()
            ]);

            return redirect()->back()->with(
                'error',
                'Error: ' . $e->getMessage() . ' (Line: ' . $e->getLine() . ')'
            );
        }
    }

    public function debugRecap($id)
    {
        $recap = P_Recaps::find($id);

        return response()->json([
            'found' => $recap ? true : false,
            'data' => $recap ? $recap->toArray() : null,
            'model_info' => [
                'table' => (new P_Recaps)->getTable(),
                'primary_key' => (new P_Recaps)->getKeyName(),
                'fillable' => (new P_Recaps)->getFillable(),
            ]
        ]);
    }

    public function recaps(Request $request)
    {
        // Ambil tahun akademik aktif
        $activeAcademicYear = P_Configs::getActiveAcademicYear();
        // Ambil handling options
        $handlingOptions = P_Config_Handlings::where('p_config_id', $activeAcademicYear->id)
            ->orderBy('handling_point', 'asc')
            ->get();
        // Query dari RefStudentAcademicYear sebagai base
        $recaps = RefStudentAcademicYear::activeAcademicYear()
            ->whereHas('recaps') // Filter hanya yang punya recaps
            ->with([
                'recaps' => function ($query) {
                    $query->with([
                        'violation.category',
                        'verifiedBy',
                        'createdBy',
                        'updatedBy',
                    ]);
                },
                'student',
                'class'
            ])
            ->get()
            ->map(function ($studentAcademicYear) {
                // Hitung total poin violations yang verified
                $verifiedPoints = $studentAcademicYear->recaps
                    ->where('status', 'verified')
                    ->whereNotNull('violation')
                    ->sum(function ($recap) {
                        return $recap->violation->point ?? 0;
                    });

                $studentAcademicYear->violations_sum_point = $verifiedPoints;
                return $studentAcademicYear;
            });

        return view('BK.dashboard.recaps', compact('recaps', 'activeAcademicYear', 'handlingOptions'));
    }
}
