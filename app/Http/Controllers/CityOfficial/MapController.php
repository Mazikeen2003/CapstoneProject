<?php

namespace App\Http\Controllers\CityOfficial;

use App\Http\Controllers\Controller;

class MapController
{
    public function index()
    {
        return view('department.map.index', [
            'mapLayout' => 'layouts.city',
            'mapTitle' => 'City Official Map',
            'projectsTitle' => 'Citywide Projects',
            'mapTheme' => 'city',
        ]);
    }
}
