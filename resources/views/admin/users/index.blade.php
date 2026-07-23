@extends('layouts.admin')

@section('title', 'Users · Admin')
@section('header-title', 'Users')
@section('header-subtitle', 'Manage all platform users')

@section('content')

<div class="space-y-5">

    <div class="bg-white rounded-2xl border border-gray-100 p-4">
        <form method="GET" class="flex flex-wrap items-end gap-3">
            <div class="flex-1 min-w-[200px]">
                <label class="block text-xs font-semibold text-gray-500 mb-1">Search</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Name, email, region..." class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-maroon-400 focus:border-maroon-400 outline-none">
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-500 mb-1">Status</label>
                <select name="status" class="px-3 py-2 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-maroon-400 outline-none">
                    <option value="">All</option>
                    <option value="active" @selected(request('status') === 'active')>Active</option>
                    <option value="suspended" @selected(request('status') === 'suspended')>Suspended</option>
                    <option value="banned" @selected(request('status') === 'banned')>Banned</option>
                </select>
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-500 mb-1">Gender</label>
                <select name="gender" class="px-3 py-2 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-maroon-400 outline-none">
                    <option value="">All</option>
                    <option value="male" @selected(request('gender') === 'male')>Male</option>
                    <option value="female" @selected(request('gender') === 'female')>Female</option>
                </select>
            </div>
            <button type="submit" class="px-4 py-2 bg-maroon-400 text-white text-sm font-bold rounded-lg hover:bg-maroon-500 transition">Filter</button>
            <a href="{{ route('admin.users.index') }}" class="px-4 py-2 text-sm font-medium text-gray-500 hover:text-gray-700">Reset</a>
        </form>
    </div>

    <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-50 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">
                        <th class="px-5 py-3">User</th>
                        <th class="px-5 py-3">Gender</th>
                        <th class="px-5 py-3">Region</th>
                        <th class="px-5 py-3">Verified</th>
                        <th class="px-5 py-3">Status</th>
                        <th class="px-5 py-3">Joined</th>
                        <th class="px-5 py-3 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($users as $user)
                        <tr class="hover:bg-gray-50">
                            <td class="px-5 py-3">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-full bg-maroon-100 flex items-center justify-center text-maroon-500 font-bold text-sm">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <p class="font-semibold text-gray-700">{{ $user->name }}</p>
                                        <p class="text-xs text-gray-400">{{ $user->email }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-5 py-3">
                                <span class="text-xs font-medium capitalize {{ $user->gender === 'female' ? 'text-pink-500' : 'text-blue-500' }}">{{ $user->gender ?? '—' }}</span>
                            </td>
                            <td class="px-5 py-3 text-gray-600">{{ $user->region ?? '—' }}</td>
                            <td class="px-5 py-3">
                                @if($user->profile?->is_verified)
                                    <span class="inline-flex items-center gap-1 text-xs font-bold text-green-600 bg-green-50 px-2 py-1 rounded-full">
                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                                        Verified
                                    </span>
                                @else
                                    <span class="text-xs text-gray-300">—</span>
                                @endif
                            </td>
                            <td class="px-5 py-3">
                                @php $colors = ['active' => 'green', 'suspended' => 'amber', 'banned' => 'red']; @endphp
                                <span class="inline-flex items-center text-xs font-bold text-{{ $colors[$user->status] ?? 'gray' }}-600 bg-{{ $colors[$user->status] ?? 'gray' }}-50 px-2 py-1 rounded-full capitalize">
                                    {{ $user->status }}
                                </span>
                            </td>
                            <td class="px-5 py-3 text-xs text-gray-400">{{ $user->created_at->format('M d, Y') }}</td>
                            <td class="px-5 py-3 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.users.show', $user) }}" class="text-xs font-semibold text-maroon-400 hover:text-maroon-600">View</a>
                                    @if($user->status !== 'suspended')
                                        <form method="POST" action="{{ route('admin.users.status', $user) }}" class="inline">
                                            @csrf @method('PATCH')
                                            <input type="hidden" name="status" value="suspended">
                                            <button type="submit" class="text-xs font-semibold text-amber-500 hover:text-amber-700" onclick="return confirm('Suspend this user?')">Suspend</button>
                                        </form>
                                    @endif
                                    @if($user->status !== 'banned')
                                        <form method="POST" action="{{ route('admin.users.status', $user) }}" class="inline">
                                            @csrf @method('PATCH')
                                            <input type="hidden" name="status" value="banned">
                                            <button type="submit" class="text-xs font-semibold text-red-500 hover:text-red-700" onclick="return confirm('Ban this user?')">Ban</button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="px-5 py-12 text-center text-gray-400">No users found</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-5 py-3 border-t border-gray-100">
            {{ $users->links() }}
        </div>
    </div>
</div>

@endsection
