<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\RequestSparepart; 
use Illuminate\Support\Facades\Validator;

class SparepartRequestController extends Controller
{
    /**
     * Tampilkan semua request sparepart (index)
     */
    public function index()
    {
        $requests = RequestSparepart::with('user') // relasi user, kalau ada
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($r) {
                return [
                    'id' => $r->id,
                    'user_name' => $r->user->name ?? '-',
                    'brand' => $r->brand_req,
                    'model' => $r->model_req,
                    'year' => $r->year_req,
                    'part_name' => $r->sparepart_req,
                    'note' => $r->note,
                    'status' => $r->status,
                ];
            });

        return response()->json($requests);
    }

    /**
     * Menampilkan detail satu request (untuk edit)
     */
    public function show($id)
    {
        $r = RequestSparepart::with('user')->findOrFail($id);

        return response()->json([
            'id' => $r->id,
            'user_name' => $r->user->name ?? '-',
            'brand' => $r->brand_req,
            'model' => $r->model_req,
            'year' => $r->year_req,
            'part_name' => $r->sparepart_req,
            'note' => $r->note,
            'status' => $r->status,
        ]);
    }

    /**
     * Update status request
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required|in:pending,in_progress,fulfilled,rejected',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        $req = RequestSparepart::findOrFail($id);
        $req->status = $request->status;
        $req->save();

        return response()->json([
            'message' => 'Status request berhasil diperbarui',
            'data' => $req
        ]);
    }
}
