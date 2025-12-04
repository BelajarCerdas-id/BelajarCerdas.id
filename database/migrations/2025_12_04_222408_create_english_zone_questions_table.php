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
            // foreign ID
            $table->foreignId('administrator_id')->constrained('user_accounts');
            $table->foreignId('level_id')->constrained('english_zone_levels');
            $table->foreignId('session_id')->nullable()->constrained('english_zone_sessions');
            $table->foreignId('passage_id')->nullable()->constrained('english_zone_passages');  

            $table->text('questions'); // teks soal
            $table->string('tipe_soal'); // TOEP, reading, writing, listening, speaking
            $table->string('question_format'); // MCQ, TFNG, YNNG, Sentence Completion, Summary Completion, short answer, dll

            // MCQ / TFNG / YNNG
            $table->string('options_key')->nullable();
            $table->text('options_value')->nullable();

            // kunci jawaban
            $table->string('answer_key')->nullable(); // "A" / "TRUE" / "NOT GIVEN" / keyword
            $table->longText('answer_text')->nullable(); // untuk summary/sentence completion (jawaban panjang / banyak jawaban)

            $table->text('explanation')->nullable();

            // Questions Status
            $table->enum('difficulty', ['Mudah', 'Sedang', 'Sukar']);
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