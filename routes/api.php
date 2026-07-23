<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\DiscoverController;
use App\Http\Controllers\Api\SwipeController;
use App\Http\Controllers\Api\MatchController;
use App\Http\Controllers\Api\MessageController;
use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\StoryController;
use App\Http\Controllers\Api\CallController;
use App\Http\Controllers\Api\BoostController;
use App\Http\Controllers\Api\BlockController;
use App\Http\Controllers\Api\ReportController;
use App\Http\Controllers\Api\VerificationController;
use App\Http\Controllers\Api\WalletController;
use App\Http\Controllers\Api\PaymentController;
use App\Http\Controllers\Api\PremiumController;
use App\Http\Controllers\Api\InterestController;
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
    Route::patch('auth/last-active', [AuthController::class, 'updateLastActive']);

    // Discover & Swipes
    Route::get('discover', [DiscoverController::class, 'profiles']);
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

    // Interests
    Route::get('interests', [InterestController::class, 'index']);
    Route::get('interests/mine', [InterestController::class, 'userInterests']);
    Route::put('interests', [InterestController::class, 'update']);

    // Stories
    Route::get('stories', [StoryController::class, 'index']);
    Route::post('stories', [StoryController::class, 'store']);
    Route::post('stories/{id}/view', [StoryController::class, 'view']);
    Route::delete('stories/{id}', [StoryController::class, 'destroy']);

    // Calls
    Route::get('calls', [CallController::class, 'history']);
    Route::post('calls/initiate', [CallController::class, 'initiate']);
    Route::patch('calls/{id}/status', [CallController::class, 'updateStatus']);

    // Boosts
    Route::get('boosts/active', [BoostController::class, 'active']);
    Route::post('boosts/activate', [BoostController::class, 'activate']);

    // Blocks
    Route::get('blocks', [BlockController::class, 'index']);
    Route::post('blocks', [BlockController::class, 'store']);
    Route::delete('blocks/{blockedId}', [BlockController::class, 'destroy']);

    // Reports
    Route::post('reports', [ReportController::class, 'store']);

    // Verification
    Route::get('verification/status', [VerificationController::class, 'status']);
    Route::post('verification/submit', [VerificationController::class, 'submit']);

    // Wallet
    Route::get('wallet', [WalletController::class, 'show']);
    Route::get('wallet/transactions', [WalletController::class, 'transactions']);
    Route::post('wallet/topup', [WalletController::class, 'topup']);

    // Payments
    Route::get('payments/history', [PaymentController::class, 'history']);

    // Premium
    Route::get('premium/plans', [PremiumController::class, 'plans']);
    Route::get('premium/status', [PremiumController::class, 'status']);
    Route::post('premium/subscribe', [PremiumController::class, 'subscribe']);
    Route::post('premium/cancel', [PremiumController::class, 'cancel']);
});
