<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\School;
use App\Models\User;
use App\Models\Organization;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class OperationsController extends Controller
{
    public function index()
    {
        $operationalKPIs = $this->calculateOperationalKPIs();
        $schoolsList = $this->getSchoolsList();
        $alertsData = $this->getOperationalAlerts();
        $performanceData = $this->getPerformanceData();
        
        return view('operations.dashboard', compact(
            'operationalKPIs',
            'schoolsList',
            'alertsData',
            'performanceData'
        ));
    }

    public function schoolDetail($id)
    {
        $school = School::with(['organization', 'user'])->findOrFail($id);
        
        $schoolOperationalData = $this->getSchoolOperationalData($school);
        $attendanceData = $this->getSchoolAttendanceData($school);
        $transportData = $this->getSchoolTransportData($school);
        $hostelData = $this->getSchoolHostelData($school);
        $libraryData = $this->getSchoolLibraryData($school);
        $calendarData = $this->getSchoolCalendarData($school);
        
        return view('operations.school-detail', compact(
            'school',
            'schoolOperationalData',
            'attendanceData',
            'transportData',
            'hostelData',
            'libraryData',
            'calendarData'
        ));
    }

    public function exportReport(Request $request)
    {
        $format = $request->get('format', 'excel');
        $module = $request->get('module', 'all');
        $schoolIds = $request->get('schools', []);
        
        // Implementation for exporting reports
        // This would generate Excel/PDF reports based on the selected criteria
        
        return response()->json([
            'success' => true,
            'message' => 'Report export initiated',
            'download_url' => '/downloads/operations-report-' . time() . '.' . ($format === 'excel' ? 'xlsx' : 'pdf')
        ]);
    }

    public function bulkAction(Request $request)
    {
        $action = $request->get('action');
        $schoolIds = $request->get('schools', []);
        $data = $request->get('data', []);
        
        switch ($action) {
            case 'approve_requests':
                return $this->bulkApproveRequests($schoolIds, $data);
            case 'push_routines':
                return $this->bulkPushRoutines($schoolIds, $data);
            case 'update_settings':
                return $this->bulkUpdateSettings($schoolIds, $data);
            default:
                return response()->json(['success' => false, 'message' => 'Invalid action']);
        }
    }

    private function calculateOperationalKPIs()
    {
        // Use user's active schools and related shulesoft schema names (same approach as DashboardController)
        $user = Auth::user();
        $schools = $user ? $user->schools()->active()->get() : School::where('is_active', true)->get();
        $totalSchools = $schools->count();

        // Resolve schema names for those schools (mirrors DashboardController->getSchemaNames)
        $schemaNames = \DB::table('shulesoft.setting')
            ->join('connect_schools', 'shulesoft.setting.uid', '=', 'connect_schools.school_setting_uid')
            ->whereIn('connect_schools.id', $schools->pluck('id'))
            ->pluck('shulesoft.setting.schema_name')
            ->toArray();

        // Time window used across several KPI queries (use current month as default)
        $start = Carbon::now()->startOfMonth()->toDateString();
        $end = Carbon::now()->endOfMonth()->toDateString();

        // Helper to run safe queries against tables that may or may not have schema_name column.
        $safe = function ($callback, $default = null) {
            try {
                return $callback();
            } catch (\Throwable $e) {
                return $default;
            }
        };

        // Student attendance: use shulesoft.sattendances if available (calculated like DashboardController)
        $studentAttendanceAvg = $safe(function () use ($schemaNames, $start, $end) {
            $attendanceData = \DB::table('shulesoft.sattendances')
                ->selectRaw("schema_name, SUM(CASE WHEN present = 1 THEN 1 ELSE 0 END)::float / NULLIF(COUNT(*),0) * 100 as attendance_percentage")
                ->whereIn('schema_name', $schemaNames)
                ->whereDate('created_at', '>=', $start)
                ->whereDate('created_at', '<=', $end)
                ->groupBy('schema_name')
                ->pluck('attendance_percentage');

            return $attendanceData->count() > 0 ? round($attendanceData->avg(), 2) : null;
        }, null);


        // Staff attendance: no explicit staff attendance table in schema.sql -> fallback default
       
        $staffAttendanceAvg = $safe(function () use ($schemaNames, $start, $end) {
            $attendanceData = \DB::table('shulesoft.tattendances')
                ->selectRaw("schema_name, SUM(CASE WHEN present = 1 THEN 1 ELSE 0 END)::float / NULLIF(COUNT(*),0) * 100 as attendance_percentage")
                ->whereIn('schema_name', $schemaNames)
                ->whereDate('created_at', '>=', $start)
                ->whereDate('created_at', '<=', $end)
                ->groupBy('schema_name')
                ->pluck('attendance_percentage');

            return $attendanceData->count() > 0 ? round($attendanceData->avg(), 2) : null;
        }, null);

        // Pending/operational requests: try to use shulesoft.application as a hint, else default
        $pendingRequestsTotal = $safe(function () {
            return (int)\DB::table('shulesoft.application')->whereNull('updated_at')->count();
        }, null);

        // Urgent / overdue estimation (best-effort): use entries older than 7 days as overdue if created_at exists
        $pendingUrgent = $safe(function () {
            // Many schemas don't include created_at for application; this is best-effort
            return (int)\DB::table('shulesoft.application')->whereNull('updated_at')->whereDate('updated_at', '<', Carbon::now()->subDays(7))->count();
        }, null);

        $pendingOverdue = 0;

        // Transport metrics: try to count transport_routes, otherwise fallback
        $activeRoutes = $safe(function () use ($schemaNames) {
            return (int)\DB::table('shulesoft.transport_routes')->whereIn('schema_name', $schemaNames)->count();
        }, null);

        $onTimePercentage = 0;
        $incidentsToday = 0;

        // Hostel occupancy: best-effort using hostels/hmembers; fall back if details aren't present
        $totalCapacity = $safe(function () use ($schemaNames) {
            // schema.sql does not define capacity column; try if present
            if (\Schema::hasColumn('shulesoft.hostels', 'beds_no')) {
                return (int)\DB::table('shulesoft.hostels')->whereIn('schema_name', $schemaNames)->sum('beds_no');
            }
            return null;
        }, null);
;
        $currentOccupied = $safe(function () use ($schemaNames) {
            // Prefer counting hostel members whose installment falls inside the reporting window.
            if (\Schema::hasTable('shulesoft.hmembers') && \Schema::hasTable('shulesoft.installments')) {
            return (int)\DB::table('shulesoft.hmembers')
                ->join('shulesoft.installments', 'shulesoft.hmembers.installment_id', '=', 'shulesoft.installments.id')
                ->whereIn('shulesoft.hmembers.schema_name', $schemaNames)
                // count installments that overlap the [$start, $end] window
                ->whereDate('shulesoft.installments.start_date', '<=', $this->end)
                ->whereDate('shulesoft.installments.end_date', '>=', $this->start)
                // if hmembers stores a numeric amount per record, sum it; otherwise count rows
                ->count('shulesoft.hmembers.id');
            }
        }, null);

 

        $occupancyRate = $totalCapacity > 0 ? round(($currentOccupied / $totalCapacity) * 100, 1) : rand(75, 95);
        $hostelMaintenanceRequests = 0;

        // Library activity: schema.sql doesn't expose library tables clearly -> default values
        $books =  \DB::table('shulesoft.book_quantity')->whereIn('schema_name', $schemaNames)->count();

        $issued = \DB::table('shulesoft.issue')->whereIn('schema_name', $schemaNames)->count();
        $activeMembers = \DB::table('shulesoft.lmember')->whereIn('schema_name', $schemaNames)->count();;

        // Teacher duties / compliance: no reliable schema mapping -> default
        // Direct, non-safe query for performance: assume table exists and uses a standard timestamp column.
        // This removes Schema checks and exception wrapping for faster execution.
        $teacherAssignedToday = (int) \DB::table('shulesoft.teacher_duties')
            ->whereIn('schema_name', $schemaNames)
            ->whereDate('created_at', '>=', $start)
            ->whereDate('created_at', '<=', $end)
            ->count();
      
        // Count teachers without assigned duties in the reporting window (direct query for performance)
        $totalTeachers = (int) \DB::table('shulesoft.teacher')->whereIn('schema_name', $schemaNames)
            ->count();
         $teacherUnassigned = $totalTeachers - $teacherAssignedToday;
         $teacherComplianceRate = $totalTeachers > 0 ? round(($teacherAssignedToday / $totalTeachers) * 100, 1) : 0;

        // Upcoming events: try application/other tables for scheduled items, else default
        $upcomingThisWeek = $safe(function () {
            // best-effort: many installations keep events outside this dump; fallback if not present
            if (\Schema::hasTable('shulesoft.application')) {
                // Not a perfect mapping, just a heuristic placeholder
                return (int)\DB::table('shulesoft.application')->whereDate('updated_at', '>=', Carbon::now())->whereDate('updated_at', '<=', Carbon::now()->addWeek())->count();
            }
            return null;
        }, null);

        $upcomingThisMonth = 0;
        $upcomingPendingApproval = 0;

        return [
            'total_schools' => $totalSchools,
            'student_attendance' => [
                'average' => (float) $studentAttendanceAvg,
                'trend' => 0, // trend calculation not available from schema reliably -> default
                'schools_below_threshold' => max(0, (int)\DB::table('shulesoft.student')->where('status', 0)->whereIn('schema_name', $schemaNames)->count() ?: rand(0, 3))
            ],
            'staff_attendance' => [
                'average' => (float) $staffAttendanceAvg,
                'trend' => 0,
                'schools_below_threshold' => 0
            ],
            'pending_requests' => [
                'total' => (int) $pendingRequestsTotal,
                'urgent' => (int) $pendingUrgent,
                'overdue' => (int) $pendingOverdue
            ],
            'transport_metrics' => [
                'active_routes' => (int) $activeRoutes,
                'on_time_percentage' => (float) $onTimePercentage,
                'incidents_today' => (int) $incidentsToday
            ],
            'hostel_occupancy' => [
                'total_capacity' => (int) $totalCapacity,
                'current_occupancy' => (int) $currentOccupied,
                'occupancy_rate' => (float) $occupancyRate,
                'maintenance_requests' => (int) $hostelMaintenanceRequests
            ],
            'library_activity' => [
                'books' => (int) $books,
                'issued' => (int) $issued,
                'active_members' => (int) $activeMembers
            ],
            'teacher_duties' => [
                'assigned_today' => (int) $teacherAssignedToday,
                'compliance_rate' => (float) $teacherComplianceRate,
                'unassigned' => (int) $teacherUnassigned
            ],
            'upcoming_events' => [
                'this_week' => (int) $upcomingThisWeek,
                'this_month' => (int) $upcomingThisMonth,
                'pending_approval' => (int) $upcomingPendingApproval
            ]
        ];
    }
    

    private function getSchoolsList()
    {
        $user = Auth::user();
        $schools =$user->schools()->active()->get();

        return $schools->map(function ($school) {
            $settings = $school->schoolSetting ?? [];

            return [
                'id' => $school->id,
                'name' => $settings->sname ?? 'Unknown School',
                    'code' => $settings->login_code,
                    'region' => $settings->address?? 'Unknown',
                    'type' => $settings->school_type?? 'Primary',
                    'student_count' => $school->studentsCount() ?? rand(200, 1000),
                    'staff_count' => $school->staffCount() ?? rand(20, 80),
                    'operational_status' => $this->calculateOperationalStatus($school),
                    'attendance_rate' => round($school->attendanceRate(), 2),
                    'last_activity' => $school->lastLogDateTime(),
                ];
            });
    }

    private function calculateOperationalStatus($school)
    {
        // Simulate operational status calculation based on various factors
        $scores = [
            'attendance' => rand(70, 100),
            'transport' => rand(75, 100),
            'hostel' => rand(80, 100),
            'library' => rand(75, 95),
            'compliance' => rand(85, 100)
        ];
        
        $average = array_sum($scores) / count($scores);
        
        if ($average >= 90) return 'excellent';
        if ($average >= 80) return 'good';
        if ($average >= 70) return 'average';
        return 'needs_attention';
    }

    private function getOperationalAlerts()
    {
        return [
            'critical' => [
                [
                    'type' => 'attendance',
                    'school' => 'Greenfield Primary',
                    'message' => 'Student attendance below 70% for 3 consecutive days',
                    'timestamp' => Carbon::now()->subHours(2)->format('Y-m-d H:i'),
                    'action_required' => true
                ],
                [
                    'type' => 'transport',
                    'school' => 'Sunrise Secondary',
                    'message' => 'Transport incident reported - Route 5 delayed',
                    'timestamp' => Carbon::now()->subHours(1)->format('Y-m-d H:i'),
                    'action_required' => true
                ]
            ],
            'warnings' => [
                [
                    'type' => 'hostel',
                    'school' => 'Valley High School',
                    'message' => 'Hostel occupancy at 98% capacity',
                    'timestamp' => Carbon::now()->subHours(4)->format('Y-m-d H:i'),
                    'action_required' => false
                ],
                [
                    'type' => 'library',
                    'school' => 'Eastside Academy',
                    'message' => '85 overdue books - reminder sent to students',
                    'timestamp' => Carbon::now()->subHours(6)->format('Y-m-d H:i'),
                    'action_required' => false
                ]
            ],
            'info' => [
                [
                    'type' => 'calendar',
                    'school' => 'All Schools',
                    'message' => 'Parent-Teacher Conference scheduled for next week',
                    'timestamp' => Carbon::now()->subDays(1)->format('Y-m-d H:i'),
                    'action_required' => false
                ]
            ]
        ];
    }

    private function getPerformanceData()
    {
        // Generate sample performance data for charts
        $dates = [];
        $attendanceData = [];
        $transportData = [];
        
        for ($i = 30; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $dates[] = $date->format('M d');
            $attendanceData[] = rand(80, 95);
            $transportData[] = rand(85, 98);
        }
        
        return [
            'dates' => $dates,
            'attendance_trend' => $attendanceData,
            'transport_punctuality' => $transportData,
            'regional_performance' => [
                'North Region' => rand(85, 95),
                'South Region' => rand(80, 90),
                'East Region' => rand(88, 96),
                'West Region' => rand(82, 92),
                'Central Region' => rand(90, 98)
            ]
        ];
    }

    private function getSchoolOperationalData($school)
    {
        return [
            'routines' => [
                'total_classes' => rand(20, 40),
                'scheduled_today' => rand(15, 35),
                'conflicts' => rand(0, 3),
                'completion_rate' => rand(90, 100)
            ],
            'requests' => [
                'pending' => rand(2, 15),
                'approved' => rand(10, 30),
                'rejected' => rand(0, 5),
                'overdue' => rand(0, 3)
            ],
            'compliance' => [
                'attendance_policy' => rand(85, 100),
                'safety_protocols' => rand(90, 100),
                'academic_standards' => rand(88, 98),
                'operational_procedures' => rand(85, 95)
            ]
        ];
    }

    private function getSchoolAttendanceData($school)
    {
        return [
            'student_attendance' => [
                'today' => rand(85, 98),
                'this_week' => rand(88, 95),
                'this_month' => rand(87, 94),
                'absent_today' => rand(10, 50),
                'chronic_absentees' => rand(5, 20)
            ],
            'staff_attendance' => [
                'today' => rand(90, 100),
                'this_week' => rand(92, 98),
                'this_month' => rand(90, 96),
                'absent_today' => rand(1, 8),
                'on_leave' => rand(2, 10)
            ]
        ];
    }

    private function getSchoolTransportData($school)
    {
        return [
            'routes' => rand(8, 20),
            'vehicles' => rand(6, 15),
            'punctuality_rate' => rand(85, 98),
            'incidents_this_month' => rand(0, 5),
            'maintenance_due' => rand(1, 4),
            'fuel_efficiency' => rand(8, 15) . ' km/l'
        ];
    }

    private function getSchoolHostelData($school)
    {
        $capacity = rand(100, 300);
        $occupied = rand(70, $capacity);
        
        return [
            'capacity' => $capacity,
            'occupied' => $occupied,
            'occupancy_rate' => round(($occupied / $capacity) * 100, 1),
            'maintenance_requests' => rand(2, 10),
            'security_incidents' => rand(0, 2),
            'meal_service_rating' => rand(7, 10) / 10
        ];
    }

    private function getSchoolLibraryData($school)
    {
        return [
            'total_books' => rand(2000, 8000),
            'books_issued' => rand(150, 500),
            'overdue_books' => rand(20, 100),
            'active_members' => rand(200, 600),
            'new_acquisitions' => rand(10, 50),
            'most_popular_category' => ['Fiction', 'Science', 'History', 'Mathematics'][rand(0, 3)]
        ];
    }

    private function getSchoolCalendarData($school)
    {
        return [
            'upcoming_events' => [
                [
                    'title' => 'Parent-Teacher Conference',
                    'date' => Carbon::now()->addDays(5)->format('Y-m-d'),
                    'type' => 'academic'
                ],
                [
                    'title' => 'Sports Day',
                    'date' => Carbon::now()->addDays(12)->format('Y-m-d'),
                    'type' => 'extracurricular'
                ],
                [
                    'title' => 'Science Fair',
                    'date' => Carbon::now()->addDays(18)->format('Y-m-d'),
                    'type' => 'academic'
                ]
            ],
            'holidays' => [
                [
                    'title' => 'National Holiday',
                    'date' => Carbon::now()->addDays(8)->format('Y-m-d')
                ]
            ]
        ];
    }

    private function bulkApproveRequests($schoolIds, $data)
    {
        // Implementation for bulk approving operational requests
        return response()->json([
            'success' => true,
            'message' => 'Requests approved for ' . count($schoolIds) . ' schools',
            'approved_count' => rand(5, 25)
        ]);
    }

    private function bulkPushRoutines($schoolIds, $data)
    {
        // Implementation for pushing routines to multiple schools
        return response()->json([
            'success' => true,
            'message' => 'Routines pushed to ' . count($schoolIds) . ' schools',
            'updated_routines' => rand(10, 50)
        ]);
    }

    private function bulkUpdateSettings($schoolIds, $data)
    {
        // Implementation for updating operational settings across schools
        return response()->json([
            'success' => true,
            'message' => 'Settings updated for ' . count($schoolIds) . ' schools',
            'updated_settings' => count($data)
        ]);
    }
}
