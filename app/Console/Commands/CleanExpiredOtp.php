<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Otp;

class CleanExpiredOtp extends Command
{
    protected $signature = 'otp:clean';
    protected $description = 'Hapus OTP yang sudah kadaluarsa';

    public function handle()
    {
        $deleted = Otp::where('expires_at', '<', now())->delete();

        $this->info("OTP expired dibersihkan: {$deleted} data.");
    }
}
