<?php

namespace App\Services;

use App\Models\Project;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

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
        self::invalidateGeoJsonCache();
    }

    public static function invalidateGeoJsonCache()
    {
        Cache::forget('geojson_projects_all');
        Cache::forget('geojson_projects');
    }

    /**
     * Get GeoJSON data with caching.
     */
    public static function getGeoJsonData($user = null, $forcePublic = false)
    {
        $user = $user ?? auth()->user();

        if ($forcePublic) {
            $cacheKey = 'geojson_projects_all';

            return Cache::remember($cacheKey, self::CACHE_TTL, function () use ($user) {
                return self::buildGeoJsonData($user, true);
            });
        }

        $role = $user?->role_slug ?? 'public';

        $cacheKey = match ($role) {
            'admin', 'city', 'public' => 'geojson_projects_all',
            default => null,
        };

        if ($cacheKey) {
            return Cache::remember($cacheKey, self::CACHE_TTL, function () use ($user) {
                return self::buildGeoJsonData($user);
            });
        }

        return self::buildGeoJsonData($user);
    }

    protected static function buildGeoJsonData($user = null, $ignoreRoleScope = false)
    {
        $query = $ignoreRoleScope ? Project::withoutRoleScope() : Project::query();

        $projects = $query->with('barangay')
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
                        'description' => $project->location_description,
                        'barangay_id' => $project->barangay_id,
                        'image'       => $project->project_image ? Storage::url($project->project_image) : null,
                        'url'         => route('department.projects.show', $project->project_id),
                    ],
                ];
            });

        return [
            'type'     => 'FeatureCollection',
            'features' => $features,
        ];
    }
}
