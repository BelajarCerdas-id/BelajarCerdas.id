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
        Schema::create('tanyas', function (Blueprint $table) {
            $table->id();
            $table->string('nama_lengkap');
            $table->string('email');
            $table->string('sekolah');
            $table->string('fase');
            $table->string('kelas');
            $table->string('mapel');
            $table->string('bab');
            $table->string('pertanyaan');
            $table->string('image_tanya')->nullable();
            $table->string('no_hp');
            $table->string('mentor')->nullable();
            $table->string('id_mentor')->nullable();
            $table->string('email_mentor')->nullable();
            $table->string('asal_mengajar')->nullable();
            $table->string('fase_mentor')->nullable();
            $table->string('kelas_mentor')->nullable();
            $table->string('jawaban')->nullable();
            $table->string('image_jawab')->nullable();
            $table->string('status')->nullable();
            $table->string('alasan_ditolak')->nullable();
            $table->enum('status_soal', ['Belum Dibaca', 'Telah Dibaca'])->default('Belum Dibaca');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tanyas');
    }
};