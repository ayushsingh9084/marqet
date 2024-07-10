<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\Frontend\Admin\AuthController as AdminAuthController;
use App\Http\Controllers\Frontend\Admin\DashboardController;
use Illuminate\Support\Facades\Route;

//Admin Routes
Route::prefix('admin')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('adminDashboard');

    // Auth route
    Route::get('/login', [AdminAuthController::class, 'login'])->name('adminLogin');
    Route::post('/login/request', [AdminAuthController::class, 'loginRequest'])->name('adminLogin.request');
    Route::get('/logout', [AdminAuthController::class, 'logout'])->name('adminLogout');
    
    
});

Route::get('login', [AuthController::class, 'login'])->name('login');
Route::post('/login/request', [AuthController::class, 'loginRequest'])->name('login/request');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
