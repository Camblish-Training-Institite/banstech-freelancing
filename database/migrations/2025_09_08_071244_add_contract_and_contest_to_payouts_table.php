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
        Schema::table('payouts', function (Blueprint $table) {
            $table->unsignedBigInteger('contract_id')->nullable()->after('freelancer_id');
            $table->unsignedBigInteger('contest_id')->nullable()->after('contract_id');
           

            $table->foreign('contract_id')->references('id')->on('contracts')->onDelete('set null');
            $table->foreign('contest_id')->references('id')->on('contests')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payouts', function (Blueprint $table) {
            $table->dropForeign(['contract_id']);
            $table->dropForeign(['contest_id']);
            
            $table->dropColumn(['contract_id', 'contest_id']);
        });
    }
};
