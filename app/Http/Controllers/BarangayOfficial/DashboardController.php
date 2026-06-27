<?php

namespace App\Http\Controllers\BarangayOfficial;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Global scope automatically filters to only this barangay
        // Use eager loading to avoid N+1 queries
        $projects = Project::withBasicRelations()->get();

        $stats = [
            'total_projects'   => $projects->count(),
            'ongoing'          => $projects->where('current_status', 'On Going')->count(),
            'completed'        => $projects->where('current_status', 'Completed')->count(),
            'budget_allocated' => $projects->sum('approved_budget') ?? 0,
            'budget_used'      => $projects->sum('actual_budget') ?? 0,
        ];

        $recentProjects = $projects->sortByDesc('created_at')->take(5);
        $barangayName = $user->barangay?->barangay_name ?? 'Unknown';

        return view('barangay-official.dashboard', compact('stats', 'recentProjects', 'barangayName'));
    }
}