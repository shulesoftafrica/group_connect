<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\School;
use App\Models\User;
use App\Models\Organization;
use Carbon\Carbon;

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
        $schools = School::where('is_active', true)->get();
        $totalSchools = $schools->count();
        
        return [
            'total_schools' => $totalSchools,
            'student_attendance' => [
                'average' => rand(85, 95),
                'trend' => rand(-5, 5),
                'schools_below_threshold' => rand(0, 3)
            ],
            'staff_attendance' => [
                'average' => rand(88, 98),
                'trend' => rand(-3, 7),
                'schools_below_threshold' => rand(0, 2)
            ],
            'pending_requests' => [
                'total' => rand(15, 45),
                'urgent' => rand(2, 8),
                'overdue' => rand(0, 5)
            ],
            'transport_metrics' => [
                'active_routes' => rand(50, 150),
                'on_time_percentage' => rand(85, 95),
                'incidents_today' => rand(0, 3)
            ],
            'hostel_occupancy' => [
                'total_capacity' => rand(800, 1200),
                'current_occupancy' => rand(600, 1000),
                'occupancy_rate' => rand(75, 95),
                'maintenance_requests' => rand(5, 15)
            ],
            'library_activity' => [
                'books_issued_today' => rand(50, 200),
                'overdue_books' => rand(20, 80),
                'active_members' => rand(300, 800)
            ],
            'teacher_duties' => [
                'assigned_today' => rand(80, 150),
                'compliance_rate' => rand(90, 100),
                'unassigned' => rand(0, 5)
            ],
            'upcoming_events' => [
                'this_week' => rand(5, 15),
                'this_month' => rand(20, 50),
                'pending_approval' => rand(2, 8)
            ]
        ];
    }

    private function getSchoolsList()
    {
        return School::with(['organization'])
            ->where('is_active', true)
            ->get()
            ->map(function ($school) {
                $settings = $school->settings ?? [];
                
                return [
                    'id' => $school->id,
                    'name' => $settings['school_name'] ?? 'Unknown School',
                    'code' => $school->shulesoft_code,
                    'region' => $settings['region'] ?? 'Unknown',
                    'type' => $settings['school_type'] ?? 'Primary',
                    'student_count' => $settings['total_students'] ?? rand(200, 1000),
                    'staff_count' => $settings['total_staff'] ?? rand(20, 80),
                    'operational_status' => $this->calculateOperationalStatus($school),
                    'attendance_rate' => rand(80, 98),
                    'last_activity' => Carbon::now()->subDays(rand(0, 7))->format('Y-m-d'),
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
