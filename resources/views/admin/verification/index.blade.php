@extends('layouts.admin')

@section('title', 'Verification · Admin')
@section('header-title', 'Verification Requests')
@section('header-subtitle', 'Review ID verification submissions')

@section('content')

<div class="space-y-5">

    <div class="bg-white rounded-2xl border border-gray-100 p-4">
        <form method="GET" class="flex items-end gap-3">
            <div>
                <label class="block text-xs font-semibold text-gray-500 mb-1">Status</label>
                <select name="status" class="px-3 py-2 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-maroon-400 outline-none">
                    <option value="pending" @selected(request('status') === 'pending' || !request('status'))>Pending</option>
                    <option value="approved" @selected(request('status') === 'approved')>Approved</option>
                    <option value="rejected" @selected(request('status') === 'rejected')>Rejected</option>
                </select>
            </div>
            <button type="submit" class="px-4 py-2 bg-maroon-400 text-white text-sm font-bold rounded-lg hover:bg-maroon-500">Filter</button>
        </form>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">
        @forelse($requests as $verification)
            <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-maroon-100 flex items-center justify-center text-maroon-500 font-bold">
                            {{ strtoupper(substr($verification->user->name, 0, 1)) }}
                        </div>
                        <div>
                            <p class="font-semibold text-gray-700">{{ $verification->user->name }}</p>
                            <p class="text-xs text-gray-400">{{ $verification->user->email }}</p>
                        </div>
                    </div>
                    @php $sc = ['pending' => 'amber', 'approved' => 'green', 'rejected' => 'red']; @endphp
                    <span class="text-xs font-bold text-{{ $sc[$verification->status] ?? 'gray' }}-600 bg-{{ $sc[$verification->status] ?? 'gray' }}-50 px-2 py-1 rounded-full capitalize">{{ $verification->status }}</span>
                </div>

                <div class="p-5 space-y-3">
                    <div class="grid grid-cols-3 gap-3">
                        <div>
                            <p class="text-xs text-gray-400 mb-1">ID Front</p>
                            <div class="aspect-video bg-gray-100 rounded-lg flex items-center justify-center text-gray-300">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            </div>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400 mb-1">ID Back</p>
                            <div class="aspect-video bg-gray-100 rounded-lg flex items-center justify-center text-gray-300">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            </div>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400 mb-1">Selfie</p>
                            <div class="aspect-video bg-gray-100 rounded-lg flex items-center justify-center text-gray-300">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            </div>
                        </div>
                    </div>

                    @if($verification->status === 'pending')
                        <div class="flex gap-2 pt-2">
                            <form method="POST" action="{{ route('admin.verification.approve', $verification) }}">
                                @csrf @method('PATCH')
                                <button type="submit" class="px-4 py-2 bg-green-500 text-white text-sm font-bold rounded-lg hover:bg-green-600" onclick="return confirm('Approve verification?')">Approve</button>
                            </form>
                            <form method="POST" action="{{ route('admin.verification.reject', $verification) }}" class="flex-1 flex gap-2">
                                @csrf @method('PATCH')
                                <input type="text" name="rejection_reason" placeholder="Rejection reason..." required
                                       class="flex-1 px-3 py-2 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-red-400 outline-none">
                                <button type="submit" class="px-4 py-2 bg-red-500 text-white text-sm font-bold rounded-lg hover:bg-red-600">Reject</button>
                            </form>
                        </div>
                    @elseif($verification->rejection_reason)
                        <div class="bg-red-50 border border-red-100 rounded-lg p-3 text-xs text-red-600">
                            <span class="font-bold">Rejection reason:</span> {{ $verification->rejection_reason }}
                        </div>
                    @endif
                </div>
            </div>
        @empty
            <div class="col-span-2 bg-white rounded-2xl border border-gray-100 p-12 text-center text-gray-400">
                No verification requests found
            </div>
        @endforelse
    </div>

    <div class="bg-white rounded-2xl border border-gray-100 px-5 py-3">
        {{ $requests->links() }}
    </div>
</div>

@endsection
