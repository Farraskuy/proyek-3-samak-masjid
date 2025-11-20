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
        Schema::create('consultation_messages', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('consultation_id');
            $table->unsignedBigInteger('user_id');
            $table->longText('message');
            $table->string('message_type')->default('text'); // text, file, image
            $table->string('attachment_url')->nullable();
            $table->boolean('is_read')->default(false);
            $table->timestamp('read_at')->nullable();
            $table->timestamps();

            $table->foreign('consultation_id')->references('id')->on('consultations')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            $table->index('consultation_id');
            $table->index('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('consultation_messages');
    }
};
