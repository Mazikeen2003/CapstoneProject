<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable, SoftDeletes;

    protected $primaryKey = 'user_id';
    protected $keyType = 'int';
    public $timestamps = true;

    protected $fillable = [
        'username',
        'user_email',
        'password_hash',
        'role_id',
        'barangay_id',
        'email_verified_at',
        'remember_token',
    ];

    protected $hidden = [
        'password_hash',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // Relationships
    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id', 'role_id');
    }

    public function barangay()
    {
        return $this->belongsTo(Barangay::class, 'barangay_id', 'barangay_id');
    }

    public function projects()
    {
        return $this->hasMany(Project::class, 'created_by', 'user_id');
    }

    public function auditLogs()
    {
        return $this->hasMany(AuditLog::class, 'user_id', 'user_id');
    }

    // Query Scopes
    public function scopeByRole($query, $roleId)
    {
        return $query->where('role_id', $roleId);
    }

    public function scopeByBarangay($query, $barangayId)
    {
        return $query->where('barangay_id', $barangayId);
    }

    public function scopeVerified($query)
    {
        return $query->whereNotNull('email_verified_at');
    }

    public function scopeUnverified($query)
    {
        return $query->whereNull('email_verified_at');
    }

    public function scopeWithRole($query)
    {
        return $query->with('role');
    }

    public function scopeWithBarangay($query)
    {
        return $query->with('barangay');
    }

    public function scopeWithRelations($query)
    {
        return $query->with(['role', 'barangay']);
    }

    // Auth methods
    public function getAuthPassword()
    {
        return $this->password_hash;
    }

    public function getEmailAttribute()
    {
        return $this->user_email;
    }

    // Computed property
    public function getRoleSlugAttribute()
    {
        return match ($this->role_id) {
            1 => 'admin',
            2 => 'city',
            3 => 'department',
            4 => 'barangay',
            default => 'public',
        };
    }

    public function hasRole($role): bool
    {
        if ($role instanceof Role) {
            return $this->hasRole($role->role_id);
        }

        if (is_numeric($role)) {
            return (int) $this->role_id === (int) $role;
        }

        $normalizedRole = $this->normalizeRoleValue($role);
        $expectedRole = match ($normalizedRole) {
            'admin' => 'admin',
            'cityofficial', 'city' => 'city',
            'barangayofficial', 'barangay' => 'barangay',
            'department' => 'department',
            default => $normalizedRole,
        };

        return $this->normalizeRoleValue($this->role_slug) === $expectedRole;
    }

    protected function normalizeRoleValue($value): string
    {
        return strtolower((string) preg_replace('/[^a-z0-9]+/', '', $value));
    }
}