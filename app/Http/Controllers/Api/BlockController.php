<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Block;
use App\Models\Report;
use Illuminate\Http\Request;

class BlockController extends Controller
{
    public function index(Request $request)
    {
        $blocks = Block::where('blocker_id', $request->user()->id)
            ->with('blocked')
            ->latest()
            ->get();

        $data = $blocks->map(function ($block) {
            return [
                'id' => (string) $block->id,
                'user' => [
                    'id' => (string) $block->blocked->id,
                    'name' => $block->blocked->name,
                    'avatar' => $block->blocked->avatar,
                ],
                'created_at' => $block->created_at?->toIso8601String(),
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $data,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate(['blocked_id' => 'required|exists:users,id']);

        $user = $request->user();

        if ($user->id == $request->blocked_id) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot block yourself',
            ], 400);
        }

        $block = Block::firstOrCreate([
            'blocker_id' => $user->id,
            'blocked_id' => $request->blocked_id,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'User blocked',
        ]);
    }

    public function destroy(Request $request, $blockedId)
    {
        Block::where('blocker_id', $request->user()->id)
            ->where('blocked_id', $blockedId)
            ->delete();

        return response()->json([
            'success' => true,
            'message' => 'User unblocked',
        ]);
    }
}
