<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Index for frequently queried columns
        Schema::table('projects', function (Blueprint $table) {
            $table->index('created_by');
            $table->index('barangay_id');
            $table->index('current_status');
            $table->index('deleted_at');
            $table->index(['barangay_id', 'current_status']);
            $table->index(['created_by', 'deleted_at']);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->index('role_id');
            $table->index('barangay_id');
            $table->index('deleted_at');
        });

        Schema::table('audit_logs', function (Blueprint $table) {
            $table->index('user_id');
            $table->index('table_name');
            $table->index('created_at');
        });

        Schema::table('budget_transactions', function (Blueprint $table) {
            $table->index('project_id');
            $table->index('user_id');
            $table->index('created_at');
        });

        Schema::table('project_updates', function (Blueprint $table) {
            $table->index('project_id');
            $table->index('user_id');
            $table->index('update_date');
        });
    }

    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->dropIndex(['created_by']);
            $table->dropIndex(['barangay_id']);
            $table->dropIndex(['current_status']);
            $table->dropIndex(['deleted_at']);
            $table->dropIndex(['barangay_id', 'current_status']);
            $table->dropIndex(['created_by', 'deleted_at']);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['role_id']);
            $table->dropIndex(['barangay_id']);
            $table->dropIndex(['deleted_at']);
        });

        Schema::table('audit_logs', function (Blueprint $table) {
            $table->dropIndex(['user_id']);
            $table->dropIndex(['table_name']);
            $table->dropIndex(['created_at']);
        });

        Schema::table('budget_transactions', function (Blueprint $table) {
            $table->dropIndex(['project_id']);
            $table->dropIndex(['user_id']);
            $table->dropIndex(['created_at']);
        });

        Schema::table('project_updates', function (Blueprint $table) {
            $table->dropIndex(['project_id']);
            $table->dropIndex(['user_id']);
            $table->dropIndex(['update_date']);
        });
    }
};