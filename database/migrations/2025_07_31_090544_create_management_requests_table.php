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
        Schema::create('management_requests', function (Blueprint $table) {
            $table->id();
            $table->unsignedBiginteger('client_id');
            $table->unsignedBiginteger('contract_id');
            $table->foreignId('project_manager_id')->nullable()->constrained('users')->onDelete('set null');
            $table->enum('status', ['pending', 'accepted', 'rejected'])->default('pending');
            $table->timestamps();

            $table->foreign('client_id')
            ->references('id')
            ->on('users')
            ->onDelete('cascade');

            $table->foreign('contract_id')
            ->references('id')
            ->on('contracts')
            ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('management_requests');
    }
};
