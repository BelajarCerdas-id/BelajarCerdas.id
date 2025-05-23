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
        Schema::create('coin_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('user_accounts')->onDelete('cascade');
            $table->integer('jumlah_koin');
            $table->enum('tipe_koin', ['Masuk', 'Keluar']);
            $table->string('sumber_koin');
            $table->foreignId('tanya_id')->nullable()->constrained('tanyas')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coin_histories');
    }
};