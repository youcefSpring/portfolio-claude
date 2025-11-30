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
        Schema::create('job_applications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('job_offer_id')->constrained()->onDelete('cascade');
            $table->string('full_name');
            $table->string('email');
            $table->string('phone', 50);
            $table->string('cv_file_path', 500);
            $table->text('cover_letter')->nullable();
            $table->enum('status', ['pending', 'reviewed', 'shortlisted', 'rejected', 'accepted'])->default('pending');
            $table->text('notes')->nullable()->comment('Admin notes');
            $table->timestamp('applied_at');
            $table->timestamp('reviewed_at')->nullable();
            $table->timestamps();

            // Indexes for better query performance
            $table->index('job_offer_id');
            $table->index('status');
            $table->index('applied_at');
            $table->index('email');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_applications');
    }
};
