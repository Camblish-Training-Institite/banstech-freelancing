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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->integer('contract_id');
            $table->decimal('amount', 10, 2);
            $table->enum('payment_gateway', ['stipe', 'paypal', 'bank_transfer', 'ozow', 'yoko']);
            $table->string('gateway_transaction_id', 255) -> nullable();
            $table->enum('status', ['pending', 'completed', 'failed', 'refunded']);

            $table->foreign('contract_id') -> references('id') -> on('contracts') -> onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
