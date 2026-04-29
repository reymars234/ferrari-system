@extends('layouts.app')
@section('title', 'Forgot Password')
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
        font-size: 22px; color: var(--ferrari-red);
        margin-bottom: 24px;
        animation: pulse 2s ease infinite;
    }
    @keyframes pulse {
        0%,100% { box-shadow: 0 0 0 0 rgba(220,0,0,0.2); }
        50%      { box-shadow: 0 0 0 10px rgba(220,0,0,0); }
    }

    .auth-title { font-family: 'Bebas Neue', sans-serif; font-size: 30px; letter-spacing: 3px; margin-bottom: 8px; }
    .auth-sub   { color: var(--gray); font-size: 14px; margin-bottom: 32px; line-height: 1.7; }
    .auth-link  { color: var(--ferrari-red); transition: opacity 0.2s; }
    .auth-link:hover { opacity: 0.8; text-decoration: underline; }

    .input-wrap { position: relative; }
    .input-wrap .form-control { padding-left: 44px; }
    .input-wrap .input-icon {
        position: absolute; left: 14px; top: 50%; transform: translateY(-50%);
        color: #444; font-size: 14px; transition: color 0.2s; pointer-events: none;
    }
    .input-wrap .form-control:focus ~ .input-icon { color: var(--ferrari-red); }

    .btn-submit {
        width: 100%; padding: 14px; font-size: 14px; letter-spacing: 2px;
        background: var(--ferrari-red); color: #fff; border: none;
        border-radius: 4px; cursor: pointer; font-weight: 700; text-transform: uppercase;
        transition: background 0.2s, transform 0.15s, box-shadow 0.2s;
        display: flex; align-items: center; justify-content: center; gap: 10px;
        font-family: 'Barlow', sans-serif;
    }
    .btn-submit:hover  { background: #b00000; transform: translateY(-2px); box-shadow: 0 8px 24px rgba(220,0,0,0.3); }
    .btn-submit:active { transform: translateY(0); }
    .btn-submit.loading { pointer-events: none; opacity: 0.8; }
    .btn-submit .spinner {
        width: 16px; height: 16px; border: 2px solid rgba(255,255,255,0.3);
        border-top-color: #fff; border-radius: 50%;
        animation: spin 0.7s linear infinite; display: none;
    }
    .btn-submit.loading .spinner { display: block; }
    .btn-submit.loading .btn-text { opacity: 0.6; }
    @keyframes spin { to { transform: rotate(360deg); } }

    .success-state {
        text-align: center; display: none;
        animation: fadeIn 0.5s ease both;
    }
    @keyframes fadeIn { from { opacity: 0; transform: scale(0.95); } to { opacity: 1; transform: scale(1); } }
    .success-icon {
        width: 64px; height: 64px; border-radius: 50%;
        background: rgba(29,185,84,0.1); border: 1px solid rgba(29,185,84,0.4);
        display: flex; align-items: center; justify-content: center;
        font-size: 26px; color: #1db954; margin: 0 auto 20px;
    }
    .back-link {
        display: inline-flex; align-items: center; gap: 6px;
        color: var(--gray); font-size: 13px; margin-top: 20px;
        transition: color 0.2s;
    }
    .back-link:hover { color: var(--ferrari-red); }
    .back-link i { transition: transform 0.2s; }
    .back-link:hover i { transform: translateX(-3px); }
</style>
@endpush

@section('content')
<div class="auth-wrap">
    <div class="auth-card">

        {{-- FORM STATE --}}
        <div id="formState">
            <div class="auth-icon"><i class="fas fa-key"></i></div>
            <div class="auth-title">FORGOT PASSWORD</div>
            <div class="auth-sub">
                Enter your registered email and we'll send you a link to reset your password.
            </div>

            <form method="POST" action="{{ route('password.email') }}" id="forgotForm">
                @csrf
                <div class="form-group">
                    <label>Email Address</label>
                    <div class="input-wrap">
                        <input
                            type="email" name="email" id="emailInput"
                            class="form-control"
                            placeholder="you@gmail.com"
                            value="{{ old('email') }}"
                            required autofocus
                        >
                        <i class="fas fa-envelope input-icon"></i>
                    </div>
                    <div class="form-error">@error('email'){{ $message }}@enderror</div>
                </div>

                <button type="submit" class="btn-submit" id="submitBtn">
                    <div class="spinner"></div>
                    <span class="btn-text">Send Reset Link</span>
                </button>
            </form>

            <div style="text-align:center; margin-top:24px;">
                <a href="{{ route('login') }}" class="back-link">
                    <i class="fas fa-arrow-left"></i> Back to Login
                </a>
            </div>
        </div>

        {{-- SUCCESS STATE --}}
        <div class="success-state" id="successState">
            <div class="success-icon"><i class="fas fa-paper-plane"></i></div>
            <div class="auth-title" style="text-align:center; font-size:24px;">CHECK YOUR EMAIL</div>
            <p style="color:var(--gray); font-size:14px; margin-top:12px; line-height:1.8; text-align:center;">
                We sent a password reset link to<br>
                <strong style="color:var(--light);" id="sentEmail"></strong>
            </p>
            <p style="color:#444; font-size:12px; margin-top:16px; text-align:center;">
                Didn't receive it? Check your spam folder or
                <a href="#" class="auth-link" onclick="resetForm(); return false;">try again</a>.
            </p>
            <div style="text-align:center;">
                <a href="{{ route('login') }}" class="back-link">
                    <i class="fas fa-arrow-left"></i> Back to Login
                </a>
            </div>
        </div>

    </div>
</div>
@endsection

@push('scripts')
<script>
const form      = document.getElementById('forgotForm');
const submitBtn = document.getElementById('submitBtn');

form.addEventListener('submit', () => {
    submitBtn.classList.add('loading');
    submitBtn.querySelector('.btn-text').textContent = 'Sending…';
});

function showSuccess() {
    const email = document.getElementById('emailInput')?.value
                || '{{ old("email") }}';
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
@endpush