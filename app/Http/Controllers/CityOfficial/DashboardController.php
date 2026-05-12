<?php

namespace App\Http\Controllers\CityOfficial;

use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function index()
    {
        return view('city-official.dashboard');
    }
}