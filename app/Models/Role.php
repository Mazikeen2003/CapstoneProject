<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Model;     

class Role extends Model
{
    protected $primaryKey = 'role_id';
     protected $table = 'roles';

    public $timestamps = false;

    protected $fillable = [
        'role_name',
        'role_description'
    ];

    public function users()
    {
        return $this->hasMany(User::class, 'role_id', 'role_id');
    }

    /**
     * Get the slug version of the role name for routing
     */
    public function getSlugAttribute()
    {
        $slugMap = [
            'Admin' => 'admin',
            'City Official' => 'city',
            'Barangay Official' => 'barangay',
            'Department' => 'department',
        ];

        return $slugMap[$this->role_name] ?? strtolower(str_replace(' ', '-', $this->role_name));
    }
}
