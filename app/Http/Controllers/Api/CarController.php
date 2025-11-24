<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ModelMobil;
use Illuminate\Http\Request;

class CarController extends Controller
{
    public function index(Request $request)
    {
        // Ambil query params jika ada, misal ?page=2&per_page=8
        $perPage = $request->input('per_page', 8); // default 8 item per halaman
        $page = $request->input('page', 1);

        // Query data mobil
        $query = ModelMobil::query();

        // Kalau ingin filter berdasarkan brand atau model
        if ($request->has('q')) {
            $search = $request->input('q');
            $query->where('brand', 'like', "%{$search}%")
                  ->orWhere('model', 'like', "%{$search}%");
        }

        // Ambil data pakai pagination
        $mobils = $query->paginate($perPage, ['*'], 'page', $page);

        // Kembalikan JSON
        return response()->json($mobils);

    }
}
