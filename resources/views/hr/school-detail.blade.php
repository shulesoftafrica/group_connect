@extends('layouts.admin')

@section('title', 'School HR Detail - ' . $school->settings['school_name'] ?? 'Unknown School')

@section('content')
<div class="container-fluid px-4">
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('hr.index') }}">HR Dashboard</a></li>
                    <li class="breadcrumb-item active">{{ $school->settings['school_name'] ?? 'Unknown School' }}</li>
                </ol>
            </nav>
            <h1 class="h3 mb-0 text-gray-800">{{ $school->settings['school_name'] ?? 'Unknown School' }}</h1>
            <p class="text-muted mb-0">Detailed HR analysis and staff management</p>
        </div>
        <div class="d-flex gap-2">
            <button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#hrActionsModal">
                <i class="bi bi-lightning me-1"></i> Quick Actions
            </button>
            <button class="btn btn-outline-success" onclick="exportSchoolHRReport()">
                <i class="bi bi-download me-1"></i> Export Report
            </button>
            <a href="{{ route('hr.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left me-1"></i> Back to Dashboard
            </a>
        </div>
    </div>

    <!-- School HR Summary Card -->
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
                            <label class="text-muted small">Total Staff</label>
                            <div class="font-weight-bold text-primary">{{ $schoolHRData['summary']['total_staff'] }}</div>
                        </div>
                        <div class="col-sm-6 mb-3">
                            <label class="text-muted small">Active Staff</label>
                            <div class="font-weight-bold text-success">{{ $schoolHRData['summary']['active_staff'] }}</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="text-center">
                        <div class="h5 mb-1">HR Health Score</div>
                        <div class="h2 text-primary">{{ $schoolHRData['summary']['attendance_rate'] }}%</div>
                        <div class="progress mx-auto" style="width: 80%;">
                            <div class="progress-bar bg-primary" style="width: {{ $schoolHRData['summary']['attendance_rate'] }}%"></div>
                        </div>
                        <small class="text-muted">Overall Attendance</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- HR KPIs Row -->
    <div class="row g-3 mb-4">
        <!-- Staff Overview -->
        <div class="col-lg-6">
            <div class="card shadow h-100">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="bi bi-people me-2"></i>Staff Overview
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-6">
                            <div class="border-end pe-3">
                                <h6 class="text-muted">Total Staff</h6>
                                <div class="h4 mb-2 text-primary">{{ $schoolHRData['summary']['total_staff'] }}</div>
                                <div class="d-flex align-items-center">
                                    @if($schoolHRData['trends']['staff_growth'] > 0)
                                        <span class="text-success me-2">
                                            <i class="bi bi-arrow-up"></i> +{{ $schoolHRData['trends']['staff_growth'] }}%
                                        </span>
                                    @else
                                        <span class="text-danger me-2">
                                            <i class="bi bi-arrow-down"></i> {{ $schoolHRData['trends']['staff_growth'] }}%
                                        </span>
                                    @endif
                                    <span class="text-sm text-muted">vs last quarter</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="ps-3">
                                <h6 class="text-muted">Vacant Positions</h6>
                                <div class="h4 mb-2 text-warning">{{ $schoolHRData['summary']['vacant_positions'] }}</div>
                                <div class="d-flex align-items-center">
                                    @if($schoolHRData['summary']['vacant_positions'] == 0)
                                        <span class="text-success me-2">
                                            <i class="bi bi-check-circle"></i> Fully Staffed
                                        </span>
                                    @else
                                        <span class="text-warning me-2">
                                            <i class="bi bi-exclamation-triangle"></i> Recruitment needed
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="text-center">
                        <div class="h5 mb-1">Turnover Rate</div>
                        <div class="h3 {{ $schoolHRData['summary']['turnover_rate'] > 10 ? 'text-danger' : 'text-success' }}">
                            {{ $schoolHRData['summary']['turnover_rate'] }}%
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Attendance & Performance -->
        <div class="col-lg-6">
            <div class="card shadow h-100">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="bi bi-calendar-check me-2"></i>Attendance & Performance
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row text-center mb-3">
                        <div class="col">
                            <div class="text-sm text-muted text-uppercase">Overall</div>
                            <div class="h5 mb-0 text-success">{{ $attendanceData['current_rate'] }}%</div>
                        </div>
                        <div class="col">
                            <div class="text-sm text-muted text-uppercase">Teachers</div>
                            <div class="h5 mb-0 text-info">{{ $attendanceData['by_category']['Teachers'] }}%</div>
                        </div>
                        <div class="col">
                            <div class="text-sm text-muted text-uppercase">Support</div>
                            <div class="h5 mb-0 text-primary">{{ $attendanceData['by_category']['Support Staff'] }}%</div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-1">
                            <span class="text-sm font-weight-bold">Performance Score</span>
                            <span class="font-weight-bold">{{ $performanceData['appraisal_completion'] }}%</span>
                        </div>
                        <div class="progress">
                            <div class="progress-bar 
                                @if($performanceData['appraisal_completion'] >= 90) bg-success
                                @elseif($performanceData['appraisal_completion'] >= 75) bg-warning
                                @else bg-danger
                                @endif" 
                                 style="width: {{ $performanceData['appraisal_completion'] }}%"></div>
                        </div>
                    </div>
                    
                    <div class="text-sm">
                        <div class="d-flex justify-content-between">
                            <span>Training Completion:</span>
                            <span class="text-primary font-weight-bold">{{ $performanceData['training_completion'] }}%</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span>Pending Appraisals:</span>
                            <span class="text-warning">{{ 100 - $performanceData['appraisal_completion'] }}%</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Staff Details & Leave Management -->
    <div class="row mb-4">
        <!-- Staff by Role -->
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="bi bi-diagram-3 me-2"></i>Staff by Role
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        @foreach($staffData['by_role'] as $role => $count)
                        <div class="col-md-6 mb-3">
                            <div class="d-flex justify-content-between align-items-center p-3 border rounded">
                                <div>
                                    <h6 class="mb-0 font-weight-bold">{{ $role }}</h6>
                                    <small class="text-muted">Active Staff</small>
                                </div>
                                <div class="text-end">
                                    <div class="h4 mb-0 text-primary">{{ $count }}</div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    
                    <h6 class="font-weight-bold mt-4 mb-3">Recent Hires</h6>
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Position</th>
                                    <th>Hire Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($staffData['recent_hires'] as $hire)
                                <tr>
                                    <td>{{ $hire['name'] }}</td>
                                    <td>{{ $hire['position'] }}</td>
                                    <td>{{ $hire['date'] }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Leave Management -->
        <div class="col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="bi bi-calendar-x me-2"></i>Leave Management
                    </h6>
                </div>
                <div class="card-body">
                    <div class="text-center mb-3">
                        <div class="h5 mb-1">Pending Requests</div>
                        <div class="h3 text-warning">{{ $leaveData['pending_requests'] }}</div>
                        @if($leaveData['pending_requests'] > 0)
                        <div class="text-sm text-muted">
                            Requires approval
                        </div>
                        @endif
                    </div>
                    
                    <div class="mb-3">
                        <h6 class="text-muted">Leave Balances</h6>
                        @foreach($leaveData['leave_balances'] as $type => $days)
                        <div class="d-flex justify-content-between">
                            <span class="text-sm">{{ $type }}:</span>
                            <span class="font-weight-bold">{{ $days }} days</span>
                        </div>
                        @endforeach
                    </div>
                    
                    <h6 class="text-muted">Recent Requests</h6>
                    @foreach($leaveData['recent_requests'] as $request)
                    <div class="border rounded p-2 mb-2">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <div class="font-weight-bold text-sm">{{ $request['staff'] }}</div>
                                <div class="text-xs text-muted">{{ $request['type'] }} - {{ $request['days'] }} days</div>
                            </div>
                            <span class="badge bg-{{ $request['status'] === 'approved' ? 'success' : 'warning' }}">
                                {{ ucfirst($request['status']) }}
                            </span>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- Payroll & Performance Analytics -->
    <div class="row mb-4">
        <!-- Payroll Summary -->
        <div class="col-lg-6">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="bi bi-cash-stack me-2"></i>Payroll Summary
                    </h6>
                </div>
                <div class="card-body">
                    <div class="text-center mb-3">
                        <div class="h5 mb-1">Monthly Payroll</div>
                        <div class="h3 text-primary">TZS {{ number_format($payrollData['monthly_payroll'] / 1000000, 1) }}M</div>
                        <div class="text-sm text-muted">
                            Compliance: {{ $payrollData['compliance_status'] }}%
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <h6 class="text-muted">Payroll Breakdown</h6>
                        @foreach($payrollData['by_category'] as $category => $amount)
                        <div class="d-flex justify-content-between">
                            <span class="text-sm">{{ $category }}:</span>
                            <span class="font-weight-bold {{ $amount < 0 ? 'text-danger' : 'text-success' }}">
                                TZS {{ number_format(abs($amount) / 1000, 0) }}K
                            </span>
                        </div>
                        @endforeach
                    </div>
                    
                    @if($payrollData['pending_payments'] > 0)
                    <div class="alert alert-warning">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        {{ $payrollData['pending_payments'] }} pending payments
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Performance Analytics -->
        <div class="col-lg-6">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="bi bi-bar-chart me-2"></i>Performance Analytics
                    </h6>
                </div>
                <div class="card-body">
                    <canvas id="performanceChart" width="100%" height="60"></canvas>
                    <div class="mt-3">
                        @foreach($performanceData['performance_distribution'] as $level => $count)
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-sm">{{ $level }}:</span>
                            <span class="font-weight-bold">{{ $count }} staff</span>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Attendance Trends -->
    <div class="card shadow">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="bi bi-graph-up me-2"></i>Attendance Trends
            </h6>
        </div>
        <div class="card-body">
            <canvas id="attendanceChart" width="100%" height="40"></canvas>
        </div>
    </div>
</div>

<!-- HR Actions Modal -->
<div class="modal fade" id="hrActionsModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">HR Actions - {{ $school->settings['school_name'] ?? 'School' }}</h5>
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
                                <i class="bi bi-people me-2"></i> Update Staff Profiles
                            </button>
                            <button type="button" class="list-group-item list-group-item-action">
                                <i class="bi bi-award me-2"></i> Conduct Performance Review
                            </button>
                            <button type="button" class="list-group-item list-group-item-action">
                                <i class="bi bi-book me-2"></i> Assign Training
                            </button>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <h6>Administrative Actions</h6>
                        <div class="list-group">
                            <button type="button" class="list-group-item list-group-item-action">
                                <i class="bi bi-calendar-check me-2"></i> Approve Leave Requests
                            </button>
                            <button type="button" class="list-group-item list-group-item-action">
                                <i class="bi bi-cash-stack me-2"></i> Process Payroll
                            </button>
                            <button type="button" class="list-group-item list-group-item-action">
                                <i class="bi bi-briefcase me-2"></i> Post Job Opening
                            </button>
                            <button type="button" class="list-group-item list-group-item-action">
                                <i class="bi bi-file-text me-2"></i> Generate HR Report
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
// Performance Chart
const performanceData = @json($performanceData);
const performanceCtx = document.getElementById('performanceChart').getContext('2d');
const performanceChart = new Chart(performanceCtx, {
    type: 'doughnut',
    data: {
        labels: Object.keys(performanceData.performance_distribution),
        datasets: [{
            data: Object.values(performanceData.performance_distribution),
            backgroundColor: [
                '#1cc88a',
                '#4e73df',
                '#36b9cc',
                '#f6c23e',
                '#e74a3b'
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

// Attendance Chart
const attendanceData = @json($attendanceData);
const attendanceCtx = document.getElementById('attendanceChart').getContext('2d');
const attendanceChart = new Chart(attendanceCtx, {
    type: 'line',
    data: {
        labels: attendanceData.monthly_trends.labels,
        datasets: [{
            label: 'Attendance Rate',
            data: attendanceData.monthly_trends.values,
            borderColor: '#4e73df',
            backgroundColor: 'rgba(78, 115, 223, 0.1)',
            tension: 0.3,
            fill: true
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
            y: {
                beginAtZero: false,
                min: 85,
                max: 100,
                title: {
                    display: true,
                    text: 'Attendance %'
                }
            }
        },
        plugins: {
            legend: {
                display: false
            }
        }
    }
});

function exportSchoolHRReport() {
    alert('Exporting detailed HR report for this school...');
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

#performanceChart, #attendanceChart {
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
