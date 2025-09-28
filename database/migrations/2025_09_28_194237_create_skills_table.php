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
        Schema::create('skills', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->enum('category', ['programming', 'framework', 'database', 'tool', 'design', 'soft_skill', 'other'])->default('other');
            $table->integer('proficiency_level')->default(1)->comment('1-5 scale (1=Beginner, 5=Expert)');
            $table->string('icon')->nullable()->comment('Font Awesome icon class or image path');
            $table->string('color', 7)->nullable()->comment('Hex color code for skill badge');
            $table->boolean('is_featured')->default(false);
            $table->integer('years_experience')->nullable();
            $table->integer('sort_order')->default(0);
            $table->timestamps();

            // Indexes
            $table->index('category');
            $table->index('proficiency_level');
            $table->index('is_featured');
            $table->index('sort_order');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('skills');
    }
};
