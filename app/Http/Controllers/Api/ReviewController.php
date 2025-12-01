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
    public function store(Request $request, Transaksi $transaksi)
    {
        // 1. Validasi Input
        $validated = $request->validate([
            // ID Sparepart wajib, harus ada di tabel 'spareparts'
            'sparepart_id' => [
                'required', 
                'integer', 
                Rule::exists('spareparts', 'id')
            ],
            // Rating wajib, antara 1 sampai 5
            'rating' => 'required|integer|min:1|max:5',
            // Komentar opsional
            'comment' => 'nullable|string|max:1000',
        ]);
        
        // 2. Cek Kepemilikan Transaksi dan Status
        // Karena kita menggunakan Route Model Binding ($transaksi), kita hanya perlu cek kepemilikan.
        if ($transaksi->user_id !== Auth::id()) {
            // Menggunakan abort() akan memberikan response 403 Forbidden yang bersih
            abort(403, 'Akses ditolak. Transaksi ini bukan milik Anda.'); 
        }

        // 3. Cek Duplikasi Review
        $existingReview = Review::where('user_id', Auth::id())
            ->where('sparepart_id', $validated['sparepart_id'])
            // Gunakan ID dari objek Transaksi yang sudah di-bind
            ->where('transaksi_id', $transaksi->id) 
            ->exists();

        if ($existingReview) {
             return response()->json([
                'message' => 'Anda sudah memberikan review untuk sparepart ini pada transaksi yang sama.'
            ], 409); 
        }

        // 4. Menyimpan Review Baru
        $review = Review::create([
            'user_id' => Auth::id(), 
            'sparepart_id' => $validated['sparepart_id'],
            'transaksi_id' => $transaksi->id, // Gunakan ID dari objek Transaksi
            'rating' => $validated['rating'],
            'comment' => $validated['comment'],
        ]);

        // 5. Response Sukses
        return response()->json([
            'message' => 'Review berhasil disimpan!',
            'review' => $review
        ], 201);
    }
}