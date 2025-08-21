@extends('layouts.admin')

@section('title', 'Finance & Accounts Dashboard')

@section('content')
<div class="container-fluid px-4">
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">Finance & Accounts Dashboard</h1>
            <p class="text-muted mb-0">Comprehensive financial overview and management across all schools</p>
        </div>
        <!-- <div class="d-flex gap-2">
            <button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#bulkActionModal">
                <i class="bi bi-gear me-1"></i> Bulk Actions
            </button>
            <button class="btn btn-outline-success" data-bs-toggle="modal" data-bs-target="#exportModal">
                <i class="bi bi-download me-1"></i> Export Reports
            </button>
            <button class="btn btn-primary" onclick="refreshDashboard()">
                <i class="bi bi-arrow-clockwise me-1"></i> Refresh
            </button>
        </div> -->
    </div>

    <!-- Financial KPIs Row -->
    <div class="row g-3 mb-4">
        <!-- Group Revenue KPI -->
        <div class="col-xl-3 col-md-6">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Group Revenue
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                TZS {{ number_format($financialKPIs['group_revenue']['total'] / 1000000, 1) }}M
                                @if($financialKPIs['group_revenue']['monthly_trend'] > 0)
                                    <small class="text-success">
                                        <i class="bi bi-arrow-up"></i> +{{ $financialKPIs['group_revenue']['monthly_trend'] }}%
                                    </small>
                                @else
                                    <small class="text-danger">
                                        <i class="bi bi-arrow-down"></i> {{ $financialKPIs['group_revenue']['monthly_trend'] }}%
                                    </small>
                                @endif
                            </div>
                            <div class="text-xs text-muted mt-1">
                                {{ $financialKPIs['group_revenue']['target_achievement'] }}% of target achieved
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-graph-up fs-2 text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Group Expenses KPI -->
        <div class="col-xl-3 col-md-6">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Group Expenses
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                TZS {{ number_format($financialKPIs['group_expenses']['total'] / 1000000, 1) }}M
                                @if($financialKPIs['group_expenses']['monthly_trend'] > 0)
                                    <small class="text-danger">
                                        <i class="bi bi-arrow-up"></i> +{{ $financialKPIs['group_expenses']['monthly_trend'] }}%
                                    </small>
                                @else
                                    <small class="text-success">
                                        <i class="bi bi-arrow-down"></i> {{ abs($financialKPIs['group_expenses']['monthly_trend']) }}%
                                    </small>
                                @endif
                            </div>
                            <div class="text-xs text-muted mt-1">
                                {{ $financialKPIs['group_expenses']['budget_utilization'] }}% budget utilized
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-graph-down fs-2 text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Outstanding Fees KPI -->
        <div class="col-xl-3 col-md-6">
            <div class="card border-left-danger shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                Outstanding Fees
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                TZS {{ number_format($financialKPIs['outstanding_fees']['total'] / 1000000, 1) }}M
                                <small class="text-warning">
                                    <i class="bi bi-exclamation-triangle"></i> {{ $financialKPIs['outstanding_fees']['defaulters_count'] }} defaulters
                                </small>
                            </div>
                            <div class="text-xs text-muted mt-1">
                                {{ $financialKPIs['outstanding_fees']['collection_rate'] }}% collection rate
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-clock-history fs-2 text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bank Balances KPI -->
        <div class="col-xl-3 col-md-6">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Total Fixed Assets
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                TZS {{ number_format($financialKPIs['fixed_assets']['total_assets'] / 1000000, 1) }}M
                            </div>
                            <div class="text-xs text-muted mt-1">
                                Across approved banks
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-bank fs-2 text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Secondary KPIs Row -->
    <div class="row g-3 mb-4">
        <!-- Payroll Summary -->
        <div class="col-lg-4">
            <div class="card shadow">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">Payroll Summary</h6>
                    <i class="bi bi-people"></i>
                </div>
                <div class="card-body">
                    <div class="row text-center mb-3">
                        <div class="col">
                            <div class="text-xs text-muted text-uppercase">Total Net Payments</div>
                            <div class="h5 font-weight-bold text-primary">
                                TZS {{ number_format($financialKPIs['payroll_summary']['total_net'] / 1000000, 1) }}M
                            </div>
                            <div class="text-xs text-muted mt-1">Net salaries paid</div>
                        </div>

                        <div class="col">
                            <div class="text-xs text-muted text-uppercase">Total PAYE (TRA)</div>
                            <div class="h5 font-weight-bold text-warning">
                                TZS {{ number_format($financialKPIs['payroll_summary']['paye_payments'] / 1000000, 1) }}M
                            </div>
                            <div class="text-xs text-muted mt-1">Taxes remitted</div>
                        </div>

                        <div class="col">
                            <div class="text-xs text-muted text-uppercase">Pension Fund Issues</div>
                            <div class="h5 font-weight-bold text-info">
                                TZS {{ number_format($financialKPIs['payroll_summary']['total_pension_payment'] / 1000000, 1) }}M
                            </div>
                            <div class="text-xs text-muted mt-1">Employer & employee contributions</div>
                        </div>

                        <div class="col">
                            <div class="text-xs text-muted text-uppercase">Total Allowances Issued</div>
                            <div class="h5 font-weight-bold text-success">
                                TZS {{ number_format($financialKPIs['payroll_summary']['total_allowances'] / 1000000, 1) }}M
                            </div>
                            <div class="text-xs text-muted mt-1">Allowances & reimbursements</div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="text-sm font-weight-bold">Staff Cost Ratio</span>
                        <span class="badge bg-primary">{{ $financialKPIs['payroll_summary']['staff_cost_percentage'] }}%</span>
                    </div>
                    <div class="progress mb-3">
                        <div class="progress-bar" role="progressbar"
                             style="width: {{ $financialKPIs['payroll_summary']['staff_cost_percentage'] }}%"
                             aria-valuenow="{{ $financialKPIs['payroll_summary']['staff_cost_percentage'] }}"
                             aria-valuemin="0" aria-valuemax="100">
                        </div>
                    </div>

                    @if(!empty($financialKPIs['payroll_summary']['overdue_count']) && $financialKPIs['payroll_summary']['overdue_count'] > 0)
                    <div class="text-center">
                        <span class="badge bg-danger">{{ $financialKPIs['payroll_summary']['overdue_count'] }} overdue payments</span>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Budget Status -->
        <div class="col-lg-4">
            <div class="card shadow">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">Budget Status</h6>
                    <i class="bi bi-clipboard-data"></i>
                </div>
                <div class="card-body">
                    <!-- Budget Creation Status Overview -->
                    <div class="row text-center mb-3">
                        <div class="col-6">
                            <div class="text-xs text-muted text-uppercase">Schools with Budget</div>
                            <div class="h4 font-weight-bold text-success">
                                {{ $financialKPIs['budget_status']['schools_with_budget'] ?? 0 }}
                                <small class="text-muted">/ {{ $financialKPIs['budget_status']['total_schools'] ?? 0 }}</small>
                            </div>
                            <div class="progress mb-2" style="height: 6px;">
                                <div class="progress-bar bg-success" 
                                     style="width: {{ $financialKPIs['budget_status']['budget_completion_rate'] ?? 0 }}%"></div>
                            </div>
                            <small class="text-muted">{{ $financialKPIs['budget_status']['budget_completion_rate'] ?? 0 }}% completion rate</small>
                        </div>
                        <div class="col-6">
                            <div class="text-xs text-muted text-uppercase">Missing Budget</div>
                            <div class="h4 font-weight-bold text-danger">
                                {{ $financialKPIs['budget_status']['schools_without_budget'] ?? 0 }}
                            </div>
                            @if(($financialKPIs['budget_status']['schools_without_budget'] ?? 0) > 0)
                                <div class="mt-2">
                                    <button class="btn btn-sm btn-outline-warning" 
                                            onclick="showMissingBudgetSchools()" 
                                            data-bs-toggle="tooltip" 
                                            title="View schools that need budget creation">
                                        <i class="bi bi-exclamation-triangle me-1"></i>View Schools
                                    </button>
                                </div>
                            @else
                                <small class="text-success"><i class="bi bi-check-circle"></i> All schools budgeted</small>
                            @endif
                        </div>
                    </div>

                    <hr/>

                    <!-- Action Recommendations -->
                    <div class="mt-3">
                        @if(($financialKPIs['budget_status']['schools_without_budget'] ?? 0) > 0)
                            <div  style="color: black !important;"  class="alert alert-warning alert-sm py-2 mb-2">
                                <i style="color: black !important;" class="bi bi-info-circle me-1"></i>
                                <strong  style="color: black !important;" >Action Required:</strong> 
                                {{ $financialKPIs['budget_status']['schools_without_budget'] }} schools need budget creation.
                                <a href="#" onclick="initiateSchoolBudgetReminder()" class="alert-link"  style="color: black !important;" >Send reminders</a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Bank Distribution -->
        <div class="col-lg-4">
            <div class="card shadow">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">Bank Collection Distribution</h6>
                    <i class="bi bi-bank2"></i>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        @foreach($financialKPIs['bank_balances'] as $bankName => $amount)
                            <div class="d-flex justify-content-between mb-1">
                                <span class="text-sm">{{ $bankName }}</span>
                                <span class="font-weight-bold">
                                    TZS {{ number_format( (float) ($amount ?? 0) / 1000000, 1) }}M
                                </span>
                            </div>
                            <hr/>
                        @endforeach
                    </div>
                    <!-- <div class="text-center">
                        <button class="btn btn-sm btn-outline-primary" onclick="viewBankReconciliation()">
                            <i class="bi bi-check-square me-1"></i> Bank Reconciliation
                        </button>
                    </div> -->
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="row mb-4">
        <!-- Financial Performance Chart -->
        <div class="col-xl-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">Financial Performance Trends</h6>
                    <div class="dropdown">
                        <button class="btn btn-sm btn-outline-primary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                            <i class="bi bi-gear"></i>
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="#" onclick="updateFinancialChart('revenue')">Revenue Only</a></li>
                            <li><a class="dropdown-item" href="#" onclick="updateFinancialChart('expenses')">Expenses Only</a></li>
                            <li><a class="dropdown-item" href="#" onclick="updateFinancialChart('profit')">Profit Only</a></li>
                            <li><a class="dropdown-item" href="#" onclick="updateFinancialChart('all')">All Metrics</a></li>
                        </ul>
                    </div>
                </div>
                <div class="card-body">
                    <canvas id="financialPerformanceChart" width="100%" height="50"></canvas>
                </div>
            </div>
        </div>

        <!-- Financial Alerts Panel -->
        <div class="col-xl-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">Other Reports</h6>
                    <small class="text-muted">{{ $reportsData['totals']['schools_count'] ?? count($schoolsList) }} schools</small>
                </div>
                <div class="card-body p-3">
                    <!-- 1. Other Revenue (Non-fee) -->
                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <div class="text-sm font-weight-bold">Other Revenue (Excluding School Fees)</div>
                                <div class="text-xs text-muted">Non School Fee income streams</div>
                            </div>
                         </div>

                        @php
                            $otherRevTotal = $reportsData['other_revenue']['total'] ?? 0;
                            $otherRevSources = $reportsData['other_revenue']['sources'] ?? [];
                        @endphp

                        <div class="d-flex justify-content-between align-items-center mt-2">
                            <div>
                                <div class="h6 font-weight-bold text-success">
                                    TZS {{ number_format($otherRevTotal / 1000000, 1) }}M
                                </div>
                                <div class="text-xs text-muted">Contribution to group revenue: {{ $reportsData['other_revenue']['percentage_of_total'] ?? 0 }}%</div>
                            </div>
                            <div class="text-end text-sm">
                                <div class="text-muted">Top sources</div>
                                @if(!empty($otherRevSources) && is_array($otherRevSources))
                                    @foreach(array_slice($otherRevSources, 0, 3) as $src)
                                        <div>
                                            <strong>{{ $src['name'] ?? 'Source' }}</strong>
                                            <div class="text-success">TZS {{ number_format(($src['amount'] ?? 0) / 1000000, 1) }}M</div>
                                        </div>
                                    @endforeach
                                @else
                                    <div class="text-muted">No other revenue data</div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <hr/>

                    <!-- 2. Discount Reports -->
                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <div class="text-sm font-weight-bold">Discounts Issued</div>
                                <div class="text-xs text-muted">Monitor discounting impact on collections</div>
                            </div>
                        </div>

                        @php
                            $discountTotal = $reportsData['discounts']['total_amount'] ?? 0;
                            $discountCount = $reportsData['discounts']['count'] ?? 0;
                            $discountTop = $reportsData['discounts']['top_types'] ?? [];
                        @endphp

                        <div class="row mt-2">
                            <div class="col-6">
                                <div class="text-xs text-muted">Total Discounted (YTD)</div>
                                <div class="h6 font-weight-bold text-warning">TZS {{ number_format($discountTotal / 1000000, 1) }}M</div>
                                <div class="text-xs text-muted">{{ $discountCount }} discount transactions</div>
                            </div>
                            <div class="col-6">
                                <div class="text-xs text-muted">Top Discount Types</div>
                                @if(!empty($discountTop))
                                    <ul class="mb-0" style="padding-left:1rem;">
                                        @foreach(array_slice($discountTop, 0, 3) as $d)
                                            <li class="text-sm">
                                                <strong>{{ $d['type'] ?? 'Type' }}</strong> — TZS {{ number_format(($d['amount'] ?? 0) / 1000000, 1) }}M
                                            </li>
                                        @endforeach
                                    </ul>
                                @else
                                    <div class="text-sm text-muted">No discount breakdown</div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <hr/>

                    <!-- 3. Student Exemptions -->
                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <div class="text-sm font-weight-bold">Student Fee Exemptions</div>
                                <div class="text-xs text-muted">Students exempted from specific fees (transport, school fee etc)</div>
                            </div>
                        </div>

                        @php
                            $exemptCount = $reportsData['exemptions']['student_count'] ?? 0;
                            $exemptValue = $reportsData['exemptions']['total_value'] ?? 0;
                            $exemptTopReasons = $reportsData['exemptions']['top_reasons'] ?? [];
                        @endphp

                        <div class="d-flex justify-content-between align-items-center mt-2">
                            <div>
                                <div class="h6 font-weight-bold text-gray-800">{{ $exemptCount }} students</div>
                                <div class="text-xs text-muted">Value exempted: TZS {{ number_format($exemptValue / 1000000, 1) }}M</div>
                            </div>
                            <div class="text-end text-sm">
                                <div class="text-muted">Top reasons</div>
                                @if(!empty($exemptTopReasons))
                                    @foreach(array_slice($exemptTopReasons, 0, 3) as $r)
                                        <div>{{ $r['reason'] ?? 'Reason' }} — {{ $r['count'] ?? 0 }} students</div>
                                    @endforeach
                                @else
                                    <div class="text-muted">No exemption breakdown</div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <hr/>

                    <!-- 4. Inventory Report -->
                    <div class="mb-2">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <div class="text-sm font-weight-bold">Inventory Overview</div>
                                <div class="text-xs text-muted">Stock value, low-stock alerts and turning inventory</div>
                            </div>
                        </div>

                        @php
                            $inventoryValue = $reportsData['inventory']['total_value'] ?? 0;
                            $lowStockCount = $reportsData['inventory']['low_stock_count'] ?? 0;
                            $slowMoving = $reportsData['inventory']['slow_moving'] ?? [];
                        @endphp

                        <div class="row mt-2">
                            <div class="col-6">
                                <div class="text-xs text-muted">Total Stock Value</div>
                                <div class="h6 font-weight-bold text-info">TZS {{ number_format($inventoryValue / 1000000, 1) }}M</div>
                                <div class="text-xs text-muted">{{ $lowStockCount }} SKUs low on stock</div>
                            </div>
                            <div class="col-6">
                                <div class="text-xs text-muted">Slow-moving Items</div>
                                @if(!empty($slowMoving))
                                    <ul class="mb-0" style="padding-left:1rem;">
                                        @foreach(array_slice($slowMoving, 0, 3) as $i)
                                            <li class="text-sm">
                                                {{ $i['item'] ?? 'Item' }} — {{ $i['months_stock'] ?? 'N/A' }} months
                                            </li>
                                        @endforeach
                                    </ul>
                                @else
                                    <div class="text-sm text-muted">No slow-moving items flagged</div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <hr/>
                </div>
            </div>
        </div>
    </div>

    <!-- Schools Financial Overview -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Schools Financial Overview</h6>
            <div class="d-flex gap-2">
                <div class="input-group" style="width: 250px;">
                    <input type="text" class="form-control" placeholder="Search schools..." id="schoolSearch">
                    <button class="btn btn-outline-secondary" type="button">
                        <i class="bi bi-search"></i>
                    </button>
                </div>
                <select class="form-select" style="width: 180px;" id="healthFilter">
                    <option value="">All Financial Health</option>
                    <option value="excellent">Excellent</option>
                    <option value="good">Good</option>
                    <option value="average">Average</option>
                    <option value="poor">Poor</option>
                </select>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover" id="schoolsTable">
                    <thead>
                        <tr>
                            <th>School</th>
                            <th>Revenue</th>
                            <th>Expenses</th>
                            <th>Profit Margin</th>
                            <th>Outstanding</th>
                            <th>Collection Rate</th>
                            <!-- <th>Bank Balance</th> -->
                            <th>Health</th>
                            <!-- <th>Actions</th> -->
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($schoolsList as $school)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="me-2">
                                        @php
                                            $healthIcon = match($school['financial_health']) {
                                                'excellent' => 'bi-check-circle-fill text-success',
                                                'good' => 'bi-check-circle text-success',
                                                'average' => 'bi-exclamation-circle text-warning',
                                                'poor' => 'bi-x-circle text-danger',
                                                default => 'bi-question-circle text-muted'
                                            };
                                        @endphp
                                        <i class="bi {{ $healthIcon }}"></i>
                                    </div>
                                    <div>
                                        <div class="font-weight-bold">{{ $school['name'] }}</div>
                                        <!-- <small class="text-muted">{{ $school['code'] }}</small> -->
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="font-weight-bold text-success">
                                    TZS {{ number_format($school['revenue'] / 1000000, 1) }}M
                                </span>
                            </td>
                            <td>
                                <span class="font-weight-bold text-warning">
                                    TZS {{ number_format($school['expenses'] / 1000000, 1) }}M
                                </span>
                            </td>
                            <td>
                                <span class="badge 
                                    @if($school['profit_margin'] >= 20) bg-success
                                    @elseif($school['profit_margin'] >= 10) bg-warning
                                    @else bg-danger
                                    @endif">
                                    {{ $school['profit_margin'] }}%
                                </span>
                            </td>
                            <td>
                                <span class="text-danger">
                                    TZS {{ number_format($school['outstanding_fees'] / 1000000, 1) }}M
                                </span>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <span class="me-2">{{ $school['collection_rate'] }}%</span>
                                    <div class="progress" style="width: 60px; height: 8px;">
                                        <div class="progress-bar 
                                            @if($school['collection_rate'] >= 90) bg-success
                                            @elseif($school['collection_rate'] >= 75) bg-warning  
                                            @else bg-danger
                                            @endif"
                                             style="width: {{ $school['collection_rate'] }}%">
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <!-- <td>
                                <span class="font-weight-bold text-info">
                                    TZS 
                                </span>
                            </td> -->
                            <td>
                                @php
                                    $healthClass = match($school['financial_health']) {
                                        'excellent' => 'bg-success',
                                        'good' => 'bg-primary',
                                        'average' => 'bg-warning',
                                        'poor' => 'bg-danger',
                                        default => 'bg-secondary'
                                    };
                                    $healthText = ucfirst($school['financial_health']);
                                @endphp
                                <span class="badge {{ $healthClass }}">{{ $healthText }}</span>
                            </td>
                            <!-- <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('finance.school', $school['id']) }}" 
                                       class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <button class="btn btn-sm btn-outline-secondary" 
                                            onclick="quickFinancialActions({{ $school['id'] }})">
                                        <i class="bi bi-gear"></i>
                                    </button>
                                </div>
                            </td> -->
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Revenue vs Expense Comparison -->
    <div class="row mb-4">
        <div class="col-lg-6">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Revenue vs Expense by School</h6>
                </div>
                <div class="card-body">
                    <canvas id="revenueExpenseChart" width="100%" height="60"></canvas>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Collection Efficiency by Region</h6>
                </div>
                <div class="card-body">
                    <canvas id="collectionChart" width="100%" height="60"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Bulk Action Modal -->
