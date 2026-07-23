<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\PremiumSubscription;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index(Request $request)
    {
        $query = Payment::with('user');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('purpose')) {
            $query->where('purpose', $request->purpose);
        }

        $payments = $query->latest()->paginate(20);
        $totalRevenue = Payment::where('status', 'completed')->sum('amount');
        $totalTransactions = Payment::count();
        $completedCount = Payment::where('status', 'completed')->count();

        return view('admin.payments.index', compact('payments', 'totalRevenue', 'totalTransactions', 'completedCount'));
    }

    public function subscriptions()
    {
        $subscriptions = PremiumSubscription::with('user')->latest()->paginate(20);
        return view('admin.payments.subscriptions', compact('subscriptions'));
    }
}
