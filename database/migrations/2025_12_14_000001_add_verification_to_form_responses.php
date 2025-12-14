<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Add verification fields to form_responses for PJ to verify registrants
     */
    public function up(): void
    {
        Schema::table('form_responses', function (Blueprint $table) {
            if (!Schema::hasColumn('form_responses', 'is_verified')) {
                $table->boolean('is_verified')->default(false)->after('user_agent');
            }
            if (!Schema::hasColumn('form_responses', 'verified_at')) {
                $table->timestamp('verified_at')->nullable()->after('is_verified');
            }
            if (!Schema::hasColumn('form_responses', 'verified_by')) {
                $table->unsignedBigInteger('verified_by')->nullable()->after('verified_at');
                
                // Add foreign key for verified_by
                $table->foreign('verified_by')->references('id')->on('users')->nullOnDelete();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('form_responses', function (Blueprint $table) {
            if (Schema::hasColumn('form_responses', 'verified_by')) {
                $table->dropForeign(['verified_by']);
                $table->dropColumn('verified_by');
            }
            if (Schema::hasColumn('form_responses', 'verified_at')) {
                $table->dropColumn('verified_at');
            }
            if (Schema::hasColumn('form_responses', 'is_verified')) {
                $table->dropColumn('is_verified');
            }
        });
    }
};
