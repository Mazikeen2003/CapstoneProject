<?php

namespace App\Http\Controllers\CityOfficial;

use App\Http\Controllers\Controller;
use App\Services\ReportService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        return view('city-official.reports.index', $this->reportFilterData($request));
    }

    public function projectsPdf(Request $request)
    {
        $this->authorize('viewAny', \App\Models\Project::class);

        $data = ReportService::generateProjectReport($this->reportFilters($request));

        $pdf = Pdf::loadView('reports.projects-pdf', $data);

        return $pdf->download('citywide_projects_' . now()->format('Y-m-d') . '.pdf');
    }

    public function budgetPdf(Request $request)
    {
        $this->authorize('viewAny', \App\Models\Project::class);

        $data = ReportService::generateBudgetReport($this->reportFilters($request));

        $pdf = Pdf::loadView('reports.budget-pdf', $data);

        return $pdf->download('citywide_budget_' . now()->format('Y-m-d') . '.pdf');
    }

    public function sglgPdf(Request $request)
    {
        $this->authorize('viewAny', \App\Models\Project::class);

        $data = ReportService::generateSglgComplianceReport(null, $this->reportFilters($request));

        $pdf = Pdf::loadView('reports.sglg-pdf', $data);

        return $pdf->download('citywide_sglg_compliance_' . now()->format('Y-m-d') . '.pdf');
    }

    private function reportFilterData(Request $request): array
    {
        return [...ReportService::reportFilterOptions(), 'reportFilters' => $this->reportFilters($request)];
    }

    private function reportFilters(Request $request): array
    {
        return $request->validate([
            'barangay_id' => ['nullable', 'integer'],
            'project_id' => ['nullable', 'integer'],
        ]);
    }
}
