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
        Schema::create('consultations', function (Blueprint $table) {
            $table->id('consultation_id');
            $table->string('question_subject', 150);
            $table->text('question_text');
            $table->string('question_from', 100)->default('Hamba Allah');
            $table->text('answer_text')->nullable();
            $table->string('status', 20)->default('draft');
            $table->boolean('is_anonymous')->default(true);
            $table->foreignId('inputted_by_admin_id')->constrained('users', 'id');
            $table->foreignId('answered_by_ustadz_id')->nullable()->constrained('users', 'id');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('answered_at')->nullable();
            $table->timestamp('published_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('consultations');
    }
};
