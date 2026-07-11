<?php

namespace App\Http\Controllers\CityOfficial;

use App\Http\Controllers\Controller;
use App\Models\Project;

class MapController
{
    public function index()
    {
        $projects = Project::withoutRoleScope()
            ->withBasicRelations()
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->get();

        return view('city-official.map.index', compact('projects'));
    }
}