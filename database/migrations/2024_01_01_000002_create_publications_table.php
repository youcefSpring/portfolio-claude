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
        Schema::create('publications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('title', 500);
            $table->string('authors', 500);
            $table->string('journal')->nullable();
            $table->string('conference')->nullable();
            $table->smallInteger('year');
            $table->text('abstract')->nullable();
            $table->string('publication_file_path', 500)->nullable();
            $table->string('external_link', 500)->nullable();
            $table->timestamps();

            // Indexes
            $table->index('user_id', 'idx_user_id');
            $table->index('year', 'idx_year');
            $table->index('journal', 'idx_journal');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('publications');
    }
};