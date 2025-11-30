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
        Schema::create('job_offers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description');
            $table->text('requirements')->nullable();
            $table->enum('project_type', ['consulting', 'freelance', 'contract'])->default('consulting');
            $table->decimal('budget_min', 10, 2)->nullable();
            $table->decimal('budget_max', 10, 2)->nullable();
            $table->string('duration', 100)->nullable()->comment('e.g., "3 months", "Ongoing"');
            $table->enum('location_type', ['remote', 'on-site', 'hybrid'])->default('remote');
            $table->string('location')->nullable();
            $table->json('skills_required')->nullable()->comment('Array of required skills');
            $table->enum('status', ['active', 'filled', 'cancelled'])->default('active');
            $table->boolean('featured')->default(false);
            $table->timestamp('published_at')->nullable();
            $table->timestamps();

            // Indexes for better query performance
            $table->index('status');
            $table->index('featured');
            $table->index('published_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_offers');
    }
};
