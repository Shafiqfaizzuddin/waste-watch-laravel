@extends('layouts.app')

@section('title', 'Community Reports – WasteWatch')

@section('content')
<div class="page-header" style="background: radial-gradient(circle at top, rgba(34,197,94,0.08) 0%, transparent 60%);">
  <div class="container">
    <p class="badge badge-review" style="margin-bottom:16px;">
      <i class="fas fa-globe"></i> Public Portal
    </p>
    <h1>Community Reports</h1>
    <p>View waste incident reports submitted by citizens across Malaysia. Join the effort to track and clean up waste.</p>
  </div>
</div>

<div class="container" style="padding-bottom: 80px;">
  <!-- Filters Bar -->
  <div style="background: var(--clr-surface); border: 1px solid var(--clr-border); border-radius: var(--radius-md); padding: 16px 24px; margin-bottom: 32px; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 16px;">
    <div style="display: flex; align-items: center; gap: 12px; flex-wrap: wrap;">
      <span style="font-size: 0.85rem; font-weight: 600; color: var(--clr-text2);"><i class="fas fa-filter"></i> Filter by:</span>
      <form action="{{ route('reports.public') }}" method="GET" style="display: flex; gap: 10px;">
        <select name="category" class="form-control" style="padding: 6px 12px; width: auto; font-size: 0.8rem; background: var(--clr-bg2);" onchange="this.form.submit()">
          <option value="">All Categories</option>
          <option value="organic" {{ request('category') == 'organic' ? 'selected' : '' }}>🌿 Organic</option>
          <option value="recyclable" {{ request('category') == 'recyclable' ? 'selected' : '' }}>♻️ Recyclable</option>
          <option value="hazardous" {{ request('category') == 'hazardous' ? 'selected' : '' }}>⚠️ Hazardous</option>
          <option value="residual" {{ request('category') == 'residual' ? 'selected' : '' }}>🗑️ Residual</option>
        </select>

        <select name="status" class="form-control" style="padding: 6px 12px; width: auto; font-size: 0.8rem; background: var(--clr-bg2);" onchange="this.form.submit()">
          <option value="">All Statuses</option>
          <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
          <option value="under_review" {{ request('status') == 'under_review' ? 'selected' : '' }}>Under Review</option>
          <option value="resolved" {{ request('status') == 'resolved' ? 'selected' : '' }}>Resolved</option>
          <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
        </select>
        @if(request('category') || request('status'))
          <a href="{{ route('reports.public') }}" class="btn btn-ghost btn-sm" style="padding: 6px 14px;"><i class="fas fa-times"></i> Clear</a>
        @endif
      </form>
    </div>
    <div style="font-size: 0.85rem; color: var(--clr-text3);">
      Showing <strong>{{ $reports->count() }}</strong> reports
    </div>
  </div>

  @if($reports->isEmpty())
    <div style="text-align:center; padding:80px 24px; background:var(--clr-surface); border:1px solid var(--clr-border); border-radius:var(--radius-lg);">
      <div style="font-size:3rem; margin-bottom:20px;">🗑️</div>
      <h3>No Reports Found</h3>
      <p style="color:var(--clr-text2); margin-top:8px;">Try modifying your search filter criteria or check back later.</p>
    </div>
  @else
    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(340px, 1fr)); gap: 24px;">
      @foreach($reports as $report)
        <div class="card" style="display: flex; flex-direction: column; justify-content: space-between;">
          <div>
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
              <span class="badge 
                @if($report->status == 'pending') badge-pending
                @elseif($report->status == 'under_review') badge-review
                @elseif($report->status == 'resolved') badge-resolved
                @else badge-rejected
                @endif">
                @if($report->status == 'pending') ⏱️ Pending
                @elseif($report->status == 'under_review') 🔍 Under Review
                @elseif($report->status == 'resolved') ✅ Resolved
                @else ❌ Rejected
                @endif
              </span>
              <span class="badge 
                @if($report->category == 'organic') cat-organic
                @elseif($report->category == 'recyclable') cat-recyclable
                @elseif($report->category == 'hazardous') cat-hazardous
                @else cat-residual
                @endif">
                @if($report->category == 'organic') 🌿 Organic
                @elseif($report->category == 'recyclable') ♻️ Recyclable
                @elseif($report->category == 'hazardous') ⚠️ Hazardous
                @else 🗑️ Residual
                @endif
              </span>
            </div>
            
            <h3 style="font-size: 1.15rem; font-weight: 700; margin-bottom: 10px; color: var(--clr-text);">{{ $report->title }}</h3>
            <p style="font-size: 0.875rem; color: var(--clr-text2); line-height: 1.6; margin-bottom: 16px;">
              {{ Str::limit($report->description, 140) }}
            </p>
          </div>

          <div style="border-top: 1px solid rgba(48,64,80,0.3); padding-top: 14px; margin-top: 14px; display: flex; justify-content: space-between; align-items: center; font-size: 0.78rem; color: var(--clr-text3);">
            <div>
              <i class="fas fa-map-marker-alt" style="color:var(--clr-primary); margin-right:4px;"></i> 
              {{ $report->address ? $report->address . ', ' : '' }}{{ $report->city ?? 'GPS Location' }}, {{ $report->state ?? 'Malaysia' }}
            </div>
            <div>
              <i class="fas fa-calendar-alt"></i> {{ \Carbon\Carbon::parse($report->date_of_incident)->format('d M Y') }}
            </div>
          </div>
        </div>
      @endforeach
    </div>
  @endif
</div>
@endsection
