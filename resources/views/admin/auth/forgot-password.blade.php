<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password — ROSSO CORSA Admin</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Barlow:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        :root {
            --ferrari-red: #dc0000;
            --dark: #0d0d0d;
            --dark2: #1a1a1a;
            --light: #e8e8e8;
            --gray: #888;
        }
        html, body {
            background: var(--dark); color: var(--light);
            font-family: 'Barlow', sans-serif;
            min-height: 100%; overflow: hidden;
        }

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

        .auth-wrap {
            position: fixed; inset: 0;
            display: flex; align-items: center; justify-content: center;
            padding: 0 16px; z-index: 10;
        }

        .auth-card {
            position: relative; z-index: 2;
            background: rgba(18,18,18,0.92);
            border: 1px solid rgba(220,0,0,0.22);
            border-radius: 14px;
            width: 100%; max-width: 420px;
            padding: 48px 44px;
            backdrop-filter: blur(24px);
            box-shadow: 0 24px 80px rgba(0,0,0,.5),
                        0 0 0 1px rgba(220,0,0,.07),
                        0 0 60px rgba(220,0,0,.04);
            animation: cardIn 0.65s cubic-bezier(.25,.8,.25,1) both;
        }
        @keyframes cardIn {
            from { opacity:0; transform:translateY(40px) scale(.97); }
            to   { opacity:1; transform:translateY(0) scale(1); }
        }

        .admin-ribbon {
            position: absolute; top: -1px; left: 50%; transform: translateX(-50%);
            background: var(--ferrari-red); color: #fff;
            font-family: 'Bebas Neue', sans-serif; font-size: 10px; letter-spacing: 3px;
            padding: 4px 20px; border-radius: 0 0 8px 8px;
            display: flex; align-items: center; gap: 6px; z-index: 5;
            white-space: nowrap;
        }

        .logo-wrap {
            display: flex; flex-direction: column; align-items: center;
            margin-bottom: 24px; margin-top: 12px;
        }
        .lock-icon-wrap {
            width: 64px; height: 64px; border-radius: 50%;
            background: rgba(220,0,0,.08); border: 1px solid rgba(220,0,0,.25);
            display: flex; align-items: center; justify-content: center;
            margin-bottom: 14px; font-size: 26px; color: var(--ferrari-red);
            animation: logoGlow 3s ease infinite alternate;
        }
        @keyframes logoGlow {
            from { filter: drop-shadow(0 0 6px rgba(220,0,0,.2)); }
            to   { filter: drop-shadow(0 0 18px rgba(220,0,0,.55)); }
        }
        .logo-name {
            font-family: 'Bebas Neue', sans-serif; font-size: 22px;
            letter-spacing: 5px; color: var(--ferrari-red);
        }
        .logo-sub { font-size: 9px; letter-spacing: 4px; color: #555; text-transform: uppercase; margin-top: 2px; }

        .accent-line {
            width: 40px; height: 2px; background: var(--ferrari-red);
            margin: 14px auto 22px;
            animation: lineExpand .6s ease .3s both;
        }
        @keyframes lineExpand {
            from { transform:scaleX(0); opacity:0; }
            to   { transform:scaleX(1); opacity:1; }
        }

        .auth-title {
            font-family: 'Bebas Neue', sans-serif; font-size: 26px;
            letter-spacing: 3px; margin-bottom: 4px; text-align: center;
        }
        .auth-sub {
            color: var(--gray); font-size: 12px; margin-bottom: 28px;
            text-align: center; letter-spacing: .5px; line-height: 1.7;
        }

        .flash {
            padding: 10px 14px; border-radius: 6px; margin-bottom: 16px;
            font-size: 12px; font-weight: 600;
            display: flex; align-items: flex-start; gap: 8px;
        }
        .flash i { margin-top: 1px; flex-shrink: 0; }
        .flash-error   { background: rgba(220,0,0,.07); border: 1px solid rgba(220,0,0,.3); color: #ff6b6b; }
        .flash-success { background: rgba(0,180,80,.07); border: 1px solid rgba(0,180,80,.3); color: #4caf82; }
        .form-error { color: #ff6b6b; font-size: 11px; margin-top: 5px; }

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

        .back-link {
            display: flex; align-items: center; justify-content: center;
            gap: 7px; margin-top: 22px;
            color: #444; font-size: 12px; letter-spacing: 1px;
            text-decoration: none; transition: color .2s;
        }
        .back-link:hover { color: var(--ferrari-red); }
        .back-link i { transition: transform .2s; }
        .back-link:hover i { transform: translateX(-3px); }

        /* Success state */
        .success-state {
            display: none;
            animation: fadeIn 0.5s ease both;
        }
        @keyframes fadeIn { from { opacity: 0; transform: scale(0.95); } to { opacity: 1; transform: scale(1); } }
        .success-icon {
            width: 64px; height: 64px; border-radius: 50%;
            background: rgba(29,185,84,0.1); border: 1px solid rgba(29,185,84,0.4);
            display: flex; align-items: center; justify-content: center;
            font-size: 26px; color: #1db954; margin: 0 auto 20px;
        }
        .auth-link { color: var(--ferrari-red); transition: opacity 0.2s; cursor: pointer; }
        .auth-link:hover { opacity: 0.8; text-decoration: underline; }

        @media (max-height:680px) { .auth-card { padding: 28px 36px; } }
        @media (max-width:480px)  { .auth-card { padding: 36px 20px; } }
    </style>
</head>
<body>

<video class="admin-bg-video" autoplay muted loop playsinline>
    <source src="{{ asset('videos/admin-bg.mp4') }}" type="video/mp4">
</video>
<div class="admin-bg-overlay"></div>
<div class="admin-tint"></div>

<div class="auth-wrap">
<div class="auth-card">

    <div class="admin-ribbon">
        <i class="fas fa-shield-alt"></i> Admin Portal
    </div>

    {{-- FORM STATE --}}
    <div id="formState">
        <div class="logo-wrap">
            @if(file_exists(public_path('images/logo.png')))
                <img src="{{ asset('images/logo.png') }}" alt="Logo"
                     style="height:50px;width:auto;margin-bottom:12px;filter:drop-shadow(0 0 10px rgba(220,0,0,.4))">
            @else
                <div class="lock-icon-wrap"><i class="fas fa-key"></i></div>
            @endif
            <div class="logo-name">ROSSO CORSA</div>
            <div class="logo-sub">Admin Dashboard</div>
        </div>

        <div class="accent-line"></div>
        <div class="auth-title">FORGOT PASSWORD</div>
        <div class="auth-sub">Enter your admin email and we'll send you a reset link.</div>

        @if(session('error'))
            <div class="flash flash-error">
                <i class="fas fa-exclamation-circle"></i>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        @error('email')
            <div class="flash flash-error">
                <i class="fas fa-exclamation-circle"></i>
                <span>{{ $message }}</span>
            </div>
        @enderror

        <form method="POST" action="{{ route('admin.password.email') }}" id="forgotForm">
            @csrf
            <div class="form-group">
                <label>Admin Email</label>
                <div class="input-wrap">
                    <input
                        type="email" name="email" id="emailInput"
                        class="form-control"
                        placeholder="admin@rossocorsa.com"
                        value="{{ old('email') }}"
                        required autofocus
                    >
                    <i class="fas fa-envelope input-icon"></i>
                </div>
            </div>

            <button type="submit" class="btn-login" id="submitBtn">
                <div class="spinner"></div>
                <span class="btn-text">
                    <i class="fas fa-paper-plane" style="margin-right:8px;font-size:12px"></i>
                    Send Reset Link
                </span>
            </button>
        </form>

        <a href="{{ route('admin.login') }}" class="back-link">
            <i class="fas fa-arrow-left"></i> Back to Admin Login
        </a>
    </div>

    {{-- SUCCESS STATE --}}
    <div class="success-state" id="successState">
        <div style="text-align:center;">
            <div class="success-icon"><i class="fas fa-paper-plane"></i></div>
            <div class="auth-title" style="font-size:22px;">CHECK YOUR EMAIL</div>
            <p style="color:var(--gray); font-size:13px; margin-top:12px; line-height:1.8;">
                If that email belongs to an admin account, a reset link has been sent to<br>
                <strong style="color:var(--light);" id="sentEmail"></strong>
            </p>
            <p style="color:#444; font-size:12px; margin-top:16px;">
                Didn't receive it? Check spam or
                <a class="auth-link" onclick="resetForm()">try again</a>.
            </p>
        </div>
        <a href="{{ route('admin.login') }}" class="back-link">
            <i class="fas fa-arrow-left"></i> Back to Admin Login
        </a>
    </div>

</div>
</div>

<script>
const form      = document.getElementById('forgotForm');
const submitBtn = document.getElementById('submitBtn');

form.addEventListener('submit', () => {
    submitBtn.classList.add('loading');
    submitBtn.querySelector('.btn-text').textContent = 'Sending…';
});

function showSuccess() {
    const email = document.getElementById('emailInput')?.value || '{{ old("email") }}';
    document.getElementById('sentEmail').textContent = email;
    document.getElementById('formState').style.display   = 'none';
    document.getElementById('successState').style.display = 'block';
}

function resetForm() {
    document.getElementById('formState').style.display   = 'block';
    document.getElementById('successState').style.display = 'none';
}

// Auto-show success if Laravel flashed a status
@if(session('status'))
    document.addEventListener('DOMContentLoaded', showSuccess);
@endif
</script>

</body>
</html>