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
}
