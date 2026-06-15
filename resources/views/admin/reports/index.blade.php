@extends('layouts.admin')

@section('title', 'Manage Incident Reports – WasteWatch Admin')
@section('topbar-title', 'Incident Reports Management')

@section('content')
<div class="card" style="padding:0; overflow:hidden;">
  <div style="padding: 24px; border-bottom:1px solid var(--clr-border); display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:16px;">
    <div>
      <h3 style="font-size:1.1rem; font-weight:700;"><i class="fas fa-file-alt" style="color:#3b82f6; margin-right:8px;"></i>All Submitted Incident Reports</h3>
      <p style="font-size:0.82rem; color:var(--clr-text3); margin-top:4px;">Review citizen complaints, assign status, and post official resolution updates.</p>
    </div>
    
    <!-- Filter options -->
    <form action="{{ route('admin.reports') }}" method="GET" style="display:flex; gap:12px; align-items:center; flex-wrap:wrap;">
      <div class="search-box">
        <i class="fas fa-search" style="position:absolute; left:12px; top:50%; transform:translateY(-50%); color:var(--clr-text3); font-size:0.85rem;"></i>
        <input type="text" name="search" placeholder="Search title or details..." value="{{ request('search') }}" style="background: var(--clr-bg); border: 1px solid var(--clr-border); border-radius: var(--radius-sm); padding: 9px 14px 9px 38px; color: var(--clr-text); font-size: 0.85rem; width: 200px; outline: none;"/>
      </div>
      
      <select name="category" class="form-control" style="width:auto; padding:8px 30px 8px 12px; font-size:0.83rem; background: var(--clr-bg);" onchange="this.form.submit()">
        <option value="">All Categories</option>
        <option value="organic" {{ request('category') == 'organic' ? 'selected' : '' }}>🌿 Organic</option>
        <option value="recyclable" {{ request('category') == 'recyclable' ? 'selected' : '' }}>♻️ Recyclable</option>
        <option value="hazardous" {{ request('category') == 'hazardous' ? 'selected' : '' }}>⚠️ Hazardous</option>
        <option value="residual" {{ request('category') == 'residual' ? 'selected' : '' }}>🗑️ Residual</option>
      </select>
      
      <select name="status" class="form-control" style="width:auto; padding:8px 30px 8px 12px; font-size:0.83rem; background: var(--clr-bg);" onchange="this.form.submit()">
        <option value="">All Statuses</option>
        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
        <option value="under_review" {{ request('status') == 'under_review' ? 'selected' : '' }}>Under Review</option>
        <option value="resolved" {{ request('status') == 'resolved' ? 'selected' : '' }}>Resolved</option>
        <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
      </select>

      <button type="submit" class="btn btn-primary btn-sm" style="padding: 8px 16px;"><i class="fas fa-filter"></i> Go</button>
      
      @if(request('search') || request('category') || request('status'))
        <a href="{{ route('admin.reports') }}" class="btn btn-ghost btn-sm" style="padding: 8px 12px;"><i class="fas fa-times"></i> Clear</a>
      @endif
    </form>
  </div>

  <div style="padding:24px;">
    @if($reports->isEmpty())
      <div style="text-align:center; padding: 40px; color: var(--clr-text3);">
        <i class="fas fa-folder-open" style="font-size: 2.5rem; margin-bottom: 12px; opacity: 0.5;"></i>
        <p>No matching reports found in database.</p>
      </div>
    @else
      <div class="table-wrapper">
        <table>
          <thead>
            <tr>
              <th>ID</th>
              <th>Reporter</th>
              <th>Report Title</th>
              <th>Category</th>
              <th>State</th>
              <th>Date Filed</th>
              <th>Status</th>
              <th style="text-align:right;">Actions</th>
            </tr>
          </thead>
          <tbody>
            @foreach($reports as $report)
              <tr>
                <td>#{{ sprintf('%03d', $report->id) }}</td>
                <td>
                  <strong>{{ $report->user->first_name }} {{ $report->user->last_name }}</strong>
                  <span style="display:block; font-size:0.75rem; color:var(--clr-text3);">{{ $report->user->email }}</span>
                </td>
                <td>{{ $report->title }}</td>
                <td>
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
                </td>
                <td>{{ $report->state ?? 'N/A' }}</td>
                <td>{{ $report->created_at->format('d M Y, h:i A') }}</td>
                <td>
                  <span class="badge 
                    @if($report->status == 'pending') badge-pending
                    @elseif($report->status == 'under_review') badge-review
                    @elseif($report->status == 'resolved') badge-resolved
                    @else badge-rejected
                    @endif">
                    @if($report->status == 'pending') ⏳ Pending
                    @elseif($report->status == 'under_review') 🔍 Under Review
                    @elseif($report->status == 'resolved') ✅ Resolved
                    @else ❌ Rejected
                    @endif
                  </span>
                </td>
                <td style="text-align:right;">
                  <a href="{{ route('admin.reports.show', $report->id) }}" class="btn btn-ghost btn-sm" style="padding: 6px 14px; display:inline-flex; align-items:center; gap:6px;">
                    <i class="fas fa-search-location"></i> Review
                  </a>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    @endif
  </div>

  <!-- Pagination -->
  @if(!$reports->isEmpty())
    <div style="padding: 16px 24px; border-top: 1px solid var(--clr-border); display:flex; justify-content:space-between; align-items:center;">
      <span style="font-size:0.82rem; color:var(--clr-text3);">Showing {{ $reports->firstItem() }} to {{ $reports->lastItem() }} of {{ $reports->total() }} reports</span>
      <div>
        {{ $reports->links('pagination::simple-bootstrap-4') }}
      </div>
    </div>
  @endif
</div>
@endsection

@section('scripts')
<style>
  .pagination { display: flex; gap: 8px; list-style: none; }
  .pagination li a, .pagination li span {
    padding: 6px 12px; border-radius: var(--radius-sm); font-size: 0.82rem;
    background: var(--clr-surface); border: 1px solid var(--clr-border); color: var(--clr-text2);
  }
  .pagination li.active span { background: #3b82f6; color: #fff; border-color: #3b82f6; }
  .pagination li.disabled span { opacity: 0.5; cursor: not-allowed; }
</style>
@endsection
