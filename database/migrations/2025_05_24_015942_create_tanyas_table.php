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
            $table->foreignId('user_id')->constrained('user_accounts')->onDelete('cascade');
            $table->foreignId('fase_id')->constrained('fases')->onDelete('cascade');
            $table->foreignId('kelas_id')->constrained('kelas')->onDelete('cascade');
            $table->foreignId('mapel_id')->constrained('mapels')->onDelete('cascade');
            $table->foreignId('bab_id')->constrained('babs')->onDelete('cascade');
            $table->integer('harga_koin');
            $table->text('pertanyaan');
            $table->string('image_tanya')->nullable();
            $table->boolean('is_being_viewed')->default(false);
            $table->foreignId('viewed_by')->nullable()->constrained('user_accounts')->onDelete('cascade');
            $table->enum('status_soal_student', ['Belum Dibaca', 'Telah Dibaca'])->default('Belum Dibaca');
            $table->foreignId('mentor_id')->nullable()->constrained('user_accounts')->onDelete('cascade');
            $table->text('jawaban')->nullable();
            $table->string('image_jawab')->nullable();
            $table->enum('status_soal', ['Ditolak', 'Diterima'])->nullable();
            $table->string('alasan_ditolak')->nullable();
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