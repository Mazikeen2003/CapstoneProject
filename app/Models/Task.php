<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $primaryKey = 'task_id';

    protected $fillable = [
        'project_id',
        'task_name',
        'task_description',
        'assigned_to',
        'task_start_date',
        'task_end_date',
        'task_status',
        'remarks',
    ];

    protected $casts = [
        'task_start_date' => 'date',
        'task_end_date'   => 'date',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id', 'project_id');
    }

    public function assignedUser()
    {
        return $this->belongsTo(User::class, 'assigned_to', 'user_id');
    }
}