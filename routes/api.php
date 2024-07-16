<?php


use App\Http\Controllers\AuthController;
use App\Http\Controllers\Backend\AuthApiController;
use App\Http\Controllers\Backend\Seller\AuthApiController as SellerAuthApiController;
use App\Http\Controllers\Backend\Seller\SellerAccountApiController;
use App\Http\Controllers\Frontend\Admin\AuthController as AdminAuthController;
use App\Http\Controllers\Frontend\Admin\DashboardController;
use Illuminate\Support\Facades\Route;


Route::prefix('v1')->group(function () {

    //MasterDataController
    Route::prefix('master-data')->group(function () {
        // master data
    });

    Route::prefix('admin')->group(function () {

        // Login and Register
        Route::post('login', [AuthApiController::class, 'login'])->name('adminLogin');
        Route::post('logout', [AuthApiController::class, 'logout'])->name('adminLogout');
    });

    Route::prefix('seller')->group(function () {

        Route::post('/update/{id?}', [SellerAccountApiController::class, 'update'])->name('sellerUpdate');
    });


    Route::post('login/{id?}',[SellerAccountApiController::class, 'verifyOtp'])->name('sellerLogin');
    

    // Route::post('login', [SellerAuthApiController::class, 'login'])->name('login');
    // Route::post('logout', [SellerAuthApiController::class, 'logout'])->name('logout');
});
