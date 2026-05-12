<?php

namespace App\Http\Controllers\BarangayOfficial;

use App\Http\Controllers\Controller;

class ProjectController extends Controller
{
    public function index()
    {
        return view('barangay-official.projects.index');
    }
}