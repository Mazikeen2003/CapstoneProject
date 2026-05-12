<?php

namespace App\Http\Controllers\Department;

use App\Http\Controllers\Controller;

class AnalyticsController extends Controller
{
    public function index()
    {
        return view('department.analytics.index');
    }
}