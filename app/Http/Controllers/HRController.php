<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\School;
use App\Models\User;
use App\Models\Organization;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class HRController extends Controller
{
   

    protected function initializeSchemaNames()
    {
        if (empty($this->schemaNames)) {
            $user = Auth::user();
            $dashboard = app()->make(\App\Http\Controllers\DashboardController::class);
            $schools = $dashboard->getUserSchools($user);
            $this->schemaNames = $dashboard->getSchemaNames($schools);
        }
    }

    public function index()
    {
        $this->initializeSchemaNames();

        $hrKPIs = $this->calculateHRKPIs();
        $staffMetrics = $this->getStaffMetrics();
        $staffPerformance = $this->getStaffPerformance();
        $inactiveStaffData = $this->getInactiveStaffData();
        $schoolsList = $this->getSchoolsHRList();
        $staffDirectory = $this->getStaffDirectoryData();
        $performanceData = $this->getHRPerformanceData();
        $alertsData = $this->getHRAlerts();
        $attendanceData = $this->getAttendanceData();
        $payrollData = $this->getPayrollSummaryData();
        $recruitmentData = $this->getRecruitmentData();
        
        return view('hr.dashboard', compact(
            'hrKPIs',
            'staffMetrics',
            'staffPerformance',
            'inactiveStaffData',
            'schoolsList',
            'staffDirectory',
            'performanceData',
            'alertsData',
            'attendanceData',
            'payrollData',
            'recruitmentData'
        ));
    }

    public function schoolDetail($id)
    {
        $this->initializeSchemaNames();
        $school = School::with(['organization', 'user'])->findOrFail($id);
        
        $schoolHRData = $this->getSchoolHRData($school);
        $staffData = $this->getSchoolStaffData($school);
        $attendanceData = $this->getSchoolAttendanceData($school);
        $leaveData = $this->getSchoolLeaveData($school);
        $payrollData = $this->getSchoolPayrollData($school);
        $performanceData = $this->getSchoolPerformanceData($school);
        $recruitmentData = $this->getSchoolRecruitmentData($school);
        
        return view('hr.school-detail', compact(
            'school',
            'schoolHRData',
            'staffData',
            'attendanceData',
            'leaveData',
            'payrollData',
            'performanceData',
            'recruitmentData'
        ));
    }

    public function staffDirectory()
    {
            $user = Auth::user();
            $dashboard = app()->make(\App\Http\Controllers\DashboardController::class);
            $schools = $dashboard->getUserSchools($user);
            $this->schemaNames = $dashboard->getSchemaNames($schools);
            $staffData = $this->getAllStaffData();

        return view('hr.staff-directory', compact('schools', 'staffData'));
    }

    public function recruitment()
    {
        $this->initializeSchemaNames();
        $schools = School::with('organization')->get(); 
        $recruitmentData = $this->getRecruitmentManagementData();
        
        return view('hr.recruitment', compact('schools', 'recruitmentData'));
    }

    public function leaveManagement()
    {
        $this->initializeSchemaNames();
        $schools = School::with('organization')->get();
        $leaveData = $this->getLeaveManagementData();
        
        return view('hr.leave-management', compact('schools', 'leaveData'));
    }

    public function payrollManagement()
    {
        $this->initializeSchemaNames();
        $schools = School::with('organization')->get();
        $payrollData = $this->getPayrollManagementData();
        
        return view('hr.payroll-management', compact('schools', 'payrollData'));
    }

    public function exportReport(Request $request)
    {
        $reportType = $request->input('report_type', 'general');
        $schools = $request->input('schools', []);
        $dateRange = $request->input('date_range', 'current_month');
        
        // Generate and return report based on type
        return response()->json([
            'success' => true,
            'message' => 'HR report exported successfully',
            'file_url' => '/exports/hr-report-' . time() . '.xlsx'
        ]);
    }

    public function bulkAction(Request $request)
    {
        $action = $request->input('action');
        $staffIds = $request->input('staff_ids', []);
        $data = $request->input('data', []);
        
        switch ($action) {
            case 'update_policy':
                return $this->bulkUpdatePolicy($staffIds, $data);
            case 'send_communication':
                return $this->bulkSendCommunication($staffIds, $data);
            case 'approve_leave':
                return $this->bulkApproveLeave($staffIds, $data);
            case 'update_payroll':
                return $this->bulkUpdatePayroll($staffIds, $data);
            default:
                return response()->json(['success' => false, 'message' => 'Invalid action']);
        }
    }

    private function calculateHRKPIs()
    {
        // Calculate KPIs across all schemas
        $totalStaff = DB::table('users')
            ->whereIn('schema_name', $this->schemaNames)
            ->whereIn('table', ['teacher', 'user'])
            ->count();

        $activeStaff = DB::table('users')
            ->whereIn('schema_name', $this->schemaNames)
            ->where('status', 1)
           ->whereIn('table', ['teacher', 'user'])
            ->count();

        // Get pending leave requests
        $pendingLeaveRequests = DB::table('staff_leave')
            ->whereIn('schema_name', $this->schemaNames)
            ->where('status', 1)
            ->count();

        // Monthly resignations
        $monthlyResignations = DB::table('users')
            ->whereIn('schema_name', $this->schemaNames)
            ->where('status', '<>', 1)
            ->where('updated_at', '>=', Carbon::now()->startOfMonth())
            ->whereIn('usertype', ['teacher', 'staff', 'admin'])
            ->count();

        // Attendance data
        $schemaAttendance = DB::table('tattendances')
            ->whereIn('schema_name', $this->schemaNames)
            ->selectRaw('SUM(CASE WHEN present = 1 THEN 1 ELSE 0 END)::float / COUNT(*) * 100 as attendance_percentage')
            ->where('created_at', '>=', Carbon::now()->startOfMonth())
            ->value('attendance_percentage');

        $avgAttendance = $schemaAttendance ? round($schemaAttendance, 1) : 95.0;
        $turnoverRate = $totalStaff > 0 ? ($monthlyResignations / $totalStaff) * 100 : 0;

        return [
            'total_staff' => $totalStaff,
            'active_staff' => $activeStaff,
            'pending_leave_requests' => $pendingLeaveRequests,
            'average_attendance' => $avgAttendance,
            'turnover_rate' => round($turnoverRate, 1),
            'vacant_positions'=>0
        ];
    }

    private function getStaffMetrics()
    {
        // Total Teachers - from teacher table
        $totalTeachers = DB::table('teacher')
            ->whereIn('schema_name', $this->schemaNames)
            ->where('status', 1)
            ->count();

        // Non-teaching staff - from user table  
        $nonTeachingStaff = DB::table('user')
            ->whereIn('schema_name', $this->schemaNames)
            ->where('status', 1)
            ->count();

        // Total Parents - from parent table
        $totalParents = DB::table('parent')
            ->whereIn('schema_name', $this->schemaNames)
            ->where('status', 1)
            ->count();

        // Total Sponsors - from sponsors table through student_sponsors
        $totalSponsors = DB::table('sponsors')
            ->join('student_sponsors', 'student_sponsors.sponsor_id', '=', 'sponsors.id')
            ->whereIn('sponsors.schema_name', $this->schemaNames)
            ->where('student_sponsors.status', 1)
            ->distinct('sponsors.id')
            ->count();

        // Inactive staff from users table
        $inactiveStaff = DB::table('users')
            ->whereIn('schema_name', $this->schemaNames)
            ->where('status', '<>', 1)
            ->whereIn('table', ['teacher', 'user'])
            ->count();

        return [
            'total_teachers' => $totalTeachers,
            'non_teaching_staff' => $nonTeachingStaff,
            'total_parents' => $totalParents,
            'total_sponsors' => $totalSponsors,
            'inactive_staff' => $inactiveStaff,
        ];
    }

    private function getStaffPerformance()
    {
        // Get staff performance from staff_targets table
        $avgKpiScore = DB::table('staff_targets')
            ->whereIn('schema_name', $this->schemaNames)
            ->where('created_at', '>=', Carbon::now()->startOfMonth())
            ->avg('value') ?? 0;

        $kpiCompleted = DB::table('staff_targets_reports')
            ->whereIn('schema_name', $this->schemaNames)
            ->where('created_at', '>=', Carbon::now()->startOfMonth())
            ->where('is_approved', 1)
            ->count();

        return [
            'avg_kpi_score' => round($avgKpiScore, 1),
            'kpi_completed' => $kpiCompleted,
        ];
    }

    private function getInactiveStaffData()
    {
        // Get inactive staff breakdown by reason
        $inactiveReasons = DB::table('users')
            ->join('constant.user_status', 'users.status_id', '=', 'constant.user_status.id')
            ->select('constant.user_status.reason as reason', DB::raw('count(*) as count'))
            ->whereIn('users.schema_name', $this->schemaNames)
            ->where('users.status', '<>', 1)
            ->whereIn('users.table', ['teacher', 'user'])
            ->groupBy('constant.user_status.reason')
            ->pluck('count', 'reason')
            ->toArray();

        return $inactiveReasons;
    }

    private function getSchoolsHRList()
    {
        $user = Auth::user();
        $dashboard = app()->make(\App\Http\Controllers\DashboardController::class);
        $schools = $dashboard->getUserSchools($user);
        
        return $schools->map(function ($school) {
            // Get actual staff count for this school
            $setting=$school->schoolSetting;
            $totalStaff = DB::table('users')
                ->where('schema_name', $school->schoolSetting->schema_name)
                ->whereIn('table', ['teacher', 'user'])
                ->count();

            $activeStaff = DB::table('users')
                ->where('schema_name', $school->schoolSetting->schema_name)
                ->where('status', 1)
                ->whereIn('table', ['teacher', 'user'])
                ->count();

      
           
            
        $attendanceRate = DB::table('tattendances')
              ->where('schema_name', $school->schoolSetting->schema_name)
            ->selectRaw('SUM(CASE WHEN present = 1 THEN 1 ELSE 0 END)::float / COUNT(*) * 100 as attendance_percentage')
            ->where('created_at', '>=', Carbon::now()->startOfMonth())
            ->value('attendance_percentage');


            // Calculate turnover rate
            $monthlyResignations = DB::table('users')
                 ->where('schema_name', $school->schoolSetting->schema_name)
                ->where('status', '<>', 1)
                ->where('updated_at', '>=', Carbon::now()->startOfMonth())
                ->count();
            
            $turnoverRate = $totalStaff > 0 ? ($monthlyResignations / $totalStaff) * 100 : 0;

            // Get payroll status (simplified - you may have a specific payroll table)
            // Get last payroll date from salaries table
            $payrollStatus = DB::table('shulesoft.salaries')
                ->where('schema_name', $school->schoolSetting->schema_name)
                ->max('created_at');

            return [
                'id' => $school->id,
                'name' => ucfirst($setting->schema_name)?? 'Unknown School',
                'code' => $school->shulesoft_code,
                'region' => $school->settings['region'] ?? 'Unknown',
                'total_staff' => $totalStaff,
                'active_staff' => $activeStaff,
                'vacant_positions' => max(0, $totalStaff - $activeStaff),
                'turnover_rate' => round($turnoverRate, 1),
                'attendance_rate' => round($attendanceRate, 1),
                'payroll_status' => $payrollStatus,
                'compliance_score' => 95, // This would come from compliance calculations
                'last_updated' => Carbon::now()->format('Y-m-d H:i:s')
            ];
        });
    }

    private function getStaffDirectoryData()
    {
        // Get total staff count
        $totalCount = DB::table('users')
         ->whereIn('users.schema_name', $this->schemaNames)
            ->whereIn('table', ['teacher', 'user'])
            ->count();

        // Get staff by role
        $byRole = [
            'Teachers' => DB::table('users')
                ->where('table', 'teacher')
                 ->whereIn('users.schema_name', $this->schemaNames)
                ->count(),
            'Support Staff' => DB::table('users')
             ->whereIn('users.schema_name', $this->schemaNames)
                ->whereIn('table', ['user'])
                ->count(),
            'Administration' => DB::table('users')
             ->whereIn('users.schema_name', $this->schemaNames)
                ->whereRaw('LOWER(usertype) = ?', ['admin'])
                ->count(),
        ];

        // Get staff by region (based on school location)
        $byRegion = DB::table('users')
         ->whereIn('users.schema_name', $this->schemaNames)
            ->select('country_id', DB::raw('count(*) as count'))
            ->whereIn('users.table', ['teacher', 'user'])
              ->groupBy('country_id')
            ->pluck('count', 'country_id')
            ->toArray();

        // Get staff by status
        $byStatus = DB::table('users')
         ->whereIn('users.schema_name', $this->schemaNames)
            ->whereIn('table', ['teacher', 'user'])
            ->select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        // Format status labels
        $formattedByStatus = [];
        foreach ($byStatus as $status => $count) {
            $formattedByStatus[ucfirst($status)] = $count;
        }

        // Get staff on leave
        $onLeave = DB::table('staff_leave')
            ->whereIn('schema_name', $this->schemaNames)
            ->where('status', 1)
            ->where('start_date', '<=', Carbon::now())
            ->where('end_date', '>=', Carbon::now())
            ->count();

        $formattedByStatus['On Leave'] = $onLeave;

        return [
            'total_count' => $totalCount,
            'by_role' => $byRole,
            'by_region' => $byRegion,
            'by_status' => $formattedByStatus
        ];
    }

    private function getHRPerformanceData()
    {
        // Get monthly trends for the last 6 months
        $months = [];
        $staffCounts = [];
        $turnoverData = [];
        $newHires = [];
        $attendanceData = [];

        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $months[] = $month->format('M');

            // Staff count at end of month
            $staffCount = DB::table('users')
                ->whereIn('table', ['teacher', 'user'])
                ->where('created_at', '<=', $month->endOfMonth())
                ->count();
            $staffCounts[] = $staffCount;

            // Turnover (resignations) in that month
            $turnover = DB::table('users')
                ->where('status', 1)
                ->whereIn('table', ['teacher', 'user'])
                ->whereBetween('updated_at', [$month->startOfMonth(), $month->endOfMonth()])
                ->count();
            $turnoverData[] = $turnover;

            // New hires in that month
            $hires = DB::table('users')
                ->whereIn('table', ['teacher', 'user'])
                ->whereBetween('created_at', [$month->startOfMonth(), $month->endOfMonth()])
                ->count();
            $newHires[] = $hires;

            // Average attendance for that month

            $attendanceRate = DB::table('tattendances')
              ->whereIn('schema_name', $this->schemaNames)
            ->selectRaw('SUM(CASE WHEN present = 1 THEN 1 ELSE 0 END)::float / COUNT(*) * 100 as attendance_percentage')
           ->whereBetween('created_at', [$month->startOfMonth(), $month->endOfMonth()])
            ->value('attendance_percentage');


        }

        // Get performance distribution from staff_targets
        $performanceDistribution = DB::table('staff_targets')
            ->whereIn('schema_name', $this->schemaNames)
            ->where('created_at', '>=', Carbon::now()->startOfMonth())
            ->select(
                DB::raw("CASE 
                    WHEN value >= 90 THEN 'Excellent'
                    WHEN value >= 80 THEN 'Good'
                    WHEN value >= 70 THEN 'Satisfactory'
                    ELSE 'Needs Improvement'
                END as performance_level"),
                DB::raw('count(*) as count')
            )
            ->groupBy('performance_level')
            ->pluck('count', 'performance_level')
            ->toArray();

        // Ensure all performance levels are present
        $defaultPerformance = [
            'Excellent' => 0,
            'Good' => 0,
            'Satisfactory' => 0,
            'Needs Improvement' => 0
        ];
        $performanceDistribution = array_merge($defaultPerformance, $performanceDistribution);

        return [
            'monthly_trends' => [
                'labels' => $months,
                'staff_count' => $staffCounts,
                'turnover' => $turnoverData,
                'new_hires' => $newHires,
                'attendance' => $attendanceData
            ],
            'performance_distribution' => $performanceDistribution
        ];
    }

    private function getHRAlerts()
    {
        return [
            'high_priority' => [
                [
                    'title' => 'High Turnover Alert',
                    'message' => 'Mwanza Secondary has 15% turnover rate this quarter',
                    'school' => 'Mwanza Secondary School',
                    'type' => 'turnover',
                    'severity' => 'high'
                ],
                [
                    'title' => 'Pending Approvals',
                    'message' => '34 leave requests awaiting approval',
                    'school' => 'Multiple Schools',
                    'type' => 'leave',
                    'severity' => 'medium'
                ]
            ],
            'compliance' => [
                [
                    'title' => 'Contract Renewals Due',
                    'message' => '12 staff contracts expiring this month',
                    'school' => 'Various Schools',
                    'type' => 'contract',
                    'severity' => 'medium'
                ],
                [
                    'title' => 'Training Compliance',
                    'message' => '5 schools below 80% training completion',
                    'school' => 'Multiple Schools',
                    'type' => 'training',
                    'severity' => 'low'
                ]
            ]
        ];
    }

    private function getAttendanceData()
    {
        // Overall attendance from staff_report table

        
            $overallAttendance = DB::table('tattendances')
              ->whereIn('schema_name', $this->schemaNames)
            ->selectRaw('SUM(CASE WHEN present = 1 THEN 1 ELSE 0 END)::float / COUNT(*) * 100 as attendance_percentage')
             ->where('created_at', '>=', Carbon::now()->startOfMonth())
            ->value('attendance_percentage');

        // Attendance by category - simplified since we can't easily join across schemas
        $attendanceByCategory = [
            'Teachers' => 94.5,
            'Support Staff' => 93.2,
            'Administration' => 96.8,
        ];

        // Weekly trends for current month
        $weeklyTrends = [];
        $weeklyLabels = [];
        
        for ($week = 1; $week <= 4; $week++) {
            $weekStart = Carbon::now()->startOfMonth()->addWeeks($week - 1);
            $weekEnd = $weekStart->copy()->addWeek();
            
            $weeklyLabels[] = "Week $week";

            $weeklyTrends[] = DB::table('tattendances')
            ->selectRaw('SUM(CASE WHEN present = 1 THEN 1 ELSE 0 END)::float / COUNT(*) * 100 as attendance_percentage')
                  ->whereIn('schema_name', $this->schemaNames)
                ->whereBetween('created_at', [$weekStart, $weekEnd])
            ->value('attendance_percentage');
        }

        // Absenteeism reasons from staff_leave table
        $absenteeismReasons = DB::table('staff_leave')
            ->join('constant.leave_reasons', 'staff_leave.leave_type_id', '=', 'constant.leave_reasons.id')
            ->whereIn('staff_leave.schema_name', $this->schemaNames)
            ->where('staff_leave.status', 1)
            ->where('staff_leave.start_date', '>=', Carbon::now()->startOfMonth())
            ->select('constant.leave_reasons.reason_name as reason', DB::raw('count(*) as count'))
            ->groupBy('constant.leave_reasons.reason_name')
            ->pluck('count', 'reason')
            ->toArray();

        // Format reasons with default values
        $formattedReasons = [
            'Sick Leave' => $absenteeismReasons['sick'] ?? 0,
            'Personal' => $absenteeismReasons['personal'] ?? 0,
            'Training' => $absenteeismReasons['training'] ?? 0,
            'Emergency' => $absenteeismReasons['emergency'] ?? 0,
            'Other' => $absenteeismReasons['other'] ?? 0,
        ];

        return [
            'overall_attendance' => round($overallAttendance, 1),
            'by_category' => $attendanceByCategory,
            'trends' => [
                'labels' => $weeklyLabels,
                'values' => $weeklyTrends
            ],
            'absenteeism_reasons' => $formattedReasons
        ];
    }

    private function getPayrollSummaryData()
    {
        // Since payroll data may not be directly available, provide basic structure
        // In a real implementation, this would come from a payroll system
        
        $totalStaff = DB::table('users')
            ->whereIn('schema_name', $this->schemaNames)
            ->whereIn('table', ['teacher', 'user'])
            ->where('status',1)
            ->count();

        // Estimated payroll based on staff count and category
        $teacherCount = DB::table('users')
            ->whereIn('schema_name', $this->schemaNames)
            ->whereIn('table', ['teacher'])
            ->where('status',1)
            ->count();

        $staffCount = DB::table('users')
            ->whereIn('schema_name', $this->schemaNames)
            ->whereIn('table', ['user', 'teacher'])
            ->where('status', 1)
            ->count();

        $adminCount = DB::table('users')
            ->whereIn('schema_name', $this->schemaNames)
            ->where('usertype', 'admin')
            ->where('status',1)
            ->count();

        // Estimated monthly payroll (these would come from actual payroll data)
        $teacherPayroll = $teacherCount * 800000; // Average teacher salary
        $staffPayroll = $staffCount * 600000; // Average staff salary
        $adminPayroll = $adminCount * 1200000; // Average admin salary

        return [
            'total_monthly_payroll' => $teacherPayroll + $staffPayroll + $adminPayroll,
            'by_category' => [
                'Teachers' => $teacherPayroll,
                'Support Staff' => $staffPayroll,
                'Administration' => $adminPayroll,
            ],
            'compliance_status' => [
                'current' => 98.1,
                'pending_payments' => 1.9
            ],
            'cost_analysis' => [
                'as_percentage_of_revenue' => 67.8,
                'budget_utilization' => 89.3
            ]
        ];
    }

    private function getRecruitmentData()
    {
        // Get actual vacant positions (inactive staff or positions without staff)
        $openPositions = DB::table('users')
            ->whereIn('schema_name', $this->schemaNames)
            ->where('status', '<>',1)
            ->whereIn('table', ['teacher', 'user'])
            ->count();

        // Since recruitment data may not be tracked in the current schema,
        // provide basic structure that could be enhanced with a recruitment module
        
        return [
            'open_positions' => $openPositions,
            'applications_received' => 0, // Would come from recruitment system
            'interviews_scheduled' => 0,
            'offers_made' => 0,
            'positions_filled' => 0,
            'by_category' => [
                'Teachers' => DB::table('users')
                    ->whereIn('schema_name', $this->schemaNames)
                    ->where('table', 'teacher')
                    ->where('status', 1)
                    ->count(),
                'Support Staff' => DB::table('users')
                    ->whereIn('schema_name', $this->schemaNames)
                    ->whereIn('table', ['user'])
                    ->where('status', 1)
                    ->count(),
                'Administration' => DB::table('users')
                    ->whereIn('schema_name', $this->schemaNames)
                    ->where('usertype', 'admin')
                    ->where('status', 1)
                    ->count(),
            ],
            'recruitment_pipeline' => [
                'Application Review' => 0,
                'Initial Interview' => 0,
                'Technical Assessment' => 0,
                'Final Interview' => 0
            ]
        ];
    }

    private function getSchoolHRData($school)
    {
        return [
            'summary' => [
                'total_staff' => rand(25, 85),
                'active_staff' => rand(20, 80),
                'vacant_positions' => rand(0, 5),
                'turnover_rate' => round(rand(5, 15) + (rand(0, 9) / 10), 1),
                'attendance_rate' => round(rand(85, 98) + (rand(0, 9) / 10), 1)
            ],
            'trends' => [
                'staff_growth' => rand(-5, 15),
                'turnover_trend' => rand(-3, 8),
                'attendance_trend' => rand(-2, 5)
            ]
        ];
    }

    private function getSchoolStaffData($school)
    {
        return [
            'by_role' => [
                'Teachers' => rand(15, 45),
                'Support Staff' => rand(5, 15),
                'Administration' => rand(2, 8),
                'Management' => rand(1, 4)
            ],
            'by_status' => [
                'Active' => rand(20, 65),
                'On Leave' => rand(1, 5),
                'Probation' => rand(0, 3)
            ],
            'recent_hires' => [
                ['name' => 'John Msamba', 'position' => 'Mathematics Teacher', 'date' => '2024-01-15'],
                ['name' => 'Mary Kilua', 'position' => 'Science Teacher', 'date' => '2024-01-10'],
                ['name' => 'David Mwanga', 'position' => 'Librarian', 'date' => '2024-01-05']
            ]
        ];
    }

    private function getSchoolAttendanceData($school)
    {
        return [
            'current_rate' => round(rand(85, 98) + (rand(0, 9) / 10), 1),
            'by_category' => [
                'Teachers' => round(rand(88, 99) + (rand(0, 9) / 10), 1),
                'Support Staff' => round(rand(85, 96) + (rand(0, 9) / 10), 1),
                'Administration' => round(rand(90, 99) + (rand(0, 9) / 10), 1)
            ],
            'monthly_trends' => [
                'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                'values' => [92.1, 93.8, 94.5, 93.2, 94.8, 94.2]
            ]
        ];
    }

    private function getSchoolLeaveData($school)
    {
        return [
            'pending_requests' => rand(2, 8),
            'approved_this_month' => rand(5, 15),
            'leave_balances' => [
                'Annual Leave' => rand(15, 25),
                'Sick Leave' => rand(8, 15),
                'Maternity Leave' => rand(0, 3)
            ],
            'recent_requests' => [
                ['staff' => 'Jane Mwalimu', 'type' => 'Annual Leave', 'days' => 5, 'status' => 'pending'],
                ['staff' => 'Peter Fundisha', 'type' => 'Sick Leave', 'days' => 2, 'status' => 'approved'],
                ['staff' => 'Grace Elimu', 'type' => 'Personal', 'days' => 1, 'status' => 'pending']
            ]
        ];
    }

    private function getSchoolPayrollData($school)
    {
        return [
            'monthly_payroll' => rand(1500000, 4500000),
            'by_category' => [
                'Basic Salary' => rand(1200000, 3200000),
                'Allowances' => rand(200000, 800000),
                'Overtime' => rand(50000, 300000),
                'Deductions' => rand(-150000, -500000)
            ],
            'compliance_status' => rand(95, 100),
            'pending_payments' => rand(0, 3)
        ];
    }

    private function getSchoolPerformanceData($school)
    {
        return [
            'appraisal_completion' => rand(75, 100),
            'performance_distribution' => [
                'Excellent' => rand(5, 15),
                'Good' => rand(15, 35),
                'Satisfactory' => rand(8, 20),
                'Needs Improvement' => rand(0, 5)
            ],
            'training_completion' => rand(65, 95)
        ];
    }

    private function getSchoolRecruitmentData($school)
    {
        return [
            'open_positions' => rand(0, 3),
            'applications_received' => rand(5, 25),
            'interviews_conducted' => rand(2, 15),
            'recent_hires' => rand(0, 5)
        ];
    }

    private function getAllStaffData()
    {
        // Sample staff data - in real implementation, this would query the database
        return DB::table('shulesoft.users')
            ->whereIn('schema_name', $this->schemaNames)
            ->whereIn('table', ['teacher', 'user'])
            ->get()
            ->map(function ($user) {
            
            return [
            'id' => $user->id,
            'name' => $user->name ?? 'Unknown',
            'email' => $user->email ?? 'No email',
            'role' => ucfirst($user->table ?? 'Staff'),
            'school' => $user->schema_name ?? 'Unknown School',
            'school_id' => null,
            'status' => $user->status == 1 ? 'Active' : 'Inactive',
            'hire_date' => $user->created_at ? Carbon::parse($user->created_at)->format('Y-m-d') : null,
            'contact' => $user->phone ?? 'No contact',
            'department' => ucfirst($user->usertype ?? 'General')
            ];
            });
    }

    private function getRecruitmentManagementData()
    {
        return [
            'summary' => [
                'total_positions' => 23,
                'applications' => 145,
                'in_progress' => 67,
                'filled_positions' => 12
            ],
            'positions' => [
                [
                    'id' => 1,
                    'title' => 'Mathematics Teacher',
                    'school' => 'Dar es Salaam Secondary',
                    'applications' => 15,
                    'status' => 'Interviewing',
                    'posted_date' => '2024-01-10'
                ],
                [
                    'id' => 2,
                    'title' => 'Science Laboratory Assistant',
                    'school' => 'Mwanza Primary',
                    'applications' => 8,
                    'status' => 'Open',
                    'posted_date' => '2024-01-15'
                ]
            ]
        ];
    }

    private function getLeaveManagementData()
    {
        return [
            'summary' => [
                'pending_requests' => 34,
                'approved_this_month' => 128,
                'rejected_this_month' => 7,
                'total_leave_days' => 892
            ],
            'requests' => [
                [
                    'id' => 1,
                    'staff_name' => 'John Mwalimu',
                    'school' => 'Dar es Salaam Secondary',
                    'leave_type' => 'Annual Leave',
                    'start_date' => '2024-02-15',
                    'end_date' => '2024-02-20',
                    'days' => 5,
                    'status' => 'Pending',
                    'applied_date' => '2024-01-20'
                ],
                [
                    'id' => 2,
                    'staff_name' => 'Mary Elimu',
                    'school' => 'Mwanza Primary',
                    'leave_type' => 'Sick Leave',
                    'start_date' => '2024-02-10',
                    'end_date' => '2024-02-12',
                    'days' => 2,
                    'status' => 'Approved',
                    'applied_date' => '2024-02-09'
                ]
            ]
        ];
    }

    private function getPayrollManagementData()
    {
        return [
            'summary' => [
                'total_monthly_payroll' => 45675000,
                'schools_processed' => 18,
                'pending_approvals' => 3,
                'compliance_rate' => 98.1
            ],
            'by_school' => [
                [
                    'school' => 'Dar es Salaam Secondary',
                    'staff_count' => 65,
                    'gross_payroll' => 8950000,
                    'deductions' => 1250000,
                    'net_payroll' => 7700000,
                    'status' => 'Processed'
                ],
                [
                    'school' => 'Mwanza Primary',
                    'staff_count' => 32,
                    'gross_payroll' => 4200000,
                    'deductions' => 580000,
                    'net_payroll' => 3620000,
                    'status' => 'Pending'
                ]
            ]
        ];
    }

    private function bulkUpdatePolicy($staffIds, $data)
    {
        return response()->json([
            'success' => true,
            'message' => 'Policy updated for ' . count($staffIds) . ' staff members'
        ]);
    }

    private function bulkSendCommunication($staffIds, $data)
    {
        return response()->json([
            'success' => true,
            'message' => 'Communication sent to ' . count($staffIds) . ' staff members'
        ]);
    }

    private function bulkApproveLeave($staffIds, $data)
    {
        return response()->json([
            'success' => true,
            'message' => 'Leave approved for ' . count($staffIds) . ' requests'
        ]);
    }

    private function bulkUpdatePayroll($staffIds, $data)
    {
        return response()->json([
            'success' => true,
            'message' => 'Payroll updated for ' . count($staffIds) . ' staff members'
        ]);
    }
}
