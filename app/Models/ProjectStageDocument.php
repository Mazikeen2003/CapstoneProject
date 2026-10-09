<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectStageDocument extends Model
{
    protected $fillable = [
        'project_id',
        'uploaded_by',
        'stage',
        'document_type',
        'original_filename',
        'file_path',
        'mime_type',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id', 'project_id');
    }

    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by', 'user_id');
    }
}
