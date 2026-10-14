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
        Schema::table('job_applications', function (Blueprint $table) {
            $table->string('github_url', 500)->nullable()->after('phone');
            $table->string('linkedin_url', 500)->nullable()->after('github_url');
            $table->string('telegram', 100)->nullable()->after('linkedin_url');
            $table->string('portfolio_url', 500)->nullable()->after('telegram');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('job_applications', function (Blueprint $table) {
            $table->dropColumn(['github_url', 'linkedin_url', 'telegram', 'portfolio_url']);
        });
    }
};
