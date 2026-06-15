@extends('layouts.dashboard')

@section('title', 'Submit Incident Report – WasteWatch')

@section('styles')
<style>
  /* Progress Steps */
  .form-progress {
    display: flex; align-items: center;
    margin-bottom: 36px;
  }
  .progress-step {
    display: flex; align-items: center; gap: 10px;
  }
  .progress-circle {
    width: 34px; height: 34px; border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    font-size: 0.82rem; font-weight: 700;
    border: 2px solid var(--clr-border);
    color: var(--clr-text3);
    transition: all var(--transition);
  }
  .progress-circle.active { border-color: var(--clr-primary); color: var(--clr-primary); background: rgba(34,197,94,0.1); }
  .progress-circle.done { border-color: var(--clr-primary); background: var(--clr-primary); color: #fff; }
  .progress-label { font-size: 0.82rem; font-weight: 600; color: var(--clr-text3); }
  .progress-label.active { color: var(--clr-text); }
  .progress-line { flex: 1; height: 2px; background: var(--clr-border); margin: 0 14px; }
  .progress-line.done { background: var(--clr-primary); }

  /* Form Card */
  .form-card {
    background: var(--clr-surface);
    border: 1px solid var(--clr-border);
    border-radius: var(--radius-xl);
    overflow: hidden;
  }
  .form-card-header {
    padding: 24px 32px;
    background: var(--clr-bg2);
    border-bottom: 1px solid var(--clr-border);
  }
  .form-card-header h2 { font-size: 1.2rem; font-weight: 800; margin-bottom: 4px; }
  .form-card-header p { font-size: 0.85rem; color: var(--clr-text2); }
  .form-card-body { padding: 32px; }

  /* Category Selector */
  .category-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 12px;
    margin-bottom: 24px;
  }
  .cat-option { display: none; }
  .cat-label {
    display: flex; flex-direction: column; align-items: center; justify-content: center;
    gap: 10px;
    padding: 20px 12px;
    background: var(--clr-bg);
    border: 2px solid var(--clr-border);
    border-radius: var(--radius-md);
    cursor: pointer;
    transition: all var(--transition);
    text-align: center;
  }
  .cat-label .cat-emoji { font-size: 1.8rem; }
  .cat-label .cat-name { font-size: 0.8rem; font-weight: 700; }
  .cat-label .cat-desc { font-size: 0.72rem; color: var(--clr-text3); line-height: 1.4; }
  .cat-option:checked + .cat-label { border-width: 2px; }
  #cat-organic:checked + .cat-label   { border-color: #22c55e; background: rgba(34,197,94,0.08); color: #22c55e; }
  #cat-recyclable:checked + .cat-label { border-color: #3b82f6; background: rgba(59,130,246,0.08); color: #3b82f6; }
  #cat-hazardous:checked + .cat-label  { border-color: #ef4444; background: rgba(239,68,68,0.08); color: #ef4444; }
  #cat-residual:checked + .cat-label   { border-color: #a855f7; background: rgba(168,85,247,0.08); color: #a855f7; }

  /* Photo Upload Zone */
  .photo-upload-zone {
    border: 2px dashed var(--clr-border);
    border-radius: var(--radius-lg);
    padding: 40px 24px;
    text-align: center;
    cursor: pointer;
    transition: all var(--transition);
    background: var(--clr-bg);
    position: relative;
  }
  .photo-upload-zone:hover { border-color: var(--clr-primary); background: rgba(34,197,94,0.03); }
  .photo-upload-zone input[type="file"] { position: absolute; inset: 0; opacity: 0; cursor: pointer; width: 100%; height: 100%; }
  .upload-icon { font-size: 2.5rem; color: var(--clr-text3); margin-bottom: 12px; }
  .upload-text strong { display: block; font-size: 0.95rem; margin-bottom: 6px; }
  .upload-text span { font-size: 0.82rem; color: var(--clr-text3); }

  .photo-previews {
    display: grid; grid-template-columns: repeat(3, 1fr); gap: 12px;
    margin-top: 16px;
  }
  .photo-preview {
    aspect-ratio: 1;
    border-radius: var(--radius-md);
    background: var(--clr-bg);
    border: 1px solid var(--clr-border);
    display: flex; align-items: center; justify-content: center;
    color: var(--clr-text3); font-size: 0.8rem;
    position: relative; overflow: hidden;
  }
  .photo-preview.filled { border-color: var(--clr-primary); }
  .photo-preview img { position: absolute; inset: 0; width: 100%; height: 100%; object-fit: cover; }

  /* Location tab toggle */
  .location-tabs { display: flex; background: var(--clr-bg); border: 1px solid var(--clr-border); border-radius: var(--radius-sm); padding: 4px; margin-bottom: 16px; }
  .loc-tab { flex: 1; padding: 8px; text-align: center; font-size: 0.85rem; font-weight: 500; border-radius: 6px; cursor: pointer; transition: all var(--transition); color: var(--clr-text2); }
  .loc-tab.active { background: var(--clr-surface); color: var(--clr-text); box-shadow: var(--shadow-sm); }

  /* GPS display */
  .gps-display {
    background: var(--clr-bg);
    border: 1px solid var(--clr-border);
    border-radius: var(--radius-md);
    padding: 16px;
    display: flex; align-items: center; gap: 14px;
  }
  .gps-display i { color: var(--clr-primary); font-size: 1.2rem; }
  .gps-coords { font-size: 0.85rem; }
  .gps-coords strong { display: block; margin-bottom: 2px; }
  .gps-coords span { color: var(--clr-text3); font-size: 0.78rem; }

  /* Form Footer */
  .form-footer {
    padding: 24px 32px;
    background: var(--clr-bg2);
    border-top: 1px solid var(--clr-border);
    display: flex; justify-content: space-between; align-items: center;
  }
  .form-footer-note { font-size: 0.82rem; color: var(--clr-text3); display: flex; align-items: center; gap: 8px; }
  .char-count { font-size: 0.78rem; color: var(--clr-text3); text-align: right; margin-top: 6px; }

  @media (max-width: 900px) {
    .category-grid { grid-template-columns: repeat(2, 1fr); }
  }
