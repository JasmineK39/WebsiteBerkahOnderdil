<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Review;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class ReviewController extends Controller
{
    /**
     * Menyimpan review baru ke database, menerima objek Transaksi melalui model binding.
     * * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Transaksi  $transaksi  <-- INI ADALAH PERUBAHAN KRUSIAL
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request, $transaksi)
    {
        $transaksiId = $transaksi; 
        // 1. Validasi Input Dasar
        $request->validate([
            'sparepart_id' => 'required|exists:spareparts,id',
            'rating'       => 'required|integer|min:1|max:5',
            'comment'      => 'nullable|string|max:1000',
        ]);
        
        $userId = Auth::id();

        // 2. Verifikasi Ketersediaan & Status Transaksi
        $transaksi = Transaksi::with('items.sparepart')
                            ->where('id', $transaksiId)
                            ->where('user_id', $userId) // Pastikan milik user
                            ->firstOrFail();

        // Cek 2A: Pastikan pesanan sudah selesai/terkirim (Sesuaikan status Anda)
        if ($transaksi->status !== 'delivered' && $transaksi->status !== 'completed') {
            return response()->json(['message' => 'Ulasan hanya dapat diberikan untuk transaksi yang sudah selesai.'], 403);
        }

        // Cek 2B: Pastikan sparepart yang diulas ada dalam transaksi ini
        $itemFound = false;
        foreach ($transaksi->items as $item) {
            if ($item->sparepart_id == $request->sparepart_id) {
                $itemFound = true;
                break;
            }
        }

        if (!$itemFound) {
             return response()->json(['message' => 'Sparepart ID tidak ditemukan dalam transaksi ini.'], 404);
        }
        
        // 3. Buat Ulasan Baru (dengan try-catch untuk menangkap duplikasi)
        try {
            $review = Review::create([
                'user_id'      => $userId,
                'sparepart_id' => $request->sparepart_id,
                'transaksi_id' => $transaksiId, 
                'rating'       => $request->rating,
                'comment'      => $request->comment,
            ]);

            return response()->json([
                'message' => 'Ulasan berhasil disimpan.',
                'review' => $review
            ], 201);

        } catch (UniqueConstraintViolationException $e) {
            // Tangkap exception jika database mendeteksi duplikasi pada (user_id, sparepart_id, transaksi_id)
            return response()->json([
                'message' => 'Anda sudah mengulas sparepart ini untuk transaksi yang sama.'
            ], 409); // 409 Conflict
        }
    }
}