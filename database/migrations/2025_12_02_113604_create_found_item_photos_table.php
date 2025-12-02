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
        Schema::create('found_item_photos', function (Blueprint $table) {
            $table->id('photo_id');
            $table->foreignId('found_item_id')->constrained('lost_and_found_items', 'item_id')->onDelete('cascade');
            $table->string('image_url');
            $table->string('caption')->nullable();
            $table->foreignId('uploaded_by_admin_id')->constrained('users');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('found_item_photos');
    }
};
