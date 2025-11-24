<?php

namespace App\Http\Controllers;

use App\Models\Sparepart;
use Illuminate\Http\Request;

class SparepartController extends Controller
{
    public function index(Request $request)
    {
        $query = Sparepart::query()->with('kategoris');

        if ($request->filled('car_id')) {
        $query->where('model_mobil_id', $request->car_id);
    }
    
        if ($request->filled('search')) {
            $keyword = $request->search;
            $query->where(function ($q) use ($keyword) {
                $q->where('name', 'like', "%{$keyword}%")
                  ->orWhere('brand', 'like', "%{$keyword}%")
                  ->orWhere('description', 'like', "%{$keyword}%");
            });
        }

        if ($request->filled('grade')) {
            $grades = explode(',', $request->grade);
            $query->whereIn('grade', $grades);
        }

        if ($request->filled('min_price')) {
            $query->where('price', '>=', (int) $request->min_price);
        }
        if ($request->filled('max_price')) {
            $query->where('price', '<=', (int) $request->max_price);
        }

        if ($request->filled('category')) {
            $categories = explode(',', $request->category);
            $query->whereHas('kategoris', function ($q) use ($categories) {
                $q->whereIn('name', $categories);
            });
        }

        $spareparts = $query->paginate(12);

        return response()->json($spareparts);
    }

    public function show($id)
    {
        $sparepart = Sparepart::with('modelMobil', 'kategoris')->findOrFail($id);
        return response()->json($sparepart);
    }

    public function store(Request $request)
{
    $data = $request->validate([
        'name' => 'required|string|max:255',
        'brand' => 'nullable|string',
        'price' => 'required|numeric',
        'grade' => 'nullable|string',
        'description' => 'nullable|string',
    ]);
    $sparepart = Sparepart::create($data);
    return response()->json($sparepart);
}

public function update(Request $request, $id)
{
    $sparepart = Sparepart::findOrFail($id);
    $sparepart->update($request->all());
    return response()->json($sparepart);
}

public function destroy($id)
{
    $sparepart = Sparepart::findOrFail($id);
    $sparepart->delete();
    return response()->json(['message' => 'Deleted successfully']);
}

}
