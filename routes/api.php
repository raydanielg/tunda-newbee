<?php

use App\Http\Controllers\Api\AuthController;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->group(function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
    Route::post('forgot-password', [AuthController::class, 'forgotPassword']);
});

Route::middleware('auth:sanctum')->group(function () {
    Route::get('auth/user', [AuthController::class, 'user']);
    Route::post('auth/logout', [AuthController::class, 'logout']);
});
