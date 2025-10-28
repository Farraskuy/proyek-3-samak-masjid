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
        Schema::create('lost_and_found_items', function (Blueprint $table) {
            $table->id('item_id');
            $table->foreignId('inputted_by_admin_id')->constrained('users', 'user_id');
            $table->string('item_name', 100);
            $table->text('description');
            $table->string('location_found', 100);
            $table->string('featured_image_url', 255);
            $table->string('status', 30)->default('Tersedia');
            $table->timestamp('created_at')->useCurrent();
            $table->string('retrieved_by_name', 100)->nullable();
            $table->timestamp('retrieved_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lost_and_found_items');
    }
};
