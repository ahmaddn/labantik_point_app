<?php

namespace App\Http\Controllers;

use App\Models\P_Violations;
use App\Models\P_Categories;
use Illuminate\Http\Request;

class ViolationController extends Controller
{
    public function index(Request $request)
    {
        $validated = $request->validate([
            'category' => 'nullable|string',
            'min_point' => 'nullable|integer|min:0',
            'max_point' => 'nullable|integer|min:0|gte:min_point',
        ]);

        // Optimasi: Gunakan query builder dengan select spesifik
        $query = P_Violations::select([
            'p_violations.id',
            'p_violations.p_category_id',
            'p_violations.name',
            'p_violations.point'
        ])
            ->with('category:id,name');

        if ($request->filled('category')) {
            $query->whereHas('category', function ($q) use ($request) {
                $q->where('name', $request->category);
            });
        }

        if ($request->filled('min_point')) {
            $query->where('point', '>=', $request->min_point);
        }

        if ($request->filled('max_point')) {
            $query->where('point', '<=', $request->max_point);
        }

        // Optimasi: Gunakan withCount untuk menghitung kasus verified
        $violations = $query->withCount([
            'recaps as verified_cases_count' => function ($q) {
                $q->where('status', 'verified');
            }
        ])
            ->orderBy('point', 'asc')
            ->get();

        $categories = P_Categories::select('id', 'name')->get();

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

        P_Violations::where('id', $id)->update([
            'p_category_id' => $request->category_id,
            'name' => $request->name,
            'point' => $request->point,
        ]);

        return redirect()->route('superadmin.violations')->with('success', 'Pelanggaran berhasil diperbarui.');
    }

    public function destroy($id)
    {
        P_Violations::where('id', $id)->delete();

        return redirect()->route('superadmin.violations')->with('success', 'Pelanggaran berhasil dihapus.');
    }
}
