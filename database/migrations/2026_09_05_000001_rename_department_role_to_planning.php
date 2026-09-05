<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('roles')
            ->where('role_name', 'Department')
            ->update([
                'role_name' => 'Planning',
                'role_description' => 'Planning Staff',
            ]);
    }

    public function down(): void
    {
        DB::table('roles')
            ->where('role_name', 'Planning')
            ->update([
                'role_name' => 'Department',
                'role_description' => 'Department Staff',
            ]);
    }
};
