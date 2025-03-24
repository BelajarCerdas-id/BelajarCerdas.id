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
        Schema::create('bulk_upload_templates', function (Blueprint $table) {
            $table->id();
            $table->string('nama_lengkap');
            $table->string('status');
            $table->string('nama_file');
            $table->string('jenis_file');
            $table->string('status_template');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bulk_upload_templates');
    }
};