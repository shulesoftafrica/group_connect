<?php

namespace App\Http\Controllers;

use App\Models\School;
use App\Models\User;
use App\Models\Organization;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AcademicController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $organization = $user->organization;
        
        // Get all schools in the organization
        $schools = School::where('connect_organization_id', $organization->id)
                        ->where('is_active', true)
                        ->get();

        // Calculate Group-wide Academic KPIs
        $academicKPIs = $this->calculateAcademicKPIs($schools);
        
        // Get performance data for visualizations
        $performanceData = $this->getPerformanceData($schools);
        
        // Get alerts and notifications
        $alerts = $this->getAcademicAlerts($schools);
        
        // Get recent activities
        $recentActivities = $this->getRecentAcademicActivities();

        return view('academics.dashboard', compact(
            'academicKPIs',
            'performanceData', 
            'alerts',
            'recentActivities',
            'schools'
        ));
    }

    private function calculateAcademicKPIs($schools)
    {
        $totalStudents = 0;
        $totalAttendance = 0;
        $totalAcademicIndex = 0;
        $schoolCount = $schools->count();
        
        $topPerformingSchools = [];
        $bottomPerformingSchools = [];
        
        foreach ($schools as $school) {
            $settings = $school->settings;
            $totalStudents += $settings['total_students'] ?? 0;
            $totalAttendance += $settings['attendance_percentage'] ?? 0;
            $totalAcademicIndex += $settings['academic_index'] ?? 0;
        }

        // Sort schools by academic performance
        $sortedByPerformance = $schools->sortByDesc(function($school) {
            return $school->settings['academic_index'] ?? 0;
        });

        $topPerformingSchools = $sortedByPerformance->take(3)->values();
        $bottomPerformingSchools = $sortedByPerformance->reverse()->take(3)->values();

        return [
            'total_students' => $totalStudents,
            'average_attendance' => $schoolCount > 0 ? round($totalAttendance / $schoolCount, 1) : 0,
            'average_academic_index' => $schoolCount > 0 ? round($totalAcademicIndex / $schoolCount, 1) : 0,
            'total_schools' => $schoolCount,
            'pass_rate' => $this->calculateGroupPassRate($schools),
            'top_performing_schools' => $topPerformingSchools,
            'bottom_performing_schools' => $bottomPerformingSchools,
            'subjects_analysis' => $this->getSubjectsAnalysis(),
            'teacher_performance' => $this->getTeacherPerformanceStats(),
        ];
    }

    private function calculateGroupPassRate($schools)
    {
        // Mock calculation - in real implementation, this would query actual exam results
        $totalPass = 0;
        $totalStudents = 0;
        
        foreach ($schools as $school) {
            $students = $school->settings['total_students'] ?? 0;
            $academicIndex = $school->settings['academic_index'] ?? 0;
            
            // Estimate pass rate based on academic index
            $passRate = min(100, max(0, ($academicIndex / 100) * 95));
            $totalPass += ($students * $passRate / 100);
            $totalStudents += $students;
        }
        
        return $totalStudents > 0 ? round(($totalPass / $totalStudents) * 100, 1) : 0;
    }

    private function getPerformanceData($schools)
    {
        $performanceByRegion = [];
        $performanceBySchoolType = [];
        $attendanceTrends = [];
        $examCompletionRates = [];

        foreach ($schools as $school) {
            $settings = $school->settings;
            $region = $settings['region'] ?? 'Unknown';
            $schoolType = $settings['school_type'] ?? 'Unknown';
            
            // Group by region
            if (!isset($performanceByRegion[$region])) {
                $performanceByRegion[$region] = [
                    'schools' => 0,
                    'total_students' => 0,
                    'avg_performance' => 0,
                    'attendance' => 0
                ];
            }
            
            $performanceByRegion[$region]['schools']++;
            $performanceByRegion[$region]['total_students'] += $settings['total_students'] ?? 0;
            $performanceByRegion[$region]['avg_performance'] += $settings['academic_index'] ?? 0;
            $performanceByRegion[$region]['attendance'] += $settings['attendance_percentage'] ?? 0;
            
            // Group by school type
            if (!isset($performanceBySchoolType[$schoolType])) {
                $performanceBySchoolType[$schoolType] = [
                    'count' => 0,
                    'avg_performance' => 0
                ];
            }
            
            $performanceBySchoolType[$schoolType]['count']++;
            $performanceBySchoolType[$schoolType]['avg_performance'] += $settings['academic_index'] ?? 0;
        }

        // Calculate averages
        foreach ($performanceByRegion as $region => &$data) {
            if ($data['schools'] > 0) {
                $data['avg_performance'] = round($data['avg_performance'] / $data['schools'], 1);
                $data['attendance'] = round($data['attendance'] / $data['schools'], 1);
            }
        }

        foreach ($performanceBySchoolType as $type => &$data) {
            if ($data['count'] > 0) {
                $data['avg_performance'] = round($data['avg_performance'] / $data['count'], 1);
            }
        }

        // Generate mock trend data for last 6 months
        $months = ['Aug', 'Jul', 'Jun', 'May', 'Apr', 'Mar'];
        foreach ($months as $month) {
            $attendanceTrends[] = [
                'month' => $month,
                'attendance' => rand(75, 95),
                'performance' => rand(70, 90)
            ];
        }

        return [
            'performance_by_region' => $performanceByRegion,
            'performance_by_school_type' => $performanceBySchoolType,
            'attendance_trends' => array_reverse($attendanceTrends),
            'exam_completion_rates' => $this->getExamCompletionData(),
            'subject_performance' => $this->getSubjectPerformanceData()
        ];
    }

    private function getAcademicAlerts($schools)
    {
        $alerts = [];
        
        foreach ($schools as $school) {
            $settings = $school->settings;
            $academicIndex = $settings['academic_index'] ?? 0;
            $attendance = $settings['attendance_percentage'] ?? 0;
            
            // Low performance alert
            if ($academicIndex < 75) {
                $alerts[] = [
                    'type' => 'warning',
                    'icon' => 'fas fa-exclamation-triangle',
                    'title' => 'Low Academic Performance',
                    'message' => $settings['name'] . ' has academic index of ' . $academicIndex . '%',
                    'school' => $settings['name'],
                    'action' => 'Review performance data'
                ];
            }
            
            // Low attendance alert
            if ($attendance < 80) {
                $alerts[] = [
                    'type' => 'danger',
                    'icon' => 'fas fa-user-times',
                    'title' => 'Low Attendance',
                    'message' => $settings['name'] . ' has attendance of ' . $attendance . '%',
                    'school' => $settings['name'],
                    'action' => 'Contact school administration'
                ];
            }
        }

        // Add mock alerts for other scenarios
        $alerts[] = [
            'type' => 'info',
            'icon' => 'fas fa-clock',
            'title' => 'Pending Grades',
            'message' => '5 schools have pending grade submissions for Term 2',
            'school' => 'Multiple Schools',
            'action' => 'Follow up with academic heads'
        ];

        return $alerts;
    }

    private function getRecentAcademicActivities()
    {
        return [
            [
                'type' => 'exam',
                'icon' => 'fas fa-file-alt',
                'title' => 'Term 2 Exams Completed',
                'description' => 'All schools completed term 2 examinations',
                'time' => '2 hours ago',
                'status' => 'completed'
            ],
            [
                'type' => 'policy',
                'icon' => 'fas fa-scroll',
                'title' => 'New Grading Policy Pushed',
                'description' => 'Updated grading policy sent to all schools',
                'time' => '1 day ago',
                'status' => 'distributed'
            ],
            [
                'type' => 'meeting',
                'icon' => 'fas fa-users',
                'title' => 'Academic Heads Meeting',
                'description' => 'Quarterly academic review meeting scheduled',
                'time' => '3 days ago',
                'status' => 'scheduled'
            ],
            [
                'type' => 'report',
                'icon' => 'fas fa-chart-line',
                'title' => 'Performance Report Generated',
                'description' => 'Monthly academic performance report ready',
                'time' => '1 week ago',
                'status' => 'ready'
            ]
        ];
    }

    private function getSubjectsAnalysis()
    {
        return [
            ['subject' => 'Mathematics', 'avg_score' => 78.5, 'pass_rate' => 85, 'trend' => 'up'],
            ['subject' => 'English', 'avg_score' => 82.1, 'pass_rate' => 88, 'trend' => 'up'],
            ['subject' => 'Science', 'avg_score' => 75.3, 'pass_rate' => 82, 'trend' => 'down'],
            ['subject' => 'Social Studies', 'avg_score' => 79.8, 'pass_rate' => 86, 'trend' => 'stable'],
            ['subject' => 'ICT', 'avg_score' => 73.2, 'pass_rate' => 79, 'trend' => 'up']
        ];
    }

    private function getTeacherPerformanceStats()
    {
        return [
            'total_teachers' => 245,
            'avg_workload' => 18.5, // subjects per teacher
            'teachers_needing_support' => 12,
            'high_performers' => 28,
            'attendance_rate' => 94.2
        ];
    }

    private function getExamCompletionData()
    {
        return [
            'completed' => 85,
            'in_progress' => 10,
            'pending' => 5,
            'grading_turnaround' => 3.2 // days average
        ];
    }

    private function getSubjectPerformanceData()
    {
        return [
            'Mathematics' => ['Central' => 78, 'Eastern' => 75, 'Western' => 82, 'Northern' => 71],
            'English' => ['Central' => 85, 'Eastern' => 80, 'Western' => 88, 'Northern' => 76],
            'Science' => ['Central' => 73, 'Eastern' => 71, 'Western' => 79, 'Northern' => 68],
            'Social Studies' => ['Central' => 81, 'Eastern' => 78, 'Western' => 84, 'Northern' => 75]
        ];
    }

    public function schoolDetail($id)
    {
        $school = School::findOrFail($id);
        $settings = $school->settings;
        
        // Get detailed academic data for this school
        $academicDetails = $this->getSchoolAcademicDetails($school);
        
        return view('academics.school-detail', compact('school', 'settings', 'academicDetails'));
    }

    private function getSchoolAcademicDetails($school)
    {
        $settings = $school->settings;
        
        return [
            'performance_overview' => [
                'academic_index' => $settings['academic_index'] ?? 0,
                'attendance_rate' => $settings['attendance_percentage'] ?? 0,
                'total_students' => $settings['total_students'] ?? 0,
                'pass_rate' => min(100, max(0, ($settings['academic_index'] ?? 0) * 0.95))
            ],
            'subject_breakdown' => [
                'Mathematics' => ['avg_score' => 78, 'pass_rate' => 85, 'students' => 120],
                'English' => ['avg_score' => 82, 'pass_rate' => 88, 'students' => 125],
                'Science' => ['avg_score' => 75, 'pass_rate' => 82, 'students' => 115],
                'Social Studies' => ['avg_score' => 80, 'pass_rate' => 86, 'students' => 118]
            ],
            'class_performance' => [
                'Primary 1' => ['students' => 45, 'avg_score' => 75, 'attendance' => 92],
                'Primary 2' => ['students' => 42, 'avg_score' => 78, 'attendance' => 89],
                'Primary 3' => ['students' => 38, 'avg_score' => 80, 'attendance' => 91],
                'Primary 4' => ['students' => 35, 'avg_score' => 77, 'attendance' => 88]
            ],
            'trends' => [
                'performance' => [85, 82, 78, 80, 83, 85], // last 6 months
                'attendance' => [88, 85, 89, 91, 87, 90]
            ]
        ];
    }

    public function exportReport(Request $request)
    {
        $format = $request->get('format', 'excel');
        $reportType = $request->get('type', 'overview');
        
        // Generate report based on type and format
        // Implementation would create Excel/PDF export
        
        return response()->json([
            'success' => true,
            'message' => 'Report generation initiated',
            'download_url' => '/downloads/academic-report-' . date('Y-m-d') . '.' . ($format === 'excel' ? 'xlsx' : 'pdf')
        ]);
    }
}
