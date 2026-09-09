<?php

namespace App\Http\Controllers\Engineering;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $projects = Project::withoutRoleScope()->withBasicRelations()->with('latestUpdate')->get();

        $stats = [
            'total_projects' => $projects->count(),
            'ongoing' => $projects->whereIn('current_status', ['Implementation', 'On Going'])->count(),
            'completed' => $projects->where('current_status', 'Completed')->count(),
            'budget_allocated' => $projects->sum('approved_budget') ?? 0,
            'budget_used' => $projects->sum('actual_budget') ?? 0,
        ];

        $recentProjects = Project::withoutRoleScope()
            ->withBasicRelations()
            ->with('latestUpdate')
            ->latest('created_at')
            ->paginate(10, ['*'], 'recent_page');

        return view('engineering.dashboard', compact('stats', 'recentProjects'));
    }
}
