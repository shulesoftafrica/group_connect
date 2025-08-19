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

    public $schemaNames;

    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $user = Auth::user();
        $organization = $user->organization;
     
        $schools = $user->schools()->active()->get();
        
        // Calculate academic metrics
        $this->schemaNames = \DB::table('shulesoft.setting')
            ->join('connect_schools', 'shulesoft.setting.uid', '=', 'connect_schools.school_setting_uid')
            ->whereIn('connect_schools.id', $schools->pluck('id'))
            ->pluck('shulesoft.setting.schema_name');

        $totalStudents = app(\App\Http\Controllers\DashboardController::class)->getTotalStudents($this->schemaNames);
        
        // Calculate Group-wide Academic KPIs
        $academicKPIs = $this->calculateAcademicKPIs($schools);
        
        // Get performance data for visualizations
        $performanceData = $this->getPerformanceData($schools);
        
        // Get alerts and notifications
        $alerts = $this->getAcademicAlerts($schools);
        
        //total exams conducted
   
        // Get total exams conducted
        $totalExamsConducted = \DB::table('shulesoft.mark')
            ->whereBetween('created_at', [$this->start, $this->end])
            ->whereIn('schema_name', $this->schemaNames)
            ->distinct('examID')
            ->count('examID');

        // Get average mark
        $averageMark = \DB::table('shulesoft.mark')
            ->whereBetween('created_at', [$this->start, $this->end])
            ->whereIn('schema_name',  $this->schemaNames)
            ->avg('mark');

        $totalSubjects = \DB::table('shulesoft.mark')
            ->whereBetween('created_at', [$this->start, $this->end])
            ->whereIn('schema_name',  $this->schemaNames)
            ->distinct('subjectID')
            ->count('subjectID');

        // Get recent activities
        $recentActivities = $this->getRecentAcademicActivities();

        return view('academics.dashboard', compact(
            'academicKPIs',
            'totalExamsConducted',
            'totalStudents',
            'performanceData', 
            'averageMark',
            'alerts',
            'recentActivities',
            'schools',
            'totalSubjects'
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
            $settings = $school->schoolSetting;
            $totalStudents += $school->studentsCount() ?? 0;
            $totalAttendance += $school->attendanceRate() ?? 0;
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
            'poor_performing_subjects' => $this->getPoorPerformingSubjects($schools),
            'total_schools' => $schoolCount,
            'pass_rate' => $this->calculateGroupPassRate($schools),
            'top_performing_schools' => $topPerformingSchools,
            'bottom_performing_schools' => $bottomPerformingSchools,
            'schools_performance' => $this->getSubjectsAnalysis($schools),
            'teacher_performance' => $this->getTeacherPerformanceStats($schools),
        ];
    }

    public function getPoorPerformingSubjects($schools)
    {
        // Aggregate poor performing subjects from shulesoft.mark for the current schemas & period
        $poorSubjects = [];

        $schemaNames = $this->schemaNames ?? collect();
        if ($schemaNames instanceof \Illuminate\Support\Collection) {
            $schemaNames = $schemaNames->values()->all();
        }

        if (!empty($schemaNames)) {
            $query = \DB::table('shulesoft.mark_info')
            ->select(
            
                \DB::raw('subject_name'),
                \DB::raw('AVG(mark) as avg_score'),
                \DB::raw('COUNT(DISTINCT schema_name) as school_count'),
                \DB::raw("STRING_AGG(DISTINCT schema_name, ',') as schools_list")
            )
            ->whereIn('schema_name', $schemaNames);

            if (!empty($this->start) && !empty($this->end)) {
            $query->whereBetween('created_at', [$this->start, $this->end]);
            }

            $rows = $query
            ->groupBy('subject_name')
            ->havingRaw('AVG(mark) < ?', [60]) // treat <60 as poor performing, adjust threshold if needed
            ->orderBy('avg_score', 'asc')
            ->get();

            $poorSubjects = $rows->map(function ($r) {
            $schools = [];
            if (!empty($r->schools_list)) {
                $schools = array_values(array_filter(array_map('trim', explode(',', $r->schools_list))));
            }

            return [
                'name' => $r->subject_name,
                'average' => round((float) $r->avg_score, 1),
                'schools' => $schools,
                'school_count' => (int) $r->school_count
            ];
            })->toArray();
        } else {
            // Fallback: derive subjects from provided School models (if they expose marks/subjects)
            foreach ($schools as $school) {
            if (method_exists($school, 'getSubjects')) {
                foreach ($school->getSubjects() as $subject) {
                $avg = $subject->averageMark ?? ($subject->avg ?? null);
                if ($avg !== null && $avg < 60) {
                    $poorSubjects[] = [
                    'name' => $subject->name ?? ($subject->subject ?? 'Unknown Subject'),
                    'average' => round((float) $avg, 1),
                    'schools' => [$school->id],
                    'school_count' => 1
                    ];
                }
                }
            }
            }
        }

        return $poorSubjects;
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
            $settings = $school->schoolSetting;
            $region = $school->schoolSetting->address ?? 'Unknown';
            $schoolType = $school->schoolSetting->school_gender ?? 'Unknown';

            // Group by region
            if (!isset($performanceByRegion[$region])) {
                $performanceByRegion[$region] = [
                    'schools' => 0,
                    'total_students' => $school->studentsCount(),
                    'avg_performance' => 0,
                    'attendance' => 0
                ];
            }
            
            $performanceByRegion[$region]['schools']++;
            $performanceByRegion[$region]['total_students'] += $school->studentsCount()?? 0;
            $performanceByRegion[$region]['avg_performance'] += $school->avgPerformance() ?? 0;
            $performanceByRegion[$region]['attendance'] += $school->attendanceRate() ?? 0;

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
        // Get last 12 months

        $months = [];
        for ($i = 1; $i <= 12; $i++) {
            $months[] = date('M', mktime(0, 0, 0, $i, 1));
        }
 
        // Get schema names for the schools
        $schemaNames = \DB::table('shulesoft.setting')
            ->join('connect_schools', 'shulesoft.setting.uid', '=', 'connect_schools.school_setting_uid')
            ->whereIn('connect_schools.id', $schools->pluck('id'))
            ->pluck('shulesoft.setting.schema_name');

        // Prepare trends for each month
        foreach ($months as $idx => $month) {
        
   
            // Average performance (mark) for the month
            $avgPerformance = \DB::table('shulesoft.mark')
            ->whereBetween('created_at', [$this->start, $this->end])
            ->whereMonth('created_at',$idx + 1)
            ->whereIn('schema_name', $schemaNames)
            ->avg('mark');
         
            $attendanceTrends[] = [
            'month' => $month,
            'attendance' => null, // Skipped as requested
            'performance' => $avgPerformance ? round($avgPerformance, 1) : 0
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
                    'message' => 'has academic index of ' . $academicIndex . '%',
                    'school' => 'Unknown School',
                    'action' => 'Review performance data'
                ];
            }
            
            // Low attendance alert
            if ($attendance < 80) {
                $alerts[] = [
                    'type' => 'danger',
                    'icon' => 'fas fa-user-times',
                    'title' => 'Low Attendance',
                    'message' => ' has attendance of ' . $attendance . '%',
                    'school' => 'unknown',
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

    private function getSubjectsAnalysis($schools)
    {
        // Get schema names for the provided schools
        $schemaNames = \DB::table('shulesoft.setting')
            ->join('connect_schools', 'shulesoft.setting.uid', '=', 'connect_schools.school_setting_uid')
            ->whereIn('connect_schools.id', $schools->pluck('id'))
            ->pluck('shulesoft.setting.schema_name')
            ->unique()
            ->values();

        if ($schemaNames->isEmpty()) {
            $results = [];
        } else {
            // Load setting rows for targets
            // Determine period for last year comparison
            if (!empty($this->start) && !empty($this->end)) {
                $lastStart = \Carbon\Carbon::parse($this->start)->subYear()->toDateTimeString();
                $lastEnd = \Carbon\Carbon::parse($this->end)->subYear()->toDateTimeString();
            } else {
                $lastStart = \Carbon\Carbon::now()->subYear()->startOfYear()->toDateTimeString();
                $lastEnd = \Carbon\Carbon::now()->subYear()->endOfYear()->toDateTimeString();
            }

            // Get current averages grouped by schema_name
            $currentAverages = \DB::table('shulesoft.mark')
                ->select('schema_name', \DB::raw('AVG(mark) as avg_score'))
                ->whereIn('schema_name', $schemaNames)
                ->groupBy('schema_name')
                ->pluck('avg_score', 'schema_name');

            $results = [];

            foreach ($schemaNames as $schema) {
                $avg = isset($currentAverages[$schema]) ? round($currentAverages[$schema], 1) : 0;

                // last year average for same period
                $lastAvg = \DB::table('shulesoft.mark')
                    ->where('schema_name', $schema)
                    ->whereBetween('created_at', [$lastStart, $lastEnd])
                    ->avg('mark');

                $lastAvgRounded = $lastAvg !== null ? round($lastAvg, 1) : 0;

                // YOY growth percentage
                if ($lastAvg && $lastAvg > 0) {
                    $yoy = round((($avg - $lastAvg) / $lastAvg) * 100, 1);
                } else {
                    $yoy = null;
                }

                $results[] = [
                    'schema_name' => $schema,
                    'average' => $avg,
                    'target' => 80,
                    'last_year_average' => $lastAvgRounded,
                    'yoy_growth_percent' => $yoy
                ];
            }
        }

        return $results;
    }

    private function getTeacherPerformanceStats($schools)
    {
        // Get all teacher IDs for the relevant schemas
        $teacherIds = \DB::table('shulesoft.teacher')
            ->whereIn('schema_name', $this->schemaNames)
            ->where('status', 1)
            ->pluck('teacherID');

        // Total teachers
        $totalTeachers = $teacherIds->count();

        // Average workload: average number of subjects per teacher
        $workloadCounts = \DB::table('shulesoft.section_subject_teacher')
            ->whereIn('schema_name', $this->schemaNames)
            ->whereIn('teacherID', $teacherIds)
            ->select('teacherID', \DB::raw('count(*) as subject_count'))
            ->groupBy('teacherID')
            ->pluck('subject_count');

        $avgWorkload = $workloadCounts->count() > 0 ? round($workloadCounts->avg(), 1) : 0;

        // Teachers needing support: teachers with more than average workload
        $teachersNeedingSupport = $workloadCounts->filter(function ($count) use ($avgWorkload) {
            return $count > $avgWorkload;
        })->count();

        // High performers: teachers whose subjects have average mark >= 80
        $highPerformerTeacherIds = \DB::table('shulesoft.mark')
            ->whereIn('schema_name', $this->schemaNames)
            ->select('subjectID')
            ->groupBy('subjectID')
            ->havingRaw('AVG(mark) >= 80')
            ->pluck('subjectID');

        $highPerformerTeachers = \DB::table('shulesoft.section_subject_teacher')
            ->whereIn('schema_name', $this->schemaNames)
            ->whereIn('subject_id', $highPerformerTeacherIds)
            ->whereIn('teacherID', $teacherIds)
            ->distinct('teacherID')
            ->count('teacherID');

        // Attendance rate: average attendance percentage for all teachers
        $attendanceRate = \DB::table('shulesoft.tattendances')
            ->whereIn('schema_name', $this->schemaNames)
            ->whereIn('user_id', $teacherIds)
            ->where('user_table','teacher')
             ->selectRaw('SUM(CASE WHEN present = 1 THEN 1 ELSE 0 END) * 100.0 / COUNT(*) as attendance_percentage')
            ->value('attendance_percentage') ;

        return [
            'total_teachers' => $totalTeachers,
            'avg_workload' => $avgWorkload,
            'teachers_needing_support' => $teachersNeedingSupport,
            'high_performers' => $highPerformerTeachers,
            'attendance_rate' => $attendanceRate ? round($attendanceRate, 1) : 0
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
