<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Conversation;
use App\Models\Message;
use App\Models\AppNotification;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function conversations(Request $request)
    {
        $user = $request->user();

        $conversations = Conversation::whereHas('match', function ($q) use ($user) {
            $q->where('is_active', true)
              ->where(function ($q2) use ($user) {
                  $q2->where('user1_id', $user->id)
                     ->orWhere('user2_id', $user->id);
              });
        })
        ->with(['match.user1.profile', 'match.user2.profile', 'messages' => function ($q) {
            $q->latest()->limit(1);
        }])
        ->orderByDesc('last_message_at')
        ->get();

        $data = $conversations->map(function ($conv) use ($user) {
            $otherUser = $conv->match->user1_id === $user->id ? $conv->match->user2 : $conv->match->user1;
            $lastMsg = $conv->messages->first();

            $unreadCount = Message::where('conversation_id', $conv->id)
                ->where('sender_id', '!=', $user->id)
                ->whereNull('read_at')
                ->count();

            return [
                'id' => (string) $conv->id,
                'user' => app(DiscoverController::class)->formatProfile($otherUser),
                'last_message' => $lastMsg?->body,
                'last_message_time' => $lastMsg?->created_at?->toIso8601String(),
                'unread_count' => $unreadCount,
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $data,
        ]);
    }

    public function messages(Request $request, $conversationId)
    {
        $conversation = Conversation::findOrFail($conversationId);

        $user = $request->user();
        $match = $conversation->match;

        if ($match->user1_id !== $user->id && $match->user2_id !== $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized',
            ], 403);
        }

        Message::where('conversation_id', $conversationId)
            ->where('sender_id', '!=', $user->id)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        $messages = $conversation->messages()
            ->orderBy('created_at')
            ->get();

        $data = $messages->map(function ($msg) use ($user) {
            return [
                'id' => (string) $msg->id,
                'sender_id' => (string) $msg->sender_id,
                'text' => $msg->body,
                'time' => $msg->created_at?->toIso8601String(),
                'is_me' => $msg->sender_id === $user->id,
                'read' => $msg->read_at !== null,
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $data,
        ]);
    }

    public function send(Request $request, $conversationId)
    {
        $request->validate(['body' => 'required|string|max:1000']);

        $conversation = Conversation::findOrFail($conversationId);
        $user = $request->user();
        $match = $conversation->match;

        if ($match->user1_id !== $user->id && $match->user2_id !== $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized',
            ], 403);
        }

        $otherUserId = $match->user1_id === $user->id ? $match->user2_id : $match->user1_id;

        $message = Message::create([
            'conversation_id' => $conversationId,
            'sender_id' => $user->id,
            'body' => $request->body,
            'type' => 'text',
        ]);

        $conversation->update(['last_message_at' => now()]);

        AppNotification::create([
            'user_id' => $otherUserId,
            'type' => 'message',
            'title' => 'New Message',
            'body' => "{$user->name} sent you a message.",
        ]);

        return response()->json([
            'success' => true,
            'data' => [
                'id' => (string) $message->id,
                'sender_id' => (string) $message->sender_id,
                'text' => $message->body,
                'time' => $message->created_at?->toIso8601String(),
                'is_me' => true,
                'read' => false,
            ],
        ]);
    }
}
