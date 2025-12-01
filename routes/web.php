<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardAdminController;
use App\Http\Controllers\SparepartController;
use App\Http\Controllers\AuthController;


Route::get('/auth/google/redirect', [AuthController::class, 'google_redirect']);
Route::get('/auth/google/callback', [AuthController::class, 'google_callback']);

Route::middleware(['auth', 'isAdmin'])->group(function () {
    Route::get('/admin/dashboard', [DashboardAdminController::class, 'index'])
        ->name('admin.dashboard');
});

//Request
Route::get('/', function () {
    return view('welcome');
});


Route::get('/{any}', function () {
    return view('welcome');
})->where('any', '.*');