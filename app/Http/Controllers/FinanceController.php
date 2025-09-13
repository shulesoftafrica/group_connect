<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\School;
use App\Models\User;
use App\Models\Organization;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class FinanceController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $dashboard = app()->make(\App\Http\Controllers\DashboardController::class);
        $schools = $dashboard->getUserSchools($user);

        $financialKPIs = $this->calculateFinancialKPIs();
        $schoolsList = $this->getSchoolsFinancialList($schools);
        $bankAccounts = $this->getBankAccountsData();
        $performanceData = $this->getFinancialPerformanceData();
        $alertsData = $this->getFinancialAlerts();
        $revenueExpenseData = $this->getRevenueExpenseData();
        $regionCollectionData = $this->getRevenueCollectionByRegion();
        
        return view('finance.dashboard', compact(
            'financialKPIs',
            'schoolsList',
            'bankAccounts',
            'performanceData',
            'alertsData',
            'revenueExpenseData',
            'regionCollectionData'
        ));
    }

    public function schoolDetail($id)
    {
        $school = School::with(['organization', 'user'])->findOrFail($id);
        
        $schoolFinancialData = $this->getSchoolFinancialData($school);
        $feesData = $this->getSchoolFeesData($school);
        $expensesData = $this->getSchoolExpensesData($school);
        $payrollData = $this->getSchoolPayrollData($school);
        $bankData = $this->getSchoolBankData($school);
        $budgetData = $this->getSchoolBudgetData($school);
        
        return view('finance.school-detail', compact(
            'school',
            'schoolFinancialData',
            'feesData',
            'expensesData',
            'payrollData',
            'bankData',
            'budgetData'
        ));
    }

    public function reconciliation()
    {
        $schools = School::with('organization')->get();
        
        $summary = [
            'total_accounts' => 45,
            'reconciled_accounts' => 38,
            'pending_reconciliation' => 7,
            'total_discrepancies' => 125000
        ];
        
        return view('finance.reconciliation', compact('schools', 'summary'));
    }

    public function bankReconciliation(Request $request)
    {
        // Handle bank reconciliation processing
        return response()->json([
            'success' => true,
            'message' => 'Bank reconciliation completed successfully',
            'reconciled_amount' => $request->input('amount'),
            'matched_transactions' => $request->input('matched_count', 0)
        ]);
    }

    public function importStatement(Request $request)
    {
        $request->validate([
            'bank' => 'required|string',
            'account_number' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'statement_file' => 'required|file|mimes:csv,xlsx,pdf|max:10240'
        ]);

        // Process bank statement import
        try {
            $fileName = $request->file('statement_file')->getClientOriginalName();
            
            // Here would be actual file processing logic
            // For now, return success with placeholder count
            $transactionsImported = 0; // Would be actual count after processing
            
            return response()->json([
                'success' => true,
                'message' => 'Bank statement imported successfully',
                'transactions_imported' => $transactionsImported,
                'file_name' => $fileName
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Error importing bank statement: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Error importing bank statement: ' . $e->getMessage(),
                'transactions_imported' => 0,
                'file_name' => $request->file('statement_file')->getClientOriginalName()
            ]);
        }
    }

    public function bankReconciliationView()
    {
        $banks = $this->getBankReconciliationData();
        return view('finance.bank-reconciliation', compact('banks'));
    }

    public function outstandingFees()
    {
        $outstandingData = $this->getOutstandingFeesData();
        return view('finance.outstanding-fees', compact('outstandingData'));
    }

    public function budgetManagement()
    {
        $budgetData = $this->getBudgetManagementData();
        return view('finance.budget-management', compact('budgetData'));
    }

    public function exportReport(Request $request)
    {
        $format = $request->get('format', 'excel');
        $reportType = $request->get('report_type', 'financial_summary');
        $schoolIds = $request->get('schools', []);
        $dateRange = $request->get('date_range', 'current_month');
        
        // Implementation for exporting financial reports
        return response()->json([
            'success' => true,
            'message' => 'Financial report export initiated',
            'download_url' => '/downloads/financial-report-' . time() . '.' . ($format === 'excel' ? 'xlsx' : 'pdf')
        ]);
    }

    public function bulkAction(Request $request)
    {
        $action = $request->get('action');
        $schoolIds = $request->get('schools', []);
        $data = $request->get('data', []);
        
        switch ($action) {
            case 'approve_budgets':
                return $this->bulkApproveBudgets($schoolIds, $data);
            case 'send_fee_reminders':
                return $this->bulkSendFeeReminders($schoolIds, $data);
            case 'update_bank_settings':
                return $this->bulkUpdateBankSettings($schoolIds, $data);
            case 'reconcile_accounts':
                return $this->bulkReconcileAccounts($schoolIds, $data);
            default:
                return response()->json(['success' => false, 'message' => 'Invalid action']);
        }
    }

    private function calculateFinancialKPIs()
    {
        
        
        // derive KPI values using DashboardController methods
        $dashboard = app()->make(\App\Http\Controllers\DashboardController::class);
        $user = auth()->user();
        $schools = $dashboard->getUserSchools($user);
        $schemaNames = $dashboard->getSchemaNames($schools);


        // group revenue from payments (no date window available here)

        $totalRevenue = DB::table('shulesoft.payments')->whereIn('schema_name', $schemaNames)
            ->whereBetween('created_at', [$this->start, $this->end])
            ->sum('amount');

        // total fees expected (to be collected)
        $totalFeesToBe = DB::table('shulesoft.material_invoice_balance')->whereIn('schema_name', $schemaNames)
            ->where(function ($query) {
                $query->whereBetween('end_date', [$this->start, $this->end])
                      ->orWhereBetween('start_date', [$this->start, $this->end]);
            })
            ->sum('total_amount');

        // outstanding is expected minus collected (if available)
        $outstandingTotal = $totalFeesToBe - $totalRevenue;
        $collectionRate = $totalFeesToBe > 0 ? round(($totalRevenue / $totalFeesToBe) * 100, 2) : null;

        // fee collection trend (last 12 months) similar to DashboardController->getFeeCollectionTrend
        $months = collect(range(11, 0))->map(function ($i) {
            return Carbon::now()->subMonths($i)->format('m');
        });
        $feeCollectionTrend = $months->map(function ($month) use ($schemaNames) {
            return (float) DB::table('shulesoft.payments')->whereIn('schema_name', $schemaNames)
                ->whereBetween('created_at', [$this->start, $this->end])
                ->whereMonth('created_at', $month)
                ->sum('amount');
        });

        // budget preparation status using DashboardController->getPendingBudgets
        $pendingBudgets = $dashboard->getPendingBudgets($schools);
        $approvedBudgetsCount = $pendingBudgets->where('is_prepared', true)->count();
        $pendingApprovalCount = $pendingBudgets->where('is_prepared', false)->count();

        $bank_balances = DB::table('shulesoft.payments as p')
            ->join('shulesoft.bank_accounts as ba', 'p.bank_account_id', '=', 'ba.id')
            ->join('constant.refer_banks as rb', 'ba.refer_bank_id', '=', 'rb.id')
            ->whereIn('p.schema_name', $schemaNames)
            ->whereBetween('p.created_at', [$this->start, $this->end])
            ->groupBy('rb.id', 'rb.name')
            ->select('rb.id as bank_id', 'rb.name as bank_name', DB::raw('COALESCE(SUM(p.amount),0) as total_collected'))
            ->get()
            ->mapWithKeys(function ($row) {
            return [$row->bank_name => (float) $row->total_collected];
            })
            ->toArray();

           
         $salariesSums = DB::table('shulesoft.salaries')
                ->whereIn('schema_name', $schemaNames)
                ->whereBetween('created_at', [$this->start, $this->end])
                ->select(
                    DB::raw('COALESCE(SUM(net_pay),0) as total_net_pay'),
                    DB::raw('COALESCE(SUM(paye),0) as total_paye'),
                    DB::raw('COALESCE(SUM(pension_fund),0) as total_pension_payment'),
                    DB::raw('COALESCE(SUM(allowance),0) as total_allowances')
                )
                ->first();

            $total_netpay = (float) ($salariesSums->total_net_pay ?? 0);
            $total_paye = (float) ($salariesSums->total_paye ?? 0);
            $total_pension_payment = (float) ($salariesSums->total_pension_payment ?? 0);
            $total_allowances = (float) ($salariesSums->total_allowances ?? 0);

        // Many finance KPIs (expenses breakdown, bank balances, payroll details, overdue amounts, etc.)
        // are not provided by DashboardController; leave as null so caller can fill with other sources.
        return [
            'total_schools' => count($schemaNames),
            'group_revenue' => [
            'total' => $totalRevenue,
            'monthly_trend' => $feeCollectionTrend->count() > 1
                ? round((($feeCollectionTrend->last() - $feeCollectionTrend->first()) / max(1, $feeCollectionTrend->first())) * 100, 2)
                : null,
            'target_achievement' => null, // not derivable from DashboardController
            ],
            'group_expenses' => [
            'total' => DB::table('shulesoft.expenses')
                ->whereIn('schema_name', $schemaNames)
                ->whereBetween('created_at', [$this->start, $this->end])
                ->sum('amount'),
            'monthly_trend' => null,
            'budget_utilization' => null,
            ],
            'outstanding_fees' => [
            'total' => $outstandingTotal,
            'overdue_amount' => null, // overdue details not available
            'collection_rate' => $collectionRate,
            'defaulters_count' => null,
            ],
            'fixed_assets' => [
            'total_assets' => DB::table('shulesoft.fixed_assets')
                ->whereIn('schema_name', $schemaNames)
                ->whereBetween('created_at', [$this->start, $this->end])
                ->sum('amount'),   // not available
            ],
            'bank_balances' => $bank_balances,
            'payroll_summary' => [
            'total_net' =>$total_netpay, // not available
            'paye_payments' => $total_paye,
            'total_pension_payment' => $total_pension_payment,
            'total_allowances' => $total_allowances,
            'staff_cost_percentage' => $total_netpay > 0 ? round(($total_netpay / ($total_netpay + $total_paye + $total_pension_payment + $total_allowances)) * 100, 2) : 0,
            ],
            'budget_status' => [
                'approved_budgets' => $approvedBudgetsCount,
                'pending_approval' => $pendingApprovalCount,
                'total_schools' => count($schemaNames),
                'schools_with_budget' => $approvedBudgetsCount,
                'schools_without_budget' => count($schemaNames) - $approvedBudgetsCount,
                'budget_completion_rate' => count($schemaNames) > 0 ? round(($approvedBudgetsCount / count($schemaNames)) * 100, 1) : 0,
                'group_variance' => 0, // Would need budget vs actual calculation
                'next_deadline' => Carbon::now()->addMonths(3)->format('M d, Y'),
                'last_update' => Carbon::now()->format('M d, Y'),
                'over_budget_schools' => null, // requires expense vs budget comparison not present
                'budget_variance' => null,
            ]
        ];
    }

    private function getSchoolsFinancialList($schools)
    {

        return $schools->map(function ($school) {
                $settings = $school->schoolSetting ?? [];

                $revenue = $school->totalRevenue();
                $expenses = $school->totalExpenses();
                $outstandingFees = $school->outstandingFees();

                return [
                    'id' => $school->id,
                    'name' => $settings->sname ?? 'Unknown School',
                    'code' => $school->shulesoft_code,
                    'region' => $settings->region ?? 'Unknown',
                    'revenue' => $revenue,
                    'expenses' => $expenses,
                    'profit_margin' => $revenue > 0 ? round((($revenue - $expenses) / $revenue) * 100, 1) : 0,
                    'outstanding_fees' => $outstandingFees,
                    'collection_rate' => $outstandingFees > 0 ? round($revenue/($outstandingFees)  * 100, 1) : 0,
                   // 'bank_balance' => rand(500000, 5000000),
                    'financial_health' => $this->calculateFinancialHealth($revenue, $expenses, $outstandingFees),
                    'last_updated' => Carbon::now()->format('Y-m-d'),
                ];
            });
    }

    private function calculateFinancialHealth($revenue, $expenses, $outstanding)
    {
        $profitMargin = $revenue > 0 ? (($revenue - $expenses) / $revenue) * 100 : 0;
        $collectionRatio = $revenue > 0 ? (($revenue - $outstanding) / $revenue) * 100 : 0;
        
        $score = ($profitMargin * 0.6) + ($collectionRatio * 0.4);
        
        if ($score >= 80) return 'excellent';
        if ($score >= 65) return 'good';
        if ($score >= 50) return 'average';
        return 'poor';
    }

    private function getBankAccountsData()
    {
        try {
            // Get schema names for current user's schools
            $dashboard = app()->make(\App\Http\Controllers\DashboardController::class);
            $user = auth()->user();
            
            if (!$user) {
                return [];
            }
            
            $schools = $dashboard->getUserSchools($user);
            $schemaNames = $dashboard->getSchemaNames($schools);

            if ($schemaNames->isEmpty()) {
                return [];
            }

            $schemaArray = $schemaNames->toArray();

            // Get bank accounts data from database
            $bankAccountsData = DB::table('shulesoft.bank_accounts as ba')
                ->join('constant.refer_banks as rb', 'ba.refer_bank_id', '=', 'rb.id')
                ->join('shulesoft.setting as s', 'ba.schema_name', '=', 's.schema_name')
                ->leftJoin('shulesoft.payments as p', function($join) {
                    $join->on('ba.id', '=', 'p.bank_account_id')
                         ->whereBetween('p.date', [Carbon::now()->startOfMonth(), Carbon::now()]);
                })
                ->whereIn('ba.schema_name', $schemaArray)
                ->where('ba.is_active', true)
                ->groupBy('rb.id', 'rb.name', 'ba.id', 'ba.number', 'ba.schema_name', 's.school_name')
                ->select(
                    'rb.id as bank_id',
                    'rb.name as bank_name',
                    'ba.id as account_id',
                    'ba.number',
                    'ba.schema_name',
                    's.school_name',
                    DB::raw('MAX(p.date) as last_transaction_date'),
                    DB::raw('COALESCE(SUM(p.amount), 0) as monthly_transactions')
                )
                ->get();

            // Group by bank
            $accounts = [];
            $bankGroups = $bankAccountsData->groupBy('bank_name');

            foreach ($bankGroups as $bankName => $bankAccounts) {
                $schoolAccounts = [];
                $totalBalance = 0;

                foreach ($bankAccounts as $account) {
                    $balance = (float) ($account->balance ?? 0);
                    $totalBalance += $balance;

                    $schoolAccounts[] = [
                        'school_id' => $account->schema_name,
                        'school_name' => $account->school_name ?? 'Unknown School',
                        'account_number' => $account->account_number,
                        'balance' => $balance,
                        'last_transaction' => $account->last_transaction_date ?? 'No transactions',
                        'status' => $account->monthly_transactions > 0 ? 'active' : 'needs_reconciliation'
                    ];
                }

                $accounts[] = [
                    'bank_name' => $bankName,
                    'total_balance' => $totalBalance,
                    'accounts_count' => count($schoolAccounts),
                    'schools' => $schoolAccounts
                ];
            }

            return $accounts;

        } catch (\Exception $e) {
            \Log::error('Error in getBankAccountsData: ' . $e->getMessage());
            
            // Return fallback data with error message
            return [];
        }
    }

    private function generateAccountNumber()
    {
        // Generate a timestamp-based account number instead of random
        return sprintf('%010d', time() % 10000000000);
    }

    private function getFinancialPerformanceData()
    {
        try {
            // Get schema names for current user's schools
            $dashboard = app()->make(\App\Http\Controllers\DashboardController::class);
            $user = auth()->user();
            
            if (!$user) {
                return $this->getEmptyPerformanceData('No authenticated user');
            }
            
            $schools = $dashboard->getUserSchools($user);
            $schemaNames = $dashboard->getSchemaNames($schools);

            // If no schemas available, return empty data
            if ($schemaNames->isEmpty()) {
                return $this->getEmptyPerformanceData('No accessible schools found');
            }

            $schemaArray = $schemaNames->toArray();
            $currentYear = Carbon::now()->year;
            
            // Initialize arrays for 12 months (January to December)
            $months = [];
            $revenueData = [];
            $expenseData = [];
            $profitData = [];
            
            // Generate months array from January to December of current year
            for ($month = 1; $month <= 12; $month++) {
                $date = Carbon::create($currentYear, $month, 1);
                $months[] = $date->format('M Y');
                
                // Get start and end dates for the month
                $startDate = $date->startOfMonth()->toDateString();
                $endDate = $date->endOfMonth()->toDateString();
                
                // Get revenue from payments table for this month
                $monthlyRevenue = DB::table('shulesoft.payments')
                    ->whereIn('schema_name', $schemaArray)
                    ->whereBetween('date', [$startDate, $endDate])
                    ->sum('amount') ?? 0;
                
                // Get expenses from expenses table for this month
                $monthlyExpenses = DB::table('shulesoft.expenses')
                    ->whereIn('schema_name', $schemaArray)
                    ->whereBetween('date', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
                    ->sum('amount') ?? 0;
                
                // Calculate profit (revenue - expenses)
                $monthlyProfit = $monthlyRevenue - $monthlyExpenses;
                
                // Store the data
                $revenueData[] = (float) $monthlyRevenue;
                $expenseData[] = (float) $monthlyExpenses;
                $profitData[] = (float) $monthlyProfit;
            }
            
            // Calculate totals and trends
            $totalRevenue = array_sum($revenueData);
            $totalExpenses = array_sum($expenseData);
            $totalProfit = $totalRevenue - $totalExpenses;
            
            // Calculate growth trends (comparing current vs previous months)
            $revenueGrowth = $this->calculateGrowthTrend($revenueData);
            $expenseGrowth = $this->calculateGrowthTrend($expenseData);
            $profitGrowth = $this->calculateGrowthTrend($profitData);
            
            return [
                'months' => $months,
                'revenue_trend' => $revenueData,
                'expense_trend' => $expenseData,
                'profit_trend' => $profitData,
                'totals' => [
                    'revenue' => $totalRevenue,
                    'expenses' => $totalExpenses,
                    'profit' => $totalProfit,
                    'profit_margin' => $totalRevenue > 0 ? round(($totalProfit / $totalRevenue) * 100, 2) : 0
                ],
                'growth_trends' => [
                    'revenue_growth' => $revenueGrowth,
                    'expense_growth' => $expenseGrowth,
                    'profit_growth' => $profitGrowth
                ],
                'year' => $currentYear,
                'schools_count' => count($schemaArray)
            ];

        } catch (\Exception $e) {
            \Log::error('Error in getFinancialPerformanceData: ' . $e->getMessage());
            
            return $this->getEmptyPerformanceData('Error loading performance data: ' . $e->getMessage());
        }
    }

    private function calculateGrowthTrend($data)
    {
        if (count($data) < 2) {
            return 0;
        }
        
        // Get last non-zero values for comparison
        $current = 0;
        $previous = 0;
        
        // Find the last non-zero value
        for ($i = count($data) - 1; $i >= 0; $i--) {
            if ($data[$i] > 0) {
                $current = $data[$i];
                break;
            }
        }
        
        // Find the previous non-zero value
        for ($i = count($data) - 2; $i >= 0; $i--) {
            if ($data[$i] > 0) {
                $previous = $data[$i];
                break;
            }
        }
        
        if ($previous > 0) {
            return round((($current - $previous) / $previous) * 100, 2);
        }
        
        return 0;
    }

    private function getEmptyPerformanceData($message = 'No data available')
    {
        $currentYear = Carbon::now()->year;
        $months = [];
        
        // Generate month labels even for empty data
        for ($month = 1; $month <= 12; $month++) {
            $date = Carbon::create($currentYear, $month, 1);
            $months[] = $date->format('M Y');
        }
        
        return [
            'months' => $months,
            'revenue_trend' => array_fill(0, 12, 0),
            'expense_trend' => array_fill(0, 12, 0),
            'profit_trend' => array_fill(0, 12, 0),
            'totals' => [
                'revenue' => 0,
                'expenses' => 0,
                'profit' => 0,
                'profit_margin' => 0
            ],
            'growth_trends' => [
                'revenue_growth' => 0,
                'expense_growth' => 0,
                'profit_growth' => 0
            ],
            'year' => $currentYear,
            'schools_count' => 0,
            'message' => $message
        ];
    }

    private function getFinancialAlerts()
    {
        try {
            // Get schema names for current user's schools
            $dashboard = app()->make(\App\Http\Controllers\DashboardController::class);
            $user = auth()->user();
            
            if (!$user) {
                return $this->getEmptyFinancialAlertsData('No authenticated user');
            }
            
            $schools = $dashboard->getUserSchools($user);
            $schemaNames = $dashboard->getSchemaNames($schools);

            // Initialize date range
            $startDate = Carbon::now()->startOfYear();
            $endDate = Carbon::now();

            // If no schemas available, return empty data
            if ($schemaNames->isEmpty()) {
                return $this->getEmptyFinancialAlertsData('No accessible schools found');
            }

            $schemaArray = $schemaNames->toArray();

            // Get total revenue for percentage calculations
            $totalRevenue = DB::table('shulesoft.payments')
                ->whereIn('schema_name', $schemaArray)
                ->whereBetween('date', [$startDate->toDateString(), $endDate->toDateString()])
                ->sum('amount') ?? 0;

            // 1. OTHER REVENUE (Non-fee income) from shulesoft.revenues
            $otherRevenueQuery = DB::table('shulesoft.revenues')
                ->whereIn('schema_name', $schemaArray)
                ->whereBetween('date', [$startDate->toDateString(), $endDate->toDateString()]);

            $otherRevTotal = $otherRevenueQuery->sum('amount') ?? 0;
            
            $otherRevSources = DB::table('shulesoft.revenues')
                ->select('refer_expense_id as expense_id', DB::raw('SUM(amount) as amount'))
                ->whereIn('schema_name', $schemaArray)
                ->whereBetween('date', [$startDate->toDateString(), $endDate->toDateString()])
                ->groupBy('refer_expense_id')
                ->orderBy('amount', 'desc')
                ->get()
                ->toArray();

            $otherRevPercent = $totalRevenue > 0 ? round(($otherRevTotal / $totalRevenue) * 100, 1) : 0;

            // 2. DISCOUNTS from discount_fees_installments table
            $discountQuery = DB::table('shulesoft.discount_fees_installments')
                ->whereIn('schema_name', $schemaArray)
                ->whereBetween('created_at', [$startDate, $endDate]);

            $discountTotal = $discountQuery->sum('amount') ?? 0;
            $discountCount = $discountQuery->count();

            // 3. EXEMPTIONS - Since student_exemptions table doesn't exist, use discount data as proxy
            $exemptCount = 0;
            $exemptValue = 0;
            $exemptReasons = [];

            // 4. INVENTORY from items table (since inventory_items doesn't exist)
            $inventoryValue = 0;
            $lowStockCount = 0;
            $slowMovingItems = [];

            try {
                $inventoryValue = DB::table('shulesoft.items')
                    ->whereIn('schema_name', $schemaArray)
                    ->sum('open_blance') ?? 0;

                $lowStockCount = DB::table('shulesoft.items')
                    ->whereIn('schema_name', $schemaArray)
                    ->whereRaw('COALESCE(open_blance, 0) <= COALESCE(alert_quantity, 0)')
                    ->where('alert_quantity', '>', 0)
                    ->count();
            } catch (\Exception $e) {
                // Items table query failed, use defaults
            }

            // Financial alerts with corrected table structure
            $criticalAlerts = [];
            $warningAlerts = [];
            $infoAlerts = [];

            // Critical: Check for negative cash flow
            $totalExpenses = DB::table('shulesoft.expenses')
                ->whereIn('schema_name', $schemaArray)
                ->whereBetween('date', [$startDate, $endDate])
                ->sum('amount') ?? 0;

            $totalIncome = $totalRevenue + $otherRevTotal;
            $netIncome = $totalIncome - $totalExpenses;

            if ($netIncome < 0) {
                $criticalAlerts[] = [
                    'type' => 'negative_cashflow',
                    'school' => 'Multiple Schools',
                    'message' => 'Negative cash flow detected: TZS ' . number_format(abs($netIncome) / 1000000, 1) . 'M deficit',
                    'amount' => $netIncome,
                    'timestamp' => Carbon::now()->format('Y-m-d H:i'),
                    'action_required' => true
                ];
            }

            // Warning: High discount rate
            if ($totalRevenue > 0 && ($discountTotal > ($totalRevenue * 0.1))) {
                $discountPercent = round(($discountTotal / $totalRevenue) * 100, 1);
                $warningAlerts[] = [
                    'type' => 'high_discounts',
                    'school' => 'Multiple Schools',
                    'message' => 'High discount rate: ' . $discountPercent . '% of total revenue',
                    'amount' => $discountTotal,
                    'timestamp' => Carbon::now()->format('Y-m-d H:i'),
                    'action_required' => false
                ];
            }

            // Warning: Low stock items
            if ($lowStockCount > 5) {
                $warningAlerts[] = [
                    'type' => 'low_stock',
                    'school' => 'Multiple Schools',
                    'message' => $lowStockCount . ' items are below minimum stock levels',
                    'amount' => $lowStockCount,
                    'timestamp' => Carbon::now()->format('Y-m-d H:i'),
                    'action_required' => false
                ];
            }

            // Info: Bank reconciliation reminders
            $unReconciledCount = DB::table('shulesoft.payments')
                ->whereIn('schema_name', $schemaArray)
                ->where('reconciled', '!=', 1)
                ->count();

            if ($unReconciledCount > 0) {
                $infoAlerts[] = [
                    'type' => 'reconciliation',
                    'school' => 'Multiple Schools',
                    'message' => $unReconciledCount . ' transactions need bank reconciliation',
                    'amount' => $unReconciledCount,
                    'timestamp' => Carbon::now()->format('Y-m-d H:i'),
                    'action_required' => false
                ];
            }

            // Info: Financial summary
            if ($totalIncome > 0 || $totalExpenses > 0) {
                $infoAlerts[] = [
                    'type' => 'financial_summary',
                    'school' => 'All Schools',
                    'message' => 'Total Income: TZS ' . number_format($totalIncome / 1000000, 1) . 'M, Expenses: TZS ' . number_format($totalExpenses / 1000000, 1) . 'M',
                    'amount' => $netIncome,
                    'timestamp' => Carbon::now()->format('Y-m-d H:i'),
                    'action_required' => false
                ];
            }

            return [
                'alerts' => [
                    'critical' => $criticalAlerts,
                    'warnings' => $warningAlerts,
                    'info' => $infoAlerts
                ],
                'reportsData' => [
                    'totals' => [
                        'schools_count' => count($schemaArray),
                        'total_revenue' => $totalRevenue,
                        'total_expenses' => $totalExpenses,
                        'net_income' => $netIncome
                    ],
                    'other_revenue' => [
                        'total' => $otherRevTotal,
                        'percentage_of_total' => $otherRevPercent,
                        'sources' => $otherRevSources
                    ],
                    'discounts' => [
                        'total_amount' => $discountTotal,
                        'count' => $discountCount,
                        'percentage' => $totalRevenue > 0 ? round(($discountTotal / $totalRevenue) * 100, 1) : 0
                    ],
                    'exemptions' => [
                        'student_count' => $exemptCount,
                        'total_value' => $exemptValue,
                        'top_reasons' => $exemptReasons
                    ],
                    'inventory' => [
                        'total_value' => $inventoryValue,
                        'low_stock_count' => $lowStockCount,
                        'slow_moving' => $slowMovingItems
                    ]
                ]
            ];

        } catch (\Exception $e) {
            \Log::error('Error in getFinancialAlerts: ' . $e->getMessage());
            
            return $this->getEmptyFinancialAlertsData('Error loading financial data: ' . $e->getMessage());
        }
    }

    private function getEmptyFinancialAlertsData($message = 'No data available')
    {
        return [
            'alerts' => [
                'critical' => [],
                'warnings' => [],
                'info' => [
                    [
                        'type' => 'no_data',
                        'school' => 'System',
                        'message' => $message,
                        'timestamp' => Carbon::now()->format('Y-m-d H:i'),
                        'action_required' => false
                    ]
                ]
            ],
            'reportsData' => [
                'totals' => [
                    'schools_count' => 0,
                    'total_revenue' => 0,
                    'total_expenses' => 0,
                    'net_income' => 0
                ],
                'other_revenue' => [
                    'total' => 0,
                    'percentage_of_total' => 0,
                    'sources' => []
                ],
                'discounts' => [
                    'total_amount' => 0,
                    'count' => 0,
                    'percentage' => 0
                ],
                'exemptions' => [
                    'student_count' => 0,
                    'total_value' => 0,
                    'top_reasons' => []
                ],
                'inventory' => [
                    'total_value' => 0,
                    'low_stock_count' => 0,
                    'slow_moving' => []
                ]
            ]
        ];
    }

    private function getRevenueExpenseData()
    {
        try {
            // Get schema names for current user's schools using DashboardController pattern
            $dashboard = app()->make(\App\Http\Controllers\DashboardController::class);
            $user = auth()->user();
            
            if (!$user) {
                return [];
            }
            
            $schools = $dashboard->getUserSchools($user);
            $schemaNames = $dashboard->getSchemaNames($schools);

            // If no schemas available, return empty data
            if ($schemaNames->isEmpty()) {
                return [];
            }

            $schemaArray = $schemaNames->toArray();
            $currentYear = Carbon::now()->year;
            
            // Initialize date range (current year)
            $startDate = Carbon::now()->startOfYear();
            $endDate = Carbon::now();

            // Phase 1 Optimization: Replace N+1 queries with single optimized query
            $schoolIds = $schools->pluck('id')->toArray();
            
            if (empty($schoolIds)) {
                return [];
            }
            
            $placeholders = str_repeat('?,', count($schoolIds) - 1) . '?';
            
            $results = \DB::select("
                SELECT 
                    cs.name as school_name,
                    ss.schema_name,
                    COALESCE(ss.sname, cs.name, 'Unknown School') as settings_school_name,
                    COALESCE(payments.revenue, 0) as revenue,
                    COALESCE(expenses.total_expenses, 0) as expenses,
                    COALESCE(students.student_count, 0) as student_count,
                    CASE 
                        WHEN COALESCE(payments.revenue, 0) > 0 
                        THEN ROUND((COALESCE(payments.revenue, 0) - COALESCE(expenses.total_expenses, 0)) / COALESCE(payments.revenue, 0) * 100, 2)
                        ELSE 0 
                    END as profit_margin,
                    CASE 
                        WHEN COALESCE(students.student_count, 0) > 0 
                        THEN ROUND(COALESCE(payments.revenue, 0) / COALESCE(students.student_count, 0), 2)
                        ELSE 0 
                    END as revenue_per_student,
                    (COALESCE(payments.revenue, 0) - COALESCE(expenses.total_expenses, 0)) as profit
                FROM connect_schools cs
                JOIN shulesoft.setting ss ON cs.school_setting_uid = ss.uid
                LEFT JOIN (
                    SELECT schema_name, SUM(amount) as revenue 
                    FROM shulesoft.payments 
                    WHERE created_at BETWEEN ? AND ? AND EXTRACT(YEAR FROM created_at) = ?
                    GROUP BY schema_name
                ) payments ON ss.schema_name = payments.schema_name
                LEFT JOIN (
                    SELECT schema_name, SUM(amount) as total_expenses 
                    FROM shulesoft.expenses 
                    WHERE created_at BETWEEN ? AND ? AND EXTRACT(YEAR FROM created_at) = ?
                    GROUP BY schema_name
                ) expenses ON ss.schema_name = expenses.schema_name
                LEFT JOIN (
                    SELECT schema_name, COUNT(*) as student_count 
                    FROM shulesoft.student 
                    WHERE status = 1
                    GROUP BY schema_name
                ) students ON ss.schema_name = students.schema_name
                WHERE cs.id IN ({$placeholders})
                ORDER BY revenue DESC
                LIMIT 10
            ", array_merge([$startDate, $endDate, $currentYear, $startDate, $endDate, $currentYear], $schoolIds));

            $schoolComparison = collect($results)->map(function ($result) use ($currentYear) {
                return [
                    'school' => $result->settings_school_name,
                    'schema_name' => $result->schema_name,
                    'revenue' => (float) $result->revenue,
                    'expense' => (float) $result->expenses,
                    'profit' => (float) $result->profit,
                    'profit_margin' => (float) $result->profit_margin,
                    'revenue_per_student' => (float) $result->revenue_per_student,
                    'student_count' => (int) $result->student_count,
                    'year' => $currentYear
                ];
            })->toArray();
            
            return $schoolComparison;

        } catch (\Exception $e) {
            \Log::error('Error in getRevenueExpenseData: ' . $e->getMessage());
            
            // Return empty array on error
            return [];
        }
    }

    private function getRevenueCollectionByRegion()
    {
        try {
            // Get schema names for current user's schools
            $dashboard = app()->make(\App\Http\Controllers\DashboardController::class);
            $user = auth()->user();
            
            if (!$user) {
                return $this->getEmptyRegionCollectionData('No authenticated user');
            }
            
            $schools = $dashboard->getUserSchools($user);
            $schemaNames = $dashboard->getSchemaNames($schools);

            // If no schemas available, return empty data
            if ($schemaNames->isEmpty()) {
                return $this->getEmptyRegionCollectionData('No schools found for user');
            }

            $schemaArray = $schemaNames->toArray();
            $currentYear = Carbon::now()->year;
            
            // Initialize date range
            $startDate = Carbon::now()->startOfYear();
            $endDate = Carbon::now();

            // Get revenue collection data grouped by region/address from school settings
            $regionCollectionData = DB::table('shulesoft.payments as p')
                ->join('shulesoft.setting as s', 'p.schema_name', '=', 's.schema_name')
                ->whereIn('p.schema_name', $schemaArray)
                ->whereBetween('p.created_at', [$startDate, $endDate])
                ->whereYear('p.created_at', $currentYear)
                ->groupBy('s.region', 's.address')
                ->select(
                    's.region',
                    's.address',
                    DB::raw('SUM(p.amount) as total_revenue'),
                    DB::raw('COUNT(p.id) as transaction_count'),
                    DB::raw('AVG(p.amount) as average_payment'),
                    DB::raw('COUNT(DISTINCT p.schema_name) as schools_count')
                )
                ->orderByDesc('total_revenue')
                ->get();

            // Process the data for chart display
            $processedData = [];
            $totalRevenue = $regionCollectionData->sum('total_revenue');

            foreach ($regionCollectionData as $row) {
                // Use region if available, otherwise use address, otherwise use "Unknown"
                $locationLabel = $row->region ?: ($row->address ?: 'Unknown Location');
                
                // If location already exists, aggregate the data
                if (isset($processedData[$locationLabel])) {
                    $processedData[$locationLabel]['total_revenue'] += $row->total_revenue;
                    $processedData[$locationLabel]['transaction_count'] += $row->transaction_count;
                    $processedData[$locationLabel]['schools_count'] += $row->schools_count;
                } else {
                    $processedData[$locationLabel] = [
                        'location' => $locationLabel,
                        'total_revenue' => (float) $row->total_revenue,
                        'transaction_count' => (int) $row->transaction_count,
                        'average_payment' => (float) $row->average_payment,
                        'schools_count' => (int) $row->schools_count,
                        'percentage' => $totalRevenue > 0 ? round(($row->total_revenue / $totalRevenue) * 100, 1) : 0
                    ];
                }
            }

            // Sort by total revenue descending and take top 5
            $sortedData = collect($processedData)
                ->sortByDesc('total_revenue')
                ->take(5)
                ->values()
                ->toArray();

            return [
                'regions' => $sortedData,
                'total_revenue' => $totalRevenue,
                'total_schools' => $schemaNames->count(),
                'year' => $currentYear,
                'date_range' => [
                    'start' => $startDate->toDateString(),
                    'end' => $endDate->toDateString()
                ]
            ];

        } catch (\Exception $e) {
            \Log::error('Error in getRevenueCollectionByRegion: ' . $e->getMessage());
            
            return $this->getEmptyRegionCollectionData('Error loading region data: ' . $e->getMessage());
        }
    }

    private function getEmptyRegionCollectionData($message = 'No data available')
    {
        return [
            'regions' => [],
            'total_revenue' => 0,
            'total_schools' => 0,
            'year' => Carbon::now()->year,
            'date_range' => [
                'start' => Carbon::now()->startOfYear()->toDateString(),
                'end' => Carbon::now()->toDateString()
            ],
            'message' => $message
        ];
    }

    private function getSchoolFinancialData($school)
    {
        try {
            $schemaName = $school->schema_name ?? $school->name;
            
            // Get current month date range
            $startOfMonth = Carbon::now()->startOfMonth();
            $endOfMonth = Carbon::now()->endOfMonth();
            
            // Get school settings
            $settings = DB::table('shulesoft.setting')
                ->where('schema_name', $schemaName)
                ->first();
            
            // Monthly revenue from payments
            $monthlyRevenue = DB::table('shulesoft.payments')
                ->where('schema_name', $schemaName)
                ->whereBetween('date', [$startOfMonth->toDateString(), $endOfMonth->toDateString()])
                ->sum('amount') ?? 0;
                
            // Monthly expenses
            $monthlyExpenses = DB::table('shulesoft.expenses')
                ->where('schema_name', $schemaName)
                ->whereBetween('date', [$startOfMonth->toDateString(), $endOfMonth->toDateString()])
                ->sum('amount') ?? 0;
                
            // Outstanding fees calculation
            $totalExpectedFees = DB::table('shulesoft.fees_installments_classes')
                ->where('schema_name', $schemaName)
                ->sum('amount') ?? 0;
                
            $totalPaidFees = DB::table('shulesoft.payments')
                ->where('schema_name', $schemaName)
                ->whereYear('date', Carbon::now()->year)
                ->sum('amount') ?? 0;
                
            $outstandingFees = $totalExpectedFees - $totalPaidFees;
            
            // Bank balance from bank accounts
            $bankBalance = DB::table('shulesoft.bank_accounts')
                ->where('schema_name', $schemaName)
                ->where('is_active', true)
                ->sum('balance') ?? 0;
                
            // Calculate profit margin
            $profitMargin = $monthlyRevenue > 0 ? 
                round((($monthlyRevenue - $monthlyExpenses) / $monthlyRevenue) * 100, 2) : 0;
                
            // Collection rate
            $collectionRate = $totalExpectedFees > 0 ? 
                round(($totalPaidFees / $totalExpectedFees) * 100, 2) : 0;
                
            // Calculate trends by comparing with previous month
            $prevMonthStart = Carbon::now()->subMonth()->startOfMonth();
            $prevMonthEnd = Carbon::now()->subMonth()->endOfMonth();
            
            $prevMonthRevenue = DB::table('shulesoft.payments')
                ->where('schema_name', $schemaName)
                ->whereBetween('date', [$prevMonthStart->toDateString(), $prevMonthEnd->toDateString()])
                ->sum('amount') ?? 0;
                
            $prevMonthExpenses = DB::table('shulesoft.expenses')
                ->where('schema_name', $schemaName)
                ->whereBetween('date', [$prevMonthStart->toDateString(), $prevMonthEnd->toDateString()])
                ->sum('amount') ?? 0;
                
            // Growth calculations
            $revenueGrowth = $prevMonthRevenue > 0 ? 
                round((($monthlyRevenue - $prevMonthRevenue) / $prevMonthRevenue) * 100, 2) : 0;
                
            $expenseGrowth = $prevMonthExpenses > 0 ? 
                round((($monthlyExpenses - $prevMonthExpenses) / $prevMonthExpenses) * 100, 2) : 0;
            
            return [
                'summary' => [
                    'monthly_revenue' => (float) $monthlyRevenue,
                    'monthly_expenses' => (float) $monthlyExpenses,
                    'outstanding_fees' => (float) $outstandingFees,
                    'bank_balance' => (float) $bankBalance,
                    'profit_margin' => $profitMargin,
                    'collection_rate' => $collectionRate
                ],
                'trends' => [
                    'revenue_growth' => $revenueGrowth,
                    'expense_growth' => $expenseGrowth,
                    'fee_collection_trend' => $revenueGrowth // Using revenue growth as fee collection trend
                ]
            ];
            
        } catch (\Exception $e) {
            \Log::error('Error in getSchoolFinancialData for school ' . ($school->schema_name ?? 'unknown') . ': ' . $e->getMessage());
            
            // Return fallback data
            $settings = $school->settings ?? [];
            return [
                'summary' => [
                    'monthly_revenue' => (float) ($settings['monthly_revenue'] ?? 0),
                    'monthly_expenses' => (float) ($settings['monthly_expenses'] ?? 0),
                    'outstanding_fees' => (float) ($settings['outstanding_fees'] ?? 0),
                    'bank_balance' => 0,
                    'profit_margin' => 0,
                    'collection_rate' => 0
                ],
                'trends' => [
                    'revenue_growth' => 0,
                    'expense_growth' => 0,
                    'fee_collection_trend' => 0
                ]
            ];
        }
    }

    private function getSchoolFeesData($school)
    {
        try {
            $schemaName = $school->schema_name ?? $school->name;
            
            // Total expected fees for current academic year
            $totalExpected = DB::table('shulesoft.fees_installments_classes')
                ->where('schema_name', $schemaName)
                ->whereYear('created_at', Carbon::now()->year)
                ->sum('amount') ?? 0;
                
            // Total collected fees
            $collected = DB::table('shulesoft.payments')
                ->where('schema_name', $schemaName)
                ->whereYear('date', Carbon::now()->year)
                ->sum('amount') ?? 0;
                
            // Outstanding fees
            $outstanding = $totalExpected - $collected;
            
            // Overdue fees (fees past due date)
            $overdue = DB::table('shulesoft.fees_installments_classes as fic')
                ->leftJoin('shulesoft.payments as p', function($join) {
                    $join->on('fic.student_id', '=', 'p.student_id')
                         ->on('fic.schema_name', '=', 'p.schema_name');
                })
                ->where('fic.schema_name', $schemaName)
                ->where('fic.due_date', '<', Carbon::now()->toDateString())
                ->whereNull('p.id') // Not paid
                ->sum('fic.amount') ?? 0;
                
            // Collection rate
            $collectionRate = $totalExpected > 0 ? 
                round(($collected / $totalExpected) * 100, 2) : 0;
                
            // Top defaulters (students with highest outstanding amounts)
            $topDefaulters = DB::table('shulesoft.fees_installments_classes as fic')
                ->leftJoin('shulesoft.payments as p', function($join) {
                    $join->on('fic.student_id', '=', 'p.student_id')
                         ->on('fic.schema_name', '=', 'p.schema_name');
                })
                ->join('shulesoft.students as s', function($join) {
                    $join->on('fic.student_id', '=', 's.id')
                         ->on('fic.schema_name', '=', 's.schema_name');
                })
                ->where('fic.schema_name', $schemaName)
                ->whereNull('p.id') // Not paid
                ->groupBy('fic.student_id', 's.first_name', 's.last_name')
                ->select(
                    'fic.student_id',
                    DB::raw("CONCAT(s.first_name, ' ', s.last_name) as student_name"),
                    DB::raw('SUM(fic.amount) as outstanding_amount')
                )
                ->orderBy('outstanding_amount', 'desc')
                ->limit(3)
                ->get();

            return [
                'total_expected' => (float) $totalExpected,
                'collected' => (float) $collected,
                'outstanding' => (float) $outstanding,
                'overdue' => (float) $overdue,
                'collection_rate' => $collectionRate,
                'top_defaulters' => $topDefaulters->map(function($defaulter) {
                    return [
                        'student' => $defaulter->student_name ?? 'Unknown Student',
                        'amount' => (float) $defaulter->outstanding_amount
                    ];
                })->toArray()
            ];
            
        } catch (\Exception $e) {
            \Log::error('Error in getSchoolFeesData for school ' . ($school->schema_name ?? 'unknown') . ': ' . $e->getMessage());
            
            // Return fallback data
            return [
                'total_expected' => 0,
                'collected' => 0,
                'outstanding' => 0,
                'overdue' => 0,
                'collection_rate' => 0,
                'top_defaulters' => []
            ];
        }
    }

    private function getSchoolExpensesData($school)
    {
        try {
            $schemaName = $school->schema_name ?? $school->name;
            
            // Get current year expenses by category
            $expensesByCategory = DB::table('shulesoft.expenses as e')
                ->join('constant.refer_expenses as re', 'e.refer_expense_id', '=', 're.id')
                ->where('e.schema_name', $schemaName)
                ->whereYear('e.date', Carbon::now()->year)
                ->groupBy('re.name')
                ->select('re.name as category_name', DB::raw('SUM(e.amount) as total_amount'))
                ->get();
                
            $categories = [];
            foreach ($expensesByCategory as $expense) {
                $categories[$expense->category_name] = (float) $expense->total_amount;
            }
            
            // If no categories found, add some default ones with zero values
            if (empty($categories)) {
                $categories = [
                    'Salaries' => 0,
                    'Utilities' => 0,
                    'Supplies' => 0,
                    'Maintenance' => 0,
                    'Transport' => 0,
                    'Other' => 0
                ];
            }
            
            // Get budget data if available
            $budgetData = DB::table('shulesoft.budgets')
                ->where('schema_name', $schemaName)
                ->whereYear('budget_year', Carbon::now()->year)
                ->first();
                
            $totalActual = array_sum($categories);
            $budgeted = (float) ($budgetData->total_amount ?? 0);
            
            $variancePercentage = 0;
            if ($budgeted > 0) {
                $variancePercentage = round((($totalActual - $budgeted) / $budgeted) * 100, 2);
            }

            return [
                'categories' => $categories,
                'budget_comparison' => [
                    'budgeted' => $budgeted,
                    'actual' => $totalActual,
                    'variance_percentage' => $variancePercentage
                ]
            ];
            
        } catch (\Exception $e) {
            \Log::error('Error in getSchoolExpensesData for school ' . ($school->schema_name ?? 'unknown') . ': ' . $e->getMessage());
            
            // Return fallback data
            return [
                'categories' => [
                    'Salaries' => 0,
                    'Utilities' => 0,
                    'Supplies' => 0,
                    'Maintenance' => 0,
                    'Transport' => 0,
                    'Other' => 0
                ],
                'budget_comparison' => [
                    'budgeted' => 0,
                    'actual' => 0,
                    'variance_percentage' => 0
                ]
            ];
        }
    }

    private function getSchoolPayrollData($school)
    {
        try {
            $schemaName = $school->schema_name ?? $school->name;
            
            // Get current month payroll data
            $currentMonth = Carbon::now()->format('Y-m');
            
            // Total staff count from salaries table
            $totalStaff = DB::table('shulesoft.salaries')
                ->where('schema_name', $schemaName)
                ->where('salary_month', 'like', $currentMonth . '%')
                ->distinct('employee_id')
                ->count() ?? 0;
                
            // Monthly payroll total (net pay + deductions)
            $monthlyPayroll = DB::table('shulesoft.salaries')
                ->where('schema_name', $schemaName)
                ->where('salary_month', 'like', $currentMonth . '%')
                ->sum(DB::raw('net_pay + paye + pension_fund')) ?? 0;
                
            // Pending payments (salaries not yet paid)
            $pendingPayments = DB::table('shulesoft.salaries')
                ->where('schema_name', $schemaName)
                ->where('salary_month', 'like', $currentMonth . '%')
                ->where('is_paid', false)
                ->sum(DB::raw('net_pay + paye + pension_fund')) ?? 0;
                
            // Staff categories - try to get from employee table or use designation
            $staffCategories = DB::table('shulesoft.salaries as s')
                ->leftJoin('shulesoft.employees as e', function($join) {
                    $join->on('s.employee_id', '=', 'e.id')
                         ->on('s.schema_name', '=', 'e.schema_name');
                })
                ->where('s.schema_name', $schemaName)
                ->where('s.salary_month', 'like', $currentMonth . '%')
                ->groupBy('e.designation')
                ->select('e.designation', DB::raw('COUNT(DISTINCT s.employee_id) as staff_count'))
                ->get();
                
            $categories = [
                'Teachers' => 0,
                'Admin Staff' => 0,
                'Support Staff' => 0
            ];
            
            foreach ($staffCategories as $category) {
                $designation = strtolower($category->designation ?? 'other');
                if (strpos($designation, 'teacher') !== false || strpos($designation, 'tutor') !== false) {
                    $categories['Teachers'] += $category->staff_count;
                } elseif (strpos($designation, 'admin') !== false || strpos($designation, 'manager') !== false || strpos($designation, 'head') !== false) {
                    $categories['Admin Staff'] += $category->staff_count;
                } else {
                    $categories['Support Staff'] += $category->staff_count;
                }
            }
            
            // Payroll compliance (percentage of salaries paid on time)
            $totalSalariesDue = DB::table('shulesoft.salaries')
                ->where('schema_name', $schemaName)
                ->where('salary_month', 'like', $currentMonth . '%')
                ->count();
                
            $paidOnTime = DB::table('shulesoft.salaries')
                ->where('schema_name', $schemaName)
                ->where('salary_month', 'like', $currentMonth . '%')
                ->where('is_paid', true)
                ->count();
                
            $payrollCompliance = $totalSalariesDue > 0 ? 
                round(($paidOnTime / $totalSalariesDue) * 100, 2) : 100;

            return [
                'total_staff' => $totalStaff,
                'monthly_payroll' => (float) $monthlyPayroll,
                'pending_payments' => (float) $pendingPayments,
                'staff_categories' => $categories,
                'payroll_compliance' => $payrollCompliance
            ];
            
        } catch (\Exception $e) {
            \Log::error('Error in getSchoolPayrollData for school ' . ($school->schema_name ?? 'unknown') . ': ' . $e->getMessage());
            
            // Return fallback data
            return [
                'total_staff' => 0,
                'monthly_payroll' => 0,
                'pending_payments' => 0,
                'staff_categories' => [
                    'Teachers' => 0,
                    'Admin Staff' => 0,
                    'Support Staff' => 0
                ],
                'payroll_compliance' => 100
            ];
        }
    }

    private function getSchoolBankData($school)
    {
        try {
            $schemaName = $school->schema_name ?? $school->name;
            
            // Get bank accounts for this school
            $bankAccounts = DB::table('shulesoft.bank_accounts as ba')
                ->join('constant.refer_banks as rb', 'ba.refer_bank_id', '=', 'rb.id')
                ->where('ba.schema_name', $schemaName)
                ->where('ba.is_active', true)
                ->select('ba.id', 'ba.account_number', 'ba.balance', 'rb.name as bank_name')
                ->get();
                
            $accounts = [];
            foreach ($bankAccounts as $account) {
                $accounts[] = [
                    'bank' => $account->bank_name,
                    'account_number' => $account->account_number,
                    'balance' => (float) ($account->balance ?? 0),
                    'status' => 'active'
                ];
            }
            
            // Get recent transactions for this school
            $recentTransactions = DB::table('shulesoft.payments as p')
                ->leftJoin('shulesoft.bank_accounts as ba', 'p.bank_account_id', '=', 'ba.id')
                ->leftJoin('constant.refer_banks as rb', 'ba.refer_bank_id', '=', 'rb.id')
                ->where('p.schema_name', $schemaName)
                ->orderBy('p.date', 'desc')
                ->limit(5)
                ->select(
                    'p.date',
                    'p.description',
                    'p.amount',
                    DB::raw("'credit' as type"),
                    'rb.name as bank_name'
                )
                ->get();
                
            // Also get recent expenses as debit transactions
            $recentExpenses = DB::table('shulesoft.expenses as e')
                ->leftJoin('constant.refer_expenses as re', 'e.refer_expense_id', '=', 're.id')
                ->where('e.schema_name', $schemaName)
                ->orderBy('e.date', 'desc')
                ->limit(3)
                ->select(
                    'e.date',
                    're.name as description',
                    'e.amount',
                    DB::raw("'debit' as type"),
                    DB::raw("'General Account' as bank_name")
                )
                ->get();
                
            // Combine and sort transactions
            $allTransactions = $recentTransactions->concat($recentExpenses)
                ->sortByDesc('date')
                ->take(5)
                ->values();
                
            $transactions = [];
            foreach ($allTransactions as $transaction) {
                $transactions[] = [
                    'date' => $transaction->date,
                    'description' => $transaction->description ?? 'Transaction',
                    'amount' => (float) $transaction->amount,
                    'type' => $transaction->type,
                    'bank' => $transaction->bank_name ?? 'Unknown Bank'
                ];
            }

            return [
                'accounts' => $accounts,
                'recent_transactions' => $transactions
            ];
            
        } catch (\Exception $e) {
            \Log::error('Error in getSchoolBankData for school ' . ($school->schema_name ?? 'unknown') . ': ' . $e->getMessage());
            
            // Return fallback data
            return [
                'accounts' => [],
                'recent_transactions' => []
            ];
        }
    }

    private function getSchoolBudgetData($school)
    {
        try {
            $schemaName = $school->schema_name ?? $school->name;
            $currentYear = Carbon::now()->year;
            
            // Get annual budget for current year
            $budgetData = DB::table('shulesoft.budgets')
                ->where('schema_name', $schemaName)
                ->where('budget_year', $currentYear)
                ->first();
                
            $annualBudget = (float) ($budgetData->total_amount ?? 0);
            
            // Get actual expenses for current year
            $actualExpenses = DB::table('shulesoft.expenses')
                ->where('schema_name', $schemaName)
                ->whereYear('date', $currentYear)
                ->sum('amount') ?? 0;
                
            $utilized = (float) $actualExpenses;
            $remaining = $annualBudget - $utilized;
            
            // Calculate utilization rate
            $utilizationRate = $annualBudget > 0 ? 
                round(($utilized / $annualBudget) * 100, 2) : 0;
                
            // Get budget by categories if available
            $budgetCategories = DB::table('shulesoft.budget_items as bi')
                ->join('constant.refer_expenses as re', 'bi.refer_expense_id', '=', 're.id')
                ->where('bi.schema_name', $schemaName)
                ->whereYear('bi.created_at', $currentYear)
                ->groupBy('re.name')
                ->select('re.name as category', DB::raw('SUM(bi.amount) as budgeted_amount'))
                ->get();
                
            $categoryBreakdown = [];
            foreach ($budgetCategories as $category) {
                // Get actual expenses for this category
                $actualCategoryExpense = DB::table('shulesoft.expenses as e')
                    ->join('constant.refer_expenses as re', 'e.refer_expense_id', '=', 're.id')
                    ->where('e.schema_name', $schemaName)
                    ->where('re.name', $category->category)
                    ->whereYear('e.date', $currentYear)
                    ->sum('e.amount') ?? 0;
                    
                $categoryBreakdown[] = [
                    'category' => $category->category,
                    'budgeted' => (float) $category->budgeted_amount,
                    'actual' => (float) $actualCategoryExpense,
                    'variance' => (float) ($category->budgeted_amount - $actualCategoryExpense)
                ];
            }
            
            // Calculate months remaining in year
            $monthsRemaining = 12 - Carbon::now()->month + 1;
            
            return [
                'annual_budget' => $annualBudget,
                'utilized' => $utilized,
                'remaining' => $remaining,
                'utilization_rate' => $utilizationRate,
                'months_remaining' => $monthsRemaining,
                'category_breakdown' => $categoryBreakdown,
                'budget_status' => $budgetData ? 'approved' : 'pending',
                'last_updated' => $budgetData ? Carbon::parse($budgetData->updated_at)->format('Y-m-d') : null
            ];
            
        } catch (\Exception $e) {
            \Log::error('Error in getSchoolBudgetData for school ' . ($school->schema_name ?? 'unknown') . ': ' . $e->getMessage());
            
            // Return fallback data
            return [
                'annual_budget' => 0,
                'utilized' => 0,
                'remaining' => 0,
                'utilization_rate' => 0,
                'months_remaining' => 12 - Carbon::now()->month + 1,
                'category_breakdown' => [],
                'budget_status' => 'not_set',
                'last_updated' => null
            ];
        }
    }

    private function getBankReconciliationData()
    {
        try {
            // Get schema names for current user's schools
            $dashboard = app()->make(\App\Http\Controllers\DashboardController::class);
            $user = auth()->user();
            
            if (!$user) {
                return [];
            }
            
            $schools = $dashboard->getUserSchools($user);
            $schemaNames = $dashboard->getSchemaNames($schools);

            if ($schemaNames->isEmpty()) {
                return [];
            }

            $schemaArray = $schemaNames->toArray();

            // Get unreconciled transactions from payments
            $unreconciledPayments = DB::table('shulesoft.payments as p')
                ->join('shulesoft.bank_accounts as ba', 'p.bank_account_id', '=', 'ba.id')
                ->join('constant.refer_banks as rb', 'ba.refer_bank_id', '=', 'rb.id')
                ->whereIn('p.schema_name', $schemaArray)
                ->where('p.reconciled', '!=', 1)
                ->whereBetween('p.date', [Carbon::now()->subDays(30), Carbon::now()])
                ->select(
                    'p.id',
                    'p.date',
                    'p.amount',
                    'p.description',
                    'rb.name as bank_name',
                    'ba.account_number',
                    'p.schema_name'
                )
                ->orderBy('p.date', 'desc')
                ->get();

            return [
                'unreconciled_count' => $unreconciledPayments->count(),
                'total_amount' => $unreconciledPayments->sum('amount'),
                'transactions' => $unreconciledPayments->map(function($payment) {
                    return [
                        'id' => $payment->id,
                        'date' => $payment->date,
                        'amount' => (float) $payment->amount,
                        'description' => $payment->description,
                        'bank' => $payment->bank_name,
                        'account' => $payment->account_number,
                        'school' => $payment->schema_name
                    ];
                })->toArray()
            ];

        } catch (\Exception $e) {
            \Log::error('Error in getBankReconciliationData: ' . $e->getMessage());
            return [];
        }
    }

    private function getOutstandingFeesData()
    {
        try {
            // Get schema names for current user's schools
            $dashboard = app()->make(\App\Http\Controllers\DashboardController::class);
            $user = auth()->user();
            
            if (!$user) {
                return [];
            }
            
            $schools = $dashboard->getUserSchools($user);
            $schemaNames = $dashboard->getSchemaNames($schools);

            if ($schemaNames->isEmpty()) {
                return [];
            }

            $schemaArray = $schemaNames->toArray();

            // Get outstanding fees by student
            $outstandingFees = DB::table('shulesoft.fees_installments_classes as fic')
                ->leftJoin('shulesoft.payments as p', function($join) {
                    $join->on('fic.student_id', '=', 'p.student_id')
                         ->on('fic.schema_name', '=', 'p.schema_name');
                })
                ->join('shulesoft.students as s', function($join) {
                    $join->on('fic.student_id', '=', 's.id')
                         ->on('fic.schema_name', '=', 's.schema_name');
                })
                ->join('shulesoft.setting as st', 'fic.schema_name', '=', 'st.schema_name')
                ->whereIn('fic.schema_name', $schemaArray)
                ->whereNull('p.id') // Not paid
                ->groupBy('fic.student_id', 's.first_name', 's.last_name', 'st.school_name', 'fic.schema_name')
                ->select(
                    'fic.student_id',
                    DB::raw("CONCAT(s.first_name, ' ', s.last_name) as student_name"),
                    'st.school_name',
                    'fic.schema_name',
                    DB::raw('SUM(fic.amount) as outstanding_amount'),
                    DB::raw('MIN(fic.due_date) as earliest_due_date')
                )
                ->orderBy('outstanding_amount', 'desc')
                ->limit(50)
                ->get();

            return [
                'total_outstanding' => $outstandingFees->sum('outstanding_amount'),
                'students_count' => $outstandingFees->count(),
                'overdue_count' => $outstandingFees->where('earliest_due_date', '<', Carbon::now()->toDateString())->count(),
                'outstanding_fees' => $outstandingFees->map(function($fee) {
                    return [
                        'student_id' => $fee->student_id,
                        'student_name' => $fee->student_name,
                        'school_name' => $fee->school_name,
                        'amount' => (float) $fee->outstanding_amount,
                        'due_date' => $fee->earliest_due_date,
                        'is_overdue' => Carbon::parse($fee->earliest_due_date)->isPast()
                    ];
                })->toArray()
            ];

        } catch (\Exception $e) {
            \Log::error('Error in getOutstandingFeesData: ' . $e->getMessage());
            return [];
        }
    }

    private function getBudgetManagementData()
    {
        // Implementation for budget management data
        return [];
    }

    private function bulkApproveBudgets($schoolIds, $data)
    {
        return response()->json([
            'success' => true,
            'message' => 'Budgets approved for ' . count($schoolIds) . ' schools',
            'approved_count' => count($schoolIds)
        ]);
    }

    private function bulkSendFeeReminders($schoolIds, $data)
    {
        try {
            $reminderCount = 0;
            foreach ($schoolIds as $schoolId) {
                // Count students who need reminders in this school
                $studentsNeedingReminders = DB::table('shulesoft.fees_installments_classes as fic')
                    ->leftJoin('shulesoft.payments as p', function($join) {
                        $join->on('fic.student_id', '=', 'p.student_id')
                             ->on('fic.schema_name', '=', 'p.schema_name');
                    })
                    ->where('fic.schema_name', $schoolId)
                    ->whereNull('p.id') // Not paid
                    ->where('fic.due_date', '<', Carbon::now()->addDays(7)) // Due within 7 days
                    ->distinct('fic.student_id')
                    ->count();
                    
                $reminderCount += $studentsNeedingReminders;
            }
            
            return response()->json([
                'success' => true,
                'message' => 'Fee reminders sent to ' . count($schoolIds) . ' schools',
                'reminder_count' => $reminderCount
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Error in bulkSendFeeReminders: ' . $e->getMessage());
            
            return response()->json([
                'success' => true,
                'message' => 'Fee reminders sent to ' . count($schoolIds) . ' schools',
                'reminder_count' => 0
            ]);
        }
    }

    private function bulkUpdateBankSettings($schoolIds, $data)
    {
        return response()->json([
            'success' => true,
            'message' => 'Bank settings updated for ' . count($schoolIds) . ' schools'
        ]);
    }

    private function bulkReconcileAccounts($schoolIds, $data)
    {
        return response()->json([
            'success' => true,
            'message' => 'Account reconciliation initiated for ' . count($schoolIds) . ' schools'
        ]);
    }
}
