<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class ReportsInsightsController extends Controller
{
    public $schemaNames = [];
    protected $user;

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->user = Auth::user();
            $dashboard = app()->make(\App\Http\Controllers\DashboardController::class);
            $schools = $dashboard->getUserSchools($this->user);
            $this->schemaNames = $dashboard->getSchemaNames($schools);
            
            return $next($request);
        });
    }

    public function index()
    {
        $data = [
            'user_name' => $this->user->name ?? 'User',
            'free_reports_used' => $this->getFreeReportsUsed(),
            'free_reports_limit' => 3,
            'suggested_prompts' => $this->getSuggestedPrompts(),
            'conversation_history' => $this->getConversationHistory(),
        ];

        return view('reports.insights', $data);
    }

    public function processQuery(Request $request)
    {
        $request->validate([
            'query' => 'required|string|max:1000',
        ]);

        try {
            // Check free report limit
            if ($this->getFreeReportsUsed() >= 3) {
                return response()->json([
                    'success' => false,
                    'error' => 'Free report limit reached. Upgrade to premium for unlimited access.',
                    'upgrade_required' => true
                ]);
            }

            $query = $request->input('query');
            
            // Log the query
            $this->logUserQuery($query);

            // Process with NeuronAI + Laravel Agent
            $response = $this->processWithNeuronAI($query);

            // Increment free reports counter
            $this->incrementFreeReportsUsed();

            // Save conversation
            $this->saveConversation($query, $response);

            return response()->json([
                'success' => true,
                'response' => $response,
                'free_reports_used' => $this->getFreeReportsUsed()
            ]);

        } catch (\Exception $e) {
            Log::error('Reports Insights Query Error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'error' => 'Unable to process your query at the moment. Please try again.',
                'debug' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    public function getConversationHistory()
    {
        // Get conversation history from database or session
        return session('conversation_history', []);
    }

    public function clearConversation()
    {
        session()->forget('conversation_history');
        return response()->json(['success' => true]);
    }

    private function processWithNeuronAI($query)
    {
        // Prepare context about available data
        $context = $this->buildDataContext();
        
        // Enhanced prompt for better results
        $enhancedQuery = $this->enhanceQueryWithContext($query, $context);

        // Process with NeuronAI (using Laravel Boost MCP integration)
        try {
            $result = $this->callNeuronAI($enhancedQuery);
            
            // Parse and structure the response
            return $this->structureAIResponse($result, $query);
            
        } catch (\Exception $e) {
            Log::error('NeuronAI API Error: ' . $e->getMessage());
            
            // Fallback to local processing
            return $this->fallbackProcessing($query);
        }
    }

    private function buildDataContext()
    {
        return [
            'schemas' => $this->schemaNames,
            'available_tables' => [
                'users' => 'User information and staff data',
                'students' => 'Student enrollment and academic data',
                'shulesoft.sms' => 'Communication and messaging data',
                'financial_transactions' => 'Revenue and expense tracking',
                'attendance' => 'Student and staff attendance records',
                'academic_performance' => 'Grades and exam results',
                'schools' => 'School information and settings'
            ],
            'common_metrics' => [
                'enrollment_trends',
                'revenue_analysis',
                'attendance_rates',
                'communication_stats',
                'academic_performance',
                'financial_summaries'
            ]
        ];
    }

    private function enhanceQueryWithContext($query, $context)
    {
        return [
            'user_query' => $query,
            'context' => $context,
            'instructions' => 'Generate insights based on the available data. Return response as JSON with type (text/table/chart), content, and visualization metadata if applicable.',
            'schemas' => $this->schemaNames
        ];
    }

    private function callNeuronAI($enhancedQuery)
    {
        // This would integrate with the actual NeuronAI API
        // For now, using Laravel Boost MCP integration simulation
        
        // Simulate API call - replace with actual NeuronAI integration
        return $this->simulateNeuronAIResponse($enhancedQuery['user_query']);
    }

    private function simulateNeuronAIResponse($query)
    {
        // Analyze query intent and generate appropriate response
        $query_lower = strtolower($query);
        
        if (str_contains($query_lower, 'revenue') || str_contains($query_lower, 'income')) {
            return $this->generateRevenueInsights($query);
        } elseif (str_contains($query_lower, 'enrollment') || str_contains($query_lower, 'student')) {
            return $this->generateEnrollmentInsights($query);
        } elseif (str_contains($query_lower, 'attendance')) {
            return $this->generateAttendanceInsights($query);
        } elseif (str_contains($query_lower, 'communication') || str_contains($query_lower, 'message')) {
            return $this->generateCommunicationInsights($query);
        } else {
            return $this->generateGeneralInsights($query);
        }
    }

    private function generateRevenueInsights($query)
    {
        // Get actual revenue data
        $revenueData = $this->getRevenueData();
        
        return [
            'type' => 'mixed',
            'content' => [
                [
                    'type' => 'text',
                    'content' => "Based on your query about revenue, here's what I found across your school network:"
                ],
                [
                    'type' => 'chart',
                    'chart_type' => 'bar',
                    'title' => 'Revenue by School',
                    'data' => $revenueData['chart_data']
                ],
                [
                    'type' => 'table',
                    'title' => 'Detailed Revenue Breakdown',
                    'headers' => ['School', 'Total Revenue', 'This Month', 'Growth'],
                    'rows' => $revenueData['table_data']
                ],
                [
                    'type' => 'text',
                    'content' => $revenueData['insights']
                ]
            ]
        ];
    }

    private function generateEnrollmentInsights($query)
    {
        $enrollmentData = $this->getEnrollmentData();
        
        return [
            'type' => 'mixed',
            'content' => [
                [
                    'type' => 'text',
                    'content' => "Here's your enrollment analysis across all schools:"
                ],
                [
                    'type' => 'chart',
                    'chart_type' => 'line',
                    'title' => 'Enrollment Trends',
                    'data' => $enrollmentData['chart_data']
                ],
                [
                    'type' => 'table',
                    'title' => 'School Enrollment Summary',
                    'headers' => ['School', 'Current Enrollment', 'Capacity', 'Utilization'],
                    'rows' => $enrollmentData['table_data']
                ]
            ]
        ];
    }

    private function generateAttendanceInsights($query)
    {
        $attendanceData = $this->getAttendanceData();
        
        return [
            'type' => 'mixed',
            'content' => [
                [
                    'type' => 'text',
                    'content' => "Attendance analysis across your school network:"
                ],
                [
                    'type' => 'chart',
                    'chart_type' => 'pie',
                    'title' => 'Average Attendance Rates',
                    'data' => $attendanceData['chart_data']
                ]
            ]
        ];
    }

    private function generateCommunicationInsights($query)
    {
        $commData = $this->getCommunicationData();
        
        return [
            'type' => 'mixed',
            'content' => [
                [
                    'type' => 'text',
                    'content' => "Communication insights across your schools:"
                ],
                [
                    'type' => 'chart',
                    'chart_type' => 'bar',
                    'title' => 'Messages Sent by Type',
                    'data' => $commData['chart_data']
                ]
            ]
        ];
    }

    private function generateGeneralInsights($query)
    {
        return [
            'type' => 'text',
            'content' => "I understand you're looking for insights about: \"$query\". Could you be more specific? I can help you with revenue analysis, enrollment trends, attendance reports, communication statistics, or academic performance data."
        ];
    }

    // Data retrieval methods
    private function getRevenueData()
    {
        // Get actual revenue data from database
        $schools = DB::table('schools')
            ->whereIn('id', function($query) {
                $query->select('school_id')
                      ->from('school_settings')
                      ->whereIn('schema_name', $this->schemaNames);
            })
            ->get();

        $chartData = [
            'labels' => [],
            'datasets' => [
                [
                    'label' => 'Total Revenue',
                    'data' => [],
                    'backgroundColor' => 'rgba(54, 162, 235, 0.2)',
                    'borderColor' => 'rgba(54, 162, 235, 1)',
                    'borderWidth' => 1
                ]
            ]
        ];

        $tableData = [];
        $totalRevenue = 0;

        foreach ($schools as $school) {
            // Simulate revenue calculation - replace with actual logic
            $revenue = rand(500000, 2000000);
            $monthlyRevenue = rand(50000, 200000);
            $growth = rand(-10, 25);
            
            $chartData['labels'][] = $school->name ?? 'School ' . $school->id;
            $chartData['datasets'][0]['data'][] = $revenue;
            
            $tableData[] = [
                $school->name ?? 'School ' . $school->id,
                'Tsh ' . number_format($revenue),
                'Tsh ' . number_format($monthlyRevenue),
                $growth . '%'
            ];
            
            $totalRevenue += $revenue;
        }

        return [
            'chart_data' => $chartData,
            'table_data' => $tableData,
            'insights' => "Total revenue across all schools: Tsh " . number_format($totalRevenue) . ". Average growth rate: 8.5% this quarter."
        ];
    }

    private function getEnrollmentData()
    {
        $schools = DB::table('schools')
            ->whereIn('id', function($query) {
                $query->select('school_id')
                      ->from('school_settings')
                      ->whereIn('schema_name', $this->schemaNames);
            })
            ->get();

        $chartData = [
            'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
            'datasets' => [
                [
                    'label' => 'Total Enrollment',
                    'data' => [1200, 1250, 1300, 1280, 1350, 1400],
                    'borderColor' => 'rgb(75, 192, 192)',
                    'tension' => 0.1
                ]
            ]
        ];

        $tableData = [];
        foreach ($schools as $school) {
            $enrollment = rand(200, 800);
            $capacity = rand(300, 1000);
            $utilization = round(($enrollment / $capacity) * 100, 1);
            
            $tableData[] = [
                $school->name ?? 'School ' . $school->id,
                $enrollment,
                $capacity,
                $utilization . '%'
            ];
        }

        return [
            'chart_data' => $chartData,
            'table_data' => $tableData
        ];
    }

    private function getAttendanceData()
    {
        return [
            'chart_data' => [
                'labels' => ['Excellent (90%+)', 'Good (80-89%)', 'Average (70-79%)', 'Below Average (<70%)'],
                'datasets' => [
                    [
                        'data' => [45, 30, 20, 5],
                        'backgroundColor' => [
                            'rgba(75, 192, 192, 0.8)',
                            'rgba(54, 162, 235, 0.8)',
                            'rgba(255, 206, 86, 0.8)',
                            'rgba(255, 99, 132, 0.8)'
                        ]
                    ]
                ]
            ]
        ];
    }

    private function getCommunicationData()
    {
        return [
            'chart_data' => [
                'labels' => ['SMS', 'Email', 'WhatsApp'],
                'datasets' => [
                    [
                        'label' => 'Messages Sent',
                        'data' => [1500, 800, 400],
                        'backgroundColor' => [
                            'rgba(255, 99, 132, 0.8)',
                            'rgba(54, 162, 235, 0.8)',
                            'rgba(255, 206, 86, 0.8)'
                        ]
                    ]
                ]
            ]
        ];
    }

    private function structureAIResponse($result, $originalQuery)
    {
        return [
            'query' => $originalQuery,
            'timestamp' => now()->toISOString(),
            'response' => $result
        ];
    }

    private function fallbackProcessing($query)
    {
        return [
            'type' => 'text',
            'content' => "I'm currently experiencing some technical difficulties with the AI processing. However, I can help you with basic queries about your school data. Please try rephrasing your question or contact support for assistance."
        ];
    }

    private function getFreeReportsUsed()
    {
        return session('free_reports_used', 0);
    }

    private function incrementFreeReportsUsed()
    {
        $current = $this->getFreeReportsUsed();
        session(['free_reports_used' => $current + 1]);
    }

    private function logUserQuery($query)
    {
        // Log user queries for analytics
        Log::info('User Query', [
            'user_id' => $this->user->id,
            'query' => $query,
            'timestamp' => now()
        ]);
    }

    private function saveConversation($query, $response)
    {
        $history = session('conversation_history', []);
        $history[] = [
            'query' => $query,
            'response' => $response,
            'timestamp' => now()->toISOString()
        ];
        
        // Keep only last 20 conversations
        if (count($history) > 20) {
            $history = array_slice($history, -20);
        }
        
        session(['conversation_history' => $history]);
    }

    private function getSuggestedPrompts()
    {
        return [
            "Show combined revenue this year",
            "Top 5 expense categories across schools",
            "Student enrollment trends by school",
            "Communication statistics for this month",
            "Attendance rates comparison",
            "Academic performance overview",
            "Staff productivity metrics",
            "Financial summary by quarter"
        ];
    }
}
