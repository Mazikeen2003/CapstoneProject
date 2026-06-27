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
        // Anyone with a recognized role can see *some* project list;
        // actual row filtering happens via the global scope on the model.
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
        return in_array($user->role_slug, ['admin', 'department']);
    }

    public function update(User $user, Project $project): bool
    {
        return match ($user->role_slug) {
            'admin'      => true,
            'department' => $project->created_by === $user->user_id,
            default      => false,
        };
    }

    public function delete(User $user, Project $project): bool
    {
        return match ($user->role_slug) {
            'admin'      => true,
            'department' => $project->created_by === $user->user_id,
            default      => false,
        };
    }
}