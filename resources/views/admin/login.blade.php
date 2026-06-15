<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Admin Portal – WasteWatch</title>
  <meta name="description" content="Secure administrator login for WasteWatch management portal."/>
  <link rel="stylesheet" href="{{ asset('css/styles.css') }}"/>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
  <style>
    body {
      display: flex; align-items: center; justify-content: center;
      min-height: 100vh;
      background:
        radial-gradient(ellipse 80% 60% at 20% 30%, rgba(59,130,246,0.07) 0%, transparent 60%),
        radial-gradient(ellipse 60% 50% at 80% 70%, rgba(168,85,247,0.06) 0%, transparent 60%),
        var(--clr-bg);
      position: relative; overflow: hidden;
    }

    /* Animated background grid */
    .bg-grid {
      position: fixed; inset: 0;
      background-image:
        linear-gradient(rgba(59,130,246,0.04) 1px, transparent 1px),
        linear-gradient(90deg, rgba(59,130,246,0.04) 1px, transparent 1px);
      background-size: 50px 50px;
      pointer-events: none;
    }

    /* Admin badge header */
    .admin-badge-header {
      display: flex; flex-direction: column; align-items: center;
      margin-bottom: 32px;
    }
    .admin-shield {
      width: 72px; height: 72px;
      background: linear-gradient(135deg, #1d4ed8, #7c3aed);
      border-radius: 20px;
      display: flex; align-items: center; justify-content: center;
      font-size: 2rem;
      margin-bottom: 16px;
      box-shadow: 0 8px 32px rgba(59,130,246,0.3);
      animation: shieldPulse 3s ease-in-out infinite;
    }
    @keyframes shieldPulse {
      0%,100% { box-shadow: 0 8px 32px rgba(59,130,246,0.3); }
      50% { box-shadow: 0 8px 48px rgba(59,130,246,0.5); }
    }
    .admin-badge-header h1 { font-size: 1.5rem; font-weight: 800; text-align: center; margin-bottom: 6px; }
    .admin-badge-header p { font-size: 0.85rem; color: var(--clr-text2); text-align: center; }

    .admin-tag {
      display: inline-flex; align-items: center; gap: 6px;
      padding: 4px 14px;
      background: rgba(59,130,246,0.1);
      border: 1px solid rgba(59,130,246,0.25);
      border-radius: 50px;
      font-size: 0.75rem; font-weight: 700;
      color: #3b82f6;
      margin-bottom: 12px;
    }
    .admin-tag .dot { width: 6px; height: 6px; background: #3b82f6; border-radius: 50%; animation: pulse 2s infinite; }
    @keyframes pulse { 0%,100%{opacity:1;transform:scale(1)} 50%{opacity:0.5;transform:scale(1.3)} }

    /* Login Card */
    .admin-login-card {
      width: 100%; max-width: 440px;
      background: var(--clr-surface);
      border: 1px solid var(--clr-border);
      border-radius: var(--radius-xl);
      padding: 44px;
      position: relative;
      box-shadow: var(--shadow-lg);
      z-index: 2;
    }
    .admin-login-card::before {
      content: '';
      position: absolute; top: 0; left: 0; right: 0; height: 3px;
      background: linear-gradient(90deg, #3b82f6, #7c3aed);
      border-radius: var(--radius-xl) var(--radius-xl) 0 0;
    }

    .input-with-icon { position: relative; }
    .input-with-icon .form-control { padding-left: 44px; }
    .input-with-icon .input-icon { position: absolute; left: 14px; top: 50%; transform: translateY(-50%); color: var(--clr-text3); }
    .input-with-icon.has-toggle .form-control { padding-right: 44px; }
    .password-toggle { position: absolute; right: 14px; top: 50%; transform: translateY(-50%); color: var(--clr-text3); cursor: pointer; background: none; border: none; font-size: 0.9rem; transition: color var(--transition); }
    .password-toggle:hover { color: #3b82f6; }

    .btn-admin {
      background: linear-gradient(135deg, #1d4ed8, #7c3aed);
      color: #fff;
      box-shadow: 0 4px 20px rgba(59,130,246,0.3);
      border: none;
      cursor: pointer;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      gap: 10px;
      font-weight: 600;
      transition: all var(--transition);
      border-radius: var(--radius-sm);
    }
    .btn-admin:hover { transform: translateY(-2px); box-shadow: 0 8px 30px rgba(59,130,246,0.45); }

    .security-strip {
      display: flex; flex-direction: column; gap: 8px;
      padding: 16px;
      background: rgba(59,130,246,0.05);
      border: 1px solid rgba(59,130,246,0.12);
      border-radius: var(--radius-sm);
      margin-top: 24px;
    }
    .security-strip-item { display: flex; align-items: center; gap: 10px; font-size: 0.78rem; color: var(--clr-text3); }
    .security-strip-item i { color: #3b82f6; width: 14px; }

    .back-link { display: flex; align-items: center; justify-content: center; gap: 8px; margin-top: 24px; font-size: 0.83rem; color: var(--clr-text3); transition: color var(--transition); text-decoration: none; }
    .back-link:hover { color: var(--clr-primary); }

    /* Watermark corners */
    .corner-mark {
      position: fixed; font-size: 0.7rem; font-weight: 700;
      letter-spacing: 0.12em; text-transform: uppercase; color: var(--clr-text3);
      opacity: 0.3;
    }
    .corner-tl { top: 20px; left: 24px; }
    .corner-br { bottom: 20px; right: 24px; }
  </style>
</head>
<body>
  <div class="bg-grid"></div>
  <div class="corner-mark corner-tl">WasteWatch Admin</div>
  <div class="corner-mark corner-br">Restricted Access</div>

  <div class="admin-login-card">
    <div class="admin-badge-header">
      <div class="admin-tag"><span class="dot"></span> Administrator Portal</div>
      <div class="admin-shield">🛡️</div>
      <h1>Admin Sign In</h1>
      <p>Authorised personnel only. All access is logged and monitored.</p>
    </div>

    @if ($errors->any())
      <div style="background: rgba(239,68,68,0.1); border: 1px solid rgba(239,68,68,0.25); border-radius: var(--radius-sm); padding: 12px 16px; margin-bottom: 20px; font-size: 0.82rem; color: #f87171;">
        <ul style="padding-left: 14px; list-style: none;">
          @foreach ($errors->all() as $error)
            <li><i class="fas fa-exclamation-circle"></i> {{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    <form method="POST" action="{{ route('admin.login.submit') }}" id="admin-login-form" novalidate>
      @csrf
      
      <div class="form-group">
        <label class="form-label" for="admin-email">Admin Email</label>
        <div class="input-with-icon">
          <i class="fas fa-user-shield input-icon"></i>
          <input type="email" name="email" id="admin-email" class="form-control" placeholder="admin@wastewatch.gov.my" value="{{ old('email') }}" required autocomplete="username"/>
        </div>
      </div>

      <div class="form-group">
        <label class="form-label" for="admin-password">Admin Password</label>
        <div class="input-with-icon has-toggle">
          <i class="fas fa-key input-icon"></i>
          <input type="password" name="password" id="admin-password" class="form-control" placeholder="Enter admin password" required autocomplete="current-password"/>
          <button type="button" class="password-toggle" id="toggle-admin-pw">
            <i class="fas fa-eye" id="admin-pw-icon"></i>
          </button>
        </div>
      </div>

      <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:24px;">
        <label style="display:flex; align-items:center; gap:8px; font-size:0.84rem; color:var(--clr-text2); cursor:pointer;">
          <input type="checkbox" name="remember" style="accent-color:#3b82f6;" id="admin-remember"/>
          Keep me signed in
        </label>
        <a href="#" style="font-size:0.83rem; color:#3b82f6; font-weight:500; text-decoration:none;">Reset password</a>
      </div>

      <button type="submit" class="btn btn-admin w-full" id="admin-login-btn" style="width:100%; padding:14px; font-size:0.95rem;">
        <i class="fas fa-sign-in-alt"></i> Access Admin Panel
      </button>

      <div class="security-strip">
        <div class="security-strip-item"><i class="fas fa-lock"></i> Connection encrypted with TLS 1.3</div>
        <div class="security-strip-item"><i class="fas fa-clipboard-list"></i> Access attempts are logged</div>
        <div class="security-strip-item"><i class="fas fa-user-clock"></i> Sessions expire after 2 hours of inactivity</div>
      </div>
    </form>

    <a href="{{ route('home') }}" class="back-link">
      <i class="fas fa-arrow-left"></i> Back to WasteWatch Public Site
    </a>
  </div>

<script>
  document.getElementById('toggle-admin-pw').addEventListener('click', function () {
    const pw = document.getElementById('admin-password');
    const icon = document.getElementById('admin-pw-icon');
    if (pw.type === 'password') {
      pw.type = 'text'; icon.classList.replace('fa-eye', 'fa-eye-slash');
    } else {
      pw.type = 'password'; icon.classList.replace('fa-eye-slash', 'fa-eye');
    }
  });
</script>
</body>
</html>
