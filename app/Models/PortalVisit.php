<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

class PortalVisit extends Model
{
    protected $primaryKey = 'visit_id';
    public $timestamps = false;

    protected $fillable = [
        'page',
        'page_type',
        'ip_address',
        'visited_at',
    ];

    protected $casts = [
        'visited_at' => 'datetime',
    ];

    public static function create(array $attributes = [])
    {
        $pageColumn = static::resolvePageColumn();

        if ($pageColumn === 'page_type') {
            if (array_key_exists('page', $attributes) && ! array_key_exists('page_type', $attributes)) {
                $attributes['page_type'] = $attributes['page'];
            }

            unset($attributes['page']);
        } elseif ($pageColumn === 'page') {
            if (array_key_exists('page_type', $attributes) && ! array_key_exists('page', $attributes)) {
                $attributes['page'] = $attributes['page_type'];
            }

            unset($attributes['page_type']);
        }

        return parent::create($attributes);
    }

    public static function resolvePageColumn(): string
    {
        $table = (new static)->getTable();

        if (Schema::hasColumn($table, 'page')) {
            return 'page';
        }

        if (Schema::hasColumn($table, 'page_type')) {
            return 'page_type';
        }

        return 'page';
    }

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
        return $query->where(static::resolvePageColumn(), $pageType);
    }
}
