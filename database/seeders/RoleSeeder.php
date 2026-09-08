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
                'role_name' => 'Planning',
                'role_description' => 'Planning Staff',
            ],
            [
                'role_name' => 'Barangay Official',
                'role_description' => 'Barangay Official',
            ],
            [
                'role_name' => 'Engineering',
                'role_description' => 'Engineering Staff',
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
