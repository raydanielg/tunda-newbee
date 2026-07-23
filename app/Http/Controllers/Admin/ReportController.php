<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Report;
use App\Models\User;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $query = Report::with(['reporter', 'reported']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $reports = $query->latest()->paginate(20);

        return view('admin.reports.index', compact('reports'));
    }

    public function resolve(Report $report, Request $request)
    {
        $request->validate([
            'status' => 'required|in:resolved,dismissed',
            'action' => 'nullable|in:warn,suspend,ban,none',
        ]);

        $report->update([
            'status' => $request->status,
            'resolved_by' => auth()->id(),
            'resolved_at' => now(),
        ]);

        if ($request->status === 'resolved' && $request->filled('action') && $request->action !== 'none') {
            $reported = $report->reported;
            $newStatus = match ($request->action) {
                'suspend' => 'suspended',
                'ban' => 'banned',
                default => $reported->status,
            };
            if ($newStatus !== $reported->status) {
                $reported->update(['status' => $newStatus]);
            }
        }

        return back()->with('success', 'Report resolved');
    }
}
