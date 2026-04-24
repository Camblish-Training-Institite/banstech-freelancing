<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('project_fundings', function (Blueprint $table) {
            $table->string('payment_gateway')->nullable()->after('transaction_id');
            $table->string('paypal_order_id')->nullable()->after('payment_gateway');
            $table->string('paypal_capture_id')->nullable()->after('paypal_order_id');
            $table->timestamp('processed_at')->nullable()->after('status');
        });
    }

    public function down(): void
    {
        Schema::table('project_fundings', function (Blueprint $table) {
            $table->dropColumn([
                'payment_gateway',
                'paypal_order_id',
                'paypal_capture_id',
                'processed_at',
            ]);
        });
    }
};
