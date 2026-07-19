<?php

namespace App\Http\Controllers\Department;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\ProjectForm;
use App\Services\AuditLogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProjectFormController extends Controller
{
    private array $months = [
        'january', 'february', 'march', 'april', 'may', 'june',
        'july', 'august', 'september', 'october', 'november', 'december',
    ];

    public function edit(Project $project, string $type)
    {
        $this->authorize('view', $project);

        abort_unless(in_array($type, ['form_1', 'form_2', 'form_3', 'form_4', 'form_5', 'form_6', 'form_7', 'form_8', 'form_9', 'form_10', 'form_11'], true), 404);

        $form = ProjectForm::where('project_id', $project->project_id)
            ->where('form_type', $type)
            ->first();

        return view('department.projects.forms.' . str_replace('_', '-', $type), [
            'project' => $project,
            'form' => $form,
            'months' => $this->months,
        ]);
    }

    public function update(Request $request, Project $project, string $type)
    {
        $this->authorize('update', $project);

        abort_unless(in_array($type, ['form_1', 'form_2', 'form_3', 'form_4', 'form_5', 'form_6', 'form_7', 'form_8', 'form_9', 'form_10', 'form_11'], true), 404);

        $validated = $request->validate($this->rulesFor($type));

        $form = ProjectForm::where('project_id', $project->project_id)
            ->where('form_type', $type)
            ->first();

        $isNew = ! $form;

        if ($isNew) {
            $form = new ProjectForm([
                'project_id' => $project->project_id,
                'form_type' => $type,
                'form_title' => $this->formTitle($type),
                'created_by' => Auth::id(),
            ]);
        }

        $form->form_data = $validated;
        $form->updated_by = Auth::id();
        $form->save();

        if ($isNew) {
            AuditLogService::logCreate($form);
        }

        return redirect()
            ->route('department.projects.show', $project->project_id)
            ->with('success', 'Form ' . str_replace('form_', '', $type) . ' saved successfully.');
    }

    private function rulesFor(string $type): array
    {
        $signatoryRules = [
            'submitted_by' => ['nullable', 'string', 'max:255'],
            'submitted_designation' => ['nullable', 'string', 'max:255'],
            'submitted_date' => ['nullable', 'date'],
            'approved_by' => ['nullable', 'string', 'max:255'],
            'approved_designation' => ['nullable', 'string', 'max:255'],
            'approved_date' => ['nullable', 'date'],
        ];

        return match ($type) {
            'form_1' => [
            'implementing_agency' => ['nullable', 'string', 'max:255'],
            'component_details' => ['nullable', 'string'],
            'fund_source' => ['nullable', 'string'],
            'funding_agency' => ['nullable', 'string', 'max:255'],
            'mode_of_implementation' => ['nullable', 'string'],
            'total_project_cost' => ['nullable', 'string', 'max:100'],
            'sector' => ['nullable', 'string'],
            'remarks' => ['nullable', 'string'],
            'target_employment_male' => ['nullable', 'integer', 'min:0'],
            'target_employment_female' => ['nullable', 'integer', 'min:0'],
            'output_indicator_1' => ['nullable', 'string', 'max:255'],
            'output_indicator_2' => ['nullable', 'string', 'max:255'],
            'output_indicator_3' => ['nullable', 'string', 'max:255'],
            'output_indicator_4' => ['nullable', 'string', 'max:255'],
            'output_indicator_5' => ['nullable', 'string', 'max:255'],
            'monthly' => ['nullable', 'array'],
            'submitted_by' => ['nullable', 'string', 'max:255'],
            'submitted_designation' => ['nullable', 'string', 'max:255'],
            'submitted_date' => ['nullable', 'date'],
            'approved_by' => ['nullable', 'string', 'max:255'],
            'approved_designation' => ['nullable', 'string', 'max:255'],
            'approved_date' => ['nullable', 'date'],
            ],
            'form_2' => array_merge([
                'implementing_agency' => ['nullable', 'string', 'max:255'],
                'start_date' => ['nullable', 'date'],
                'end_date' => ['nullable', 'date'],
                'fund_source' => ['nullable', 'string', 'max:255'],
                'funding_agency' => ['nullable', 'string', 'max:255'],
                'total_project_cost' => ['nullable', 'string', 'max:100'],
                'appropriations' => ['nullable', 'string', 'max:100'],
                'allotment' => ['nullable', 'string', 'max:100'],
                'obligations' => ['nullable', 'string', 'max:100'],
                'disbursements' => ['nullable', 'string', 'max:100'],
                'target_owpa_to_date' => ['nullable', 'string', 'max:100'],
                'actual_owpa_to_date' => ['nullable', 'string', 'max:100'],
                'slippage' => ['nullable', 'string', 'max:100'],
                'output_indicator_1' => ['nullable', 'string', 'max:255'],
                'output_indicator_2' => ['nullable', 'string', 'max:255'],
                'output_indicator_3' => ['nullable', 'string', 'max:255'],
                'output_indicator_4' => ['nullable', 'string', 'max:255'],
                'output_indicator_5' => ['nullable', 'string', 'max:255'],
                'end_of_project_target' => ['nullable', 'date'],
                'target_to_date' => ['nullable', 'date'],
                'actual_to_date' => ['nullable', 'date'],
                'employment_generated_male' => ['nullable', 'integer', 'min:0'],
                'employment_generated_female' => ['nullable', 'integer', 'min:0'],
                'remarks' => ['nullable', 'string'],
            ], $signatoryRules),
            'form_3' => array_merge([
                'implementing_agency' => ['nullable', 'string', 'max:255'],
                'implementing_agency_type' => ['nullable', 'string', 'max:255'],
                'sector' => ['nullable', 'string', 'max:255'],
                'findings' => ['nullable', 'string'],
                'typology' => ['nullable', 'string', 'max:255'],
                'issue_status' => ['nullable', 'string', 'max:255'],
                'reasons' => ['nullable', 'string'],
                'actions_taken' => ['nullable', 'string'],
                'actions_to_be_taken' => ['nullable', 'string'],
            ], $signatoryRules),
            'form_4' => array_merge([
                'implementing_agency' => ['nullable', 'string', 'max:255'],
                'program_objectives' => ['nullable', 'string', 'max:255'],
                'results_outcome_target' => ['nullable', 'string', 'max:255'],
                'observed_results' => ['nullable', 'string'],
            ], $signatoryRules),
            'form_5' => array_merge([
                'fund_source' => ['nullable', 'string', 'max:255'],
                'funding_agency' => ['nullable', 'string', 'max:255'],
                'total_project_cost' => ['nullable', 'string', 'max:100'],
                'appropriations' => ['nullable', 'string', 'max:100'],
                'allotment' => ['nullable', 'string', 'max:100'],
                'obligations' => ['nullable', 'string', 'max:100'],
                'disbursements' => ['nullable', 'string', 'max:100'],
                'funding_support' => ['nullable', 'string', 'max:100'],
                'fund_utilization' => ['nullable', 'string', 'max:100'],
                'target_owpa_to_date' => ['nullable', 'string', 'max:100'],
                'actual_owpa_to_date' => ['nullable', 'string', 'max:100'],
                'slippage' => ['nullable', 'string', 'max:100'],
                'employment_generated_male' => ['nullable', 'integer', 'min:0'],
                'employment_generated_female' => ['nullable', 'integer', 'min:0'],
                'remarks' => ['nullable', 'string'],
            ], $signatoryRules),
            'form_6' => array_merge([
                'implementing_agency' => ['nullable', 'string', 'max:255'],
                'fund_utilization' => ['nullable', 'string', 'max:100'],
                'target_owpa_to_date' => ['nullable', 'string', 'max:100'],
                'actual_owpa_to_date' => ['nullable', 'string', 'max:100'],
                'slippage' => ['nullable', 'string', 'max:100'],
                'issue_details' => ['nullable', 'string'],
                'issue_typology' => ['nullable', 'string'],
                'issue_status' => ['nullable', 'string'],
                'source_of_information' => ['nullable', 'string'],
                'actions_taken' => ['nullable', 'string'],
                'actions_to_be_taken' => ['nullable', 'string'],
                'for_npmc_action' => ['nullable', 'in:Yes,No'],
                'requested_actions_from_npmc' => ['nullable', 'string'],
            ], $signatoryRules),
            'form_7' => array_merge([
                'total_project_cost' => ['nullable', 'string', 'max:100'],
                'implementing_agency' => ['nullable', 'string', 'max:255'],
                'inspection_date' => ['nullable', 'date'],
                'site_details' => ['nullable', 'string'],
                'findings' => ['nullable', 'string'],
                'issues' => ['nullable', 'string'],
                'actions_taken' => ['nullable', 'string'],
                'actions_to_be_taken' => ['nullable', 'string'],
            ], $signatoryRules),
            'form_8' => array_merge([
                'issue_details' => ['nullable', 'string'],
                'issue_typology' => ['nullable', 'string'],
                'implementing_agency' => ['nullable', 'string', 'max:255'],
                'meeting_date' => ['nullable', 'date'],
                'concerned_agencies' => ['nullable', 'string'],
                'agreements_reached' => ['nullable', 'string'],
            ], $signatoryRules),
            'form_9' => array_merge([
                'training_title' => ['nullable', 'string', 'max:255'],
                'training_objective' => ['nullable', 'string'],
                'training_date' => ['nullable', 'date'],
                'participation_type' => ['nullable', 'string', 'max:255'],
                'lead_office' => ['nullable', 'string', 'max:255'],
                'participating_offices' => ['nullable', 'string'],
                'participants_male' => ['nullable', 'integer', 'min:0'],
                'participants_female' => ['nullable', 'integer', 'min:0'],
                'participants_total' => ['nullable', 'integer', 'min:0'],
                'results_feedback' => ['nullable', 'string'],
            ], $signatoryRules),
            'form_10' => array_merge([
                'resolution_number' => ['nullable', 'integer', 'min:0'],
                'resolution_title' => ['nullable', 'string', 'max:255'],
                'date_approved' => ['nullable', 'date'],
                'resolution' => ['nullable', 'string'],
                'resolution_link' => ['nullable', 'string', 'max:2048'],
            ], $signatoryRules),
            'form_11' => array_merge([
                'implementing_agency' => ['nullable', 'in:LGU'],
                'problem_nature' => ['nullable', 'string'],
                'problem_details' => ['nullable', 'string'],
                'strategies_actions_taken' => ['nullable', 'string'],
                'responsible_entities_assistance' => ['nullable', 'string'],
                'lessons_learned_best_practices' => ['nullable', 'string'],
            ], $signatoryRules),
        };
    }

    private function formTitle(string $type): string
    {
        return match ($type) {
            'form_1' => 'Initial Project Report',
            'form_2' => 'Physical and Financial Accomplishment Report',
            'form_3' => 'Project Exception Report',
            'form_4' => 'Project Results',
            'form_5' => 'Summary of Financial and Physical Accomplishments',
            'form_6' => 'Report on the Status of Projects Encountering Implementation Problems',
            'form_7' => 'Project Inspection Report',
            'form_8' => 'Problem Solving Sessions/Facilitation Meeting Conducted',
            'form_9' => 'Training/Workshop Conducted/Facilitated/Attended by the PMC',
            'form_10' => 'RPMC and RDC Resolutions Related to Implementation of the RPMES',
            'form_11' => 'Key Lessons Learned from Issues Resolved and Best Practices',
        };
    }
}
