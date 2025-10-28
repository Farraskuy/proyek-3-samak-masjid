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
        Schema::create('posts', function (Blueprint $table) {
            $table->id('post_id');
            $table->foreignId('user_id')->constrained('users', 'user_id');
            $table->string('title', 255);
            $table->string('slug', 270)->unique();
            $table->text('content');
            $table->string('featured_image_url', 255);
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('published_at')->nullable();
            $table->string('status', 20)->default('published');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
