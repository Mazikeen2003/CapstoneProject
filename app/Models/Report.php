<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Report extends Model
{
    protected $fillable = [
        'report_type',
        'title',
        'generated_by_user_id',
        'generated_by_username',
        'status',
        'pdf_path',
        'snapshot',
    ];

    protected $casts = [
        'snapshot' => 'array',
    ];

    public function generatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'generated_by_user_id');
    }
}
