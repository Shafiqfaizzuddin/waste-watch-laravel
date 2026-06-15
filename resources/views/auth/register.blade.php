@extends('layouts.auth')

@section('title', 'Create Account – WasteWatch')

@section('styles')
<style>
  .auth-steps { display: flex; flex-direction: column; gap: 20px; }
  .auth-step { display: flex; align-items: flex-start; gap: 14px; }
  .auth-step-num {
    width: 32px; height: 32px; border-radius: 50%;
    background: linear-gradient(135deg, var(--clr-primary), var(--clr-teal));
    display: flex; align-items: center; justify-content: center;
    font-size: 0.82rem; font-weight: 800; flex-shrink: 0;
    box-shadow: 0 4px 12px rgba(34,197,94,0.3);
  }
  .auth-step-text strong { display: block; font-size: 0.9rem; margin-bottom: 2px; }
  .auth-step-text span { font-size: 0.8rem; color: var(--clr-text3); }
  .auth-left-content h2 { font-size: 2.2rem; font-weight: 800; line-height: 1.2; margin-bottom: 20px; }
  .auth-left-content p { color: var(--clr-text2); font-size: 0.92rem; line-height: 1.7; margin-bottom: 32px; }

  .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }

  .input-with-icon { position: relative; }
  .input-with-icon .form-control { padding-left: 44px; }
  .input-with-icon .input-icon { position: absolute; left: 14px; top: 50%; transform: translateY(-50%); color: var(--clr-text3); font-size: 0.9rem; }
  .input-with-icon.has-toggle .form-control { padding-right: 44px; }
  .password-toggle { position: absolute; right: 14px; top: 50%; transform: translateY(-50%); color: var(--clr-text3); cursor: pointer; background: none; border: none; font-size: 0.9rem; transition: color var(--transition); }
  .password-toggle:hover { color: var(--clr-primary); }

  .pw-strength { margin-top: 8px; }
  .pw-strength-bar { height: 4px; background: var(--clr-border); border-radius: 2px; overflow: hidden; margin-bottom: 6px; }
  .pw-strength-fill { height: 100%; border-radius: 2px; transition: width 0.3s, background 0.3s; width: 0%; }
  .pw-strength-label { font-size: 0.75rem; color: var(--clr-text3); }

  .terms-check { display: flex; align-items: flex-start; gap: 10px; font-size: 0.85rem; color: var(--clr-text2); cursor: pointer; margin-bottom: 24px; }
  .terms-check input { accent-color: var(--clr-primary); width: 15px; height: 15px; flex-shrink: 0; margin-top: 2px; }
  .terms-check a { color: var(--clr-primary); }

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

  @media (max-width: 500px) {
    .form-row { grid-template-columns: 1fr; }
  }
</style>
@endsection

@section('auth-left-content')
<h2>Join the<br/><span class="text-gradient">Movement</span></h2>
<p>Create your free account and start making a real impact on waste management in your community.</p>
<div class="auth-steps">
  <div class="auth-step">
    <div class="auth-step-num">1</div>
    <div class="auth-step-text">
      <strong>Register in seconds</strong>
      <span>Fill in your details — takes under a minute</span>
    </div>
  </div>
  <div class="auth-step">
    <div class="auth-step-num">2</div>
    <div class="auth-step-text">
      <strong>Spot a waste issue?</strong>
      <span>Photograph it and submit a report instantly</span>
    </div>
  </div>
  <div class="auth-step">
    <div class="auth-step-num">3</div>
    <div class="auth-step-text">
      <strong>Track &amp; get resolved</strong>
      <span>Follow up on reports and see your impact</span>
    </div>
  </div>
</div>
@endsection

@section('auth-form-content')
<h1>Create your account</h1>
<p class="auth-sub">Already have an account? <a href="{{ route('login') }}">Sign in</a></p>

