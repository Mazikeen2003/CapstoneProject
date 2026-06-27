<?php

namespace App\Http\Controllers\CityOfficial;

use App\Http\Controllers\Controller;

class AnalyticsController extends Controller
{
    public function index()
    {
        return view('city-official.analytics.index');
    }
}