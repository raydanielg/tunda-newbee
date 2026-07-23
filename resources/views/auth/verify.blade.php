@extends('layouts.auth')

@section('title', 'Verify Email - Tunda')

@section('content')
<div class="w-full max-w-md" style="animation: simpleFadeIn 0.4s ease-out both;">
    <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
        {{-- Header --}}
        <div class="bg-gradient-to-br from-maroon-400 to-maroon-500 px-8 py-8 text-center">
            <div class="w-20 h-20 mx-auto bg-white/10 backdrop-blur-sm rounded-2xl flex items-center justify-center mb-4">
                <img src="{{ asset('whitelogo.png') }}" alt="Tunda" class="w-14 h-14 object-contain">
            </div>
            <h2 class="text-2xl font-extrabold text-white">Verify Your Email</h2>
            <p class="text-maroon-100 text-sm mt-1">Check your inbox for a verification link</p>
        </div>

        {{-- Body --}}
        <div class="p-8 text-center">
            @if (session('resent'))
                <div class="mb-4 p-3 rounded-lg bg-maroon-50 border border-maroon-100 text-sm text-maroon-400 flex items-center gap-2">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    A fresh verification link has been sent to your email address.
                </div>
            @endif

            <p class="text-sm text-gray-600 mb-6">
                Before proceeding, please check your email for a verification link.<br>
                If you did not receive the email, click the button below to request another.
            </p>

            <form method="POST" action="{{ route('verification.resend') }}" class="space-y-5">
                @csrf
                <button type="submit" class="w-full py-3 text-sm font-bold text-white bg-gradient-to-r from-maroon-400 to-maroon-500 hover:from-maroon-500 hover:to-maroon-600 rounded-lg shadow-md hover:shadow-lg transition-all flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                    Resend Verification Email
                </button>
            </form>

            <p class="mt-5 text-sm text-gray-500">
                <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="font-semibold text-maroon-400 hover:text-maroon-500 transition-colors">Sign out</a>
            </p>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                @csrf
            </form>
        </div>
    </div>

    <p class="mt-6 text-center text-xs text-gray-400">&copy; {{ date('Y') }} Tunda. All rights reserved.</p>
</div>
@endsection
