<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        // Determine the current user id from route parameters (supports resource routes and manual id param)
        $routeUser = $this->route('user') ?? $this->route('id');
        $userId = null;
        if ($routeUser instanceof \App\Models\User) {
            $userId = $routeUser->user_id;
        } elseif (is_numeric($routeUser)) {
            $userId = (int) $routeUser;
        }

        return [
            'first_name' => ['required', 'string', 'max:100'],
            'last_name' => ['required', 'string', 'max:100'],
            'username' => ['required', 'string', 'max:100', Rule::unique('users', 'username')->ignore($userId, 'user_id')],
            'user_email' => ['required', 'email', 'max:100', Rule::unique('users', 'user_email')->ignore($userId, 'user_id')],
            'password_hash' => ['nullable', 'string', 'min:8', 'confirmed'],
            'role_id' => ['required', 'exists:roles,role_id'],
            'barangay_id' => ['nullable', 'exists:barangays,barangay_id'],
            'permissions' => ['nullable', 'array'],
            'permissions.can_create_project' => ['nullable', 'boolean'],
            'permissions.can_edit_project' => ['nullable', 'boolean'],
            'permissions.can_delete_project' => ['nullable', 'boolean'],
            'permissions.can_generate_reports' => ['nullable', 'boolean'],
            'permissions.can_manage_users' => ['nullable', 'boolean'],
            'permissions.can_manage_project_permissions' => ['nullable', 'boolean'],
            'permissions.can_view_reports' => ['nullable', 'boolean'],
            'permissions.can_manage_audit_logs' => ['nullable', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'username.required' => 'Username is required.',
            'username.unique' => 'This username is already taken.',
            'user_email.required' => 'Email is required.',
            'user_email.email' => 'Please enter a valid email.',
            'user_email.unique' => 'This email is already registered.',
            'password_hash.min' => 'Password must be at least 8 characters.',
            'password_hash.confirmed' => 'Password confirmation does not match.',
            'role_id.required' => 'Role is required.',
            'role_id.exists' => 'Selected role does not exist.',
            'barangay_id.exists' => 'Selected barangay does not exist.',
        ];
    }
}
