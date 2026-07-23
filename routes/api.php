<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\DiscoverController;
use App\Http\Controllers\Api\SwipeController;
use App\Http\Controllers\Api\MatchController;
use App\Http\Controllers\Api\MessageController;
use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\Api\ProfileController;
use Illuminate\Support\Facades\Route;

// Auth routes (public)
Route::prefix('auth')->group(function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
    Route::post('forgot-password', [AuthController::class, 'forgotPassword']);
});

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    // Auth
    Route::get('auth/user', [AuthController::class, 'user']);
    Route::post('auth/logout', [AuthController::class, 'logout']);

    // Discover
    Route::get('discover', [DiscoverController::class, 'profiles']);

    // Swipes
    Route::post('swipe', [SwipeController::class, 'swipe']);

    // Matches
    Route::get('matches', [MatchController::class, 'index']);

    // Messages
    Route::get('conversations', [MessageController::class, 'conversations']);
    Route::get('conversations/{id}/messages', [MessageController::class, 'messages']);
    Route::post('conversations/{id}/send', [MessageController::class, 'send']);

    // Notifications
    Route::get('notifications', [NotificationController::class, 'index']);
    Route::patch('notifications/{id}/read', [NotificationController::class, 'markRead']);
    Route::patch('notifications/read-all', [NotificationController::class, 'markAllRead']);

    // Profile
    Route::get('profile', [ProfileController::class, 'show']);
    Route::patch('profile', [ProfileController::class, 'update']);
});
