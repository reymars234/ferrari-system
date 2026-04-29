<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login — Veloce Vantage</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Barlow:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>  
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        :root {
            --ferrari-red: #dc0000;
            --dark: #0d0d0d;
            --dark2: #1a1a1a;
            --dark3: #252525;
            --light: #e8e8e8;
            --gray: #888;
        }  
        html, body {
            background: var(--dark); color: var(--light);
            font-family: 'Barlow', sans-serif;
            height: 100%; overflow: hidden;
        }

        /* ── BG ── */
        .admin-bg-video {
            position: fixed; inset: 0; width: 100%; height: 100%;
            object-fit: cover; z-index: 0; opacity: 0.35; pointer-events: none;
        }
        .admin-bg-overlay {
            position: fixed; inset: 0; z-index: 1;
            background: rgba(10,10,10,0.65); pointer-events: none;
        }
        .admin-tint {
            position: fixed; inset: 0; z-index: 1;
            background: radial-gradient(ellipse 80% 60% at 50% 50%,
                        rgba(220,0,0,0.06) 0%, transparent 70%);
            pointer-events: none;
        }

        /* ── LAYOUT ── */
        .auth-wrap {
            position: fixed; inset: 0;
            display: flex; align-items: center; justify-content: center;
            padding: 0 16px; z-index: 10;
        }

        /* ── CARD ── */
        .auth-card {
            position: relative; z-index: 2;
            background: rgba(18,18,18,0.92);
            border: 1px solid rgba(220,0,0,0.22);
            border-radius: 14px;
            width: 100%; max-width: 420px;
            backdrop-filter: blur(24px);
            box-shadow: 0 24px 80px rgba(0,0,0,.5),
                        0 0 0 1px rgba(220,0,0,.07),
                        0 0 60px rgba(220,0,0,.04);
            animation: cardIn 0.65s cubic-bezier(.25,.8,.25,1) both;
            overflow: hidden;
        }
        @keyframes cardIn {
            from { opacity:0; transform:translateY(40px) scale(.97); }
            to   { opacity:1; transform:translateY(0) scale(1); }
        }

        /* Admin ribbon */
        .admin-ribbon {
            position: absolute; top: -1px; left: 50%; transform: translateX(-50%);
            background: var(--ferrari-red); color: #fff;
            font-family: 'Bebas Neue', sans-serif; font-size: 10px; letter-spacing: 3px;
            padding: 4px 20px; border-radius: 0 0 8px 8px;
            display: flex; align-items: center; gap: 6px; z-index: 5;
            white-space: nowrap;
        }

        /* ── SLIDING PANEL SYSTEM ── */
        .card-slider {
            display: grid;
            grid-template-columns: 1fr;
            overflow: hidden;
        }
        .panel {
            grid-row: 1;
            grid-column: 1;
            padding: 48px 44px;
            transition: transform 0.55s cubic-bezier(.77,0,.18,1),
                        opacity  0.45s ease;
            will-change: transform, opacity;
        }
        .panel-login {
            transform: translateX(0);
            opacity: 1;
            pointer-events: all;
        }
        .panel-forgot {
            transform: translateX(105%);
            opacity: 0;
            pointer-events: none;
        }
        .card-slider.show-forgot .panel-login {
            transform: translateX(-105%);
            opacity: 0;
            pointer-events: none;
        }
        .card-slider.show-forgot .panel-forgot {
            transform: translateX(0);
            opacity: 1;
            pointer-events: all;
        }

        /* ── LOGO ── */
        .login-logo-wrap {
            display: flex; flex-direction: column; align-items: center;
            margin-bottom: 28px; margin-top: 12px;
            animation: logoIn 0.8s cubic-bezier(.25,.8,.25,1) 0.05s both;
        }
        @keyframes logoIn {
            from { opacity:0; transform:translateY(-20px) scale(.9); }
            to   { opacity:1; transform:translateY(0) scale(1); }
        }
        .login-logo-img {
            height: 60px; width: auto; margin-bottom: 12px;
            animation: logoGlow 3s ease infinite alternate;
            transition: transform .4s ease;
        }
        .login-logo-img:hover { transform: scale(1.08) rotate(-3deg); }
        @keyframes logoGlow {
            from { filter: drop-shadow(0 0 6px rgba(220,0,0,.2)); }
            to   { filter: drop-shadow(0 0 18px rgba(220,0,0,.55)); }
        }
        .login-logo-icon {
            width: 60px; height: 60px; border-radius: 50%;
            background: rgba(220,0,0,.08); border: 1px solid rgba(220,0,0,.25);
            display: flex; align-items: center; justify-content: center;
            margin-bottom: 12px; animation: logoGlow 3s ease infinite alternate;
        }
        .login-logo-icon i { color: var(--ferrari-red); font-size: 24px; }
        .login-logo-name {
            font-family: 'Bebas Neue', sans-serif; font-size: 22px;
            letter-spacing: 5px; color: var(--ferrari-red);
        }
        .login-logo-sub { font-size: 9px; letter-spacing: 4px; color: #555; text-transform: uppercase; margin-top: 2px; }

        @keyframes fadeUp {
            from { opacity:0; transform:translateY(12px); }
            to   { opacity:1; transform:translateY(0); }
        }

        .accent-line {
            width: 40px; height: 2px; background: var(--ferrari-red);
            margin: 14px auto 22px; transform-origin: center;
            animation: lineExpand .6s ease .3s both;
        }
        @keyframes lineExpand {
            from { transform:scaleX(0); opacity:0; }
            to   { transform:scaleX(1); opacity:1; }
        }

        .auth-title {
            font-family: 'Bebas Neue', sans-serif; font-size: 26px;
            letter-spacing: 3px; margin-bottom: 4px; text-align: center;
            animation: fadeUp .6s ease .25s both;
        }
        .auth-sub {
            color: var(--gray); font-size: 12px; margin-bottom: 28px;
            text-align: center; letter-spacing: .5px;
            animation: fadeUp .6s ease .3s both;
        }

        /* ── INPUTS ── */
        .form-group { margin-bottom: 18px; }
        .form-group label {
            display: block; margin-bottom: 7px;
            font-size: 10px; font-weight: 700; letter-spacing: 2.5px;
            text-transform: uppercase; color: var(--gray);
        }
        .input-wrap { position: relative; }
        .input-wrap .form-control { padding-left: 44px; }
        .form-control {
            width: 100%; padding: 13px 16px;
            background: rgba(255,255,255,.04);
            border: 1px solid #2a2a2a;
            border-radius: 8px; color: var(--light); font-size: 14px;
            transition: border-color .25s, box-shadow .25s, background .25s;
            font-family: 'Barlow', sans-serif; outline: none;
        }
        .form-control::placeholder { color: #3a3a3a; }
        .form-control:focus {
            border-color: rgba(220,0,0,.5);
            background: rgba(220,0,0,.03);
            box-shadow: 0 0 0 3px rgba(220,0,0,.08);
        }
        .input-icon {
            position: absolute; left: 14px; top: 50%; transform: translateY(-50%);
            color: #333; font-size: 14px; transition: color .2s; pointer-events: none;
        }
        .form-control:focus ~ .input-icon { color: var(--ferrari-red); }
        .toggle-pw {
            position: absolute; right: 14px; top: 50%; transform: translateY(-50%);
            color: #444; cursor: pointer; font-size: 13px; transition: color .2s;
        }
        .toggle-pw:hover { color: var(--ferrari-red); }
        .has-toggle { padding-right: 44px; }

        /* ── FLASH MESSAGES ── */
        .flash {
            padding: 10px 14px; border-radius: 6px; margin-bottom: 16px;
            font-size: 12px; font-weight: 600;
            display: flex; align-items: flex-start; gap: 8px;
        }
        .flash i { margin-top: 1px; flex-shrink: 0; }
        .flash-error   { background: rgba(220,0,0,.07); border: 1px solid rgba(220,0,0,.3); color: #ff6b6b; }
        .flash-success { background: rgba(0,180,80,.07); border: 1px solid rgba(0,180,80,.3); color: #4caf82; }

        /* ── REMEMBER ROW ── */
        .remember-row {
            display: flex; align-items: center; justify-content: space-between;
            margin-bottom: 24px;
        }
        .remember-row label {
            color: var(--gray); font-size: 13px;
            display: flex; align-items: center; gap: 8px; cursor: pointer;
        }
        .remember-row input[type="checkbox"] { accent-color: var(--ferrari-red); width: 15px; height: 15px; }

        .fp-trigger {
            color: var(--gray); font-size: 12px;
            background: none; border: none; cursor: pointer;
            font-family: 'Barlow', sans-serif;
            transition: color .2s; padding: 0;
        }
        .fp-trigger:hover { color: var(--ferrari-red); }

        /* ── SUBMIT BUTTON ── */
        .btn-login {
            width: 100%; padding: 14px; font-size: 13px; letter-spacing: 3px;
            background: var(--ferrari-red); color: #fff; border: none;
            border-radius: 8px; cursor: pointer; font-weight: 700;
            text-transform: uppercase; font-family: 'Barlow', sans-serif;
            display: flex; align-items: center; justify-content: center; gap: 10px;
            transition: background .2s, transform .15s, box-shadow .2s;
            position: relative; overflow: hidden;
        }
        .btn-login::after {
            content: ''; position: absolute; inset: 0;
            background: linear-gradient(120deg,transparent 30%,rgba(255,255,255,.1) 50%,transparent 70%);
            transform: translateX(-100%); transition: transform .5s ease;
        }
        .btn-login:hover::after { transform: translateX(100%); }
        .btn-login:hover  { background: #b00000; transform: translateY(-2px); box-shadow: 0 10px 28px rgba(220,0,0,.35); }
        .btn-login:active { transform: translateY(0); }
        .btn-login:disabled { opacity: .6; cursor: not-allowed; pointer-events: none; }
        .btn-login .spinner {
            width: 16px; height: 16px;
            border: 2px solid rgba(255,255,255,.3); border-top-color: #fff;
            border-radius: 50%; animation: spin .7s linear infinite; display: none;
        }
        .btn-login.loading .spinner { display: block; }
        @keyframes spin { to { transform: rotate(360deg); } }

        .btn-secondary {
            width: 100%; padding: 13px; font-size: 13px; letter-spacing: 2px;
            background: transparent; color: var(--gray);
            border: 1px solid #2a2a2a; border-radius: 8px;
            cursor: pointer; font-weight: 600; text-transform: uppercase;
            font-family: 'Barlow', sans-serif;
            display: flex; align-items: center; justify-content: center; gap: 8px;
            transition: border-color .25s, color .25s, transform .2s, box-shadow .2s;
            position: relative; overflow: hidden;
        }
        .btn-secondary:hover {
            border-color: rgba(220,0,0,.4); color: var(--light);
            transform: translateY(-1px); box-shadow: 0 6px 18px rgba(220,0,0,.1);
        }
        .btn-secondary:active { transform: translateY(0); }
        .btn-secondary .spinner {
            width: 15px; height: 15px;
            border: 2px solid rgba(255,255,255,.2); border-top-color: var(--ferrari-red);
            border-radius: 50%; animation: spin .7s linear infinite; display: none;
        }
        .btn-secondary.loading .spinner { display: block; }

        /* ── FORGOT PANEL STYLES ── */
        .forgot-header {
            display: flex; flex-direction: column; align-items: center;
            margin-bottom: 28px; margin-top: 12px;
        }
        .forgot-icon-wrap {
            width: 64px; height: 64px; border-radius: 50%;
            background: rgba(220,0,0,.08); border: 1px solid rgba(220,0,0,.25);
            display: flex; align-items: center; justify-content: center;
            margin-bottom: 14px; font-size: 26px; color: var(--ferrari-red);
            animation: logoGlow 3s ease infinite alternate; transition: transform .3s;
        }
        .forgot-icon-wrap:hover { transform: scale(1.08); }

        .back-btn {
            display: flex; align-items: center; gap: 7px;
            background: none; border: none; cursor: pointer;
            color: #444; font-size: 12px; letter-spacing: 1px;
            font-family: 'Barlow', sans-serif; padding: 0;
            margin-bottom: 20px; transition: color .2s;
        }
        .back-btn:hover { color: var(--ferrari-red); }

        .fp-success { display: none; text-align: center; padding: 10px 0; }
        .fp-success.visible { display: block; }
        .fp-success-icon {
            width: 64px; height: 64px; border-radius: 50%;
            background: rgba(29,185,84,.1); border: 1px solid rgba(29,185,84,.3);
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto 16px; font-size: 28px; color: #1db954;
            animation: successPop .5s cubic-bezier(.25,.8,.25,1) both;
        }
        @keyframes successPop {
            from { transform: scale(0); opacity: 0; }
            to   { transform: scale(1); opacity: 1; }
        }
        .fp-success-title {
            font-family: 'Bebas Neue', sans-serif; font-size: 22px;
            letter-spacing: 2px; margin-bottom: 10px; color: #1db954;
        }
        .fp-success-msg { color: var(--gray); font-size: 13px; line-height: 1.8; margin-bottom: 24px; }
        .fp-success-email { color: var(--light); font-weight: 700; font-size: 14px; word-break: break-all; }

        .resend-wrap { text-align: center; margin-top: 16px; font-size: 12px; color: #555; }
        .resend-btn {
            background: none; border: none; cursor: pointer;
            color: var(--ferrari-red); font-family: 'Barlow', sans-serif;
            font-size: 12px; font-weight: 700; padding: 0;
            transition: opacity .2s; display: none;
        }
        .resend-btn:hover { opacity: .75; text-decoration: underline; }
        .resend-btn.visible { display: inline; }

        /* ── LOCKOUT ── */
        .lockout-panel {
            display: none;
            background: rgba(220,0,0,.06); border: 1px solid rgba(220,0,0,.3);
            border-radius: 10px; padding: 20px; text-align: center; margin-bottom: 16px;
        }
        .lockout-panel.visible { display: block; animation: lockShake .5s cubic-bezier(.25,.8,.25,1) both; }
        @keyframes lockShake {
            0%,100% { transform:translateX(0); }
            20%     { transform:translateX(-8px); }
            40%     { transform:translateX(8px); }
            60%     { transform:translateX(-5px); }
            80%     { transform:translateX(5px); }
        }
        .lockout-icon  { font-size: 26px; color: var(--ferrari-red); margin-bottom: 8px; }
        .lockout-title { font-family:'Bebas Neue',sans-serif; font-size:18px; letter-spacing:2px; color:var(--ferrari-red); margin-bottom:6px; }
        .lockout-msg   { color: var(--gray); font-size: 12px; line-height: 1.7; margin-bottom: 14px; }
        .countdown-wrap { position: relative; width: 72px; height: 72px; margin: 0 auto 10px; }
        .countdown-svg  { transform: rotate(-90deg); }
        .countdown-bg   { fill: none; stroke: rgba(220,0,0,.12); stroke-width: 4; }
        .countdown-arc  {
            fill: none; stroke: var(--ferrari-red); stroke-width: 4;
            stroke-linecap: round; stroke-dasharray: 188.5; stroke-dashoffset: 0;
            transition: stroke-dashoffset 1s linear;
        }
        .countdown-number {
            position: absolute; inset: 0;
            display: flex; align-items: center; justify-content: center;
            font-family: 'Bebas Neue', sans-serif; font-size: 22px;
            letter-spacing: 1px; color: var(--ferrari-red);
        }

        .back-link {
            display: flex; align-items: center; justify-content: center;
            gap: 7px; margin-top: 22px;
            color: #444; font-size: 12px; letter-spacing: 1px;
            text-decoration: none; transition: color .2s;
        }
        .back-link:hover { color: var(--ferrari-red); }

        /* ── SOUND BUTTON ── */
        .sound-btn {
            position: fixed; bottom: 24px; right: 24px; z-index: 100;
            width: 42px; height: 42px; background: rgba(18,18,18,.85);
            border: 1px solid rgba(220,0,0,.25); border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            cursor: pointer; color: var(--gray); font-size: 15px;
            backdrop-filter: blur(10px);
            transition: background .25s, border-color .25s, color .25s, transform .25s;
        }
        .sound-btn:hover {
            background: rgba(220,0,0,.12); border-color: rgba(220,0,0,.5);
            color: var(--ferrari-red); transform: scale(1.1);
        }
        .sound-btn.unmuted { color: var(--ferrari-red); border-color: rgba(220,0,0,.4); background: rgba(220,0,0,.08); }
        .sound-btn.unmuted::after {
            content: ''; position: absolute; inset: -4px; border-radius: 50%;
            border: 1px solid rgba(220,0,0,.3);
            animation: soundPulse 1.8s ease infinite;
        }
        @keyframes soundPulse {
            0%   { transform: scale(1);   opacity: .6; }
            100% { transform: scale(1.5); opacity: 0; }
        }

        @media (max-height:680px) { .panel { padding: 28px 36px; } .login-logo-wrap { margin-bottom:14px; } }
        @media (max-width:480px)  { .panel { padding: 36px 20px; } }
    </style>
</head>
<body>

<video class="admin-bg-video" autoplay muted loop playsinline>
    <source src="{{ asset('videos/admin-bg.mp4') }}" type="video/mp4">
</video>
<div class="admin-bg-overlay"></div>
<div class="admin-tint"></div>

<button class="sound-btn" id="soundBtn" title="Toggle sound" onclick="toggleSound()">
    <i class="fas fa-volume-mute" id="soundIcon"></i>
</button>

<div class="auth-wrap">
<div class="auth-card">

    <div class="admin-ribbon">
        <i class="fas fa-shield-alt"></i> Admin Portal
    </div>

    <div class="card-slider" id="cardSlider">

        {{-- ── PANEL 1 — LOGIN ── --}}
        <div class="panel panel-login">

            <div class="login-logo-wrap">
                @if(file_exists(public_path('images/logo.png')))
                    <img src="{{ asset('images/logo.png') }}" alt="Logo" class="login-logo-img">
                @else
                    <div class="login-logo-icon"><i class="fas fa-horse"></i></div>
                @endif
                <div class="login-logo-name">Veloce Vantage</div>
                <div class="login-logo-sub">Admin Dashboard</div>
            </div>

            <div class="accent-line"></div>
            <div class="auth-title">ADMIN ACCESS</div>
            <div class="auth-sub">Restricted area — authorized personnel only</div>

            @if(session('error'))
                <div class="flash flash-error">
                    <i class="fas fa-exclamation-circle"></i>
                    <span>{{ session('error') }}</span>
                </div>
            @endif
            @if(session('success'))
                <div class="flash flash-success">
                    <i class="fas fa-check-circle"></i>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if(session('throttle_seconds'))
                <div class="lockout-panel visible" id="lockoutPanel">
                    <div class="lockout-icon"><i class="fas fa-lock"></i></div>
                    <div class="lockout-title">Temporarily Locked</div>
                    <div class="countdown-wrap">
                        <svg class="countdown-svg" width="72" height="72" viewBox="0 0 72 72">
                            <circle class="countdown-bg" cx="36" cy="36" r="30"/>
                            <circle class="countdown-arc" id="serverArc" cx="36" cy="36" r="30"/>
                        </svg>
                        <div class="countdown-number" id="serverCountdown">{{ session('throttle_seconds') }}</div>
                    </div>
                    <div class="lockout-msg">Too many failed attempts.<br>Please wait before trying again.</div>
                </div>
            @endif

            @if($errors->any() && old('from_panel') !== 'forgot')
                <div class="flash flash-error">
                    <i class="fas fa-exclamation-circle"></i>
                    <span>{{ $errors->first() }}</span>
                </div>
            @endif

            <div class="lockout-panel" id="clientLockout">
                <div class="lockout-icon"><i class="fas fa-lock"></i></div>
                <div class="lockout-title">Too Many Attempts</div>
                <div class="countdown-wrap">
                    <svg class="countdown-svg" width="72" height="72" viewBox="0 0 72 72">
                        <circle class="countdown-bg" cx="36" cy="36" r="30"/>
                        <circle class="countdown-arc" id="countdownArc" cx="36" cy="36" r="30"/>
                    </svg>
                    <div class="countdown-number" id="countdownNum">30</div>
                </div>
                <div class="lockout-msg">Please wait <strong id="waitSec">30</strong> seconds.</div>
            </div>

            <form method="POST" action="{{ route('admin.login.post') }}" id="adminLoginForm">
                @csrf
                <div class="form-group">
                    <label>Email Address</label>
                    <div class="input-wrap">
                        <input type="email" name="email" id="loginEmail" class="form-control"
                            placeholder="admin@rossocorsa.com"
                            value="{{ old('email') }}" required autofocus>
                        <i class="fas fa-envelope input-icon"></i>
                    </div>
                </div>
                <div class="form-group">
                    <label>Password</label>
                    <div class="input-wrap">
                        <input type="password" name="password" id="loginPassword"
                            class="form-control has-toggle"
                            placeholder="Your admin password" required>
                        <i class="fas fa-lock input-icon"></i>
                        <span class="toggle-pw" onclick="togglePw('loginPassword','eyeIconLogin')">
                            <i class="fas fa-eye" id="eyeIconLogin"></i>
                        </span>
                    </div>
                </div>
                <div class="remember-row">
                    <label>
                        <input type="checkbox" name="remember"> Remember me
                    </label>
                    <button type="button" class="fp-trigger" onclick="showForgot()">
                        Forgot password?
                    </button>
                </div>
                <button type="submit" class="btn-login" id="loginBtn">
                    <div class="spinner"></div>
                    <span class="btn-text">
                        <i class="fas fa-shield-alt" style="margin-right:8px;font-size:12px"></i>
                        Admin Sign In
                    </span>
                </button>
            </form>

            <a href="{{ route('home') }}" class="back-link">
                <i class="fas fa-arrow-left"></i> Back to main site
            </a>
        </div>

        {{-- ── PANEL 2 — FORGOT PASSWORD ── --}}
        <div class="panel panel-forgot">

            <button type="button" class="back-btn" onclick="showLogin()">
                <i class="fas fa-arrow-left"></i> Back to Login
            </button>

            <div class="forgot-header">
                <div class="forgot-icon-wrap">
                    <i class="fas fa-key"></i>
                </div>
                <div class="auth-title" style="margin-bottom:4px">FORGOT PASSWORD</div>
                <div class="auth-sub" style="margin-bottom:0">
                    Enter your admin email to receive a reset link.
                </div>
            </div>

            {{-- STEP A: Email form --}}
            <div id="fpForm">

                {{-- Show error only when from forgot panel --}}
                @if($errors->has('email') && old('from_panel') === 'forgot')
                    <div class="flash flash-error">
                        <i class="fas fa-exclamation-circle"></i>
                        <span>{{ $errors->first('email') }}</span>
                    </div>
                @endif

                {{-- ✅ FIXED: now points to admin.password.email --}}
                <form method="POST" action="{{ route('admin.password.email') }}" id="forgotForm"
                      onsubmit="startForgotSubmit()">
                    @csrf
                    <input type="hidden" name="from_panel" value="forgot">

                    <div class="form-group">
                        <label>Admin Email Address</label>
                        <div class="input-wrap">
                            <input type="email" name="email" id="fpEmail" class="form-control"
                                placeholder="admin@rossocorsa.com"
                                value="{{ old('email') }}" required>
                            <i class="fas fa-envelope input-icon"></i>
                        </div>
                    </div>

                    <button type="submit" class="btn-login" id="fpSubmitBtn" style="margin-bottom:12px">
                        <div class="spinner"></div>
                        <span class="fp-btn-text">
                            <i class="fas fa-paper-plane" style="margin-right:8px;font-size:12px"></i>
                            Send Reset Link
                        </span>
                    </button>
                </form>
            </div>

            {{-- STEP B: Success state --}}
            <div class="fp-success" id="fpSuccess">
                <div class="fp-success-icon"><i class="fas fa-check"></i></div>
                <div class="fp-success-title">Email Sent!</div>
                <div class="fp-success-msg">
                    We've sent a password reset link to:<br>
                    <span class="fp-success-email" id="fpSuccessEmail">—</span>
                </div>
                <div style="color:var(--gray);font-size:12px;margin-bottom:16px;line-height:1.7">
                    Check your inbox (and spam folder).<br>
                    The link expires in <strong style="color:var(--light)">60 minutes</strong>.
                </div>
                <button type="button" class="btn-secondary" onclick="showLogin()">
                    <i class="fas fa-arrow-left"></i> Back to Login
                </button>
                <div class="resend-wrap" id="resendWrap">
                    <span id="resendTimer">Resend available in <strong id="resendSec">60</strong>s</span>
                    <button type="button" class="resend-btn" id="resendBtn" onclick="resendEmail()">
                        Resend email
                    </button>
                </div>
            </div>

        </div>{{-- /.panel-forgot --}}

    </div>{{-- /.card-slider --}}
</div>{{-- /.auth-card --}}
</div>{{-- /.auth-wrap --}}

<script>
// ── PANEL SWITCHING ──────────────────────────────────────────
const slider = document.getElementById('cardSlider');

function showForgot() {
    slider.classList.add('show-forgot');
    setTimeout(() => document.getElementById('fpEmail')?.focus(), 580);
}
function showLogin() {
    slider.classList.remove('show-forgot');
    setTimeout(() => document.getElementById('loginEmail')?.focus(), 580);
}

// Auto-slide back to forgot panel if submission failed
@if(old('from_panel') === 'forgot' && $errors->has('email'))
    document.addEventListener('DOMContentLoaded', showForgot);
@endif

// Auto-slide + show success if Laravel flashed status
@if(session('status'))
    document.addEventListener('DOMContentLoaded', () => {
        showForgot();
        showForgotSuccess('{{ old('email', '') }}');
    });
@endif

// ── FORGOT SUCCESS STATE ─────────────────────────────────────
function showForgotSuccess(email) {
    document.getElementById('fpForm').style.display = 'none';
    const success = document.getElementById('fpSuccess');
    success.classList.add('visible');
    document.getElementById('fpSuccessEmail').textContent = email || '(your email)';
    startResendTimer();
}

let resendInterval = null;
function startResendTimer() {
    const secEl = document.getElementById('resendSec');
    const timer = document.getElementById('resendTimer');
    const btn   = document.getElementById('resendBtn');
    let secs    = 60;
    if (resendInterval) clearInterval(resendInterval);
    resendInterval = setInterval(() => {
        secs--;
        secEl.textContent = secs;
        if (secs <= 0) {
            clearInterval(resendInterval);
            timer.style.display = 'none';
            btn.classList.add('visible');
        }
    }, 1000);
}
function resendEmail() {
    document.getElementById('forgotForm').submit();
}

// ── FORGOT SUBMIT LOADING ────────────────────────────────────
function startForgotSubmit() {
    const btn  = document.getElementById('fpSubmitBtn');
    const text = btn.querySelector('.fp-btn-text');
    btn.disabled = true;
    btn.classList.add('loading');
    text.textContent = 'Sending…';
}

// ── LOGIN SUBMIT LOADING ─────────────────────────────────────
const loginBtn = document.getElementById('loginBtn');
const btnText  = loginBtn.querySelector('.btn-text');

document.getElementById('adminLoginForm').addEventListener('submit', function(e) {
    const stillLocked = parseInt(localStorage.getItem('ferrari_admin_lockout_until') || '0');
    if (stillLocked > Date.now()) {
        e.preventDefault();
        showClientLockout(Math.ceil((stillLocked - Date.now()) / 1000));
        return;
    }
    loginBtn.disabled = true;
    loginBtn.classList.add('loading');
    btnText.innerHTML = 'Signing in…';
});

// ── LOCKOUT SYSTEM ───────────────────────────────────────────
const LOCKOUT_KEY   = 'ferrari_admin_lockout_until';
const CIRCUMFERENCE = 188.5;
const serverThrottled = {{ session('throttle_seconds', 0) }};

if (serverThrottled > 0) {
    disableLoginForm();
    startServerCountdown(serverThrottled);
} else {
    const lockoutUntil = parseInt(localStorage.getItem(LOCKOUT_KEY) || '0');
    if (lockoutUntil > Date.now()) {
        showClientLockout(Math.ceil((lockoutUntil - Date.now()) / 1000));
    }
}

function startServerCountdown(secs) {
    const arc = document.getElementById('serverArc');
    const num = document.getElementById('serverCountdown');
    if (!arc || !num) return;
    let remaining = secs;
    const startedAt = Date.now();
    const tick = setInterval(() => {
        remaining = secs - Math.round((Date.now() - startedAt) / 1000);
        if (remaining <= 0) {
            clearInterval(tick);
            document.getElementById('lockoutPanel')?.classList.remove('visible');
            enableLoginForm();
            return;
        }
        num.textContent = remaining;
        arc.style.strokeDashoffset = CIRCUMFERENCE * (1 - remaining / secs);
    }, 1000);
}

function showClientLockout(seconds) {
    const panel = document.getElementById('clientLockout');
    const arc   = document.getElementById('countdownArc');
    const num   = document.getElementById('countdownNum');
    const wait  = document.getElementById('waitSec');
    localStorage.setItem(LOCKOUT_KEY, Date.now() + seconds * 1000);
    panel.classList.add('visible');
    disableLoginForm();
    arc.style.transition       = 'none';
    arc.style.strokeDashoffset = 0;
    void arc.getBoundingClientRect();
    arc.style.transition = 'stroke-dashoffset 1s linear';
    let remaining = seconds;
    num.textContent  = remaining;
    wait.textContent = remaining;
    const startedAt = Date.now();
    const tick = setInterval(() => {
        remaining = seconds - Math.round((Date.now() - startedAt) / 1000);
        if (remaining <= 0) {
            clearInterval(tick);
            panel.classList.remove('visible');
            localStorage.removeItem(LOCKOUT_KEY);
            enableLoginForm();
            return;
        }
        num.textContent  = remaining;
        wait.textContent = remaining;
        arc.style.strokeDashoffset = CIRCUMFERENCE * (1 - remaining / seconds);
    }, 1000);
}

function disableLoginForm() {
    loginBtn.disabled = true;
    loginBtn.style.opacity = '0.5';
    loginBtn.style.cursor  = 'not-allowed';
    document.getElementById('loginEmail').disabled    = true;
    document.getElementById('loginPassword').disabled = true;
}
function enableLoginForm() {
    loginBtn.disabled = false;
    loginBtn.style.opacity = '';
    loginBtn.style.cursor  = '';
    loginBtn.classList.remove('loading');
    btnText.innerHTML = '<i class="fas fa-shield-alt" style="margin-right:8px;font-size:12px"></i>Admin Sign In';
    document.getElementById('loginEmail').disabled    = false;
    document.getElementById('loginPassword').disabled = false;
}

// ── HELPERS ──────────────────────────────────────────────────
function togglePw(inputId, iconId) {
    const input  = document.getElementById(inputId);
    const isText = input.type === 'text';
    input.type   = isText ? 'password' : 'text';
    document.getElementById(iconId).className = isText ? 'fas fa-eye' : 'fas fa-eye-slash';
}

// ── SOUND ────────────────────────────────────────────────────
const bgVideo   = document.querySelector('.admin-bg-video');
const soundBtn  = document.getElementById('soundBtn');
const soundIcon = document.getElementById('soundIcon');

function toggleSound() {
    if (bgVideo.muted) {
        bgVideo.muted  = false;
        bgVideo.volume = 0.35;
        soundBtn.classList.add('unmuted');
        soundIcon.className = 'fas fa-volume-up';
    } else {
        bgVideo.muted = true;
        soundBtn.classList.remove('unmuted');
        soundIcon.className = 'fas fa-volume-mute';
    }
}
</script>

</body>
</html>