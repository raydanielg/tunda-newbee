<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Profile;
use App\Models\Wallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $data = $request->validate([
            'name'              => ['required', 'string', 'max:255'],
            'email'             => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password'          => ['required', 'string', 'min:8', 'confirmed'],
            'phone'             => ['nullable', 'string', 'max:20'],
            'gender'            => ['nullable', 'in:male,female,other'],
            'date_of_birth'     => ['nullable', 'date', 'before:today'],
            'region'            => ['nullable', 'string', 'max:100'],
            'looking_for'       => ['nullable', 'in:male,female,both'],
            'relationship_goal' => ['nullable', 'in:dating,serious_relationship,marriage,friendship'],
        ]);

        $user = User::create([
            'name'           => $data['name'],
            'email'          => $data['email'],
            'password'       => Hash::make($data['password']),
            'phone'          => $data['phone'] ?? null,
            'gender'         => $data['gender'] ?? null,
            'date_of_birth'  => $data['date_of_birth'] ?? null,
            'region'         => $data['region'] ?? null,
            'role'           => 'user',
            'status'         => 'active',
            'last_active_at' => now(),
        ]);

        // Auto-create profile
        Profile::create([
            'user_id'             => $user->id,
            'relationship_goal'   => $data['relationship_goal'] ?? 'dating',
            'looking_for'         => $data['looking_for'] ?? 'both',
            'is_verified'         => false,
            'is_premium'          => false,
            'profile_completion'  => 25,
            'min_age_preference'  => 18,
            'max_age_preference'  => 50,
            'max_distance_km'     => 50,
        ]);

        // Auto-create wallet
        Wallet::create([
            'user_id'  => $user->id,
            'balance'  => 0,
            'currency' => 'TZS',
        ]);

        $token = $user->createToken('tunda-mobile')->plainTextToken;
        $user->load('profile');

        return response()->json([
            'success' => true,
            'message' => 'Account created successfully',
            'data'    => [
                'user'  => $user,
                'token' => $token,
            ],
        ], 201);
    }

    public function login(Request $request)
    {
        $data = $request->validate([
            'email'    => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ]);

        $user = User::where('email', $data['email'])->first();

        if (!$user || !Hash::check($data['password'], $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        if ($user->status === 'banned') {
            throw ValidationException::withMessages([
                'email' => ['Your account has been banned. Contact support.'],
            ]);
        }

        if ($user->status === 'suspended') {
            throw ValidationException::withMessages([
                'email' => ['Your account has been suspended. Contact support.'],
            ]);
        }

        // Update last active
        $user->update(['last_active_at' => now()]);

        $token = $user->createToken('tunda-mobile')->plainTextToken;
        $user->load('profile');

        return response()->json([
            'success' => true,
            'message' => 'Login successful',
            'data'    => [
                'user'  => $user,
                'token' => $token,
            ],
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Logged out successfully',
        ]);
    }

    public function user(Request $request)
    {
        $user = $request->user();
        $user->load(['profile', 'interests', 'wallet']);

        return response()->json([
            'success' => true,
            'data'    => [
                'id'              => (string) $user->id,
                'name'            => $user->name,
                'email'           => $user->email,
                'phone'           => $user->phone,
                'gender'          => $user->gender,
                'region'          => $user->region,
                'date_of_birth'   => $user->date_of_birth?->toDateString(),
                'avatar'          => $user->avatar,
                'role'            => $user->role,
                'status'          => $user->status,
                'last_active_at'  => $user->last_active_at?->toIso8601String(),
                'is_verified'     => (bool) ($user->profile?->is_verified ?? false),
                'is_premium'      => (bool) ($user->profile?->is_premium ?? false),
                'profile'         => $user->profile,
                'interests'       => $user->interests->pluck('name'),
                'wallet_balance'  => $user->wallet?->balance ?? 0,
            ],
        ]);
    }

    public function forgotPassword(Request $request)
    {
        $request->validate([
            'email' => ['required', 'string', 'email'],
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            throw ValidationException::withMessages([
                'email' => ['No account found with this email address.'],
            ]);
        }

        \Password::sendResetLink($request->only('email'));

        return response()->json([
            'success' => true,
            'message' => 'Reset link sent to your email',
        ]);
    }

    public function updateLastActive(Request $request)
    {
        $request->user()->update(['last_active_at' => now()]);

        return response()->json([
            'success' => true,
            'message' => 'Updated',
        ]);
    }
}
