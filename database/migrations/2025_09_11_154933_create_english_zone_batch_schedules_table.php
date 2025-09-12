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
        Schema::create('english_zone_batch_schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('administrator_id')->constrained('user_accounts');
            $table->foreignId('batch_id')->constrained('english_zone_batches')->onDelete('cascade');
            $table->integer('batch_schedule_group');
            $table->integer('schedule_time_group');
            $table->string('day_of_week');
            $table->time('start_time');
            $table->time('end_time');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('english_zone_batch_schedules');
    }
};