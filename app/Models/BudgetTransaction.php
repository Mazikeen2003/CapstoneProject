<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BudgetTransaction extends Model
{
    public $timestamps = false;

    protected $primaryKey = 'transaction_id';

    protected $fillable = [
        'project_id',
        'action',
        'amount',
        'transaction_type',
        'description',
        'user_id',
        'created_at',
    ];

    protected $casts = [
        'amount'     => 'decimal:2',
        'created_at' => 'datetime',
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