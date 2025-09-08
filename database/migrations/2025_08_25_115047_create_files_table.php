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
        Schema::create('files', function (Blueprint $table) {
            $table->id();
            $table->string('file_name');
            $table->string('file_path'); // e.g., 'projects/123/document.pdf'
            $table->string('file_size'); // e.g., '2.5 MB'
            $table->unsignedBigInteger('project_id')->constrained()->onDelete('cascade');
            $table->unsignedBigInteger('user_id')->constrained()->onDelete('set null');
            $table->timestamps();

            $table->foreign('project_id')->references('id')->on('contracts')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('files');
    }
};
