<?php

namespace App\Policies;

use App\Models\Project;
use App\Models\User;

class ProjectPolicy
{
    /**
     * Admin and City Official can view/manage everything.
     * Department can only act on projects they created.
     * Barangay Official can only view projects in their own barangay.
     */
    public function viewAny(User $user): bool
    {
        return in_array($user->role_slug, ['admin', 'city', 'department', 'barangay']);
    }

    public function view(User $user, Project $project): bool
    {
        return match ($user->role_slug) {
            'admin', 'city' => true,
            'department'    => $project->created_by === $user->user_id,
            'barangay'      => $project->barangay_id === $user->barangay_id,
            default         => false,
        };
    }

    public function create(User $user): bool
    {
        if (! in_array($user->role_slug, ['admin', 'department'])) {
            return false;
        }

        return $user->hasPermission('can_create_project');
    }

    public function update(User $user, Project $project): bool
    {
        return match ($user->role_slug) {
            'admin'      => true,
            'department' => $project->created_by === $user->user_id && $user->hasPermission('can_edit_project'),
            default      => false,
        };
    }

    public function delete(User $user, Project $project): bool
    {
        return match ($user->role_slug) {
            'admin'      => true,
            'department' => $project->created_by === $user->user_id && $user->hasPermission('can_delete_project'),
            default      => false,
        };
    }

    public function generateReports(User $user): bool
    {
        if (! in_array($user->role_slug, ['admin', 'city', 'department', 'barangay'])) {
            return false;
        }

        return $user->hasPermission('can_generate_reports');
    }
}