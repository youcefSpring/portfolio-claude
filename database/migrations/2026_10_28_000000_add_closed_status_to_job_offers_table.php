<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Modify the status enum to include 'closed'
        DB::statement("ALTER TABLE job_offers MODIFY COLUMN status ENUM('active', 'closed', 'filled', 'cancelled') DEFAULT 'active'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("UPDATE job_offers SET status = 'cancelled' WHERE status = 'closed'");
        DB::statement("ALTER TABLE job_offers MODIFY COLUMN status ENUM('active', 'filled', 'cancelled') DEFAULT 'active'");
    }
};
