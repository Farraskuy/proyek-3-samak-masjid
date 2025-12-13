<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('website_information', function (Blueprint $table) {
            $table->json('zakat_settings')->nullable()->after('footer_social_links');
        });

        // Set default values
        \DB::table('website_information')->update([
            'zakat_settings' => json_encode([
                'harga_emas_per_gram' => 1300000,
                'harga_beras_per_kg' => 13500,
            ])
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('website_information', function (Blueprint $table) {
            $table->dropColumn('zakat_settings');
        });
    }
};
