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
        Schema::create('visitasi_data', function (Blueprint $table) {
            $table->id();
            $table->string('provinsi');
            $table->string('kab_kota');
            $table->string('kecamatan');
            $table->string('jenjang_sekolah');
            $table->string('sekolah');
            $table->string('status_sekolah');
            $table->date('tanggal_mulai')->nullable();
            $table->date('tanggal_akhir')->nullable();
            $table->enum('status_kunjungan', ['Pending', 'Belum dikunjungi', 'Telah dikunjungi'])->default('Belum dikunjungi');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('visitasi_data');
    }
};