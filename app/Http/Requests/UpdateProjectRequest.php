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
            'current_status'       => ['required', 'string', function ($attribute, $value, $fail) use ($projectId) {
                $project = \App\Models\Project::find($projectId);
                $statusSteps = ['Proposed', 'For bidding', 'Bidding ongoing', 'Award of contract', 'Implementation', 'Completed'];
                $currentStep = array_search($project?->current_status, $statusSteps, true);
                $allowedStatuses = match ($project?->current_status) {
                    'On Hold' => array_filter([$project?->statusBeforeOnHold(), 'On Hold', 'Cancelled']),
                    'Cancelled' => ['Cancelled'],
                    default => array_filter([
                        $project?->current_status,
                        $currentStep !== false ? ($statusSteps[$currentStep + 1] ?? null) : null,
                        $project?->current_status !== 'Completed' ? 'On Hold' : null,
                        $project?->current_status !== 'Completed' ? 'Cancelled' : null,
                    ]),
                };

                if (! in_array($value, $allowedStatuses, true)) {
                    $fail('The project status can only remain the same or advance one phase at a time.');
                }
            }],
            'lifecycle_stage'      => ['nullable', 'integer', 'between:1,6'],
            'public_description'   => ['nullable', 'string', 'max:1000'],
            'remarks'              => ['nullable', 'string', 'max:2000'],
            'project_image'        => ['nullable', 'file', 'image', 'mimes:jpg,jpeg,png,webp,gif', 'max:2048'],
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
