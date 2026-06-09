<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('roles')->insert([
            [
                'role_name' => 'Admin',
                'role_description' => 'System Administrator',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'role_name' => 'City Official',
                'role_description' => 'City Government Official',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'role_name' => 'Department',
                'role_description' => 'Department Staff',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'role_name' => 'Barangay Official',
                'role_description' => 'Barangay Official',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