</style>
@endsection

@section('content')
<!-- Page Header -->
<div style="margin-bottom:28px;">
  <div style="margin-bottom:6px;">
    <a href="{{ route('dashboard') }}" style="color:var(--clr-text3); font-size:0.85rem;"><i class="fas fa-arrow-left"></i> Back to Dashboard</a>
  </div>
  <h1 style="font-size:1.6rem; font-weight:800;">Submit Incident Report</h1>
  <p style="color:var(--clr-text2); font-size:0.9rem;">Report a waste issue in your area. All reports are reviewed by administrators.</p>
</div>

<!-- Progress Steps -->
<div class="form-progress">
  <div class="progress-step">
    <div class="progress-circle done" id="step1-circle"><i class="fas fa-check" style="font-size:0.7rem;"></i></div>
    <span class="progress-label active" id="step1-label">Details</span>
  </div>
  <div class="progress-line done" id="line1"></div>
  <div class="progress-step">
    <div class="progress-circle active" id="step2-circle">2</div>
    <span class="progress-label active" id="step2-label">Location</span>
  </div>
  <div class="progress-line" id="line2"></div>
  <div class="progress-step">
    <div class="progress-circle" id="step3-circle">3</div>
    <span class="progress-label" id="step3-label">Photos</span>
  </div>
  <div class="progress-line" id="line3"></div>
  <div class="progress-step">
    <div class="progress-circle" id="step4-circle">4</div>
    <span class="progress-label" id="step4-label">Review</span>
  </div>
</div>

