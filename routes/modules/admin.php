<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AdminAuthController;
use App\Http\Controllers\WebsiteController;

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
| Routes for the admin module
*/

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminAuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AdminAuthController::class, 'login'])->name('login.submit');
    Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');

    // Protected routes - require authentication and admin type
    Route::middleware(['auth', 'can:access-admin'])->group(function () {
        Route::get('/dashboard', [AdminAuthController::class, 'showDashboard'])->name('dashboard');
        Route::get('/website', [WebsiteController::class, 'showWebsite'])->name('website');
        Route::post('/website', [WebsiteController::class, 'store'])->name('website.store');
        Route::post('/website/{id}/update', [WebsiteController::class, 'update'])->name('website.update');
        Route::post('/website/{id}/delete', [WebsiteController::class, 'destroy'])->name('website.delete');

        Route::get('/users', [AdminAuthController::class, 'showUsers'])->name('users');
        Route::post('/users', [AdminAuthController::class, 'createUser'])->name('users.create');
        Route::post('/users/{id}/update', [AdminAuthController::class, 'updateUser'])->name('users.update');
        Route::post('/users/{id}/delete', [AdminAuthController::class, 'deleteUser'])->name('users.delete');
    });
});
