<?php

namespace App\Http\Controllers\CityOfficial;

use App\Http\Controllers\Controller;
use App\Models\Project;

class ProjectController extends Controller
{
    public function index()
    {
        $projects = Project::withoutRoleScope()
            ->withBasicRelations()
            ->latest('created_at')
            ->paginate(15);

        return view('city-official.projects.index', compact('projects'));
    }

    public function show($id)
    {
        $project = Project::withoutRoleScope()
            ->withRelations()
            ->findOrFail($id);

        $this->authorize('view', $project);

        return view('city-official.projects.show', compact('project'));
    }
}
