<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Boost;
use Illuminate\Http\Request;

class BoostController extends Controller
{
    public function active(Request $request)
    {
        $boost = Boost::where('user_id', $request->user()->id)
            ->where('is_active', true)
            ->where('ends_at', '>', now())
            ->first();

        if (!$boost) {
            return response()->json([
                'success' => true,
                'data' => null,
            ]);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'id' => (string) $boost->id,
                'starts_at' => $boost->starts_at?->toIso8601String(),
                'ends_at' => $boost->ends_at?->toIso8601String(),
                'impressions' => $boost->impressions,
                'time_remaining' => $boost->ends_at->diffInSeconds(now()),
            ],
        ]);
    }

    public function activate(Request $request)
    {
        $user = $request->user();

        $existing = Boost::where('user_id', $user->id)
            ->where('is_active', true)
            ->where('ends_at', '>', now())
            ->first();

        if ($existing) {
            return response()->json([
                'success' => false,
                'message' => 'You already have an active boost',
            ], 400);
        }

        // Check wallet balance (boost costs 2000 TZS)
        $wallet = $user->wallet;
        if (!$wallet || $wallet->balance < 2000) {
            return response()->json([
                'success' => false,
                'message' => 'Insufficient wallet balance. Boost costs 2,000 TZS.',
            ], 400);
        }

        $wallet->balance -= 2000;
        $wallet->save();

        \App\Models\WalletTransaction::create([
            'wallet_id' => $wallet->id,
            'type' => 'debit',
            'amount' => 2000,
            'description' => 'Profile boost purchase',
            'reason' => 'boost',
        ]);

        $boost = Boost::create([
            'user_id' => $user->id,
            'starts_at' => now(),
            'ends_at' => now()->addMinutes(30),
            'impressions' => 0,
            'is_active' => true,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Boost activated for 30 minutes',
            'data' => [
                'id' => (string) $boost->id,
                'ends_at' => $boost->ends_at->toIso8601String(),
            ],
        ]);
    }
}
