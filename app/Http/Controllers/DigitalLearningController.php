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
        $exams = $this->getAIExamsList();
        $schools = School::all();
        return view('digital-learning.exams', compact('exams', 'schools'));
    }

    public function contentManagement()
    {
        $contentStats = $this->getContentStatistics();
        $schools = School::all();
        return view('digital-learning.content', compact('contentStats', 'schools'));
    }

    public function analytics()
    {
        $data = $this->getDigitalLearningAnalytics();
        return view('digital-learning.analytics', $data);
    }

    public function aiTools()
    {
        $aiToolsData = $this->getAIToolsData();
        return view('digital-learning.ai-tools', $aiToolsData);
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
