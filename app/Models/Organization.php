<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Organization extends Model
{
    use HasFactory;

    protected $table = 'connect_organizations';

    protected $fillable = [
        'username',
        'name',
        'description',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get the users for this organization.
     */
    public function users()
    {
        return $this->hasMany(User::class, 'connect_organization_id');
    }

    /**
     * Get the schools for this organization.
     */
    public function schools()
    {
        return $this->hasMany(School::class, 'connect_organization_id');
    }

    /**
     * Scope for active organizations.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
