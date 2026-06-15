@extends('layouts.auth')

@section('title', 'Sign In – WasteWatch')

@section('styles')
<style>
  .auth-features { display: flex; flex-direction: column; gap: 16px; }
  .auth-feature { display: flex; align-items: center; gap: 14px; }
  .auth-feature-icon {
    width: 40px; height: 40px;
    background: rgba(34,197,94,0.1);
    border: 1px solid rgba(34,197,94,0.2);
    border-radius: 10px;
    display: flex; align-items: center; justify-content: center;
    color: var(--clr-primary); flex-shrink: 0;
  }
  .auth-feature-text strong { display: block; font-size: 0.9rem; margin-bottom: 2px; }
  .auth-feature-text span { font-size: 0.8rem; color: var(--clr-text3); }
  .auth-left-content h2 { font-size: 2.4rem; font-weight: 800; line-height: 1.2; margin-bottom: 20px; }
  .auth-left-content p { color: var(--clr-text2); font-size: 1rem; line-height: 1.7; margin-bottom: 36px; }

  .auth-options { display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; }
  .remember-me { display: flex; align-items: center; gap: 8px; font-size: 0.85rem; color: var(--clr-text2); cursor: pointer; }
  .remember-me input[type="checkbox"] { accent-color: var(--clr-primary); width: 14px; height: 14px; }
  .forgot-link { font-size: 0.85rem; color: var(--clr-primary); font-weight: 500; }
  .forgot-link:hover { text-decoration: underline; }

  .auth-divider {
    display: flex; align-items: center; gap: 16px;
    margin: 28px 0;
    color: var(--clr-text3); font-size: 0.82rem;
  }
  .auth-divider::before, .auth-divider::after {
    content: ''; flex: 1; height: 1px; background: var(--clr-border);
  }

  .input-with-icon { position: relative; }
  .input-with-icon .form-control { padding-left: 44px; }
  .input-with-icon .input-icon {
    position: absolute; left: 14px; top: 50%; transform: translateY(-50%);
    color: var(--clr-text3); font-size: 0.9rem;
  }
  .input-with-icon.has-toggle .form-control { padding-right: 44px; }
  .password-toggle {
    position: absolute; right: 14px; top: 50%; transform: translateY(-50%);
    color: var(--clr-text3); cursor: pointer; transition: color var(--transition);
    background: none; border: none; font-size: 0.9rem;
  }
  .password-toggle:hover { color: var(--clr-primary); }

  .security-note {
    display: flex; align-items: center; gap: 10px;
    padding: 12px 16px;
    background: rgba(34,197,94,0.06);
    border: 1px solid rgba(34,197,94,0.15);
    border-radius: var(--radius-sm);
    font-size: 0.8rem; color: var(--clr-text2);
    margin-top: 24px;
  }
  .security-note i { color: var(--clr-primary); }
  .auth-sub { color: var(--clr-text2); font-size: 0.92rem; margin-bottom: 36px; }
  .auth-sub a { color: var(--clr-primary); font-weight: 600; }
  h1 { font-size: 1.8rem; font-weight: 800; margin-bottom: 8px; }
</style>
@endsection

@section('auth-left-content')
<h2>Welcome<br/><span class="text-gradient">Back</span></h2>
<p>Sign in to manage your waste incident reports and contribute to a cleaner Malaysia.</p>
<div class="auth-features">
  <div class="auth-feature">
    <div class="auth-feature-icon"><i class="fas fa-shield-alt"></i></div>
    <div class="auth-feature-text">
      <strong>Secure Authentication</strong>
      <span>Bcrypt-encrypted passwords &amp; session management</span>
    </div>
  </div>
  <div class="auth-feature">
    <div class="auth-feature-icon"><i class="fas fa-file-alt"></i></div>
    <div class="auth-feature-text">
      <strong>Track Your Reports</strong>
      <span>Monitor status updates in real time</span>
    </div>
  </div>
  <div class="auth-feature">
    <div class="auth-feature-icon"><i class="fas fa-map-marker-alt"></i></div>
    <div class="auth-feature-text">
      <strong>GPS Integration</strong>
      <span>Auto-detect your location for accurate reports</span>
    </div>
  </div>
</div>
@endsection

@section('auth-form-content')
<h1>Sign in to your account</h1>
<p class="auth-sub">Don't have an account? <a href="{{ route('register') }}">Create one free</a></p>

<form method="POST" action="{{ route('login') }}" novalidate>
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

  <div class="form-group">
    <label class="form-label" for="login-email">Email Address</label>
    <div class="input-with-icon">
      <i class="fas fa-envelope input-icon"></i>
      <input type="email" name="email" id="login-email" class="form-control" placeholder="you@example.com" value="{{ old('email') }}" required autocomplete="email"/>
    </div>
  </div>

  <div class="form-group">
    <label class="form-label" for="login-password">Password</label>
    <div class="input-with-icon has-toggle">
      <i class="fas fa-lock input-icon"></i>
      <input type="password" name="password" id="login-password" class="form-control" placeholder="Enter your password" required autocomplete="current-password"/>
      <button type="button" class="password-toggle" id="toggle-pw" aria-label="Toggle password visibility">
        <i class="fas fa-eye" id="pw-eye-icon"></i>
      </button>
    </div>
  </div>

  <div class="auth-options">
    <label class="remember-me">
      <input type="checkbox" name="remember" id="remember-me"/>
      Remember me for 30 days
    </label>
    <a href="#" class="forgot-link">Forgot password?</a>
  </div>

  <button type="submit" class="btn btn-primary" id="login-btn" style="width:100%; padding:14px;">
    <i class="fas fa-sign-in-alt"></i> Sign In
  </button>

  <div class="auth-divider">or continue as</div>

  <a href="{{ route('home') }}" class="btn btn-ghost" style="width:100%; padding:12px;">
    <i class="fas fa-eye"></i> Browse as Guest
  </a>

  <div class="security-note">
    <i class="fas fa-lock"></i>
    <span>Your connection is encrypted. We never store plain-text passwords.</span>
  </div>
</form>

<p style="text-align:center; margin-top:28px; font-size:0.82rem; color:var(--clr-text3);">
  Are you an administrator? <a href="{{ route('admin.login') }}" style="color:var(--clr-primary); font-weight:600;">Admin Portal →</a>
</p>
@endsection

@section('scripts')
<script>
  // Password toggle
  document.getElementById('toggle-pw').addEventListener('click', function () {
    const pw = document.getElementById('login-password');
    const icon = document.getElementById('pw-eye-icon');
    if (pw.type === 'password') {
      pw.type = 'text';
      icon.classList.replace('fa-eye', 'fa-eye-slash');
    } else {
      pw.type = 'password';
      icon.classList.replace('fa-eye-slash', 'fa-eye');
    }
  });
</script>
@endsection
