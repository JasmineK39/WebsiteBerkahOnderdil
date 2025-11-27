<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SparepartController;
use App\Http\Controllers\Admin\SparepartController as AdminSparepartController;
use App\Http\Controllers\Admin\ModelMobilController as AdminModelMobilController;
use App\Http\Controllers\Api\CarController;
use App\Http\Controllers\SparepartRequestController;
use App\Http\Controllers\Api\ModelMobilController;
use App\Http\Controllers\AuthController;

// Tes route awal
Route::get('/test', function () {
    return response()->json(['message' => 'API is working!']);
});

Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

// Route utama sparepart
Route::get('/spareparts', [SparepartController::class, 'index']);
Route::get('/spareparts/{id}', [SparepartController::class, 'show']);
Route::get('/cars', [CarController::class, 'index']);
Route::get('/brands', [ModelMobilController::class, 'getBrands']);
Route::get('/models/{brand}', [ModelMobilController::class, 'getModelsByBrand']);


Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();});
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/request-sparepart', [SparepartRequestController::class, 'apiIndex']);
    Route::post('/request-sparepart', [SparepartRequestController::class, 'apiStore']);

Route::middleware(['auth:sanctum', 'admin'])->prefix('admin')->group(function () {

    // CRUD Sparepart
    Route::get('/spareparts', [AdminSparepartController::class, 'index']);
    Route::get('/spareparts/{id}', [AdminSparepartController::class, 'show']);
    Route::post('/spareparts', [AdminSparepartController::class, 'store']);
    Route::put('/spareparts/{id}', [AdminSparepartController::class, 'update']);
    Route::delete('/spareparts/{id}', [AdminSparepartController::class, 'destroy']);

    // CRUD Model Mobil (tanpa delete)
    Route::get('/models', [AdminModelMobilController::class, 'index']);
    Route::get('/models/{id}', [AdminModelMobilController::class, 'show']);
    Route::post('/models', [AdminModelMobilController::class, 'store']);
    Route::put('/models/{id}', [AdminModelMobilController::class, 'update']);
});
});
