<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserMatch;
use App\Models\Payment;
use App\Models\Report;
use App\Models\VerificationRequest;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_users' => User::where('role', 'user')->count(),
            'active_users' => User::where('role', 'user')->where('status', 'active')->count(),
            'premium_users' => User::whereHas('profile', fn($q) => $q->where('is_premium', true))->count(),
            'verified_users' => User::whereHas('profile', fn($q) => $q->where('is_verified', true))->count(),
            'total_matches' => UserMatch::count(),
            'active_matches' => UserMatch::where('is_active', true)->count(),
            'total_revenue' => Payment::where('status', 'completed')->sum('amount'),
            'pending_reports' => Report::where('status', 'pending')->count(),
            'pending_verifications' => VerificationRequest::where('status', 'pending')->count(),
            'new_users_today' => User::where('role', 'user')->whereDate('created_at', today())->count(),
            'new_users_week' => User::where('role', 'user')->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count(),
            'male_users' => User::where('role', 'user')->where('gender', 'male')->count(),
            'female_users' => User::where('role', 'user')->where('gender', 'female')->count(),
        ];

        $recentUsers = User::where('role', 'user')->latest()->limit(8)->get();
        $recentReports = Report::with(['reporter', 'reported'])->where('status', 'pending')->latest()->limit(5)->get();
        $recentVerifications = VerificationRequest::with('user')->where('status', 'pending')->latest()->limit(5)->get();

        return view('admin.dashboard', compact('stats', 'recentUsers', 'recentReports', 'recentVerifications'));
    }
}
