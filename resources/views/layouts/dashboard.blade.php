<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>@yield('title', 'My Dashboard – WasteWatch')</title>
  <meta name="description" content="Manage your waste incident reports on WasteWatch."/>
  <link rel="stylesheet" href="{{ asset('css/styles.css') }}"/>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
  <style>
    body { padding-top: 70px; }
    /* Dashboard-specific CSS layout settings */
    .dashboard-layout { display: flex; min-height: calc(100vh - 70px); }
    
    .sidebar {
      width: 260px; flex-shrink: 0;
      background: var(--clr-bg2);
      border-right: 1px solid var(--clr-border);
      padding: 28px 16px;
      position: sticky; top: 70px;
      height: calc(100vh - 70px);
      overflow-y: auto;
      display: flex; flex-direction: column;
    }
    .sidebar-user {
      display: flex; align-items: center; gap: 14px;
      padding: 16px;
      background: var(--clr-surface);
      border: 1px solid var(--clr-border);
      border-radius: var(--radius-md);
      margin-bottom: 28px;
    }
    .avatar {
      width: 42px; height: 42px; border-radius: 50%;
      background: linear-gradient(135deg, var(--clr-primary), var(--clr-teal));
      display: flex; align-items: center; justify-content: center;
      font-weight: 700; font-size: 1rem; flex-shrink: 0;
    }
    .sidebar-user-info strong { display: block; font-size: 0.9rem; }
    .sidebar-user-info span { font-size: 0.77rem; color: var(--clr-text3); }

    .sidebar-nav { flex: 1; }
    .nav-section-label {
      font-size: 0.7rem; font-weight: 700; letter-spacing: 0.1em;
      text-transform: uppercase; color: var(--clr-text3);
      padding: 0 12px; margin-bottom: 8px; margin-top: 20px;
    }
    .sidebar-nav a {
      display: flex; align-items: center; gap: 12px;
      padding: 10px 12px;
      border-radius: var(--radius-sm);
      font-size: 0.88rem; font-weight: 500;
      color: var(--clr-text2);
      transition: all var(--transition);
      margin-bottom: 2px;
    }
    .sidebar-nav a:hover { background: var(--clr-surface); color: var(--clr-text); }
    .sidebar-nav a.active { background: rgba(34,197,94,0.1); color: var(--clr-primary); }
    .sidebar-nav a i { width: 18px; text-align: center; }
    .sidebar-nav a .nav-badge {
      margin-left: auto;
      background: rgba(245,158,11,0.15);
      color: #f59e0b;
      padding: 2px 8px;
      border-radius: 50px;
      font-size: 0.72rem; font-weight: 700;
    }
    .sidebar-footer { padding-top: 16px; border-top: 1px solid var(--clr-border); }
    .sidebar-footer a, .sidebar-footer button { 
      display: flex; width: 100%; align-items: center; gap: 10px; font-size: 0.85rem; color: var(--clr-text3); padding: 8px 12px; border-radius: var(--radius-sm); transition: color var(--transition); text-align: left; background: none; border: none; cursor: pointer;
    }
    .sidebar-footer a:hover, .sidebar-footer button:hover { color: var(--clr-red); }

    .main-content { flex: 1; padding: 36px; min-width: 0; }

    @media (max-width: 900px) {
      .sidebar { display: none; }
      .main-content { padding: 24px 16px; }
    }
  </style>
  @yield('styles')
</head>
<body>

  <!-- NAVBAR -->
  <nav class="navbar">
    <div class="container">
      <a href="{{ route('home') }}" class="nav-logo">
        <div class="logo-icon">🗑️</div>
        Waste<span>Watch</span>
      </a>
      <div class="nav-links">
        <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">Dashboard</a>
        <a href="{{ route('reports.create') }}" class="{{ request()->routeIs('reports.create') ? 'active' : '' }}">New Report</a>
        <a href="{{ route('home') }}#education">Education</a>
      </div>
      <div class="nav-actions">
        <div class="avatar" style="cursor:pointer;" title="{{ Auth::user()->name ?? 'User' }}">
          {{ strtoupper(substr(Auth::user()->name ?? 'U', 0, 2)) }}
        </div>
        <form action="{{ route('logout') }}" method="POST" style="display:inline;">
          @csrf
          <button type="submit" class="btn btn-ghost btn-sm"><i class="fas fa-sign-out-alt"></i> Sign Out</button>
        </form>
      </div>
    </div>
  </nav>

  <div class="dashboard-layout">
    <!-- SIDEBAR -->
    <aside class="sidebar">
      <div class="sidebar-user">
        <div class="avatar">
          {{ strtoupper(substr(Auth::user()->name ?? 'U', 0, 2)) }}
        </div>
        <div class="sidebar-user-info">
          <strong>{{ Auth::user()->name ?? 'Ahmad Razali' }}</strong>
          <span>{{ Auth::user()->email ?? 'user@example.com' }}</span>
        </div>
      </div>

      <nav class="sidebar-nav">
        <div class="nav-section-label">Menu</div>
        <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}"><i class="fas fa-th-large"></i> Dashboard</a>
        <a href="{{ route('reports.create') }}" class="{{ request()->routeIs('reports.create') ? 'active' : '' }}"><i class="fas fa-plus-circle"></i> New Report</a>
        
        <div class="nav-section-label">Learn</div>
        <a href="{{ route('home') }}#education"><i class="fas fa-book-open"></i> Waste Education</a>
        <a href="{{ route('home') }}#how-it-works"><i class="fas fa-info-circle"></i> How It Works</a>

        <div class="nav-section-label">Account</div>
        <a href="#"><i class="fas fa-user-cog"></i> Profile Settings</a>
        <a href="#"><i class="fas fa-bell"></i> Notifications</a>
      </nav>

      <div class="sidebar-footer">
        <form action="{{ route('logout') }}" method="POST">
          @csrf
          <button type="submit"><i class="fas fa-sign-out-alt"></i> Sign Out</button>
        </form>
      </div>
    </aside>

    <!-- MAIN CONTENT -->
    <main class="main-content">
      @if (session('success'))
        <div class="toast toast-success" id="alert-toast">
          <i class="fas fa-check-circle" style="color:var(--clr-primary);"></i>
          {{ session('success') }}
        </div>
        <script>
          setTimeout(() => {
            const toast = document.getElementById('alert-toast');
            if (toast) toast.style.display = 'none';
          }, 3000);
        </script>
      @endif

      @yield('content')
    </main>
  </div>

  @yield('scripts')
</body>
</html>
