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
            $table->string('video_materi');
            $table->foreignId('level_id')->constrained('english_zone_levels');
            $table->string('session');
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