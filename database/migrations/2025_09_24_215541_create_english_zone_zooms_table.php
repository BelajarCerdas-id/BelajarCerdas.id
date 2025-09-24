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
        Schema::create('english_zone_zooms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('administrator_id')->constrained('user_accounts');
            $table->foreignId('batch_schedule_id')->constrained('english_zone_batch_schedules');
            $table->foreignId('mentor_id')->constrained('user_accounts');
            $table->foreignId('level_id')->constrained('english_zone_levels');
            $table->foreignId('unit_id')->constrained('english_zone_units');
            $table->string('session');
            $table->string('link_zoom');
            $table->string('meeting_id');
            $table->string('zoom_passcode');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('english_zone_zooms');
    }
};