<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\UserMatch;
use App\Models\Conversation;
use App\Models\Message;
use Illuminate\Http\Request;

class MatchController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        $matches = UserMatch::where('is_active', true)
            ->where(function ($q) use ($user) {
                $q->where('user1_id', $user->id)
                  ->orWhere('user2_id', $user->id);
            })
            ->with([
                'user1.profile',
                'user2.profile',
                'conversation.messages' => function ($q) {
                    $q->latest()->limit(1);
                },
            ])
            ->latest()
            ->get();

        $data = $matches->map(function ($match) use ($user) {
            $otherUser = $match->user1_id === $user->id ? $match->user2 : $match->user1;
            $lastMsg = $match->conversation?->messages->first();

            $unreadCount = 0;
            if ($match->conversation) {
                $unreadCount = Message::where('conversation_id', $match->conversation->id)
                    ->where('sender_id', '!=', $user->id)
                    ->whereNull('read_at')
                    ->count();
            }

            return [
                'id' => (string) $match->id,
                'user' => app(DiscoverController::class)->formatProfile($otherUser),
                'matched_at' => $match->matched_at?->toIso8601String(),
                'last_message' => $lastMsg?->body,
                'last_message_time' => $lastMsg?->created_at?->toIso8601String(),
                'unread_count' => $unreadCount,
                'conversation_id' => $match->conversation?->id,
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $data,
        ]);
    }
}
