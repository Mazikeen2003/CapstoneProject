<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EditPermissionRequest extends Model
{
    protected $primaryKey = 'request_id';
    public $timestamps = true;

    protected $fillable = [
        'project_id',
        'requested_by',
        'fields_requested',
        'reason',
        'status',
        'reviewed_by',
        'reviewed_at',
        'review_notes',
        'used_at',
    ];

    protected $casts = [
        'fields_requested' => 'array',
        'reviewed_at' => 'datetime',
        'used_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id', 'project_id');
    }

    public function requester()
    {
        return $this->belongsTo(User::class, 'requested_by', 'user_id');
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by', 'user_id');
    }
}