<div class="modal fade" id="bulkActionModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Financial Bulk Actions</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="bulkActionForm">
                    <div class="mb-3">
                        <label class="form-label">Select Action</label>
                        <select class="form-select" name="action" required>
                            <option value="">Choose an action...</option>
                            <option value="approve_budgets">Approve Pending Budgets</option>
                            <option value="send_fee_reminders">Send Fee Reminders</option>
                            <option value="update_bank_settings">Update Bank Settings</option>
                            <option value="reconcile_accounts">Reconcile Bank Accounts</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Select Schools</label>
                        <div class="border rounded p-3" style="max-height: 200px; overflow-y: auto;">
                            @foreach($schoolsList as $school)
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="schools[]" value="{{ $school['id'] }}">
                                <label class="form-check-label">{{ $school['name'] }}</label>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="executeBulkAction()">Execute Action</button>
            </div>
        </div>
    </div>
</div>

<!-- Export Modal -->
<div class="modal fade" id="exportModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Export Financial Reports</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="exportForm">
                    <div class="mb-3">
                        <label class="form-label">Report Type</label>
                        <select class="form-select" name="report_type" required>
                            <option value="financial_summary">Financial Summary</option>
                            <option value="revenue_analysis">Revenue Analysis</option>
                            <option value="expense_breakdown">Expense Breakdown</option>
                            <option value="outstanding_fees">Outstanding Fees Report</option>
                            <option value="bank_reconciliation">Bank Reconciliation</option>
                            <option value="payroll_summary">Payroll Summary</option>
                            <option value="budget_analysis">Budget Analysis</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Date Range</label>
                        <select class="form-select" name="date_range" required>
                            <option value="current_month">Current Month</option>
                            <option value="last_month">Last Month</option>
                            <option value="current_quarter">Current Quarter</option>
                            <option value="last_quarter">Last Quarter</option>
                            <option value="current_year">Current Year</option>
                            <option value="custom">Custom Range</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Format</label>
                        <select class="form-select" name="format" required>
                            <option value="excel">Excel (.xlsx)</option>
                            <option value="pdf">PDF</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Include Schools</label>
                        <div class="border rounded p-3" style="max-height: 150px; overflow-y: auto;">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="selectAllSchools" checked>
                                <label class="form-check-label" for="selectAllSchools"><strong>All Schools</strong></label>
                            </div>
                            <hr>
                            @foreach($schoolsList as $school)
                            <div class="form-check">
                                <input class="form-check-input school-checkbox" type="checkbox" name="schools[]" value="{{ $school['id'] }}" checked>
                                <label class="form-check-label">{{ $school['name'] }}</label>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-success" onclick="exportReport()">Export Report</button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
