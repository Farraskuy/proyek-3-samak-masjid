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
        Schema::table('events', function (Blueprint $table) {
            // Control whether registration form is open/closed
            $table->boolean('registration_enabled')->default(true)->after('registration_form_id');
            // Manual event start flag (PJ can start event early)
            $table->boolean('event_started')->default(false)->after('questionnaire_enabled');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn(['registration_enabled', 'event_started']);
        });
    }
};
