<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Report;
use App\Models\User;

class AdminController extends Controller
{
    // Admin dashboard home
    public function dashboard()
    {
        $totalReports = Report::count();
        $pendingCount = Report::where('status', 'pending')->count();
        $resolvedCount = Report::where('status', 'resolved')->count();
        $usersCount = User::where('role', 'user')->count();

        // Categories count
        $organicCount = Report::where('category', 'organic')->count();
        $recyclableCount = Report::where('category', 'recyclable')->count();
        $hazardousCount = Report::where('category', 'hazardous')->count();
        $residualCount = Report::where('category', 'residual')->count();

        // Monthly statistics (for current year)
        $currentYear = date('Y');
        $monthlyResolvedData = [];
        $monthlyPendingData = [];

        for ($m = 1; $m <= 12; $m++) {
            $monthlyResolvedData[] = Report::where('status', 'resolved')
                ->whereYear('created_at', $currentYear)
                ->whereMonth('created_at', $m)
                ->count();

            $monthlyPendingData[] = Report::where('status', 'pending')
                ->whereYear('created_at', $currentYear)
                ->whereMonth('created_at', $m)
                ->count();
        }

        // Recent pending reports (limit 4)
        $recentReports = Report::where('status', 'pending')
            ->orderBy('created_at', 'desc')
            ->take(4)
            ->get();

        return view('admin.dashboard', compact(
            'totalReports',
            'pendingCount',
            'resolvedCount',
            'usersCount',
            'organicCount',
            'recyclableCount',
            'hazardousCount',
            'residualCount',
            'monthlyResolvedData',
            'monthlyPendingData',
            'recentReports'
        ));
    }

    // List all reports with search and filter
    public function reportsIndex(Request $request)
    {
        $query = Report::with('user');

        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $reports = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('admin.reports.index', compact('reports'));
    }

    // Show single report detail
    public function showReport($id)
    {
        $report = Report::with('user')->findOrFail($id);
        return view('admin.reports.show', compact('report'));
    }

    // Update single report status & remarks
    public function updateReport(Request $request, $id)
    {
        $request->validate([
            'status' => ['required', 'in:pending,under_review,resolved,rejected'],
            'admin_remarks' => ['nullable', 'string', 'max:1000'],
        ]);

        $report = Report::findOrFail($id);
        $report->status = $request->status;
        $report->admin_remarks = $request->admin_remarks;
        $report->save();

        return redirect()->route('admin.reports')->with('success', 'Incident report #' . sprintf('%03d', $report->id) . ' updated successfully.');
    }
}
