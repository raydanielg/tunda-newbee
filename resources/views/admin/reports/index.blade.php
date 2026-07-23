@extends('layouts.admin')

@section('title', 'Reports · Admin')
@section('header-title', 'Reports')
@section('header-subtitle', 'User reports and moderation')

@section('content')

<div class="space-y-5">

    <div class="bg-white rounded-2xl border border-gray-100 p-4">
        <form method="GET" class="flex items-end gap-3">
            <div>
                <label class="block text-xs font-semibold text-gray-500 mb-1">Status</label>
                <select name="status" class="px-3 py-2 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-maroon-400 outline-none">
                    <option value="">All</option>
                    <option value="pending" @selected(request('status') === 'pending')>Pending</option>
                    <option value="reviewing" @selected(request('status') === 'reviewing')>Reviewing</option>
                    <option value="resolved" @selected(request('status') === 'resolved')>Resolved</option>
                    <option value="dismissed" @selected(request('status') === 'dismissed')>Dismissed</option>
                </select>
            </div>
            <button type="submit" class="px-4 py-2 bg-maroon-400 text-white text-sm font-bold rounded-lg hover:bg-maroon-500">Filter</button>
            <a href="{{ route('admin.reports.index') }}" class="px-4 py-2 text-sm font-medium text-gray-500">Reset</a>
        </form>
    </div>

    <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-50 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">
                        <th class="px-5 py-3">Reporter</th>
                        <th class="px-5 py-3">Reported</th>
                        <th class="px-5 py-3">Reason</th>
                        <th class="px-5 py-3">Description</th>
                        <th class="px-5 py-3">Status</th>
                        <th class="px-5 py-3">Date</th>
                        <th class="px-5 py-3 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($reports as $report)
                        <tr class="hover:bg-gray-50">
                            <td class="px-5 py-3 font-medium text-gray-700">{{ $report->reporter->name }}</td>
                            <td class="px-5 py-3 font-medium text-gray-700">{{ $report->reported->name }}</td>
                            <td class="px-5 py-3">
                                <span class="text-xs font-bold uppercase text-red-500 bg-red-50 px-2 py-1 rounded">{{ str_replace('_', ' ', $report->reason) }}</span>
                            </td>
                            <td class="px-5 py-3 text-gray-500 max-w-xs truncate">{{ $report->description ?? '—' }}</td>
                            <td class="px-5 py-3">
                                @php $sc = ['pending' => 'amber', 'reviewing' => 'blue', 'resolved' => 'green', 'dismissed' => 'gray']; @endphp
                                <span class="text-xs font-bold text-{{ $sc[$report->status] ?? 'gray' }}-600 bg-{{ $sc[$report->status] ?? 'gray' }}-50 px-2 py-1 rounded-full capitalize">{{ $report->status }}</span>
                            </td>
                            <td class="px-5 py-3 text-xs text-gray-400">{{ $report->created_at->format('M d, Y') }}</td>
                            <td class="px-5 py-3 text-right">
                                @if($report->status === 'pending')
                                    <form method="POST" action="{{ route('admin.reports.resolve', $report) }}" class="inline-flex items-center gap-2">
                                        @csrf @method('PATCH')
                                        <input type="hidden" name="status" value="resolved">
                                        <select name="action" class="text-xs border border-gray-200 rounded-lg px-2 py-1">
                                            <option value="none">No action</option>
                                            <option value="warn">Warn</option>
                                            <option value="suspend">Suspend</option>
                                            <option value="ban">Ban</option>
                                        </select>
                                        <button type="submit" class="text-xs font-bold text-green-600 hover:text-green-800">Resolve</button>
                                    </form>
                                    <form method="POST" action="{{ route('admin.reports.resolve', $report) }}" class="inline ml-2">
                                        @csrf @method('PATCH')
                                        <input type="hidden" name="status" value="dismissed">
                                        <button type="submit" class="text-xs font-bold text-gray-400 hover:text-gray-600">Dismiss</button>
                                    </form>
                                @else
                                    <span class="text-xs text-gray-300">—</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="px-5 py-12 text-center text-gray-400">No reports found</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-5 py-3 border-t border-gray-100">{{ $reports->links() }}</div>
    </div>
</div>

@endsection
