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
        Schema::create('payouts', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->unsignedBigInteger('freelancer_id');
            $table->decimal('amount', 10, 2);
            $table->enum('status', ['pending', 'processed', 'failed']) -> default('pending');
<<<<<<< HEAD
            $table->timestamp('requested_at') -> nullable();
=======
            $table->timestamp('requested_at')->nullable();
>>>>>>> 87e7cb90781e557b2aa2c43cfa7eb9a46bb45507
            $table->timestamp('processed_at') -> nullable();

            $table->foreign('freelancer_id') -> references('id') -> on('users') -> onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payouts');
    }
};
