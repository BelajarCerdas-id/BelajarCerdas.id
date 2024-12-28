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
        Schema::create('english_zones', function (Blueprint $table) {
            $table->id();
            $table->string('nama_lengkap');
            $table->string('sekolah');
            $table->string('email');
            $table->string('status');
            $table->string('kode_jenjang_murid')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('english_zones');
    }
};
