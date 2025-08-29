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
        Schema::create('job_modifiers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('job_id')->onDelete('cascade');
            $table->boolean('is_physical')->default(false);
            $table->enum('urgency_level', ['low', 'medium', 'high'])->default('medium');
            $table->timestamps();

            $table->foreign('job_id')->references('id')->on('jobs')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_modifiers');
    }
};
