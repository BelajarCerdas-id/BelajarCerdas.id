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
        Schema::create('keynotes', function (Blueprint $table) {
            $table->id();
            $table->string('nama_lengkap');
            $table->string('email');
            $table->string('sekolah');
            $table->string('fase');
            $table->string('kelas');
            $table->string('no_hp');
            $table->string('fase_catatan');
            $table->string('kelas_catatan');
            $table->string('mapel');
            $table->string('bab');
            $table->string('catatan')->nullable();
            $table->string('image_catatan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('keynotes');
    }
};