<form method="POST" action="{{ route('reports.store') }}" enctype="multipart/form-data" id="report-form" novalidate>
  @csrf

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
      <h2><i class="fas fa-exclamation-triangle" style="color:var(--clr-amber); margin-right:8px;"></i>Incident Information</h2>
      <p>Provide clear, accurate details to help administrators respond quickly.</p>
    </div>
    <div class="form-card-body">

      <!-- Title -->
      <div class="form-group">
        <label class="form-label" for="report-title">Report Title <span style="color:var(--clr-red);">*</span></label>
        <input type="text" name="title" id="report-title" class="form-control" placeholder="e.g. Illegal dumping near playground" value="{{ old('title') }}" maxlength="120" oninput="updateChar(this,'title-count',120)" required/>
        <div class="char-count"><span id="title-count">0</span>/120</div>
      </div>

      <!-- Description -->
      <div class="form-group">
        <label class="form-label" for="report-desc">Description <span style="color:var(--clr-red);">*</span></label>
        <textarea name="description" id="report-desc" class="form-control" rows="4" placeholder="Describe the waste situation in detail — type, quantity, potential hazard, and any other relevant information..." maxlength="1000" oninput="updateChar(this,'desc-count',1000)" required>{{ old('description') }}</textarea>
        <div class="char-count"><span id="desc-count">0</span>/1000</div>
      </div>

      <!-- Waste Category -->
      <div class="form-group">
        <label class="form-label">Waste Category <span style="color:var(--clr-red);">*</span></label>
        <div class="category-grid">
          <div>
            <input type="radio" name="category" id="cat-organic" class="cat-option" value="organic" {{ old('category') == 'organic' ? 'checked' : '' }} required/>
            <label class="cat-label" for="cat-organic">
              <span class="cat-emoji">🌿</span>
              <span class="cat-name">Organic</span>
              <span class="cat-desc">Food waste, garden matter</span>
            </label>
          </div>
          <div>
            <input type="radio" name="category" id="cat-recyclable" class="cat-option" value="recyclable" {{ old('category') == 'recyclable' ? 'checked' : '' }}/>
            <label class="cat-label" for="cat-recyclable">
              <span class="cat-emoji">♻️</span>
              <span class="cat-name">Recyclable</span>
              <span class="cat-desc">Paper, glass, plastic, metal</span>
            </label>
          </div>
          <div>
            <input type="radio" name="category" id="cat-hazardous" class="cat-option" value="hazardous" {{ old('category') == 'hazardous' ? 'checked' : '' }}/>
            <label class="cat-label" for="cat-hazardous">
              <span class="cat-emoji">⚠️</span>
              <span class="cat-name">Hazardous</span>
              <span class="cat-desc">Chemicals, e-waste, batteries</span>
            </label>
          </div>
          <div>
            <input type="radio" name="category" id="cat-residual" class="cat-option" value="residual" {{ old('category') == 'residual' ? 'checked' : '' }}/>
            <label class="cat-label" for="cat-residual">
              <span class="cat-emoji">🗑️</span>
              <span class="cat-name">Residual</span>
              <span class="cat-desc">Non-recyclable, mixed waste</span>
            </label>
          </div>
        </div>
      </div>

      <!-- Date of Incident -->
      <div class="form-group">
        <label class="form-label" for="report-date">Date of Incident <span style="color:var(--clr-red);">*</span></label>
        <div style="position:relative;">
          <i class="fas fa-calendar-alt" style="position:absolute; left:14px; top:50%; transform:translateY(-50%); color:var(--clr-text3); pointer-events:none;"></i>
          <input type="date" name="date_of_incident" id="report-date" class="form-control" style="padding-left:44px;" value="{{ old('date_of_incident', date('Y-m-d')) }}" required/>
        </div>
      </div>

      <!-- Location Setup -->
      <div class="form-group">
        <label class="form-label">Location <span style="color:var(--clr-red);">*</span></label>
        
        <!-- Location Type hidden selector -->
        <input type="hidden" name="location_type" id="location-type-input" value="{{ old('location_type', 'gps') }}"/>

        <div class="location-tabs" role="tablist">
          <div class="loc-tab {{ old('location_type', 'gps') == 'gps' ? 'active' : '' }}" id="tab-gps" role="tab" onclick="switchTab('gps')">
            <i class="fas fa-crosshairs"></i> Use GPS
          </div>
          <div class="loc-tab {{ old('location_type') == 'manual' ? 'active' : '' }}" id="tab-manual" role="tab" onclick="switchTab('manual')">
            <i class="fas fa-pencil-alt"></i> Enter Manually
          </div>
        </div>

        <!-- GPS Panel -->
        <div id="panel-gps" style="{{ old('location_type', 'gps') == 'gps' ? '' : 'display:none;' }}">
          <div class="gps-display">
            <i class="fas fa-map-marker-alt"></i>
            <div class="gps-coords">
              <strong id="gps-addr">3.1390° N, 101.6869° E — Kuala Lumpur, Malaysia</strong>
              <span id="gps-status">📍 Location detected automatically</span>
            </div>
            
            <input type="hidden" name="latitude" id="latitude" value="{{ old('latitude', '3.1390') }}"/>
            <input type="hidden" name="longitude" id="longitude" value="{{ old('longitude', '101.6869') }}"/>
            <input type="hidden" name="gps_city" id="gps_city" value="{{ old('gps_city', 'Kuala Lumpur') }}"/>
            <input type="hidden" name="gps_state" id="gps_state" value="{{ old('gps_state', 'Kuala Lumpur') }}"/>
            
            <button type="button" class="btn btn-ghost btn-sm" style="margin-left:auto;" onclick="refreshGPS()">
              <i class="fas fa-redo"></i> Refresh
            </button>
          </div>
        </div>

        <!-- Manual Panel -->
        <div id="panel-manual" style="{{ old('location_type') == 'manual' ? '' : 'display:none;' }}">
          <div class="form-group" style="margin-bottom:12px;">
            <input type="text" name="address" class="form-control" placeholder="Street address or landmark" value="{{ old('address') }}"/>
          </div>
          <div class="form-row" style="display:grid; grid-template-columns:1fr 1fr; gap:12px;">
            <input type="text" name="city" class="form-control" placeholder="City / Town" value="{{ old('city') }}"/>
            <select name="state" class="form-control">
              <option value="" disabled {{ old('state') == '' ? 'selected' : '' }}>State</option>
              @foreach (['Kuala Lumpur', 'Selangor', 'Johor', 'Penang', 'Perak', 'Sabah', 'Sarawak', 'Kelantan', 'Terengganu', 'Pahang', 'Negeri Sembilan', 'Melaka', 'Kedah', 'Perlis', 'Putrajaya', 'Labuan'] as $state)
                <option value="{{ $state }}" {{ old('state') == $state ? 'selected' : '' }}>{{ $state }}</option>
              @endforeach
            </select>
          </div>
        </div>
      </div>

      <!-- Photo Upload -->
      <div class="form-group">
        <label class="form-label">Photographs <span style="color:var(--clr-text3);">(up to 3, JPEG/PNG, max 5MB each)</span></label>
        <div class="photo-upload-zone" id="upload-zone">
          <input type="file" name="photos[]" accept="image/jpeg,image/png" multiple id="photo-input" onchange="handlePhotos(this)"/>
          <div class="upload-icon"><i class="fas fa-cloud-upload-alt"></i></div>
          <div class="upload-text">
            <strong>Drag & drop photos here, or click to browse</strong>
            <span>JPEG or PNG · Maximum 5MB per photo · Up to 3 files</span>
          </div>
        </div>
        <div class="photo-previews">
          <div class="photo-preview" id="preview-1"><i class="fas fa-image" style="font-size:1.5rem;"></i></div>
          <div class="photo-preview" id="preview-2"><i class="fas fa-image" style="font-size:1.5rem;"></i></div>
          <div class="photo-preview" id="preview-3"><i class="fas fa-image" style="font-size:1.5rem;"></i></div>
        </div>
      </div>

    </div><!-- /form-card-body -->

    <div class="form-footer">
      <div class="form-footer-note">
        <i class="fas fa-info-circle" style="color:var(--clr-primary);"></i>
        Reports are reviewed within 2–5 business days.
      </div>
      <div style="display:flex; gap:12px;">
        <a href="{{ route('dashboard') }}" class="btn btn-ghost">Cancel</a>
        <button type="submit" class="btn btn-primary" id="submit-report-btn">
          <i class="fas fa-paper-plane"></i> Submit Report
        </button>
      </div>
    </div>
  </div>
