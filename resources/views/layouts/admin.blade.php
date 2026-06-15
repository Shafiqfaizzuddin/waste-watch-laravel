<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>@yield('title', 'Admin Dashboard – WasteWatch')</title>
  <link rel="stylesheet" href="{{ asset('css/styles.css') }}"/>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
  <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
  <style>
    body { padding-top: 0; background: var(--clr-bg); }
    .admin-layout { display: flex; min-height: 100vh; }
    
    /* Admin Sidebar */
    .admin-sidebar {
      width: 260px; flex-shrink: 0;
      background: var(--clr-bg2);
      border-right: 1px solid var(--clr-border);
      display: flex; flex-direction: column;
      position: fixed; top: 0; left: 0; bottom: 0;
      z-index: 100; overflow-y: auto;
    }
    .admin-sidebar-logo {
      padding: 24px 20px;
      border-bottom: 1px solid var(--clr-border);
      display: flex; align-items: center; gap: 12px;
    }
    .admin-logo-icon {
      width: 40px; height: 40px;
      background: linear-gradient(135deg, #1d4ed8, #7c3aed);
      border-radius: 10px;
      display: flex; align-items: center; justify-content: center;
      font-size: 1.1rem;
    }
    .admin-logo-text { font-family: var(--font-head); font-size: 1.1rem; font-weight: 800; }
    .admin-logo-text span { display: block; font-size: 0.68rem; font-weight: 600; color: var(--clr-text3); letter-spacing: 0.08em; text-transform: uppercase; margin-top: 1px; }

    .admin-nav { flex: 1; padding: 20px 12px; }
    .admin-nav-label { font-size: 0.68rem; font-weight: 700; letter-spacing: 0.1em; text-transform: uppercase; color: var(--clr-text3); padding: 0 10px; margin: 18px 0 6px; }
    .admin-nav a {
      display: flex; align-items: center; gap: 12px;
      padding: 10px 12px; border-radius: var(--radius-sm);
      font-size: 0.875rem; font-weight: 500; color: var(--clr-text2);
      transition: all var(--transition); margin-bottom: 2px;
    }
    .admin-nav a:hover { background: var(--clr-surface); color: var(--clr-text); }
    .admin-nav a.active { background: rgba(59,130,246,0.12); color: #3b82f6; }
    .admin-nav a i { width: 18px; text-align: center; }
    .nav-count { margin-left: auto; background: rgba(245,158,11,0.15); color: #f59e0b; padding: 2px 8px; border-radius: 50px; font-size: 0.7rem; font-weight: 700; }
    .nav-count.blue { background: rgba(59,130,246,0.12); color: #3b82f6; }

    .admin-sidebar-footer {
      padding: 16px 12px;
      border-top: 1px solid var(--clr-border);
    }
    .admin-user-card {
      display: flex; align-items: center; gap: 12px;
      padding: 12px; background: var(--clr-surface);
      border: 1px solid var(--clr-border); border-radius: var(--radius-md);
      margin-bottom: 10px;
    }
    .admin-avatar {
      width: 36px; height: 36px; border-radius: 50%;
      background: linear-gradient(135deg, #1d4ed8, #7c3aed);
      display: flex; align-items: center; justify-content: center;
      font-size: 0.8rem; font-weight: 700; flex-shrink: 0;
    }
    .admin-user-info strong { display: block; font-size: 0.82rem; }
    .admin-user-info span { font-size: 0.72rem; color: #3b82f6; }
    .admin-sidebar-footer a, .admin-sidebar-footer button { 
      display: flex; width: 100%; align-items: center; gap: 10px; font-size: 0.83rem; color: var(--clr-text3); padding: 8px 12px; border-radius: var(--radius-sm); transition: color var(--transition); text-align: left; background: none; border: none; cursor: pointer;
    }
    .admin-sidebar-footer a:hover, .admin-sidebar-footer button:hover { color: var(--clr-red); }

    /* Admin Main */
    .admin-main { margin-left: 260px; flex: 1; display: flex; flex-direction: column; }

    /* Topbar */
    .admin-topbar {
      height: 64px;
      background: var(--clr-bg2);
      border-bottom: 1px solid var(--clr-border);
      display: flex; align-items: center; justify-content: space-between;
      padding: 0 32px;
      position: sticky; top: 0; z-index: 50;
    }
    .topbar-left h2 { font-size: 1.1rem; font-weight: 700; }
    .topbar-left span { font-size: 0.8rem; color: var(--clr-text3); }
    .topbar-right { display: flex; align-items: center; gap: 12px; }
    .topbar-icon-btn {
      width: 38px; height: 38px;
      background: var(--clr-surface); border: 1px solid var(--clr-border);
      border-radius: var(--radius-sm);
      display: flex; align-items: center; justify-content: center;
      color: var(--clr-text2); cursor: pointer; position: relative;
      transition: all var(--transition);
    }
    .topbar-icon-btn:hover { color: var(--clr-text); border-color: var(--clr-border2); }
    .notif-dot { position: absolute; top: 6px; right: 6px; width: 8px; height: 8px; background: var(--clr-red); border-radius: 50%; border: 2px solid var(--clr-bg2); }

    .admin-body { padding: 32px; flex: 1; }

    @media (max-width: 900px) {
      .admin-sidebar { display: none; }
      .admin-main { margin-left: 0; }
    }
  </style>
  @yield('styles')
</head>
<body>
<div class="admin-layout">

  <!-- ADMIN SIDEBAR -->
  <aside class="admin-sidebar">
    <div class="admin-sidebar-logo">
      <div class="admin-logo-icon">🛡️</div>
      <div class="admin-logo-text">
        WasteWatch
        <span>Admin Panel</span>
      </div>
    </div>

    <nav class="admin-nav">
      <div class="admin-nav-label">Overview</div>
      <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"><i class="fas fa-chart-pie"></i> Dashboard</a>

      <div class="admin-nav-label">Management</div>
      <a href="{{ route('admin.reports') }}" class="{{ request()->routeIs('admin.reports') ? 'active' : '' }}"><i class="fas fa-file-alt"></i> Reports <span class="nav-count">{{ \App\Models\Report::count() ?? 14 }}</span></a>
      <a href="#"><i class="fas fa-users"></i> Users <span class="nav-count blue">{{ \App\Models\User::count() ?? 128 }}</span></a>

      <div class="admin-nav-label">System</div>
      <a href="#"><i class="fas fa-bell"></i> Notifications <span class="nav-count">3</span></a>
      <a href="#"><i class="fas fa-cog"></i> Settings</a>
    </nav>

    <div class="admin-sidebar-footer">
      <div class="admin-user-card">
        <div class="admin-avatar">SA</div>
        <div class="admin-user-info">
          <strong>Super Admin</strong>
          <span>Administrator</span>
        </div>
      </div>
      <form action="{{ route('admin.logout') }}" method="POST">
        @csrf
        <button type="submit"><i class="fas fa-sign-out-alt"></i> Sign Out</button>
      </form>
    </div>
  </aside>

  <!-- ADMIN MAIN -->
  <div class="admin-main">
    <!-- Topbar -->
    <div class="admin-topbar">
      <div class="topbar-left">
        <h2>@yield('topbar-title', 'Dashboard Overview')</h2>
        <span>{{ now()->format('l, d F Y') }}</span>
      </div>
      <div class="topbar-right">
        <div class="topbar-icon-btn" title="Notifications">
          <i class="fas fa-bell"></i>
          <div class="notif-dot"></div>
        </div>
        <a href="{{ route('home') }}" target="_blank" class="btn btn-ghost btn-sm" style="font-size:0.82rem;">
          <i class="fas fa-external-link-alt"></i> View Site
        </a>
        <div class="admin-avatar" style="cursor:pointer;">SA</div>
      </div>
    </div>

    <!-- Page Body -->
    <div class="admin-body">
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
    </div>
  </div>
</div>

@yield('scripts')
</body>
</html>
