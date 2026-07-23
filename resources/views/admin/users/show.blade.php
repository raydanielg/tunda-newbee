@extends('layouts.admin')

@section('title', $user->name . ' · Admin')
@section('header-title', $user->name)
@section('header-subtitle', 'User details')

@section('content')

<div class="space-y-5 max-w-4xl">

    <div class="bg-white rounded-2xl border border-gray-100 p-6">
        <div class="flex items-center gap-5 mb-6">
            <div class="w-20 h-20 rounded-2xl bg-maroon-100 flex items-center justify-center text-maroon-500 font-bold text-3xl">
                {{ strtoupper(substr($user->name, 0, 1)) }}
            </div>
            <div class="flex-1">
                <div class="flex items-center gap-2">
                    <h2 class="text-xl font-extrabold text-gray-800">{{ $user->name }}</h2>
                    @if($user->profile?->is_verified)
                        <span class="text-green-500"><svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg></span>
                    @endif
                </div>
                <p class="text-sm text-gray-400">{{ $user->email }}</p>
                <div class="flex items-center gap-3 mt-2">
                    @php $colors = ['active' => 'green', 'suspended' => 'amber', 'banned' => 'red']; @endphp
                    <span class="text-xs font-bold text-{{ $colors[$user->status] ?? 'gray' }}-600 bg-{{ $colors[$user->status] ?? 'gray' }}-50 px-2 py-1 rounded-full capitalize">{{ $user->status }}</span>
                    <span class="text-xs font-medium text-gray-500 capitalize">{{ $user->gender ?? '—' }}</span>
                    <span class="text-xs text-gray-400">{{ $user->region ?? '—' }}</span>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div class="bg-gray-50 rounded-xl p-4">
                <p class="text-xs text-gray-400 font-semibold mb-1">Phone</p>
                <p class="text-sm text-gray-700">{{ $user->phone ?? 'Not set' }}</p>
            </div>
            <div class="bg-gray-50 rounded-xl p-4">
                <p class="text-xs text-gray-400 font-semibold mb-1">Date of Birth</p>
                <p class="text-sm text-gray-700">{{ $user->date_of_birth?->format('M d, Y') ?? 'Not set' }}</p>
            </div>
            <div class="bg-gray-50 rounded-xl p-4">
                <p class="text-xs text-gray-400 font-semibold mb-1">Occupation</p>
                <p class="text-sm text-gray-700">{{ $user->profile?->occupation ?? 'Not set' }}</p>
            </div>
            <div class="bg-gray-50 rounded-xl p-4">
                <p class="text-xs text-gray-400 font-semibold mb-1">Education</p>
                <p class="text-sm text-gray-700">{{ $user->profile?->education ?? 'Not set' }}</p>
            </div>
            <div class="bg-gray-50 rounded-xl p-4">
                <p class="text-xs text-gray-400 font-semibold mb-1">Relationship Goal</p>
                <p class="text-sm text-gray-700 capitalize">{{ str_replace('_', ' ', $user->profile?->relationship_goal ?? '—') }}</p>
            </div>
            <div class="bg-gray-50 rounded-xl p-4">
                <p class="text-xs text-gray-400 font-semibold mb-1">Wallet Balance</p>
                <p class="text-sm text-gray-700">{{ number_format($user->wallet?->balance ?? 0, 0) }} TZS</p>
            </div>
        </div>

        @if($user->profile?->bio)
            <div class="mt-4 bg-gray-50 rounded-xl p-4">
                <p class="text-xs text-gray-400 font-semibold mb-1">Bio</p>
                <p class="text-sm text-gray-700">{{ $user->profile->bio }}</p>
            </div>
        @endif

        @if($user->interests->isNotEmpty())
            <div class="mt-4">
                <p class="text-xs text-gray-400 font-semibold mb-2">Interests</p>
                <div class="flex flex-wrap gap-2">
                    @foreach($user->interests as $interest)
                        <span class="text-xs font-medium text-maroon-500 bg-maroon-50 px-3 py-1 rounded-full">{{ $interest->name }}</span>
                    @endforeach
                </div>
            </div>
        @endif
    </div>

    <div class="flex gap-3">
        @if($user->status !== 'suspended')
            <form method="POST" action="{{ route('admin.users.status', $user) }}">
                @csrf @method('PATCH')
                <input type="hidden" name="status" value="suspended">
                <button type="submit" class="px-4 py-2 bg-amber-500 text-white text-sm font-bold rounded-lg hover:bg-amber-600" onclick="return confirm('Suspend?')">Suspend User</button>
            </form>
        @endif
        @if($user->status !== 'banned')
            <form method="POST" action="{{ route('admin.users.status', $user) }}">
                @csrf @method('PATCH')
                <input type="hidden" name="status" value="banned">
                <button type="submit" class="px-4 py-2 bg-red-500 text-white text-sm font-bold rounded-lg hover:bg-red-600" onclick="return confirm('Ban?')">Ban User</button>
            </form>
        @endif
        @if($user->status !== 'active')
            <form method="POST" action="{{ route('admin.users.status', $user) }}">
                @csrf @method('PATCH')
                <input type="hidden" name="status" value="active">
                <button type="submit" class="px-4 py-2 bg-green-500 text-white text-sm font-bold rounded-lg hover:bg-green-600">Activate</button>
            </form>
        @endif
        <a href="{{ route('admin.users.index') }}" class="px-4 py-2 text-sm font-medium text-gray-500 hover:text-gray-700">← Back to Users</a>
    </div>
</div>

@endsection
