<?php

use App\Http\Controllers\VideoController;
use Illuminate\Support\Facades\Route;

Route::controller(VideoController::class)->group(function () {
    // Homepage
    Route::get('/', 'home')
        ->name('index');

    // Search Video Page
    Route::get('/cari-video', 'searchVideo')
        ->name('search-video');

    // Post Video Page in Profile - Admin
    Route::middleware(['auth', 'can:isAdmin'])
        ->prefix('post-video')
        ->name('post.')
        ->group(function () {
            Route::get('/', 'postVideo')
                ->name('create');
        
            Route::post('/', 'storeVideo')
                ->name('store');
    });

    // Dedicated video Page
    Route::get('/video/{video}', 'show')
        ->name('video.show');

    // Save Video button
    Route::post('/video/{video}/simpan', 'saveVideo')
        ->middleware('auth')
        ->name('video.save');

    // Save Video To Watchlist button
    Route::post('/video/{video}/watchlist', 'saveVideoToWatchlist')
        ->middleware('auth')
        ->name('video.save.watchlist');
});