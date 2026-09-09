<?php

namespace App\Http\Controllers\Engineering;

use App\Http\Controllers\Controller;

class MapController extends Controller
{
    public function index()
    {
        $this->authorize('viewAny', \App\Models\Project::class);

        return view('department.map.index', [
            'mapTitle' => 'Engineering Map',
            'projectsTitle' => 'Engineering Projects',
            'mapTheme' => 'engineering',
        ]);
    }
}
