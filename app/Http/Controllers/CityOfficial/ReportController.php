<?php

namespace App\Http\Controllers\CityOfficial;

use App\Http\Controllers\Controller;

class ReportController extends Controller
{
    public function index()
    {
        return view('city-official.reports.index');
    }
}