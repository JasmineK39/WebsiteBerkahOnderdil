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
}