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
        Schema::create('credentials', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->string('issuing_organization');
            $table->date('issue_date')->nullable();
            $table->date('expiry_date')->nullable();
            $table->string('certificate_file_path', 500)->nullable();
            $table->string('credential_url', 500)->nullable();
            $table->timestamps();

            // Indexes
            $table->index('user_id', 'idx_user_id');
            $table->index('issue_date', 'idx_issue_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('credentials');
    }
};