(function () {
    // Load Chart.js dynamically if it's not already available
    function loadChartJS() {
        return new Promise((resolve, reject) => {
            if (typeof Chart !== 'undefined') {
                return resolve();
            }
            const s = document.createElement('script');
            s.src = 'https://cdn.jsdelivr.net/npm/chart.js';
            s.async = true;
            s.onload = () => resolve();
            s.onerror = () => reject(new Error('Failed to load Chart.js'));
            document.head.appendChild(s);
        });
    }

    // Helper to run a function after DOM is ready
    function runWhenReady(fn) {
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', fn);
        } else {
            fn();
        }
    }

    function initChartsAndHandlers() {
        // Chart.js configurations
        const performanceData = @json($performanceData);
        const revenueExpenseData = @json($revenueExpenseData);

        // Only initialize charts if canvas elements exist
        const perfEl = document.getElementById('financialPerformanceChart');
        const revExpEl = document.getElementById('revenueExpenseChart');
        const collEl = document.getElementById('collectionChart');

        let financialPerformanceChart = null;
        let revenueExpenseChart = null;
        let collectionChart = null;

        if (perfEl) {
            const performanceCtx = perfEl.getContext('2d');
            financialPerformanceChart = new Chart(performanceCtx, {
                type: 'line',
                data: {
                    labels: performanceData.months,
                    datasets: [{
                        label: 'Revenue (TZS)',
                        data: performanceData.revenue_trend,
                        borderColor: '#1cc88a',
                        backgroundColor: 'rgba(28, 200, 138, 0.1)',
                        borderWidth: 2,
                        fill: true,
                        tension: 0.3
                    }, {
                        label: 'Expenses (TZS)',
                        data: performanceData.expense_trend,
                        borderColor: '#f6c23e',
                        backgroundColor: 'rgba(246, 194, 62, 0.1)',
                        borderWidth: 2,
                        fill: true,
                        tension: 0.3
                    }, {
                        label: 'Profit (TZS)',
                        data: performanceData.profit_trend,
                        borderColor: '#4e73df',
                        backgroundColor: 'rgba(78, 115, 223, 0.1)',
                        borderWidth: 2,
                        fill: true,
                        tension: 0.3
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'top',
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function (value) {
                                    return 'TZS ' + (value / 1000000).toFixed(1) + 'M';
                                }
                            }
                        }
                    },
                    interaction: {
                        intersect: false,
                        mode: 'index'
                    }
                }
            });
        }

        if (revExpEl) {
            const revenueExpenseCtx = revExpEl.getContext('2d');
            revenueExpenseChart = new Chart(revenueExpenseCtx, {
                type: 'bar',
                data: {
                    labels: revenueExpenseData.slice(0, 8).map(item => item.school),
                    datasets: [{
                        label: 'Revenue',
                        data: revenueExpenseData.slice(0, 8).map(item => item.revenue),
                        backgroundColor: 'rgba(28, 200, 138, 0.8)',
                        borderColor: '#1cc88a',
                        borderWidth: 1
                    }, {
                        label: 'Expenses',
                        data: revenueExpenseData.slice(0, 8).map(item => item.expense),
                        backgroundColor: 'rgba(246, 194, 62, 0.8)',
                        borderColor: '#f6c23e',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'top',
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function (value) {
                                    return 'TZS ' + (value / 1000000).toFixed(1) + 'M';
                                }
                            }
                        }
                    }
                }
            });
        }

        if (collEl) {
            const collectionCtx = collEl.getContext('2d');
            const regionCollectionData = @json($regionCollectionData);
            
            // Prepare data for the chart
            const regionLabels = regionCollectionData.regions.map(region => region.location);
            const regionValues = regionCollectionData.regions.map(region => region.percentage);
            
            collectionChart = new Chart(collectionCtx, {
                type: 'doughnut',
                data: {
                    labels: regionLabels.length > 0 ? regionLabels : ['No Data'],
                    datasets: [{
                        data: regionValues.length > 0 ? regionValues : [100],
                        backgroundColor: [
                            '#4e73df',
                            '#1cc88a',
                            '#36b9cc',
                            '#f6c23e',
                            '#e74a3b',
                            '#858796',
                            '#5a5c69'
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
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    const region = regionCollectionData.regions[context.dataIndex];
                                    if (region) {
                                        return region.location + ': ' + region.percentage + '% (TZS ' + 
                                               (region.total_revenue / 1000000).toFixed(1) + 'M)';
                                    }
                                    return context.label + ': ' + context.parsed + '%';
                                }
                            }
                        }
                    }
                }
            });
        }

        // Dashboard Functions
        function refreshDashboard() {
            location.reload();
        }

        function updateFinancialChart(type) {
            if (!financialPerformanceChart) return;
            const chart = financialPerformanceChart;

            // Hide all datasets first
            chart.data.datasets.forEach((dataset) => {
                dataset.hidden = true;
            });

            // Show selected datasets
            switch (type) {
                case 'revenue':
                    chart.data.datasets[0].hidden = false;
                    break;
                case 'expenses':
                    chart.data.datasets[1].hidden = false;
                    break;
                case 'profit':
                    chart.data.datasets[2].hidden = false;
                    break;
                case 'all':
                    chart.data.datasets.forEach(dataset => {
                        dataset.hidden = false;
                    });
                    break;
            }

            chart.update();
        }

        function viewBankReconciliation() {
            window.location.href = '/finance/bank-reconciliation';
        }

        function quickFinancialActions(schoolId) {
            alert('Quick financial actions for school ID: ' + schoolId);
        }

        function executeBulkAction() {
            const form = document.getElementById('bulkActionForm');
            if (!form) return;
            const formData = new FormData(form);

            const data = {};
            for (let [key, value] of formData.entries()) {
                if (data[key]) {
                    if (Array.isArray(data[key])) {
                        data[key].push(value);
                    } else {
                        data[key] = [data[key], value];
                    }
                } else {
                    data[key] = value;
                }
            }

            fetch('/finance/bulk-action', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': (document.querySelector('meta[name="csrf-token"]') || {}).getAttribute?.('content') || ''
                },
                body: JSON.stringify(data)
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert(data.message);
                        const modalEl = document.getElementById('bulkActionModal');
                        if (modalEl) {
                            bootstrap.Modal.getInstance(modalEl)?.hide();
                        }
                        refreshDashboard();
                    } else {
                        alert('Error: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while executing the action.');
                });
        }

        function exportReport() {
            const form = document.getElementById('exportForm');
            if (!form) return;
            const formData = new FormData(form);

            const data = {};
            for (let [key, value] of formData.entries()) {
                if (data[key]) {
                    if (Array.isArray(data[key])) {
                        data[key].push(value);
                    } else {
                        data[key] = [data[key], value];
                    }
                } else {
                    data[key] = value;
                }
            }

            fetch('/finance/export', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': (document.querySelector('meta[name="csrf-token"]') || {}).getAttribute?.('content') || ''
                },
                body: JSON.stringify(data)
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert(data.message);
                        const modalEl = document.getElementById('exportModal');
                        if (modalEl) {
                            bootstrap.Modal.getInstance(modalEl)?.hide();
                        }
                    } else {
                        alert('Error: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while exporting the report.');
                });
        }

        // Attach event handlers (guarding for element existence)
        const refreshBtn = document.querySelector('button[onclick="refreshDashboard()"]');
        if (refreshBtn) refreshBtn.addEventListener('click', refreshDashboard);

        // expose some functions to global scope used by inline onclicks
        window.updateFinancialChart = updateFinancialChart;
        window.viewBankReconciliation = viewBankReconciliation;
        window.quickFinancialActions = quickFinancialActions;
        window.executeBulkAction = executeBulkAction;
        window.exportReport = exportReport;
        window.refreshDashboard = refreshDashboard;

        // Table filtering and search
        const schoolSearchEl = document.getElementById('schoolSearch');
        if (schoolSearchEl) {
            schoolSearchEl.addEventListener('input', function () {
                const searchTerm = this.value.toLowerCase();
                const table = document.getElementById('schoolsTable');
                if (!table) return;
                const rows = table.getElementsByTagName('tbody')[0].getElementsByTagName('tr');

                for (let row of rows) {
                    const schoolName = row.cells[0].textContent.toLowerCase();

                    if (schoolName.includes(searchTerm)) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                }
            });
        }

        const healthFilterEl = document.getElementById('healthFilter');
        if (healthFilterEl) {
            healthFilterEl.addEventListener('change', function () {
                const filterValue = this.value;
                const table = document.getElementById('schoolsTable');
                if (!table) return;
                const rows = table.getElementsByTagName('tbody')[0].getElementsByTagName('tr');

                for (let row of rows) {
                    if (!filterValue) {
                        row.style.display = '';
                        continue;
                    }

                    const healthBadge = row.querySelector('.badge');
                    const healthText = (healthBadge && healthBadge.textContent || '').toLowerCase();

                    if (healthText === filterValue) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                }
            });
        }

        // Export modal - select all functionality
        const selectAllEl = document.getElementById('selectAllSchools');
        if (selectAllEl) {
            selectAllEl.addEventListener('change', function () {
                const schoolCheckboxes = document.querySelectorAll('.school-checkbox');
                schoolCheckboxes.forEach(checkbox => {
                    checkbox.checked = this.checked;
                });
            });
        }
    }

    // Load Chart.js then initialize charts after DOM ready
    loadChartJS()
        .then(() => {
            runWhenReady(initChartsAndHandlers);
        })
        .catch(err => {
            console.error('Chart.js failed to load:', err);
        });
})();
</script>
@endpush

