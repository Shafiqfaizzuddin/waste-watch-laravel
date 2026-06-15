@extends('layouts.admin')

@section('title', 'Review Incident Report – WasteWatch Admin')
@section('topbar-title', 'Review Report #' . sprintf('%03d', $report->id))

@section('styles')
<style>
  .review-layout { display: grid; grid-template-columns: 2fr 1fr; gap: 24px; }
  .detail-section { margin-bottom: 28px; }
  .detail-section h4 { font-size: 0.85rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; color: var(--clr-text3); margin-bottom: 12px; border-bottom: 1px solid var(--clr-border); padding-bottom: 6px; }
  .meta-value { font-size: 0.95rem; line-height: 1.6; margin-bottom: 16px; }
  
  .photo-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 14px; margin-top: 14px; }
  .photo-item { aspect-ratio: 1.2; border-radius: var(--radius-md); overflow: hidden; border: 1px solid var(--clr-border); cursor: pointer; transition: all var(--transition); position: relative; }
  .photo-item:hover { transform: scale(1.03); border-color: #3b82f6; }
  .photo-item img { width: 100%; height: 100%; object-fit: cover; }
  
  .map-box { background: var(--clr-bg); border: 1px solid var(--clr-border); border-radius: var(--radius-md); padding: 16px; display: flex; align-items: center; gap: 16px; }
  .map-icon { width: 44px; height: 44px; border-radius: 50%; background: rgba(59,130,246,0.12); color: #3b82f6; display: flex; align-items: center; justify-content: center; font-size: 1.2rem; flex-shrink: 0; }
  
  .status-badge-lg { display: inline-flex; align-items: center; gap: 8px; padding: 6px 16px; border-radius: 50px; font-weight: 700; font-size: 0.85rem; }
  
  .history-timeline { display: flex; flex-direction: column; gap: 16px; position: relative; padding-left: 20px; }
  .history-timeline::before { content: ''; position: absolute; left: 6px; top: 4px; bottom: 4px; width: 2px; background: var(--clr-border); }
  .history-item { position: relative; font-size: 0.82rem; }
  .history-dot { position: absolute; left: -19px; top: 4px; width: 10px; height: 10px; border-radius: 50%; background: var(--clr-border); border: 2px solid var(--clr-surface); }
  .history-dot.active { background: #3b82f6; box-shadow: 0 0 0 3px rgba(59,130,246,0.2); }
  .history-time { color: var(--clr-text3); font-size: 0.75rem; margin-top: 2px; }

  @media (max-width: 950px) {
    .review-layout { grid-template-columns: 1fr; }
  }
</style>
@endsection

@section('content')
<div style="margin-bottom: 24px;">
  <a href="{{ route('admin.reports') }}" style="color:var(--clr-text3); font-size:0.85rem; text-decoration:none;"><i class="fas fa-arrow-left"></i> Back to Incident Reports List</a>
</div>

<div class="review-layout">
  <!-- Left Side: Report Details -->
  <div>
    <div class="card" style="margin-bottom: 24px;">
      <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:24px;">
        <span class="status-badge-lg 
          @if($report->status == 'pending') badge-pending
          @elseif($report->status == 'under_review') badge-review
          @elseif($report->status == 'resolved') badge-resolved
          @else badge-rejected
          @endif">
          @if($report->status == 'pending') ⏳ Pending Review
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
          @if($report->category == 'organic') 🌿 Organic Waste
          @elseif($report->category == 'recyclable') ♻️ Recyclable Waste
          @elseif($report->category == 'hazardous') ⚠️ Hazardous Waste
          @else 🗑️ Residual Waste
          @endif
        </span>
      </div>

      <h2 style="font-size: 1.4rem; font-weight:800; margin-bottom: 20px;">{{ $report->title }}</h2>

      <!-- Details Block -->
      <div class="detail-section">
        <h4>Incident Description</h4>
        <p class="meta-value" style="white-space: pre-wrap;">{{ $report->description }}</p>
      </div>

      <!-- Location Section -->
      <div class="detail-section">
        <h4>Incident Location</h4>
        <div class="meta-value">
          <p style="font-weight: 500; margin-bottom: 8px;">
            <i class="fas fa-map-marker-alt" style="color:#ef4444; margin-right:6px;"></i>
            {{ $report->address ? $report->address . ', ' : '' }}{{ $report->city ?? 'N/A' }}, {{ $report->state ?? 'Malaysia' }}
          </p>
          <div class="map-box">
            <div class="map-icon"><i class="fas fa-crosshairs"></i></div>
            <div>
              <strong style="display:block; font-size:0.85rem;">GPS Coordinates</strong>
              <span style="font-size:0.8rem; color:var(--clr-text3);">Latitude: {{ $report->latitude ?? 'N/A' }} | Longitude: {{ $report->longitude ?? 'N/A' }}</span>
            </div>
            @if($report->latitude)
              <a href="https://www.google.com/maps/search/?api=1&query={{ $report->latitude }},{{ $report->longitude }}" target="_blank" class="btn btn-ghost btn-sm" style="margin-left:auto;">
                <i class="fas fa-map"></i> Open Google Maps
              </a>
            @endif
          </div>
        </div>
      </div>

      <!-- Attachments Section -->
      <div class="detail-section">
        <h4>Citizen Attachments</h4>
        @php
          $photosList = is_array($report->photos) ? $report->photos : json_decode($report->photos, true) ?: [];
        @endphp
        @if(empty($photosList))
          <p style="color:var(--clr-text3); font-style:italic; font-size:0.88rem;">No photographs were uploaded with this incident report.</p>
        @else
          <div class="photo-grid">
            @foreach($photosList as $idx => $photoPath)
              <div class="photo-item" onclick="openLightbox('{{ Str::startsWith($photoPath, 'http') ? $photoPath : asset('storage/' . $photoPath) }}')">
                <img src="{{ Str::startsWith($photoPath, 'http') ? $photoPath : asset('storage/' . $photoPath) }}" alt="Incident Photo {{ $idx+1 }}"/>
              </div>
            @endforeach
          </div>
        @endif
      </div>
    </div>
  </div>

  <!-- Right Side: Status Updates & Actions -->
  <div>
    <!-- Citizen Info Card -->
    <div class="card" style="margin-bottom: 24px; border-left: 4px solid #3b82f6;">
      <h3 style="font-size: 0.95rem; font-weight:700; margin-bottom: 12px;"><i class="fas fa-user-circle"></i> Reporter Contact</h3>
      <div style="font-size:0.85rem; line-height: 1.6;">
        <p><strong>Name:</strong> {{ $report->user->first_name }} {{ $report->user->last_name }}</p>
        <p><strong>Email:</strong> {{ $report->user->email }}</p>
        <p><strong>Phone:</strong> {{ $report->user->phone ?? 'N/A' }}</p>
        <p><strong>Home State:</strong> {{ $report->user->state ?? 'N/A' }}</p>
      </div>
    </div>

    <!-- Update Action Card -->
    <div class="card" style="border-left: 4px solid #f59e0b;">
      <h3 style="font-size: 0.95rem; font-weight:700; margin-bottom: 16px;"><i class="fas fa-tools"></i> Take Action</h3>
      
      <form method="POST" action="{{ route('admin.reports.update', $report->id) }}">
        @csrf
        @method('PATCH')

        <div class="form-group">
          <label class="form-label" for="admin-status">Assign Status</label>
          <select name="status" id="admin-status" class="form-control" style="background:var(--clr-bg);" required>
            <option value="pending" {{ $report->status == 'pending' ? 'selected' : '' }}>⏳ Pending Review</option>
            <option value="under_review" {{ $report->status == 'under_review' ? 'selected' : '' }}>🔍 Under Review</option>
            <option value="resolved" {{ $report->status == 'resolved' ? 'selected' : '' }}>✅ Resolved</option>
            <option value="rejected" {{ $report->status == 'rejected' ? 'selected' : '' }}>❌ Rejected</option>
          </select>
        </div>

        <div class="form-group">
          <label class="form-label" for="remarks">Official Remarks</label>
          <textarea name="admin_remarks" id="remarks" rows="4" class="form-control" placeholder="Provide remarks for the citizen to see on their dashboard..." style="background:var(--clr-bg);">{{ $report->admin_remarks }}</textarea>
        </div>

        <button type="submit" class="btn btn-admin w-full" style="width:100%; padding:12px; margin-top:8px; display:inline-flex; align-items:center; justify-content:center; gap:8px;">
          <i class="fas fa-save"></i> Save Resolution
        </button>
      </form>
    </div>
  </div>
</div>

<!-- Simple Lightbox Modal -->
<div id="lightbox" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.9); z-index:9999; align-items:center; justify-content:center; padding: 24px;" onclick="closeLightbox()">
  <span style="position:absolute; top:20px; right:24px; color:#fff; font-size:2rem; cursor:pointer;"><i class="fas fa-times"></i></span>
  <img id="lightbox-img" src="" alt="Lightbox Preview" style="max-width:100%; max-height:85vh; border-radius:var(--radius-md); box-shadow:0 12px 48px rgba(0,0,0,0.5);"/>
</div>

<script>
  function openLightbox(src) {
    const lightbox = document.getElementById('lightbox');
    const img = document.getElementById('lightbox-img');
    img.src = src;
    lightbox.style.display = 'flex';
  }

  function closeLightbox() {
    document.getElementById('lightbox').style.display = 'none';
  }
</script>
@endsection
