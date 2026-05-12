<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;

class MapController extends Controller
{
    public function index()
    {
        return view('public.map');
    }
}