@push('styles')
<style>
.border-left-primary {
    border-left: 0.25rem solid #4e73df !important;
}

.border-left-success {
    border-left: 0.25rem solid #1cc88a !important;
}

.border-left-info {
    border-left: 0.25rem solid #36b9cc !important;
}

.border-left-warning {
    border-left: 0.25rem solid #f6c23e !important;
}

.border-left-danger {
    border-left: 0.25rem solid #e74a3b !important;
}

.text-gray-300 {
    color: #d1d3e2 !important;
}

.text-gray-800 {
    color: #5a5c69 !important;
}

.shadow {
    box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15) !important;
}

.card-header {
    /* background-color: #f8f9fc; */
    border-bottom: 1px solid #e3e6f0;
}

.progress {
    height: 8px;
}

.table th {
    border-top: none;
    font-weight: 600;
    color: #858796;
    font-size: 0.85rem;
}

.btn-group .btn {
    border-radius: 0.375rem;
    margin-right: 2px;
}

.list-group-item {
    border: none;
    border-bottom: 1px solid #e3e6f0;
}

.list-group-item:last-child {
    border-bottom: none;
}

.text-sm {
    font-size: 0.875rem;
}

.text-xs {
    font-size: 0.75rem;
}

.font-weight-bold {
    font-weight: 700 !important;
}

#financialPerformanceChart {
    height: 300px !important;
}

#revenueExpenseChart, #collectionChart {
    height: 250px !important;
}

.badge {
    font-size: 0.75rem;
}

.form-check {
    margin-bottom: 0.5rem;
}

.dropdown-menu {
    box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
}

@media (max-width: 768px) {
    .col-xl-3, .col-lg-4, .col-lg-6 {
        margin-bottom: 1rem;
    }
    
    .table-responsive {
        font-size: 0.875rem;
    }
    
    .btn-group .btn {
        padding: 0.25rem 0.5rem;
    }
}
</style>
@endpush
