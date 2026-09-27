<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('edit_permission_requests') && ! Schema::hasColumn('edit_permission_requests', 'request_type')) {
            Schema::table('edit_permission_requests', function (Blueprint $table) {
                $table->string('request_type', 20)->default('edit');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('edit_permission_requests') && Schema::hasColumn('edit_permission_requests', 'request_type')) {
            Schema::table('edit_permission_requests', function (Blueprint $table) {
                $table->dropColumn('request_type');
            });
        }
    }
};