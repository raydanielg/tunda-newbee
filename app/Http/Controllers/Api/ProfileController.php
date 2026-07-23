<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Profile;
use App\Models\Interest;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function show(Request $request)
    {
        $user = $request->user();
        $user->load(['profile', 'interests', 'photos', 'wallet']);

        return response()->json([
            'success' => true,
            'data' => [
                'id' => (string) $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone,
                'gender' => $user->gender,
                'region' => $user->region,
                'date_of_birth' => $user->date_of_birth?->toDateString(),
                'avatar' => $user->avatar,
                'role' => $user->role,
                'status' => $user->status,
                'profile' => $user->profile ? [
                    'bio' => $user->profile->bio,
                    'occupation' => $user->profile->occupation,
                    'education' => $user->profile->education,
                    'relationship_goal' => $user->profile->relationship_goal,
                    'looking_for' => $user->profile->looking_for,
                    'is_verified' => (bool) $user->profile->is_verified,
                    'is_premium' => (bool) $user->profile->is_premium,
                    'profile_completion' => $user->profile->profile_completion,
                ] : null,
                'interests' => $user->interests->pluck('name')->toArray(),
                'wallet_balance' => $user->wallet?->balance ?? 0,
                'stats' => [
                    'matches' => $user->matchesAsUser1()->count() + $user->matchesAsUser2()->count(),
                    'likes' => \App\Models\Swipe::where('swiped_id', $user->id)->whereIn('action', ['like', 'super_like'])->count(),
                    'photos' => $user->photos->count(),
                ],
            ],
        ]);
    }

    public function update(Request $request)
    {
        $user = $request->user();

        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'phone' => 'sometimes|nullable|string|max:20',
            'region' => 'sometimes|nullable|string|max:100',
            'date_of_birth' => 'sometimes|nullable|date',
            'gender' => 'sometimes|nullable|in:male,female,other',
            'bio' => 'sometimes|nullable|string|max:500',
            'occupation' => 'sometimes|nullable|string|max:100',
            'education' => 'sometimes|nullable|string|max:100',
            'relationship_goal' => 'sometimes|nullable|in:dating,serious_relationship,marriage,friendship',
            'looking_for' => 'sometimes|nullable|in:male,female,both',
            'interests' => 'sometimes|array',
            'interests.*' => 'string',
        ]);

        $userFields = $request->only(['name', 'phone', 'region', 'date_of_birth', 'gender']);
        if (!empty($userFields)) {
            $user->update($userFields);
        }

        $profileFields = $request->only(['bio', 'occupation', 'education', 'relationship_goal', 'looking_for']);
        if (!empty($profileFields)) {
            $profile = $user->profile ?? new Profile(['user_id' => $user->id]);
            $profile->fill($profileFields);
            $profile->user_id = $user->id;
            $profile->save();
        }

        if ($request->has('interests')) {
            $interestIds = [];
            foreach ($request->interests as $name) {
                $interest = Interest::firstOrCreate(['name' => $name]);
                $interestIds[] = $interest->id;
            }
            $user->interests()->sync($interestIds);
        }

        $user->load(['profile', 'interests']);

        return response()->json([
            'success' => true,
            'message' => 'Profile updated successfully',
            'data' => [
                'id' => (string) $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone,
                'gender' => $user->gender,
                'region' => $user->region,
                'avatar' => $user->avatar,
                'profile' => $user->profile ? [
                    'bio' => $user->profile->bio,
                    'occupation' => $user->profile->occupation,
                    'education' => $user->profile->education,
                    'relationship_goal' => $user->profile->relationship_goal,
                    'is_verified' => (bool) $user->profile->is_verified,
                    'is_premium' => (bool) $user->profile->is_premium,
                ] : null,
                'interests' => $user->interests->pluck('name')->toArray(),
            ],
        ]);
    }
}
