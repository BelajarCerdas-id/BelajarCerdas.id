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
        Schema::create('sub_babs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('user_accounts')->onDelete('cascade');
            $table->string('sub_bab');
            $table->string('kode');
            $table->foreignId('bab_id')->constrained('babs')->onDelete('cascade');
            $table->foreignId('kelas_id')->constrained('kelas')->onDelete('cascade');
            $table->foreignId('mapel_id')->constrained('mapels')->onDelete('cascade');
            $table->foreignId('fase_id')->constrained('fases')->onDelete('cascade');
            $table->foreignId('kurikulum_id')->constrained('kurikulums')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sub_babs');
    }
};
