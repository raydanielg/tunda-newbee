<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\VerificationRequest;
use App\Models\Profile;
use Illuminate\Http\Request;

class VerificationController extends Controller
{
    public function status(Request $request)
    {
        $latest = VerificationRequest::where('user_id', $request->user()->id)
            ->latest()
            ->first();

        if (!$latest) {
            return response()->json([
                'success' => true,
                'data' => ['status' => 'none'],
            ]);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'id' => (string) $latest->id,
                'status' => $latest->status,
                'rejection_reason' => $latest->rejection_reason,
                'submitted_at' => $latest->created_at?->toIso8601String(),
                'reviewed_at' => $latest->reviewed_at?->toIso8601String(),
            ],
        ]);
    }

    public function submit(Request $request)
    {
        $request->validate([
            'id_front_path' => 'required|string',
            'id_back_path' => 'required|string',
            'selfie_path' => 'required|string',
        ]);

        $user = $request->user();

        $pending = VerificationRequest::where('user_id', $user->id)
            ->where('status', 'pending')
            ->exists();

        if ($pending) {
            return response()->json([
                'success' => false,
                'message' => 'You already have a pending verification request',
            ], 400);
        }

        $verification = VerificationRequest::create([
            'user_id' => $user->id,
            'id_front_path' => $request->id_front_path,
            'id_back_path' => $request->id_back_path,
            'selfie_path' => $request->selfie_path,
            'status' => 'pending',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Verification submitted. Review typically takes 24-48 hours.',
            'data' => ['id' => (string) $verification->id],
        ], 201);
    }
}
