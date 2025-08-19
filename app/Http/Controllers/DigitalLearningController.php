<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\School;
use App\Models\User;
use App\Models\Organization;
use Illuminate\Support\Facades\DB;

class DigitalLearningController extends Controller
{
    public function dashboard()
    {
        $data = $this->calculateDigitalLearningKPIs();
        $data['schools'] = $this->getSchoolsDigitalLearningList();
        $data['aiExams'] = $this->getAIExamsList();
        $data['contentStats'] = $this->getContentStatistics();
        
        return view('digital-learning.dashboard', $data);
    }

    public function exams()
    {
        // Initialize all variables used in the exams view to prevent errors

        // Exams list
        $exams = $this->getAIExamsList();

        // Schools for select options
        $schools = School::all()->map(function($school) {
            return [
            'id' => $school->id,
            'name' => $school->name ?? 'School ' . $school->id,
            'location' => $school->address ?? 'Location ' . $school->id,
            ];
        });

        // Exam templates (example static data, replace with actual if available)
        $exam_templates = [
            [
            'id' => 1,
            'name' => 'Standard Math Exam',
            'description' => '20 questions, 90 minutes, mixed difficulty.',
            'duration' => 90,
            'questions' => 20,
            'difficulty' => 'Mixed',
            ],
            [
            'id' => 2,
            'name' => 'English Proficiency Test',
            'description' => '15 questions, 60 minutes, medium difficulty.',
            'duration' => 60,
            'questions' => 15,
            'difficulty' => 'Medium',
            ],
            [
            'id' => 3,
            'name' => 'Science Practical',
            'description' => '25 questions, 120 minutes, hard difficulty.',
            'duration' => 120,
            'questions' => 25,
            'difficulty' => 'Hard',
            ],
        ];

        // Quick stats (calculate from $exams or set static for demo)
        $total_exams = count($exams);
        $active_exams = collect($exams)->whereIn('status', ['Scheduled', 'In Progress'])->count();
        $total_participants = collect($exams)->sum('students_registered');
        $avg_completion = $total_exams > 0
            ? round(collect($exams)->whereNotNull('completion_rate')->avg('completion_rate'), 1)
            : 0;

        // Add extra fields to $exams for blade compatibility
        $exams = collect($exams)->map(function($exam) {
            // Participation rate and eligible students (simulate for demo)
            $exam['eligible_students'] = $exam['students_registered'] ?? 0;
            $exam['participants'] = isset($exam['completion_rate']) && $exam['completion_rate'] > 0
            ? round(($exam['completion_rate'] / 100) * $exam['students_registered'])
            : 0;
            $exam['participation_rate'] = $exam['eligible_students'] > 0
            ? round(($exam['participants'] / $exam['eligible_students']) * 100)
            : 0;
            $exam['questions_count'] = rand(15, 30);
            $exam['total_marks'] = $exam['questions_count'] * 2;
            $exam['schools_count'] = $exam['schools_assigned'] ?? 0;
            $exam['ai_generated_at'] = now()->subDays(rand(1, 7))->format('M d, Y');
            // Time until start for scheduled exams
            if ($exam['status'] == 'Scheduled') {
            $exam['time_until_start'] = now()->diffForHumans(now()->addDays(rand(1, 5)), true) . ' left';
            } else {
            $exam['time_until_start'] = '';
            }
            return $exam;
        })->toArray();

        return view('digital-learning.exams', compact(
            'exams',
            'schools',
            'exam_templates',
            'total_exams',
            'active_exams',
            'total_participants',
            'avg_completion'
        ));
    }

