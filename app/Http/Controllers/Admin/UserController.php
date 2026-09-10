<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Mail\NewAccountPasswordMail;
use App\Models\User;
use App\Models\Role;
use App\Models\Barangay;
use App\Services\AuditLogService;
use App\Services\BackupService;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(\Illuminate\Http\Request $request): View
    {
        $roleId = $request->input('role_id');
        $query = User::with(['role', 'barangay'])->orderBy('username');

        if (is_numeric($roleId) && (int) $roleId > 0) {
            $query->where('role_id', (int) $roleId);
        }

        $users = $query->paginate(10)->withQueryString();
        $roles = Role::orderBy('role_name')->get();

        return view('admin.users.index', compact('users', 'roles'));
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

    $setupToken = Str::random(64);

    $data['password_hash'] = Hash::make(Str::random(48));
    $data['must_change_password'] = true;
    $data['permissions'] = $this->normalizePermissions($request);
    $data['is_disabled'] = $request->boolean('is_disabled');
    $data['is_department_head'] = $this->roleSlugForId((int) $data['role_id']) === 'department'
        ? (bool) $request->boolean('is_department_head')
        : false;
    $data['disabled_at'] = $data['is_disabled'] ? now() : null;

    $user = User::create($data);

    DB::table('account_setup_tokens')->where('user_id', $user->user_id)->delete();
    DB::table('account_setup_tokens')->insert([
        'user_id' => $user->user_id,
        'token_hash' => hash('sha256', $setupToken),
        'expires_at' => now()->addHours(24),
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    $setupUrl = route('account.setup', ['token' => $setupToken]);

    $emailSent = true;

    try {
        Mail::to($user->user_email)->send(
            new NewAccountPasswordMail($user->first_name ?: $user->username, $user->username, $setupUrl)
        );
    } catch (\Throwable $exception) {
        $emailSent = false;
        Log::error('New user account email could not be sent.', [
            'user_id' => $user->user_id,
            'email' => $user->user_email,
            'error' => $exception->getMessage(),
        ]);
    }

    AuditLogService::logCreate($user);
    BackupService::createBackup('user_create', $request->user()->user_id);

    $redirect = redirect()->route('admin.users.index')
        ->with('success', $emailSent
            ? 'User created successfully. A secure password setup link has been emailed to them.'
            : 'User created successfully, but the password setup email could not be sent.');

    if (! $emailSent) {
        $redirect->with('warning', 'Check the mail configuration or application logs before asking the user to sign in.');
    }

    return $redirect;
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

            unset($data['password_hash']);

            $data['is_disabled'] = $request->boolean('is_disabled');
            $data['barangay_id'] = $this->roleSlugForId((int) $data['role_id']) === 'barangay'
                ? ($data['barangay_id'] ?? null)
                : null;
            $data['is_department_head'] = $this->roleSlugForId((int) $data['role_id']) === 'department'
                ? (bool) $request->boolean('is_department_head')
                : false;
            $data['disabled_at'] = $data['is_disabled'] ? ($user->disabled_at ?? now()) : null;

            if ($this->usesGranularPermissions($user, (int) $data['role_id'])) {
                $data['permissions'] = $this->normalizePermissions($request);
            } else {
                unset($data['permissions']);
            }

            $original = $user->getOriginal();
            $user->update($data);

            AuditLogService::logUpdate($user, $original);
            BackupService::createBackup('user_update', $request->user()->user_id);

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

        AuditLogService::logDelete($user);
        BackupService::createBackup('user_delete', request()->user()->user_id);

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
        'can_view_reports',
        'can_manage_audit_logs',
        'can_manage_backups',
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

    private function roleSlugForId(int $roleId): string
    {
        return match ($roleId) {
            1 => 'admin',
            2 => 'city',
            3 => 'department',
            4 => 'barangay',
            default => '',
        };
    }

    private function usesGranularPermissions(User $user, int $roleId): bool
    {
        if ($roleId === 3) {
            return true;
        }

        return $roleId === 1 && ! $user->isPrimaryAdmin();
    }
}
