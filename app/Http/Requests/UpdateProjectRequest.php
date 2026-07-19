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
        $project = $this->route('project') ?? $this->route('id');
        $projectId = is_object($project) ? $project->project_id : $project;

        return [
            'project_code'         => ['required', 'string', 'max:100', Rule::unique('projects', 'project_code')->ignore($projectId, 'project_id')],
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
            'current_status'       => ['required', 'string', 'in:Planning,On Going,Completed,On Hold,Cancelled,Bidding - Success,Bidding - Failed,Procurement'],
            'remarks'              => ['nullable', 'string', 'max:2000'],
            'project_image'        => ['nullable', 'image', 'max:5120'],
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
