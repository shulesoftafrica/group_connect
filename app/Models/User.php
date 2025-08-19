<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory>  */
    use HasFactory, Notifiable;

    protected $table = 'connect_users';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id',
        'status',
        'phone',
        'avatar',
        'last_login_at',
        'last_login_ip',
        'connect_organization_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
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
            'last_login_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get the organization that owns the user.
     */
    public function organization()
    {
        return $this->belongsTo(Organization::class, 'connect_organization_id');
    }

    /**
     * Get the role that owns the user.
     */
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * Get the schools assigned to the user.
     */
    public function schools()
    {
        return $this->hasMany(School::class, 'connect_user_id');
    }

    /**
     * Check if user has permission.
     */
    public function hasPermission($permission)
    {
        return $this->role->permissions->contains('name', $permission);
    }

    /**
     * Check if user has access to module.
     */
    public function hasModuleAccess($module)
    {
        $menuAccess = $this->role->menu_access ?? [];
        return in_array($module, $menuAccess);
    }
}
