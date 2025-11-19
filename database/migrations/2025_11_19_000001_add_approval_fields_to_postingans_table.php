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
        Schema::table('postingans', function (Blueprint $table) {
            $table->enum('approval_status', ['pending', 'approved', 'rejected', 'revision'])->default('pending')->after('status');
            $table->text('approval_note')->nullable()->after('approval_status');
            $table->foreignId('approved_by')->nullable()->constrained('users', 'id')->after('approval_note');
            $table->timestamp('approved_at')->nullable()->after('approved_by');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('postingans', function (Blueprint $table) {
            if (Schema::hasColumn('postingans', 'approved_at')) {
                $table->dropColumn('approved_at');
            }
            if (Schema::hasColumn('postingans', 'approved_by')) {
                $table->dropForeign(['approved_by']);
                $table->dropColumn('approved_by');
            }
            if (Schema::hasColumn('postingans', 'approval_note')) {
                $table->dropColumn('approval_note');
            }
            if (Schema::hasColumn('postingans', 'approval_status')) {
                $table->dropColumn('approval_status');
            }
        });
    }
};
