<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('lost_item_photos', function (Blueprint $table) {
            $table->id('photo_id');
            $table->foreignId('lost_item_id')->constrained('lost_items')->onDelete('cascade');
            $table->string('image_url');
            $table->string('caption')->nullable();
            $table->foreignId('uploaded_by_admin_id')->constrained('users');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('lost_item_photos');
    }
};
