<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\SparepartController;
use App\Http\Controllers\AuthController;

Route::get('/auth/google/redirect', [AuthController::class, 'google_redirect']);
Route::get('/auth/google/callback', [AuthController::class, 'google_callback']);

//Request
Route::get('/', function () {
    return view('welcome');
});


Route::get('/{any}', function () {
    return view('welcome');
})->where('any', '.*');