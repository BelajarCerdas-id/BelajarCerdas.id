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
        Schema::create('model_datas', function (Blueprint $table) {
            $table->id();
            $table->string('nama_lengkap');
            $table->string('sekolah');
            $table->string('fase');
            $table->string('kelas');
            $table->string('email');
            $table->string('password');
            $table->integer('no_hp');
            $table->string('status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('model_datas');
    }
};
