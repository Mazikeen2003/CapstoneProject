<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    protected $primaryKey = 'user_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'username',
        'password_hash',
        'user_email',
        'role_id',
        'barangay_id'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password_hash',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password_hash' => 'hashed',
        ];
    }

    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id', 'role_id');
    }

    public function barangay()
    {
        return $this->belongsTo(Barangay::class, 'barangay_id', 'barangay_id');
    }

    public function getAuthPassword()
    {
        return $this->password_hash;
    }

    public function getEmailForPasswordReset()
    {
        return $this->user_email;
    }

    public function getNameAttribute()
    {
        return $this->username;
    }

    public function getEmailAttribute()
    {
        return $this->user_email;
    }

    public function getRoleSlugAttribute(): string
    {
        if (!$this->role) {
            return 'dashboard';
        }

        $roleName = $this->role->role_name ?? '';
        $roleName = strtolower(trim($roleName));

        return match ($roleName) {
            'admin' => 'admin',
            'city official' => 'city',
            'barangay official' => 'barangay',
            'department' => 'department',
            default => 'dashboard',
        };
    }

    public function hasRole(string $role): bool
    {
        return $this->role_slug === $role;
    }
}
