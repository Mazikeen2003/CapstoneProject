<?php

namespace App\Http\Controllers\BarangayOfficial;

use App\Http\Controllers\Controller;

class MapController extends Controller
{
    public function index()
    {
        return view('barangay-official.map');
    }
}