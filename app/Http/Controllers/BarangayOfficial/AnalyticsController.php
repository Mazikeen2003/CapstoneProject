<?php

namespace App\Http\Controllers\BarangayOfficial;

use App\Http\Controllers\Controller;

class AnalyticsController extends Controller
{
    public function index()
    {
        return view('barangay-official.analytics');
    }
}