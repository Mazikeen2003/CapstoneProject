<?php

namespace App\Http\Controllers\CityOfficial;

use App\Models\Project;

class MapController
{
    public function index()
    {
        // City sees all projects (no role scope filter)
        $projects = Project::withoutRoleScope()
            ->withBasicRelations()
            ->latest('created_at')
            ->get();

        return view('city-official.map.index', compact('projects'));
    }
}