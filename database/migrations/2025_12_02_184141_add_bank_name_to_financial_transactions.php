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
        Schema::table('financial_transactions', function (Blueprint $table) {
            $table->string('bank_name')->default('Bank Infaq')->after('type'); 
        });
    }

    public function down()
    {
        Schema::table('financial_transactions', function (Blueprint $table) {
            $table->dropColumn('bank_name');
        });
    }
};
