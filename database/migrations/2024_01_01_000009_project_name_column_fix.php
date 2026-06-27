<?php

use Illuminate\Database\Migrations\Migration;

/**
 * The actual database already uses project_name (correct).
 * The typo project_namee existed only in the old Laravel fillable array,
 * which has been fixed in app/Models/Project.php.
 * This migration is a no-op kept for record only.
 */
return new class extends Migration
{
    public function up(): void
    {
        // No action needed — project_name column is already correct in the DB.
    }

    public function down(): void
    {
        // Nothing to reverse.
    }
};