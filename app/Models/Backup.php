<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Backup extends Model
{
    protected $primaryKey = 'backup_id';
    protected $keyType = 'int';
    public $timestamps = true;

    protected $fillable = [
        'trigger_type',
        'triggered_by_user_id',
        'file_path',
        'file_size',
        'status',
        'error_message',
    ];

    public function triggeredBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'triggered_by_user_id', 'user_id');
    }
}
