<?php

use App\Http\Controllers\Auth\Admin\AuthController;
use App\Http\Controllers\Auth\Admin\DashboardController;

Route::prefix('admin/')->name('admin.')->group(function () {

    // Guest routes (login & register)
    Route::middleware(['guest:admin'])->group(function () {
        Route::get('login', [AuthController::class, 'showLoginForm'])->name('login');
        Route::post('login', [AuthController::class, 'login']);
    });

    // Authenticated routes
    Route::middleware(['auth:admin'])->group(function () {
        Route::redirect('/', '/admin/dashboard');
        Route::post('logout', [AuthController::class, 'logout'])->name('logout');
        Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
    });
});