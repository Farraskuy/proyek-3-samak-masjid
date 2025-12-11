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
        Schema::create('kotak_amal_collections', function (Blueprint $table) {
            $table->id();
            $table->string('box_name')->comment('Identitas Kotak Amal');
            $table->date('collection_date');
            $table->decimal('total_amount', 15, 2)->default(0);
            $table->enum('status', ['draft', 'finalized'])->default('draft');
            $table->timestamps();
        });

        Schema::create('kotak_amal_officers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('collection_id')->constrained('kotak_amal_collections')->onDelete('cascade');
            $table->string('name');
            $table->string('phone')->nullable();
            $table->text('signature')->nullable(); // Base64 or path
            $table->timestamps();
        });

        Schema::create('kotak_amal_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('collection_id')->constrained('kotak_amal_collections')->onDelete('cascade');
            $table->integer('nominal'); // 100000, 50000
            $table->integer('quantity')->default(0);
            $table->decimal('subtotal', 15, 2)->default(0);
            $table->boolean('is_locked')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kotak_amal_details');
        Schema::dropIfExists('kotak_amal_officers');
        Schema::dropIfExists('kotak_amal_collections');
    }
};
