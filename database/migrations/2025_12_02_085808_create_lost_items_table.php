<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('lost_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reported_by_admin_id')->constrained('users');
            $table->foreignId('category_id')->constrained('item_categories');
            $table->string('item_name');
            $table->text('description');
            $table->string('location_lost')->nullable();
            $table->date('lost_at');
            $table->date('expiry_date');
            $table->enum('status', ['aktif', 'kadaluarsa'])->default('aktif');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lost_items');
    }
};
