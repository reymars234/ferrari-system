<?php
namespace App\Helpers;

use Illuminate\Support\Facades\Http;

class RecaptchaHelper
{
    public static function verify(string $token): bool
    {
        if (empty($token)) {
            return false;
        }

        $response = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
            'secret'   => config('services.recaptcha.secret_key'),
            'response' => $token,
        ]);

        return $response->json('success') === true;
    }
}