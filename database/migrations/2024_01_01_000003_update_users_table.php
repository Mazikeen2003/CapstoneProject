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
        Schema::table('users', function (Blueprint $table) {
            // Rename id to user_id if it exists
            if (Schema::hasColumn('users', 'id')) {
                $table->renameColumn('id', 'user_id');
            }
            
            // Rename name to username if it exists
            if (Schema::hasColumn('users', 'name')) {
                $table->renameColumn('name', 'username');
            }
            
            // Rename email to user_email if it exists
            if (Schema::hasColumn('users', 'email')) {
                $table->renameColumn('email', 'user_email');
            }
            
            // Rename password to password_hash if it exists
            if (Schema::hasColumn('users', 'password')) {
                $table->renameColumn('password', 'password_hash');
            }
        });

        Schema::table('users', function (Blueprint $table) {
            // Add foreign keys
            if (!Schema::hasColumn('users', 'role_id')) {
                $table->unsignedBigInteger('role_id')->nullable()->after('user_email');
            }
            if (!Schema::hasColumn('users', 'barangay_id')) {
                $table->unsignedBigInteger('barangay_id')->nullable()->after('role_id');
            }

            // Add foreign key constraints
            $table->foreign('role_id')->references('role_id')->on('roles')->onDelete('set null');
            $table->foreign('barangay_id')->references('barangay_id')->on('barangays')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeignKey(['role_id']);
            $table->dropForeignKey(['barangay_id']);
            $table->dropColumn(['role_id', 'barangay_id']);
        });
    }
};
