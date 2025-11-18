<?php

namespace App\Http\Controllers;

use App\Models\P_Violations;
use App\Models\P_Categories;
use Illuminate\Http\Request;

class ViolationController extends Controller
{
    public function index(Request $request)
    {
        $query = P_Violations::with('category');

        // Filter berdasarkan kategori
        if ($request->filled('category')) {
            $query->whereHas('category', function ($q) use ($request) {
                $q->where('name', $request->category);
            });
        }

        // Filter berdasarkan rentang poin
        if ($request->filled('min_point')) {
            $query->where('point', '>=', $request->min_point);
        }

        if ($request->filled('max_point')) {
            $query->where('point', '<=', $request->max_point);
        }

        // Hitung kasus verified tanpa paginate
        $violations = $query->withCount([
            'recaps as verified_cases_count' => function ($q) {
                $q->where('status', 'verified');
            }
        ])->orderBy('point', 'asc')->get();

        // Get all categories for filter dropdown
        $categories = P_Categories::all();

        return view('superadmin.violations.index', compact('violations', 'categories'));
    }

    public function add(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:p_categories,id',
            'name' => 'required|string|max:255',
            'point' => 'required|integer|min:0|max:100',
        ]);

        P_Violations::create([
            'p_category_id' => $request->category_id,
            'name' => $request->name,
            'point' => $request->point,
        ]);

        return redirect()->route('superadmin.violations')->with('success', 'Pelanggaran berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'category_id' => 'required|exists:p_categories,id',
            'name' => 'required|string|max:255',
            'point' => 'required|integer|min:0|max:100',
        ]);

        $violation = P_Violations::findOrFail($id);
        $violation->update([
            'p_category_id' => $request->category_id,
            'name' => $request->name,
            'point' => $request->point,
        ]);

        return redirect()->route('superadmin.violations')->with('success', 'Pelanggaran berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $violation = P_Violations::findOrFail($id);
        $violation->delete();

        return redirect()->route('superadmin.violations')->with('success', 'Pelanggaran berhasil dihapus.');
    }
}
