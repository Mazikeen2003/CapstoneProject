<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectForm extends Model
{
    protected $primaryKey = 'form_id';

    protected $fillable = [
        'project_id',
        'form_type',
        'form_title',
        'form_data',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'form_data' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id', 'project_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by', 'user_id');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by', 'user_id');
    }
}