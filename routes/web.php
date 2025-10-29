<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\SparepartController;

Route::get('/api/spareparts', [SparepartController::class, 'index']);
Route::get('/api/spareparts/{id}', [SparepartController::class, 'show']);


Route::get('/', function () {
    return view('welcome');
});
