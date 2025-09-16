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
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description');
            $table->json('images')->nullable()->comment('Array of image paths');
            $table->string('live_demo_url', 500)->nullable();
            $table->string('source_code_url', 500)->nullable();
            $table->text('technologies_used')->nullable();
            $table->date('date_completed')->nullable();
            $table->enum('status', ['active', 'featured', 'archived'])->default('active');
            $table->timestamps();

            // Indexes
            $table->index('user_id', 'idx_user_id');
            $table->index('date_completed', 'idx_date_completed');
            $table->index('status', 'idx_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};