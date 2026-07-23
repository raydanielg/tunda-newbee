<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Wallet;
use App\Models\WalletTransaction;
use App\Models\Payment;
use Illuminate\Http\Request;

class WalletController extends Controller
{
    public function show(Request $request)
    {
        $wallet = $request->user()->wallet;

        if (!$wallet) {
            $wallet = Wallet::create([
                'user_id' => $request->user()->id,
                'balance' => 0,
                'currency' => 'TZS',
            ]);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'id' => (string) $wallet->id,
                'balance' => (float) $wallet->balance,
                'currency' => $wallet->currency,
            ],
        ]);
    }

    public function transactions(Request $request)
    {
        $wallet = $request->user()->wallet;

        if (!$wallet) {
            return response()->json([
                'success' => true,
                'data' => [],
            ]);
        }

        $transactions = $wallet->transactions()->latest()->limit(50)->get();

        $data = $transactions->map(function ($t) {
            return [
                'id' => (string) $t->id,
                'type' => $t->type,
                'amount' => (float) $t->amount,
                'description' => $t->description,
                'reason' => $t->reason,
                'created_at' => $t->created_at?->toIso8601String(),
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $data,
        ]);
    }

    public function topup(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1000',
            'method' => 'required|in:mobile_money,card,bank',
            'payment_reference' => 'nullable|string',
        ]);

        $user = $request->user();
        $wallet = $user->wallet ?? Wallet::create([
            'user_id' => $user->id,
            'balance' => 0,
            'currency' => 'TZS',
        ]);

        // Create payment record
        $payment = Payment::create([
            'user_id' => $user->id,
            'payment_reference' => $request->payment_reference ?? 'TND-' . strtoupper(uniqid()),
            'purpose' => 'wallet_topup',
            'amount' => $request->amount,
            'currency' => 'TZS',
            'method' => $request->method,
            'status' => 'completed',
        ]);

        // Credit wallet
        $wallet->balance += $request->amount;
        $wallet->save();

        WalletTransaction::create([
            'wallet_id' => $wallet->id,
            'type' => 'credit',
            'amount' => $request->amount,
            'description' => 'Wallet top-up via ' . $request->method,
            'reason' => 'wallet_topup',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Wallet topped up successfully',
            'data' => [
                'new_balance' => (float) $wallet->balance,
                'payment_id' => (string) $payment->id,
            ],
        ]);
    }
}
