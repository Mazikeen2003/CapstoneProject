<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class UpdateProjectRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::check() && Auth::user()->hasRole('department');
    }

    public function rules(): array
    {
        $projectId = $this->route('project');

        return [
            'project_code'         => ['required', 'string', 'max:100',
                Rule::unique('projects', 'project_code')->ignore($projectId, 'project_id'),
            ],
            'project_name'         => ['required', 'string', 'max:300'],
            'project_type'         => ['required', 'string', 'max:100'],
            'barangay_id'          => ['nullable', 'exists:barangays,barangay_id'],
            'location_description' => ['nullable', 'string', 'max:1000'],
            'latitude'             => ['nullable', 'numeric', 'between:-90,90'],
            'longitude'            => ['nullable', 'numeric', 'between:-180,180'],
            'approved_budget'      => ['nullable', 'numeric', 'min:0', 'max:999999999999.99'],
            'actual_budget'        => ['nullable', 'numeric', 'min:0', 'max:999999999999.99'],
            'start_date'           => ['nullable', 'date'],
            'target_end_date'      => ['nullable', 'date', 'after_or_equal:start_date'],
            'actual_end_date'      => ['nullable', 'date'],
            'current_status'       => ['required', 'string', 'in:Planning,On Going,Completed,On Hold,Cancelled'],
            'remarks'              => ['nullable', 'string', 'max:2000'],
        ];
    }

    public function messages(): array
    {
        return [
            'project_code.required'          => 'A project code is required.',
            'project_code.unique'            => 'This project code is already in use.',
            'project_name.required'          => 'A project name is required.',
            'project_type.required'          => 'A project type is required.',
            'current_status.required'        => 'A project status is required.',
            'current_status.in'              => 'Status must be one of: Planning, On Going, Completed, On Hold, Cancelled.',
            'target_end_date.after_or_equal' => 'Target end date must be on or after the start date.',
            'latitude.between'               => 'Latitude must be between -90 and 90.',
            'longitude.between'              => 'Longitude must be between -180 and 180.',
            'approved_budget.numeric'        => 'Budget must be a valid number.',
            'approved_budget.min'            => 'Budget cannot be negative.',
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'project_code' => trim($this->project_code ?? ''),
            'project_name' => trim($this->project_name ?? ''),
        ]);
    }
}