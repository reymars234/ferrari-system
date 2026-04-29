@extends('layouts.app')
@section('title', 'Login')
@push('styles')
<style>
    /* ── Lock page, full screen ── */
    html, body { overflow: hidden !important; height: 100% !important; }
    .page-content { overflow: hidden !important; }

    .auth-wrap {
        position: fixed; inset: 0;
        display: flex; align-items: center; justify-content: center;
        padding: 0 16px; z-index: 10;
    }

    /* BG video */
    .login-bg-video {
        position: fixed; inset: 0; width: 100%; height: 100%;
        object-fit: cover; z-index: 0; opacity: 0.35; pointer-events: none;
    }
    .login-bg-overlay {
        position: fixed; inset: 0; z-index: 1;
        background: rgba(10,10,10,0.58); pointer-events: none;
    }

    /* Card */
    .auth-card {
        position: relative; z-index: 2;
        background: rgba(22,22,22,0.88);
        border: 1px solid rgba(220,0,0,0.18);
        border-radius: 14px; padding: 48px 44px;
        width: 100%; max-width: 420px;
        backdrop-filter: blur(24px);
        box-shadow: 0 24px 60px rgba(0,0,0,0.35), 0 0 0 1px rgba(220,0,0,0.06);
        animation: cardIn 0.65s cubic-bezier(.25,.8,.25,1) both;
    }
    @keyframes cardIn {
        from { opacity: 0; transform: translateY(40px) scale(0.97); }
        to   { opacity: 1; transform: translateY(0) scale(1); }
    }

    /* Logo */
    .login-logo-wrap {
        display: flex; flex-direction: column; align-items: center; margin-bottom: 32px;
        animation: logoIn 0.8s cubic-bezier(.25,.8,.25,1) 0.05s both;
    }
    @keyframes logoIn {
        from { opacity: 0; transform: translateY(-20px) scale(0.9); }
        to   { opacity: 1; transform: translateY(0) scale(1); }
    }
    .login-logo-img {
        height: 64px; width: auto; margin-bottom: 12px;
        filter: drop-shadow(0 0 10px rgba(220,0,0,0.3));
        transition: filter 0.4s ease, transform 0.4s ease;
        animation: logoGlow 3s ease infinite alternate;
    }
    @keyframes logoGlow {
        from { filter: drop-shadow(0 0 6px rgba(220,0,0,0.2)); }
        to   { filter: drop-shadow(0 0 18px rgba(220,0,0,0.55)); }
    }
    .login-logo-img:hover { transform: scale(1.08) rotate(-3deg); filter: drop-shadow(0 0 22px rgba(220,0,0,0.7)); }
    .login-logo-icon {
        width: 64px; height: 64px; border-radius: 50%;
        background: rgba(220,0,0,0.08); border: 1px solid rgba(220,0,0,0.25);
        display: flex; align-items: center; justify-content: center;
        margin-bottom: 12px; animation: logoGlow 3s ease infinite alternate; transition: all 0.4s ease;
    }
    .login-logo-icon:hover { background: rgba(220,0,0,0.18); transform: scale(1.08); }
    .login-logo-icon i { color: var(--ferrari-red); font-size: 26px; }
    .login-logo-name { font-family: 'Bebas Neue', sans-serif; font-size: 22px; letter-spacing: 5px; color: var(--ferrari-red); animation: fadeUp 0.6s ease 0.15s both; }
    .login-logo-sub  { font-size: 9px; letter-spacing: 4px; color: #444; text-transform: uppercase; margin-top: 2px; animation: fadeUp 0.6s ease 0.2s both; }

    .accent-line { width: 40px; height: 2px; background: var(--ferrari-red); margin: 16px auto 24px; animation: lineExpand 0.6s ease 0.3s both; transform-origin: center; }
    @keyframes lineExpand { from{transform:scaleX(0);opacity:0} to{transform:scaleX(1);opacity:1} }

    .auth-title { font-family: 'Bebas Neue', sans-serif; font-size: 26px; letter-spacing: 3px; margin-bottom: 6px; text-align: center; animation: fadeUp 0.6s ease 0.25s both; }
    .auth-sub    { color: var(--gray); font-size: 13px; margin-bottom: 32px; text-align: center; animation: fadeUp 0.6s ease 0.3s both; }
    .auth-link   { color: var(--ferrari-red); transition: opacity 0.2s; }
    .auth-link:hover { opacity: 0.75; text-decoration: underline; }
    @keyframes fadeUp { from{opacity:0;transform:translateY(12px)} to{opacity:1;transform:translateY(0)} }

    /* Inputs */
    .form-group { margin-bottom: 18px; }
    .input-wrap { position: relative; }
    .input-wrap .form-control { padding-left: 44px; }
    .input-wrap .input-icon { position: absolute; left: 14px; top: 50%; transform: translateY(-50%); color: #444; font-size: 14px; transition: color 0.2s; pointer-events: none; }
    .input-wrap .form-control:focus ~ .input-icon { color: var(--ferrari-red); }
    .toggle-pw { position: absolute; right: 14px; top: 50%; transform: translateY(-50%); color: #444; cursor: pointer; font-size: 13px; transition: color 0.2s; }
    .input-wrap .form-control.has-toggle { padding-right: 44px; }
    .toggle-pw:hover { color: var(--ferrari-red); }

    /* Input error shake */
    .input-error {
        border-color: var(--ferrari-red) !important;
        animation: inputShake 0.4s cubic-bezier(.25,.8,.25,1) both;
    }
    @keyframes inputShake {
        0%,100% { transform: translateX(0); }
        20%     { transform: translateX(-5px); }
        40%     { transform: translateX(5px); }
        60%     { transform: translateX(-3px); }
        80%     { transform: translateX(3px); }
    }

    /* Remember + forgot */
    .remember-row { display: flex; align-items: center; justify-content: space-between; margin-bottom: 24px; }
    .remember-row label { color: var(--gray); font-size: 13px; display: flex; align-items: center; gap: 8px; cursor: pointer; }
    .remember-row input[type="checkbox"] { accent-color: var(--ferrari-red); width: 15px; height: 15px; }
    .forgot-link { color: var(--gray); font-size: 12px; transition: color 0.2s; }
    .forgot-link:hover { color: var(--ferrari-red); }

    /* Submit */
    .btn-login {
        width: 100%; padding: 14px; font-size: 13px; letter-spacing: 3px;
        background: var(--ferrari-red); color: #fff; border: none;
        border-radius: 4px; cursor: pointer; font-weight: 700; text-transform: uppercase;
        font-family: 'Barlow', sans-serif;
        display: flex; align-items: center; justify-content: center; gap: 10px;
        transition: background 0.2s, transform 0.15s, box-shadow 0.2s;
        position: relative; overflow: hidden;
    }
    .btn-login::after {
        content: ''; position: absolute; inset: 0;
        background: linear-gradient(120deg,transparent 30%,rgba(255,255,255,0.1) 50%,transparent 70%);
        transform: translateX(-100%); transition: transform 0.5s ease;
    }
    .btn-login:hover::after { transform: translateX(100%); }
    .btn-login:hover  { background: #b00000; transform: translateY(-2px); box-shadow: 0 10px 28px rgba(220,0,0,0.3); }
    .btn-login:active { transform: translateY(0); }
    .btn-login.loading, .btn-login:disabled { pointer-events: none; opacity: 0.65; cursor: not-allowed; }
    .btn-login .spinner { width: 16px; height: 16px; border: 2px solid rgba(255,255,255,0.3); border-top-color: #fff; border-radius: 50%; animation: spin 0.7s linear infinite; display: none; }
    .btn-login.loading .spinner { display: block; }
    @keyframes spin { to { transform: rotate(360deg); } }

    /* ══════════════════════════════════════════════════════
       LOCKOUT PANEL
       KEY FIX: Base rule has NO opacity/transform.
       .visible sets explicit opacity:1 + transform:none so
       the browser cascade never snaps back to hidden after
       the animation ends.
    ══════════════════════════════════════════════════════ */
    .lockout-panel {
        display: none;
        background: rgba(180,0,0,0.08);
        border: 1px solid rgba(220,0,0,0.28);
        border-radius: 10px;
        padding: 14px 16px;
        margin-bottom: 16px;
        overflow: hidden;
    }
    .lockout-panel.visible {
        display: block;
        opacity: 1;
        transform: translateY(0) scale(1);
        animation: panelIn 0.4s cubic-bezier(.25,.8,.25,1) forwards;
    }
    @keyframes panelIn {
        from { opacity: 0; transform: translateY(-8px) scale(0.97); }
        to   { opacity: 1; transform: translateY(0) scale(1); }
    }
    .lockout-panel.visible.shake {
        animation: lockShake 0.45s cubic-bezier(.25,.8,.25,1) both;
        opacity: 1;
    }
    @keyframes lockShake {
        0%,100% { transform: translateX(0); }
        20%     { transform: translateX(-7px); }
        40%     { transform: translateX(7px); }
        60%     { transform: translateX(-4px); }
        80%     { transform: translateX(4px); }
    }

    .lockout-inner { display: flex; align-items: center; gap: 14px; }
    .lockout-left { flex: 1; min-width: 0; }
    .lockout-icon-row { display: flex; align-items: center; gap: 8px; margin-bottom: 4px; }
    .lockout-icon-row i { color: var(--ferrari-red); font-size: 15px; flex-shrink: 0; }
    .lockout-title {
        font-family: 'Bebas Neue', sans-serif; font-size: 15px;
        letter-spacing: 2px; color: var(--ferrari-red); white-space: nowrap;
    }
    .lockout-msg { color: #888; font-size: 11px; line-height: 1.55; margin: 0; }

    /* ── Countdown ring ── */
    .countdown-wrap { position: relative; width: 52px; height: 52px; flex-shrink: 0; }
    .countdown-svg  { display: block; transform: rotate(-90deg); }
    .countdown-bg   { fill: none; stroke: rgba(220,0,0,0.12); stroke-width: 4; }

    /*
        KEY FIX for disappearing ring:
        Remove `transition` from the CSS entirely.
        We set it via JS AFTER the first paint so the initial
        strokeDashoffset=0 render is instant (no accidental animation
        from 138 → 0 on first frame), then transitions take over
        for each 1-second tick.
    */
    .countdown-arc {
        fill: none;
        stroke: var(--ferrari-red);
        stroke-width: 4;
        stroke-linecap: round;
        stroke-dasharray: 138.2;
        stroke-dashoffset: 0;
        /* NO transition here — added by JS after first paint */
    }
    .countdown-number {
        position: absolute; inset: 0;
        display: flex; align-items: center; justify-content: center;
        font-family: 'Bebas Neue', sans-serif; font-size: 17px;
        letter-spacing: 1px; color: var(--ferrari-red);
    }

    /* Error alert */
    .auth-error {
        background: rgba(220,0,0,0.06); border: 1px solid rgba(220,0,0,0.3);
        border-radius: 6px; padding: 10px 14px; margin-bottom: 16px;
        font-size: 12px; color: var(--ferrari-red);
        display: none; align-items: flex-start; gap: 8px;
    }
    .auth-error.visible {
        display: flex;
        animation: fadeUp 0.3s ease both;
    }
    .auth-error i { margin-top: 1px; flex-shrink: 0; }

    /* Register prompt */
    .register-prompt { text-align: center; margin-top: 24px; font-size: 13px; color: var(--gray); animation: fadeUp 0.5s ease 0.65s both; }
    .or-divider { display: flex; align-items: center; gap: 12px; color: #2a2a2a; font-size: 11px; letter-spacing: 2px; margin: 20px 0; animation: fadeUp 0.5s ease 0.6s both; }
    .or-divider::before, .or-divider::after { content:''; flex:1; height:1px; background:#1e1e1e; }

    @media(max-height:680px) { .auth-card{padding:28px 36px} .login-logo-wrap{margin-bottom:18px} .login-logo-img{height:44px} .auth-sub{margin-bottom:18px} }
    @media(max-width:480px)  { .auth-card{padding:36px 24px} }
</style>
@endpush

@section('content')

<video class="login-bg-video" autoplay muted loop playsinline>
    <source src="{{ asset('videos/login-bg.mp4') }}" type="video/mp4">
</video>
<div class="login-bg-overlay"></div>

<div class="auth-wrap">
    <div class="auth-card">

        <div class="login-logo-wrap">
            @if(file_exists(public_path('images/logo.png')))
                <img src="{{ asset('images/logo.png') }}" alt="Logo" class="login-logo-img">
            @else
                <div class="login-logo-icon"><i class="fas fa-horse"></i></div>
            @endif
            <div class="login-logo-name">VELOCE VANTAGE</div>
            <div class="login-logo-sub">Luxury Automotive</div>
        </div>

        <div class="accent-line"></div>
        <div class="auth-title">WELCOME BACK</div>
        <div class="auth-sub">
            Don't have an account?
            <a href="{{ route('register') }}" class="auth-link">Create one</a>
        </div>

        @if(session('throttle_seconds'))
            <div class="lockout-panel visible" id="lockoutPanel">
                <div class="lockout-inner">
                    <div class="lockout-left">
                        <div class="lockout-icon-row">
                            <i class="fas fa-lock"></i>
                            <div class="lockout-title">Account Temporarily Locked</div>
                        </div>
                        <p class="lockout-msg">
                            Too many failed attempts.<br>
                            Please wait <strong id="serverWaitSec" style="color:var(--ferrari-red)">{{ session('throttle_seconds') }}</strong>s before trying again.
                        </p>
                    </div>
                    <div class="countdown-wrap">
                        <svg class="countdown-svg" width="52" height="52" viewBox="0 0 52 52">
                            <circle class="countdown-bg" cx="26" cy="26" r="22"/>
                            <circle class="countdown-arc" id="serverArc" cx="26" cy="26" r="22"/>
                        </svg>
                        <div class="countdown-number" id="serverCountdown">{{ session('throttle_seconds') }}</div>
                    </div>
                </div>
            </div>
        @endif

        <div class="auth-error" id="authError">
            <i class="fas fa-exclamation-circle"></i>
            <span id="authErrorMsg"></span>
        </div>

        <div class="lockout-panel" id="clientLockout">
            <div class="lockout-inner">
                <div class="lockout-left">
                    <div class="lockout-icon-row">
                        <i class="fas fa-lock"></i>
                        <div class="lockout-title" id="lockoutTitle">Too Many Attempts</div>
                    </div>
                    <p class="lockout-msg">
                        Too many failed attempts.<br>
                        Please wait <strong id="waitSec" style="color:var(--ferrari-red)">30</strong>s before trying again.
                    </p>
                </div>
                <div class="countdown-wrap">
                    <svg class="countdown-svg" width="52" height="52" viewBox="0 0 52 52">
                        <circle class="countdown-bg" cx="26" cy="26" r="22"/>
                        <circle class="countdown-arc" id="countdownArc" cx="26" cy="26" r="22"/>
                    </svg>
                    <div class="countdown-number" id="countdownNum">30</div>
                </div>
            </div>
        </div>

        <form id="loginForm">
            @csrf

            <div class="form-group">
                <label>Email Address</label>
                <div class="input-wrap">
                    <input type="email" name="email" class="form-control"
                        placeholder="you@gmail.com"
                        value="{{ old('email') }}" required autofocus
                        id="emailInput">
                    <i class="fas fa-envelope input-icon"></i>
                </div>
            </div>

            <div class="form-group">
                <label>Password</label>
                <div class="input-wrap">
                    <input type="password" name="password" id="loginPw"
                        class="form-control has-toggle"
                        placeholder="Your password" required>
                    <i class="fas fa-lock input-icon"></i>
                    <span class="toggle-pw" onclick="togglePw()">
                        <i class="fas fa-eye" id="eyeIcon"></i>
                    </span>
                </div>
            </div>

            <div class="remember-row">
                <label>
                    <input type="checkbox" name="remember" id="rememberMe"> Remember me
                </label>
                <a href="{{ route('password.request') }}" class="forgot-link">
                    Forgot password?
                </a>
            </div>

            <button type="submit" class="btn-login" id="loginBtn">
                <div class="spinner"></div>
                <span class="btn-text">Sign In</span>
                <i class="fas fa-arrow-right" id="loginArrow"></i>
            </button>
        </form>

        <div class="or-divider">or</div>
        <div class="register-prompt">
            New here?
            <a href="{{ route('register') }}" class="auth-link" style="font-weight:700;">
                Create a free account →
            </a>
        </div>

    </div>
</div>
@endsection

@push('scripts')
<script>
// ══════════════════════════════════════════════════════════════
//  CONFIG
// ══════════════════════════════════════════════════════════════
const LOCKOUT_KEY   = 'ferrari_lockout_until';
const CIRCUMFERENCE = 138.2; // 2π × 22

const LOGIN_URL  = '{{ route('login.post') }}';
const CSRF_TOKEN = '{{ csrf_token() }}';

const serverThrottled = {{ session('throttle_seconds', 0) }};

// ── DOM refs ──────────────────────────────────────────────────
const loginForm     = document.getElementById('loginForm');
const loginBtn      = document.getElementById('loginBtn');
const btnText       = loginBtn.querySelector('.btn-text');
const loginArrow    = document.getElementById('loginArrow');
const authError     = document.getElementById('authError');
const authErrorMsg  = document.getElementById('authErrorMsg');
const clientLockout = document.getElementById('clientLockout');
const countdownNum  = document.getElementById('countdownNum');
const waitSec       = document.getElementById('waitSec');
const countdownArc  = document.getElementById('countdownArc');
const emailInput    = document.getElementById('emailInput');
const pwInput       = document.getElementById('loginPw');

let lockoutTick = null;

// ══════════════════════════════════════════════════════════════
//  COUNTDOWN ARC HELPER
//
//  Why this function exists:
//  If we set strokeDashoffset AND transition in the same JS tick,
//  the browser may batch them together — the arc starts from its
//  current value (138) and transitions TO 0 instantly, making it
//  look like it disappears. We need two separate frames:
//    Frame 1: set dashoffset = 0, transition = none  (instant paint at full)
//    Frame 2: enable transition                      (now ticks animate smoothly)
// ══════════════════════════════════════════════════════════════
function initArc(arcEl) {
    // Frame 1: paint the full arc instantly with no transition
    arcEl.style.transition      = 'none';
    arcEl.style.strokeDashoffset = '0';

    // Frame 2: after browser has painted, enable smooth transitions
    requestAnimationFrame(() => {
        requestAnimationFrame(() => {
            arcEl.style.transition = 'stroke-dashoffset 1s linear';
        });
    });
}

function tickArc(arcEl, remaining, total) {
    arcEl.style.strokeDashoffset = String(CIRCUMFERENCE * (1 - remaining / total));
}

// ══════════════════════════════════════════════════════════════
//  BOOT
// ══════════════════════════════════════════════════════════════
if (serverThrottled > 0) {
    // Panel already visible via Blade. Disable form and start countdown.
    localStorage.setItem(LOCKOUT_KEY, Date.now() + serverThrottled * 1000);
    disableForm();
    startServerCountdown(serverThrottled);
} else {
    const lockoutUntil = parseInt(localStorage.getItem(LOCKOUT_KEY) || '0');
    const now          = Date.now();
    if (lockoutUntil > now) {
        showClientLockout(Math.ceil((lockoutUntil - now) / 1000));
    } else if (lockoutUntil) {
        localStorage.removeItem(LOCKOUT_KEY);
    }
}

// ══════════════════════════════════════════════════════════════
//  AJAX SUBMIT
// ══════════════════════════════════════════════════════════════
loginForm.addEventListener('submit', async function (e) {
    e.preventDefault();

    const lockoutUntil = parseInt(localStorage.getItem(LOCKOUT_KEY) || '0');
    if (lockoutUntil > Date.now()) {
        showClientLockout(Math.ceil((lockoutUntil - Date.now()) / 1000));
        return;
    }

    hideError();
    loginBtn.classList.add('loading');
    btnText.textContent      = 'Signing in…';
    loginArrow.style.display = 'none';
    emailInput.classList.remove('input-error');
    pwInput.classList.remove('input-error');

    try {
        const res = await fetch(LOGIN_URL, {
            method: 'POST',
            headers: {
                'Content-Type'    : 'application/json',
                'Accept'          : 'application/json',
                'X-CSRF-TOKEN'    : CSRF_TOKEN,
                'X-Requested-With': 'XMLHttpRequest',
            },
            body: JSON.stringify({
                email   : emailInput.value,
                password: pwInput.value,
                remember: document.getElementById('rememberMe').checked ? 1 : 0,
            }),
        });

        const data = await res.json();

        if (data.success) {
            btnText.textContent       = 'Welcome!';
            loginBtn.style.background = '#1a8a1a';
            setTimeout(() => { window.location.href = data.redirect; }, 600);
            return;
        }

        resetBtn();

        if (data.throttle_seconds) {
            localStorage.setItem(LOCKOUT_KEY, Date.now() + data.throttle_seconds * 1000);
            hideError();
            showClientLockout(data.throttle_seconds);
        } else {
            showError(data.message);
            shakeInput(emailInput);
            shakeInput(pwInput);
            pwInput.value = '';
            pwInput.focus();
        }

    } catch (err) {
        resetBtn();
        showError('Something went wrong. Please try again.');
    }
});

// ══════════════════════════════════════════════════════════════
//  SERVER COUNTDOWN  (hard page load — panel pre-rendered by Blade)
// ══════════════════════════════════════════════════════════════
function startServerCountdown(secs) {
    const arc  = document.getElementById('serverArc');
    const num  = document.getElementById('serverCountdown');
    const wait = document.getElementById('serverWaitSec');
    if (!arc || !num) return;

    // Set the arc to full immediately, then enable transitions
    initArc(arc);

    let remaining = secs;

    // Use a stable 1-second interval tied to real wall-clock time
    // so drift doesn't cause the number to skip
    const startedAt = Date.now();

    const tick = setInterval(() => {
        remaining = secs - Math.round((Date.now() - startedAt) / 1000);

        if (remaining <= 0) {
            clearInterval(tick);
            tickArc(arc, 0, secs);
            if (num)  num.textContent  = '0';
            if (wait) wait.textContent = '0';
            setTimeout(() => {
                hidePanel('lockoutPanel');
                localStorage.removeItem(LOCKOUT_KEY);
                enableForm();
            }, 400);
            return;
        }

        if (num)  num.textContent  = remaining;
        if (wait) wait.textContent = remaining;
        tickArc(arc, remaining, secs);
    }, 1000);
}

// ══════════════════════════════════════════════════════════════
//  CLIENT LOCKOUT  (AJAX-triggered)
// ══════════════════════════════════════════════════════════════
function showClientLockout(seconds) {
    if (lockoutTick) return;

    disableForm();
    showPanel('clientLockout', true);

    countdownNum.textContent = seconds;
    waitSec.textContent      = seconds;

    // Init arc AFTER showPanel so the element is display:block and painted
    requestAnimationFrame(() => {
        initArc(countdownArc);
    });

    let remaining    = seconds;
    const startedAt  = Date.now();

    lockoutTick = setInterval(() => {
        remaining = seconds - Math.round((Date.now() - startedAt) / 1000);

        if (remaining <= 0) {
            clearInterval(lockoutTick);
            lockoutTick = null;
            tickArc(countdownArc, 0, seconds);
            countdownNum.textContent = '0';
            waitSec.textContent      = '0';
            setTimeout(() => {
                hidePanel('clientLockout');
                localStorage.removeItem(LOCKOUT_KEY);
                enableForm();
            }, 400);
            return;
        }

        countdownNum.textContent = remaining;
        waitSec.textContent      = remaining;
        tickArc(countdownArc, remaining, seconds);
    }, 1000);
}

// ══════════════════════════════════════════════════════════════
//  PANEL HELPERS
// ══════════════════════════════════════════════════════════════
function showPanel(id, shake = false) {
    const el = document.getElementById(id);
    if (!el) return;
    el.classList.remove('visible', 'shake');
    void el.offsetWidth;
    el.classList.add('visible');
    if (shake) {
        setTimeout(() => {
            el.classList.remove('shake');
            void el.offsetWidth;
            el.classList.add('shake');
        }, 420);
    }
}

function hidePanel(id) {
    const el = document.getElementById(id);
    if (!el) return;
    el.style.transition = 'opacity 0.35s ease, transform 0.35s ease';
    el.style.opacity    = '0';
    el.style.transform  = 'translateY(-6px) scale(0.97)';
    setTimeout(() => {
        el.classList.remove('visible', 'shake');
        el.style.cssText = '';
    }, 380);
}

// ══════════════════════════════════════════════════════════════
//  ERROR HELPERS
// ══════════════════════════════════════════════════════════════
function showError(msg) {
    authErrorMsg.textContent = msg;
    authError.classList.remove('visible');
    void authError.offsetWidth;
    authError.classList.add('visible');
}

function hideError() {
    authError.classList.remove('visible');
}

function shakeInput(el) {
    el.classList.remove('input-error');
    void el.offsetWidth;
    el.classList.add('input-error');
}

// ══════════════════════════════════════════════════════════════
//  FORM STATE
// ══════════════════════════════════════════════════════════════
function disableForm() {
    loginBtn.disabled         = true;
    loginBtn.style.opacity    = '0.5';
    loginBtn.style.cursor     = 'not-allowed';
    loginBtn.style.transform  = 'none';
    loginBtn.style.boxShadow  = 'none';
    pwInput.disabled          = true;
    emailInput.disabled       = true;
}

function enableForm() {
    loginBtn.disabled         = false;
    loginBtn.style.opacity    = '';
    loginBtn.style.cursor     = '';
    loginBtn.style.transform  = '';
    loginBtn.style.boxShadow  = '';
    pwInput.disabled          = false;
    emailInput.disabled       = false;
    resetBtn();
}

function resetBtn() {
    loginBtn.classList.remove('loading');
    loginBtn.style.background = '';
    btnText.textContent       = 'Sign In';
    loginArrow.style.display  = '';
}

function togglePw() {
    const isText = pwInput.type === 'text';
    pwInput.type = isText ? 'password' : 'text';
    document.getElementById('eyeIcon').className = isText ? 'fas fa-eye' : 'fas fa-eye-slash';
}
</script>
@endpush