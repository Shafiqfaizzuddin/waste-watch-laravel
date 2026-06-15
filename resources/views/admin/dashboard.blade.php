@extends('layouts.admin')

@section('title', 'Admin Dashboard – WasteWatch')
@section('topbar-title', 'Dashboard Overview')

@section('styles')
<style>
  /* Summary Stats */
  .summary-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; margin-bottom: 28px; }
  .summary-card {
    background: var(--clr-surface);
    border: 1px solid var(--clr-border);
    border-radius: var(--radius-lg);
    padding: 22px 24px;
    position: relative; overflow: hidden;
    transition: all var(--transition);
  }
  .summary-card:hover { border-color: rgba(59,130,246,0.25); box-shadow: 0 4px 20px rgba(0,0,0,0.3); }
  .summary-card-top { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 16px; }
  .summary-icon { width: 44px; height: 44px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.1rem; }
  .summary-trend { font-size: 0.75rem; font-weight: 600; display: flex; align-items: center; gap: 4px; }
  .trend-up { color: #22c55e; }
  .trend-down { color: #ef4444; }
  .summary-num { font-family: var(--font-head); font-size: 2.2rem; font-weight: 800; line-height: 1; margin-bottom: 6px; }
  .summary-label { font-size: 0.8rem; color: var(--clr-text3); }
  .summary-card .bar-bg { position: absolute; bottom: 0; left: 0; right: 0; height: 3px; }

  /* Charts Row */
  .charts-row { display: grid; grid-template-columns: 2fr 1fr; gap: 20px; margin-bottom: 28px; }
  .chart-card { background: var(--clr-surface); border: 1px solid var(--clr-border); border-radius: var(--radius-lg); padding: 24px; }
  .chart-card-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
  .chart-card-header h3 { font-size: 1rem; font-weight: 700; }
  .chart-card-header select { background: var(--clr-bg); border: 1px solid var(--clr-border); border-radius: 6px; padding: 5px 10px; font-size: 0.78rem; color: var(--clr-text2); outline: none; }

  /* Category Breakdown */
  .cat-list { display: flex; flex-direction: column; gap: 14px; margin-top: 8px; }
  .cat-row-top { display: flex; justify-content: space-between; font-size: 0.82rem; margin-bottom: 6px; }
  .cat-row-top span:first-child { display: flex; align-items: center; gap: 8px; font-weight: 500; }
  .cat-dot { width: 10px; height: 10px; border-radius: 50%; }
  .cat-bar-bg { height: 6px; background: var(--clr-bg); border-radius: 3px; overflow: hidden; }
  .cat-bar-fill { height: 100%; border-radius: 3px; transition: width 1s ease; }

  /* Activity + Quick Actions row */
  .bottom-row { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }

  /* Recent Reports Mini Table */
  .mini-table tbody td { padding: 12px 14px; font-size: 0.83rem; }
  .mini-table thead th { padding: 10px 14px; font-size: 0.72rem; }

  /* Quick Actions */
  .quick-actions { display: flex; flex-direction: column; gap: 10px; }
  .quick-action-btn {
    display: flex; align-items: center; gap: 14px;
    padding: 14px 18px;
    background: var(--clr-bg);
    border: 1px solid var(--clr-border);
    border-radius: var(--radius-md);
    cursor: pointer; transition: all var(--transition);
    text-decoration: none;
  }
  .quick-action-btn:hover { border-color: rgba(59,130,246,0.3); background: rgba(59,130,246,0.04); }
  .qa-icon { width: 38px; height: 38px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 1rem; flex-shrink: 0; }
  .qa-text strong { display: block; font-size: 0.87rem; color: var(--clr-text); }
  .qa-text span { font-size: 0.77rem; color: var(--clr-text3); }
  .qa-arrow { margin-left: auto; color: var(--clr-text3); font-size: 0.75rem; }

  @media (max-width: 1200px) { .summary-grid { grid-template-columns: repeat(2, 1fr); } }
  @media (max-width: 900px) {
    .charts-row, .bottom-row { grid-template-columns: 1fr; }
  }
</style>
@endsection

@section('content')
<!-- Summary Stats -->
<div class="summary-grid">
  <!-- Total Reports -->
  <div class="summary-card">
    <div class="bar-bg" style="background: linear-gradient(90deg,#3b82f6,#60a5fa);"></div>
    <div class="summary-card-top">
      <div class="summary-icon" style="background:rgba(59,130,246,0.12); color:#3b82f6;"><i class="fas fa-file-alt"></i></div>
      <div class="summary-trend trend-up"><i class="fas fa-arrow-up"></i> Dynamic</div>
    </div>
    <div class="summary-num">{{ number_format($totalReports) }}</div>
    <div class="summary-label">Total Reports</div>
  </div>
  
  <!-- Pending Reports -->
  <div class="summary-card">
    <div class="bar-bg" style="background: linear-gradient(90deg,#f59e0b,#fbbf24);"></div>
    <div class="summary-card-top">
      <div class="summary-icon" style="background:rgba(245,158,11,0.12); color:#f59e0b;"><i class="fas fa-clock"></i></div>
      <div class="summary-trend trend-up"><i class="fas fa-clock"></i> Active</div>
    </div>
    <div class="summary-num" style="color:#f59e0b;">{{ number_format($pendingCount) }}</div>
    <div class="summary-label">Pending Reports</div>
  </div>
  
  <!-- Resolved Reports -->
  <div class="summary-card">
    <div class="bar-bg" style="background: linear-gradient(90deg,#22c55e,#4ade80);"></div>
    <div class="summary-card-top">
      <div class="summary-icon" style="background:rgba(34,197,94,0.12); color:#22c55e;"><i class="fas fa-check-circle"></i></div>
      <div class="summary-trend trend-up"><i class="fas fa-arrow-up"></i> resolved</div>
    </div>
    <div class="summary-num" style="color:#22c55e;">{{ number_format($resolvedCount) }}</div>
    <div class="summary-label">Resolved Reports</div>
  </div>
  
  <!-- Registered Users -->
  <div class="summary-card">
    <div class="bar-bg" style="background: linear-gradient(90deg,#a855f7,#c084fc);"></div>
    <div class="summary-card-top">
      <div class="summary-icon" style="background:rgba(168,85,247,0.12); color:#a855f7;"><i class="fas fa-users"></i></div>
      <div class="summary-trend trend-up"><i class="fas fa-arrow-up"></i> citizens</div>
    </div>
    <div class="summary-num" style="color:#a855f7;">{{ number_format($usersCount) }}</div>
    <div class="summary-label">Registered Citizens</div>
  </div>
</div>

<!-- Charts Row -->
<div class="charts-row">
  <!-- Bar Chart: Monthly Resolved -->
  <div class="chart-card">
    <div class="chart-card-header">
      <h3><i class="fas fa-chart-bar" style="color:#3b82f6; margin-right:8px;"></i>Reports Resolved by Month</h3>
      <select id="year-select">
        <option>2026</option>
        <option>2025</option>
      </select>
    </div>
    <canvas id="monthlyChart" height="220"></canvas>
  </div>

  <!-- Doughnut: Category Breakdown -->
  @php
    $sumCategories = max(($organicCount + $recyclableCount + $hazardousCount + $residualCount), 1);
    
    $organicPct = round(($organicCount / $sumCategories) * 100);
    $recyclablePct = round(($recyclableCount / $sumCategories) * 100);
    $hazardousPct = round(($hazardousCount / $sumCategories) * 100);
    $residualPct = round(($residualCount / $sumCategories) * 100);
  @endphp
  <div class="chart-card">
    <div class="chart-card-header">
      <h3><i class="fas fa-chart-pie" style="color:#a855f7; margin-right:8px;"></i>By Category</h3>
    </div>
    <canvas id="catChart" height="180"></canvas>
    <div class="cat-list" style="margin-top:20px;">
      <div class="cat-row">
        <div class="cat-row-top">
          <span><span class="cat-dot" style="background:#ef4444;"></span>Hazardous</span>
          <span style="font-weight:700;">{{ $hazardousCount }} ({{ $hazardousPct }}%)</span>
        </div>
        <div class="cat-bar-bg"><div class="cat-bar-fill" style="width:{{ $hazardousPct }}%; background:#ef4444;"></div></div>
      </div>
      <div class="cat-row">
        <div class="cat-row-top">
          <span><span class="cat-dot" style="background:#3b82f6;"></span>Recyclable</span>
          <span style="font-weight:700;">{{ $recyclableCount }} ({{ $recyclablePct }}%)</span>
        </div>
        <div class="cat-bar-bg"><div class="cat-bar-fill" style="width:{{ $recyclablePct }}%; background:#3b82f6;"></div></div>
      </div>
      <div class="cat-row">
        <div class="cat-row-top">
          <span><span class="cat-dot" style="background:#22c55e;"></span>Organic</span>
          <span style="font-weight:700;">{{ $organicCount }} ({{ $organicPct }}%)</span>
        </div>
        <div class="cat-bar-bg"><div class="cat-bar-fill" style="width:{{ $organicPct }}%; background:#22c55e;"></div></div>
      </div>
      <div class="cat-row">
        <div class="cat-row-top">
          <span><span class="cat-dot" style="background:#a855f7;"></span>Residual</span>
          <span style="font-weight:700;">{{ $residualCount }} ({{ $residualPct }}%)</span>
        </div>
        <div class="cat-bar-bg"><div class="cat-bar-fill" style="width:{{ $residualPct }}%; background:#a855f7;"></div></div>
      </div>
    </div>
  </div>
</div>

<!-- Bottom Row -->
<div class="bottom-row">
  <!-- Recent Reports -->
  <div class="chart-card">
    <div class="chart-card-header">
      <h3><i class="fas fa-clock" style="color:#f59e0b; margin-right:8px;"></i>Recent Pending Reports</h3>
      <a href="{{ route('admin.reports') }}" class="btn btn-ghost btn-sm">View All</a>
    </div>
    <div class="table-wrapper">
      @if($recentReports->isEmpty())
        <div style="text-align:center; padding: 24px; color: var(--clr-text3);">
          No pending reports at this time. Good job!
        </div>
      @else
        <table class="mini-table">
          <thead>
            <tr>
              <th>ID</th>
              <th>Title</th>
              <th>Category</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            @foreach($recentReports as $report)
              <tr>
                <td>#{{ sprintf('%03d', $report->id) }}</td>
                <td>{{ $report->title }}</td>
                <td>
                  <span class="badge 
                    @if($report->category == 'organic') cat-organic
                    @elseif($report->category == 'recyclable') cat-recyclable
                    @elseif($report->category == 'hazardous') cat-hazardous
                    @else cat-residual
                    @endif" style="font-size:0.7rem;">
                    @if($report->category == 'organic') 🌿 Organic
                    @elseif($report->category == 'recyclable') ♻️ Recyclable
                    @elseif($report->category == 'hazardous') ⚠️ Hazardous
                    @else 🗑️ Residual
                    @endif
                  </span>
                </td>
                <td>
                  <a href="{{ route('admin.reports.show', $report->id) }}" class="btn btn-ghost btn-sm" style="padding:4px 10px; font-size:0.75rem;">Review</a>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      @endif
    </div>
  </div>

  <!-- Quick Actions -->
  <div class="chart-card">
    <div class="chart-card-header">
      <h3><i class="fas fa-bolt" style="color:#f59e0b; margin-right:8px;"></i>Quick Actions</h3>
    </div>
    <div class="quick-actions">
      <a href="{{ route('admin.reports') }}" class="quick-action-btn">
        <div class="qa-icon" style="background:rgba(245,158,11,0.12); color:#f59e0b;"><i class="fas fa-tasks"></i></div>
        <div class="qa-text">
          <strong>Manage Reports</strong>
          <span>Review, update status &amp; add remarks</span>
        </div>
        <i class="fas fa-chevron-right qa-arrow"></i>
      </a>
      <a href="#" class="quick-action-btn">
        <div class="qa-icon" style="background:rgba(168,85,247,0.12); color:#a855f7;"><i class="fas fa-users-cog"></i></div>
        <div class="qa-text">
          <strong>Manage Users</strong>
          <span>View, activate or deactivate accounts</span>
        </div>
        <i class="fas fa-chevron-right qa-arrow"></i>
      </a>
      <a href="#" class="quick-action-btn">
        <div class="qa-icon" style="background:rgba(34,197,94,0.12); color:#22c55e;"><i class="fas fa-file-export"></i></div>
        <div class="qa-text">
          <strong>Export Data</strong>
          <span>Download reports as CSV or PDF</span>
        </div>
        <i class="fas fa-chevron-right qa-arrow"></i>
      </a>
      <a href="{{ route('home') }}" class="quick-action-btn" target="_blank">
        <div class="qa-icon" style="background:rgba(20,184,166,0.12); color:#14b8a6;"><i class="fas fa-globe"></i></div>
        <div class="qa-text">
          <strong>View Public Site</strong>
          <span>See the user-facing WasteWatch portal</span>
        </div>
        <i class="fas fa-chevron-right qa-arrow"></i>
      </a>
    </div>
  </div>
</div>
@endsection

@section('scripts')
<script>
  // Monthly resolved bar chart
  const mCtx = document.getElementById('monthlyChart').getContext('2d');
  new Chart(mCtx, {
    type: 'bar',
    data: {
      labels: ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'],
      datasets: [{
        label: 'Resolved',
        data: @json($monthlyResolvedData),
        backgroundColor: 'rgba(59,130,246,0.7)',
        borderRadius: 6,
        borderSkipped: false,
      },{
        label: 'Pending',
        data: @json($monthlyPendingData),
        backgroundColor: 'rgba(245,158,11,0.6)',
        borderRadius: 6,
        borderSkipped: false,
      }]
    },
    options: {
      responsive: true, maintainAspectRatio: false,
      plugins: { legend: { labels: { color: '#8b949e', font: { size: 11 } } } },
      scales: {
        x: { grid: { color: 'rgba(48,64,80,0.4)' }, ticks: { color: '#8b949e', font: { size: 11 } } },
        y: { grid: { color: 'rgba(48,64,80,0.4)' }, ticks: { color: '#8b949e', font: { size: 11 } } }
      }
    }
  });

  // Category doughnut
  const cCtx = document.getElementById('catChart').getContext('2d');
  new Chart(cCtx, {
    type: 'doughnut',
    data: {
      labels: ['Hazardous','Recyclable','Organic','Residual'],
      datasets: [{
        data: [{{ $hazardousCount }}, {{ $recyclableCount }}, {{ $organicCount }}, {{ $residualCount }}],
        backgroundColor: ['#ef4444','#3b82f6','#22c55e','#a855f7'],
        borderWidth: 3,
        borderColor: '#1c2333',
        hoverBorderColor: '#1c2333',
      }]
    },
    options: {
      responsive: true, maintainAspectRatio: false, cutout: '68%',
      plugins: {
        legend: { display: false },
        tooltip: { bodyColor: '#e6edf3', backgroundColor: '#21262d', borderColor: '#30405080', borderWidth: 1 }
      }
    }
  });
</script>
@endsection
