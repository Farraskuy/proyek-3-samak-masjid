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
            $table->boolean('questionnaire_enabled')->default(true)->after('closing_form_id');
        });
        
        Schema::table('form_responses', function (Blueprint $table) {
            if (!Schema::hasColumn('form_responses', 'is_verified')) {
                $table->boolean('is_verified')->default(false)->after('user_agent');
                $table->timestamp('verified_at')->nullable()->after('is_verified');
                $table->foreignId('verified_by')->nullable()->constrained('users')->nullOnDelete()->after('verified_at');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn('questionnaire_enabled');
        });
        
        Schema::table('form_responses', function (Blueprint $table) {
            if (Schema::hasColumn('form_responses', 'is_verified')) {
                $table->dropForeign(['verified_by']);
                $table->dropColumn(['is_verified', 'verified_at', 'verified_by']);
            }
        });
    }
};