    public function contentManagement()
    {
        // Initialize all variables used in the content view to prevent errors
        $contentStats = $this->getContentStatistics();

        // Content KPIs
        $total_content = $contentStats['total_content'] ?? 0;
        $monthly_growth = $contentStats['monthly_growth'] ?? 0;
        $ai_generated_content = $contentStats['by_type']['ai_generated'] ?? 0;
        $ai_content_percentage = isset($contentStats['by_type']['ai_generated'], $contentStats['total_content']) && $contentStats['total_content'] > 0
            ? round($contentStats['by_type']['ai_generated'] / $contentStats['total_content'] * 100, 1)
            : 0;
        $total_downloads = 2345; // Example static value, replace with actual if available
        $monthly_downloads = 320; // Example static value, replace with actual if available
        $storage_used = 18; // Example static value in GB
        $storage_limit = 50; // Example static value in GB
        $storage_percentage = $storage_limit > 0 ? round($storage_used / $storage_limit * 100, 1) : 0;

        // Content distribution breakdown
        $content_distribution = [
            'Notes' => ['count' => $contentStats['by_type']['notes'] ?? 0, 'percentage' => isset($contentStats['by_type']['notes'], $total_content) && $total_content > 0 ? round($contentStats['by_type']['notes'] / $total_content * 100, 1) : 0],
            'Videos' => ['count' => $contentStats['by_type']['videos'] ?? 0, 'percentage' => isset($contentStats['by_type']['videos'], $total_content) && $total_content > 0 ? round($contentStats['by_type']['videos'] / $total_content * 100, 1) : 0],
            'Assignments' => ['count' => $contentStats['by_type']['assignments'] ?? 0, 'percentage' => isset($contentStats['by_type']['assignments'], $total_content) && $total_content > 0 ? round($contentStats['by_type']['assignments'] / $total_content * 100, 1) : 0],
            'AI Notes' => ['count' => $contentStats['by_type']['ai_generated'] ?? 0, 'percentage' => $ai_content_percentage],
            'Presentations' => ['count' => 120, 'percentage' => 5], // Example static value
        ];

        // Content items for grid/list
        $content_items = [
            [
            'id' => 1,
            'title' => 'Algebra Fundamentals',
            'type' => 'AI Notes',
            'subject' => 'Mathematics',
            'class' => 'Grade 9',
            'description' => 'Comprehensive notes on algebraic concepts, equations, and problem-solving techniques.',
            'uploaded_by' => 'AI System',
            'schools_distributed' => 12,
            'upload_date' => now()->subHours(2)->format('M d, Y'),
            'downloads' => 120,
            'views' => 340,
            'file_size' => '2.1MB',
            'status' => 'Active',
            ],
            [
            'id' => 2,
            'title' => 'Photosynthesis Process',
            'type' => 'Videos',
            'subject' => 'Science',
            'class' => 'Grade 10',
            'description' => 'Animated video explaining the process of photosynthesis in plants.',
            'uploaded_by' => 'Content Team',
            'schools_distributed' => 8,
            'upload_date' => now()->subHours(6)->format('M d, Y'),
            'downloads' => 85,
            'views' => 210,
            'file_size' => '45MB',
            'status' => 'Active',
            ],
            [
            'id' => 3,
            'title' => 'History Essay Assignment',
            'type' => 'Assignments',
            'subject' => 'History',
            'class' => 'Grade 11',
            'description' => 'Assignment on the causes and effects of World War II.',
            'uploaded_by' => 'Academic Team',
            'schools_distributed' => 15,
            'upload_date' => now()->subDays(1)->format('M d, Y'),
            'downloads' => 60,
            'views' => 150,
            'file_size' => '1.2MB',
            'status' => 'Active',
            ],
            [
            'id' => 4,
            'title' => 'English Literature Notes',
            'type' => 'Notes',
            'subject' => 'English',
            'class' => 'Grade 12',
            'description' => 'Key points and summaries for English literature exam preparation.',
            'uploaded_by' => 'Teacher A',
            'schools_distributed' => 10,
            'upload_date' => now()->subDays(2)->format('M d, Y'),
            'downloads' => 40,
            'views' => 90,
            'file_size' => '900KB',
            'status' => 'Active',
            ],
        ];

        // Schools for select options
        $schools = School::all()->map(function($school) {
            return [
            'id' => $school->id,
            'name' => $school->name ?? 'School ' . $school->id,
            ];
        });

        return view('digital-learning.content', compact(
            'total_content',
            'monthly_growth',
            'ai_generated_content',
            'ai_content_percentage',
            'total_downloads',
            'monthly_downloads',
            'storage_used',
            'storage_limit',
            'storage_percentage',
            'content_distribution',
            'content_items',
            'schools'
        ));
    }

