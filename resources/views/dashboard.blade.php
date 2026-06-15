@extends('layouts.dashboard')

@section('title', 'My Dashboard – WasteWatch')

@section('content')
<!-- Welcome Banner -->
<div class="welcome-banner">
  <div class="welcome-text">
    <h2>Good morning, {{ Auth::user()->first_name }}! 👋</h2>
    @php
      $pendingCount = Auth::user()->reports()->where('status', 'pending')->count();
    @endphp
    <p>You have <strong style="color:var(--clr-amber);">{{ $pendingCount }} pending</strong> reports awaiting admin review.</p>
  </div>
  <a href="{{ route('reports.create') }}" class="btn btn-primary">
    <i class="fas fa-plus"></i> Submit New Report
  </a>
</div>

<!-- Stats Row -->
@php
  $total = Auth::user()->reports()->count();
  $pending = Auth::user()->reports()->where('status', 'pending')->count();
  $review = Auth::user()->reports()->where('status', 'under_review')->count();
  $resolved = Auth::user()->reports()->where('status', 'resolved')->count();
@endphp
<div class="stats-row">
  <div class="stat-card">
    <div class="stat-card-icon" style="background:rgba(34,197,94,0.1); color:var(--clr-primary);">
      <i class="fas fa-file-alt"></i>
    </div>
    <div>
      <div class="stat-card-num">{{ $total }}</div>
      <div class="stat-card-label">Total Reports</div>
    </div>
  </div>
  <div class="stat-card">
    <div class="stat-card-icon" style="background:rgba(245,158,11,0.1); color:var(--clr-amber);">
      <i class="fas fa-clock"></i>
    </div>
    <div>
      <div class="stat-card-num" style="color:var(--clr-amber);">{{ $pending }}</div>
      <div class="stat-card-label">Pending Review</div>
    </div>
  </div>
  <div class="stat-card">
    <div class="stat-card-icon" style="background:rgba(59,130,246,0.1); color:var(--clr-blue);">
      <i class="fas fa-search"></i>
    </div>
    <div>
      <div class="stat-card-num" style="color:var(--clr-blue);">{{ $review }}</div>
      <div class="stat-card-label">Under Review</div>
    </div>
  </div>
  <div class="stat-card">
    <div class="stat-card-icon" style="background:rgba(34,197,94,0.1); color:var(--clr-primary);">
      <i class="fas fa-check-circle"></i>
    </div>
    <div>
      <div class="stat-card-num" style="color:var(--clr-primary);">{{ $resolved }}</div>
      <div class="stat-card-label">Resolved</div>
    </div>
  </div>
</div>

<!-- Reports Table -->
<div class="card" style="padding:0; overflow:hidden;">
  <div class="section-header" style="padding:22px 24px 0;">
    <h3><i class="fas fa-list" style="color:var(--clr-primary); margin-right:8px;"></i>My Incident Reports</h3>
    <div class="search-filter">
      <div class="search-box">
        <i class="fas fa-search"></i>
        <input type="text" placeholder="Search reports..." id="user-search"/>
      </div>
      <select class="form-control" style="width:auto; padding:9px 36px 9px 14px; font-size:0.85rem;" id="status-filter">
        <option value="">All Status</option>
        <option value="pending">Pending</option>
        <option value="under_review">Under Review</option>
        <option value="resolved">Resolved</option>
        <option value="rejected">Rejected</option>
      </select>
    </div>
  </div>
  
  <div style="padding:0 24px 20px; margin-top:16px;">
    @if($reports->isEmpty())
      <div class="empty-state">
        <i class="fas fa-folder-open"></i>
        <p>You haven't submitted any reports yet. Click "Submit New Report" to start.</p>
      </div>
    @else
      <div class="table-wrapper">
        <table id="user-reports-table">
          <thead>
            <tr>
              <th>#</th>
              <th>Title</th>
              <th>Category</th>
              <th>Location</th>
              <th>Date</th>
              <th>Status</th>
              <th>Admin Remarks</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            @foreach($reports as $report)
              <tr data-status="{{ strtolower($report->status) }}">
                <td>#{{ sprintf('%03d', $report->id) }}</td>
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
                <td>{{ $report->address ?? 'GPS Location' }}, {{ $report->city }}</td>
                <td>{{ \Carbon\Carbon::parse($report->date_of_incident)->format('d M Y') }}</td>
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
                <td>
                  @if($report->admin_remarks)
                    <span style="color:var(--clr-text2); font-size:0.82rem;">{{ $report->admin_remarks }}</span>
                  @else
                    <span style="color:var(--clr-text3);">—</span>
                  @endif
                </td>
                <td>
                  <div class="row-actions">
                    @if($report->status == 'pending')
                      <a href="{{ route('reports.edit', $report->id) }}" class="btn btn-ghost btn-sm btn-icon" title="Edit"><i class="fas fa-edit"></i></a>
                      <form action="{{ route('reports.destroy', $report->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this report? This action cannot be undone.');" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm btn-icon" title="Delete"><i class="fas fa-trash"></i></button>
                      </form>
                    @else
                      <a href="{{ route('reports.edit', $report->id) }}" class="btn btn-ghost btn-sm btn-icon" title="View"><i class="fas fa-eye"></i></a>
                    @endif
                  </div>
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
  /* Fix simple pagination layout styling */
  .pagination { display: flex; gap: 8px; list-style: none; }
  .pagination li a, .pagination li span {
    padding: 6px 12px; border-radius: var(--radius-sm); font-size: 0.82rem;
    background: var(--clr-surface); border: 1px solid var(--clr-border); color: var(--clr-text2);
  }
  .pagination li.active span { background: var(--clr-primary); color: #fff; border-color: var(--clr-primary); }
  .pagination li.disabled span { opacity: 0.5; cursor: not-allowed; }
</style>
<script>
  // Search filter
  document.getElementById('user-search').addEventListener('input', filterTable);
  document.getElementById('status-filter').addEventListener('change', filterTable);

  function filterTable() {
    const query = document.getElementById('user-search').value.toLowerCase();
    const status = document.getElementById('status-filter').value;
    
    document.querySelectorAll('#user-reports-table tbody tr').forEach(row => {
      const textMatches = row.textContent.toLowerCase().includes(query);
      const rowStatus = row.getAttribute('data-status');
      const statusMatches = !status || rowStatus === status;

      row.style.display = (textMatches && statusMatches) ? '' : 'none';
    });
  }
</script>
@endsection
