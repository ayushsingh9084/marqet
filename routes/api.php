<?php


use App\Http\Controllers\AuthController;
use App\Http\Controllers\Backend\AuthApiController;
use App\Http\Controllers\Frontend\Admin\AuthController as AdminAuthController;
use App\Http\Controllers\Frontend\Admin\DashboardController;
use Illuminate\Support\Facades\Route;


Route::prefix('v1')->group(function () {

    //MasterDataController
    Route::prefix('master-data')->group(function () {

        // //CityApiController
        // Route::get("cities/read", [CityApiController::class, "read"]);
        // Route::post("cities/search", [CityApiController::class, "search"]);
        // Route::get("cities/{id}", [CityApiController::class, "readOne"]);
        // Route::middleware('auth:sanctum', 'ability:c-city')->post("cities/create", [CityApiController::class, "create"]);
        // Route::middleware('auth:sanctum', 'ability:u-city')->put('/cities/{id}', [CityApiController::class, "update"]);
        // Route::middleware('auth:sanctum', 'ability:d-city')->delete('/cities/{id}', [CityApiController::class, "delete"]);

        // //StateApiController
        // Route::get("states/read", [StateApiController::class, "read"]);
        // Route::post("states/search", [StateApiController::class, "search"]);
        // Route::get("states/{id}", [StateApiController::class, "readOne"]);
        // Route::middleware('auth:sanctum', 'ability:c-state')->post("states/create", [StateApiController::class, "create"]);
        // Route::middleware('auth:sanctum', 'ability:u-state')->put('/states/{id}', [StateApiController::class, "update"]);
        // Route::middleware('auth:sanctum', 'ability:d-state')->delete('/states/{id}', [StateApiController::class, "delete"]);

        // Route::get("countries/read", [CountryApiController::class, "read"]);
        // Route::get("countries/{id}", [CountryApiController::class, "readOne"]);
        // Route::middleware('auth:sanctum', 'ability:c-country')->post("countries/create", [CountryApiController::class, "create"]);
        // Route::middleware('auth:sanctum', 'ability:u-country')->put('/countries/{id}', [CountryApiController::class, "update"]);
        // Route::middleware('auth:sanctum', 'ability:d-country')->delete('/countries/{id}', [CountryApiController::class, "delete"]);
    });

    Route::prefix('admin')->group(function () {

        // Login and Register
        Route::post('login', [AuthApiController::class, 'login'])->name('login');
        Route::post('logout', [AuthApiController::class, 'logout'])->name('logout');        
    });


});