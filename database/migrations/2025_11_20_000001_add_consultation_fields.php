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
        Schema::table('consultations', function (Blueprint $table) {
            // Add new columns for enhanced consultation management
            $table->text('rejection_reason')->nullable()->after('answer_text');
            $table->text('conclusion')->nullable()->after('rejection_reason');
            $table->timestamp('closed_at')->nullable()->after('published_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('consultations', function (Blueprint $table) {
            $table->dropColumn(['rejection_reason', 'conclusion', 'closed_at']);
        });
    }
};
