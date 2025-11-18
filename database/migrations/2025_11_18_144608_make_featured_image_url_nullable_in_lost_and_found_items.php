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
        Schema::table('lost_and_found_items', function (Blueprint $table) {
            $table->string('featured_image_url', 255)->nullable()->change();
        });
    }

    public function down()
    {
        Schema::table('lost_and_found_items', function (Blueprint $table) {
            $table->string('featured_image_url', 255)->nullable(false)->change();
        });
    }
};
