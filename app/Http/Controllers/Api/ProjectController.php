<?php

namespace App\Http\Controllers\Api;

use App\Services\CacheService;
use Illuminate\Http\Request;

class ProjectController
{
    /**
     * Return GeoJSON of projects with location data.
     * Uses eager loading and caching for performance.
     * Accepts ?public=1 to force an unscoped public dataset.
     */
    public function geojson(Request $request)
    {
        $forcePublic = (bool) $request->query('public');

        $data = CacheService::getGeoJsonData(auth()->user(), $forcePublic);

        return response()->json($data);
    }
}