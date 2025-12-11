<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Add JSON columns to main table
        Schema::table('kotak_amal_collections', function (Blueprint $table) {
            $table->json('officers')->nullable()->after('status');
            $table->json('details')->nullable()->after('officers');
        });

        // Drop related tables
        Schema::dropIfExists('kotak_amal_details');
        Schema::dropIfExists('kotak_amal_officers');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Recreate officers table
        Schema::create('kotak_amal_officers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('collection_id')->constrained('kotak_amal_collections')->onDelete('cascade');
            $table->string('name');
            $table->string('phone')->nullable();
            $table->text('signature')->nullable();
            $table->timestamps();
        });

        // Recreate details table
        Schema::create('kotak_amal_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('collection_id')->constrained('kotak_amal_collections')->onDelete('cascade');
            $table->integer('nominal');
            $table->integer('quantity')->default(0);
            $table->decimal('subtotal', 15, 2)->default(0);
            $table->boolean('is_locked')->default(false);
            $table->timestamps();
        });

        // Remove JSON columns from main table
        Schema::table('kotak_amal_collections', function (Blueprint $table) {
            $table->dropColumn(['officers', 'details']);
        });
    }
};
