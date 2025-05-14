<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('english_zone_soals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('user_accounts')->onDelete('cascade');
            $table->string('modul_soal');
            $table->string('jenjang_soal');
            $table->string('soal');
            $table->string('option_pilihan');
            $table->string('jawaban_pilihan');
            $table->string('tingkat_kesulitan');
            $table->string('jawaban_benar');
            $table->string('deskripsi_jawaban');
            $table->string('tipe_upload');
            $table->string('status_soal')->default('unpublish');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('english_zone_soals');
    }
};