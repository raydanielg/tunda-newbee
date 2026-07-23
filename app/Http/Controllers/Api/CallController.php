<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Call;
use Illuminate\Http\Request;

class CallController extends Controller
{
    public function history(Request $request)
    {
        $calls = Call::where('caller_id', $request->user()->id)
            ->orWhere('receiver_id', $request->user()->id)
            ->with(['caller', 'receiver'])
            ->latest()
            ->limit(50)
            ->get();

        $data = $calls->map(function ($call) use ($request) {
            $otherUser = $call->caller_id === $request->user()->id ? $call->receiver : $call->caller;
            return [
                'id' => (string) $call->id,
                'type' => $call->type,
                'status' => $call->status,
                'duration' => $call->duration_seconds,
                'started_at' => $call->started_at?->toIso8601String(),
                'ended_at' => $call->ended_at?->toIso8601String(),
                'is_caller' => $call->caller_id === $request->user()->id,
                'other_user' => [
                    'id' => (string) $otherUser->id,
                    'name' => $otherUser->name,
                    'avatar' => $otherUser->avatar,
                ],
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $data,
        ]);
    }

    public function initiate(Request $request)
    {
        $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'type' => 'required|in:voice,video',
        ]);

        $call = Call::create([
            'caller_id' => $request->user()->id,
            'receiver_id' => $request->receiver_id,
            'type' => $request->type,
            'status' => 'ringing',
            'started_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Call initiated',
            'data' => ['id' => (string) $call->id],
        ], 201);
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:accepted,declined,missed,ended',
            'duration_seconds' => 'nullable|integer',
        ]);

        $call = Call::where('id', $id)
            ->where(function ($q) use ($request) {
                $q->where('caller_id', $request->user()->id)
                  ->orWhere('receiver_id', $request->user()->id);
            })->firstOrFail();

        $call->update([
            'status' => $request->status,
            'duration_seconds' => $request->duration_seconds ?? $call->duration_seconds,
            'ended_at' => in_array($request->status, ['ended', 'declined', 'missed']) ? now() : $call->ended_at,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Call status updated',
        ]);
    }
}
