<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Add event_id to form_responses to track which event the response is for
     */
    public function up(): void
    {
        Schema::table('form_responses', function (Blueprint $table) {
            $table->unsignedBigInteger('event_id')->nullable()->after('form_id');
            
            // Add foreign key if events table exists
            if (Schema::hasTable('events')) {
                $table->foreign('event_id')->references('event_id')->on('events')->nullOnDelete();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('form_responses', function (Blueprint $table) {
            $table->dropForeign(['event_id']);
            $table->dropColumn('event_id');
        });
    }
};
