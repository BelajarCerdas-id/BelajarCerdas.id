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
            $table->string('nama_lengkap');
            $table->string('email');
            $table->string('status');
            $table->string('modul');
            $table->string('judul_modul');
            $table->string('materi_pdf');
            $table->string('judul_video');
            $table->string('link_video');
            $table->integer('modul_download')->nullable();
            $table->string('jenjang_murid');
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