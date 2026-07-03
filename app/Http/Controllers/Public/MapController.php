<?php

namespace App\Http\Controllers\Public;

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
        // Only completed and ongoing projects
        $projects = Project::withoutRoleScope()
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
                    'id'     => $project->project_id,
                    'name'   => $project->project_name,
                    'status' => $project->current_status,
                    'type'   => $project->project_type,
                    // NO budget, NO barangay_id, NO internal details
                ],
            ];
        });

        return response()->json([
            'type'     => 'FeatureCollection',
            'features' => $features,
        ]);
    }
}