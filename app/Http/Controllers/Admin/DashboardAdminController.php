<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Sparepart;
use App\Models\Transaksi;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class DashboardAdminController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json([
    'totalUser' => User::count(),
    'totalSparepart' => Sparepart::count(),
    'totalPenjualan' => Transaksi::count(),

    'latestSpareparts' => Sparepart::orderBy('id', 'desc')
        ->take(5)
        ->get(['id', 'name', 'price', 'stock']),

    'userPerMonth' => User::selectRaw('COUNT(*) as total, MONTH(created_at) as bulan')
        ->groupBy('bulan')
        ->orderBy('bulan')
        ->get(),

    'checkoutPerMonth' => Transaksi::selectRaw('COUNT(*) as total, MONTH(transaction_date) as bulan')
        ->whereNotNull('transaction_date')
        ->groupBy('bulan')
        ->orderBy('bulan')
        ->get(),
]);
    }
}
