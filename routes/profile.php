<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->controller(ProfileController::class)->group(function () {
    Route::prefix('profile')
        ->name('profile.')
        ->group(function() {
            // Display Profile Page
            Route::get('/', 'index')
                ->name('index');

            // Update Profile
            Route::post('/', 'updateProfile')
                ->name('update');

            // Update Password
            Route::post('/ubah-password', 'updatePassword')
                ->name('update-password');
        });

    // Control User Page - Admin
    Route::middleware('can:isAdmin')
        ->prefix('users')
        ->name('users.')
        ->group(function () {
            // Display Control User Page
            Route::get('/', 'controlUsers')
                ->name('index');

            // Display Edit Page
            Route::get('/{user}', 'editControlUsers')
                ->name('edit');
        
            // Update User
            Route::put('/{user}', 'updateControlUsers')
                ->name('update');
        
            // Delete User
            Route::delete('/{user}', 'destroyControlUsers')
                ->name('destroy');
    });

    // All Video Page - Admin
    Route::middleware('can:isAdmin')
        ->prefix('semua-video')
        ->name('all-video.')
        ->group(function () {
            // Display All Video Page
            Route::get('/', 'allVideo')
                ->name('index');

            // Display Edit Page
            Route::get('/{video}', 'editVideoAllVideo')
                ->name('edit');

            // Update Video
            Route::put('/{video}', 'updateVideoAllVideo')
                ->name('update');
        
            // Delete Video
            Route::delete('/{video}', 'destroyVideoAllVideo')
                ->name('destroy');
    });

    // Saved Video Page
    Route::get('/video-tersimpan', 'savedVideo')
        ->name('saved-video.index');

});

Route::controller(ProfileController::class)->group(function () {
    // History Page
    Route::get('/history', 'history')
        ->name('history.index');

    // History Page for Guest
    Route::get('/history-guest', 'historyGuest')
        ->name('history-guest.index');
});