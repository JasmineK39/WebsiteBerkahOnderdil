<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Sparepart;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class DashboardAdminController extends Controller
{
    public function index()
    {
        return Inertia::render('Admin/Index', [

            'totalUser' => User::count(),
            'totalSparepart' => Sparepart::count(),

            'sparepartBulanan' => Sparepart::whereMonth('created_at', now()->month)->count(),

            'latestSpareparts' => Sparepart::orderBy('id', 'desc')->take(5)->get(),

            'userPerMonth' => User::selectRaw('COUNT(*) as total, MONTH(created_at) as bulan')
                ->groupBy('bulan')
                ->orderBy('bulan')
                ->get(),

            'sparepartPerMonth' => Sparepart::selectRaw('COUNT(*) as total, MONTH(created_at) as bulan')
                ->groupBy('bulan')
                ->orderBy('bulan')
                ->get(),
        ]);
    }
}
