<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OtpMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $code;
    public $destination;

    public function __construct($code, $destination)
    {
        $this->code = $code;
        $this->destination = $destination;
    }

    public function build()
    {
        return $this->subject('Kode Verifikasi Samak Masjid')
                    ->view('emails.otp')
                    ->with(['code' => $this->code, 'destination' => $this->destination]);
    }
}
