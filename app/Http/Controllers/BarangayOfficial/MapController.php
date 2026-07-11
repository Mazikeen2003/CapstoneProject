<?php

namespace App\Http\Controllers\BarangayOfficial;

use App\Http\Controllers\Controller;
use App\Models\Project;

class MapController extends Controller
{
    public function index()
    {
        // Barangay sees only their projects (global scope applied)
        $projects = Project::withBasicRelations()
            ->latest('created_at')
            ->get();

        return view('barangay-official.map.index', compact('projects'));
    }
}