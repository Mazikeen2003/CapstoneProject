<?php

namespace App\Http\Controllers\Engineering;

use App\Http\Controllers\Controller;
use App\Services\ReportService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class ReportController extends Controller
{
    use AuthorizesRequests;

    public function index()
    {
        $this->authorize('viewAny', \App\Models\Project::class);
        $this->authorize('generateReports', \App\Models\Project::class);

        return view('engineering.reports.index');
    }

    public function projectsPdf()
    {
        $this->authorize('viewAny', \App\Models\Project::class);
        $this->authorize('generateReports', \App\Models\Project::class);

        $data = ReportService::generateProjectReport();
        $pdf = Pdf::loadView('reports.projects-pdf', $data);

        return $pdf->download('engineering_projects_' . now()->format('Y-m-d') . '.pdf');
    }

    public function budgetPdf()
    {
        $this->authorize('viewAny', \App\Models\Project::class);
        $this->authorize('generateReports', \App\Models\Project::class);

        $data = ReportService::generateBudgetReport();
        $pdf = Pdf::loadView('reports.budget-pdf', $data);

        return $pdf->download('engineering_budget_' . now()->format('Y-m-d') . '.pdf');
    }

    public function sglgPdf()
    {
        $this->authorize('viewAny', \App\Models\Project::class);
        $this->authorize('generateReports', \App\Models\Project::class);

        $data = ReportService::generateSglgComplianceReport();
        $pdf = Pdf::loadView('reports.sglg-pdf', $data);

        return $pdf->download('engineering_sglg_compliance_' . now()->format('Y-m-d') . '.pdf');
    }
}
