<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Modify the project_type enum to include 'internship'
        DB::statement("ALTER TABLE job_offers MODIFY COLUMN project_type ENUM('consulting', 'freelance', 'contract', 'internship') DEFAULT 'consulting'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert back to original enum values
        DB::statement("ALTER TABLE job_offers MODIFY COLUMN project_type ENUM('consulting', 'freelance', 'contract') DEFAULT 'consulting'");
    }
};
