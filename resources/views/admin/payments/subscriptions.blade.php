@extends('layouts.admin')

@section('title', 'Subscriptions · Admin')
@section('header-title', 'Premium Subscriptions')
@section('header-subtitle', 'Active and past premium subscriptions')

@section('content')

<div class="bg-white rounded-2xl border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-gray-50 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">
                    <th class="px-5 py-3">User</th>
                    <th class="px-5 py-3">Plan</th>
                    <th class="px-5 py-3">Amount</th>
                    <th class="px-5 py-3">Start</th>
                    <th class="px-5 py-3">End</th>
                    <th class="px-5 py-3">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($subscriptions as $sub)
                    <tr class="hover:bg-gray-50">
                        <td class="px-5 py-3 font-medium text-gray-700">{{ $sub->user->name }}</td>
                        <td class="px-5 py-3">
                            <span class="text-xs font-bold text-maroon-400 bg-maroon-50 px-2 py-1 rounded capitalize">{{ $sub->plan }}</span>
                        </td>
                        <td class="px-5 py-3 font-semibold text-gray-700">{{ number_format($sub->amount, 0) }} {{ $sub->currency }}</td>
                        <td class="px-5 py-3 text-xs text-gray-400">{{ $sub->starts_at->format('M d, Y') }}</td>
                        <td class="px-5 py-3 text-xs text-gray-400">{{ $sub->ends_at->format('M d, Y') }}</td>
                        <td class="px-5 py-3">
                            @php $sc = ['active' => 'green', 'expired' => 'gray', 'cancelled' => 'red']; @endphp
                            <span class="text-xs font-bold text-{{ $sc[$sub->status] ?? 'gray' }}-600 bg-{{ $sc[$sub->status] ?? 'gray' }}-50 px-2 py-1 rounded-full capitalize">{{ $sub->status }}</span>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="px-5 py-12 text-center text-gray-400">No subscriptions found</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="px-5 py-3 border-t border-gray-100">{{ $subscriptions->links() }}</div>
</div>

<div class="mt-4">
    <a href="{{ route('admin.payments.index') }}" class="text-sm font-medium text-gray-500 hover:text-gray-700">← Back to Payments</a>
</div>

@endsection
