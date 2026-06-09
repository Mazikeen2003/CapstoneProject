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
        $data['password_hash'] = Hash::make($data['password_hash']);

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

        // Only hash password if provided
        if (!empty($data['password_hash'])) {
            $data['password_hash'] = Hash::make($data['password_hash']);
        } else {
            unset($data['password_hash']);
        }

        $user->update($data);

        return redirect()->route('admin.users.index')
            ->with('success', 'User updated successfully.');
    }

    public function destroy($id): RedirectResponse
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'User deleted successfully.');
    }
}