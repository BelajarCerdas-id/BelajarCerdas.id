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
        Schema::create('mentor_payment_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mentor_id')->constrained('user_accounts');
            $table->foreignId('payment_mentor_id')->constrained('mentor_payments');
            $table->foreignId('tanya_verification_id')->nullable()->constrained('tanya_verifications');
            $table->string('source_payment_mentor')->nullable();
            $table->integer('amount');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mentor_payment_details');
    }
};