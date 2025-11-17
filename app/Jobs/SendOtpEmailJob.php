<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use App\Mail\OtpMail;

class SendOtpEmailJob implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    public $destination;
    public $code;

    public function __construct(string $destination, string $code)
    {
        $this->destination = $destination;
        $this->code = $code;
    }

    public function handle()
    {
        Mail::to($this->destination)->send(new OtpMail($this->code, $this->destination));
    }
}
