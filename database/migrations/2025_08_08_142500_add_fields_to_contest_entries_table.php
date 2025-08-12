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
        Schema::table('contest_entries', function (Blueprint $table) {
            $table->string('title')->after('freelancer_id');
            $table->renameColumn('submission_details', 'description');
            $table->boolean('is_original')->default(true)->after('description'); // For licensed content
            $table->decimal('sell_price', 10, 2)->nullable()->after('is_original');
            $table->boolean('is_highlighted')->default(false)->after('sell_price');
            $table->boolean('is_sealed')->default(false)->after('is_highlighted');
            $table->json('files')->nullable()->after('is_sealed'); // To store uploaded file paths
        });
    }


    /**
     * Reverse the migrations.
     */
   public function down(): void
    {
        Schema::table('contest_entries', function (Blueprint $table) {
            $table->dropColumn('title');
            $table->renameColumn('description', 'submission_details');
            $table->dropColumn('is_original');
            $table->dropColumn('sell_price');
            $table->dropColumn('is_highlighted');
            $table->dropColumn('is_sealed');
            $table->dropColumn('files');
        });
    }
};
