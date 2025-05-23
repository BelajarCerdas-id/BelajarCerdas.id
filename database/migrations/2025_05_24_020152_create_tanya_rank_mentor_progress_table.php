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
        Schema::create('tanya_rank_mentor_progress', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mentor_id')->constrained('user_accounts')->onDelete('cascade');
            $table->foreignId('rank_id')->nullable()->constrained('tanya_rank_mentors')->onDelete('cascade');
            $table->integer('jumlah_soal_diterima')->nullable();
            $table->integer('jumlah_soal_ditolak')->nullable();
            $table->integer('jumlah_soal_approved')->nullable();
            $table->integer('jumlah_soal_rejected')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tanya_rank_mentor_progress');
    }
};