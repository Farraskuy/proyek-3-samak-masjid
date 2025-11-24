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
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('notifications_email')->default(true)->after('password');
            $table->boolean('newsletter')->default(false)->after('notifications_email');
            $table->boolean('public_profile')->default(false)->after('newsletter');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['notifications_email', 'newsletter', 'public_profile']);
        });
    }
};
