<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('withdrawal_requests', function (Blueprint $table) {
            $table->string('paypal_recipient_email')->nullable()->after('destination_details');
            $table->string('paypal_batch_id')->nullable()->after('paypal_recipient_email');
            $table->string('paypal_payout_item_id')->nullable()->after('paypal_batch_id');
            $table->json('gateway_response')->nullable()->after('paypal_payout_item_id');
            $table->timestamp('confirmed_at')->nullable()->after('gateway_response');
            $table->foreignId('reviewed_by_admin_id')->nullable()->after('processed_at')->constrained('users')->nullOnDelete();
            $table->text('failure_reason')->nullable()->after('reviewed_by_admin_id');
        });
    }

    public function down(): void
    {
        Schema::table('withdrawal_requests', function (Blueprint $table) {
            $table->dropForeign(['reviewed_by_admin_id']);
            $table->dropColumn([
                'paypal_recipient_email',
                'paypal_batch_id',
                'paypal_payout_item_id',
                'gateway_response',
                'confirmed_at',
                'reviewed_by_admin_id',
                'failure_reason',
            ]);
        });
    }
};
