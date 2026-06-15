<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>@yield('title', 'WasteWatch – Smart Waste Management & Reporting')</title>
  <meta name="description" content="@yield('description', 'WasteWatch empowers Malaysian communities to report, track, and resolve waste management issues.')" />
  <link rel="stylesheet" href="{{ asset('css/styles.css') }}" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
  @yield('styles')
</head>
<body>

  <!-- PUBLIC NAVBAR -->
  <nav class="navbar" id="main-navbar">
    <div class="container">
      <a href="{{ route('home') }}" class="nav-logo">
        <div class="logo-icon">🗑️</div>
        Waste<span>Watch</span>
      </a>
      <div class="nav-links">
        <a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}">Home</a>
        <a href="{{ route('home') }}#education">Education</a>
        <a href="{{ route('home') }}#how-it-works">How It Works</a>
        <a href="{{ route('reports.public') }}">Reports</a>
      </div>
      <div class="nav-actions">
        @auth
          <a href="{{ route('dashboard') }}" class="btn btn-ghost btn-sm">Dashboard</a>
          <form action="{{ route('logout') }}" method="POST" style="display: inline;">
            @csrf
            <button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-sign-out-alt"></i> Sign Out</button>
          </form>
        @else
          <a href="{{ route('login') }}" class="btn btn-ghost btn-sm">Sign In</a>
          <a href="{{ route('register') }}" class="btn btn-primary btn-sm">Get Started</a>
        @endauth
      </div>
      <button class="nav-hamburger" id="nav-hamburger" aria-label="Open menu">
        <span></span><span></span><span></span>
      </button>
    </div>
  </nav>

  @yield('content')

  <!-- PUBLIC FOOTER -->
  <footer>
    <div class="container">
      <div class="footer-grid">
        <div class="footer-brand">
          <div class="nav-logo" style="margin-bottom:8px;">
            <div class="logo-icon">🗑️</div>
            Waste<span style="color:var(--clr-primary);">Watch</span>
          </div>
          <p>A community-driven waste management and reporting platform built to support Malaysia's journey toward a cleaner, greener future.</p>
        </div>
        <div class="footer-col">
          <h4>Platform</h4>
          <ul>
            <li><a href="{{ route('home') }}#education">Education</a></li>
            <li><a href="{{ route('reports.public') }}">Public Reports</a></li>
            <li><a href="{{ route('register') }}">Register</a></li>
            <li><a href="{{ route('login') }}">Sign In</a></li>
          </ul>
        </div>
        <div class="footer-col">
          <h4>Resources</h4>
          <ul>
            <li><a href="#">Waste Guidelines</a></li>
            <li><a href="#">Recycling Centers</a></li>
            <li><a href="#">Policy Documents</a></li>
            <li><a href="#">FAQ</a></li>
          </ul>
        </div>
        <div class="footer-col">
          <h4>Legal</h4>
          <ul>
            <li><a href="#">Privacy Policy</a></li>
            <li><a href="#">Terms of Service</a></li>
            <li><a href="#">Cookie Policy</a></li>
          </ul>
        </div>
      </div>
      <div class="footer-bottom">
        <p>© {{ date('Y') }} WasteWatch. All rights reserved.</p>
        <p>Aligned with <strong>Act 672</strong> · Malaysia</p>
      </div>
    </div>
  </footer>

  <script>
    // Navbar scroll effect
    const navbar = document.getElementById('main-navbar');
    if (navbar) {
      window.addEventListener('scroll', () => {
        navbar.style.background = window.scrollY > 50
          ? 'rgba(13,17,23,0.97)'
          : 'rgba(13,17,23,0.85)';
      });
    }
  </script>
  @yield('scripts')
</body>
</html>
