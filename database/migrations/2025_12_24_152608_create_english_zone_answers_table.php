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
        Schema::create('english_zone_answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('user_accounts');
            $table->foreignId('subscription_history_id')->nullable()->constrained('feature_subscription_histories');
            $table->foreignId('level_id')->nullable()->constrained('english_zone_levels');
            $table->foreignId('passage_id')->nullable()->constrained('english_zone_passages');
            $table->foreignId('question_id')->nullable()->constrained('english_zone_questions');
            $table->string('user_answer_option')->nullable();
            $table->longText('user_answer_text')->nullable();
            $table->string('user_answer_audio')->nullable();
            $table->integer('question_score')->nullable();
            $table->string('exam_answer_duration')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('english_zone_answers');
    }
};