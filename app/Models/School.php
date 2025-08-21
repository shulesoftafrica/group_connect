<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;


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
        return $this->belongsTo(\App\Models\ShulesoftSetting::class, 'school_setting_uid', 'uid');
    }

    public function studentsCount()
    {
       return DB::table('shulesoft.student')
            ->where('schema_name', $this->schoolSetting->schema_name)
            ->where('status', 1)
            ->count();
    }

    public function totalRevenue()
    {
        return DB::table('shulesoft.payments')
            ->where('schema_name', $this->schoolSetting->schema_name)
            ->whereYear('date', date('Y'))
            ->sum('amount');
    }

    public function totalOtherRevenue()
    {
        return DB::table('shulesoft.revenues')
            ->where('schema_name', $this->schoolSetting->schema_name)
            ->whereYear('date', date('Y'))
            ->sum('amount');
    }

    public function totalExpenses()
    {
        return DB::table('shulesoft.expenses')
            ->where('schema_name', $this->schoolSetting->schema_name)
            ->whereYear('date', date('Y'))
            ->sum('amount');
    }

    public function outstandingFees()
    {
         $query=DB::table('shulesoft.material_invoice_balance')
            ->where('schema_name', $this->schoolSetting->schema_name) 
            ->whereIn('academic_year_id', function ($query) {
                $query->select('id')
                    ->from('shulesoft.academic_year')
                    ->where('schema_name', $this->schoolSetting->schema_name)
                    ->whereYear('start_date', date('Y'))
                    ->whereYear('end_date', date('Y'));
            })
            ->sum('total_amount') ;
            return $query;
    }

    public function staffCount()
    {
       return DB::table('shulesoft.users')
            ->where('schema_name', $this->schoolSetting->schema_name)
            ->whereIn('table',['teacher','user'])
            ->where('status', 1)
            ->count();
    }

    public function teachersCount(){
        return DB::table('shulesoft.users')
            ->where('schema_name', $this->schoolSetting->schema_name)
            ->where('table', 'teacher')
            ->where('status', 1)
            ->count();
    }

    public function messageSentTotal(){
        return DB::table('shulesoft.sms')
            ->where('schema_name', $this->schoolSetting->schema_name)
            ->count();
    }

    public function schoolLevels(){
        return DB::table('shulesoft.classlevel')
            ->where('schema_name', $this->schoolSetting->schema_name)
            ->pluck('name')
            ->toArray();
    }

    public function lastLogDateTime()
    {
        $schemaName = $this->schoolSetting->schema_name ?? null;
        if (!$schemaName) {
            return null;
        }

        // Return the latest created_at value from shulesoft.log for this school's schema
        return DB::table('shulesoft.log')
            ->where('schema_name', $schemaName)
            ->max('created_at');
    }

    public function avgPerformance()
    {
        return DB::table('shulesoft.mark')
            ->where('schema_name', $this->schoolSetting->schema_name)
            ->avg('mark');
    }

    public function attendanceRate()
    {
        return DB::table('shulesoft.sattendances')
            ->where('schema_name', $this->schoolSetting->schema_name)
            ->selectRaw('SUM(CASE WHEN present = 1 THEN 1 ELSE 0 END) * 100.0 / COUNT(*) as attendance_percentage')
            ->value('attendance_percentage') ?? 0;
    }

    public function feeCollectionPercentage($start_date = null, $end_date = null)
    {
        $schemaName = $this->schoolSetting->schema_name ?? null;
        if (!$schemaName) {
            return 0;
        }

        // Default dates: Jan 1st and Dec 31st of current year
        $year = date('Y');
        $start_date = $start_date ?? "$year-01-01";
        $end_date = $end_date ?? "$year-12-31";

        // Total collected from payments within date range
        $totalCollected = DB::table('shulesoft.payments')
            ->where('schema_name', $schemaName)
            ->whereYear('date', date('Y'))
            ->sum('amount');

        // Total to be collected from fees_installments_classes within date range
        $totalToBeCollected = DB::table('shulesoft.material_invoice_balance')
            ->where('schema_name', $schemaName)
           ->whereIn('academic_year_id', function ($query) {
                $query->select('id')
                    ->from('shulesoft.academic_year')
                    ->where('schema_name', $this->schoolSetting->schema_name)
                    ->whereYear('start_date', date('Y'))
                    ->whereYear('end_date', date('Y'));
            })
            ->sum('total_amount');

        if ($totalToBeCollected == 0) {
            return 0;
        }

        return round(($totalCollected / $totalToBeCollected) * 100, 2);
    }

    public function totalStaff(){
        $schemaName = $this->schoolSetting->schema_name ?? null;
        if (!$schemaName) {
            return 0;
        }

        $totalTeachers = DB::table('shulesoft.teacher')
            ->where('schema_name', $schemaName)
            ->where('status', 1)
            ->count();

        $totalUsers = DB::table('shulesoft.user')
            ->where('schema_name', $schemaName)
            ->where('status', 1)
            ->count();

        return $totalTeachers + $totalUsers;
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
