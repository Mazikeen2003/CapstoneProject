<?php

namespace App\Http\Controllers\Department;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\ProjectForm;
use App\Services\AuditLogService;
use Barryvdh\DomPDF\Facade\Pdf;
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
            'sections' => $this->sectionsFor($type),
            'number' => (int) str_replace('form_', '', $type),
            'title' => $this->formTitle($type),
            'formType' => $type,
        ]);
    }

    public function pdf(Project $project, string $type)
    {
        $this->authorize('view', $project);

        abort_unless(in_array($type, ['form_1', 'form_2', 'form_3', 'form_4', 'form_5', 'form_6', 'form_7', 'form_8', 'form_9', 'form_10', 'form_11'], true), 404);

        $form = ProjectForm::where('project_id', $project->project_id)
            ->where('form_type', $type)
            ->firstOrFail();

        $data = [
            'project' => $project,
            'form' => $form,
            'form_title' => $this->formTitle($type),
            'field_labels' => $this->fieldLabelsFor($type),
            'form_number' => str_replace('form_', '', $type),
        ];

        return Pdf::loadView('reports.project-form-pdf', $data)->download(
            'project_' . $project->project_code . '_form_' . $data['form_number'] . '_' . now()->format('Y-m-d') . '.pdf'
        );
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

    private function fieldLabelsFor(string $type): array
    {
        $signatories = [
            'submitted_by' => 'Submitted By', 'submitted_designation' => 'Submitted Designation/Office', 'submitted_date' => 'Submitted Date',
            'approved_by' => 'Approved By', 'approved_designation' => 'Approved Designation/Office', 'approved_date' => 'Approved Date',
        ];

        if (in_array($type, ['form_5', 'form_6', 'form_7', 'form_8', 'form_9', 'form_10', 'form_11'], true)) {
            $labels = [];
            foreach ($this->sectionsFor($type) as $section) {
                foreach ($section['fields'] as $field => $definition) {
                    $labels[$field] = $definition['label'] ?? $field;
                }
            }

            return array_merge($labels, $signatories);
        }

        $labels = match ($type) {
            'form_1' => [
                'implementing_agency' => '1. Implementing Agency', 'component_details' => '3. Component Details', 'fund_source' => '4. Fund Source', 'funding_agency' => '5. Funding Agency', 'mode_of_implementation' => '6. Mode of Implementation', 'total_project_cost' => '7. Total Program/Project Cost (PHP)', 'sector' => '8. Sector', 'remarks' => '12. Remarks', 'target_employment_male' => '13. Target Employment Generated - Male', 'target_employment_female' => '13. Target Employment Generated - Female', 'output_indicator_1' => 'Output Indicator 1', 'output_indicator_2' => 'Output Indicator 2', 'output_indicator_3' => 'Output Indicator 3', 'output_indicator_4' => 'Output Indicator 4', 'output_indicator_5' => 'Output Indicator 5',
            ],
            'form_2' => [
                'implementing_agency' => 'Implementing Agency', 'start_date' => 'Implementation Start Date', 'end_date' => 'Implementation End Date', 'fund_source' => 'Fund Source', 'funding_agency' => 'Funding Agency', 'total_project_cost' => 'Total Project Cost (PHP)', 'appropriations' => 'Appropriations', 'allotment' => 'Allotment', 'obligations' => 'Obligations', 'disbursements' => 'Disbursements', 'target_owpa_to_date' => 'Target OWPA to Date', 'actual_owpa_to_date' => 'Actual OWPA to Date', 'slippage' => 'Slippage', 'output_indicator_1' => 'Output Indicator 1', 'output_indicator_2' => 'Output Indicator 2', 'output_indicator_3' => 'Output Indicator 3', 'output_indicator_4' => 'Output Indicator 4', 'output_indicator_5' => 'Output Indicator 5', 'end_of_project_target' => 'End of Project Target', 'target_to_date' => 'Target to Date', 'actual_to_date' => 'Actual to Date', 'employment_generated_male' => 'Employment Generated - Male', 'employment_generated_female' => 'Employment Generated - Female', 'remarks' => 'Remarks',
            ],
            'form_3' => [
                'implementing_agency' => 'Implementing Agency/NGOs/Concerned Citizens', 'implementing_agency_type' => 'Implementing Agency Type', 'sector' => 'Sector', 'findings' => 'Findings', 'typology' => 'Typology', 'issue_status' => 'Issue Status', 'reasons' => 'Reasons', 'actions_taken' => 'Actions Taken', 'actions_to_be_taken' => 'Actions to be Taken',
            ],
            'form_4' => [
                'implementing_agency' => 'Implementing Agency', 'program_objectives' => 'Program Objectives', 'results_outcome_target' => 'Results/Outcome Target', 'observed_results' => 'Observed Results',
            ],
        };

        return array_merge($labels, $signatories);
    }

    private function sectionsFor(string $type): array
    {
        $fundSources = ['ODA Loan', 'ODA Grant', 'ODA Loan and Grant', 'LFP', 'PPP', 'NTA', 'Local Development Fund'];

        return match ($type) {
            'form_5' => [
                ['title' => 'Project and Financial Information', 'fields' => ['project_title' => ['type' => 'project', 'label' => 'Program/Project Title'], 'fund_source' => ['type' => 'select', 'label' => 'Fund Source', 'options' => $fundSources], 'funding_agency' => ['label' => 'Funding Agency'], 'total_project_cost' => ['label' => 'Total Program/Project Cost (PHP)'], 'appropriations' => ['label' => 'Appropriations (PHP)'], 'allotment' => ['label' => 'Allotment (PHP)'], 'obligations' => ['label' => 'Obligations (PHP)'], 'disbursements' => ['label' => 'Disbursements (PHP)'], 'funding_support' => ['label' => 'Funding Support (%)'], 'fund_utilization' => ['label' => 'Fund Utilization (%)']]],
                ['title' => 'Physical Accomplishment', 'fields' => ['target_owpa_to_date' => ['label' => 'Target OWPA to Date (%)'], 'actual_owpa_to_date' => ['label' => 'Actual OWPA to Date (%)'], 'slippage' => ['label' => 'Slippage'], 'employment_generated_male' => ['type' => 'number', 'label' => 'Employment Generated - Male'], 'employment_generated_female' => ['type' => 'number', 'label' => 'Employment Generated - Female'], 'remarks' => ['type' => 'textarea', 'label' => 'Remarks', 'wide' => true]]],
            ],
            'form_6' => [
                ['title' => 'Project Status', 'fields' => ['project_title' => ['type' => 'project', 'label' => 'Program/Project Title'], 'location' => ['type' => 'location', 'label' => 'Location (Barangay, City/Municipality, Province)'], 'implementing_agency' => ['type' => 'select', 'label' => 'Implementing Agency', 'options' => ['LGU']], 'fund_utilization' => ['label' => 'Fund Utilization (%)']]],
                ['title' => 'Physical Accomplishment and Issues', 'fields' => ['target_owpa_to_date' => ['label' => 'Target OWPA to Date (%)'], 'actual_owpa_to_date' => ['label' => 'Actual OWPA to Date (%)'], 'slippage' => ['label' => 'Slippage'], 'issue_details' => ['type' => 'textarea', 'label' => 'Issue Details'], 'issue_typology' => ['type' => 'textarea', 'label' => 'Issue Typology'], 'issue_status' => ['label' => 'Issue Status'], 'source_of_information' => ['type' => 'textarea', 'label' => 'Source of Information'], 'actions_taken' => ['type' => 'textarea', 'label' => 'Actions Taken'], 'actions_to_be_taken' => ['type' => 'textarea', 'label' => 'Actions to be Taken'], 'for_npmc_action' => ['type' => 'select', 'label' => 'For NPMC Action', 'options' => ['Yes', 'No']], 'requested_actions_from_npmc' => ['type' => 'textarea', 'label' => 'Requested Actions from the NPMC', 'wide' => true]]],
            ],
            'form_7' => [
                ['title' => 'Project Inspection Details', 'fields' => ['project_title' => ['type' => 'project', 'label' => 'Program/Project Title'], 'total_project_cost' => ['label' => 'Total Program/Project Cost (PHP)'], 'location' => ['type' => 'location', 'label' => 'Location (Barangay, City/Municipality, Province)'], 'implementing_agency' => ['type' => 'select', 'label' => 'Implementing Agency', 'options' => ['LGU']], 'inspection_date' => ['type' => 'date', 'label' => 'Date of Project Inspection'], 'site_details' => ['type' => 'textarea', 'label' => 'Details on Site(s) Inspected', 'wide' => true]]],
                ['title' => 'Findings and Actions', 'fields' => ['findings' => ['type' => 'textarea', 'label' => 'Findings'], 'issues' => ['type' => 'textarea', 'label' => 'Issues'], 'actions_taken' => ['type' => 'textarea', 'label' => 'Actions Taken'], 'actions_to_be_taken' => ['type' => 'textarea', 'label' => 'Actions to be Taken']]],
            ],
            'form_8' => [[ 'title' => 'Meeting Details', 'fields' => ['project_title' => ['type' => 'project', 'label' => 'Program/Project Title'], 'issue_details' => ['type' => 'textarea', 'label' => 'Issue Details'], 'issue_typology' => ['type' => 'textarea', 'label' => 'Issue Typology'], 'location' => ['type' => 'location', 'label' => 'Location (Barangay, City/Municipality, Province)'], 'implementing_agency' => ['type' => 'select', 'label' => 'Implementing Agency', 'options' => ['LGU']], 'meeting_date' => ['type' => 'date', 'label' => 'Date of Meeting'], 'concerned_agencies' => ['type' => 'textarea', 'label' => 'Concerned Agencies'], 'agreements_reached' => ['type' => 'textarea', 'label' => 'Agreements Reached']]]],
            'form_9' => [
                ['title' => 'Training/Workshop Details', 'fields' => ['training_title' => ['label' => 'Title of Training/Workshop'], 'training_objective' => ['type' => 'textarea', 'label' => 'Objective of the Training/Workshop'], 'training_date' => ['type' => 'date', 'label' => 'Date'], 'participation_type' => ['label' => 'Conducted/Facilitated/Attended'], 'lead_office' => ['label' => 'Lead Office/Unit'], 'participating_offices' => ['type' => 'textarea', 'label' => 'Participating Offices/Agencies/Organizations', 'wide' => true]]],
                ['title' => 'Participants and Results', 'fields' => ['participants_male' => ['type' => 'number', 'label' => 'Total Participants - Male'], 'participants_female' => ['type' => 'number', 'label' => 'Total Participants - Female'], 'participants_total' => ['type' => 'number', 'label' => 'Total Participants', 'readonly' => true], 'results_feedback' => ['type' => 'textarea', 'label' => 'Results and Feedback', 'wide' => true]]],
            ],
            'form_10' => [[ 'title' => 'Resolution Details', 'fields' => ['resolution_number' => ['type' => 'number', 'label' => 'Resolution Number'], 'resolution_title' => ['label' => 'Resolution Title'], 'date_approved' => ['type' => 'date', 'label' => 'Date Approved'], 'resolution' => ['type' => 'textarea', 'label' => 'Resolution', 'wide' => true], 'resolution_link' => ['label' => 'Link to the Resolution', 'wide' => true]]]],
            'form_11' => [
                ['title' => 'Project Information', 'fields' => ['project_title' => ['type' => 'project', 'label' => 'Program/Project Title'], 'location' => ['type' => 'location', 'label' => 'Location (Barangay, City/Municipality, Province)'], 'implementing_agency' => ['type' => 'select', 'label' => 'Implementing Agency', 'options' => ['LGU']]]],
                ['title' => 'Problem/Issue and Lessons Learned', 'fields' => ['problem_nature' => ['type' => 'textarea', 'label' => 'Problem/Issue - Nature'], 'problem_details' => ['type' => 'textarea', 'label' => 'Problem/Issue - Details'], 'strategies_actions_taken' => ['type' => 'textarea', 'label' => 'Strategies/Actions Taken to Resolve the Problem/Issue', 'wide' => true], 'responsible_entities_assistance' => ['type' => 'textarea', 'label' => 'Responsible Entity/Key Actors and their Specific Assistance', 'wide' => true], 'lessons_learned_best_practices' => ['type' => 'textarea', 'label' => 'Lessons Learned and Good Practices that Could Be Shared to the NPMC/Other PMCs', 'wide' => true]]],
            ],
            default => [],
        };
    }
}
