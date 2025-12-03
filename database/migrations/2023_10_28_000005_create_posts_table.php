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
        Schema::create('postingans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users', 'id');
            $table->string('title', 255);
            $table->string('slug', 270)->unique();
            $table->text('keterangan')->nullable();
            $table->text('content');
            $table->string('featured_image_url', 255)->nullable();
            $table->enum('status', ['published', 'arsip', 'pending', 'revisi','draft']);
            $table->enum('kategori', ['Berita', 'Artikel', 'Tausiyah']);
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('Postingan');
    }
};
