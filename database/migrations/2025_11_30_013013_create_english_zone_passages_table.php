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
        Schema::create('english_zone_passages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('administrator_id')->constrained('user_accounts');
            $table->foreignId('level_id')->constrained('english_zone_levels');
            $table->text('passage_content')->nullable();
            $table->string('audio_file')->nullable();
            $table->string('audio_script')->nullable();
            $table->string('passage_type');
            $table->enum('passage_status', ['Unpublish', 'Publish'])->default('Unpublish');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('english_zone_passages');
    }
};