    public function analytics()
    {
        // Initialize all variables used in the analytics view to prevent errors
        $digital_adoption_rate = 82;
        $adoption_growth = 5.2;
        $ai_tool_usage = 1245;
        $ai_sessions_today = 34;
        $content_engagement = 88;
        $avg_engagement_time = 17;
        $avg_exam_score = 79;
        $exams_completed = 42;

        // Learning modes
        $learning_modes = [
            'Self-Paced' => ['hours' => 120, 'percentage' => 35],
            'AI-Assisted' => ['hours' => 96, 'percentage' => 28],
            'Live Sessions' => ['hours' => 75, 'percentage' => 22],
            'Hybrid' => ['hours' => 52, 'percentage' => 15],
        ];

        // AI tool usage by subject
        $ai_usage_by_subject = [
            'Mathematics' => [
            'ai_exams' => 12,
            'ai_notes' => 8,
            'auto_grading' => 15,
            'total' => 35
            ],
            'English' => [
            'ai_exams' => 9,
            'ai_notes' => 7,
            'auto_grading' => 10,
            'total' => 26
            ],
            'Science' => [
            'ai_exams' => 14,
            'ai_notes' => 10,
            'auto_grading' => 12,
            'total' => 36
            ],
            'History' => [
            'ai_exams' => 7,
            'ai_notes' => 5,
            'auto_grading' => 8,
            'total' => 20
            ]
        ];

        // Top performing schools
        $top_schools = [
            ['name' => 'Greenwood High', 'location' => 'North', 'score' => 92],
            ['name' => 'Sunset Academy', 'location' => 'East', 'score' => 89],
            ['name' => 'Oak Valley School', 'location' => 'West', 'score' => 87],
            ['name' => 'Riverside Prep', 'location' => 'South', 'score' => 84],
            ['name' => 'Mountain View', 'location' => 'North', 'score' => 81],
        ];

        // Content analytics
        $content_analytics = [
            [
            'icon' => 'book',
            'type' => 'Notes',
            'total_items' => 1250,
            'avg_views' => 320,
            'engagement' => 85,
            'rating' => 4
            ],
            [
            'icon' => 'video',
            'type' => 'Videos',
            'total_items' => 680,
            'avg_views' => 210,
            'engagement' => 78,
            'rating' => 5
            ],
            [
            'icon' => 'tasks',
            'type' => 'Assignments',
            'total_items' => 420,
            'avg_views' => 150,
            'engagement' => 62,
            'rating' => 3
            ],
            [
            'icon' => 'robot',
            'type' => 'AI-Generated',
            'total_items' => 230,
            'avg_views' => 180,
            'engagement' => 70,
            'rating' => 4
            ]
        ];

        // Exam analytics
        $exam_analytics = [
            [
            'subject' => 'Mathematics',
            'ai_exams_count' => 12,
            'participation' => 456,
            'avg_score' => 82,
            'completion_rate' => 94
            ],
            [
            'subject' => 'English',
            'ai_exams_count' => 9,
            'participation' => 234,
            'avg_score' => 78,
            'completion_rate' => 89
            ],
            [
            'subject' => 'Science',
            'ai_exams_count' => 14,
            'participation' => 567,
            'avg_score' => 84,
            'completion_rate' => 91
            ],
            [
            'subject' => 'History',
            'ai_exams_count' => 7,
            'participation' => 123,
            'avg_score' => 76,
            'completion_rate' => 85
            ]
        ];

        // Regional summary
        $regional_summary = [
            [
            'region' => 'North',
            'schools_count' => 7,
            'digital_adoption' => 88,
            'ai_usage' => 75,
            'engagement' => 90,
            'exam_performance' => 85,
            'satisfaction_stars' => 5,
            'overall_score' => 91,
            'trend' => 'up',
            'trend_percentage' => 4.2
            ],
            [
            'region' => 'South',
            'schools_count' => 6,
            'digital_adoption' => 81,
            'ai_usage' => 68,
            'engagement' => 84,
            'exam_performance' => 78,
            'satisfaction_stars' => 4,
            'overall_score' => 82,
            'trend' => 'right',
            'trend_percentage' => 0.0
            ],
            [
            'region' => 'East',
            'schools_count' => 5,
            'digital_adoption' => 76,
            'ai_usage' => 62,
            'engagement' => 80,
            'exam_performance' => 72,
            'satisfaction_stars' => 3,
            'overall_score' => 74,
            'trend' => 'down',
            'trend_percentage' => -2.1
            ],
            [
            'region' => 'West',
            'schools_count' => 7,
            'digital_adoption' => 83,
            'ai_usage' => 70,
            'engagement' => 86,
            'exam_performance' => 80,
            'satisfaction_stars' => 4,
            'overall_score' => 85,
            'trend' => 'up',
            'trend_percentage' => 1.8
            ]
        ];

        // Schools for custom report modal
        $schools = [
            ['id' => 1, 'name' => 'Greenwood High'],
            ['id' => 2, 'name' => 'Sunset Academy'],
            ['id' => 3, 'name' => 'Oak Valley School'],
            ['id' => 4, 'name' => 'Riverside Prep'],
            ['id' => 5, 'name' => 'Mountain View'],
        ];

        // Pass all variables to the view
        return view('digital-learning.analytics', compact(
            'digital_adoption_rate',
            'adoption_growth',
            'ai_tool_usage',
            'ai_sessions_today',
            'content_engagement',
            'avg_engagement_time',
            'avg_exam_score',
            'exams_completed',
            'learning_modes',
            'ai_usage_by_subject',
            'top_schools',
            'content_analytics',
            'exam_analytics',
            'regional_summary',
            'schools'
        ));
    }

