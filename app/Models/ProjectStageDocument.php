<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectStageDocument extends Model
{
    public const DOCUMENT_TYPES = [
        1 => ['Detailed Project Information'],
        2 => ['Technical Proposal', 'Eligibility Requirement'],
        3 => ['Bid Submission'],
        4 => ['Signed Contract', 'Notice of Award', 'Purchase Order'],
    ];

    protected $casts = [
        'stage' => 'integer',
        'is_public' => 'boolean',
    ];

    protected $fillable = [
        'project_id',
        'uploaded_by',
        'stage',
        'document_type',
        'original_filename',
        'file_path',
        'mime_type',
        'is_public',
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
