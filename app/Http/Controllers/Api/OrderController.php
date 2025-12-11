<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function index()
    {
        $userId = Auth::id();

        if (!$userId) {
            return response()->json(['message' => 'Unauthorized. Please login.'], 401);
        }

        $orders = Transaksi::where('user_id', $userId)
            ->whereIn('status', ['paid', 'shipped', 'completed']) 
            ->with(['items.sparepart', 'reviews']) // Ambil juga relasi review jika diperlukan
            ->orderBy('created_at', 'desc')
            ->paginate(10); // Paginate untuk performa

        return response()->json($orders);
    }

    public function completeOrder(Request $request, $id)
    {
        // 1. Ambil transaksi berdasarkan ID dan pastikan itu milik user yang login
        $transaksi = Transaksi::where('id', $id)
                              ->where('user_id', Auth::id())
                              ->first();

        if (!$transaksi) {
            return response()->json(['message' => 'Pesanan tidak ditemukan atau bukan milik Anda.'], 404);
        }

        // 2. Pastikan status saat ini adalah 'shipped' sebelum bisa di 'completed'
        if (!in_array($transaksi->status, ['paid', 'shipped'])) { 
        return response()->json(['message' => 'Pesanan tidak dalam status yang dapat diselesaikan oleh pengguna.'], 400);
    }
        // 3. Update status menjadi 'completed'
        $transaksi->status = 'completed';
        $transaksi->save();

        return response()->json(['message' => 'Status pesanan berhasil diubah menjadi Completed.'], 200);
    }
}