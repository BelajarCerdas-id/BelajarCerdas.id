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
        Schema::create('soal_pembahasan_answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('user_accounts');
            $table->foreignId('subscription_id')->nullable()->constrained('feature_subscription_histories');
            $table->foreignId('question_id')->constrained('soal_pembahasan_questions');
            $table->string('user_answer_option');
            $table->integer('question_score')->nullable();
            $table->enum('status_answer', ['Draft', 'Saved']);
            $table->string('exam_answer_duration')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('soal_pembahasan_answers');
    }
};