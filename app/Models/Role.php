<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    protected $table = 'connect_roles';

    protected $fillable = [
        'name',
        'display_name',
        'description',
        'menu_access',
        'is_active',
    ];

    protected $casts = [
        'menu_access' => 'array',
        'is_active' => 'boolean',
    ];

    /**
     * Get the users for this role.
     */
    public function users()
    {
        return $this->hasMany(User::class);
    }

    /**
     * Get the permissions for this role.
     */
    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'connect_role_permissions')
                    ->withTimestamps();
    }

    /**
     * Scope for active roles.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
