<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\ProjectUpdate;
use App\Services\CacheService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ProjectController extends Controller
{
    /**
     * Return aggregated map counts and statistics for the authenticated user's scope.
     */
    public function mapCounts(Request $request)
    {
        $this->authorize('viewAny', Project::class);

        $projects = Project::query();
        $activeCounts = (clone $projects)
            ->where(fn (Builder $query) => $query->whereNull('current_status')->orWhere('current_status', '!=', 'Completed'))
            ->select('barangay_id')
            ->selectRaw('COUNT(*) as project_count')
            ->with('barangay:barangay_id,barangay_name')
            ->groupBy('barangay_id')
            ->get()
            ->map(fn (Project $project) => [
                'barangay_id' => $project->barangay_id,
                'name' => $project->barangay?->barangay_name ?? 'Citywide',
                'project_count' => (int) $project->project_count,
            ]);

        $statsProjects = clone $projects;
        $barangayName = trim((string) $request->query('barangay', ''));
        if ($barangayName !== '') {
            if (strcasecmp($barangayName, 'Citywide') === 0) {
                $statsProjects->whereNull('barangay_id');
            } else {
                $statsProjects->whereHas('barangay', fn (Builder $query) => $query->where('barangay_name', $barangayName));
            }
        }
        $statsProjects->where(fn (Builder $query) => $query->whereNull('current_status')->orWhere('current_status', '!=', 'Completed'));

        $latestProgress = (clone $statsProjects)
            ->selectSub(
                ProjectUpdate::query()
                    ->select('progress_percentage')
                    ->whereColumn('project_updates.project_id', 'projects.project_id')
                    ->orderByDesc('update_date')
                    ->orderByDesc('update_id')
                    ->limit(1),
                'latest_progress'
            );

        return response()->json([
            'counts' => $activeCounts,
            'stats' => [
                'total_projects' => (clone $statsProjects)->count(),
                'avg_progress' => (float) (DB::query()->fromSub($latestProgress, 'map_projects')->avg('latest_progress') ?? 0),
                'total_budget' => (float) (clone $statsProjects)->sum('approved_budget'),
                'barangays' => $barangayName === ''
                    ? (int) (clone $statsProjects)->whereNotNull('barangay_id')->distinct()->count('barangay_id')
                    : (int) ((clone $statsProjects)->whereNotNull('barangay_id')->exists() ? 1 : 0),
            ],
        ]);
    }

    /**
     * Return a paginated list of lightweight project summaries.
     */
    public function mapProjects(Request $request)
    {
        $this->authorize('viewAny', Project::class);

        $status = $request->query('status') === 'completed' ? 'completed' : 'active';
        $barangayName = trim((string) $request->query('barangay', ''));
        $search = trim(mb_substr((string) $request->query('search', ''), 0, 100));
        $scope = Project::query();

        if ($barangayName !== '') {
            if (strcasecmp($barangayName, 'Citywide') === 0) {
                $scope->whereNull('barangay_id');
            } else {
                $scope->whereHas('barangay', fn (Builder $query) => $query->where('barangay_name', $barangayName));
            }
        }

        $counts = [
            'active' => (clone $scope)
                ->where(fn (Builder $query) => $query->whereNull('current_status')->orWhere('current_status', '!=', 'Completed'))
                ->count(),
            'completed' => (clone $scope)->where('current_status', 'Completed')->count(),
        ];

        $query = clone $scope;
        if ($status === 'completed') {
            $query->where('current_status', 'Completed');
        } else {
            $query->where(fn (Builder $statusQuery) => $statusQuery->whereNull('current_status')->orWhere('current_status', '!=', 'Completed'));
        }

        if ($search !== '') {
            $query->where(function (Builder $searchQuery) use ($search) {
                $searchQuery
                    ->where('project_name', 'like', '%' . $search . '%')
                    ->orWhere('project_code', 'like', '%' . $search . '%')
                    ->orWhere('current_status', 'like', '%' . $search . '%')
                    ->orWhere('project_type', 'like', '%' . $search . '%')
                    ->orWhere('public_description', 'like', '%' . $search . '%')
                    ->orWhereHas('barangay', fn (Builder $query) => $query->where('barangay_name', 'like', '%' . $search . '%'));

                if (stripos('Citywide', $search) !== false) {
                    $searchQuery->orWhereNull('barangay_id');
                }
                if (ctype_digit($search)) {
                    $searchQuery->orWhere('project_id', (int) $search);
                }
            });
        }

        $projects = $query
            ->with('barangay:barangay_id,barangay_name')
            ->select(['project_id', 'project_code', 'project_name', 'current_status', 'project_type', 'barangay_id'])
            ->orderByDesc('project_id')
            ->paginate(12);

        return response()->json([
            'data' => $projects->getCollection()->map(fn (Project $project) => [
                'id' => $project->project_id,
                'code' => $project->project_code,
                'name' => $project->project_name,
                'status' => $project->current_status,
                'type' => $project->project_type,
                'barangay' => $project->barangay?->barangay_name ?? 'Citywide',
            ]),
            'meta' => [
                'current_page' => $projects->currentPage(),
                'last_page' => $projects->lastPage(),
                'per_page' => $projects->perPage(),
                'total' => $projects->total(),
                'active_count' => $counts['active'],
                'completed_count' => $counts['completed'],
            ],
        ]);
    }

    /**
     * Return lightweight active project markers for a selected barangay.
     */
    public function mapMarkers(Request $request)
    {
        $this->authorize('viewAny', Project::class);

        $barangayName = trim((string) $request->query('barangay', ''));
        $query = Project::query()
            ->with('barangay:barangay_id,barangay_name,latitude,longitude')
            ->where(fn (Builder $statusQuery) => $statusQuery->whereNull('current_status')->orWhere('current_status', '!=', 'Completed'));

        if ($barangayName !== '') {
            if (strcasecmp($barangayName, 'Citywide') === 0) {
                $query->whereNull('barangay_id');
            } else {
                $query->whereHas('barangay', fn (Builder $barangayQuery) => $barangayQuery->where('barangay_name', $barangayName));
            }
        }

        $features = $query
            ->select(['project_id', 'project_code', 'project_name', 'project_type', 'current_status', 'barangay_id', 'latitude', 'longitude'])
            ->get()
            ->map(function (Project $project) {
                $latitude = $project->barangay_id === null
                    ? CacheService::CITYWIDE_LATITUDE
                    : ($project->latitude ?: $project->barangay?->latitude);
                $longitude = $project->barangay_id === null
                    ? CacheService::CITYWIDE_LONGITUDE
                    : ($project->longitude ?: $project->barangay?->longitude);

                if (empty($latitude) || empty($longitude)) {
                    return null;
                }

                return [
                    'type' => 'Feature',
                    'geometry' => ['type' => 'Point', 'coordinates' => [(float) $longitude, (float) $latitude]],
                    'properties' => [
                        'id' => $project->project_id,
                        'code' => $project->project_code,
                        'name' => $project->project_name,
                        'status' => $project->current_status,
                        'type' => $project->project_type,
                        'barangay' => $project->barangay?->barangay_name ?? 'Citywide',
                    ],
                ];
            })
            ->filter()
            ->values();

        return response()->json(['type' => 'FeatureCollection', 'features' => $features]);
    }

    /**
     * Return full project details only after selection.
     */
    public function mapProjectDetails(int $projectId)
    {
        $this->authorize('viewAny', Project::class);

        $project = Project::query()
            ->with(['barangay', 'latestUpdate'])
            ->withActualTransactionSum()
            ->findOrFail($projectId);

        $latitude = $project->barangay_id === null
            ? CacheService::CITYWIDE_LATITUDE
            : ($project->latitude ?: $project->barangay?->latitude);
        $longitude = $project->barangay_id === null
            ? CacheService::CITYWIDE_LONGITUDE
            : ($project->longitude ?: $project->barangay?->longitude);
        $geometry = empty($latitude) || empty($longitude)
            ? null
            : ['type' => 'Point', 'coordinates' => [(float) $longitude, (float) $latitude]];

        return response()->json([
            'type' => 'Feature',
            'geometry' => $geometry,
            'properties' => [
                'id' => $project->project_id,
                'code' => $project->project_code,
                'name' => $project->project_name,
                'status' => $project->current_status,
                'type' => $project->project_type,
                'barangay' => $project->barangay?->barangay_name ?? 'Citywide',
                'is_citywide' => $project->barangay_id === null,
                'budget' => $project->approved_budget ?? 0,
                'actual_budget' => $project->actual_budget_total,
                'description' => $project->public_description ?: 'No description available.',
                'barangay_id' => $project->barangay_id,
                'image' => $project->project_image ? Storage::url($project->project_image) : null,
                'start_date' => $project->start_date?->toDateString(),
                'target_end_date' => $project->target_end_date?->toDateString(),
                'progress_percentage' => $project->latestUpdate?->progress_percentage,
            ],
        ]);
    }

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