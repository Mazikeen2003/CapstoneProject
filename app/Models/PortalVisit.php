<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PortalVisit extends Model
{
    protected $primaryKey = 'visit_id';
    public $timestamps = false;

    protected $fillable = [
        'page',
        'ip_address',
        'visited_at',
    ];

    protected $casts = [
        'visited_at' => 'datetime',
    ];

    public function scopeToday($query)
    {
        return $query->whereDate('visited_at', now()->toDateString());
    }

    public function scopeLast30Days($query)
    {
        return $query->where('visited_at', '>=', now()->subDays(30));
    }

    public function scopeForPage($query, $pageType)
    {
        return $query->where('page', $pageType);
    }
}
