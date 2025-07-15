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
            $table->integer('freelancer_id');
            $table->decimal('amount', 10, 2);
            $table->enum('status', ['pending', 'processed', 'failed']);
            $table->timestamp('requested_at');
            $table->timestamp('processed_at') -> nullable();
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
