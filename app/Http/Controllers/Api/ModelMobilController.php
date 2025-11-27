<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ModelMobil;
use Illuminate\Http\Request;

class ModelMobilController extends Controller
{
    // Ambil semua brand (distinct)
    public function getBrands()
    {
        $brands = ModelMobil::select('brand')
            ->distinct()
            ->orderBy('brand', 'asc')
            ->get();

        return response()->json($brands);
    }

    // Ambil model berdasarkan brand
    public function getModelsByBrand($brand)
    {
        $models = ModelMobil::where('brand', $brand)
            ->select('id', 'model', 'year')
            ->orderBy('model', 'asc')
            ->get();

        return response()->json($models);
    }
}
