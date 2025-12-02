<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ItemCategory;

class ItemCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Kendaraan', 'slug' => 'kendaraan'],
            ['name' => 'Elektronik', 'slug' => 'elektronik'],
            ['name' => 'Aksesoris', 'slug' => 'aksesoris'],
            ['name' => 'Dokumen', 'slug' => 'dokumen'],
            ['name' => 'Lain-lain', 'slug' => 'lain-lain'],
        ];

        foreach ($categories as $category) {
            ItemCategory::updateOrCreate(
                ['slug' => $category['slug']],
                ['name' => $category['name']]
            );
        }
    }
}
