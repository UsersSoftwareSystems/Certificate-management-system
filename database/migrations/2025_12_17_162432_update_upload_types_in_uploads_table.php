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
        // Modify the enum type column to include 'sports' and 'extraordinary'
        // Since DB::statement is different per driver, and assuming MySQL/MariaDB for production-like behavior:
        // Use a generic approach if possible, but for ENUMs usually raw SQL is needed.
        // We will try to modify the column definition.

        // Get the current driver
        $connection = config('database.default');
        
        if ($connection === 'mysql') {
             DB::statement("ALTER TABLE uploads MODIFY COLUMN type ENUM('tenth', 'twelfth', 'graduation', 'masters', 'sports', 'extraordinary') NOT NULL");
        } else {
             // For SQLite, we can't easily modify ENUM as it's just check constraint.
             // We drop the constraint or recreating table is complex.
             // Usually in SQLite created by Laravel migrations, ENUMs map to VARCHAR with CHECK constraint.
             // Easiest "safe" way for SQLite/Others is to change column to string generally, OR ignore if it's just local dev.
             // Let's assume standard Laravel Schema change which might fail for enum on some drivers without doctrine/dbal.
             
             // Simplest fallback: Just make it string.
             Schema::table('uploads', function (Blueprint $table) {
                 $table->string('type')->change();
             });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $connection = config('database.default');
        
        if ($connection === 'mysql') {
            DB::statement("ALTER TABLE uploads MODIFY COLUMN type ENUM('tenth', 'twelfth', 'graduation', 'masters') NOT NULL");
        } else {
            // Revert to known types (if we could, but string covers all so it's 'safe' to leave as string)
        }
    }
};
