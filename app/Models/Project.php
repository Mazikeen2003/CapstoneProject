<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Scopes\RoleScopedScope;

class Project extends Model
{
    use SoftDeletes;

    protected $primaryKey = 'project_id';
    protected $keyType = 'int';
    public $timestamps = true;

    protected $fillable = [
        'project_code',
        'project_name',
        'project_type',
        'barangay_id',
        'location_description',
        'latitude',
        'longitude',
        'approved_budget',
        'actual_budget',
        'start_date',
        'target_end_date',
        'actual_end_date',
        'current_status',
        'remarks',
        'project_image',
        'created_by',
        'updated_by',
    ];

    protected $dates = [
        'start_date',
        'target_end_date',
        'actual_end_date',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected static function booted()
    {
        static::addGlobalScope(new RoleScopedScope());
    }

    // Relationships with eager loading
    public function barangay()
    {
        return $this->belongsTo(Barangay::class, 'barangay_id', 'barangay_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by', 'user_id');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by', 'user_id');
    }

    public function updates()
    {
        return $this->hasMany(ProjectUpdate::class, 'project_id', 'project_id');
    }

    public function budgetTransactions()
    {
        return $this->hasMany(BudgetTransaction::class, 'project_id', 'project_id');
    }

    // Query Scopes
    public function scopeWithRelations($query)
    {
        return $query->with(['barangay', 'creator', 'updates', 'budgetTransactions']);
    }

    public function scopeWithBasicRelations($query)
    {
        return $query->with(['barangay', 'creator']);
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('current_status', $status);
    }

    public function scopeByBarangay($query, $barangayId)
    {
        return $query->where('barangay_id', $barangayId);
    }

    public function scopeActive($query)
    {
        return $query->where('current_status', '!=', 'Cancelled');
    }

    public function scopeCompleted($query)
    {
        return $query->where('current_status', 'Completed');
    }

    public function scopeOngoing($query)
    {
        return $query->where('current_status', 'On Going');
    }

    public function scopeWithLocation($query)
    {
        return $query->whereNotNull('latitude')->whereNotNull('longitude');
    }

    public function scopeRecent($query, $limit = 5)
    {
        return $query->latest('created_at')->limit($limit);
    }

    public function scopeForUser($query)
    {
        return $query->where('created_by', auth()->id());
    }

    // Helper to bypass role scope when needed
    public static function withoutRoleScope()
    {
        return static::withoutGlobalScope(RoleScopedScope::class);
    }
}