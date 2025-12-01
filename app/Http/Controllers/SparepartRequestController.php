<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\RequestSparepart;

class SparepartRequestController extends Controller
{
    public function apiIndex()
    {
<<<<<<< HEAD
        return RequestSparepart::where('user_id', Auth::id())->get();
=======
        return RequestSparepart::where('user_id', Auth::id())
    ->orderBy('created_at', 'desc')
    ->get();

>>>>>>> 6ed2205f85b9826a31eae9b670fca7c4b7ec218c
    }

    public function apiStore(Request $request)
{
    $validated = $request->validate([
        'brand_req' => 'required|string|max:50',
        'model_req' => 'required|string|max:100',
        'year_req' => 'required|numeric',
        'sparepart_req' => 'required|string|max:100',
        'note' => 'nullable|string',
    ]);

    $validated['user_id'] = Auth::id();

    // Ganti sesuai tipe kolom di DB
    $validated['status'] = 'pending'; 

    $data = RequestSparepart::create($validated);

    return response()->json($data, 201);
}

}
