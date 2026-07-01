<?php

namespace App\Http\Controllers\Department;

use App\Http\Controllers\Controller;
use App\Models\Project;

class AnalyticsController extends Controller
{
    public function index()
    {
        $projects = Project::withBasicRelations()->get();

        $stats = [
            'total_projects'   => $projects->count(),
            'completed'        => $projects->where('current_status', 'Completed')->count(),
            'ongoing'          => $projects->where('current_status', 'On Going')->count(),
            'budget_used'      => $projects->sum('actual_budget') ?? 0,
        ];

        return view('department.analytics.index', compact('stats'));
    }
}