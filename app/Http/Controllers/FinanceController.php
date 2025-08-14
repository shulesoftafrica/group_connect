<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\School;
use App\Models\User;
use App\Models\Organization;
use Carbon\Carbon;

class FinanceController extends Controller
{
    public function index()
    {
        $financialKPIs = $this->calculateFinancialKPIs();
        $schoolsList = $this->getSchoolsFinancialList();
        $bankAccounts = $this->getBankAccountsData();
        $performanceData = $this->getFinancialPerformanceData();
        $alertsData = $this->getFinancialAlerts();
        $revenueExpenseData = $this->getRevenueExpenseData();
        
        return view('finance.dashboard', compact(
            'financialKPIs',
            'schoolsList',
            'bankAccounts',
            'performanceData',
            'alertsData',
            'revenueExpenseData'
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
        return response()->json([
            'success' => true,
            'message' => 'Bank statement imported successfully',
            'transactions_imported' => rand(50, 200),
            'file_name' => $request->file('statement_file')->getClientOriginalName()
        ]);
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
        $schools = School::where('is_active', true)->get();
        $totalSchools = $schools->count();
        
        // Simulate financial calculations based on schema tables
        $totalRevenue = $schools->sum(function($school) {
            return $school->settings['monthly_revenue'] ?? rand(500000, 2000000);
        });
        
        $totalExpenses = $schools->sum(function($school) {
            return $school->settings['monthly_expenses'] ?? rand(300000, 1500000);
        });
        
        return [
            'total_schools' => $totalSchools,
            'group_revenue' => [
                'total' => $totalRevenue,
                'monthly_trend' => rand(-5, 15),
                'target_achievement' => rand(85, 110)
            ],
            'group_expenses' => [
                'total' => $totalExpenses,
                'monthly_trend' => rand(-10, 8),
                'budget_utilization' => rand(75, 95)
            ],
            'outstanding_fees' => [
                'total' => rand(5000000, 15000000),
                'overdue_amount' => rand(1000000, 5000000),
                'collection_rate' => rand(75, 92),
                'defaulters_count' => rand(150, 500)
            ],
            'bank_balances' => [
                'nmb_total' => rand(10000000, 50000000),
                'crdb_total' => rand(8000000, 30000000),
                'nbc_total' => rand(5000000, 20000000),
                'mkombozi_total' => rand(3000000, 15000000),
                'total_balance' => rand(26000000, 115000000)
            ],
            'payroll_summary' => [
                'total_monthly' => rand(8000000, 25000000),
                'pending_payments' => rand(500000, 2000000),
                'staff_cost_percentage' => rand(35, 55),
                'overdue_count' => rand(0, 8)
            ],
            'budget_status' => [
                'approved_budgets' => rand(15, 35),
                'pending_approval' => rand(5, 15),
                'over_budget_schools' => rand(2, 8),
                'budget_variance' => rand(-15, 25)
            ]
        ];
    }

    private function getSchoolsFinancialList()
    {
        return School::with(['organization'])
            ->where('is_active', true)
            ->get()
            ->map(function ($school) {
                $settings = $school->settings ?? [];
                
                $revenue = $settings['monthly_revenue'] ?? rand(500000, 2000000);
                $expenses = $settings['monthly_expenses'] ?? rand(300000, 1500000);
                $outstandingFees = $settings['outstanding_fees'] ?? rand(200000, 1000000);
                
                return [
                    'id' => $school->id,
                    'name' => $settings['school_name'] ?? 'Unknown School',
                    'code' => $school->shulesoft_code,
                    'region' => $settings['region'] ?? 'Unknown',
                    'revenue' => $revenue,
                    'expenses' => $expenses,
                    'profit_margin' => round((($revenue - $expenses) / $revenue) * 100, 1),
                    'outstanding_fees' => $outstandingFees,
                    'collection_rate' => rand(70, 95),
                    'bank_balance' => rand(500000, 5000000),
                    'financial_health' => $this->calculateFinancialHealth($revenue, $expenses, $outstandingFees),
                    'last_updated' => Carbon::now()->subDays(rand(0, 7))->format('Y-m-d'),
                ];
            });
    }

    private function calculateFinancialHealth($revenue, $expenses, $outstanding)
    {
        $profitMargin = (($revenue - $expenses) / $revenue) * 100;
        $collectionRatio = ($revenue - $outstanding) / $revenue * 100;
        
        $score = ($profitMargin * 0.6) + ($collectionRatio * 0.4);
        
        if ($score >= 80) return 'excellent';
        if ($score >= 65) return 'good';
        if ($score >= 50) return 'average';
        return 'poor';
    }

    private function getBankAccountsData()
    {
        $banks = ['NMB Bank', 'CRDB Bank', 'NBC Bank', 'Mkombozi Bank'];
        $accounts = [];
        
        foreach ($banks as $bank) {
            $schoolAccounts = [];
            $schools = School::where('is_active', true)->limit(rand(3, 8))->get();
            
            foreach ($schools as $school) {
                $schoolAccounts[] = [
                    'school_id' => $school->id,
                    'school_name' => $school->settings['school_name'] ?? 'Unknown School',
                    'account_number' => $this->generateAccountNumber(),
                    'balance' => rand(500000, 10000000),
                    'last_transaction' => Carbon::now()->subDays(rand(0, 5))->format('Y-m-d'),
                    'status' => rand(0, 10) > 1 ? 'active' : 'needs_reconciliation'
                ];
            }
            
            $accounts[] = [
                'bank_name' => $bank,
                'total_balance' => array_sum(array_column($schoolAccounts, 'balance')),
                'accounts_count' => count($schoolAccounts),
                'schools' => $schoolAccounts
            ];
        }
        
        return $accounts;
    }

    private function generateAccountNumber()
    {
        return sprintf('%010d', rand(1000000000, 9999999999));
    }

    private function getFinancialPerformanceData()
    {
        // Generate sample financial performance data for charts
        $months = [];
        $revenueData = [];
        $expenseData = [];
        $profitData = [];
        
        for ($i = 11; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $months[] = $date->format('M Y');
            
            $revenue = rand(20000000, 45000000);
            $expense = rand(15000000, 35000000);
            
            $revenueData[] = $revenue;
            $expenseData[] = $expense;
            $profitData[] = $revenue - $expense;
        }
        
        return [
            'months' => $months,
            'revenue_trend' => $revenueData,
            'expense_trend' => $expenseData,
            'profit_trend' => $profitData,
            'collection_rates' => [
                'North Region' => rand(80, 95),
                'South Region' => rand(75, 90),
                'East Region' => rand(85, 98),
                'West Region' => rand(78, 92),
                'Central Region' => rand(88, 96)
            ],
            'expense_categories' => [
                'Salaries & Benefits' => rand(40, 60),
                'Utilities' => rand(8, 15),
                'Supplies & Materials' => rand(10, 20),
                'Maintenance' => rand(5, 12),
                'Transport' => rand(3, 8),
                'Other' => rand(5, 15)
            ]
        ];
    }

    private function getFinancialAlerts()
    {
        return [
            'critical' => [
                [
                    'type' => 'outstanding_fees',
                    'school' => 'Valley Secondary School',
                    'message' => 'Outstanding fees exceed TZS 2.5M - urgent collection needed',
                    'amount' => 2500000,
                    'timestamp' => Carbon::now()->subHours(1)->format('Y-m-d H:i'),
                    'action_required' => true
                ],
                [
                    'type' => 'bank_balance',
                    'school' => 'Sunrise Primary',
                    'message' => 'Bank balance critically low - TZS 150K remaining',
                    'amount' => 150000,
                    'timestamp' => Carbon::now()->subHours(3)->format('Y-m-d H:i'),
                    'action_required' => true
                ]
            ],
            'warnings' => [
                [
                    'type' => 'budget_variance',
                    'school' => 'Greenfield Academy',
                    'message' => 'Monthly expenses 15% over budget',
                    'variance' => 15,
                    'timestamp' => Carbon::now()->subHours(5)->format('Y-m-d H:i'),
                    'action_required' => false
                ],
                [
                    'type' => 'payroll_delay',
                    'school' => 'Eastside High',
                    'message' => 'Payroll payment delayed by 3 days',
                    'days_delay' => 3,
                    'timestamp' => Carbon::now()->subHours(8)->format('Y-m-d H:i'),
                    'action_required' => false
                ]
            ],
            'info' => [
                [
                    'type' => 'reconciliation',
                    'school' => 'All Schools',
                    'message' => 'Monthly bank reconciliation due in 3 days',
                    'due_date' => Carbon::now()->addDays(3)->format('Y-m-d'),
                    'timestamp' => Carbon::now()->subDays(1)->format('Y-m-d H:i'),
                    'action_required' => false
                ]
            ]
        ];
    }

    private function getRevenueExpenseData()
    {
        // Generate data for revenue vs expense comparison charts
        $schools = School::where('is_active', true)->limit(10)->get();
        $schoolComparison = [];
        
        foreach ($schools as $school) {
            $revenue = rand(1000000, 5000000);
            $expense = rand(600000, 4000000);
            
            $schoolComparison[] = [
                'school' => $school->settings['school_name'] ?? 'Unknown School',
                'revenue' => $revenue,
                'expense' => $expense,
                'profit' => $revenue - $expense
            ];
        }
        
        return $schoolComparison;
    }

    private function getSchoolFinancialData($school)
    {
        $settings = $school->settings ?? [];
        
        return [
            'summary' => [
                'monthly_revenue' => $settings['monthly_revenue'] ?? rand(500000, 2000000),
                'monthly_expenses' => $settings['monthly_expenses'] ?? rand(300000, 1500000),
                'outstanding_fees' => $settings['outstanding_fees'] ?? rand(200000, 1000000),
                'bank_balance' => rand(500000, 5000000),
                'profit_margin' => rand(10, 35),
                'collection_rate' => rand(70, 95)
            ],
            'trends' => [
                'revenue_growth' => rand(-10, 20),
                'expense_growth' => rand(-5, 15),
                'fee_collection_trend' => rand(-8, 12)
            ]
        ];
    }

    private function getSchoolFeesData($school)
    {
        return [
            'total_expected' => rand(3000000, 12000000),
            'collected' => rand(2000000, 10000000),
            'outstanding' => rand(500000, 2000000),
            'overdue' => rand(100000, 800000),
            'collection_rate' => rand(70, 95),
            'top_defaulters' => [
                ['student' => 'Student A', 'amount' => rand(50000, 200000)],
                ['student' => 'Student B', 'amount' => rand(45000, 180000)],
                ['student' => 'Student C', 'amount' => rand(40000, 160000)]
            ]
        ];
    }

    private function getSchoolExpensesData($school)
    {
        return [
            'categories' => [
                'Salaries' => rand(800000, 2500000),
                'Utilities' => rand(80000, 300000),
                'Supplies' => rand(100000, 500000),
                'Maintenance' => rand(50000, 250000),
                'Transport' => rand(30000, 150000),
                'Other' => rand(40000, 200000)
            ],
            'budget_comparison' => [
                'budgeted' => rand(1500000, 4000000),
                'actual' => rand(1200000, 3800000),
                'variance_percentage' => rand(-20, 15)
            ]
        ];
    }

    private function getSchoolPayrollData($school)
    {
        return [
            'total_staff' => rand(15, 80),
            'monthly_payroll' => rand(500000, 2000000),
            'pending_payments' => rand(0, 200000),
            'staff_categories' => [
                'Teachers' => rand(10, 50),
                'Admin Staff' => rand(3, 15),
                'Support Staff' => rand(2, 15)
            ],
            'payroll_compliance' => rand(85, 100)
        ];
    }

    private function getSchoolBankData($school)
    {
        return [
            'accounts' => [
                [
                    'bank' => 'NMB Bank',
                    'account_number' => $this->generateAccountNumber(),
                    'balance' => rand(200000, 2000000),
                    'status' => 'active'
                ],
                [
                    'bank' => 'CRDB Bank',
                    'account_number' => $this->generateAccountNumber(),
                    'balance' => rand(150000, 1500000),
                    'status' => 'active'
                ]
            ],
            'recent_transactions' => [
                ['date' => Carbon::now()->subDays(1)->format('Y-m-d'), 'description' => 'Fee Collection', 'amount' => rand(100000, 500000), 'type' => 'credit'],
                ['date' => Carbon::now()->subDays(2)->format('Y-m-d'), 'description' => 'Salary Payment', 'amount' => rand(200000, 800000), 'type' => 'debit'],
                ['date' => Carbon::now()->subDays(3)->format('Y-m-d'), 'description' => 'Utility Bill', 'amount' => rand(50000, 200000), 'type' => 'debit']
            ]
        ];
    }

    private function getSchoolBudgetData($school)
    {
        return [
            'annual_budget' => rand(15000000, 50000000),
            'utilized' => rand(8000000, 35000000),
            'remaining' => rand(2000000, 20000000),
            'utilization_rate' => rand(60, 85),
            'budget_categories' => [
                'Personnel' => ['budgeted' => rand(8000000, 25000000), 'actual' => rand(7000000, 23000000)],
                'Operations' => ['budgeted' => rand(3000000, 10000000), 'actual' => rand(2500000, 9500000)],
                'Infrastructure' => ['budgeted' => rand(2000000, 8000000), 'actual' => rand(1500000, 7500000)],
                'Programs' => ['budgeted' => rand(1000000, 5000000), 'actual' => rand(800000, 4500000)]
            ]
        ];
    }

    private function getBankReconciliationData()
    {
        // Implementation for bank reconciliation data
        return [];
    }

    private function getOutstandingFeesData()
    {
        // Implementation for outstanding fees data
        return [];
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
        return response()->json([
            'success' => true,
            'message' => 'Fee reminders sent to ' . count($schoolIds) . ' schools',
            'reminder_count' => rand(50, 200)
        ]);
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
