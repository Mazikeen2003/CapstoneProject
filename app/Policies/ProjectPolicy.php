<?php

namespace App\Policies;

use App\Models\Project;
use App\Models\User;

class ProjectPolicy
{
    /**
     * Admin and City Official can view/manage everything.
     * Department users can access any project in the department workflow.
     * Barangay Official can only view projects in their own barangay.
     */
    public function viewAny(User $user): bool
    {
        return in_array($user->role_slug, ['admin', 'city', 'department', 'barangay', 'engineering']);
    }

    public function view(User $user, Project $project): bool
    {
        return match ($user->role_slug) {
            'admin', 'city', 'engineering' => true,
            'department'    => true,
            'barangay'      => $project->barangay_id === $user->barangay_id,
            default         => false,
        };
    }

    public function create(User $user): bool
    {
        if (! in_array($user->role_slug, ['admin', 'department'])) {
            return false;
        }

        if ($user->isDepartmentHead()) {
            return true;
        }

        return $user->hasPermission('can_create_project');
    }

    public function update(User $user, Project $project): bool
    {
        return match ($user->role_slug) {
            'admin' => true,
            'department' => true,
            default => false,
        };
    }

    public function delete(User $user, Project $project): bool
    {
        return match ($user->role_slug) {
            'admin' => true,
            'department' => true,
            default => false,
        };
    }

    public function generateReports(User $user): bool
    {
        if (! in_array($user->role_slug, ['admin', 'city', 'department', 'barangay', 'engineering'])) {
            return false;
        }

        if ($user->isDepartmentHead()) {
            return true;
        }

        return $user->hasPermission('can_generate_reports');
    }

    public function updateForms(User $user, Project $project): bool
    {
        if (! in_array($user->role_slug, ['admin', 'department', 'engineering'], true)) {
            return false;
        }

        if ($user->role_slug === 'admin') {
            return true;
        }

        if ($user->role_slug === 'engineering') {
            return true;
        }

        if ($user->role_slug === 'department') {
            return true;
        }

        return false;
    }
}