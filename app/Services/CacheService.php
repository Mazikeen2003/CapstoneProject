<?php

namespace App\Services;

use App\Models\Project;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class CacheService
{
    const CACHE_TTL = 3600; // 1 hour
    const GEOJSON_CACHE_VERSION = 'v2';

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
        foreach (['public', 'admin', 'city', 'department', 'engineering', 'barangay'] as $role) {
            Cache::forget('geojson_projects_' . self::GEOJSON_CACHE_VERSION . '_' . $role);
        }
    }

    /**
     * Get GeoJSON data with caching.
     */
    public static function getGeoJsonData($user = null, $forcePublic = false)
    {
        $user = $user ?? auth()->user();
        $role = $user?->role_slug ?? 'public';

        if ($forcePublic) {
            $cacheKey = 'geojson_projects_' . self::GEOJSON_CACHE_VERSION . '_public';

            return Cache::remember($cacheKey, self::CACHE_TTL, function () use ($user) {
                return self::buildGeoJsonData($user, true);
            });
        }

        $cacheKey = match ($role) {
            'admin' => 'geojson_projects_' . self::GEOJSON_CACHE_VERSION . '_admin',
            'city' => 'geojson_projects_' . self::GEOJSON_CACHE_VERSION . '_city',
            'department' => 'geojson_projects_' . self::GEOJSON_CACHE_VERSION . '_department',
            'engineering' => 'geojson_projects_' . self::GEOJSON_CACHE_VERSION . '_engineering',
            'barangay' => 'geojson_projects_' . self::GEOJSON_CACHE_VERSION . '_barangay',
            'public' => 'geojson_projects_' . self::GEOJSON_CACHE_VERSION . '_public',
            default => 'geojson_projects_' . self::GEOJSON_CACHE_VERSION . '_public',
        };

        return Cache::remember($cacheKey, self::CACHE_TTL, function () use ($user) {
            return self::buildGeoJsonData($user);
        });
    }

    protected static function buildGeoJsonData($user = null, $ignoreRoleScope = false)
    {
        $query = $ignoreRoleScope ? Project::withoutRoleScope() : Project::query();

        $projects = $query->with(['barangay', 'latestUpdate'])->get();

        $features = $projects->map(function ($project) use ($user) {
                $latitude = $project->latitude;
                $longitude = $project->longitude;

                if (empty($latitude) || empty($longitude)) {
                    $latitude = $project->barangay?->latitude;
                    $longitude = $project->barangay?->longitude;
                }

                if (empty($latitude) || empty($longitude)) {
                    return null;
                }

                $projectUrl = match ($user?->role_slug ?? 'public') {
                    'department' => route('department.projects.show', $project->project_id, false),
                    'engineering' => route('engineering.projects.show', $project->project_id, false),
                    'city' => route('city.projects.show', $project->project_id, false),
                    'barangay' => route('barangay.projects.show', $project->project_id, false),
                    default => route('public.map', [], false),
                };

                if ($user === null || $user?->role_slug === 'public') {
                    $projectUrl = route('public.map', [], false);
                }

                return [
                    'type'       => 'Feature',
                    'geometry'   => [
                        'type'        => 'Point',
                        'coordinates' => [$longitude, $latitude],
                    ],
                    'properties' => [
                        'id'                => $project->project_id,
                        'name'              => $project->project_name,
                        'code'              => $project->project_code,
                        'status'            => $project->current_status,
                        'barangay'          => $project->barangay?->barangay_name,
                        'budget'            => $project->approved_budget,
                        'actual_budget'     => $project->actual_budget ?? 0,
                        'description'       => $project->public_description ?: 'No description available.',
                        'barangay_id'       => $project->barangay_id,
                        'image'             => $project->project_image ? Storage::url($project->project_image) : null,
                        'start_date'        => $project->start_date?->toDateString(),
                        'target_end_date'   => $project->target_end_date?->toDateString(),
                        'progress_percentage' => $project->latestUpdate?->progress_percentage,
                        'url'               => $projectUrl,
                    ],
                ];
            })
            ->filter()
            ->values();

        return [
            'type'     => 'FeatureCollection',
            'features' => $features,
        ];
    }
}