<form method="POST" action="{{ route('register') }}" id="register-form" novalidate>
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

  <div class="form-row">
    <div class="form-group">
      <label class="form-label" for="reg-fname">First Name</label>
      <div class="input-with-icon">
        <i class="fas fa-user input-icon"></i>
        <input type="text" name="first_name" id="reg-fname" class="form-control" placeholder="Ahmad" value="{{ old('first_name') }}" required/>
      </div>
    </div>
    <div class="form-group">
      <label class="form-label" for="reg-lname">Last Name</label>
      <div class="input-with-icon">
        <i class="fas fa-user input-icon"></i>
        <input type="text" name="last_name" id="reg-lname" class="form-control" placeholder="Razali" value="{{ old('last_name') }}" required/>
      </div>
    </div>
  </div>

  <div class="form-group">
    <label class="form-label" for="reg-email">Email Address</label>
    <div class="input-with-icon">
      <i class="fas fa-envelope input-icon"></i>
      <input type="email" name="email" id="reg-email" class="form-control" placeholder="ahmad@example.com" value="{{ old('email') }}" required autocomplete="email"/>
    </div>
  </div>

  <div class="form-group">
    <label class="form-label" for="reg-phone">Phone Number</label>
    <div class="input-with-icon">
      <i class="fas fa-phone input-icon"></i>
      <input type="tel" name="phone" id="reg-phone" class="form-control" placeholder="+60 12-345 6789" value="{{ old('phone') }}"/>
    </div>
  </div>

  <div class="form-group">
    <label class="form-label" for="reg-state">State / Territory</label>
    <div class="input-with-icon">
      <i class="fas fa-map-marker-alt input-icon"></i>
      <select name="state" id="reg-state" class="form-control" style="padding-left:44px;" required>
        <option value="" disabled {{ old('state') == '' ? 'selected' : '' }}>Select your state</option>
        @foreach (['Kuala Lumpur', 'Selangor', 'Johor', 'Penang', 'Perak', 'Sabah', 'Sarawak', 'Kelantan', 'Terengganu', 'Pahang', 'Negeri Sembilan', 'Melaka', 'Kedah', 'Perlis', 'Putrajaya', 'Labuan'] as $state)
          <option value="{{ $state }}" {{ old('state') == $state ? 'selected' : '' }}>{{ $state }}</option>
        @endforeach
      </select>
    </div>
  </div>

  <div class="form-group">
    <label class="form-label" for="reg-password">Password</label>
    <div class="input-with-icon has-toggle">
      <i class="fas fa-lock input-icon"></i>
      <input type="password" name="password" id="reg-password" class="form-control" placeholder="Minimum 8 characters" oninput="checkStrength(this.value)" required autocomplete="new-password"/>
      <button type="button" class="password-toggle" id="toggle-reg-pw" aria-label="Toggle password">
        <i class="fas fa-eye" id="reg-pw-icon"></i>
      </button>
    </div>
    <div class="pw-strength">
      <div class="pw-strength-bar">
        <div class="pw-strength-fill" id="pw-strength-fill"></div>
      </div>
      <span class="pw-strength-label" id="pw-strength-label">Password strength</span>
    </div>
  </div>

  <div class="form-group">
    <label class="form-label" for="reg-confirm">Confirm Password</label>
    <div class="input-with-icon has-toggle">
      <i class="fas fa-lock input-icon"></i>
      <input type="password" name="password_confirmation" id="reg-confirm" class="form-control" placeholder="Re-enter your password" required autocomplete="new-password"/>
      <button type="button" class="password-toggle" id="toggle-confirm-pw" aria-label="Toggle confirm password">
        <i class="fas fa-eye" id="confirm-pw-icon"></i>
      </button>
    </div>
  </div>

  <label class="terms-check">
    <input type="checkbox" name="agree" id="agree-terms" required {{ old('agree') ? 'checked' : '' }}/>
    I agree to WasteWatch's <a href="#">Terms of Service</a> and <a href="#">Privacy Policy</a>
  </label>

  <button type="submit" class="btn btn-primary" id="register-btn" style="width:100%; padding:14px;">
    <i class="fas fa-user-plus"></i> Create Account
  </button>

  <div class="security-note">
    <i class="fas fa-shield-alt"></i>
    <span>Your password is hashed with bcrypt. We take your security seriously.</span>
  </div>
</form>
@endsection

@section('scripts')
<script>
  // Password strength
  function checkStrength(val) {
    const fill = document.getElementById('pw-strength-fill');
    const label = document.getElementById('pw-strength-label');
    let score = 0;
    if (val.length >= 8) score++;
    if (/[A-Z]/.test(val)) score++;
    if (/[0-9]/.test(val)) score++;
    if (/[^A-Za-z0-9]/.test(val)) score++;
    const levels = [
      { w: '0%', color: '', text: 'Password strength' },
      { w: '25%', color: '#ef4444', text: 'Weak' },
      { w: '50%', color: '#f59e0b', text: 'Fair' },
      { w: '75%', color: '#3b82f6', text: 'Good' },
      { w: '100%', color: '#22c55e', text: 'Strong' },
    ];
    fill.style.width = levels[score].w;
    fill.style.background = levels[score].color;
    label.textContent = levels[score].text;
    label.style.color = levels[score].color || 'var(--clr-text3)';
  }

  // Toggle passwords
  [['toggle-reg-pw','reg-password','reg-pw-icon'],['toggle-confirm-pw','reg-confirm','confirm-pw-icon']].forEach(([btn,inp,icon]) => {
    document.getElementById(btn).addEventListener('click', function () {
      const el = document.getElementById(inp);
      const ic = document.getElementById(icon);
      if (el.type === 'password') { el.type = 'text'; ic.classList.replace('fa-eye','fa-eye-slash'); }
      else { el.type = 'password'; ic.classList.replace('fa-eye-slash','fa-eye'); }
    });
  });
</script>
@endsection
