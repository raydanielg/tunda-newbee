<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Story;
use App\Models\StoryView;
use Illuminate\Http\Request;

class StoryController extends Controller
{
    public function index(Request $request)
    {
        $stories = Story::with('user.profile')
            ->where('expires_at', '>', now())
            ->orderByDesc('created_at')
            ->get();

        $grouped = $stories->groupBy('user_id')->map(function ($userStories, $userId) use ($request) {
            $user = $userStories->first()->user;
            $storyData = $userStories->map(function ($story) use ($request) {
                $viewed = $story->views()->where('viewer_id', $request->user()->id)->exists();
                return [
                    'id' => (string) $story->id,
                    'media_path' => $story->media_path,
                    'media_type' => $story->media_type,
                    'caption' => $story->caption,
                    'expires_at' => $story->expires_at?->toIso8601String(),
                    'created_at' => $story->created_at?->toIso8601String(),
                    'viewed' => $viewed,
                    'views_count' => $story->views()->count(),
                ];
            });

            return [
                'user' => [
                    'id' => (string) $user->id,
                    'name' => $user->name,
                    'avatar' => $user->avatar,
                ],
                'stories' => $storyData->values(),
            ];
        })->values();

        return response()->json([
            'success' => true,
            'data' => $grouped,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'media_path' => 'required|string',
            'media_type' => 'required|in:image,video',
            'caption' => 'nullable|string|max:500',
        ]);

        $story = Story::create([
            'user_id' => $request->user()->id,
            'media_path' => $request->media_path,
            'media_type' => $request->media_type,
            'caption' => $request->caption,
            'expires_at' => now()->addHours(24),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Story created',
            'data' => [
                'id' => (string) $story->id,
                'expires_at' => $story->expires_at->toIso8601String(),
            ],
        ], 201);
    }

    public function view(Request $request, $id)
    {
        $story = Story::findOrFail($id);

        StoryView::firstOrCreate([
            'story_id' => $story->id,
            'viewer_id' => $request->user()->id,
        ], [
            'viewed_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Story viewed',
        ]);
    }

    public function destroy(Request $request, $id)
    {
        $story = Story::where('id', $id)->where('user_id', $request->user()->id)->firstOrFail();
        $story->delete();

        return response()->json([
            'success' => true,
            'message' => 'Story deleted',
        ]);
    }
}
