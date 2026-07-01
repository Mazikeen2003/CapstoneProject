<?php

namespace App\Http\Controllers\BarangayOfficial;

use App\Http\Controllers\Controller;
use App\Models\Project;

class ProjectController extends Controller
{
    public function index()
    {
        // Paginate results with eager loading (read-only view)
        $projects = Project::withBasicRelations()
            ->latest('created_at')
            ->paginate(15);

        return view('barangay-official.projects.index', compact('projects'));
    }

    public function show(Project $project)
    {
        $this->authorize('view', $project);

        // Eager load all relations
        $project = Project::withRelations()
            ->findOrFail($project->project_id);

        return view('barangay-official.projects.show', compact('project'));
    }
}