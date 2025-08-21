@extends('layouts.admin')

@section('title', 'Human Resources Dashboard')

@section('content')
<div class="container-fluid px-4">
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">Human Resources Dashboard</h1>
            <p class="text-muted mb-0">Comprehensive HR management across all schools</p>
        </div>
        <!-- <div class="d-flex gap-2">
            <button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#hrActionsModal">
                <i class="bi bi-lightning me-1"></i> Quick Actions
            </button>
            <button class="btn btn-outline-success" onclick="exportHRReport()">
                <i class="bi bi-download me-1"></i> Export Report
            </button>
            <div class="dropdown">
                <button class="btn btn-primary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                    <i class="bi bi-plus me-1"></i> Add New
                </button>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="{{ route('hr.staff-directory') }}">Add Staff Member</a></li>
                    <li><a class="dropdown-item" href="{{ route('hr.recruitment') }}">Post Job Opening</a></li>
                    <li><a class="dropdown-item" href="#">Update HR Policy</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item" href="#">Bulk Operations</a></li>
                </ul>
            </div>
        </div> -->
    </div>

    <!-- KPI Cards Row -->
    <div class="row g-3 mb-4">
        <!-- Total Staff -->
        <div class="col-xl-3 col-md-6">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Staff</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($hrKPIs['total_staff']) }}</div>
                            <div class="text-xs text-success">
                                <i class="bi bi-person-check me-1"></i>{{ number_format($hrKPIs['active_staff']) }} Active
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-people fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Turnover Rate -->
        <div class="col-xl-3 col-md-6">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Turnover Rate</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $hrKPIs['turnover_rate'] }}%</div>
                            <div class="text-xs text-{{ $hrKPIs['turnover_rate'] < 10 ? 'success' : 'danger' }}">
                                <i class="bi bi-arrow-{{ $hrKPIs['turnover_rate'] < 10 ? 'down' : 'up' }} me-1"></i>vs last quarter
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-arrow-repeat fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Attendance Rate -->
        <div class="col-xl-3 col-md-6">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Attendance Rate</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $hrKPIs['average_attendance'] }}%</div>
                            <div class="progress progress-sm">
                                <div class="progress-bar bg-success" style="width: {{ $hrKPIs['average_attendance'] }}%"></div>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-check-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Vacant Positions -->
        <div class="col-xl-3 col-md-6">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Vacant Positions</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $hrKPIs['vacant_positions'] }}</div>
                            <div class="text-xs text-info">
                                <i class="bi bi-briefcase me-1"></i>Recruitment in progress
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-briefcase fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- New Database-Driven Metrics Row -->
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card text-center shadow border-left-success">
                <div class="card-body">
                    <div class="h4 text-success">{{ $staffMetrics['total_teachers'] ?? 0 }}</div>
                    <div class="text-muted">
                        <i class="fas fa-chalkboard-teacher me-1"></i>Total Teachers
                    </div>
                    <small class="text-xs text-muted">Active teaching staff</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center shadow border-left-info">
                <div class="card-body">
                    <div class="h4 text-info">{{ $staffMetrics['non_teaching_staff'] ?? 0 }}</div>
                    <div class="text-muted">
                        <i class="fas fa-users-cog me-1"></i>Non-Teaching Staff
                    </div>
                    <small class="text-xs text-muted">Administrative & support</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center shadow border-left-warning">
                <div class="card-body">
                    <div class="h4 text-warning">{{ $staffMetrics['total_parents'] ?? 0 }}</div>
                    <div class="text-muted">
                        <i class="fas fa-user-friends me-1"></i>Total Parents
                    </div>
                    <small class="text-xs text-muted">Registered parents</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center shadow border-left-primary">
                <div class="card-body">
                    <div class="h4 text-primary">{{ $staffMetrics['total_sponsors'] ?? 0 }}</div>
                    <div class="text-muted">
                        <i class="fas fa-handshake me-1"></i>Total Sponsors
                    </div>
                    <small class="text-xs text-muted">Active sponsors</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Staff Performance KPI Row -->
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card text-center shadow">
                <div class="card-body">
                    <div class="h4 text-success">{{ $staffPerformance['avg_kpi_score'] ?? 0 }}%</div>
                    <div class="text-muted">
                        <i class="fas fa-chart-line me-1"></i>Avg KPI Score
                    </div>
                    <small class="text-xs text-muted">Overall staff performance</small>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-center shadow">
                <div class="card-body">
                    <div class="h4 text-info">{{ $staffPerformance['kpi_completed'] ?? 0 }}</div>
                    <div class="text-muted">
                        <i class="fas fa-tasks me-1"></i>KPIs Completed
                    </div>
                    <small class="text-xs text-muted">This month</small>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-center shadow">
                <div class="card-body">
                    <div class="h4 text-warning">{{ $staffMetrics['inactive_staff'] ?? 0 }}</div>
                    <div class="text-muted">
                        <i class="fas fa-user-slash me-1"></i>Inactive Staff
                    </div>
                    <small class="text-xs text-muted">Currently inactive</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Row -->
    <div class="row">
        <!-- Left Column -->
        <div class="col-lg-8">
            <!-- HR Performance Trends -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="bi bi-graph-up me-2"></i>HR Performance Trends
                    </h6>
                </div>
                <div class="card-body">
                    <canvas id="hrTrendsChart" width="100%" height="60"></canvas>
                </div>
            </div>

            <!-- Schools HR Overview -->
            <div class="card shadow">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="bi bi-building me-2"></i>Schools HR Overview
                    </h6>
                    <div class="dropdown">
                        <button class="btn btn-sm btn-outline-primary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                            Filter by Region
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="#" onclick="filterSchools('all')">All Regions</a></li>
                            <li><a class="dropdown-item" href="#" onclick="filterSchools('dar')">Dar es Salaam</a></li>
                            <li><a class="dropdown-item" href="#" onclick="filterSchools('mwanza')">Mwanza</a></li>
                            <li><a class="dropdown-item" href="#" onclick="filterSchools('arusha')">Arusha</a></li>
                        </ul>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover" id="schoolsHRTable">
                            <thead>
                                <tr>
                                    <th><input type="checkbox" id="selectAllSchools"></th>
                                    <th>School</th>
                                    <th>Total Staff</th>
                                    <!-- <th>Vacant Positions</th> -->
                                    <th>Turnover Rate</th>
                                    <th>Attendance</th>
                                    <th>Payroll Status</th>
                                    <!-- <th>Actions</th> -->
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($schoolsList as $school)
                                <tr>
                                    <td><input type="checkbox" name="school_ids" value="{{ $school['id'] }}"></td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="school-avatar bg-primary text-white rounded-circle me-2 d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                                                {{ substr($school['name'], 0, 1) }}
                                            </div>
                                            <div>
                                                <div class="font-weight-bold">{{ $school['name'] }}</div>
                                                <!-- <div class="text-xs text-muted">{{ $school['region'] }}</div> -->
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="font-weight-bold">{{ $school['total_staff'] }}</span>
                                        <div class="text-xs text-muted">{{ $school['active_staff'] }} active</div>
                                    </td>
                                    <!-- <td>
                                        @if($school['vacant_positions'] > 0)
                                            <span class="badge bg-warning">{{ $school['vacant_positions'] }}</span>
                                        @else
                                            <span class="badge bg-success">0</span>
                                        @endif
                                    </td> -->
                                    <td>
                                        <span class="text-{{ $school['turnover_rate'] > 12 ? 'danger' : ($school['turnover_rate'] > 8 ? 'warning' : 'success') }}">
                                            {{ $school['turnover_rate'] }}%
                                        </span>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <span class="me-2">{{ $school['attendance_rate'] }}%</span>
                                            <div class="progress flex-grow-1" style="height: 6px;">
                                                <div class="progress-bar bg-{{ $school['attendance_rate'] > 95 ? 'success' : ($school['attendance_rate'] > 90 ? 'warning' : 'danger') }}" 
                                                     style="width: {{ $school['attendance_rate'] }}%"></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        @php
                                            $payrollDate = $school['payroll_date'] ?? null;
                                            $badgeClass = 'bg-danger';
                                            $statusText = 'Never';
                                            
                                            if ($payrollDate) {
                                                $payrollDateCarbon = \Carbon\Carbon::parse($payrollDate);
                                                $now = \Carbon\Carbon::now();
                                                
                                                if ($payrollDateCarbon->isSameMonth($now)) {
                                                    $badgeClass = 'bg-success';
                                                    $statusText = 'Current';
                                                } elseif ($payrollDateCarbon->isSameMonth($now->subMonth())) {
                                                    $badgeClass = 'bg-warning';
                                                    $statusText = 'Last Month';
                                                } else {
                                                    $badgeClass = 'bg-danger';
                                                    $statusText = 'Overdue';
                                                }
                                            }
                                        @endphp
                                        <span class="badge {{ $badgeClass }}">
                                            {{ $statusText }}
                                        </span>
                                        @if($payrollDate)
                                            <div class="text-xs text-muted">{{ \Carbon\Carbon::parse($payrollDate)->format('d/m/Y') }}</div>
                                        @endif
                                    </td>
                                    <!-- <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('hr.school', $school['id']) }}" class="btn btn-sm btn-outline-primary">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <button class="btn btn-sm btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown">
                                                <i class="bi bi-three-dots"></i>
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li><a class="dropdown-item" href="#">Send Communication</a></li>
                                                <li><a class="dropdown-item" href="#">View Payroll</a></li>
                                                <li><a class="dropdown-item" href="#">HR Analytics</a></li>
                                            </ul>
                                        </div>
                                    </td> -->
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Bulk Actions -->
                    <div class="row mt-3">
                        <div class="col-md-6">
                            <div class="d-flex align-items-center">
                                <label for="bulkHRAction" class="form-label me-2 mb-0">Bulk Action:</label>
                                <select class="form-select form-select-sm me-2" id="bulkHRAction" style="width: auto;">
                                    <option value="">Select Action</option>
                                    <option value="send_communication">Send Communication</option>
                                    <option value="update_policy">Update HR Policy</option>
                                    <option value="generate_report">Generate Report</option>
                                    <option value="approve_payroll">Approve Payroll</option>
                                </select>
                                <button class="btn btn-sm btn-primary" onclick="executeBulkHRAction()">Execute</button>
                            </div>
                        </div>
                        <div class="col-md-6 text-end">
                            <small class="text-muted">{{ count($schoolsList) }} schools total</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column -->
        <div class="col-lg-4">
            <!-- HR Alerts -->
            <!-- <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-danger">
                        <i class="bi bi-exclamation-triangle me-2"></i>HR Alerts
                    </h6>
                </div>
                <div class="card-body">
                    @foreach($alertsData['high_priority'] as $alert)
                    <div class="alert alert-{{ $alert['severity'] === 'high' ? 'danger' : ($alert['severity'] === 'medium' ? 'warning' : 'info') }} mb-2" role="alert">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h6 class="alert-heading mb-1">{{ $alert['title'] }}</h6>
                                <p class="mb-1">{{ $alert['message'] }}</p>
                                <small class="text-muted">{{ $alert['school'] }}</small>
                            </div>
                            <button type="button" class="btn btn-sm btn-outline-secondary">
                                <i class="bi bi-arrow-right"></i>
                            </button>
                        </div>
                    </div>
                    @endforeach
                    
                    <div class="text-center mt-3">
                        <a href="#" class="btn btn-outline-primary btn-sm">View All Alerts</a>
                    </div>
                </div>
            </div> -->

            <!-- Staff Distribution -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="bi bi-pie-chart me-2"></i>Staff Distribution
                    </h6>
                </div>
                <div class="card-body">
                    <canvas id="staffDistributionChart" width="100%" height="180"></canvas>
                    <div class="mt-3">
                        @foreach($staffDirectory['by_role'] as $role => $count)
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-sm">{{ $role }}:</span>
                            <span class="font-weight-bold">{{ number_format($count) }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Inactive Staff Analysis -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-user-slash me-2"></i>Inactive Staff Analysis
                    </h6>
                </div>
                <div class="card-body">
                    <canvas id="inactiveStaffChart" width="100%" height="180"></canvas>
                    <div class="mt-3">
                        @if(isset($inactiveStaffData) && count($inactiveStaffData) > 0)
                            @foreach($inactiveStaffData as $reason => $count)
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-sm">{{ ucfirst(str_replace('_', ' ', $reason)) }}:</span>
                                <span class="font-weight-bold">{{ number_format($count) }}</span>
                            </div>
                            @endforeach
                        @else
                            <div class="text-center text-muted">
                                <i class="fas fa-check-circle fa-2x mb-2"></i>
                                <p>No inactive staff currently</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Quick Stats -->
            <!-- <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="bi bi-speedometer2 me-2"></i>Quick Stats
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-6 mb-3">
                            <div class="h5 text-primary">{{ $recruitmentData['open_positions'] }}</div>
                            <div class="text-xs text-muted">Open Positions</div>
                        </div>
                        <div class="col-6 mb-3">
                            <div class="h5 text-success">{{ $recruitmentData['applications_received'] }}</div>
                            <div class="text-xs text-muted">Applications</div>
                        </div>
                        <div class="col-6 mb-3">
                            <div class="h5 text-warning">{{ $payrollData['total_monthly_payroll'] / 1000000 }}M</div>
                            <div class="text-xs text-muted">Monthly Payroll</div>
                        </div>
                        <div class="col-6 mb-3">
                            <div class="h5 text-info">{{ $attendanceData['overall_attendance'] }}%</div>
                            <div class="text-xs text-muted">Avg Attendance</div>
                        </div>
                    </div>
                    
                    <hr>
                    
                    <div class="text-center">
                        <a href="{{ route('hr.staff-directory') }}" class="btn btn-primary btn-sm me-2">
                            <i class="bi bi-people me-1"></i> Staff Directory
                        </a>
                        <a href="{{ route('hr.recruitment') }}" class="btn btn-outline-primary btn-sm">
                            <i class="bi bi-briefcase me-1"></i> Recruitment
                        </a>
                    </div>
                </div>
            </div> -->
        </div>
    </div>
</div>

<!-- HR Actions Modal -->
<div class="modal fade" id="hrActionsModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">HR Quick Actions</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <h6>Staff Management</h6>
                        <div class="list-group">
                            <button type="button" class="list-group-item list-group-item-action">
                                <i class="bi bi-person-plus me-2"></i> Add New Staff
                            </button>
                            <button type="button" class="list-group-item list-group-item-action">
                                <i class="bi bi-people me-2"></i> Bulk Update Profiles
                            </button>
                            <button type="button" class="list-group-item list-group-item-action">
                                <i class="bi bi-award me-2"></i> Performance Reviews
                            </button>
                            <button type="button" class="list-group-item list-group-item-action">
                                <i class="bi bi-calendar-check me-2"></i> Attendance Monitoring
                            </button>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <h6>HR Operations</h6>
                        <div class="list-group">
                            <button type="button" class="list-group-item list-group-item-action">
                                <i class="bi bi-briefcase me-2"></i> Post Job Opening
                            </button>
                            <button type="button" class="list-group-item list-group-item-action">
                                <i class="bi bi-calendar-x me-2"></i> Approve Leave Requests
                            </button>
                            <button type="button" class="list-group-item list-group-item-action">
                                <i class="bi bi-cash-stack me-2"></i> Process Payroll
                            </button>
                            <button type="button" class="list-group-item list-group-item-action">
                                <i class="bi bi-file-text me-2"></i> Update HR Policies
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
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// HR Trends Chart
const hrTrendsData = @json($performanceData);
const ctx = document.getElementById('hrTrendsChart').getContext('2d');
const hrTrendsChart = new Chart(ctx, {
    type: 'line',
    data: {
        labels: hrTrendsData.monthly_trends.labels,
        datasets: [
            {
                label: 'Staff Count',
                data: hrTrendsData.monthly_trends.staff_count,
                borderColor: '#4e73df',
                backgroundColor: 'rgba(78, 115, 223, 0.1)',
                tension: 0.3,
                yAxisID: 'y'
            },
            {
                label: 'Turnover',
                data: hrTrendsData.monthly_trends.turnover,
                borderColor: '#e74a3b',
                backgroundColor: 'rgba(231, 74, 59, 0.1)',
                tension: 0.3,
                yAxisID: 'y1'
            },
            {
                label: 'New Hires',
                data: hrTrendsData.monthly_trends.new_hires,
                borderColor: '#1cc88a',
                backgroundColor: 'rgba(28, 200, 138, 0.1)',
                tension: 0.3,
                yAxisID: 'y1'
            },
            {
                label: 'Attendance %',
                data: hrTrendsData.monthly_trends.attendance,
                borderColor: '#36b9cc',
                backgroundColor: 'rgba(54, 185, 204, 0.1)',
                tension: 0.3,
                yAxisID: 'y2'
            }
        ]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
            y: {
                type: 'linear',
                display: true,
                position: 'left',
                title: {
                    display: true,
                    text: 'Staff Count'
                }
            },
            y1: {
                type: 'linear',
                display: true,
                position: 'right',
                title: {
                    display: true,
                    text: 'Turnover/Hires'
                },
                grid: {
                    drawOnChartArea: false,
                }
            },
            y2: {
                type: 'linear',
                display: false,
                min: 80,
                max: 100
            }
        },
        plugins: {
            legend: {
                position: 'top',
            }
        }
    }
});

