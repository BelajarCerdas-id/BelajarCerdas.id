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
        Schema::create('mentor_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mentor_id')->constrained('user_accounts')->onDelete('cascade');
            $table->integer('total_amount');
            $table->enum('status_payment', ['Unpaid', 'Paid'])->default('Unpaid');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mentor_payments');
    }
};