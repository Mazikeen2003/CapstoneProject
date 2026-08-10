<?php

namespace Tests\Feature;

use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ProjectRoleScopeTest extends TestCase
{
    use RefreshDatabase;

    public function test_department_user_only_sees_projects_they_created(): void
    {
        $departmentUser = User::create([
            'username' => 'dept_user',
            'user_email' => 'dept@example.com',
            'password_hash' => Hash::make('password'),
            'role_id' => 3,
            'barangay_id' => null,
        ]);

        $anotherDepartmentUser = User::create([
            'username' => 'other_dept_user',
            'user_email' => 'otherdept@example.com',
            'password_hash' => Hash::make('password'),
            'role_id' => 3,
            'barangay_id' => null,
        ]);

        $ownProject = Project::create([
            'project_code' => 'OWN-001',
            'project_name' => 'Own Project',
            'project_type' => 'Roads',
            'current_status' => 'Planning',
            'created_by' => $departmentUser->user_id,
        ]);

        Project::create([
            'project_code' => 'OTHER-001',
            'project_name' => 'Other Project',
            'project_type' => 'Roads',
            'current_status' => 'Planning',
            'created_by' => $anotherDepartmentUser->user_id,
        ]);

        $this->actingAs($departmentUser);

        $projects = Project::query()->get();

        $this->assertTrue($projects->contains('project_id', $ownProject->project_id));
        $this->assertFalse($projects->contains('project_id', $ownProject->project_id + 1));
    }

    public function test_barangay_user_only_sees_projects_in_their_barangay(): void
    {
        $barangayUser = User::create([
            'username' => 'barangay_user',
            'user_email' => 'barangay@example.com',
            'password_hash' => Hash::make('password'),
            'role_id' => 4,
            'barangay_id' => 10,
        ]);

        $ownProject = Project::create([
            'project_code' => 'BRGY-001',
            'project_name' => 'Own Barangay Project',
            'project_type' => 'Roads',
            'barangay_id' => 10,
            'current_status' => 'Planning',
            'created_by' => $barangayUser->user_id,
        ]);

        Project::create([
            'project_code' => 'BRGY-002',
            'project_name' => 'Other Barangay Project',
            'project_type' => 'Roads',
            'barangay_id' => 20,
            'current_status' => 'Planning',
            'created_by' => $barangayUser->user_id,
        ]);

        $this->actingAs($barangayUser);

        $projects = Project::query()->get();

        $this->assertTrue($projects->contains('project_id', $ownProject->project_id));
        $this->assertFalse($projects->contains('project_id', $ownProject->project_id + 1));
    }
}
