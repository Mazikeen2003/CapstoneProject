<?php

namespace App\Http\Controllers\BarangayOfficial;

use App\Http\Controllers\Controller;
use App\Services\ReportService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        return view('barangay-official.reports.index', $this->reportFilterData($request));
    }

    public function projectsPdf(Request $request)
    {
        $this->authorize('viewAny', \App\Models\Project::class);

        $data = ReportService::generateProjectReport($this->reportFilters($request));
        $barangayName = Auth::user()->barangay?->barangay_name ?? 'Unknown';

        $pdf = Pdf::loadView('reports.projects-pdf', $data);

        return $pdf->download('barangay_' . strtolower(str_replace(' ', '_', $barangayName)) . '_projects_' . now()->format('Y-m-d') . '.pdf');
    }

    public function budgetPdf(Request $request)
    {
        $this->authorize('viewAny', \App\Models\Project::class);

        $data = ReportService::generateBudgetReport($this->reportFilters($request));
        $barangayName = Auth::user()->barangay?->barangay_name ?? 'Unknown';

        $pdf = Pdf::loadView('reports.budget-pdf', $data);

        return $pdf->download('barangay_' . strtolower(str_replace(' ', '_', $barangayName)) . '_budget_' . now()->format('Y-m-d') . '.pdf');
    }

    private function reportFilterData(Request $request): array
    {
        return [...ReportService::reportFilterOptions(), 'reportFilters' => $this->reportFilters($request)];
    }

    private function reportFilters(Request $request): array
    {
        return $request->validate([
            'project_id' => ['nullable', 'integer'],
        ]);
    }
}
