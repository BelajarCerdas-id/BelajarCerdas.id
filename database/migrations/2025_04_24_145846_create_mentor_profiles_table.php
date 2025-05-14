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
        Schema::create('mentor_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('user_accounts')->onDelete('cascade');
            $table->string('nama_lengkap');
            $table->string('personal_email');
            $table->enum('status_pendidikan', ['D3', 'D4', 'S1']);
            $table->enum('bidang', ['pendidikan', 'non-pendidikan']);
            $table->string('jurusan');
            $table->string('tahun_lulus')->nullable();
            $table->string('sekolah_mengajar')->nullable();
            $table->string('kode_referral');
            $table->enum('status_mentor', ['Menunggu', 'Ditolak', 'Diterima'])->default('Menunggu');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mentor_profiles');
    }
};
