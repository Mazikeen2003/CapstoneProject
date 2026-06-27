<?php

namespace App\Http\Controllers\Department;

use App\Http\Controllers\Controller; // Add this
use App\Services\ReportService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests; // Add this trait
use Illuminate\Support\Facades\Auth; // Add if you need Auth in the future

class ReportController extends Controller // Extend Controller
{
    use AuthorizesRequests; // Add this trait

    public function index()
    {
        return view('department.reports.index');
    }

    public function projectsPdf()
    {
        $this->authorize('viewAny', \App\Models\Project::class);

        $data = ReportService::generateProjectReport();

        $pdf = Pdf::loadView('reports.projects-pdf', $data);

        return $pdf->download('department_projects_' . now()->format('Y-m-d') . '.pdf');
    }

    public function budgetPdf()
    {
        $this->authorize('viewAny', \App\Models\Project::class);

        $data = ReportService::generateBudgetReport();

        $pdf = Pdf::loadView('reports.budget-pdf', $data);

        return $pdf->download('department_budget_' . now()->format('Y-m-d') . '.pdf');
    }
}