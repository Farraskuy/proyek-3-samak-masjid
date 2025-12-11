<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\BankAccount;
use Illuminate\Support\Facades\DB;

class BankAccountSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('bank_accounts')->truncate();

        // 1. KAS - Default account for internal recording (not deletable)
        BankAccount::create([
            'bank_name' => 'Kas Masjid',
            'account_number' => null,
            'account_holder_name' => 'DKM Masjid',
            'logo_url' => null,
            'category' => 'zakat',
            'type' => 'kas',
            'is_deletable' => false,
            'is_active' => true,
        ]);

        // 2. Bank Zakat Accounts
        BankAccount::create([
            'bank_name' => 'Bank Syariah Indonesia (BSI)',
            'account_number' => '7123456789',
            'account_holder_name' => 'DKM Masjid Kampus - Zakat',
            'logo_url' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/a/a0/Bank_Syariah_Indonesia.svg/512px-Bank_Syariah_Indonesia.svg.png',
            'category' => 'zakat',
            'type' => 'bank_zakat',
            'is_deletable' => true,
            'is_active' => true,
        ]);

        BankAccount::create([
            'bank_name' => 'Bank Muamalat',
            'account_number' => '3210987654',
            'account_holder_name' => 'DKM Masjid Kampus - Zakat',
            'logo_url' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/9/9a/Bank_Muamalat_logo.svg/512px-Bank_Muamalat_logo.svg.png',
            'category' => 'zakat',
            'type' => 'bank_zakat',
            'is_deletable' => true,
            'is_active' => true,
        ]);

        // 3. Bank Infaq Accounts
        BankAccount::create([
            'bank_name' => 'BCA Syariah',
            'account_number' => '5678901234',
            'account_holder_name' => 'DKM Masjid Kampus - Infaq',
            'logo_url' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/5/5c/Bank_Central_Asia.svg/512px-Bank_Central_Asia.svg.png',
            'category' => 'infaq',
            'type' => 'bank_infaq',
            'is_deletable' => true,
            'is_active' => true,
        ]);

        BankAccount::create([
            'bank_name' => 'BNI Syariah',
            'account_number' => '8901234567',
            'account_holder_name' => 'DKM Masjid Kampus - Infaq',
            'logo_url' => 'https://upload.wikimedia.org/wikipedia/id/thumb/5/55/BNI_logo.svg/512px-BNI_logo.svg.png',
            'category' => 'infaq',
            'type' => 'bank_infaq',
            'is_deletable' => true,
            'is_active' => true,
        ]);
    }
}
