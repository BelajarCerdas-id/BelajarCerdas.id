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
        Schema::create('english_zone_soals', function (Blueprint $table) {
            $table->id();
            $table->string('nama_lengkap');
            $table->string('modul');
            $table->string('jenjang');
            $table->string('status');
            $table->string('soal');
            $table->string('pilihan_A');
            $table->string('bobot_A');
            $table->string('pilihan_B');
            $table->string('bobot_B');
            $table->string('pilihan_C');
            $table->string('bobot_C');
            $table->string('pilihan_D');
            $table->string('bobot_D');
            $table->string('pilihan_E');
            $table->string('bobot_E');
            $table->string('tingkat_kesulitan');
            $table->string('jawaban');
            $table->string('deskripsi_jawaban');
            $table->string('tipe_upload');
            $table->string('status_soal')->default('unpublish');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('english_zone_soals');
    }
};