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
        Schema::create('surat_pks', function (Blueprint $table) {
            $table->id();
            $table->string('nama_lengkap');
            $table->string('status');
            $table->string('surat_pks');
            $table->string('tipe_surat_pks');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('surat_pks');
    }
};