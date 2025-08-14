@extends('layouts.admin')

@section('title', 'School Financial Detail - ' . $school->settings['school_name'] ?? 'Unknown School')

@section('content')
<div class="container-fluid px-4">
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('finance.index') }}">Finance</a></li>
                    <li class="breadcrumb-item active">{{ $school->settings['school_name'] ?? 'Unknown School' }}</li>
                </ol>
            </nav>
            <h1 class="h3 mb-0 text-gray-800">{{ $school->settings['school_name'] ?? 'Unknown School' }}</h1>
            <p class="text-muted mb-0">Detailed financial analysis and management</p>
        </div>
        <div class="d-flex gap-2">
            <button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#financialActionsModal">
                <i class="bi bi-lightning me-1"></i> Quick Actions
            </button>
            <button class="btn btn-outline-success" onclick="exportSchoolFinancialReport()">
                <i class="bi bi-download me-1"></i> Export Report
            </button>
            <a href="{{ route('finance.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left me-1"></i> Back to Dashboard
            </a>
        </div>
    </div>

    <!-- School Financial Summary Card -->
    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="row">
                <div class="col-md-8">
                    <div class="row">
                        <div class="col-sm-6 mb-3">
                            <label class="text-muted small">School Code</label>
                            <div class="font-weight-bold">{{ $school->shulesoft_code }}</div>
                        </div>
                        <div class="col-sm-6 mb-3">
                            <label class="text-muted small">Region</label>
                            <div class="font-weight-bold">{{ $school->settings['region'] ?? 'Unknown' }}</div>
                        </div>
                        <div class="col-sm-6 mb-3">
                            <label class="text-muted small">Monthly Revenue</label>
                            <div class="font-weight-bold text-success">TZS {{ number_format($schoolFinancialData['summary']['monthly_revenue']) }}</div>
                        </div>
                        <div class="col-sm-6 mb-3">
                            <label class="text-muted small">Monthly Expenses</label>
                            <div class="font-weight-bold text-warning">TZS {{ number_format($schoolFinancialData['summary']['monthly_expenses']) }}</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="text-center">
                        <div class="h5 mb-1">Financial Health Score</div>
                        <div class="h2 text-primary">{{ $schoolFinancialData['summary']['profit_margin'] }}%</div>
                        <div class="progress mx-auto" style="width: 80%;">
                            <div class="progress-bar bg-primary" style="width: {{ $schoolFinancialData['summary']['profit_margin'] }}%"></div>
                        </div>
                        <small class="text-muted">Profit Margin</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Financial KPIs Row -->
    <div class="row g-3 mb-4">
        <!-- Revenue & Expenses -->
        <div class="col-lg-6">
            <div class="card shadow h-100">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="bi bi-graph-up me-2"></i>Revenue & Expenses
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-6">
                            <div class="border-end pe-3">
                                <h6 class="text-muted">Monthly Revenue</h6>
                                <div class="h4 mb-2 text-success">TZS {{ number_format($schoolFinancialData['summary']['monthly_revenue'] / 1000000, 1) }}M</div>
                                <div class="d-flex align-items-center">
                                    @if($schoolFinancialData['trends']['revenue_growth'] > 0)
                                        <span class="text-success me-2">
                                            <i class="bi bi-arrow-up"></i> +{{ $schoolFinancialData['trends']['revenue_growth'] }}%
                                        </span>
                                    @else
                                        <span class="text-danger me-2">
                                            <i class="bi bi-arrow-down"></i> {{ $schoolFinancialData['trends']['revenue_growth'] }}%
                                        </span>
                                    @endif
                                    <span class="text-sm text-muted">vs last month</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="ps-3">
                                <h6 class="text-muted">Monthly Expenses</h6>
                                <div class="h4 mb-2 text-warning">TZS {{ number_format($schoolFinancialData['summary']['monthly_expenses'] / 1000000, 1) }}M</div>
                                <div class="d-flex align-items-center">
                                    @if($schoolFinancialData['trends']['expense_growth'] > 0)
                                        <span class="text-danger me-2">
                                            <i class="bi bi-arrow-up"></i> +{{ $schoolFinancialData['trends']['expense_growth'] }}%
                                        </span>
                                    @else
                                        <span class="text-success me-2">
                                            <i class="bi bi-arrow-down"></i> {{ abs($schoolFinancialData['trends']['expense_growth']) }}%
                                        </span>
                                    @endif
                                    <span class="text-sm text-muted">vs last month</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="text-center">
                        <div class="h5 mb-1">Net Profit</div>
                        @php $profit = $schoolFinancialData['summary']['monthly_revenue'] - $schoolFinancialData['summary']['monthly_expenses']; @endphp
                        <div class="h3 {{ $profit > 0 ? 'text-success' : 'text-danger' }}">
                            TZS {{ number_format($profit / 1000000, 1) }}M
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Fees & Collections -->
        <div class="col-lg-6">
            <div class="card shadow h-100">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="bi bi-currency-dollar me-2"></i>Fees & Collections
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row text-center mb-3">
                        <div class="col">
                            <div class="text-sm text-muted text-uppercase">Expected</div>
                            <div class="h5 mb-0 text-info">TZS {{ number_format($feesData['total_expected'] / 1000000, 1) }}M</div>
                        </div>
                        <div class="col">
                            <div class="text-sm text-muted text-uppercase">Collected</div>
                            <div class="h5 mb-0 text-success">TZS {{ number_format($feesData['collected'] / 1000000, 1) }}M</div>
                        </div>
                        <div class="col">
                            <div class="text-sm text-muted text-uppercase">Outstanding</div>
                            <div class="h5 mb-0 text-danger">TZS {{ number_format($feesData['outstanding'] / 1000000, 1) }}M</div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-1">
                            <span class="text-sm font-weight-bold">Collection Rate</span>
                            <span class="font-weight-bold">{{ $feesData['collection_rate'] }}%</span>
                        </div>
                        <div class="progress">
                            <div class="progress-bar 
                                @if($feesData['collection_rate'] >= 90) bg-success
                                @elseif($feesData['collection_rate'] >= 75) bg-warning
                                @else bg-danger
                                @endif" 
                                 style="width: {{ $feesData['collection_rate'] }}%"></div>
                        </div>
                    </div>
                    
                    <div class="text-sm">
                        <div class="d-flex justify-content-between">
                            <span>Overdue Amount:</span>
                            <span class="text-danger font-weight-bold">TZS {{ number_format($feesData['overdue']) }}</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span>Top Defaulters:</span>
                            <span class="text-warning">{{ count($feesData['top_defaulters']) }} students</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bank Accounts & Payroll -->
    <div class="row mb-4">
        <!-- Bank Accounts -->
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="bi bi-bank me-2"></i>Bank Accounts
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        @foreach($bankData['accounts'] as $account)
                        <div class="col-md-6 mb-3">
                            <div class="border rounded p-3">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <h6 class="mb-0 font-weight-bold">{{ $account['bank'] }}</h6>
                                    <span class="badge bg-{{ $account['status'] === 'active' ? 'success' : 'warning' }}">
                                        {{ ucfirst($account['status']) }}
                                    </span>
                                </div>
                                <div class="text-sm text-muted mb-1">Account: {{ $account['account_number'] }}</div>
                                <div class="h5 mb-0 text-primary">TZS {{ number_format($account['balance']) }}</div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    
                    <h6 class="font-weight-bold mt-4 mb-3">Recent Transactions</h6>
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Description</th>
                                    <th>Amount</th>
                                    <th>Type</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($bankData['recent_transactions'] as $transaction)
                                <tr>
                                    <td>{{ $transaction['date'] }}</td>
                                    <td>{{ $transaction['description'] }}</td>
                                    <td class="{{ $transaction['type'] === 'credit' ? 'text-success' : 'text-danger' }}">
                                        {{ $transaction['type'] === 'credit' ? '+' : '-' }}TZS {{ number_format($transaction['amount']) }}
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $transaction['type'] === 'credit' ? 'success' : 'danger' }}">
                                            {{ ucfirst($transaction['type']) }}
                                        </span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Payroll Summary -->
        <div class="col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="bi bi-people me-2"></i>Payroll Summary
                    </h6>
                </div>
                <div class="card-body">
                    <div class="text-center mb-3">
                        <div class="h5 mb-1">Monthly Payroll</div>
                        <div class="h3 text-primary">TZS {{ number_format($payrollData['monthly_payroll'] / 1000000, 1) }}M</div>
                        @if($payrollData['pending_payments'] > 0)
                        <div class="text-sm text-warning">
                            TZS {{ number_format($payrollData['pending_payments']) }} pending
                        </div>
                        @endif
                    </div>
                    
                    <div class="mb-3">
                        <h6 class="text-muted">Staff Distribution</h6>
                        @foreach($payrollData['staff_categories'] as $category => $count)
                        <div class="d-flex justify-content-between">
                            <span class="text-sm">{{ $category }}:</span>
                            <span class="font-weight-bold">{{ $count }}</span>
                        </div>
                        @endforeach
                    </div>
                    
                    <div class="text-center">
                        <div class="text-sm text-muted">Total Staff</div>
                        <div class="h4 text-info">{{ $payrollData['total_staff'] }}</div>
                        <div class="text-sm text-muted">
                            Compliance: {{ $payrollData['payroll_compliance'] }}%
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Expenses Breakdown & Budget Analysis -->
    <div class="row mb-4">
        <!-- Expenses Breakdown -->
        <div class="col-lg-6">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="bi bi-pie-chart me-2"></i>Expenses Breakdown
                    </h6>
                </div>
                <div class="card-body">
                    <canvas id="expensesChart" width="100%" height="60"></canvas>
                    <div class="mt-3">
                        @foreach($expensesData['categories'] as $category => $amount)
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-sm">{{ $category }}:</span>
                            <span class="font-weight-bold">TZS {{ number_format($amount / 1000, 0) }}K</span>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- Budget Analysis -->
        <div class="col-lg-6">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="bi bi-bar-chart me-2"></i>Budget Analysis
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row text-center mb-4">
                        <div class="col">
                            <div class="text-sm text-muted text-uppercase">Annual Budget</div>
                            <div class="h5 mb-0 text-info">TZS {{ number_format($budgetData['annual_budget'] / 1000000, 1) }}M</div>
                        </div>
                        <div class="col">
                            <div class="text-sm text-muted text-uppercase">Utilized</div>
                            <div class="h5 mb-0 text-warning">TZS {{ number_format($budgetData['utilized'] / 1000000, 1) }}M</div>
                        </div>
                        <div class="col">
                            <div class="text-sm text-muted text-uppercase">Remaining</div>
                            <div class="h5 mb-0 text-success">TZS {{ number_format($budgetData['remaining'] / 1000000, 1) }}M</div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-1">
                            <span class="text-sm font-weight-bold">Utilization Rate</span>
                            <span class="font-weight-bold">{{ $budgetData['utilization_rate'] }}%</span>
                        </div>
                        <div class="progress">
                            <div class="progress-bar 
                                @if($budgetData['utilization_rate'] <= 80) bg-success
                                @elseif($budgetData['utilization_rate'] <= 95) bg-warning
                                @else bg-danger
                                @endif" 
                                 style="width: {{ $budgetData['utilization_rate'] }}%"></div>
                        </div>
                    </div>
                    
                    <h6 class="font-weight-bold mb-2">Budget vs Actual</h6>
                    @foreach($budgetData['budget_categories'] as $category => $data)
                    <div class="mb-2">
                        <div class="d-flex justify-content-between text-sm">
                            <span>{{ $category }}</span>
                            <span>{{ number_format(($data['actual'] / $data['budgeted']) * 100, 1) }}%</span>
                        </div>
                        <div class="progress" style="height: 5px;">
                            <div class="progress-bar bg-primary" 
                                 style="width: {{ min(($data['actual'] / $data['budgeted']) * 100, 100) }}%"></div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- Top Defaulters -->
    <div class="card shadow">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="bi bi-exclamation-triangle me-2"></i>Top Fee Defaulters
            </h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Student</th>
                            <th>Outstanding Amount</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($feesData['top_defaulters'] as $defaulter)
                        <tr>
                            <td>
                                <div class="font-weight-bold">{{ $defaulter['student'] }}</div>
                            </td>
                            <td>
                                <span class="text-danger font-weight-bold">
                                    TZS {{ number_format($defaulter['amount']) }}
                                </span>
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <button class="btn btn-sm btn-outline-warning">Send Reminder</button>
                                    <button class="btn btn-sm btn-outline-primary">View Details</button>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Financial Actions Modal -->
