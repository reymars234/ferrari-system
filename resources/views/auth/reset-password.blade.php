@extends('layouts.app')
@section('title', 'Reset Password')
@push('styles')
<style>
    .auth-wrap {
        min-height: 90vh; display: flex; align-items: center;
        justify-content: center; padding: 40px 16px;
    }
    .auth-card {
        background: var(--dark2); border: 1px solid #222;
        border-radius: 12px; padding: 48px 44px; width: 100%; max-width: 440px;
        animation: slideUp 0.6s cubic-bezier(.25,.8,.25,1) both;
        box-shadow: 0 32px 80px rgba(0,0,0,0.5);
    }
    @keyframes slideUp {
        from { opacity: 0; transform: translateY(40px); }
        to   { opacity: 1; transform: translateY(0); }
    }
    .auth-icon {
        width: 56px; height: 56px; border-radius: 50%;
        background: rgba(220,0,0,0.1); border: 1px solid rgba(220,0,0,0.3);
        display: flex; align-items: center; justify-content: center;
        font-size: 22px; color: var(--ferrari-red); margin-bottom: 24px;
    }
    .auth-title { font-family: 'Bebas Neue', sans-serif; font-size: 30px; letter-spacing: 3px; margin-bottom: 8px; }
    .auth-sub   { color: var(--gray); font-size: 14px; margin-bottom: 32px; line-height: 1.7; }

    .input-wrap { position: relative; }
    .input-wrap .form-control { padding-left: 44px; padding-right: 44px; }
    .input-wrap .input-icon {
        position: absolute; left: 14px; top: 50%; transform: translateY(-50%);
        color: #444; font-size: 14px; transition: color 0.2s; pointer-events: none;
    }
    .input-wrap .form-control:focus ~ .input-icon { color: var(--ferrari-red); }
    .toggle-pw {
        position: absolute; right: 14px; top: 50%; transform: translateY(-50%);
        color: #444; cursor: pointer; font-size: 14px; transition: color 0.2s;
    }
    .toggle-pw:hover { color: var(--ferrari-red); }

    /* Password strength bar */
    .strength-bar { height: 3px; border-radius: 2px; background: #1e1e1e; margin-top: 8px; overflow: hidden; }
    .strength-fill { height: 100%; width: 0; border-radius: 2px; transition: width 0.4s ease, background 0.4s ease; }
    .strength-label { font-size: 11px; color: var(--gray); margin-top: 4px; transition: color 0.3s; }

    .btn-submit {
        width: 100%; padding: 14px; font-size: 14px; letter-spacing: 2px;
        background: var(--ferrari-red); color: #fff; border: none;
        border-radius: 4px; cursor: pointer; font-weight: 700; text-transform: uppercase;
        transition: background 0.2s, transform 0.15s, box-shadow 0.2s;
        font-family: 'Barlow', sans-serif;
    }
    .btn-submit:hover  { background: #b00000; transform: translateY(-2px); box-shadow: 0 8px 24px rgba(220,0,0,0.3); }
    .btn-submit:active { transform: translateY(0); }

    .back-link {
        display: inline-flex; align-items: center; gap: 6px;
        color: var(--gray); font-size: 13px; margin-top: 20px;
        transition: color 0.2s;
    }
    .back-link:hover { color: var(--ferrari-red); }
    .back-link:hover i { transform: translateX(-3px); }
    .back-link i { transition: transform 0.2s; }
</style>
@endpush

@section('content')
<div class="auth-wrap">
    <div class="auth-card">
        <div class="auth-icon"><i class="fas fa-lock-open"></i></div>
        <div class="auth-title">RESET PASSWORD</div>
        <div class="auth-sub">Create a strong new password for your account.</div>

        {{-- Flash errors --}}
        @if(session('error'))
            <div style="background:rgba(220,0,0,.07);border:1px solid rgba(220,0,0,.3);color:#ff6b6b;
                        padding:10px 14px;border-radius:6px;font-size:12px;margin-bottom:16px;">
                {{ session('error') }}
            </div>
        @endif

        <form method="POST" action="{{ route('password.store') }}">
            @csrf
            {{-- Token from the signed URL --}}
            <input type="hidden" name="token" value="{{ $token }}">

            <div class="form-group">
                <label>Email Address</label>
                <div class="input-wrap">
                    <input type="email" name="email" class="form-control"
                       value="{{ old('email', $email ?? '') }}" required readonly
                        style="color:var(--gray);">
                    <i class="fas fa-envelope input-icon"></i>
                </div>
                <div class="form-error">@error('email'){{ $message }}@enderror</div>
            </div>

            <div class="form-group">
                <label>New Password</label>
                <div class="input-wrap">
                    <input type="password" name="password" id="newPw" class="form-control"
                        placeholder="Min. 8 characters" required>
                    <i class="fas fa-lock input-icon"></i>
                    <span class="toggle-pw" onclick="togglePw('newPw', this)">
                        <i class="fas fa-eye"></i>
                    </span>
                </div>
                <div class="strength-bar"><div class="strength-fill" id="strengthFill"></div></div>
                <div class="strength-label" id="strengthLabel">Enter a password</div>
                <div class="form-error">@error('password'){{ $message }}@enderror</div>
            </div>

            <div class="form-group">
                <label>Confirm New Password</label>
                <div class="input-wrap">
                    <input type="password" name="password_confirmation" id="confirmPw"
                        class="form-control" placeholder="Repeat password" required>
                    <i class="fas fa-lock input-icon"></i>
                    <span class="toggle-pw" onclick="togglePw('confirmPw', this)">
                        <i class="fas fa-eye"></i>
                    </span>
                </div>
            </div>

            <button type="submit" class="btn-submit">Reset Password</button>
        </form>

        <div style="text-align:center;">
            <a href="{{ route('login') }}" class="back-link">
                <i class="fas fa-arrow-left"></i> Back to Login
            </a>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function togglePw(id, icon) {
    const input = document.getElementById(id);
    const isText = input.type === 'text';
    input.type = isText ? 'password' : 'text';
    icon.querySelector('i').className = isText ? 'fas fa-eye' : 'fas fa-eye-slash';
}

const pwInput      = document.getElementById('newPw');
const strengthFill = document.getElementById('strengthFill');
const strengthLabel= document.getElementById('strengthLabel');

const levels = [
    { label: 'Too weak',   color: '#ff2222', width: '20%' },
    { label: 'Weak',       color: '#ff6600', width: '40%' },
    { label: 'Fair',       color: '#f5c518', width: '60%' },
    { label: 'Strong',     color: '#1db954', width: '80%' },
    { label: 'Very strong',color: '#1db954', width: '100%'},
];

pwInput.addEventListener('input', () => {
    const v = pwInput.value;
    let score = 0;
    if (v.length >= 8)           score++;
    if (/[A-Z]/.test(v))         score++;
    if (/[0-9]/.test(v))         score++;
    if (/[^A-Za-z0-9]/.test(v))  score++;
    if (v.length >= 12)          score++;

    const lvl = v.length === 0 ? null : levels[Math.min(score, 4)];
    strengthFill.style.width      = lvl ? lvl.width : '0';
    strengthFill.style.background = lvl ? lvl.color : 'transparent';
    strengthLabel.textContent     = lvl ? lvl.label : 'Enter a password';
    strengthLabel.style.color     = lvl ? lvl.color : 'var(--gray)';
});
</script>
@endpush