// Staff Distribution Chart
const staffDistData = @json($staffDirectory);
const staffCtx = document.getElementById('staffDistributionChart').getContext('2d');
const staffDistChart = new Chart(staffCtx, {
    type: 'doughnut',
    data: {
        labels: Object.keys(staffDistData.by_role),
        datasets: [{
            data: Object.values(staffDistData.by_role),
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

// Inactive Staff Analysis Chart
@if(isset($inactiveStaffData) && count($inactiveStaffData) > 0)
const inactiveStaffData = @json($inactiveStaffData);
const inactiveCtx = document.getElementById('inactiveStaffChart').getContext('2d');
const inactiveStaffChart = new Chart(inactiveCtx, {
    type: 'bar',
    data: {
        labels: Object.keys(inactiveStaffData).map(key => key.replace('_', ' ').replace(/\b\w/g, l => l.toUpperCase())),
        datasets: [{
            label: 'Number of Staff',
            data: Object.values(inactiveStaffData),
            backgroundColor: [
                '#e74a3b',
                '#f39c12',
                '#95a5a6',
                '#34495e',
                '#9b59b6'
            ],
            borderWidth: 2,
            borderColor: '#fff'
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                display: false
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    stepSize: 1
                }
            }
        }
    }
});
@else
// Show empty state for inactive staff chart
const inactiveCtx = document.getElementById('inactiveStaffChart').getContext('2d');
const inactiveStaffChart = new Chart(inactiveCtx, {
    type: 'bar',
    data: {
        labels: ['No Data'],
        datasets: [{
            label: 'Number of Staff',
            data: [0],
            backgroundColor: ['#e9ecef'],
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                display: false
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                max: 5
            }
        }
    }
});
@endif

// Utility Functions
function filterSchools(region) {
    // Implementation for filtering schools by region
    console.log('Filtering schools by region:', region);
}

function executeBulkHRAction() {
    const action = document.getElementById('bulkHRAction').value;
    const selectedSchools = document.querySelectorAll('input[name="school_ids"]:checked');
    
    if (!action) {
        alert('Please select an action');
        return;
    }
    
    if (selectedSchools.length === 0) {
        alert('Please select at least one school');
        return;
    }
    
    alert(`Executing ${action} for ${selectedSchools.length} schools`);
}

function exportHRReport() {
    alert('Exporting HR report...');
}

// Select All functionality
document.getElementById('selectAllSchools').addEventListener('change', function() {
    const checkboxes = document.querySelectorAll('input[name="school_ids"]');
    checkboxes.forEach(checkbox => checkbox.checked = this.checked);
});
</script>
@endpush

@push('styles')
<style>
.border-left-primary {
    border-left: 4px solid #4e73df !important;
}

.border-left-success {
    border-left: 4px solid #1cc88a !important;
}

.border-left-info {
    border-left: 4px solid #36b9cc !important;
}

.border-left-warning {
    border-left: 4px solid #f6c23e !important;
}

.text-gray-800 {
    color: #5a5c69 !important;
}

.shadow {
    box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15) !important;
}

.progress-sm {
    height: 0.5rem;
}

.school-avatar {
    font-size: 0.75rem;
    font-weight: bold;
}

.card-header {
    /* background-color: #f8f9fc; */
    border-bottom: 1px solid #e3e6f0;
}

.font-weight-bold {
    font-weight: 700 !important;
}

.text-xs {
    font-size: 0.75rem;
}

.list-group-item-action:hover {
    background-color: #f8f9fc;
}

#hrTrendsChart {
    height: 300px !important;
}

#staffDistributionChart {
    height: 200px !important;
}

#inactiveStaffChart {
    height: 200px !important;
}

@media (max-width: 768px) {
    .col-xl-3, .col-md-6 {
        margin-bottom: 1rem;
    }
    
    .d-flex.gap-2 {
        flex-direction: column;
        gap: 0.5rem !important;
    }
    
    .btn-group .btn {
        font-size: 0.75rem;
        padding: 0.25rem 0.5rem;
    }
}
</style>
@endpush
