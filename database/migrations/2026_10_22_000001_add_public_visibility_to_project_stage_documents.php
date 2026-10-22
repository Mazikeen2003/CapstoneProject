<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('project_stage_documents', function (Blueprint $table) {
            $table->boolean('is_public')->default(false)->after('mime_type');
        });

        DB::table('project_stage_documents')
            ->where('stage', 1)
            ->where('document_type', 'Detailed Project Information')
            ->update(['is_public' => true]);
    }

    public function down(): void
    {
        Schema::table('project_stage_documents', function (Blueprint $table) {
            $table->dropColumn('is_public');
        });
    }
};
