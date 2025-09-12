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
            'collection_rate'
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
        return $schools->map(function ($school) {
            $schemaName = \DB::table('shulesoft.setting')
                ->where('uid', $school->school_setting_uid)
                ->value('schema_name');

            $totalFees = \DB::table('shulesoft.payments')
                ->where('schema_name', $schemaName)
                                    ->whereDate('created_at', '>=', $this->start)
                    ->whereDate('created_at', '<=', $this->end)
                ->sum('amount');

            $studentCount = \DB::table('shulesoft.student')
                ->where('schema_name', $schemaName)
                ->count();

            $avgPerStudent = $studentCount > 0 ? $totalFees / $studentCount : 0;

            return (object)[
                'school_name' => $school->name,
                'schema_name' => $schemaName,
                'amount'=>$totalFees,
                'avg_per_student' => round($avgPerStudent, 2),
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
        return $months->map(function ($month) use ($schemaNames) {
     
            $amount = \DB::table('shulesoft.payments')
                ->whereIn('schema_name', $schemaNames)
                ->whereDate('created_at', '>=', $this->start)
                ->whereDate('created_at', '<=', $this->end)
                ->whereMonth('date', $month)
                ->sum('amount');
            return round($amount, 2);
        });
    }

    public function getPoorRevenueSchools($schemaNames)
    {
       

        return \DB::table('shulesoft.setting')
            ->join('connect_schools', 'shulesoft.setting.uid', '=', 'connect_schools.school_setting_uid')
            ->whereIn('shulesoft.setting.schema_name', $schemaNames)
            ->select('shulesoft.setting.schema_name', 'shulesoft.setting.name')
            ->get()
            ->map(function ($school) use ($schemaNames) {
                $collected = \DB::table('shulesoft.payments')
                    ->where('schema_name', $school->schema_name)
                    ->whereDate('created_at', '>=', $this->start)
                    ->whereDate('created_at', '<=', $this->end)
                    ->sum('amount');

                $target = \DB::table('shulesoft.fees_installments_classes')
                    ->where('schema_name', $school->schema_name)
                    ->whereDate('created_at', '>=', $this->start)
                    ->whereDate('created_at', '<=', $this->end)
                    ->sum('amount');

                $collection_percent = $target > 0 ? ($collected / $target) * 100 : 0;

                return (object)[
                    'schema_name' => $school->schema_name,
                    'school_name' => $school->name,
                    'collected' => $collected,
                    'target' => $target,
                    'collection_percent' => round($collection_percent, 1),
                ];
            })
            ->sortBy('collection_percent')
            ->take(5)
            ->values();
    }

    public function getPendingBudgets($schools)
    {
        return $schools->map(function ($school) {
            $schemaName = \DB::table('shulesoft.setting')
                ->where('uid', $school->school_setting_uid)
                ->value('schema_name');

            $budget = \DB::table('shulesoft.budgets')
                ->where('schema_name', $schemaName)
                ->orderByDesc('id')
                ->first();

            $totalAmount = \DB::table('shulesoft.budgets')
                ->where('schema_name', $schemaName)
                ->whereDate('budget_from', '>=', $this->start)
                ->whereDate('budget_to', '<=', $this->end)
                ->sum('amount');

            return (object)[
                'school_name' => $school->name,
                'schema_name' => $schemaName,
                'is_prepared' => $budget ? true : false,
                'budget_from' => $budget->budget_from ?? null,
                'budget_to' => $budget->budget_to ?? null,
                'total_amount' => $totalAmount,
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
        
        $basicStats = [
            'total_schools' => $schools->count(),
            'active_schools' => count($schemaNames),
            'total_students' => $this->getTotalStudents($schemaNames),
            'pending_requests' => $onboardingStatus['pending_school_requests']
        ];

        return view('dashboard-getting-started', compact(
            'user',
            'onboardingStatus', 
            'schools',
            'basicStats'
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