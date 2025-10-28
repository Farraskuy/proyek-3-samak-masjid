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
        Schema::create('gallery_photos', function (Blueprint $table) {
            $table->id('photo_id');
            $table->foreignId('album_id')->constrained('gallery_albums', 'album_id');
            $table->string('image_url', 255);
            $table->string('caption', 255);
            $table->foreignId('uploaded_by')->constrained('users', 'user_id');
            $table->timestamp('uploaded_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gallery_photos');
    }
};
