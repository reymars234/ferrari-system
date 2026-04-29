<?php
namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Mail;
use App\Mail\OtpMail;

class OtpService
{
    public static function generate(User $user): string
    {
        $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        $user->update([
            'otp'            => bcrypt($otp),   // store hashed
            'otp_expires_at' => now()->addMinutes(10),
        ]);

        Mail::to($user->email)->send(new OtpMail($otp, $user->name));

        return $otp;
    }

    public static function verify(User $user, string $otp): bool
    {
        if (!$user->otp || !$user->otp_expires_at) {
            return false;
        }

        if (now()->gt($user->otp_expires_at)) {
            return false;  // expired
        }

        return password_verify($otp, $user->otp);
    }

    public static function clear(User $user): void
    {
        $user->update(['otp' => null, 'otp_expires_at' => null]);
    }
}