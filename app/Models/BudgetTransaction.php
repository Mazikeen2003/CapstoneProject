<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

class BudgetTransaction extends Model
{
    public const CATEGORIES = ['Project Overhead', 'Manpower', 'Material', 'Admin Overhead'];

    public const TYPES = ['planned', 'actual'];

    public static function supportsCategoryTracking(): bool
    {
        return Schema::hasColumn('budget_transactions', 'category')
            && Schema::hasColumn('budget_transactions', 'type')
            && Schema::hasColumn('budget_transactions', 'transaction_date');
    }

    public $timestamps = false;

    protected $primaryKey = 'transaction_id';

    protected $fillable = [
        'project_id',
        'action',
        'amount',
        'transaction_type',
        'category',
        'type',
        'transaction_date',
        'description',
        'user_id',
        'created_at',
    ];

    protected $casts = [
        'amount'     => 'decimal:2',
        'created_at' => 'datetime',
        'transaction_date' => 'date',
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