<div class="modal fade" id="financialActionsModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Financial Actions - {{ $school->settings['school_name'] ?? 'School' }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <h6>Fee Management</h6>
                        <div class="list-group">
                            <button type="button" class="list-group-item list-group-item-action">
                                <i class="bi bi-envelope me-2"></i> Send Fee Reminders
                            </button>
                            <button type="button" class="list-group-item list-group-item-action">
                                <i class="bi bi-file-text me-2"></i> Generate Fee Reports
                            </button>
                            <button type="button" class="list-group-item list-group-item-action">
                                <i class="bi bi-credit-card me-2"></i> Process Fee Payments
                            </button>
                            <button type="button" class="list-group-item list-group-item-action">
                                <i class="bi bi-percent me-2"></i> Apply Fee Discounts
                            </button>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <h6>Financial Management</h6>
                        <div class="list-group">
                            <button type="button" class="list-group-item list-group-item-action">
                                <i class="bi bi-bank me-2"></i> Bank Reconciliation
                            </button>
                            <button type="button" class="list-group-item list-group-item-action">
                                <i class="bi bi-clipboard-check me-2"></i> Approve Budget
                            </button>
                            <button type="button" class="list-group-item list-group-item-action">
                                <i class="bi bi-cash-stack me-2"></i> Process Payroll
                            </button>
                            <button type="button" class="list-group-item list-group-item-action">
                                <i class="bi bi-graph-up me-2"></i> Financial Analysis
                            </button>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="mb-3">
                    <label class="form-label">Action Notes</label>
                    <textarea class="form-control" rows="3" placeholder="Enter any notes or instructions for this action..."></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Execute Selected Actions</button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