    public function aiTools()
    {
        $aiToolsData = $this->getAIToolsData();
        $ai_tools_active = collect($aiToolsData['ai_tools'])->where('status', 'Active')->count();
        $ai_uptime = '99.8%'; // Example value, replace with actual uptime if available
        $ai_requests_today=10;
        $avg_response_time=12;
        // Initialize all variables used in the view to prevent errors
        $ai_content_generated = 1234; // Example value
        $content_generated_today = 56; // Example value
        $ai_accuracy = 97.5; // Example value
        $accuracy_samples = 200; // Example value

        // AI Tool Categories
        $ai_tool_categories = [
            [
            'id' => 1,
            'name' => 'Content Generation',
            'color' => 'primary',
            'status' => 'Active',
            'description' => 'Tools for generating notes, summaries, and learning materials.',
            'tools_count' => 3,
            'usage_rate' => 85,
            'performance' => 92,
            ],
            [
            'id' => 2,
            'name' => 'Assessment',
            'color' => 'success',
            'status' => 'Active',
            'description' => 'AI-powered exam and quiz creation and grading.',
            'tools_count' => 2,
            'usage_rate' => 78,
            'performance' => 88,
            ],
            [
            'id' => 3,
            'name' => 'Tutoring',
            'color' => 'info',
            'status' => 'Inactive',
            'description' => 'AI tutoring and personalized learning support.',
            'tools_count' => 1,
            'usage_rate' => 60,
            'performance' => 75,
            ],
            [
            'id' => 4,
            'name' => 'Analytics',
            'color' => 'warning',
            'status' => 'Active',
            'description' => 'Performance analytics and insights for teachers and admins.',
            'tools_count' => 2,
            'usage_rate' => 70,
            'performance' => 80,
            ],
        ];

        // AI Health Metrics
        $ai_health_metrics = [
            'CPU Usage' => ['value' => 72, 'unit' => '%', 'status' => 'Good'],
            'Memory' => ['value' => 65, 'unit' => '%', 'status' => 'Good'],
            'Response Time' => ['value' => 120, 'unit' => 'ms', 'status' => 'Good'],
            'Accuracy' => ['value' => 97.5, 'unit' => '%', 'status' => 'Good'],
            'Uptime' => ['value' => 99.8, 'unit' => '%', 'status' => 'Good'],
        ];

        // AI Tools Table (individual tools)
        $ai_tools = [
            [
            'id' => 1,
            'name' => 'AI Exam Generator',
            'icon' => 'file-alt',
            'color' => 'primary',
            'description' => 'Generate exams using AI.',
            'category' => 'Assessment',
            'usage_rate' => 85,
            'total_requests' => 1200,
            'performance' => 94,
            'avg_response_time' => 110,
            'last_updated' => 'Jun 10, 2024',
            'version' => '1.2.0',
            'schools_using' => 18,
            'status' => 'Active',
            'maintenance_eta' => null,
            ],
            [
            'id' => 2,
            'name' => 'AI Notes Creator',
            'icon' => 'sticky-note',
            'color' => 'success',
            'description' => 'Create study notes automatically.',
            'category' => 'Content Generation',
            'usage_rate' => 78,
            'total_requests' => 950,
            'performance' => 89,
            'avg_response_time' => 130,
            'last_updated' => 'Jun 9, 2024',
            'version' => '1.1.5',
            'schools_using' => 12,
            'status' => 'Active',
            'maintenance_eta' => null,
            ],
            [
            'id' => 3,
            'name' => 'Auto Grading System',
            'icon' => 'check-circle',
            'color' => 'info',
            'description' => 'Grade exams automatically.',
            'category' => 'Assessment',
            'usage_rate' => 65,
            'total_requests' => 800,
            'performance' => 91,
            'avg_response_time' => 125,
            'last_updated' => 'Jun 8, 2024',
            'version' => '1.0.9',
            'schools_using' => 22,
            'status' => 'Maintenance',
            'maintenance_eta' => '2h left',
            ],
            [
            'id' => 4,
            'name' => 'Performance Analytics',
            'icon' => 'chart-bar',
            'color' => 'warning',
            'description' => 'AI-powered analytics.',
            'category' => 'Analytics',
            'usage_rate' => 70,
            'total_requests' => 600,
            'performance' => 80,
            'avg_response_time' => 140,
            'last_updated' => 'Jun 7, 2024',
            'version' => '1.0.3',
            'schools_using' => 15,
            'status' => 'Inactive',
            'maintenance_eta' => null,
            ],
        ];

        // Total schools (for badge display)
        $total_schools = 25;

        // Top performing tools (for sidebar)
        $top_performing_tools = [
            [
            'id' => 1,
            'name' => 'AI Exam Generator',
            'icon' => 'file-alt',
            'color' => 'primary',
            'category' => 'Assessment',
            'performance' => 94,
            'requests' => 1200,
            ],
            [
            'id' => 2,
            'name' => 'Auto Grading System',
            'icon' => 'check-circle',
            'color' => 'info',
            'category' => 'Assessment',
            'performance' => 91,
            'requests' => 800,
            ],
            [
            'id' => 3,
            'name' => 'AI Notes Creator',
            'icon' => 'sticky-note',
            'color' => 'success',
            'category' => 'Content Generation',
            'performance' => 89,
            'requests' => 950,
            ],
        ];

        // AI Errors (for error monitoring table)
        $ai_errors = [
            [
            'id' => 1,
            'timestamp' => '2024-06-10 10:15:00',
            'tool_name' => 'AI Exam Generator',
            'error_type' => 'Timeout',
            'severity' => 'Warning',
            'message' => 'Request timed out after 30s',
            'affected_users' => 5,
            'status' => 'Resolved',
            ],
            [
            'id' => 2,
            'timestamp' => '2024-06-10 09:45:00',
            'tool_name' => 'Auto Grading System',
            'error_type' => 'Model Error',
            'severity' => 'Critical',
            'message' => 'Model failed to load',
            'affected_users' => 12,
            'status' => 'Unresolved',
            ],
            [
            'id' => 3,
            'timestamp' => '2024-06-09 16:30:00',
            'tool_name' => 'Performance Analytics',
            'error_type' => 'Data Sync',
            'severity' => 'Warning',
            'message' => 'Data sync delayed',
            'affected_users' => 3,
            'status' => 'Resolved',
            ],
        ];

        return view('digital-learning.ai-tools', array_merge($aiToolsData, [
            'ai_tools_active' => $ai_tools_active,
            'ai_uptime' => $ai_uptime,
            'ai_requests_today' => $ai_requests_today,
            'avg_response_time' => $avg_response_time,
            'ai_content_generated' => $ai_content_generated,
            'content_generated_today' => $content_generated_today,
            'ai_accuracy' => $ai_accuracy,
            'accuracy_samples' => $accuracy_samples,
            'ai_tool_categories' => $ai_tool_categories,
            'ai_health_metrics' => $ai_health_metrics,
            'ai_tools' => $ai_tools,
            'total_schools' => $total_schools,
            'top_performing_tools' => $top_performing_tools,
            'ai_errors' => $ai_errors,
        ]));
       
    }

