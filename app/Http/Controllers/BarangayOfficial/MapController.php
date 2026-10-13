<?php

namespace App\Http\Controllers\BarangayOfficial;

use App\Http\Controllers\Controller;
use App\Models\Project;

class MapController extends Controller
{
    public function index()
    {
        $this->authorize('viewAny', Project::class);

        return view('department.map.index', [
            'mapLayout' => 'layouts.barangay',
            'mapTitle' => 'Barangay Map',
            'projectsTitle' => 'Barangay Projects',
            'mapTheme' => 'barangay',
        ]);
    }
}
