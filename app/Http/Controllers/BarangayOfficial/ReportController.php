<?php

namespace App\Http\Controllers\BarangayOfficial;

use App\Http\Controllers\Controller;
use App\Services\ReportService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    public function index()
    {
        return view('barangay-official.reports.index');
    }

    public function projectsPdf()
    {
        $this->authorize('viewAny', \App\Models\Project::class);

        $data = ReportService::generateProjectReport();
        $barangayName = Auth::user()->barangay?->barangay_name ?? 'Unknown';

        $pdf = Pdf::loadView('reports.projects-pdf', $data);

        return $pdf->download('barangay_' . strtolower(str_replace(' ', '_', $barangayName)) . '_projects_' . now()->format('Y-m-d') . '.pdf');
    }

    public function budgetPdf()
    {
        $this->authorize('viewAny', \App\Models\Project::class);

        $data = ReportService::generateBudgetReport();
        $barangayName = Auth::user()->barangay?->barangay_name ?? 'Unknown';

        $pdf = Pdf::loadView('reports.budget-pdf', $data);

        return $pdf->download('barangay_' . strtolower(str_replace(' ', '_', $barangayName)) . '_budget_' . now()->format('Y-m-d') . '.pdf');
    }
}