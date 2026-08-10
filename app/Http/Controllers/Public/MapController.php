<?php

namespace App\Http\Controllers\Public;

use App\Models\Barangay;
use App\Models\Project;
use App\Models\Scopes\RoleScopedScope;
use App\Services\PortalVisitService;

class MapController
{
    /**
     * Show public map with all projects.
     * Hides budget and other internal details.
     */
    public function index()
    {
        PortalVisitService::logVisit('map');

        // Show every project on the public map, regardless of status.
        $projects = Project::withoutRoleScope()
            ->withBasicRelations()
            ->withLocation()
            ->get();

        return view('public.map', compact('projects'));
    }

    /**
     * API endpoint for public GeoJSON (limited data).
     */
        public function geojson()
    {
        // Include every project status; eager-load barangay to avoid N+1.
        $projects = Project::withoutRoleScope()
            ->with(['barangay', 'latestUpdate'])
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
                    'image'    => $project->project_image
                        ? asset('storage/' . $project->project_image)
                        : null,
                    'description' => $project->remarks ?: 'No description available.',
                    'start_date'         => $project->start_date?->toDateString(),
                    'target_end_date'    => $project->target_end_date?->toDateString(),
                    'progress_percentage' => $project->latestUpdate?->progress_percentage ?? 0,
                    'budget'             => $project->approved_budget ?? 0,
                    'actual_budget'      => $project->actual_budget ?? 0,
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
            ->withoutGlobalScope(RoleScopedScope::class)
            ->with('latestUpdate')
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
                    'image'  => $project->project_image
                        ? asset('storage/' . $project->project_image)
                        : null,
                    'description' => $project->remarks ?: 'No description available.',
                    'start_date'         => $project->start_date?->toDateString(),
                    'target_end_date'    => $project->target_end_date?->toDateString(),
                    'progress_percentage' => $project->latestUpdate?->progress_percentage ?? 0,
                    'budget'             => $project->approved_budget ?? 0,
                    'actual_budget'      => $project->actual_budget ?? 0,
                ],
            ];
        });

        return response()->json([
            'type'     => 'FeatureCollection',
            'features' => $features,
        ]);
    }
}
