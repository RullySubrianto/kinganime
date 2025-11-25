<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->controller(AuthController::class)->group(function () {
    Route::get('daftar', 'registerCreate')
        ->name('register');

    Route::post('daftar', 'registerStore')
        ->name('register.store');

    Route::get('masuk', 'loginCreate')
        ->name('login');

    Route::post('masuk', 'loginStore')
        ->name('login.store');
});

Route::middleware('auth')->controller(AuthController::class)->group(function () {
    Route::post('logout', 'logout')
        ->name('logout');
});