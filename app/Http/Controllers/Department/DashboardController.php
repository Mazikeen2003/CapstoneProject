<?php

namespace App\Http\Controllers\Department;

use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function index()
    {
        return view('department.dashboard');
    }
}