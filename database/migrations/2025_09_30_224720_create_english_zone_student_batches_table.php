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
        Schema::create('english_zone_student_batches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('user_accounts');
            $table->foreignId('subscription_history_id')->constrained('feature_subscription_histories');
            $table->foreignId('level_id')->constrained('english_zone_levels');
            $table->foreignId('batch_schedule_id')->constrained('english_zone_batch_schedules');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('english_zone_student_batches');
    }
};