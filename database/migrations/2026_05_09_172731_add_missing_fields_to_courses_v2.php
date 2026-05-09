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
        Schema::table('courses', function (Blueprint $table) {
            if (!Schema::hasColumn('courses', 'learning_objectives')) {
                $table->text('learning_objectives')->nullable()->after('objectives');
            }
            $table->text('content')->nullable()->after('description');
            $table->text('assessment_methods')->nullable()->after('syllabus_content');
            $table->text('resources')->nullable()->after('assessment_methods');
            $table->string('meta_title')->nullable()->after('resources');
            $table->text('meta_description')->nullable()->after('meta_title');
            $table->string('semester')->nullable()->after('department');
            $table->integer('year')->nullable()->after('semester');
            $table->string('instructor')->nullable()->after('year');
            $table->boolean('is_published')->default(false)->after('is_featured');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->dropColumn([
                'learning_objectives',
                'content',
                'assessment_methods',
                'resources',
                'meta_title',
                'meta_description',
                'semester',
                'year',
                'instructor',
                'is_published'
            ]);
        });
    }
};
