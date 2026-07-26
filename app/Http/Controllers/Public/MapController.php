<?php

namespace App\Http\Controllers\Public;

use App\Models\Barangay;
use App\Models\Project;

class MapController
{
    /**
     * Show public map with only completed and ongoing projects.
     * Hides budget, barangay assignment, and internal details.
     */
    public function index()
    {
        // Only show completed and ongoing projects on public map
        $projects = Project::withoutRoleScope()
            ->withBasicRelations()
            ->whereIn('current_status', ['Completed', 'On Going'])
            ->withLocation()
            ->get();

        return view('public.map', compact('projects'));
    }

    /**
     * API endpoint for public GeoJSON (limited data).
     */
        public function geojson()
    {
        // Only completed and ongoing projects, eager-load barangay to avoid N+1
        $projects = Project::withoutRoleScope()
            ->with('barangay')
            ->whereIn('current_status', ['Completed', 'On Going'])
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->get();

        $features = $projects->map(function ($project) {
            return [
                'type'       => 'Feature',
                'geometry'   => [
                    'type'        => 'Point',
                    'coordinates' => [$project->longitude, $project->latitude],
                ],
                'properties' => [
                    'id'       => $project->project_id,
                    'name'     => $project->project_name,
                    'status'   => $project->current_status,
                    'type'     => $project->project_type,
                    'barangay' => $project->barangay?->barangay_name ?? 'Citywide',
                    // Still intentionally NO budget, NO barangay_id, NO internal details
                ],
            ];
        });

        return response()->json([
            'type'     => 'FeatureCollection',
            'features' => $features,
        ]);
    }

    /**
     * API endpoint for barangay pins GeoJSON.
     */

    public function barangaysGeojson()
    {
        $barangays = Barangay::query()
            ->withPublicProjectCount()
            ->whereNotNull('boundary_geojson')
            ->get();

        $features = $barangays->map(function (Barangay $barangay) {
            return [
                'type'       => 'Feature',
                'geometry'   => $barangay->boundary_geojson,
                'properties' => [
                    'barangay_id'   => $barangay->barangay_id,
                    'name'          => $barangay->barangay_name,
                    'project_count' => $barangay->public_project_count,
                ],
            ];
        });

        return response()->json([
            'type'     => 'FeatureCollection',
            'features' => $features,
        ]);
    }

    /**
     * API endpoint for projects within a specific barangay.
     */
    
    public function projectsForBarangay(Barangay $barangay)
    {
        $projects = $barangay->projects()
            ->whereIn('current_status', ['Completed', 'On Going'])
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->get();

        $features = $projects->map(function (Project $project) {
            return [
                'type' => 'Feature',
                'geometry' => [
                    'type'        => 'Point',
                    'coordinates' => [$project->longitude, $project->latitude],
                ],
                'properties' => [
                    'id'     => $project->project_id,
                    'name'   => $project->project_name,
                    'status' => $project->current_status,
                    'type'   => $project->project_type,
                ],
            ];
        });

        return response()->json([
            'type'     => 'FeatureCollection',
            'features' => $features,
        ]);
    }
}