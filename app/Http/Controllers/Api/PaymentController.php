<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function history(Request $request)
    {
        $payments = Payment::where('user_id', $request->user()->id)
            ->latest()
            ->limit(50)
            ->get();

        $data = $payments->map(function ($p) {
            return [
                'id' => (string) $p->id,
                'reference' => $p->payment_reference,
                'purpose' => $p->purpose,
                'amount' => (float) $p->amount,
                'currency' => $p->currency,
                'method' => $p->method,
                'status' => $p->status,
                'created_at' => $p->created_at?->toIso8601String(),
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $data,
        ]);
    }
}
