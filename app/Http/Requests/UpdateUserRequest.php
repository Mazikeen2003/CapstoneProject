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
        $userId = $this->route('id');

        return [
            'username' => ['required', 'string', 'max:100', Rule::unique('users', 'username')->ignore($userId, 'user_id')],
            'user_email' => ['required', 'email', 'max:100', Rule::unique('users', 'user_email')->ignore($userId, 'user_id')],
            'password_hash' => ['nullable', 'string', 'min:8', 'confirmed'],
            'role_id' => ['required', 'exists:roles,role_id'],
            'barangay_id' => ['nullable', 'exists:barangays,barangay_id'],
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
