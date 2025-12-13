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
        Schema::create('backup_schedules', function (Blueprint $table) {
            $table->id();
            $table->boolean('is_enabled')->default(false);
            $table->enum('frequency', ['daily', 'weekly', 'monthly', 'yearly'])->default('weekly');
            $table->json('tables')->nullable(); // Selected tables for backup
            $table->string('output_format')->default('single'); // 'single' = 1 SQL, 'zip' = multiple files zipped
            $table->timestamp('last_run_at')->nullable();
            $table->timestamp('next_run_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('backup_schedules');
    }
};
