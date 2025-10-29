<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SparepartController;

// Tes route awal
Route::get('/test', function () {
    return response()->json(['message' => 'API is working!']);
});

// Route utama sparepart
Route::get('/spareparts', [SparepartController::class, 'index']);
Route::get('/spareparts/{id}', [SparepartController::class, 'show']);
