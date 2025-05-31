<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Dashboard\DashboardApiController;
use App\Http\Controllers\Master\CustomerApiController;
use App\Http\Controllers\Master\ProductApiController;
use App\Http\Controllers\Master\RoleApiController;
use App\Http\Controllers\Master\UserApiController;
use App\Http\Controllers\Order\OrderApiController;
use Illuminate\Support\Facades\Route;

Route::post('token', [LoginController::class, 'generateToken'])->name('api.login');

Route::middleware('auth:sanctum')->as('api.')->group(function () {
    Route::get('me', [LoginController::class, 'me'])->name('me');
    Route::post('logout', [LoginController::class, 'logoutApi'])
        ->name('logout');

    Route::get('dashboard', [DashboardApiController::class, 'dashboard'])
        ->middleware('can:dashboard')
        ->name('dashboard');

    Route::apiResource('customers', CustomerApiController::class);
    Route::apiResource('products', ProductApiController::class);
    Route::apiResource('roles', RoleApiController::class);
    Route::apiResource('users', UserApiController::class);
    Route::apiResource('orders', OrderApiController::class)->except(['destroy']);
});