<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bank_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('account_holder_name');
            $table->string('bank_name');
            $table->string('account_number');
            $table->string('account_type', 50)->nullable();
            $table->string('branch_code', 50)->nullable();
            $table->string('swift_code', 50)->nullable();
            $table->timestamps();

            $table->unique('user_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bank_details');
    }
};
