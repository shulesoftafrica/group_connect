<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    use HasFactory;

    protected $table = 'connect_permissions';

    protected $fillable = [
        'name',
        'display_name',
        'description',
        'module',
        'action',
    ];

    /**
     * Get the roles that have this permission.
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'connect_role_permissions')
                    ->withTimestamps();
    }

    /**
     * Scope for permissions in a specific module.
     */
    public function scopeForModule($query, $module)
    {
        return $query->where('module', $module);
    }
}
