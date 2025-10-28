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
        Schema::create('donation_confirmations', function (Blueprint $table) {
            $table->id('confirmation_id');
            $table->foreignId('user_id')->constrained('users', 'user_id');
            $table->decimal('amount', 15, 2);
            $table->date('transfer_date');
            $table->foreignId('destination_account_id')->constrained('bank_accounts', 'account_id');
            $table->string('source_bank', 50);
            $table->string('proof_image_url', 255);
            $table->string('notes', 255);
            $table->string('status', 20)->default('Pending');
            $table->foreignId('verified_by')->nullable()->constrained('users', 'user_id');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('verified_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('donation_confirmations');
    }
};
