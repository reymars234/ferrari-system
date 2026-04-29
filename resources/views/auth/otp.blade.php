@extends('layouts.app')
@section('title', 'Verify Email')
@push('styles')
<style>
    .auth-wrap { min-height:90vh; display:flex; align-items:center; justify-content:center; padding:40px 16px; }
    .auth-card { background:var(--dark2); border:1px solid #222; border-radius:10px; padding:44px; width:100%; max-width:420px; text-align:center; }
    .otp-icon { font-size:48px; color:var(--ferrari-red); margin-bottom:20px; }
    .auth-title { font-family:'Bebas Neue',sans-serif; font-size:28px; letter-spacing:3px; margin-bottom:8px; }
    .otp-input { text-align:center; font-size:28px; letter-spacing:12px; font-weight:700; }
</style>
@endpush
@section('content')
<div class="auth-wrap">
    <div class="auth-card">
        <div class="otp-icon"><i class="fas fa-envelope-open-text"></i></div>
        <div class="auth-title">VERIFY EMAIL</div>
        <p style="color:var(--gray); margin-bottom:28px; font-size:14px; line-height:1.7;">
            We sent a 6-digit code to <strong>{{ auth()->user()->email }}</strong>.<br>
            Enter it below. Expires in 10 minutes.
        </p>

        <form method="POST" action="{{ route('otp.verify.post') }}">
            @csrf
            <div class="form-group">
                <input type="text" name="otp" class="form-control otp-input" placeholder="• • • • • •" maxlength="6" inputmode="numeric" required autofocus>
                <div class="form-error">@error('otp'){{ $message }}@enderror</div>
            </div>
            <button type="submit" class="btn btn-red" style="width:100%;">Verify OTP</button>
        </form>

        <form method="POST" action="{{ route('otp.resend') }}" style="margin-top:16px;">
            @csrf
            <button type="submit" class="btn btn-gray" style="width:100%; font-size:12px;">Resend OTP</button>
        </form>
    </div>
</div>
@endsection