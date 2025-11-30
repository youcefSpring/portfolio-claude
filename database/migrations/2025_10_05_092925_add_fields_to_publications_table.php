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
        Schema::table('publications', function (Blueprint $table) {
            $table->enum('type', ['journal', 'conference', 'book', 'book_chapter', 'thesis', 'report', 'preprint'])->after('authors');
            $table->enum('status', ['published', 'accepted', 'under_review', 'in_preparation'])->default('published')->after('type');
            $table->string('venue')->nullable()->after('conference');
            $table->string('volume', 50)->nullable()->after('venue');
            $table->string('issue', 50)->nullable()->after('volume');
            $table->string('pages', 50)->nullable()->after('issue');
            $table->string('doi')->nullable()->after('year');
            $table->string('url', 500)->nullable()->after('doi');
            $table->text('description')->nullable()->after('abstract');
            $table->string('keywords', 500)->nullable()->after('description');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('publications', function (Blueprint $table) {
            $table->dropColumn([
                'type', 'status', 'venue', 'volume', 'issue', 'pages',
                'doi', 'url', 'description', 'keywords'
            ]);
        });
    }
};