    public function createAIExam(Request $request)
    {
        $request->validate([
            'exam_title' => 'required|string|max:255',
            'class_level' => 'required|string',
            'subject' => 'required|string',
            'exam_date' => 'required|date',
            'exam_time' => 'required',
            'duration' => 'required|integer',
            'target_schools' => 'required|array',
        ]);

        // In a real implementation, this would create the AI exam
        // For now, we'll simulate the creation
        
        return response()->json([
            'success' => true,
            'message' => 'AI Exam created successfully and assigned to ' . count($request->target_schools) . ' schools'
        ]);
    }

    public function generateAINotes(Request $request)
    {
        $request->validate([
            'subject' => 'required|string',
            'topic' => 'required|string',
            'class_level' => 'required|string',
            'content_type' => 'required|in:notes,revision,exercises',
            'target_schools' => 'required|array',
        ]);

        // Simulate AI content generation
        return response()->json([
            'success' => true,
            'message' => 'AI-generated content created and distributed to ' . count($request->target_schools) . ' schools'
        ]);
    }

    public function bulkContentPush(Request $request)
    {
        $request->validate([
            'content_type' => 'required|in:notes,videos,assignments,exams',
            'target_schools' => 'required|array',
            'content_files' => 'required',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Content pushed to ' . count($request->target_schools) . ' schools successfully'
        ]);
    }

