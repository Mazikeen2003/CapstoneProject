<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $fillable = [
        'project_code',
        'project_namee',
        'project_type',
        'barangay_id',
        'location_description',
        'latitude',
        'longtitude',
        'approved_budget',
        'actual_budget',
        'start_date',
        'target_end_date',
        'actual_end_date',
        'current_status',
        'remarks', 
        'created_by',
        'updated_by',
        'created_at',
        'updated_at'
    ];
}