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
        Schema::create('english_zone_materis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('administrator_id')->constrained('user_accounts');
            $table->string('materi_vocabulary');
            $table->string('materi_grammar');
            $table->string('materi_lesson_plan');
            $table->string('materi_reading');
            $table->string('materi_writing');
            $table->string('materi_listening');
            $table->string('materi_speaking');
            $table->string('materi_pembelajaran');
            $table->string('worksheet');
            $table->string('video_materi');
            $table->foreignId('level_id')->constrained('english_zone_levels');
            $table->foreignId('session_id')->constrained('english_zone_sessions');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('english_zone_materis');
    }
};