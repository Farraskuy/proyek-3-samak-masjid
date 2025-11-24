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
            // Hapus ustadz_user_id
            if (Schema::hasColumn('events', 'ustadz_user_id')) {
                $table->dropForeign(['ustadz_user_id']);
                $table->dropColumn('ustadz_user_id');
            }

            // Tambah kolom is_have_tamu_undangan
            $table->boolean('is_have_tamu_undangan')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            // Rollback (kembalikan kolom lama)
            $table->foreignId('ustadz_user_id')->nullable()->constrained('users', 'id');
            $table->dropColumn('is_have_tamu_undangan');
        });
    }
};
