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
        Schema::create('data_surat_pks', function (Blueprint $table) {
            $table->id();
            $table->string('provinsi');
            $table->string('kab_kota');
            $table->string('kecamatan');
            $table->string('jenjang_sekolah');
            $table->string('sekolah');
            $table->string('status_sekolah');
            $table->string('alamat_sekolah');
            $table->string('nama_kepsek');
            $table->string('nip_kepsek');
            $table->string('paket_kerjasama');
            $table->date('tanggal_mulai')->nullable();
            $table->date('tanggal_akhir')->nullable();
            $table->string('status_paket_kerjasama')->default('PKS tidak aktif');
            $table->enum('status_pks', ['Belum PKS', 'PKS'])->default('Belum PKS');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('data_surat_pks');
    }
};