<?php

namespace App\Http\Controllers;

use App\Models\Sparepart;
use Illuminate\Http\Request;

class SparepartController extends Controller
{
public function index(Request $request)
{
    $query = Sparepart::query();

    if ($request->has('search') && $request->search !== '') {
        $keyword = $request->search;
        $query->where('name', 'like', "%{$keyword}%")
              ->orWhere('brand', 'like', "%{$keyword}%")
              ->orWhere('description', 'like', "%{$keyword}%");
    }

    $spareparts = $query->get();

    return response()->json($spareparts);
}



    public function show($id)
    {
        $sparepart = Sparepart::with('modelMobil', 'kategoris')->findOrFail($id);
        return response()->json($sparepart);
    }
}
