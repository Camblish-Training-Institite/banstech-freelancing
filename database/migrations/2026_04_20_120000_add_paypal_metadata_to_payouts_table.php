<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('payouts', function (Blueprint $table) {
            $table->unsignedBigInteger('milestone_id')->nullable()->after('contract_id');
            $table->string('method')->nullable()->after('amount');
            $table->string('paypal_recipient_email')->nullable()->after('method');
            $table->string('paypal_batch_id')->nullable()->after('paypal_recipient_email');
            $table->string('paypal_payout_item_id')->nullable()->after('paypal_batch_id');
            $table->json('gateway_response')->nullable()->after('paypal_payout_item_id');

            $table->foreign('milestone_id')->references('id')->on('milestones')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('payouts', function (Blueprint $table) {
            $table->dropForeign(['milestone_id']);
            $table->dropColumn([
                'milestone_id',
                'method',
                'paypal_recipient_email',
                'paypal_batch_id',
                'paypal_payout_item_id',
                'gateway_response',
            ]);
        });
    }
};
