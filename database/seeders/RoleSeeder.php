<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            [
                'role_name' => 'Admin',
                'role_description' => 'System Administrator',
            ],
            [
                'role_name' => 'City Official',
                'role_description' => 'City Government Official',
            ],
            [
                'role_name' => 'Department',
                'role_description' => 'Department Staff',
            ],
            [
                'role_name' => 'Barangay Official',
                'role_description' => 'Barangay Official',
            ],
        ];

        foreach ($roles as $role) {
            Role::query()->updateOrCreate(
                ['role_name' => $role['role_name']],
                ['role_description' => $role['role_description']]
            );
        }
    }
}
