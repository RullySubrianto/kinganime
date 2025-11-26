<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// Auth
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

            // Unblock User
            Route::get('/unblock/{user}', 'unblockControlUsers')
                ->name('unblock');

            // Block User
            Route::get('/block/{user}', 'blockControlUsers')
                ->name('block');

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

    // Category Page - Admin
    Route::middleware('can:isAdmin')
        ->prefix('kategori')
        ->name('category.')
        ->group(function () {
            // Display All Video Page
            Route::get('/', 'indexCategory')
                ->name('index');

            // Display Edit Page
            Route::get('/edit/{category}', 'editCategory')
                ->name('edit');

            // Update Video
            Route::put('/{category}', 'updateCategory')
                ->name('update');
        
            // Delete Video
            Route::delete('/{category}', 'destroyCategory')
                ->name('destroy');

            // Show Create Category Page
            Route::get('/create', 'createCategory')
                ->name('create');
        
            // Store Category
            Route::post('/', 'storeCategory')
                ->name('store');
    });


    // Saved Video Page
    Route::get('/video-tersimpan', 'savedVideo')
        ->name('saved-video.index');

});

// Guest
Route::controller(ProfileController::class)->group(function () {
    // History Page
    Route::get('/history', 'history')
        ->name('history.index');

    // History Page for Guest
    Route::get('/history-guest', 'historyGuest')
        ->name('history-guest.index');
});