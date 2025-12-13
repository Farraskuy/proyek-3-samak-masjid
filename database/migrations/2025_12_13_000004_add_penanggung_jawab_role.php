<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Add Penanggung Jawab role if not exists
     */
    public function up(): void
    {
        // Check if role already exists
        $exists = DB::table('roles')->where('name', 'Penanggung Jawab')->exists();
        
        if (!$exists) {
            DB::table('roles')->insert([
                'name' => 'Penanggung Jawab',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('roles')->where('name', 'Penanggung Jawab')->delete();
    }
};
