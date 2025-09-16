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
        Schema::create('taggables', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tag_id')->constrained()->onDelete('cascade');
            $table->string('taggable_type');
            $table->unsignedBigInteger('taggable_id');
            $table->timestamp('created_at')->nullable();

            // Indexes
            $table->index('tag_id', 'idx_tag_id');
            $table->index(['taggable_type', 'taggable_id'], 'idx_taggable');
            $table->unique(['tag_id', 'taggable_type', 'taggable_id'], 'unique_tag_taggable');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('taggables');
    }
};