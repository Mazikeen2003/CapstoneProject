<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use App\Models\Role;
use App\Models\Barangay;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(): View
    {
        $users = User::with(['role', 'barangay'])->paginate(15);
        return view('admin.users.index', compact('users'));
    }

    public function create(): View
    {
        $roles = Role::all();
        $barangays = Barangay::all();
        return view('admin.users.create', compact('roles', 'barangays'));
    }

public function store(StoreUserRequest $request): RedirectResponse
{
    $data = $request->validated();

    if ($this->isAdminRole($data['role_id']) && ! $request->user()->isPrimaryAdmin()) {
        abort(403, 'Only the original Admin can create additional Admin accounts.');
    }

    $data['password_hash'] = Hash::make($data['password_hash']);
    $data['permissions'] = $this->normalizePermissions($request);

    User::create($data);

    return redirect()->route('admin.users.index')
        ->with('success', 'User created successfully.');
}

    public function edit($id): View
    {
        $user = User::findOrFail($id);
        $roles = Role::all();
        $barangays = Barangay::all();
        return view('admin.users.edit', compact('user', 'roles', 'barangays'));
    }

        public function update(UpdateUserRequest $request, $id): RedirectResponse
        {
            $user = User::findOrFail($id);
            $data = $request->validated();

            if (($user->role_slug === 'admin' || $this->isAdminRole($data['role_id'])) && ! $request->user()->isPrimaryAdmin()) {
                abort(403, 'Only the original Admin can modify Admin accounts.');
            }

            if (!empty($data['password_hash'])) {
                $data['password_hash'] = Hash::make($data['password_hash']);
            } else {
                unset($data['password_hash']);
            }

            $data['permissions'] = $this->normalizePermissions($request);

            $user->update($data);

            return redirect()->route('admin.users.index')
                ->with('success', 'User updated successfully.');
        }

    public function destroy($id): RedirectResponse
    {
        $user = User::findOrFail($id);

        if ($user->role_slug === 'admin' && ! request()->user()->isPrimaryAdmin()) {
            abort(403, 'Only the original Admin can delete Admin accounts.');
        }

        if ($user->isPrimaryAdmin()) {
            abort(403, 'The original Admin account cannot be deleted.');
        }

        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'User deleted successfully.');
    }

    private function normalizePermissions($request): array
{
    $keys = [
        'can_create_project',
        'can_edit_project',
        'can_delete_project',
        'can_generate_reports',
        'can_manage_users',
        'can_manage_project_permissions',
        'can_view_reports',
        'can_manage_audit_logs',
    ];
    $submitted = $request->input('permissions', []);

    $normalized = [];
    foreach ($keys as $key) {
        $normalized[$key] = isset($submitted[$key]) && $submitted[$key] == '1';
    }

    return $normalized;
}

    private function isAdminRole(int $roleId): bool
    {
        return $roleId === 1;
    }
}
