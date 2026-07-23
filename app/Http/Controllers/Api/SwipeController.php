<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Swipe;
use App\Models\UserMatch;
use App\Models\AppNotification;
use Illuminate\Http\Request;

class SwipeController extends Controller
{
    public function swipe(Request $request)
    {
        $request->validate([
            'swiped_id' => 'required|exists:users,id',
            'action' => 'required|in:like,dislike,super_like',
        ]);

        $user = $request->user();
        $swipedId = $request->swiped_id;
        $action = $request->action;

        if ($user->id == $swipedId) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot swipe yourself',
            ], 400);
        }

        $existing = Swipe::where('swiper_id', $user->id)
            ->where('swiped_id', $swipedId)
            ->first();

        if ($existing) {
            return response()->json([
                'success' => false,
                'message' => 'Already swiped this user',
            ], 400);
        }

        Swipe::create([
            'swiper_id' => $user->id,
            'swiped_id' => $swipedId,
            'action' => $action,
        ]);

        $matched = false;

        if ($action === 'like' || $action === 'super_like') {
            $reverseSwipe = Swipe::where('swiper_id', $swipedId)
                ->where('swiped_id', $user->id)
                ->whereIn('action', ['like', 'super_like'])
                ->first();

            if ($reverseSwipe) {
                $user1Id = min($user->id, $swipedId);
                $user2Id = max($user->id, $swipedId);

                $existingMatch = UserMatch::where('user1_id', $user1Id)
                    ->where('user2_id', $user2Id)
                    ->first();

                if (!$existingMatch) {
                    UserMatch::create([
                        'user1_id' => $user1Id,
                        'user2_id' => $user2Id,
                        'matched_at' => now(),
                        'is_active' => true,
                    ]);

                    AppNotification::create([
                        'user_id' => $swipedId,
                        'type' => 'match',
                        'title' => 'New Match!',
                        'body' => "You and {$user->name} liked each other.",
                    ]);

                    AppNotification::create([
                        'user_id' => $user->id,
                        'type' => 'match',
                        'title' => 'New Match!',
                        'body' => "You and the person you liked matched!",
                    ]);

                    $matched = true;
                }
            }
        }

        return response()->json([
            'success' => true,
            'data' => ['matched' => $matched],
        ]);
    }
}