    private function calculateDigitalLearningKPIs()
    {
        // Sample data - in real implementation, fetch from actual digital learning tables
        return [
            'total_digital_content' => 2580,
            'active_ai_exams' => 12,
            'avg_engagement_rate' => 84.7,
            'content_uploads_this_month' => 156,
            'ai_generated_content' => 89,
            'schools_using_digital' => School::count(),
            'total_online_exams' => 45,
            'avg_exam_completion' => 92.3,
            
            // Content type breakdown
            'content_types' => [
                'Class Notes' => ['count' => 1250, 'percentage' => 48.4],
                'Videos' => ['count' => 680, 'percentage' => 26.4],
                'Assignments' => ['count' => 420, 'percentage' => 16.3],
                'AI-Generated' => ['count' => 230, 'percentage' => 8.9]
            ],
            
            // Engagement metrics
            'engagement_stats' => [
                'high' => 8,
                'medium' => 12,
                'low' => 5
            ],

            // Monthly growth
            'monthly_growth' => 18.5
        ];
    }

    private function getSchoolsDigitalLearningList()
    {
        $schools = School::all();
        
        return $schools->map(function ($school) {
            return [
                'id' => $school->id,
                'name' => $school->name ?? 'School ' . $school->id,
                'location' => $school->address ?? 'Location ' . $school->id,
                'digital_adoption' => rand(60, 95) . '%',
                'content_uploads' => rand(25, 120),
                'ai_exam_participation' => rand(70, 100) . '%',
                'engagement_rate' => rand(75, 95) . '%',
                'last_activity' => now()->subDays(rand(1, 7))->format('M d, Y'),
                'digital_status' => rand(0, 1) ? 'Active' : 'Moderate',
                'ai_tools_usage' => rand(40, 90) . '%'
            ];
        });
    }

    private function getAIExamsList()
    {
        return [
            [
                'id' => 1,
                'title' => 'Mathematics Mid-Term Assessment',
                'class_level' => 'Grade 10',
                'subject' => 'Mathematics',
                'exam_date' => now()->addDays(5)->format('M d, Y'),
                'exam_time' => '09:00 AM',
                'duration' => 120,
                'status' => 'Scheduled',
                'schools_assigned' => 12,
                'students_registered' => 456,
                'ai_generated' => true,
                'completion_rate' => 0,
                'avg_score' => null
            ],
            [
                'id' => 2,
                'title' => 'English Language Proficiency Test',
                'class_level' => 'Grade 9',
                'subject' => 'English',
                'exam_date' => now()->subDays(3)->format('M d, Y'),
                'exam_time' => '10:30 AM',
                'duration' => 90,
                'status' => 'Completed',
                'schools_assigned' => 8,
                'students_registered' => 234,
                'ai_generated' => true,
                'completion_rate' => 94.4,
                'avg_score' => 78.5
            ],
            [
                'id' => 3,
                'title' => 'Science Practical Assessment',
                'class_level' => 'Grade 8',
                'subject' => 'Science',
                'exam_date' => now()->format('M d, Y'),
                'exam_time' => '02:00 PM',
                'duration' => 105,
                'status' => 'In Progress',
                'schools_assigned' => 15,
                'students_registered' => 567,
                'ai_generated' => true,
                'completion_rate' => 67.2,
                'avg_score' => null
            ]
        ];
    }

