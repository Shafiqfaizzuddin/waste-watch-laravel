@extends('layouts.dashboard')

@section('title', 'Edit Report – WasteWatch')

@section('styles')
<style>
  /* Status Alert Banner */
  .status-alert {
    display: flex; align-items: center; gap: 14px;
    padding: 16px 20px;
    border-radius: var(--radius-md);
    margin-bottom: 28px;
    font-size: 0.88rem;
  }
  .status-alert.pending {
    background: rgba(245,158,11,0.08);
    border: 1px solid rgba(245,158,11,0.25);
  }
  .status-alert i { font-size: 1.1rem; }
  .status-alert strong { display: block; margin-bottom: 2px; }
  .status-alert span { color: var(--clr-text2); }

  /* Report Meta */
  .report-meta {
    display: flex; flex-wrap: wrap; gap: 20px;
    padding: 20px 24px;
    background: var(--clr-bg2);
    border: 1px solid var(--clr-border);
    border-radius: var(--radius-md);
    margin-bottom: 24px;
  }
  .meta-item label { font-size: 0.72rem; font-weight: 700; letter-spacing: 0.06em; text-transform: uppercase; color: var(--clr-text3); display: block; margin-bottom: 4px; }
  .meta-item span { font-size: 0.9rem; font-weight: 500; }

  /* Form Card */
  .form-card { background: var(--clr-surface); border: 1px solid var(--clr-border); border-radius: var(--radius-xl); overflow: hidden; }
  .form-card-header { padding: 24px 32px; background: var(--clr-bg2); border-bottom: 1px solid var(--clr-border); }
  .form-card-header h2 { font-size: 1.2rem; font-weight: 800; margin-bottom: 4px; }
  .form-card-header p { font-size: 0.85rem; color: var(--clr-text2); }
  .form-card-body { padding: 32px; }

  /* Category Selector */
  .category-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 12px; }
  .cat-option { display: none; }
  .cat-label { display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 10px; padding: 20px 12px; background: var(--clr-bg); border: 2px solid var(--clr-border); border-radius: var(--radius-md); cursor: pointer; transition: all var(--transition); text-align: center; }
  .cat-label .cat-emoji { font-size: 1.8rem; }
  .cat-label .cat-name { font-size: 0.8rem; font-weight: 700; }
  .cat-label .cat-desc { font-size: 0.72rem; color: var(--clr-text3); line-height: 1.4; }
  #cat-organic:checked + .cat-label   { border-color: #22c55e; background: rgba(34,197,94,0.08); color: #22c55e; }
  #cat-recyclable:checked + .cat-label { border-color: #3b82f6; background: rgba(59,130,246,0.08); color: #3b82f6; }
  #cat-hazardous:checked + .cat-label  { border-color: #ef4444; background: rgba(239,68,68,0.08); color: #ef4444; }
  #cat-residual:checked + .cat-label   { border-color: #a855f7; background: rgba(168,85,247,0.08); color: #a855f7; }

  /* Photo Area */
  .photo-previews { display: grid; grid-template-columns: repeat(3, 1fr); gap: 12px; margin-top: 16px; }
  .photo-preview { aspect-ratio: 1; border-radius: var(--radius-md); background: var(--clr-bg); border: 2px dashed var(--clr-border); display: flex; flex-direction: column; align-items: center; justify-content: center; color: var(--clr-text3); font-size: 0.78rem; gap: 8px; cursor: pointer; transition: all var(--transition); position: relative; overflow: hidden; }
  .photo-preview:hover { border-color: var(--clr-primary); }
  .photo-preview.has-photo { border-style: solid; border-color: var(--clr-border); }
  .photo-preview img { position: absolute; inset:0; width:100%; height:100%; object-fit:cover; }
  .photo-remove { position: absolute; top: 6px; right: 6px; width: 22px; height: 22px; background: rgba(239,68,68,0.85); border-radius: 50%; display:flex; align-items:center; justify-content:center; font-size:0.7rem; color:#fff; cursor:pointer; opacity: 0; transition: opacity var(--transition); }
  .photo-preview:hover .photo-remove { opacity: 1; }

  .form-footer { padding: 24px 32px; background: var(--clr-bg2); border-top: 1px solid var(--clr-border); display: flex; justify-content: space-between; align-items: center; }
  .form-footer-note { font-size: 0.82rem; color: var(--clr-text3); display: flex; align-items: center; gap: 8px; }
  .char-count { font-size: 0.78rem; color: var(--clr-text3); text-align: right; margin-top: 6px; }

  @media (max-width: 900px) {
    .category-grid { grid-template-columns: repeat(2, 1fr); }
  }
</style>
@endsection

@section('content')
<!-- Breadcrumb -->
<div style="margin-bottom:24px;">
  <div style="display:flex; align-items:center; gap:8px; font-size:0.82rem; color:var(--clr-text3); margin-bottom:8px;">
    <a href="{{ route('dashboard') }}" style="color:var(--clr-text3);">Dashboard</a>
    <i class="fas fa-chevron-right" style="font-size:0.65rem;"></i>
    <span style="color:var(--clr-text);">Report #{{ sprintf('%03d', $report->id) }}</span>
  </div>
  
  @if($report->status == 'pending')
    <h1 style="font-size:1.6rem; font-weight:800;">Edit Incident Report</h1>
    <p style="color:var(--clr-text2); font-size:0.9rem;">You can only edit reports that are in <strong style="color:var(--clr-amber);">Pending</strong> status.</p>
  @else
    <h1 style="font-size:1.6rem; font-weight:800;">View Incident Report</h1>
    <p style="color:var(--clr-text2); font-size:0.9rem;">This report is locked. Editing is disabled as it is already being processed.</p>
  @endif
</div>

<!-- Status Alert -->
@if($report->status == 'pending')
  <div class="status-alert pending">
    <i class="fas fa-clock" style="color:var(--clr-amber);"></i>
    <div>
      <strong style="color:var(--clr-amber);">Status: Pending Review</strong>
      <span>This report has not yet been actioned. You may edit or delete it.</span>
    </div>
    <span class="badge badge-pending" style="margin-left:auto;">⏳ Pending</span>
  </div>
@elseif($report->status == 'under_review')
  <div class="status-alert" style="background: rgba(59,130,246,0.08); border: 1px solid rgba(59,130,246,0.25);">
    <i class="fas fa-search" style="color:var(--clr-blue);"></i>
    <div>
      <strong style="color:var(--clr-blue);">Status: Under Review</strong>
      <span>An administrator is currently reviewing this incident report. Editing is disabled.</span>
    </div>
    <span class="badge badge-review" style="margin-left:auto;">🔍 Under Review</span>
  </div>
@elseif($report->status == 'resolved')
  <div class="status-alert" style="background: rgba(34,197,94,0.08); border: 1px solid rgba(34,197,94,0.25);">
    <i class="fas fa-check-circle" style="color:var(--clr-primary);"></i>
    <div>
      <strong style="color:var(--clr-primary);">Status: Resolved</strong>
      <span>This issue has been resolved by authorities. See admin remarks below.</span>
    </div>
    <span class="badge badge-resolved" style="margin-left:auto;">✅ Resolved</span>
  </div>
@elseif($report->status == 'rejected')
  <div class="status-alert" style="background: rgba(239,68,68,0.08); border: 1px solid rgba(239,68,68,0.25);">
    <i class="fas fa-times-circle" style="color:var(--clr-red);"></i>
    <div>
      <strong style="color:var(--clr-red);">Status: Rejected</strong>
      <span>This report was rejected. Review the remarks below for more details.</span>
    </div>
    <span class="badge badge-rejected" style="margin-left:auto;">❌ Rejected</span>
  </div>
@endif

<!-- Report Meta -->
<div class="report-meta">
  <div class="meta-item">
    <label>Report ID</label>
    <span>#{{ sprintf('%03d', $report->id) }}</span>
  </div>
  <div class="meta-item">
    <label>Submitted</label>
    <span>{{ $report->created_at->format('d M Y, h:i A') }}</span>
  </div>
  <div class="meta-item">
    <label>Last Updated</label>
    <span>{{ $report->updated_at->format('d M Y, h:i A') }}</span>
  </div>
  <div class="meta-item">
    <label>Photos Attached</label>
    <span>{{ is_array($report->photos) ? count($report->photos) : 0 }} of 3</span>
  </div>
</div>

@php
  $disabled = $report->status != 'pending' ? 'disabled' : '';
@endphp

<!-- Edit Form -->
<form method="POST" action="{{ route('reports.update', $report->id) }}" enctype="multipart/form-data" id="edit-form" novalidate>
  @csrf
  @method('PUT')

  @if ($errors->any())
    <div style="background: rgba(239,68,68,0.1); border: 1px solid rgba(239,68,68,0.25); border-radius: var(--radius-sm); padding: 12px 16px; margin-bottom: 20px; font-size: 0.82rem; color: #f87171;">
      <ul style="padding-left: 14px; list-style: none;">
        @foreach ($errors->all() as $error)
          <li><i class="fas fa-exclamation-circle"></i> {{ $error }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  <div class="form-card">
    <div class="form-card-header">
      <h2><i class="fas fa-edit" style="color:var(--clr-primary); margin-right:8px;"></i>Report Details</h2>
      <p>
        @if($report->status == 'pending')
          Update the fields below. Changes are saved only when you click "Save Changes".
        @else
          Form fields are view-only since this report is no longer pending.
        @endif
      </p>
    </div>
    <div class="form-card-body">

      <!-- Title -->
      <div class="form-group">
        <label class="form-label" for="edit-title">Report Title <span style="color:var(--clr-red);">*</span></label>
        <input type="text" name="title" id="edit-title" class="form-control" value="{{ old('title', $report->title) }}" maxlength="120" oninput="updateChar(this,'title-count',120)" {{ $disabled }} required/>
        <div class="char-count"><span id="title-count">0</span>/120</div>
      </div>

      <!-- Description -->
      <div class="form-group">
        <label class="form-label" for="edit-desc">Description <span style="color:var(--clr-red);">*</span></label>
        <textarea name="description" id="edit-desc" class="form-control" rows="5" maxlength="1000" oninput="updateChar(this,'desc-count',1000)" {{ $disabled }} required>{{ old('description', $report->description) }}</textarea>
        <div class="char-count"><span id="desc-count">0</span>/1000</div>
      </div>

      <!-- Category -->
      <div class="form-group">
        <label class="form-label">Waste Category <span style="color:var(--clr-red);">*</span></label>
        <div class="category-grid">
          <div>
            <input type="radio" name="category" id="cat-organic" class="cat-option" value="organic" {{ old('category', $report->category) == 'organic' ? 'checked' : '' }} {{ $disabled }} required/>
            <label class="cat-label" for="cat-organic">
              <span class="cat-emoji">🌿</span>
              <span class="cat-name">Organic</span>
              <span class="cat-desc">Food waste, garden matter</span>
            </label>
          </div>
          <div>
            <input type="radio" name="category" id="cat-recyclable" class="cat-option" value="recyclable" {{ old('category', $report->category) == 'recyclable' ? 'checked' : '' }} {{ $disabled }}/>
            <label class="cat-label" for="cat-recyclable">
              <span class="cat-emoji">♻️</span>
              <span class="cat-name">Recyclable</span>
              <span class="cat-desc">Paper, glass, plastic, metal</span>
            </label>
          </div>
          <div>
            <input type="radio" name="category" id="cat-hazardous" class="cat-option" value="hazardous" {{ old('category', $report->category) == 'hazardous' ? 'checked' : '' }} {{ $disabled }}/>
            <label class="cat-label" for="cat-hazardous">
              <span class="cat-emoji">⚠️</span>
              <span class="cat-name">Hazardous</span>
              <span class="cat-desc">Chemicals, e-waste, batteries</span>
            </label>
          </div>
          <div>
            <input type="radio" name="category" id="cat-residual" class="cat-option" value="residual" {{ old('category', $report->category) == 'residual' ? 'checked' : '' }} {{ $disabled }}/>
            <label class="cat-label" for="cat-residual">
              <span class="cat-emoji">🗑️</span>
              <span class="cat-name">Residual</span>
              <span class="cat-desc">Non-recyclable, mixed waste</span>
            </label>
          </div>
        </div>
      </div>

      <!-- Date -->
      <div class="form-group">
        <label class="form-label" for="edit-date">Date of Incident <span style="color:var(--clr-red);">*</span></label>
        <div style="position:relative;">
          <i class="fas fa-calendar-alt" style="position:absolute; left:14px; top:50%; transform:translateY(-50%); color:var(--clr-text3); pointer-events:none;"></i>
          <input type="date" name="date_of_incident" id="edit-date" class="form-control" style="padding-left:44px;" value="{{ old('date_of_incident', $report->date_of_incident) }}" {{ $disabled }} required/>
        </div>
      </div>

      <!-- Location -->
      <div class="form-group">
        <label class="form-label" for="edit-location">Location Address</label>
        <div style="position:relative;">
          <i class="fas fa-map-marker-alt" style="position:absolute; left:14px; top:50%; transform:translateY(-50%); color:var(--clr-text3); pointer-events:none;"></i>
          @php
            $loc = $report->address;
            if ($report->city) $loc .= ', ' . $report->city;
            if ($report->state) $loc .= ', ' . $report->state;
            if (!$loc && $report->latitude) $loc = $report->latitude . ', ' . $report->longitude;
          @endphp
          <input type="text" name="address" id="edit-location" class="form-control" style="padding-left:44px;" value="{{ old('address', $loc) }}" {{ $disabled }}/>
        </div>
      </div>

      <!-- Photographs -->
      <div class="form-group">
        <label class="form-label">Photographs</label>
        @if($report->status == 'pending')
          <p style="font-size:0.82rem; color:var(--clr-text2); margin-bottom:12px;">Click on an empty slot to add a photo, or hover over an existing photo to remove it.</p>
        @endif
        
        <div class="photo-previews">
          @php
            $photosList = is_array($report->photos) ? $report->photos : (json_decode($report->photos, true) ?: []);
          @endphp
          @for($i = 0; $i < 3; $i++)
            @php
              $photoPath = $photosList[$i] ?? null;
            @endphp
            @if($photoPath)
              <div class="photo-preview has-photo" id="prev-{{ $i+1 }}">
                <img src="{{ Str::startsWith($photoPath, 'http') ? $photoPath : asset('storage/' . $photoPath) }}" alt="Photo {{ $i+1 }}"/>
                @if($report->status == 'pending')
                  <input type="hidden" name="existing_photos[]" value="{{ $photoPath }}"/>
                  <div class="photo-remove" onclick="removePhoto({{ $i+1 }})"><i class="fas fa-times"></i></div>
                @endif
              </div>
            @else
              <div class="photo-preview" id="prev-{{ $i+1 }}" @if($report->status == 'pending') onclick="document.getElementById('add-photo-{{ $i+1 }}').click()" @endif>
                <i class="fas fa-plus" style="font-size:1.3rem;"></i>
                <span>Add Photo</span>
                @if($report->status == 'pending')
                  <input type="file" name="photos[]" id="add-photo-{{ $i+1 }}" accept="image/jpeg,image/png" style="display:none;" onchange="addPhoto(this,{{ $i+1 }})"/>
                @endif
              </div>
            @endif
          @endfor
        </div>
      </div>

      <!-- Admin Remarks (Visible only if there are remarks or status is resolved/rejected) -->
      @if($report->status != 'pending')
        <div class="form-group" style="margin-top:24px; border-top: 1px solid var(--clr-border); padding-top:24px;">
          <label class="form-label" style="color:var(--clr-primary);">Official Admin Remarks</label>
          <div style="background:var(--clr-bg); border:1px solid var(--clr-border); border-radius:var(--radius-md); padding:16px; min-height:60px;">
            @if($report->admin_remarks)
              <p style="font-size:0.9rem; color:var(--clr-text);">{{ $report->admin_remarks }}</p>
            @else
              <p style="font-size:0.9rem; color:var(--clr-text3); font-style:italic;">No official remarks have been added yet.</p>
            @endif
          </div>
        </div>
      @endif

    </div>

    <!-- Form Footer -->
    <div class="form-footer">
      <div class="form-footer-note">
        <i class="fas fa-history" style="color:var(--clr-primary);"></i>
        Last updated: {{ $report->updated_at->format('d M Y, h:i A') }}
      </div>
      <div style="display:flex; gap:12px;">
        <a href="{{ route('dashboard') }}" class="btn btn-ghost">Discard</a>
        @if($report->status == 'pending')
          <button type="submit" class="btn btn-primary" id="save-btn">
            <i class="fas fa-save"></i> Save Changes
          </button>
        @endif
      </div>
    </div>
  </div>
</form>

@if($report->status == 'pending')
  <!-- Delete form -->
  <form id="delete-form" action="{{ route('reports.destroy', $report->id) }}" method="POST" style="display:none;">
    @csrf
    @method('DELETE')
  </form>
@endif
@endsection

@section('scripts')
<script>
  function updateChar(el, id, max) {
    document.getElementById(id).textContent = el.value.length;
  }

  document.addEventListener('DOMContentLoaded', () => {
    updateChar(document.getElementById('edit-title'), 'title-count', 120);
    updateChar(document.getElementById('edit-desc'), 'desc-count', 1000);
  });

  function removePhoto(n) {
    const p = document.getElementById('prev-' + n);
    p.innerHTML = '<i class="fas fa-plus" style="font-size:1.3rem;"></i><span>Add Photo</span><input type="file" name="photos[]" accept="image/jpeg,image/png" style="display:none;" onchange="addPhoto(this,' + n + ')"/>';
    p.classList.remove('has-photo');
    p.onclick = () => p.querySelector('input').click();
  }

  function addPhoto(input, n) {
    if (!input.files[0]) return;
    const reader = new FileReader();
    reader.onload = e => {
      const p = document.getElementById('prev-' + n);
      p.innerHTML = `<img src="${e.target.result}" alt="Photo ${n}"/><div class="photo-remove" onclick="removePhoto(${n})"><i class="fas fa-times"></i></div>`;
      p.classList.add('has-photo');
      p.onclick = null;
    };
    reader.readAsDataURL(input.files[0]);
  }
</script>
@endsection
