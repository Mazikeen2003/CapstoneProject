<?php

namespace App\Http\Controllers\Department;

use App\Http\Controllers\Controller;

class ReportExportController extends Controller
{
    public function index()
    {
        return view('department.reports.index');
    }
}