<?php

namespace App\Traits;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

trait VerifyCaptcha
{
    public function validateCaptcha($token, $ip)
    {
        try {
            $response = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
                'secret' => config('services.recaptcha.secret'),
                'response' => $token,
                'remoteip' => $ip
            ]);

            Log::info('reCAPTCHA Verification', [
                'ip' => $ip,
                'token_length' => strlen($token),
                'response' => $response->json()
            ]);

            return $response->json()['success'] ?? false;
        } catch (\Exception $e) {
            Log::error('reCAPTCHA error: ' . $e->getMessage());
            return false;
        }
    }
}
