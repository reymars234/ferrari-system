@extends('layouts.app')
@section('title', 'Register — ROSSO CORSA')
@push('styles')
<style>
    html, body { overflow: hidden !important; height: 100% !important; }
    .page-content { overflow: hidden !important; }

    /* Fixed full-screen, perfectly centered, zero scroll */
    .register-page {
        position: fixed;
        top: var(--nav-h); left: 0; right: 0; bottom: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 10;
        padding: 0 16px;
        overflow: hidden;
    }

    .register-bg-video {
        position: fixed; inset: 0; width: 100%; height: 100%;
        object-fit: cover; z-index: 0; opacity: 0.40; pointer-events: none;
    }
    .register-bg-overlay {
        position: fixed; inset: 0; z-index: 1;
        background: rgba(13,13,13,0.65); pointer-events: none;
    }

    /* ── Card — compact, static, never scrolls ── */
    .register-card {
        position: relative; z-index: 2;
        background: rgba(14,14,14,0.92);
        border: 1px solid rgba(220,0,0,0.18);
        border-radius: 14px;
        padding: 22px 36px 18px;
        width: 100%; max-width: 470px;
        backdrop-filter: blur(32px);
        box-shadow: 0 24px 64px rgba(0,0,0,0.55), 0 0 0 1px rgba(220,0,0,0.07);
        animation: cardIn 0.55s cubic-bezier(.25,.8,.25,1) both;
        overflow: hidden;
    }
    @keyframes cardIn {
        from { opacity: 0; transform: translateY(28px) scale(0.97); }
        to   { opacity: 1; transform: translateY(0) scale(1); }
    }

    /* ── Logo row ── */
    .register-logo-wrap {
        display: flex; align-items: center; gap: 13px;
        margin-bottom: 10px;
        animation: logoSlide 0.6s cubic-bezier(.25,.8,.25,1) 0.1s both;
    }
    @keyframes logoSlide {
        from { opacity: 0; transform: translateX(-14px); }
        to   { opacity: 1; transform: translateX(0); }
    }
    .register-logo-img {
        height: 40px; width: auto;
        filter: drop-shadow(0 0 6px rgba(220,0,0,0.3));
        transition: filter 0.3s, transform 0.3s;
    }
    .register-logo-img:hover {
        filter: drop-shadow(0 0 14px rgba(220,0,0,0.6));
        transform: scale(1.06) rotate(-2deg);
    }
    .register-logo-icon {
        width: 46px; height: 46px; border-radius: 50%;
        background: rgba(220,0,0,0.1); border: 1px solid rgba(220,0,0,0.3);
        display: flex; align-items: center; justify-content: center;
    }
    .register-logo-icon i { color: var(--ferrari-red); font-size: 20px; }
    .register-logo-text { display: flex; flex-direction: column; line-height: 1; }
    .register-logo-name {
        font-family: 'Bebas Neue', sans-serif;
        font-size: 20px; letter-spacing: 4px; color: var(--ferrari-red);
    }
    .register-logo-sub { font-size: 9.5px; letter-spacing: 3px; color: #555; text-transform: uppercase; margin-top: 3px; }

    /* ── Headings ── */
    .auth-title {
        font-family: 'Bebas Neue', sans-serif; font-size: 22px; letter-spacing: 3.5px; margin-bottom: 2px; color: var(--light);
        animation: fadeUp 0.5s ease 0.2s both;
    }
    .auth-sub { color: var(--gray); font-size: 12px; margin-bottom: 10px; animation: fadeUp 0.5s ease 0.25s both; }
    .auth-link { color: var(--ferrari-red); transition: opacity 0.2s; }
    .auth-link:hover { opacity: 0.75; text-decoration: underline; }
    @keyframes fadeUp {
        from { opacity: 0; transform: translateY(10px); }
        to   { opacity: 1; transform: translateY(0); }
    }

    /* ── Steps ── */
    .steps { display: flex; align-items: center; margin-bottom: 10px; animation: fadeUp 0.5s ease 0.3s both; }
    .step  { flex: none; text-align: center; }
    .step-dot {
        width: 26px; height: 26px; border-radius: 50%;
        background: var(--dark3); border: 1px solid #333;
        display: flex; align-items: center; justify-content: center;
        font-size: 10px; color: var(--gray); margin: 0 auto 4px;
        transition: all 0.4s ease; font-weight: 700;
    }
    .step.active .step-dot { background: var(--ferrari-red); border-color: var(--ferrari-red); color: #fff; box-shadow: 0 0 12px rgba(220,0,0,0.3); }
    .step.done   .step-dot { background: #1db954; border-color: #1db954; color: #fff; }
    .step-label  { font-size: 9px; letter-spacing: 1.2px; color: var(--gray); text-transform: uppercase; }
    .step.active .step-label { color: var(--light); }
    .step-line   { flex: 1; height: 1px; background: #222; margin-bottom: 14px; transition: background 0.4s; }

    /* ── Form ── */
    .form-group { margin-bottom: 7px; }
    .form-group label { font-size: 10px; margin-bottom: 3px; letter-spacing: 1px; font-weight: 600; text-transform: uppercase; color: var(--gray); display: block; }

    /* Input with icon */
    .input-wrap { position: relative; }
    .input-wrap .form-control {
        padding-left: 38px; font-size: 13px;
        padding-top: 8px; padding-bottom: 8px;
        background: rgba(255,255,255,0.04);
        border: 1px solid rgba(255,255,255,0.09);
        border-radius: 7px;
        color: var(--light);
    }
    .input-wrap .form-control:focus {
        border-color: rgba(220,0,0,0.5);
        background: rgba(255,255,255,0.05);
    }
    .input-wrap .input-icon {
        position: absolute; left: 13px; top: 50%; transform: translateY(-50%);
        color: #383838; font-size: 12px; transition: color 0.25s; pointer-events: none;
    }
    .input-wrap .form-control:focus ~ .input-icon { color: var(--ferrari-red); }
    .toggle-pw {
        position: absolute; right: 12px; top: 50%; transform: translateY(-50%);
        color: #383838; cursor: pointer; font-size: 12px; transition: color 0.2s;
    }
    .input-wrap .form-control.has-toggle { padding-right: 38px; }
    .toggle-pw:hover { color: var(--ferrari-red); }
    .form-control.valid   { border-color: rgba(29,185,84,0.55) !important; }
    .form-control.invalid { border-color: rgba(220,0,0,0.5) !important; }
    .form-error { color: var(--ferrari-red); font-size: 10px; margin-top: 2px; min-height: 0; }

    /* Strength meter UI hidden — logic still runs in JS */
    .pw-strength-wrap { display: none !important; }

    /* ── Password warning banner ── */
    .pw-warning {
        display: flex;
        align-items: flex-start;
        gap: 10px;
        background: rgba(220, 0, 0, 0.10);
        border: 1px solid rgba(220, 0, 0, 0.40);
        border-radius: 8px;
        padding: 10px 14px;
        margin-bottom: 10px;
        animation: warnIn 0.3s cubic-bezier(.25,.8,.25,1) both;
        overflow: hidden;
    }
    @keyframes warnIn {
        from { opacity: 0; transform: translateY(-8px); max-height: 0; }
        to   { opacity: 1; transform: translateY(0);    max-height: 200px; }
    }
    .pw-warning-icon {
        color: var(--ferrari-red);
        font-size: 14px;
        flex-shrink: 0;
        margin-top: 1px;
    }
    .pw-warning-body { flex: 1; }
    .pw-warning-title {
        font-size: 11px; font-weight: 700; letter-spacing: 1px;
        text-transform: uppercase; color: var(--ferrari-red); margin-bottom: 5px;
    }
    .pw-warning-rules {
        display: grid; grid-template-columns: 1fr 1fr; gap: 2px 10px;
    }
    .pw-warning-rule {
        display: flex; align-items: center; gap: 5px;
        font-size: 10px;
    }
    .pw-warning-rule i { font-size: 9px; flex-shrink: 0; }
    .pw-warning-rule.ok  { color: #1db954; }
    .pw-warning-rule.ok i { color: #1db954; }
    .pw-warning-rule.fail { color: #ff6b6b; }
    .pw-warning-rule.fail i { color: #ff6b6b; }
    .pw-warning-close {
        color: rgba(220,0,0,0.5); font-size: 12px; cursor: pointer;
        flex-shrink: 0; margin-top: 1px; transition: color 0.2s;
        background: none; border: none; padding: 0;
    }
    .pw-warning-close:hover { color: var(--ferrari-red); }

    /* ── reCAPTCHA ── */
    .recaptcha-wrap {
        margin: 5px 0 5px;
        transform: scale(0.78); transform-origin: left;
    }

    /* ── Submit ── */
    .btn-register {
        width: 100%; padding: 10px; font-size: 12px; letter-spacing: 2.5px;
        background: var(--ferrari-red); color: #fff; border: none;
        border-radius: 5px; cursor: pointer; font-weight: 700; text-transform: uppercase;
        font-family: 'Barlow', sans-serif;
        display: flex; align-items: center; justify-content: center; gap: 8px;
        transition: background 0.2s, transform 0.15s, box-shadow 0.2s;
        position: relative; overflow: hidden;
        animation: fadeUp 0.5s ease 0.4s both;
    }
    .btn-register::after {
        content: ''; position: absolute; inset: 0;
        background: linear-gradient(120deg,transparent 30%,rgba(255,255,255,0.1) 50%,transparent 70%);
        transform: translateX(-100%); transition: transform 0.5s ease;
    }
    .btn-register:hover::after { transform: translateX(100%); }
    .btn-register:hover  { background: #b00000; transform: translateY(-2px); box-shadow: 0 8px 22px rgba(220,0,0,0.28); }
    .btn-register:active { transform: translateY(0); }
    .btn-register.loading { pointer-events: none; opacity: 0.8; }
    .btn-register .spinner {
        width: 13px; height: 13px;
        border: 2px solid rgba(255,255,255,0.3); border-top-color: #fff;
        border-radius: 50%; animation: spin 0.7s linear infinite; display: none;
    }
    .btn-register.loading .spinner { display: block; }
    @keyframes spin { to { transform: rotate(360deg); } }
    @keyframes pwShake {
        0%,100% { transform: translateX(0); }
        20%      { transform: translateX(-6px); }
        40%      { transform: translateX(6px); }
        60%      { transform: translateX(-4px); }
        80%      { transform: translateX(4px); }
    }
    #pwInput { animation: none; }

    .or-divider {
        display: flex; align-items: center; gap: 10px;
        color: #2a2a2a; font-size: 10px; letter-spacing: 2px; margin: 6px 0 4px;
    }
    .or-divider::before, .or-divider::after { content:''; flex:1; height:1px; background:#1a1a1a; }

    .register-footer { text-align: center; font-size: 10px; color: #383838; }

    /* confirm match msg */
    #confirmMsg { font-size: 10px; margin-top: 3px; min-height: 10px; }

    @media(max-width:480px) {
        .register-card { padding: 22px 18px 18px; }
        .pw-rules { grid-template-columns: 1fr; }
    }
</style>
@endpush

@section('content')

<video class="register-bg-video" autoplay muted loop playsinline>
    <source src="{{ asset('videos/register-bg.mp4') }}" type="video/mp4">
</video>
<div class="register-bg-overlay"></div>

<div class="register-page">
    <div class="register-card">

        {{-- Logo --}}
        <div class="register-logo-wrap">
            @if(file_exists(public_path('images/logo.png')))
                <img src="{{ asset('images/logo.png') }}" alt="Logo" class="register-logo-img">
            @else
                <div class="register-logo-icon"><i class="fas fa-horse"></i></div>
            @endif
            <div class="register-logo-text">
                <span class="register-logo-name">VELOCE VANTAGE</span>
                <span class="register-logo-sub">Official System</span>
            </div>
        </div>

        <div class="auth-title">CREATE ACCOUNT</div>
        <div class="auth-sub">
            Already have an account? <a href="{{ route('login') }}" class="auth-link">Sign in</a>
        </div>

        {{-- Steps --}}
        <div class="steps">
            <div class="step active" id="step1">
                <div class="step-dot">1</div>
                <div class="step-label">Details</div>
            </div>
            <div class="step-line" id="line1"></div>
            <div class="step" id="step2">
                <div class="step-dot">2</div>
                <div class="step-label">Security</div>
            </div>
            <div class="step-line" id="line2"></div>
            <div class="step" id="step3">
                <div class="step-dot">3</div>
                <div class="step-label">Verify</div>
            </div>
        </div>

        <form method="POST" action="{{ route('register.post') }}" id="registerForm">
            @csrf

            {{-- Full Name --}}
            <div class="form-group">
                <label>Full Name *</label>
                <div class="input-wrap">
                    <input type="text" name="name" id="nameInput"
                        class="form-control {{ $errors->has('name') ? 'invalid' : '' }}"
                        placeholder="e.g. Juan Dela Cruz"
                        value="{{ old('name') }}"
                        oninput="this.value=this.value.replace(/[0-9]/g,''); validateField(this,this.value.trim().length>1)"
                        required>
                    <i class="fas fa-user input-icon"></i>
                </div>
                <div class="form-error">@error('name'){{ $message }}@enderror</div>
            </div>

            {{-- Email --}}
            <div class="form-group">
                <label>Email Address *</label>
                <div class="input-wrap">
                    <input type="email" name="email" id="emailInput"
                        class="form-control {{ $errors->has('email') ? 'invalid' : '' }}"
                        placeholder="you@gmail.com"
                        value="{{ old('email') }}"
                        oninput="validateField(this,this.value.includes('@')&&this.value.includes('.'))"
                        required>
                    <i class="fas fa-envelope input-icon"></i>
                </div>
                <div class="form-error">@error('email'){{ $message }}@enderror</div>
            </div>

            {{-- Contact --}}
            <div class="form-group">
                <label>Contact Number *</label>
                <div class="input-wrap">
                    <input type="text" name="contact_number" id="contactInput"
                        class="form-control {{ $errors->has('contact_number') ? 'invalid' : '' }}"
                        placeholder="e.g. 09171234567"
                        value="{{ old('contact_number') }}"
                        oninput="this.value=this.value.replace(/[^0-9]/g,''); validateField(this,this.value.length>=7)"
                        maxlength="15" required>
                    <i class="fas fa-phone input-icon"></i>
                </div>
                <div class="form-error">@error('contact_number'){{ $message }}@enderror</div>
            </div>

            {{-- Password --}}
            <div class="form-group">
                <label>Password *</label>
                <div class="input-wrap">
                    <input type="password" name="password" id="pwInput"
                        class="form-control has-toggle {{ $errors->has('password') ? 'invalid' : '' }}"
                        placeholder="Min. 8 characters"
                        oninput="onPasswordInput()" required>
                    <i class="fas fa-lock input-icon"></i>
                    <span class="toggle-pw" onclick="togglePw('pwInput','eyeIcon1')">
                        <i class="fas fa-eye" id="eyeIcon1"></i>
                    </span>
                </div>
                <div class="form-error">@error('password'){{ $message }}@enderror</div>

                {{-- Strength meter — hidden until typing --}}
                <div class="pw-strength-wrap" id="pwStrengthWrap">
                    <div class="pw-label" id="pwLabel" style="color:#444">—</div>
                    <div class="pw-bar">
                        <div class="pw-segment" id="seg0"></div>
                        <div class="pw-segment" id="seg1"></div>
                        <div class="pw-segment" id="seg2"></div>
                        <div class="pw-segment" id="seg3"></div>
                        <div class="pw-segment" id="seg4"></div>
                    </div>
                    <div class="pw-rules">
                        <div class="pw-rule" id="rule-len"><i class="fas fa-circle"></i> 8+ characters</div>
                        <div class="pw-rule" id="rule-upper"><i class="fas fa-circle"></i> Uppercase (A–Z)</div>
                        <div class="pw-rule" id="rule-lower"><i class="fas fa-circle"></i> Lowercase (a–z)</div>
                        <div class="pw-rule" id="rule-num"><i class="fas fa-circle"></i> Number (0–9)</div>
                        <div class="pw-rule" id="rule-sym"><i class="fas fa-circle"></i> Symbol (!@#…)</div>
                        <div class="pw-rule" id="rule-no-space"><i class="fas fa-circle"></i> No spaces</div>
                    </div>
                </div>
            </div>

            {{-- Confirm Password --}}
            <div class="form-group">
                <label>Confirm Password *</label>
                <div class="input-wrap">
                    <input type="password" name="password_confirmation" id="pwConfirm"
                        class="form-control has-toggle"
                        placeholder="Repeat your password"
                        oninput="checkConfirm()" required>
                    <i class="fas fa-lock input-icon"></i>
                    <span class="toggle-pw" onclick="togglePw('pwConfirm','eyeIcon2')">
                        <i class="fas fa-eye" id="eyeIcon2"></i>
                    </span>
                </div>
                <div id="confirmMsg"></div>
            </div>

            {{-- reCAPTCHA --}}
            <div class="recaptcha-wrap">
                <div class="g-recaptcha" data-sitekey="{{ config('services.recaptcha.site_key') }}"></div>
                <div class="form-error">@error('g-recaptcha-response'){{ $message }}@enderror</div>
            </div>

            {{-- Password warning (shown on failed submit) --}}
            <div class="pw-warning" id="pwWarning" style="display:none">
                <i class="fas fa-shield-alt pw-warning-icon"></i>
                <div class="pw-warning-body">
                    <div class="pw-warning-title">Password too weak</div>
                    <div class="pw-warning-rules" id="pwWarningRules">
                        <div class="pw-warning-rule" id="wr-len"><i class="fas fa-circle"></i> 8+ characters</div>
                        <div class="pw-warning-rule" id="wr-upper"><i class="fas fa-circle"></i> Uppercase (A–Z)</div>
                        <div class="pw-warning-rule" id="wr-lower"><i class="fas fa-circle"></i> Lowercase (a–z)</div>
                        <div class="pw-warning-rule" id="wr-num"><i class="fas fa-circle"></i> Number (0–9)</div>
                        <div class="pw-warning-rule" id="wr-sym"><i class="fas fa-circle"></i> Symbol (!@#…)</div>
                        <div class="pw-warning-rule" id="wr-space"><i class="fas fa-circle"></i> No spaces</div>
                    </div>
                </div>
                <button type="button" class="pw-warning-close" onclick="hidePwWarning()" aria-label="Dismiss">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <button type="submit" class="btn-register" id="submitBtn">
                <div class="spinner"></div>
                <span class="btn-text">Create My Account</span>
                <i class="fas fa-arrow-right" id="btnArrow"></i>
            </button>
        </form>

        <div class="or-divider">or</div>
        <div class="register-footer">
            By registering you agree to our
            <a href="#" class="auth-link">Terms</a> &amp;
            <a href="#" class="auth-link">Privacy Policy</a>.
        </div>

    </div>
</div>
@endsection

@push('scripts')
<script src="https://www.google.com/recaptcha/api.js" async defer></script>
<script>
const rules = {
    len:     pw => pw.length >= 8,
    upper:   pw => /[A-Z]/.test(pw),
    lower:   pw => /[a-z]/.test(pw),
    num:     pw => /[0-9]/.test(pw),
    sym:     pw => /[^A-Za-z0-9]/.test(pw),
    noSpace: pw => !/\s/.test(pw) && pw.length > 0,
};

const strengthLevels = [
    { score: 1, label: 'Very Weak',   color: '#ff4444' },
    { score: 2, label: 'Weak',        color: '#ff8c00' },
    { score: 3, label: 'Fair',        color: '#f5c518' },
    { score: 4, label: 'Strong',      color: '#7ec8e3' },
    { score: 5, label: 'Very Strong', color: '#1db954' },
];

const segments = [0,1,2,3,4].map(i => document.getElementById('seg'+i));
const pwLabel  = document.getElementById('pwLabel');
const pwWrap   = document.getElementById('pwStrengthWrap');
const ruleEls  = {
    len:     document.getElementById('rule-len'),
    upper:   document.getElementById('rule-upper'),
    lower:   document.getElementById('rule-lower'),
    num:     document.getElementById('rule-num'),
    sym:     document.getElementById('rule-sym'),
    noSpace: document.getElementById('rule-no-space'),
};

function onPasswordInput() {
    const pw = document.getElementById('pwInput').value;
    checkStep2();
    checkConfirm();

    if (pw.length === 0) {
        // Hide strength meter with animation
        pwWrap.classList.remove('visible');
        return;
    }

    // Show strength meter
    pwWrap.classList.add('visible');

    const results = {};
    let score = 0;
    for (const [key, fn] of Object.entries(rules)) {
        results[key] = fn(pw);
        if (results[key]) score++;
    }
    if (!results.noSpace) score = Math.max(0, score - 1);

    for (const [key, el] of Object.entries(ruleEls)) {
        const passed = results[key];
        el.className = 'pw-rule ' + (passed ? 'met' : 'fail');
        el.querySelector('i').className = passed ? 'fas fa-check-circle' : 'fas fa-times-circle';
    }

    const capped = Math.min(score, 5);
    segments.forEach((seg, i) => {
        seg.className = 'pw-segment';
        if (i < capped) seg.classList.add(`active-${capped}`);
    });

    const level = strengthLevels[capped - 1] || strengthLevels[0];
    pwLabel.textContent = capped > 0 ? level.label : '';
    pwLabel.style.color = capped > 0 ? level.color : '#444';

    const pwInput = document.getElementById('pwInput');
    pwInput.classList.remove('valid','invalid');
    if (pw.length > 0) {
        pwInput.classList.add(capped >= 4 && results.noSpace ? 'valid' : 'invalid');
    }
}

function checkConfirm() {
    const pw      = document.getElementById('pwInput').value;
    const confirm = document.getElementById('pwConfirm').value;
    const msg     = document.getElementById('confirmMsg');
    const el      = document.getElementById('pwConfirm');

    if (!confirm) { msg.textContent = ''; el.classList.remove('valid','invalid'); return; }

    if (pw === confirm) {
        msg.style.color = '#1db954';
        msg.textContent = '✓ Passwords match';
        el.classList.add('valid'); el.classList.remove('invalid');
    } else {
        msg.style.color = '#ff4444';
        msg.textContent = '✗ Passwords do not match';
        el.classList.add('invalid'); el.classList.remove('valid');
    }
}

function togglePw(inputId, iconId) {
    const input = document.getElementById(inputId);
    const isText = input.type === 'text';
    input.type = isText ? 'password' : 'text';
    document.getElementById(iconId).className = isText ? 'fas fa-eye' : 'fas fa-eye-slash';
}

function validateField(el, condition) {
    el.classList.toggle('valid',   condition);
    el.classList.toggle('invalid', !condition && el.value.length > 0);
}

// Steps
const stepEls = ['step1','step2','step3'].map(id => document.getElementById(id));
const lineEls = ['line1','line2'].map(id => document.getElementById(id));

function setStep(n) {
    stepEls.forEach((s, i) => {
        s.classList.remove('active','done');
        if (i < n) s.classList.add('done');
        else if (i === n) s.classList.add('active');
    });
    lineEls.forEach((l, i) => {
        l.style.background = i < n ? 'var(--ferrari-red)' : '#222';
    });
}

function checkStep1() {
    return document.getElementById('nameInput').value.trim().length > 1 &&
           document.getElementById('emailInput').value.includes('@') &&
           document.getElementById('contactInput').value.length >= 7;
}

function checkStep2() {
    const pw = document.getElementById('pwInput').value;
    const score = Object.values(rules).filter(fn => fn(pw)).length;
    if (checkStep1() && score >= 4 && rules.noSpace(pw)) setStep(2);
    else if (checkStep1()) setStep(1);
    else setStep(0);
}

['nameInput','emailInput','contactInput'].forEach(id => {
    document.getElementById(id).addEventListener('input', () => {
        if (checkStep1()) setStep(1); else setStep(0);
    });
});

// ── Password warning banner ──────────────────────────────────
const warnRuleMap = {
    len:     'wr-len',
    upper:   'wr-upper',
    lower:   'wr-lower',
    num:     'wr-num',
    sym:     'wr-sym',
    noSpace: 'wr-space',
};

function showPwWarning(pw) {
    const banner = document.getElementById('pwWarning');
    banner.style.display = 'flex';
    // Trigger re-animation
    banner.style.animation = 'none';
    banner.offsetHeight; // reflow
    banner.style.animation = '';

    for (const [key, elId] of Object.entries(warnRuleMap)) {
        const el = document.getElementById(elId);
        const passed = rules[key](pw);
        el.className = 'pw-warning-rule ' + (passed ? 'ok' : 'fail');
        el.querySelector('i').className = passed ? 'fas fa-check-circle' : 'fas fa-times-circle';
    }
}

function hidePwWarning() {
    document.getElementById('pwWarning').style.display = 'none';
}

// Hide warning when user starts fixing password
document.getElementById('pwInput').addEventListener('input', function() {
    const pw = this.value;
    const warningVisible = document.getElementById('pwWarning').style.display !== 'none';
    if (warningVisible) {
        // Update rules live while warning is open
        showPwWarning(pw);
        // Auto-hide if all required rules pass
        const required = ['len','upper','lower','num','noSpace'];
        if (required.every(k => rules[k](pw))) hidePwWarning();
    }
});

document.getElementById('registerForm').addEventListener('submit', function(e) {
    const pw      = document.getElementById('pwInput').value;
    const confirm = document.getElementById('pwConfirm').value;
    const required = ['len','upper','lower','num','noSpace'];
    const allPass  = required.every(k => rules[k](pw));

    if (!allPass) {
        e.preventDefault();
        showPwWarning(pw);
        document.getElementById('pwInput').focus();
        // Shake the password field
        const pwEl = document.getElementById('pwInput');
        pwEl.style.animation = 'none';
        pwEl.offsetHeight;
        pwEl.style.animation = 'pwShake 0.4s ease';
        return;
    }

    hidePwWarning();

    if (pw !== confirm) {
        e.preventDefault();
        checkConfirm();
        document.getElementById('pwConfirm').focus();
        return;
    }

    const btn = document.getElementById('submitBtn');
    btn.classList.add('loading');
    btn.querySelector('.btn-text').textContent = 'Creating account…';
    document.getElementById('btnArrow').style.display = 'none';
    setStep(2);
});
</script>
@endpush