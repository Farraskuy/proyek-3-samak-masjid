<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Infaq;
use App\Models\BankAccount;

class InfaqSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get infaq bank accounts to assign
        $infaqBanks = BankAccount::where('category', 'infaq')
            ->where('is_active', true)
            ->get();
        
        // If no infaq banks, get first available bank
        $defaultBank = $infaqBanks->first() ?? BankAccount::where('is_active', true)->first();
        
        $infaqPrograms = [
            [
                'name' => 'Pembangunan Masjid',
                'description' => 'Program pembangunan dan renovasi masjid untuk memperluas area ibadah dan fasilitas.',
                'poster_url' => null,
                'bank_account_id' => $defaultBank?->account_id,
                'is_active' => true,
            ],
            [
                'name' => 'Pendidikan Anak Yatim',
                'description' => 'Program bantuan pendidikan untuk anak-anak yatim di sekitar masjid.',
                'poster_url' => null,
                'bank_account_id' => $defaultBank?->account_id,
                'is_active' => true,
            ],
            [
                'name' => 'Santunan Duafa',
                'description' => 'Program santunan dan bantuan untuk kaum duafa dan tidak mampu.',
                'poster_url' => null,
                'bank_account_id' => $defaultBank?->account_id,
                'is_active' => true,
            ],
            [
                'name' => 'Operasional Masjid',
                'description' => 'Dana untuk kebutuhan operasional harian masjid seperti listrik, air, dan kebersihan.',
                'poster_url' => null,
                'bank_account_id' => $defaultBank?->account_id,
                'is_active' => true,
            ],
        ];

        foreach ($infaqPrograms as $program) {
            Infaq::updateOrCreate(
                ['name' => $program['name']],
                $program
            );
        }

        $this->command->info('✅ 4 program infaq berhasil di-seed!');
    }
}
