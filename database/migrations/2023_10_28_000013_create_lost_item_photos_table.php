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
        Schema::create('lost_item_photos', function (Blueprint $table) {
            $table->id('photo_id');
            $table->foreignId('item_id')->constrained('lost_and_found_items', 'item_id');
            $table->string('image_url', 255);
            $table->string('caption', 255)->nullable();
            $table->foreignId('uploaded_by_admin_id')->constrained('users', 'id');
            $table->timestamp('created_at')->useCurrent();
            $table->index('item_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lost_item_photos');
    }
};
