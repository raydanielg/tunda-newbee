<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Report;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'reported_id' => 'required|exists:users,id',
            'reason' => 'required|in:harassment,fake_profile,inappropriate_content,spam,scam,other',
            'description' => 'nullable|string|max:1000',
        ]);

        $user = $request->user();

        if ($user->id == $request->reported_id) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot report yourself',
            ], 400);
        }

        $existing = Report::where('reporter_id', $user->id)
            ->where('reported_id', $request->reported_id)
            ->where('status', 'pending')
            ->exists();

        if ($existing) {
            return response()->json([
                'success' => false,
                'message' => 'You already have a pending report against this user',
            ], 400);
        }

        Report::create([
            'reporter_id' => $user->id,
            'reported_id' => $request->reported_id,
            'reason' => $request->reason,
            'description' => $request->description,
            'status' => 'pending',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Report submitted. Our team will review it.',
        ], 201);
    }
}
