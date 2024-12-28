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
        Schema::create('cruds', function (Blueprint $table) {
            $table->id();
            $table->string('nama_lengkap')->nullable();
            $table->string('sekolah')->nullable();
            $table->string('fase')->nullable();
            $table->string('kelas')->nullable();
            $table->string('email')->nullable();
            $table->string('password')->nullable();
            $table->string('no_hp')->nullable();
            $table->string('status')->nullable();
            $table->string('kode_jenjang_murid')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cruds');
    }
};
