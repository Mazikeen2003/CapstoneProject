<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectUpdate extends Model
{
    public $timestamps = false;

    protected $primaryKey = 'update_id';

    protected $fillable = [
        'project_id',
        'update_date',
        'status',
        'progress_percentage',
        'remarks',
        'user_id',
    ];

    protected $casts = [
        'update_date'         => 'date',
        'progress_percentage' => 'integer',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id', 'project_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }
}