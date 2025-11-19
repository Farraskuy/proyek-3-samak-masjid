<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Postingan;

class PostinganSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // create 40 sample postingans
        Postingan::factory()->count(40)->create();
    }
}
