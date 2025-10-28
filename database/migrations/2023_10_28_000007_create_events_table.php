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
        Schema::create('events', function (Blueprint $table) {
            $table->id('event_id');
            $table->string('event_name', 200);
            $table->foreignId('ustadz_user_id')->constrained('users', 'user_id');
            $table->string('theme', 255);
            $table->timestamp('start_time');
            $table->timestamp('end_time');
            $table->string('location', 100);
            $table->boolean('is_recurring')->default(false);
            $table->boolean('requires_registration')->default(false);
            $table->foreignId('created_by')->constrained('users', 'user_id');
            $table->timestamp('created_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
