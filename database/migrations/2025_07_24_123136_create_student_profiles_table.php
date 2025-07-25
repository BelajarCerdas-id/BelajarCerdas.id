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
        Schema::create('student_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('user_accounts')->onDelete('cascade');
            $table->string('nama_lengkap');
            $table->string('personal_email');
            $table->string('sekolah');
            $table->foreignId('fase_id')->constrained('fases');
            $table->foreignId('kelas_id')->constrained('kelas');
            $table->string('mentor_referral_code')->nullable();
            $table->string('mentor_referral_joined_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_profiles');
    }
};
