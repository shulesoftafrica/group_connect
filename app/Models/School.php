<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class School extends Model
{
    use HasFactory;

    protected $table = 'connect_schools';

    protected $fillable = [
        'school_setting_uid',
        'connect_organization_id',
        'connect_user_id',
        'is_active',
        'shulesoft_code',
        'settings',
        'created_by',
    ];

    protected $casts = [
        'settings' => 'array',
        'is_active' => 'boolean',
    ];

    /**
     * Get the organization that owns this school.
     */
    public function organization()
    {
        return $this->belongsTo(Organization::class, 'connect_organization_id');
    }

    /**
     * Get the user assigned to this school.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'connect_user_id');
    }

    /**
     * Get the user who created this school.
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get school setting from shulesoft.setting table.
     */
    public function schoolSetting()
    {
        return $this->belongsTo(SchoolSetting::class, 'school_setting_uid', 'uid');
    }

    /**
     * Scope for active schools.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for schools in a specific organization.
     */
    public function scopeForOrganization($query, $organizationId)
    {
        return $query->where('connect_organization_id', $organizationId);
    }

    /**
     * Get school basic information from settings.
     */
    public function getSchoolInfoAttribute()
    {
        $settings = $this->settings ?? [];
        return [
            'name' => $settings['name'] ?? 'Unknown School',
            'location' => $settings['location'] ?? 'Unknown Location',
            'region' => $settings['region'] ?? 'Unknown Region',
            'total_students' => $settings['total_students'] ?? 0,
            'academic_index' => $settings['academic_index'] ?? 0,
            'attendance_percentage' => $settings['attendance_percentage'] ?? 0,
            'fee_collection_percentage' => $settings['fee_collection_percentage'] ?? 0,
        ];
    }
}
