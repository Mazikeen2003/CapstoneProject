<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Services\PortalVisitService;
use App\Services\ReportService;

class TransparencyController extends Controller
{
    public function index()
    {
        PortalVisitService::logVisit('transparency');

        $report = ReportService::generateSglgComplianceReport('Public Portal');

        $summary = $report['summary'] ?? [];
        $byBarangay = $report['by_barangay'] ?? collect();

        $metrics = [
            'documentation_rate' => $summary['documentation_rate'] ?? 0,
            'up_to_date_rate' => $summary['up_to_date_rate'] ?? 0,
            'transparency_rate' => $summary['transparency_rate'] ?? 0,
            'completion_rate' => $summary['completion_rate'] ?? 0,
        ];

        return view('public.transparency.index', compact('metrics', 'byBarangay'));
    }
}
