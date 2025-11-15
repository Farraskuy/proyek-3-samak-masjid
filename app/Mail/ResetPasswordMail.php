<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ResetPasswordMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $token;
    public $email;

    public function __construct($token, $email)
    {
        $this->token = $token;
        $this->email = $email;
    }

    public function build()
    {
        $resetUrl = url(route('password.reset', ['token' => $this->token, 'email' => $this->email], false));
        return $this->subject('Reset Password Akun Anda')
            ->view('emails.reset-password')
            ->with([
                'resetUrl' => $resetUrl,
            ]);
    }
}
