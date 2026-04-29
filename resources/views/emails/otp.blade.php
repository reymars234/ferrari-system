<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: Arial, sans-serif; background: #111; color: #fff; margin: 0; padding: 40px; }
        .card { background: #1a1a1a; border: 1px solid #ff2800; border-radius: 8px; max-width: 480px; margin: auto; padding: 40px; text-align: center; }
        .logo { color: #ff2800; font-size: 28px; font-weight: bold; letter-spacing: 4px; margin-bottom: 24px; }
        .otp-code { font-size: 48px; font-weight: bold; color: #ff2800; letter-spacing: 12px; margin: 24px 0; }
        .note { color: #999; font-size: 14px; margin-top: 20px; }
    </style>
</head>
<body>
    <div class="card">
        <div class="logo">FERRARI SYSTEM</div>
        <p>Hello, <strong>{{ $userName }}</strong></p>
        <p>Your One-Time Password (OTP) for registration:</p>
        <div class="otp-code">{{ $otp }}</div>
        <p>This code expires in <strong>10 minutes</strong>.</p>
        <p class="note">If you did not request this, please ignore this email.</p>
    </div>
</body>
</html>