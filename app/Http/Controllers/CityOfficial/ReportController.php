<?php

namespace App\Http\Controllers\CityOfficial;

use App\Http\Controllers\Controller;
use App\Services\ReportService;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller
{
    public function index()
    {
        return view('city-official.reports.index');
    }

    public function projectsPdf()
    {
        $this->authorize('viewAny', \App\Models\Project::class);

        $data = ReportService::generateProjectReport();

        $pdf = Pdf::loadView('reports.projects-pdf', $data);

        return $pdf->download('citywide_projects_' . now()->format('Y-m-d') . '.pdf');
    }

    public function budgetPdf()
    {
        $this->authorize('viewAny', \App\Models\Project::class);

        $data = ReportService::generateBudgetReport();

        $pdf = Pdf::loadView('reports.budget-pdf', $data);

        return $pdf->download('citywide_budget_' . now()->format('Y-m-d') . '.pdf');
    }

    public function sglgPdf()
    {
        $this->authorize('viewAny', \App\Models\Project::class);

        $data = ReportService::generateSglgComplianceReport();

        $pdf = Pdf::loadView('reports.sglg-pdf', $data);

        return $pdf->download('citywide_sglg_compliance_' . now()->format('Y-m-d') . '.pdf');
    }
}