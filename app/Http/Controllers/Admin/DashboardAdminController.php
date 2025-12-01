<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Sparepart;
<<<<<<< HEAD
=======
use App\Models\Transaksi;
>>>>>>> 6ed2205f85b9826a31eae9b670fca7c4b7ec218c
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class DashboardAdminController extends Controller
{
<<<<<<< HEAD
    public function index()
    {
        return Inertia::render('Admin/Index', [

            'totalUser' => User::count(),
            'totalSparepart' => Sparepart::count(),

            'sparepartBulanan' => Sparepart::whereMonth('created_at', now()->month)->count(),

            'latestSpareparts' => Sparepart::orderBy('id', 'desc')->take(5)->get(),

            'userPerMonth' => User::selectRaw('COUNT(*) as total, MONTH(created_at) as bulan')
=======
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
>>>>>>> 6ed2205f85b9826a31eae9b670fca7c4b7ec218c
                ->groupBy('bulan')
                ->orderBy('bulan')
                ->get(),

<<<<<<< HEAD
            'sparepartPerMonth' => Sparepart::selectRaw('COUNT(*) as total, MONTH(created_at) as bulan')
=======
            // === CHECKOUT PER MONTH === (riwayat checkout)
            'checkoutPerMonth' => Transaksi::selectRaw("
                    COUNT(*) as total,
                    MONTH(transaction_date) as bulan
                ")
>>>>>>> 6ed2205f85b9826a31eae9b670fca7c4b7ec218c
                ->groupBy('bulan')
                ->orderBy('bulan')
                ->get(),
        ]);
    }
<<<<<<< HEAD
=======

>>>>>>> 6ed2205f85b9826a31eae9b670fca7c4b7ec218c
}
