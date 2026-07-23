@extends('layouts.admin')

@section('title', 'Payments · Admin')
@section('header-title', 'Payments')
@section('header-subtitle', 'Transaction history and revenue')

@section('content')

<div class="space-y-5">

    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="bg-white rounded-2xl border border-gray-100 p-5">
            <p class="text-xs text-gray-400 font-semibold mb-1">Total Revenue</p>
            <p class="text-2xl font-extrabold text-maroon-400">{{ number_format($totalRevenue, 0) }} TZS</p>
        </div>
        <div class="bg-white rounded-2xl border border-gray-100 p-5">
            <p class="text-xs text-gray-400 font-semibold mb-1">Total Transactions</p>
            <p class="text-2xl font-extrabold text-gray-700">{{ number_format($totalTransactions) }}</p>
        </div>
        <div class="bg-white rounded-2xl border border-gray-100 p-5">
            <p class="text-xs text-gray-400 font-semibold mb-1">Completed</p>
            <p class="text-2xl font-extrabold text-green-500">{{ number_format($completedCount) }}</p>
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-gray-100 p-4">
        <form method="GET" class="flex items-end gap-3">
            <div>
                <label class="block text-xs font-semibold text-gray-500 mb-1">Status</label>
                <select name="status" class="px-3 py-2 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-maroon-400 outline-none">
                    <option value="">All</option>
                    <option value="pending" @selected(request('status') === 'pending')>Pending</option>
                    <option value="completed" @selected(request('status') === 'completed')>Completed</option>
                    <option value="failed" @selected(request('status') === 'failed')>Failed</option>
                    <option value="refunded" @selected(request('status') === 'refunded')>Refunded</option>
                </select>
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-500 mb-1">Purpose</label>
                <select name="purpose" class="px-3 py-2 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-maroon-400 outline-none">
                    <option value="">All</option>
                    <option value="premium" @selected(request('purpose') === 'premium')>Premium</option>
                    <option value="boost" @selected(request('purpose') === 'boost')>Boost</option>
                    <option value="super_like" @selected(request('purpose') === 'super_like')>Super Like</option>
                    <option value="wallet_topup" @selected(request('purpose') === 'wallet_topup')>Wallet Topup</option>
                </select>
            </div>
            <button type="submit" class="px-4 py-2 bg-maroon-400 text-white text-sm font-bold rounded-lg hover:bg-maroon-500">Filter</button>
        </form>
    </div>

    <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-50 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">
                        <th class="px-5 py-3">Reference</th>
                        <th class="px-5 py-3">User</th>
                        <th class="px-5 py-3">Purpose</th>
                        <th class="px-5 py-3">Amount</th>
                        <th class="px-5 py-3">Method</th>
                        <th class="px-5 py-3">Status</th>
                        <th class="px-5 py-3">Date</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($payments as $payment)
                        <tr class="hover:bg-gray-50">
                            <td class="px-5 py-3 font-mono text-xs text-gray-500">{{ $payment->payment_reference }}</td>
                            <td class="px-5 py-3 font-medium text-gray-700">{{ $payment->user->name }}</td>
                            <td class="px-5 py-3">
                                <span class="text-xs font-bold text-maroon-400 bg-maroon-50 px-2 py-1 rounded capitalize">{{ str_replace('_', ' ', $payment->purpose) }}</span>
                            </td>
                            <td class="px-5 py-3 font-semibold text-gray-700">{{ number_format($payment->amount, 0) }} {{ $payment->currency }}</td>
                            <td class="px-5 py-3 text-gray-500 capitalize">{{ str_replace('_', ' ', $payment->method) }}</td>
                            <td class="px-5 py-3">
                                @php $sc = ['pending' => 'amber', 'completed' => 'green', 'failed' => 'red', 'refunded' => 'blue']; @endphp
                                <span class="text-xs font-bold text-{{ $sc[$payment->status] ?? 'gray' }}-600 bg-{{ $sc[$payment->status] ?? 'gray' }}-50 px-2 py-1 rounded-full capitalize">{{ $payment->status }}</span>
                            </td>
                            <td class="px-5 py-3 text-xs text-gray-400">{{ $payment->created_at->format('M d, Y H:i') }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="px-5 py-12 text-center text-gray-400">No payments found</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-5 py-3 border-t border-gray-100">{{ $payments->links() }}</div>
    </div>

    <div class="flex justify-end">
        <a href="{{ route('admin.payments.subscriptions') }}" class="px-4 py-2 text-sm font-bold text-maroon-400 hover:text-maroon-600">View Subscriptions →</a>
    </div>
</div>

@endsection
