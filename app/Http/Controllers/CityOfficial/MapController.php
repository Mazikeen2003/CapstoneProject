<?php

namespace App\Http\Controllers\CityOfficial;

use App\Http\Controllers\Controller;

class MapController extends Controller
{
    public function index()
    {
        return view('city-official.map.index');
    }
}