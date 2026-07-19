<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'first_name' => ['required', 'string', 'max:100'],
            'last_name' => ['required', 'string', 'max:100'],
            'username' => ['required', 'string', 'max:100', 'unique:users,username'],
            'user_email' => ['required', 'email', 'max:100', 'unique:users,user_email'],
            'password_hash' => ['required', 'string', 'min:8', 'confirmed'],
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

    public function messages()
    {
        return [
            'first_name.required' => 'First name is required.',
            'last_name.required' => 'Last name is required.',
            'username.required' => 'Username is required.',
            'username.unique' => 'This username is already taken.',
            'user_email.required' => 'Email is required.',
            'user_email.email' => 'Please enter a valid email.',
            'user_email.unique' => 'This email is already registered.',
            'password_hash.required' => 'Password is required.',
            'password_hash.min' => 'Password must be at least 8 characters.',
            'password_hash.confirmed' => 'Password confirmation does not match.',
            'role_id.required' => 'Role is required.',
            'role_id.exists' => 'Selected role does not exist.',
            'barangay_id.exists' => 'Selected barangay does not exist.',
        ];
    }
}
