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
        $schemaNames = $this->getSchemaNames($schools);

        $totalStudents = $this->getTotalStudents($schemaNames);
        $avgAttendance = $this->getAverageAttendance($schemaNames);
        $feesCollected = $this->getFeesCollected($schemaNames);
        $feestobeCollected = $this->getFeesToBeCollected($schemaNames);
        $collection_rate=round(($feesCollected / $feestobeCollected) * 100, 2);
        
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

    public function getExamResults($schemaNames)
    {
        return \DB::table('shulesoft.exam_report')
            ->selectRaw('schema_name, COUNT(*) as total_exams, MAX(created_at::date) as last_exam_date')
            ->whereIn('schema_name', $schemaNames)
            ->groupBy('schema_name')
            ->get();
    }
    }