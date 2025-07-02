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
        Schema::create('soal_pembahasan_questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('administrator_id')->constrained('user_accounts');
            $table->foreignId('kurikulum_id')->constrained('kurikulums');
            $table->foreignId('kelas_id')->constrained('kelas');
            $table->foreignId('mapel_id')->constrained('mapels');
            $table->foreignId('bab_id')->constrained('babs');
            $table->foreignId('sub_bab_id')->constrained('sub_babs');
            $table->text('questions');
            $table->string('options_key');
            $table->text('options_value');
            $table->string('answer_key');
            $table->integer('skilltag');
            $table->enum('difficulty', ['Mudah', 'Sedang', 'Sulit']);
            $table->text('explanation');
            $table->enum('status_bank_soal', ['Unpublish', 'Publish'])->default('Unpublish');
            $table->enum('status_soal', ['Free', 'Premium']);
            $table->enum('tipe_soal', ['Latihan', 'Ujian']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('soal_pembahasan_questions');
    }
};
