<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('project_fundings', function (Blueprint $table) {
            $table->string('source_currency', 3)->nullable()->after('amount');
            $table->string('paypal_currency', 3)->nullable()->after('source_currency');
            $table->decimal('paypal_amount', 10, 2)->nullable()->after('paypal_currency');
            $table->decimal('exchange_rate', 14, 8)->nullable()->after('paypal_amount');
        });

        Schema::table('payouts', function (Blueprint $table) {
            $table->string('source_currency', 3)->nullable()->after('amount');
            $table->string('paypal_currency', 3)->nullable()->after('source_currency');
            $table->decimal('paypal_amount', 10, 2)->nullable()->after('paypal_currency');
            $table->decimal('exchange_rate', 14, 8)->nullable()->after('paypal_amount');
        });
    }

    public function down(): void
    {
        Schema::table('project_fundings', function (Blueprint $table) {
            $table->dropColumn(['source_currency', 'paypal_currency', 'paypal_amount', 'exchange_rate']);
        });

        Schema::table('payouts', function (Blueprint $table) {
            $table->dropColumn(['source_currency', 'paypal_currency', 'paypal_amount', 'exchange_rate']);
        });
    }
};
