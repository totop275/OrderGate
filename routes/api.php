<?php

use App\Http\Controllers\Master\CustomerApiController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->as('api.')->group(function () {
    Route::apiResource('customers', CustomerApiController::class);
});