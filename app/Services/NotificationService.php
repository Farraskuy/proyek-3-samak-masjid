<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use App\Mail\OtpMail;
use Illuminate\Support\Facades\Mail;
use App\Jobs\SendOtpEmailJob;

class NotificationService
{
    // Send email OTP (uses Mail)
    public static function sendEmailOtp($destination, $code)
    {
        try {
            // Dispatch a dedicated job which sends the mailable.
            // Using a job gives more control (backoff, retries, logging) than direct queueing.
            dispatch(new SendOtpEmailJob($destination, (string) $code));
            return true;
        } catch (\Exception $e) {
            Log::error('Failed to dispatch OTP email job: ' . $e->getMessage());
            return false;
        }
    }

    // Placeholder for SMS or WhatsApp sending
    public static function sendSms($phone, $message)
    {
        // Integrate with provider (Twilio, Nexmo, etc.) here.
        // For now, log the message and return true.
        Log::info("SMS to {$phone}: {$message}");
        return true;
    }

    public static function sendWhatsApp($phone, $message)
    {
        // Integrate with WhatsApp provider here (e.g., Twilio WhatsApp).
        Log::info("WhatsApp to {$phone}: {$message}");
        return true;
    }
}
