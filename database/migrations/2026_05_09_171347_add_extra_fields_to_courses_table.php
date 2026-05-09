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
            $table->string('course_code')->nullable()->after('title');
            $table->integer('credits')->nullable()->after('course_code');
            $table->text('objectives')->nullable()->after('description');
            $table->text('prerequisites')->nullable()->after('objectives');
            $table->text('syllabus_content')->nullable()->after('prerequisites');
            $table->string('level')->nullable()->after('syllabus_content');
            $table->string('department')->nullable()->after('level');
            $table->boolean('is_active')->default(true)->after('status');
            $table->boolean('is_featured')->default(false)->after('is_active');
            $table->string('image')->nullable()->after('is_featured');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->dropColumn([
                'course_code',
                'credits',
                'objectives',
                'prerequisites',
                'syllabus_content',
                'level',
                'department',
                'is_active',
                'is_featured',
                'image'
            ]);
        });
    }
};
