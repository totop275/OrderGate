<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Master\ProductController;
use App\Http\Controllers\Order\OrderController;
use App\Http\Controllers\Master\CustomerController;
use App\Http\Controllers\Master\UserController;
use App\Http\Controllers\Master\RoleController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/', [HomeController::class, 'landing'])->name('landing');
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    Route::get('/dashboard', [DashboardController::class, 'dashboard'])
        ->name('dashboard')
        ->middleware('can:dashboard');

    Route::resource('orders', OrderController::class)->except(['edit', 'destroy', 'store', 'update']);
    Route::resource('products', ProductController::class)->except(['show']);
    Route::resource('customers', CustomerController::class)->except(['show']);
    Route::resource('users', UserController::class)->except(['show']);
    Route::resource('roles', RoleController::class)->except(['show']);
});

Route::middleware(['guest', 'web'])->group(function () {
    Route::get('/login', [LoginController::class, 'loginView'])->name('login_view');
    Route::post('/login', [LoginController::class, 'login'])->name('login');
});