<?php

namespace App\Http\Controllers;

use App\Models\School;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{

 
    public function __construct()
    {
     parent::__construct();
    }

    public function index()
    {
        $user = Auth::user();
        $schools = $this->getUserSchools($user);
        
        // Check if user has no schools associated
        $hasNoSchoolsAssociated = $schools->isEmpty();
        
        // Phase 2: Enhanced UX - Check user onboarding status
        $userOnboardingStatus = $this->getUserOnboardingStatus($user, $schools);
        
        // If user is in getting started mode, show simplified dashboard
        if ($userOnboardingStatus['is_getting_started']) {
            return $this->showGettingStartedDashboard($user, $userOnboardingStatus);
        }
        
        // Regular dashboard for established users
        $schemaNames = $this->getSchemaNames($schools);

        $totalStudents = $this->getTotalStudents($schemaNames);
        $avgAttendance = $this->getAverageAttendance($schemaNames);
        $feesCollected = $this->getFeesCollected($schemaNames);
        $feestobeCollected = $this->getFeesToBeCollected($schemaNames);
        $collection_rate = $feestobeCollected > 0 
            ? round(($feesCollected / $feestobeCollected) * 100, 2) 
            : 0;
        
        $activeSchools = count($schemaNames);
        $totalSchools = $schools->count();
        $topSchools = $this->getTopSchools($schools);
        $months = $this->getLast12Months();
        $feeCollectionTrend = $this->getFeeCollectionTrend($months, $schemaNames);
        $poorRevenueSchools = $this->getPoorRevenueSchools($schemaNames);
        $pendingBudgets = $this->getPendingBudgets($schools);
        $examResults = $this->getExamResults($schemaNames);

        return view('dashboard', compact(
            'totalStudents',
            'poorRevenueSchools',
            'schools',
            'pendingBudgets',
            'avgAttendance', 
            'feesCollected',
            'activeSchools',
            'totalSchools',
            'topSchools',
            'feeCollectionTrend',
            'months',
            'examResults',
            'collection_rate',
            'hasNoSchoolsAssociated'
        ));
    }

    public function getUserSchools($user)
    {
        return $user->schools()->active()->get();
    }

    public function getSchemaNames($schools)
    {
        return \DB::table('shulesoft.setting')
            ->join('connect_schools', 'shulesoft.setting.uid', '=', 'connect_schools.school_setting_uid')
            ->whereIn('connect_schools.id', $schools->pluck('id'))
            ->pluck('shulesoft.setting.schema_name');
    }

    public function getTotalStudents($schemaNames)
    {
        return \DB::table('shulesoft.student')
            ->whereIn('schema_name', $schemaNames)
            ->where('status',1)
            ->count();
    }

    public function getAverageAttendance($schemaNames)
    {
        $attendanceData = \DB::table('shulesoft.sattendances')
            ->selectRaw('schema_name, SUM(CASE WHEN present = 1 THEN 1 ELSE 0 END)::float / COUNT(*) * 100 as attendance_percentage')
            ->whereIn('schema_name', $schemaNames)
            ->groupBy('schema_name')
            ->pluck('attendance_percentage');

        return $attendanceData->count() > 0
            ? round($attendanceData->avg(), 2)
            : 0;
    }

    public function getFeesCollected($schemaNames)
    {
        return \DB::table('shulesoft.payments')
            ->whereIn('schema_name', $schemaNames)
                                ->whereDate('created_at', '>=', $this->start)
                    ->whereDate('created_at', '<=', $this->end)
            ->sum('amount');
    }
    public function getFeesToBeCollected($schemaNames)
    {
        return \DB::table('shulesoft.fees_installments_classes')
            ->whereIn('schema_name', $schemaNames)
            ->whereDate('created_at', '>=', $this->start)
            ->whereDate('created_at', '<=', $this->end)
            ->sum('amount');
    }

    public function getTopSchools($schools)
    {
        // Phase 1 Optimization: Replace N+1 queries with single optimized query
        $schoolIds = $schools->pluck('id')->toArray();
        
        if (empty($schoolIds)) {
            return collect([]);
        }
        
        $placeholders = str_repeat('?,', count($schoolIds) - 1) . '?';
        
        $results = \DB::select("
            SELECT 
                ss.sname as school_name,
                ss.schema_name,
                COALESCE(SUM(p.amount), 0) as total_fees,
                COALESCE(COUNT(DISTINCT s.student_id), 0) as student_count,
                CASE 
                    WHEN COUNT(DISTINCT s.student_id) > 0 
                    THEN ROUND(COALESCE(SUM(p.amount), 0) / COUNT(DISTINCT s.student_id), 2)
                    ELSE 0 
                END as avg_per_student
            FROM connect_schools cs
            JOIN shulesoft.setting ss ON cs.school_setting_uid = ss.uid
            LEFT JOIN shulesoft.payments p ON ss.schema_name = p.schema_name 
                AND p.created_at >= ? AND p.created_at <= ?
            LEFT JOIN shulesoft.student s ON ss.schema_name = s.schema_name 
                AND s.status = 1
            WHERE cs.id IN ({$placeholders})
            GROUP BY cs.id, ss.sname, ss.schema_name
            ORDER BY total_fees DESC
            LIMIT 5
        ", array_merge([$this->start, $this->end], $schoolIds));
        
        return collect($results)->map(function ($result) {
            return (object)[
                'school_name' => $result->school_name,
                'schema_name' => $result->schema_name,
                'amount' => (float) $result->total_fees,
                'avg_per_student' => (float) $result->avg_per_student,
            ];
        })
        ->sortByDesc('amount')
        ->values()
        ->take(5)
        ->map(function ($school, $index) {
            $school->rank = $index + 1;
            return $school;
        });
    }

    public function getLast12Months()
    {
        return collect(range(0, 11))->map(function ($i) {
            return now()->subMonths($i)->format('m');
        })->reverse()->values();
    }

    public function getFeeCollectionTrend($months, $schemaNames)
    {
        // Phase 1 Optimization: Replace 12 individual queries with single query
        if (empty($schemaNames) || $months->isEmpty()) {
            return collect([]);
        }
        
        $monthPlaceholders = str_repeat('?,', $months->count() - 1) . '?';
        $schemaPlaceholders = str_repeat('?,', count($schemaNames) - 1) . '?';
        
        $results = \DB::select("
            SELECT 
                EXTRACT(MONTH FROM date) as month_num,
                ROUND(COALESCE(SUM(amount), 0), 2) as amount
            FROM shulesoft.payments
            WHERE schema_name IN ({$schemaPlaceholders})
                AND created_at >= ? 
                AND created_at <= ?
                AND EXTRACT(MONTH FROM date) IN ({$monthPlaceholders})
            GROUP BY EXTRACT(MONTH FROM date)
            ORDER BY EXTRACT(MONTH FROM date)
        ", array_merge($schemaNames->toArray(), [$this->start, $this->end], $months->toArray()));
        
        // Create a map of results for quick lookup
        $resultMap = collect($results)->keyBy('month_num');
        
        // Return amounts in the same order as months, filling missing months with 0
        return $months->map(function ($month) use ($resultMap) {
            return $resultMap->has($month) ? (float) $resultMap[$month]->amount : 0.0;
        });
    }

    public function getPoorRevenueSchools($schemaNames)
    {
        // Phase 1 Optimization: Replace N+1 queries with single optimized query
        // Convert Collection to array for compatibility with array functions
        if (is_object($schemaNames) && method_exists($schemaNames, 'toArray')) {
            $schemaNames = $schemaNames->toArray();
        }
        
        if (empty($schemaNames)) {
            return collect([]);
        }
        
        $placeholders = str_repeat('?,', count($schemaNames) - 1) . '?';
        
        $results = \DB::select("
            SELECT 
                ss.schema_name,
                ss.name as school_name,
                COALESCE(payments.collected, 0) as collected,
                COALESCE(targets.target, 0) as target,
                CASE 
                    WHEN COALESCE(targets.target, 0) > 0 
                    THEN ROUND((COALESCE(payments.collected, 0) / targets.target * 100), 1)
                    ELSE 0 
                END as collection_percent
            FROM shulesoft.setting ss
            JOIN connect_schools cs ON ss.uid = cs.school_setting_uid
            LEFT JOIN (
                SELECT schema_name, SUM(amount) as collected 
                FROM shulesoft.payments 
                WHERE created_at >= ? AND created_at <= ?
                GROUP BY schema_name
            ) payments ON ss.schema_name = payments.schema_name
            LEFT JOIN (
                SELECT schema_name, SUM(amount) as target 
                FROM shulesoft.fees_installments_classes 
                WHERE created_at >= ? AND created_at <= ?
                GROUP BY schema_name  
            ) targets ON ss.schema_name = targets.schema_name
            WHERE ss.schema_name IN ({$placeholders})
            ORDER BY collection_percent ASC
            LIMIT 5
        ", array_merge([$this->start, $this->end, $this->start, $this->end], $schemaNames));
        
        return collect($results)->map(function ($result) {
            return (object)[
                'schema_name' => $result->schema_name,
                'school_name' => $result->school_name,
                'collected' => (float) $result->collected,
                'target' => (float) $result->target,
                'collection_percent' => (float) $result->collection_percent,
            ];
        });
    }

    public function getPendingBudgets($schools)
    {
        // Phase 1 Optimization: Replace N+1 queries with single optimized query
        $schoolIds = $schools->pluck('id')->toArray();
        
        if (empty($schoolIds)) {
            return collect([]);
        }
        
        $placeholders = str_repeat('?,', count($schoolIds) - 1) . '?';
        
        $results = \DB::select("
            SELECT 
                ss.name as school_name,
                ss.schema_name,
                CASE WHEN b.id IS NOT NULL THEN 1 ELSE 0 END as is_prepared,
                b.budget_from,
                b.budget_to,
                COALESCE(budget_totals.total_amount, 0) as total_amount
            FROM connect_schools cs  
            JOIN shulesoft.setting ss ON cs.school_setting_uid = ss.uid
            LEFT JOIN (
                SELECT schema_name, id, budget_from, budget_to,
                       ROW_NUMBER() OVER (PARTITION BY schema_name ORDER BY id DESC) as rn
                FROM shulesoft.budgets
            ) b ON ss.schema_name = b.schema_name AND b.rn = 1
            LEFT JOIN (
                SELECT schema_name, SUM(amount) as total_amount
                FROM shulesoft.budgets
                WHERE budget_from >= ? AND budget_to <= ?
                GROUP BY schema_name
            ) budget_totals ON ss.schema_name = budget_totals.schema_name
            WHERE cs.id IN ({$placeholders})
        ", array_merge([$this->start, $this->end], $schoolIds));
        
        return collect($results)->map(function ($result) {
            return (object)[
                'school_name' => $result->school_name,
                'schema_name' => $result->schema_name,
                'is_prepared' => (bool) $result->is_prepared,
                'budget_from' => $result->budget_from,
                'budget_to' => $result->budget_to,
                'total_amount' => (float) $result->total_amount,
            ];
        });
    }

    /**
     * Phase 2: Determine user onboarding status and readiness
     */
    private function getUserOnboardingStatus($user, $schools)
    {
        $schoolCount = $schools->count();
        $daysSinceRegistration = now()->diffInDays($user->created_at);
        
        // Check for pending school requests
        $pendingSchoolRequests = \DB::table('shulesoft.school_creation_requests')
            ->where('connect_user_id', $user->id)
            ->where('status', 'pending')
            ->count();
        
        // Determine if user is in "getting started" mode
        $isGettingStarted = (
            $schoolCount <= 1 || // 1 or fewer active schools
            $daysSinceRegistration <= 7 || // Registered within last 7 days
            $pendingSchoolRequests > 0 // Has pending school requests
        );
        
        return [
            'is_getting_started' => $isGettingStarted,
            'school_count' => $schoolCount,
            'days_since_registration' => $daysSinceRegistration,
            'pending_school_requests' => $pendingSchoolRequests,
            'setup_completion_percentage' => $this->calculateSetupCompletion($user, $schools),
            'next_steps' => $this->getNextSteps($user, $schools, $pendingSchoolRequests)
        ];
    }

    /**
     * Phase 2: Calculate setup completion percentage
     */
    private function calculateSetupCompletion($user, $schools)
    {
        $completedSteps = 0;
        $totalSteps = 5;

        // Step 1: Account created
        if ($user->created_at) $completedSteps++;
        
        // Step 2: Email verified
        if ($user->email_verified_at) $completedSteps++;
        
        // Step 3: Has at least one school
        if ($schools->count() > 0) $completedSteps++;
        
        // Step 4: Profile completed (has phone)
        if ($user->phone) $completedSteps++;
        
        // Step 5: Organization setup
        if ($user->connect_organization_id) $completedSteps++;

        return round(($completedSteps / $totalSteps) * 100);
    }

    /**
     * Phase 2: Get personalized next steps for user
     */
    private function getNextSteps($user, $schools, $pendingSchoolRequests)
    {
        $nextSteps = [];
        
        if ($schools->count() === 0) {
            $nextSteps[] = [
                'title' => 'Add Your First School',
                'description' => 'Connect your first school to start using the platform',
                'action_url' => route('onboarding.start'),
                'action_text' => 'Add School',
                'priority' => 'high',
                'icon' => 'bi-building'
            ];
        }
        
        if ($pendingSchoolRequests > 0) {
            $nextSteps[] = [
                'title' => "Track School Setup Progress",
                'description' => "You have {$pendingSchoolRequests} school(s) being set up by our team",
                'action_url' => route('settings.schools'),
                'action_text' => 'View Status',
                'priority' => 'medium',
                'icon' => 'bi-clock'
            ];
        }
        
        if (!$user->phone) {
            $nextSteps[] = [
                'title' => 'Complete Your Profile',
                'description' => 'Add your phone number for better security and notifications',
                'action_url' => route('profile.edit'),
                'action_text' => 'Update Profile',
                'priority' => 'low',
                'icon' => 'bi-person'
            ];
        }
        
        if ($schools->count() > 0 && $schools->count() < 3) {
            $nextSteps[] = [
                'title' => 'Add More Schools',
                'description' => 'Connect additional schools to your organization',
                'action_url' => route('settings.schools'),
                'action_text' => 'Add Schools',
                'priority' => 'low',
                'icon' => 'bi-plus-circle'
            ];
        }

        return $nextSteps;
    }

    /**
     * Phase 2: Show getting started dashboard for new users
     */
    private function showGettingStartedDashboard($user, $onboardingStatus)
    {
        // Get basic stats even for new users (may be limited data)
        $schools = $this->getUserSchools($user);
        $schemaNames = $this->getSchemaNames($schools);
        
        // Check if user has no schools associated
        $hasNoSchoolsAssociated = $schools->isEmpty();
        
        // Get all the same data as regular dashboard to avoid undefined variable errors
        $totalStudents = $this->getTotalStudents($schemaNames);
        $avgAttendance = $this->getAverageAttendance($schemaNames);
        $feesCollected = $this->getFeesCollected($schemaNames);
        $feestobeCollected = $this->getFeesToBeCollected($schemaNames);
        $collection_rate = $feestobeCollected > 0 
            ? round(($feesCollected / $feestobeCollected) * 100, 2) 
            : 0;
        
        $activeSchools = count($schemaNames);
        $totalSchools = $schools->count();
        $topSchools = $this->getTopSchools($schools);
        $months = $this->getLast12Months();
        $feeCollectionTrend = $this->getFeeCollectionTrend($months, $schemaNames);
        $poorRevenueSchools = $this->getPoorRevenueSchools($schemaNames);
        $pendingBudgets = $this->getPendingBudgets($schools);
        $examResults = $this->getExamResults($schemaNames);

        $basicStats = [
            'total_schools' => $schools->count(),
            'active_schools' => count($schemaNames),
            'total_students' => $totalStudents,
            'pending_requests' => $onboardingStatus['pending_school_requests']
        ];

        return view('dashboard', compact(
            'totalStudents',
            'poorRevenueSchools',
            'schools',
            'pendingBudgets',
            'avgAttendance', 
            'feesCollected',
            'activeSchools',
            'totalSchools',
            'topSchools',
            'feeCollectionTrend',
            'months',
            'examResults',
            'collection_rate',
            'user',
            'onboardingStatus', 
            'basicStats',
            'hasNoSchoolsAssociated'
        ));
    }

    public function getExamResults($schemaNames)
    {
        return \DB::table('shulesoft.exam_report')
            ->selectRaw('schema_name, COUNT(*) as total_exams, MAX(created_at::date) as last_exam_date')
            ->whereIn('schema_name', $schemaNames)
            ->groupBy('schema_name')
            ->get();
    }
    }