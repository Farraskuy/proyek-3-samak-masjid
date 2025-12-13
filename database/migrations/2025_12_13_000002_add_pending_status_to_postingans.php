<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Add 'pending' status to the postingans status check constraint
     */
    public function up(): void
    {
        // Drop the old constraint
        DB::statement('ALTER TABLE postingans DROP CONSTRAINT IF EXISTS postingans_status_check');
        
        // Add new constraint with 'pending' included
        DB::statement("ALTER TABLE postingans ADD CONSTRAINT postingans_status_check CHECK (status IN ('published', 'arsip', 'revisi', 'draft', 'pending'))");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert to old constraint (without 'pending')
        DB::statement('ALTER TABLE postingans DROP CONSTRAINT IF EXISTS postingans_status_check');
        DB::statement("ALTER TABLE postingans ADD CONSTRAINT postingans_status_check CHECK (status IN ('published', 'arsip', 'revisi', 'draft'))");
    }
};
