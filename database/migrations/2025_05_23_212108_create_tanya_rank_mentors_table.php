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
        Schema::create('tanya_rank_mentors', function (Blueprint $table) {
            $table->id();
            $table->string('nama_rank');
            $table->integer('jumlah_soal_diterima');
            $table->integer('jumlah_soal_approved');
            $table->integer('harga_per_soal');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tanya_rank_mentors');
    }
};