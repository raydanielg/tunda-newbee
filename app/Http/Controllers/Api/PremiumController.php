<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\PremiumSubscription;
use App\Models\Wallet;
use App\Models\WalletTransaction;
use App\Models\Profile;
use Illuminate\Http\Request;

class PremiumController extends Controller
{
    public function plans()
    {
        return response()->json([
            'success' => true,
            'data' => [
                [
                    'plan' => 'monthly',
                    'name' => 'Monthly',
                    'price' => 15000,
                    'currency' => 'TZS',
                    'duration_days' => 30,
                    'features' => ['Unlimited swipes', 'See who liked you', '5 super likes/day', '1 boost/month', 'No ads'],
                ],
                [
                    'plan' => 'quarterly',
                    'name' => 'Quarterly',
                    'price' => 40000,
                    'currency' => 'TZS',
                    'duration_days' => 90,
                    'features' => ['Unlimited swipes', 'See who liked you', '10 super likes/day', '3 boosts/month', 'No ads', 'Advanced filters'],
                    'savings' => '11%',
                ],
                [
                    'plan' => 'annual',
                    'name' => 'Annual',
                    'price' => 120000,
                    'currency' => 'TZS',
                    'duration_days' => 365,
                    'features' => ['Unlimited swipes', 'See who liked you', 'Unlimited super likes', 'Unlimited boosts', 'No ads', 'Advanced filters', 'Priority support', 'Profile badge'],
                    'savings' => '33%',
                ],
            ],
        ]);
    }

    public function status(Request $request)
    {
        $sub = PremiumSubscription::where('user_id', $request->user()->id)
            ->where('status', 'active')
            ->where('ends_at', '>', now())
            ->latest()
            ->first();

        if (!$sub) {
            return response()->json([
                'success' => true,
                'data' => ['is_premium' => false],
            ]);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'is_premium' => true,
                'plan' => $sub->plan,
                'starts_at' => $sub->starts_at?->toIso8601String(),
                'ends_at' => $sub->ends_at?->toIso8601String(),
                'auto_renew' => (bool) $sub->auto_renew,
                'days_remaining' => $sub->ends_at->diffInDays(now()),
            ],
        ]);
    }

    public function subscribe(Request $request)
    {
        $request->validate([
            'plan' => 'required|in:monthly,quarterly,annual',
            'payment_method' => 'required|in:wallet,mobile_money,card',
        ]);

        $user = $request->user();
        $prices = ['monthly' => 15000, 'quarterly' => 40000, 'annual' => 120000];
        $durations = ['monthly' => 30, 'quarterly' => 90, 'annual' => 365];
        $amount = $prices[$request->plan];
        $days = $durations[$request->plan];

        // Check if already has active subscription
        $existing = PremiumSubscription::where('user_id', $user->id)
            ->where('status', 'active')
            ->where('ends_at', '>', now())
            ->first();

        if ($existing) {
            return response()->json([
                'success' => false,
                'message' => 'You already have an active premium subscription',
            ], 400);
        }

        if ($request->payment_method === 'wallet') {
            $wallet = $user->wallet;
            if (!$wallet || $wallet->balance < $amount) {
                return response()->json([
                    'success' => false,
                    'message' => 'Insufficient wallet balance',
                ], 400);
            }

            $wallet->balance -= $amount;
            $wallet->save();

            WalletTransaction::create([
                'wallet_id' => $wallet->id,
                'type' => 'debit',
                'amount' => $amount,
                'description' => "Premium subscription - {$request->plan}",
                'reason' => 'premium',
            ]);
        }

        // Create payment record
        Payment::create([
            'user_id' => $user->id,
            'payment_reference' => 'TND-PREM-' . strtoupper(uniqid()),
            'purpose' => 'premium',
            'amount' => $amount,
            'currency' => 'TZS',
            'method' => $request->payment_method,
            'status' => 'completed',
        ]);

        // Create subscription
        $sub = PremiumSubscription::create([
            'user_id' => $user->id,
            'plan' => $request->plan,
            'amount' => $amount,
            'currency' => 'TZS',
            'starts_at' => now(),
            'ends_at' => now()->addDays($days),
            'status' => 'active',
            'auto_renew' => $request->boolean('auto_renew'),
        ]);

        // Update profile
        Profile::where('user_id', $user->id)->update(['is_premium' => true]);

        return response()->json([
            'success' => true,
            'message' => 'Premium subscription activated!',
            'data' => [
                'plan' => $sub->plan,
                'ends_at' => $sub->ends_at->toIso8601String(),
                'days_remaining' => $days,
            ],
        ]);
    }

    public function cancel(Request $request)
    {
        $sub = PremiumSubscription::where('user_id', $request->user()->id)
            ->where('status', 'active')
            ->latest()
            ->first();

        if (!$sub) {
            return response()->json([
                'success' => false,
                'message' => 'No active subscription to cancel',
            ], 400);
        }

        $sub->update(['auto_renew' => false]);

        return response()->json([
            'success' => true,
            'message' => 'Auto-renewal cancelled. Premium remains active until ' . $sub->ends_at->format('M d, Y'),
        ]);
    }
}