    private function getContentStatistics()
    {
        return [
            'total_content' => 2580,
            'by_type' => [
                'notes' => 1250,
                'videos' => 680,
                'assignments' => 420,
                'ai_generated' => 230
            ],
            'recent_uploads' => [
                [
                    'title' => 'Algebra Fundamentals',
                    'type' => 'AI Notes',
                    'subject' => 'Mathematics',
                    'class' => 'Grade 9',
                    'uploaded_by' => 'AI System',
                    'schools_distributed' => 12,
                    'upload_date' => now()->subHours(2)->format('M d, Y H:i'),
                    'status' => 'Published'
                ],
                [
                    'title' => 'Photosynthesis Process',
                    'type' => 'Video',
                    'subject' => 'Biology',
                    'class' => 'Grade 10',
                    'uploaded_by' => 'Content Team',
                    'schools_distributed' => 8,
                    'upload_date' => now()->subHours(6)->format('M d, Y H:i'),
                    'status' => 'Published'
                ],
                [
                    'title' => 'History Essay Assignment',
                    'type' => 'Assignment',
                    'subject' => 'History',
                    'class' => 'Grade 11',
                    'uploaded_by' => 'Academic Team',
                    'schools_distributed' => 15,
                    'upload_date' => now()->subDays(1)->format('M d, Y H:i'),
                    'status' => 'Published'
                ]
            ]
        ];
    }

    private function getDigitalLearningAnalytics()
    {
        return [
            'monthly_trends' => [
                'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                'content_uploads' => [120, 145, 180, 160, 195, 220],
                'ai_content' => [15, 28, 35, 42, 56, 89],
                'exam_participation' => [85, 88, 92, 89, 94, 96]
            ],
            'subject_performance' => [
                'Mathematics' => 82.5,
                'English' => 78.3,
                'Science' => 84.7,
                'History' => 76.8,
                'Geography' => 79.2
            ],
            'ai_tools_adoption' => [
                'AI Exam Generator' => 78,
                'AI Notes Creator' => 65,
                'Auto Grading' => 89,
                'Performance Analytics' => 72
            ],
            'engagement_by_content' => [
                'labels' => ['Notes', 'Videos', 'Assignments', 'Exams'],
                'data' => [85, 92, 78, 88]
            ]
        ];
    }

    private function getAIToolsData()
    {
        return [
            'ai_tools' => [
                [
                    'name' => 'AI Exam Generator',
                    'description' => 'Generate comprehensive exams using AI for any subject and grade level',
                    'usage_count' => 156,
                    'schools_using' => 18,
                    'success_rate' => 94.2,
                    'status' => 'Active',
                    'last_used' => now()->subHours(3)->format('M d, Y H:i')
                ],
                [
                    'name' => 'AI Notes Creator',
                    'description' => 'Create detailed study notes and revision materials automatically',
                    'usage_count' => 89,
                    'schools_using' => 12,
                    'success_rate' => 89.7,
                    'status' => 'Active',
                    'last_used' => now()->subHours(1)->format('M d, Y H:i')
                ],
                [
                    'name' => 'Auto Grading System',
                    'description' => 'Automatically grade and rank exam submissions',
                    'usage_count' => 234,
                    'schools_using' => 22,
                    'success_rate' => 97.8,
                    'status' => 'Active',
                    'last_used' => now()->subMinutes(45)->format('M d, Y H:i')
                ],
                [
                    'name' => 'Performance Analytics',
                    'description' => 'AI-powered insights and recommendations for academic improvement',
                    'usage_count' => 67,
                    'schools_using' => 15,
                    'success_rate' => 91.3,
                    'status' => 'Active',
                    'last_used' => now()->subHours(5)->format('M d, Y H:i')
                ]
            ],
            'ai_recommendations' => [
                'Increase AI exam frequency for better performance tracking',
                'Deploy AI-generated notes for struggling subjects',
                'Implement intervention programs based on AI analytics',
                'Expand digital content library with AI assistance'
            ]
        ];
    }
}
