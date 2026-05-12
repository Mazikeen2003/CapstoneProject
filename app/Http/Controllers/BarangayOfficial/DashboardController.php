<?php

namespace App\Http\Controllers\BarangayOfficial;

use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function index()
    {
        return view('barangay-official.dashboard');
    }
}