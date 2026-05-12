<?php

namespace App\Http\Controllers\Department;

use App\Http\Controllers\Controller;

class MapController extends Controller
{
    public function index()
    {
        return view('department.map.index');
    }
}