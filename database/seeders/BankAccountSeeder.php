<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\BankAccount;
use Illuminate\Support\Facades\DB;

class BankAccountSeeder extends Seeder
{
    
    public function run(): void
    {
        DB::table('bank_accounts')->truncate();

        BankAccount::create([
            'bank_name' => 'Bank Syariah Indonesia (BSI)',
            'account_number' => '7123456789',
            'account_holder_name' => 'DKM Masjid Kampus',
            'logo_url' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/a/a0/Bank_Syariah_Indonesia.svg/512px-Bank_Syariah_Indonesia.svg.png',
            'is_active' => true
        ]);

        // BankAccount::create([
        //     'bank_name' => '',
        //     'account_number' => '',
        //     'account_holder_name' => '',
        //     'logo_url' => '',
        //     'is_active' => true
        // ]);
    }
}
