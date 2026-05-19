<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;

class MapController extends Controller
{
    public function index()
    {
        $projects = Project::all();
        
        // Mock data for demonstration (remove this when you have real data)
        $mockProjects = [
            [
                'id' => 1,
                'name' => 'Barangay Hall Renovation',
                'barangay' => 'Barangay 1',
                'status' => 'ongoing',
                'lat' => 14.2749,
                'lng' => 121.1245,
                'budget' => 2500000,
                'start_date' => '2024-01-15',
                'completion' => '85%'
            ],
            [
                'id' => 2,
                'name' => 'Road Concreting Project',
                'barangay' => 'Barangay 2',
                'status' => 'completed',
                'lat' => 14.2800,
                'lng' => 121.1300,
                'budget' => 5000000,
                'start_date' => '2023-06-01',
                'completion' => '100%'
            ],
            [
                'id' => 3,
                'name' => 'New School Building',
                'barangay' => 'Barangay 3',
                'status' => 'planning',
                'lat' => 14.2650,
                'lng' => 121.1180,
                'budget' => 8000000,
                'start_date' => '2024-03-01',
                'completion' => '30%'
            ],
            [
                'id' => 4,
                'name' => 'Health Center Upgrade',
                'barangay' => 'Barangay 4',
                'status' => 'ongoing',
                'lat' => 14.2900,
                'lng' => 121.1400,
                'budget' => 3200000,
                'start_date' => '2024-02-10',
                'completion' => '60%'
            ],
            [
                'id' => 5,
                'name' => 'Flood Control System',
                'barangay' => 'Barangay 5',
                'status' => 'ongoing',
                'lat' => 14.2600,
                'lng' => 121.1100,
                'budget' => 15000000,
                'start_date' => '2024-01-20',
                'completion' => '45%'
            ]
        ];
        
        return view('public.map', compact('mockProjects'));
    }
}