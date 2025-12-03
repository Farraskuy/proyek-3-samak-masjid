<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->boolean('has_registration_form')->default(false);
            $table->foreignId('registration_form_id')->nullable()->constrained('forms')->nullOnDelete();
            $table->boolean('has_closing_form')->default(false);
            $table->foreignId('closing_form_id')->nullable()->constrained('forms')->nullOnDelete();
            $table->boolean('has_pj')->default(false);
            $table->foreignId('pj_user_id')->nullable()->constrained('users')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropForeign(['registration_form_id']);
            $table->dropColumn('registration_form_id');
            $table->dropColumn('has_registration_form');

            $table->dropForeign(['closing_form_id']);
            $table->dropColumn('closing_form_id');
            $table->dropColumn('has_closing_form');

            $table->dropForeign(['pj_user_id']);
            $table->dropColumn('pj_user_id');
            $table->dropColumn('has_pj');
        });
    }
};
