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
        Schema::create('english_zone_questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('administrator_id')->constrained('user_accounts');
            $table->text('questions');
            $table->string('options_key');
            $table->text('options_value');
            $table->string('answer_key');
            $table->enum('difficulty', ['Mudah', 'Sedang', 'Sukar']);
            $table->text('explanation');
            $table->string('level');
            $table->string('unit');
            $table->string('status_soal');
            $table->enum('status_bank_soal', ['Unpublish', 'Publish'])->default('Unpublish');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('english_zone_questions');
    }
};