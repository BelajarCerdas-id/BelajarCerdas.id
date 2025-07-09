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
        Schema::create('bab_feature_statuses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bab_id')->constrained('babs')->onDelete('cascade');
            $table->foreignId('feature_id')->constrained('features')->onDelete('cascade');
            $table->enum('status_bab', ['publish', 'unpublish'])->default('unpublish');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bab_feature_statuses');
    }
};
