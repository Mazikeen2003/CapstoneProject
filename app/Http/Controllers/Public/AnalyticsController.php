<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;

class AnalyticsController extends Controller
{
    public function index()
    {
        return view('public.analytics');
    }
}