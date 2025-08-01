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
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->unsignedBigInteger('user_id');
            // $table->enum('plan_type', ['Basic', 'Pro', 'Premium']) -> default('basic'); --> possible future options
            $table->timestamp('start_date')->nullable();
            $table->timestamp('end_date') -> nullable();
            $table->boolean('is_active') -> default(true);
            $table->string('payment_method', 50);
            


            $table->foreign('user_id') -> references('id') -> on('users') -> onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscriptions');
    }
};
