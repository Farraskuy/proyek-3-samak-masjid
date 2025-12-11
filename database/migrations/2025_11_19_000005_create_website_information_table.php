<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWebsiteInformationTable extends Migration
{
    public function up()
    {
        Schema::create('website_information', function (Blueprint $table) {
            $table->id();
            $table->string('title')->unique();
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->longText('content')->nullable();
            $table->string('featured_image_url')->nullable();

            // Footer Information
            $table->text('footer_address')->nullable();
            $table->string('footer_phone')->nullable();
            $table->string('footer_email')->nullable();
            $table->json('footer_social_links')->nullable();

            $table->foreignId('updated_by_admin')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('website_information');
    }
}
