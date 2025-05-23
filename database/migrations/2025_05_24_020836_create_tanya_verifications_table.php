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
        Schema::create('tanya_verifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mentor_id')->constrained('user_accounts')->onDelete('cascade');
            $table->foreignId('tanya_id')->constrained('tanyas')->onDelete('cascade');
            $table->integer('harga_soal');
            $table->foreignId('administrator_id')->nullable()->constrained('user_accounts')->onDelete('cascade');
            $table->enum('status_verifikasi', ['Ditolak', 'Menunggu', 'Diterima'])->default('Menunggu')->nullable();
            $table->text('alasan_verifikasi_ditolak')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tanya_verifications');
    }
};