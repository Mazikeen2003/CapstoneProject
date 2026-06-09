<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Project;

class MapController extends Controller
{
    public function index()
    {
        $projects = Project::all();

        return view('public.map', compact('projects'));
    }
}
