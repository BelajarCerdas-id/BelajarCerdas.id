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
        Schema::create('english_zone_batches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('administrator_id')->constrained('user_accounts');
            $table->string('batch_name');
            $table->string('start_day');
            $table->string('start_month');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('english_zone_batches');
    }
};