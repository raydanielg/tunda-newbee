<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Swipe;
use App\Models\Block;
use Illuminate\Http\Request;

class DiscoverController extends Controller
{
    public function profiles(Request $request)
    {
        $user = $request->user();

        $swipedIds = Swipe::where('swiper_id', $user->id)->pluck('swiped_id');
        $blockedIds = Block::where('blocker_id', $user->id)->pluck('blocked_id');
        $blockerIds = Block::where('blocked_id', $user->id)->pluck('blocker_id');

        $query = User::where('role', 'user')
            ->where('status', 'active')
            ->where('id', '!=', $user->id)
            ->whereNotIn('id', $swipedIds)
            ->whereNotIn('id', $blockedIds)
            ->whereNotIn('id', $blockerIds)
            ->with(['profile', 'interests', 'photos']);

        if ($user->profile && $user->profile->looking_for !== 'both') {
            $query->where('gender', $user->profile->looking_for);
        }

        if ($request->filled('region') && $request->region !== 'all') {
            $query->where('region', $request->region);
        }

        $profiles = $query->inRandomOrder()->limit(20)->get();

        $data = $profiles->map(function ($p) {
            return $this->formatProfile($p);
        });

        return response()->json([
            'success' => true,
            'data' => $data,
        ]);
    }

    public function formatProfile(User $user): array
    {
        $age = null;
        if ($user->date_of_birth) {
            $age = $user->date_of_birth->age;
        }

        return [
            'id' => (string) $user->id,
            'name' => $user->name,
            'age' => $age ?? 0,
            'bio' => $user->profile?->bio ?? '',
            'occupation' => $user->profile?->occupation ?? '',
            'education' => $user->profile?->education ?? '',
            'region' => $user->region ?? '',
            'distance' => 0,
            'interests' => $user->interests->pluck('name')->toArray(),
            'relationship_goal' => ucfirst(str_replace('_', ' ', $user->profile?->relationship_goal ?? 'dating')),
            'verified' => (bool) ($user->profile?->is_verified ?? false),
            'online' => $user->last_active_at && $user->last_active_at->gt(now()->subMinutes(5)),
            'avatar' => $user->avatar,
            'gender' => $user->gender,
        ];
    }
}
