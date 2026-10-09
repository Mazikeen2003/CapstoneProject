<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Scopes\RoleScopedScope;

class Barangay extends Model
{
    protected $primaryKey = 'barangay_id';

    public $timestamps = false;

    protected $fillable = [
        'barangay_name',
        'boundary_geojson',
        'latitude',        // NEW: for barangay pin coordinates
        'longitude',       // NEW: for barangay pin coordinates
    ];

    protected $casts = [
        'boundary_geojson' => 'array',
    ];

    public function users()
    {
        return $this->hasMany(User::class, 'barangay_id', 'barangay_id');
    }

    public function projects()
    {
        return $this->hasMany(Project::class, 'barangay_id', 'barangay_id');
    }

    /**
     * Get the route key for the model (for route-model binding).
     */
    public function getRouteKeyName()
    {
        return 'barangay_id';
    }

    /**
     * Scope to include the selected status view's project count on the public map.
     */
    public function scopeWithPublicProjectCount($query, bool $completed = false)
    {
        return $query->withCount(['projects as public_project_count' => function ($q) use ($completed) {
            $q->withoutGlobalScope(RoleScopedScope::class)
                ->when(
                    $completed,
                    fn ($projects) => $projects->where('current_status', 'Completed'),
                    fn ($projects) => $projects->where('current_status', '!=', 'Completed')
                );
        }]);
    }
}
