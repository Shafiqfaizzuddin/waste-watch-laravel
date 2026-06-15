<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Report;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class WasteReportController extends Controller
{
    // Browse public reports (anyone can access)
    public function publicIndex(Request $request)
    {
        $query = Report::query();

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $reports = $query->orderBy('created_at', 'desc')->get();

        return view('reports.public', compact('reports'));
    }

    // User's private dashboard (requires auth)
    public function dashboard()
    {
        $reports = Auth::user()->reports()
            ->orderBy('created_at', 'desc')
            ->paginate(5);

        return view('dashboard', compact('reports'));
    }

    // Show report creation form
    public function create()
    {
        return view('reports.create');
    }

    // Store a new report in the database
    public function store(Request $request)
    {
        $request->validate([
            'title' => ['required', 'string', 'max:120'],
            'description' => ['required', 'string', 'max:1000'],
            'category' => ['required', 'in:organic,recyclable,hazardous,residual'],
            'date_of_incident' => ['required', 'date', 'before_or_equal:today'],
            'location_type' => ['required', 'in:gps,manual'],
            'photos' => ['nullable', 'array', 'max:3'],
            'photos.*' => ['image', 'mimes:jpeg,png,jpg', 'max:5120'], // 5MB limit
        ]);

        $reportData = [
            'user_id' => Auth::id(),
            'title' => $request->title,
            'description' => $request->description,
            'category' => $request->category,
            'location_type' => $request->location_type,
            'date_of_incident' => $request->date_of_incident,
            'status' => 'pending',
        ];
        // dd($request->all());
        // Geolocation vs Manual Address handling
        if ($request->location_type === 'gps') {
            $reportData['latitude'] = $request->latitude ?? '3.1390';
            $reportData['longitude'] = $request->longitude ?? '101.6869';
            $reportData['city'] = $request->gps_city ?? 'Kuala Lumpur';
            $reportData['state'] = $request->gps_state ?? 'Kuala Lumpur';
            $reportData['address'] = 'GPS Location';
        } elseif ($request->location_type === 'manual') {

            $reportData['address'] = $request->address;
            $reportData['city'] = $request->city;
            $reportData['state'] = $request->state;
        }

        // Upload photos
        $photos = [];
        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $file) {
                $path = $file->store('reports', 'public');
                $photos[] = $path;
            }
        }
        $reportData['photos'] = $photos;

        Report::create($reportData);

        return redirect()->route('dashboard')->with('success', 'Incident report submitted successfully.');
    }

    // Show edit or details view
    public function edit(Report $report)
    {
        // Check ownership
        if ($report->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        return view('reports.edit', compact('report'));
    }

    // Update report (only if status is pending)
    public function update(Request $request, Report $report)
    {
        // Check ownership
        if ($report->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        // Check if report is pending
        if ($report->status !== 'pending') {
            return redirect()->route('dashboard')->with('error', 'Only pending reports can be edited.');
        }

        $request->validate([
            'title' => ['required', 'string', 'max:120'],
            'description' => ['required', 'string', 'max:1000'],
            'category' => ['required', 'in:organic,recyclable,hazardous,residual'],
            'date_of_incident' => ['required', 'date', 'before_or_equal:today'],
            'address' => ['nullable', 'string', 'max:255'],
            'photos' => ['nullable', 'array', 'max:3'],
            'photos.*' => ['image', 'mimes:jpeg,png,jpg', 'max:5120'],
        ]);

        $report->title = $request->title;
        $report->description = $request->description;
        $report->category = $request->category;
        $report->date_of_incident = $request->date_of_incident;
        $report->address = $request->address;

        // Process photos: merge existing ones and upload new ones
        $photos = $request->input('existing_photos', []);

        if ($request->hasFile('photos')) {
            $allowedNewCount = 3 - count($photos);
            if ($allowedNewCount > 0) {
                $files = array_slice($request->file('photos'), 0, $allowedNewCount);
                foreach ($files as $file) {
                    $path = $file->store('reports', 'public');
                    $photos[] = $path;
                }
            }
        }
        $report->photos = $photos;

        $report->save();

        return redirect()->route('dashboard')->with('success', 'Report updated successfully.');
    }

    // Delete report (only if status is pending)
    public function destroy(Report $report)
    {
        // Check ownership
        if ($report->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        // Check status
        if ($report->status !== 'pending') {
            return redirect()->route('dashboard')->with('error', 'Only pending reports can be deleted.');
        }

        // Delete photos from disk
        if (is_array($report->photos)) {
            foreach ($report->photos as $photo) {
                if (!Str::startsWith($photo, 'http')) {
                    Storage::disk('public')->delete($photo);
                }
            }
        }

        $report->delete();

        return redirect()->route('dashboard')->with('success', 'Report deleted successfully.');
    }
}
