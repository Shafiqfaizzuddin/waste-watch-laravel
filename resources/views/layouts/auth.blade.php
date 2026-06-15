<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>@yield('title', 'Sign In – WasteWatch')</title>
  <meta name="description" content="@yield('description', 'Sign in or register your WasteWatch account.')"/>
  <link rel="stylesheet" href="{{ asset('css/styles.css') }}"/>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
  <style>
    .auth-page {
      min-height: 100vh;
      display: grid;
      grid-template-columns: 1fr 1fr;
    }
    /* Left Panel */
    .auth-left {
      background: linear-gradient(145deg, #0d1117 0%, #0f1e14 50%, #0d1117 100%);
      display: flex; flex-direction: column; justify-content: space-between;
      padding: 48px;
      position: relative; overflow: hidden;
    }
    .auth-left-bg {
      position: absolute; inset: 0;
      background: radial-gradient(ellipse 70% 60% at 30% 50%, rgba(34,197,94,0.1) 0%, transparent 70%);
      pointer-events: none;
    }
    .auth-left-grid {
      position: absolute; inset: 0;
      background-image: linear-gradient(rgba(34,197,94,0.03) 1px, transparent 1px),
                        linear-gradient(90deg, rgba(34,197,94,0.03) 1px, transparent 1px);
      background-size: 50px 50px;
    }
    .auth-left-logo { position: relative; z-index: 2; }
    .auth-left-content { position: relative; z-index: 2; }
    
    /* Right Panel */
    .auth-right {
      background: var(--clr-bg);
      display: flex; align-items: center; justify-content: center;
      padding: 48px 32px;
      overflow-y: auto;
    }
    .auth-form-box { width: 100%; max-width: 500px; }
    
    @media (max-width: 900px) {
      .auth-page { grid-template-columns: 1fr; }
      .auth-left { display: none; }
    }
  </style>
  @yield('styles')
</head>
<body>

<div class="auth-page">
  <!-- Left Panel -->
  <div class="auth-left">
    <div class="auth-left-bg"></div>
    <div class="auth-left-grid"></div>
    <div class="auth-left-logo">
      <a href="{{ route('home') }}" class="nav-logo" style="display:inline-flex;">
        <div class="logo-icon">🗑️</div>
        Waste<span style="color:var(--clr-primary);">Watch</span>
      </a>
    </div>
    
    <div class="auth-left-content">
      @yield('auth-left-content')
    </div>
    
    <div class="auth-left-footer">
      © {{ date('Y') }} WasteWatch · Malaysia
    </div>
  </div>

  <!-- Right Panel -->
  <div class="auth-right">
    <div class="auth-form-box">
      @yield('auth-form-content')
    </div>
  </div>
</div>

@yield('scripts')
</body>
</html>
