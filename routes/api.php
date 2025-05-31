<?php

use App\Http\Controllers\Master\CustomerApiController;
use App\Http\Controllers\Master\ProductApiController;
use App\Http\Controllers\Order\OrderApiController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->as('api.')->group(function () {
    Route::apiResource('customers', CustomerApiController::class);
    Route::apiResource('products', ProductApiController::class);
    Route::apiResource('orders', OrderApiController::class);
});