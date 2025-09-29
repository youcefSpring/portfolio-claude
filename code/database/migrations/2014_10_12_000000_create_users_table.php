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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->enum('role', ['teacher', 'admin', 'editor'])->default('teacher');
            $table->enum('status', ['active', 'inactive', 'suspended'])->default('active');
            $table->text('bio')->nullable();
            $table->string('profile_picture', 500)->nullable();
            $table->json('contact_info')->nullable();
            $table->string('cv_file_path', 500)->nullable();
            $table->rememberToken();
            $table->timestamps();

            // Indexes
            $table->index('role', 'idx_role');
            $table->index('status', 'idx_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
