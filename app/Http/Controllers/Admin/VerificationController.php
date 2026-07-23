<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\VerificationRequest;
use App\Models\Profile;
use Illuminate\Http\Request;

class VerificationController extends Controller
{
    public function index(Request $request)
    {
        $query = VerificationRequest::with('user');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        } else {
            $query->where('status', 'pending');
        }

        $requests = $query->latest()->paginate(20);

        return view('admin.verification.index', compact('requests'));
    }

    public function approve(VerificationRequest $verification)
    {
        $verification->update([
            'status' => 'approved',
            'reviewed_by' => auth()->id(),
            'reviewed_at' => now(),
        ]);

        Profile::where('user_id', $verification->user_id)->update(['is_verified' => true]);

        return back()->with('success', 'Verification approved');
    }

    public function reject(VerificationRequest $verification, Request $request)
    {
        $request->validate(['rejection_reason' => 'required|string']);

        $verification->update([
            'status' => 'rejected',
            'rejection_reason' => $request->rejection_reason,
            'reviewed_by' => auth()->id(),
            'reviewed_at' => now(),
        ]);

        return back()->with('success', 'Verification rejected');
    }
}