</form>
@endsection

@section('scripts')
<script>
  function updateChar(el, countId, max) {
    document.getElementById(countId).textContent = el.value.length;
  }

  // Set char count values on page load (for old inputs)
  document.addEventListener('DOMContentLoaded', () => {
    updateChar(document.getElementById('report-title'), 'title-count', 120);
    updateChar(document.getElementById('report-desc'), 'desc-count', 1000);
  });

  function switchTab(tab) {
    const gps = document.getElementById('panel-gps');
    const manual = document.getElementById('panel-manual');
    const tabGps = document.getElementById('tab-gps');
    const tabManual = document.getElementById('tab-manual');
    const typeInput = document.getElementById('location-type-input');

    if (tab === 'gps') {
      gps.style.display = ''; manual.style.display = 'none';
      tabGps.classList.add('active'); tabManual.classList.remove('active');
      typeInput.value = 'gps';
    } else {
      manual.style.display = ''; gps.style.display = 'none';
      tabManual.classList.add('active'); tabGps.classList.remove('active');
      typeInput.value = 'manual';
    }
  }

  function refreshGPS() {
    document.getElementById('gps-status').textContent = '🔄 Refreshing location…';
    
    if (navigator.geolocation) {
      navigator.geolocation.getCurrentPosition(
        (position) => {
          const lat = position.coords.latitude.toFixed(4);
          const lng = position.coords.longitude.toFixed(4);
          document.getElementById('gps-addr').textContent = `${lat}° N, ${lng}° E — GPS Detected Coordinates`;
          document.getElementById('gps-status').textContent = '📍 Location detected successfully';
          
          document.getElementById('latitude').value = lat;
          document.getElementById('longitude').value = lng;
          document.getElementById('gps_city').value = 'Detected City';
          document.getElementById('gps_state').value = 'Detected State';
        },
        (error) => {
          document.getElementById('gps-status').textContent = '⚠️ Geolocation failed. Using default location.';
        }
      );
    } else {
      setTimeout(() => {
        document.getElementById('gps-status').textContent = '📍 Location refreshed';
      }, 1000);
    }
  }

  function handlePhotos(input) {
    // Reset previews
    for (let idx = 1; idx <= 3; idx++) {
      const preview = document.getElementById('preview-' + idx);
      preview.innerHTML = '<i class="fas fa-image" style="font-size:1.5rem;"></i>';
      preview.classList.remove('filled');
    }

    const files = Array.from(input.files).slice(0, 3);
    files.forEach((file, i) => {
      const preview = document.getElementById('preview-' + (i + 1));
      const reader = new FileReader();
      reader.onload = e => {
        preview.innerHTML = `<img src="${e.target.result}" alt="Preview ${i+1}"/>`;
        preview.classList.add('filled');
      };
      reader.readAsDataURL(file);
    });
  }

  // Set max date to today
  document.getElementById('report-date').max = new Date().toISOString().split('T')[0];
</script>
@endsection
