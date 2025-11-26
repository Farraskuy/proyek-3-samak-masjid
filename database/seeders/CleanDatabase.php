<?php

namespace Database\Seeders;

use DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Schema;

class CleanDatabase extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // truncate
        Schema::disableForeignKeyConstraints();
        DB::statement('TRUNCATE TABLE users');
        DB::statement('TRUNCATE TABLE lost_and_founds');
        DB::statement('TRUNCATE TABLE postingans');
        DB::statement('TRUNCATE TABLE consultations');
        DB::statement('TRUNCATE TABLE static_pages');
        Schema::enableForeignKeyConstraints();
    }
}
