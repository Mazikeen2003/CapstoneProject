<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
     * Scope to include a count of public projects for this barangay.
     * Public projects are those with status 'Completed' or 'On Going'.
     */
    public function scopeWithPublicProjectCount($query)
    {
        return $query->withCount(['projects as public_project_count' => function ($q) {
            $q->whereIn('current_status', ['Completed', 'On Going']);
        }]);
    }
}