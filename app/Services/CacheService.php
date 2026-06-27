<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use App\Models\Project;

class CacheService
{
    const CACHE_TTL = 3600; // 1 hour

    /**
     * Get dashboard stats with caching.
     * Cache is invalidated when projects are created/updated.
     */
    public static function getDashboardStats($userId, $roleSlug)
    {
        $cacheKey = "dashboard_stats_{$roleSlug}_{$userId}";

        return Cache::remember($cacheKey, self::CACHE_TTL, function () {
            $projects = Project::get();

            return [
                'total_projects'  => $projects->count(),
                'ongoing'         => $projects->where('current_status', 'On Going')->count(),
                'completed'       => $projects->where('current_status', 'Completed')->count(),
                'budget_allocated' => $projects->sum('approved_budget') ?? 0,
                'budget_used'     => $projects->sum('actual_budget') ?? 0,
            ];
        });
    }

    /**
     * Get recent projects with caching.
     */
    public static function getRecentProjects($limit = 5)
    {
        $cacheKey = "recent_projects_{$limit}";

        return Cache::remember($cacheKey, self::CACHE_TTL, function () use ($limit) {
            return Project::with(['barangay', 'creator'])
                ->latest('created_at')
                ->take($limit)
                ->get();
        });
    }

    /**
     * Invalidate all dashboard caches when project changes.
     * Call this after create/update/delete.
     */
    public static function invalidateDashboardCaches()
    {
        Cache::forget('dashboard_stats_department_*');
        Cache::forget('dashboard_stats_city_*');
        Cache::forget('dashboard_stats_barangay_*');
        Cache::forget('recent_projects_*');
    }

    /**
     * Get GeoJSON data with caching.
     */
    public static function getGeoJsonData()
    {
        $cacheKey = 'geojson_projects';

        return Cache::remember($cacheKey, self::CACHE_TTL, function () {
            $projects = Project::with('barangay')
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
                        'id'          => $project->project_id,
                        'name'        => $project->project_name,
                        'code'        => $project->project_code,
                        'status'      => $project->current_status,
                        'barangay'    => $project->barangay?->barangay_name,
                        'budget'      => $project->approved_budget,
                        'url'         => route('department.projects.show', $project->project_id),
                    ],
                ];
            });

            return [
                'type'     => 'FeatureCollection',
                'features' => $features,
            ];
        });
    }
}