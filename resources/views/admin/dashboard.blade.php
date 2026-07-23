@extends('layouts.admin')

@section('title', 'Dashboard · Admin')

@section('header-title', 'Dashboard')
@section('header-subtitle', 'Overview of Tunda platform')

@section('content')

<div class="space-y-6">

    {{-- Stats Grid --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">

        {{-- Total Users --}}
        <div class="bg-white rounded-2xl border border-gray-100 p-5">
            <div class="flex items-center justify-between mb-3">
                <div class="w-11 h-11 rounded-xl bg-maroon-50 flex items-center justify-center">
                    <svg class="w-6 h-6 text-maroon-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87m6-1a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                </div>
                <span class="text-xs font-bold text-green-600 bg-green-50 px-2 py-1 rounded-full">+{{ $stats['new_users_today'] }} today</span>
            </div>
            <p class="text-3xl font-extrabold text-gray-800">{{ number_format($stats['total_users']) }}</p>
            <p class="text-sm text-gray-400 mt-1">Total Users</p>
        </div>

        {{-- Active Users --}}
        <div class="bg-white rounded-2xl border border-gray-100 p-5">
            <div class="flex items-center justify-between mb-3">
                <div class="w-11 h-11 rounded-xl bg-green-50 flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                </div>
                <span class="text-xs font-bold text-gray-400 bg-gray-50 px-2 py-1 rounded-full">{{ $stats['new_users_week'] }} this week</span>
            </div>
            <p class="text-3xl font-extrabold text-gray-800">{{ number_format($stats['active_users']) }}</p>
            <p class="text-sm text-gray-400 mt-1">Active Users</p>
        </div>

        {{-- Matches --}}
        <div class="bg-white rounded-2xl border border-gray-100 p-5">
            <div class="flex items-center justify-between mb-3">
                <div class="w-11 h-11 rounded-xl bg-pink-50 flex items-center justify-center">
                    <svg class="w-6 h-6 text-pink-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                </div>
                <span class="text-xs font-bold text-pink-500 bg-pink-50 px-2 py-1 rounded-full">{{ $stats['active_matches'] }} active</span>
            </div>
            <p class="text-3xl font-extrabold text-gray-800">{{ number_format($stats['total_matches']) }}</p>
            <p class="text-sm text-gray-400 mt-1">Total Matches</p>
        </div>

        {{-- Revenue --}}
        <div class="bg-white rounded-2xl border border-gray-100 p-5">
            <div class="flex items-center justify-between mb-3">
                <div class="w-11 h-11 rounded-xl bg-amber-50 flex items-center justify-center">
                    <svg class="w-6 h-6 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/></svg>
                </div>
                <span class="text-xs font-bold text-amber-600 bg-amber-50 px-2 py-1 rounded-full">TZS</span>
            </div>
            <p class="text-3xl font-extrabold text-gray-800">{{ number_format($stats['total_revenue'], 0) }}</p>
            <p class="text-sm text-gray-400 mt-1">Total Revenue</p>
        </div>
    </div>

    {{-- Secondary Stats --}}
    <div class="grid grid-cols-2 lg:grid-cols-6 gap-4">
        <div class="bg-white rounded-xl border border-gray-100 px-4 py-3 text-center">
            <p class="text-xl font-bold text-maroon-400">{{ $stats['premium_users'] }}</p>
            <p class="text-xs text-gray-400 mt-1">Premium</p>
        </div>
        <div class="bg-white rounded-xl border border-gray-100 px-4 py-3 text-center">
            <p class="text-xl font-bold text-blue-500">{{ $stats['verified_users'] }}</p>
            <p class="text-xs text-gray-400 mt-1">Verified</p>
        </div>
        <div class="bg-white rounded-xl border border-gray-100 px-4 py-3 text-center">
            <p class="text-xl font-bold text-gray-600">{{ $stats['male_users'] }}</p>
            <p class="text-xs text-gray-400 mt-1">Male</p>
        </div>
        <div class="bg-white rounded-xl border border-gray-100 px-4 py-3 text-center">
            <p class="text-xl font-bold text-pink-400">{{ $stats['female_users'] }}</p>
            <p class="text-xs text-gray-400 mt-1">Female</p>
        </div>
        <div class="bg-white rounded-xl border border-gray-100 px-4 py-3 text-center">
            <p class="text-xl font-bold text-red-500">{{ $stats['pending_reports'] }}</p>
            <p class="text-xs text-gray-400 mt-1">Reports</p>
        </div>
        <div class="bg-white rounded-xl border border-gray-100 px-4 py-3 text-center">
            <p class="text-xl font-bold text-amber-500">{{ $stats['pending_verifications'] }}</p>
            <p class="text-xs text-gray-400 mt-1">Verifications</p>
        </div>
    </div>

    {{-- Recent Activity --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Recent Users --}}
        <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
                <h3 class="font-bold text-gray-800">New Users</h3>
                <a href="{{ route('admin.users.index') }}" class="text-xs font-semibold text-maroon-400 hover:text-maroon-600">View all →</a>
            </div>
            <div class="divide-y divide-gray-50">
                @forelse($recentUsers as $user)
                    <div class="flex items-center gap-3 px-5 py-3 hover:bg-gray-50">
                        <div class="w-9 h-9 rounded-full bg-maroon-100 flex items-center justify-center text-maroon-500 font-bold text-sm">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-semibold text-gray-700 truncate">{{ $user->name }}</p>
                            <p class="text-xs text-gray-400 truncate">{{ $user->email }}</p>
                        </div>
                        <span class="text-xs text-gray-300">{{ $user->created_at->diffForHumans() }}</span>
                    </div>
                @empty
                    <p class="px-5 py-8 text-center text-sm text-gray-400">No new users</p>
                @endforelse
            </div>
        </div>

        {{-- Pending Reports --}}
        <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
                <h3 class="font-bold text-gray-800">Pending Reports</h3>
                <a href="{{ route('admin.reports.index') }}" class="text-xs font-semibold text-maroon-400 hover:text-maroon-600">View all →</a>
            </div>
            <div class="divide-y divide-gray-50">
                @forelse($recentReports as $report)
                    <div class="px-5 py-3 hover:bg-gray-50">
                        <div class="flex items-center justify-between mb-1">
                            <span class="text-sm font-semibold text-gray-700">{{ $report->reporter->name }}</span>
                            <span class="text-[10px] font-bold uppercase text-red-500 bg-red-50 px-2 py-0.5 rounded">{{ $report->reason }}</span>
                        </div>
                        <p class="text-xs text-gray-400">Reported: <span class="font-medium text-gray-600">{{ $report->reported->name }}</span></p>
                    </div>
                @empty
                    <p class="px-5 py-8 text-center text-sm text-gray-400">No pending reports</p>
                @endforelse
            </div>
        </div>

        {{-- Pending Verifications --}}
        <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
                <h3 class="font-bold text-gray-800">Pending Verifications</h3>
                <a href="{{ route('admin.verification.index') }}" class="text-xs font-semibold text-maroon-400 hover:text-maroon-600">View all →</a>
            </div>
            <div class="divide-y divide-gray-50">
                @forelse($recentVerifications as $verification)
                    <div class="flex items-center gap-3 px-5 py-3 hover:bg-gray-50">
                        <div class="w-9 h-9 rounded-full bg-amber-100 flex items-center justify-center text-amber-600 font-bold text-sm">
                            {{ strtoupper(substr($verification->user->name, 0, 1)) }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-semibold text-gray-700 truncate">{{ $verification->user->name }}</p>
                            <p class="text-xs text-gray-400">{{ $verification->created_at->diffForHumans() }}</p>
                        </div>
                    </div>
                @empty
                    <p class="px-5 py-8 text-center text-sm text-gray-400">No pending verifications</p>
                @endforelse
            </div>
        </div>
    </div>
</div>

@endsection
