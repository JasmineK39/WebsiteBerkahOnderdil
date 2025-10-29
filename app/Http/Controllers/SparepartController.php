<?php

namespace App\Http\Controllers;

use App\Models\Sparepart;
use Illuminate\Http\Request;

class SparepartController extends Controller
{
    public function index()
    {
        $spareparts = Sparepart::with('modelMobil', 'kategoris')->get();
        return response()->json($spareparts);
    }

    public function show($id)
    {
        $sparepart = Sparepart::with('modelMobil', 'kategoris')->findOrFail($id);
        return response()->json($sparepart);
    }
}