// Expenses Chart
const expensesData = @json($expensesData);
const expensesCtx = document.getElementById('expensesChart').getContext('2d');
const expensesChart = new Chart(expensesCtx, {
    type: 'doughnut',
    data: {
        labels: Object.keys(expensesData.categories),
        datasets: [{
            data: Object.values(expensesData.categories),
            backgroundColor: [
                '#4e73df',
                '#1cc88a',
                '#36b9cc',
                '#f6c23e',
                '#e74a3b',
                '#858796'
            ],
            borderWidth: 2
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'bottom',
            }
        }
    }
});

function exportSchoolFinancialReport() {
    alert('Exporting detailed financial report for this school...');
}
</script>
@endpush

@push('styles')
<style>
.border-end {
    border-right: 1px solid #e3e6f0 !important;
}

.text-gray-800 {
    color: #5a5c69 !important;
}

.shadow {
    box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15) !important;
}

.card-header {
    background-color: #f8f9fc;
    border-bottom: 1px solid #e3e6f0;
}

.progress {
    height: 8px;
    background-color: #f1f1f1;
}

.text-sm {
    font-size: 0.875rem;
}

.font-weight-bold {
    font-weight: 700 !important;
}

.list-group-item-action:hover {
    background-color: #f8f9fc;
}

.badge {
    font-size: 0.75rem;
}

#expensesChart {
    height: 250px !important;
}

@media (max-width: 768px) {
    .col-lg-6, .col-lg-8, .col-lg-4 {
        margin-bottom: 1rem;
    }
    
    .border-end {
        border-right: none !important;
        border-bottom: 1px solid #e3e6f0 !important;
        padding-bottom: 1rem !important;
        margin-bottom: 1rem !important;
    }
    
    .ps-3 {
        padding-left: 0 !important;
    }
}
</style>
@endpush
