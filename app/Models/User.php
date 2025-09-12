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

    /**
     * Phase 2: Check if user has access to module with progressive disclosure
     */
    public function hasProgressiveModuleAccess($module)
    {
        // First check basic role permissions
        if (!$this->hasModuleAccess($module)) {
            return false;
        }

        // Get user setup status
        $schoolCount = $this->schools()->count();
        $daysSinceRegistration = now()->diffInDays($this->created_at);
        
        // Progressive disclosure rules
        $restrictions = [
            'schools' => $schoolCount >= 0, // Always available
            'academics' => $schoolCount >= 1, // Need at least 1 school
            'operations' => $schoolCount >= 1, // Need at least 1 school
            'finance' => $schoolCount >= 1, // Need at least 1 school
            'hr' => $schoolCount >= 1, // Need at least 1 school
            'communications' => $schoolCount >= 1, // Need at least 1 school
            'insights' => $schoolCount >= 2 || $daysSinceRegistration >= 14, // Advanced feature
            'reports' => $schoolCount >= 1, // Need at least 1 school
            'settings' => true, // Always available for setup
        ];

        return $restrictions[$module] ?? false;
    }

    /**
     * Phase 2: Get disabled module info for progressive disclosure
     */
    public function getDisabledModuleInfo($module)
    {
        $schoolCount = $this->schools()->count();
        $daysSinceRegistration = now()->diffInDays($this->created_at);
        
        $requirements = [
            'academics' => [
                'message' => 'Add at least 1 school to access academic features',
                'action_url' => route('onboarding.start'),
                'action_text' => 'Add School'
            ],
            'operations' => [
                'message' => 'Add at least 1 school to access operations features',
                'action_url' => route('onboarding.start'),
                'action_text' => 'Add School'
            ],
            'finance' => [
                'message' => 'Add at least 1 school to access finance features',
                'action_url' => route('onboarding.start'),
                'action_text' => 'Add School'
            ],
            'hr' => [
                'message' => 'Add at least 1 school to access HR features',
                'action_url' => route('onboarding.start'),
                'action_text' => 'Add School'
            ],
            'communications' => [
                'message' => 'Add at least 1 school to access communication features',
                'action_url' => route('onboarding.start'),
                'action_text' => 'Add School'
            ],
            'insights' => [
                'message' => $schoolCount < 2 ? 
                    'Add at least 2 schools to access executive insights' : 
                    'Executive insights available after 14 days of usage',
                'action_url' => $schoolCount < 2 ? route('settings.schools') : null,
                'action_text' => $schoolCount < 2 ? 'Add More Schools' : null
            ],
            'reports' => [
                'message' => 'Add at least 1 school to access reporting features',
                'action_url' => route('onboarding.start'),
                'action_text' => 'Add School'
            ]
        ];

        return $requirements[$module] ?? null;
    }
}
