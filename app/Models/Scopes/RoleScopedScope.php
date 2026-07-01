<?php

namespace App\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Facades\Auth;

class RoleScopedScope implements Scope
{
    /**
     * Apply role-based filtering automatically to every Project query.
     * Admin and City Official see everything. Department sees only
     * what they created. Barangay Official sees only their barangay.
     *
     * This runs automatically — controllers no longer need to remember
     * to call ->forUser($user) manually.
     */
    public function apply(Builder $builder, Model $model): void
    {
        if (! Auth::check()) {
            return;
        }

        $user = Auth::user();

        match ($user->role_slug) {
            'department' => $builder->whereHas('creator', function ($query) {
                $query->where('role_id', 3);
            }),
            'barangay'   => $builder->where('barangay_id', $user->barangay_id),
            default      => null, // admin and city see everything, no filter applied
        };
    }
}