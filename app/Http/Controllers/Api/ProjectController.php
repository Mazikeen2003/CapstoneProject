<?php

namespace App\Http\Controllers\Api;

use App\Models\Project;
use App\Services\CacheService;

class ProjectController
{
    /**
     * Return GeoJSON of projects with location data.
     * Uses eager loading and caching for performance.
     */
    public function geojson()
    {
        // Use cache service for GeoJSON (cached for 1 hour)
        $data = CacheService::getGeoJsonData();

        return response()->json($data);
    }
}