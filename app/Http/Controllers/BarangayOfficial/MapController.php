<?php

namespace App\Http\Controllers\BarangayOfficial;

use App\Http\Controllers\Controller;

class MapController extends Controller
{
    public function index()
    {
        return view('department.map.index', [
            'mapLayout' => 'layouts.barangay',
            'mapTitle' => 'Barangay Map',
            'projectsTitle' => 'Barangay Projects',
            'mapTheme' => 'barangay',
        ]);
    }
}
