<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Admin\DashboardAdminController;
use App\Http\Controllers\SparepartController;

Route::middleware(['auth', 'isAdmin'])->group(function () {
    Route::get('/admin/dashboard', [DashboardAdminController::class, 'index'])
        ->name('admin.dashboard');
});

Route::get('/api/spareparts', [SparepartController::class, 'index']);
Route::get('/api/spareparts/{id}', [SparepartController::class, 'show']);


//Request
Route::get('/', function () {
    return view('welcome');
});


Route::get('/{any}', function () {
    return view('welcome');
})->where('any', '.*');