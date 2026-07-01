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
        // Use cache service for GeoJSON (cached for admin/city/public only)
        $data = CacheService::getGeoJsonData(auth()->user());

        return response()->json($data);
    }
}