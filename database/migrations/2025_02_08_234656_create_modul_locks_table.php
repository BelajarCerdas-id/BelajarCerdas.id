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
        Schema::create('modul_locks', function (Blueprint $table) {
            $table->id();
            $table->string('nama_lengkap');
            $table->foreignId('module_id')->constrained()->onDelete('cascade'); // Relasi ke modules
            $table->boolean('is_completed')->default(false); // Apakah module sudah selesai?
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('modul_locks');
    }
};