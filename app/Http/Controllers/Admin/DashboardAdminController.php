<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Sparepart;
use App\Models\Transaksi;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class DashboardAdminController extends Controller
{
public function index()
    {
        return Inertia::render('Admin/Index', [

            // === CARD STATISTIC ===
            'totalUser' => User::count(),
            'totalSparepart' => Sparepart::count(),

            // TOTAL PENJUALAN (total checkout)
            'totalPenjualan' => Transaksi::count(),

            // === LATEST SPAREPARTS ===
            'latestSpareparts' => Sparepart::orderBy('id', 'desc')->take(5)->get(),

            // === USER PER MONTH ===
            'userPerMonth' => User::selectRaw("
                    COUNT(*) as total,
                    MONTH(created_at) as bulan
                ")
                ->groupBy('bulan')
                ->orderBy('bulan')
                ->get(),

            // === CHECKOUT PER MONTH === (riwayat checkout)
            'checkoutPerMonth' => Transaksi::selectRaw("
                    COUNT(*) as total,
                    MONTH(transaction_date) as bulan
                ")
                ->groupBy('bulan')
                ->orderBy('bulan')
                ->get(),
        ]);
    }
}
