@extends('layouts.admin')

@section('title', 'Operations Dashboard')

@section('content')
<div class="container-fluid px-4">
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">Operations Dashboard</h1>
            <p class="text-muted mb-0">Monitor and manage operational performance across all schools</p>
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

    <!-- Key Performance Indicators -->
    <div class="row g-3 mb-4">
        <!-- Student Attendance KPI -->
        <div class="col-xl-3 col-md-6">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Student Attendance
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $operationalKPIs['student_attendance']['average'] }}%
                                @if($operationalKPIs['student_attendance']['trend'] > 0)
                                    <small class="text-success">
                                        <i class="bi bi-arrow-up"></i> +{{ $operationalKPIs['student_attendance']['trend'] }}%
                                    </small>
                                @else
                                    <small class="text-danger">
                                        <i class="bi bi-arrow-down"></i> {{ $operationalKPIs['student_attendance']['trend'] }}%
                                    </small>
                                @endif
                            </div>
                            <div class="text-xs text-muted mt-1">
                                {{ $operationalKPIs['student_attendance']['schools_below_threshold'] }} schools below threshold
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-people fs-2 text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Staff Attendance KPI -->
        <div class="col-xl-3 col-md-6">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Staff Attendance
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $operationalKPIs['staff_attendance']['average'] }}%
                                @if($operationalKPIs['staff_attendance']['trend'] > 0)
                                    <small class="text-success">
                                        <i class="bi bi-arrow-up"></i> +{{ $operationalKPIs['staff_attendance']['trend'] }}%
                                    </small>
                                @else
                                    <small class="text-danger">
                                        <i class="bi bi-arrow-down"></i> {{ $operationalKPIs['staff_attendance']['trend'] }}%
                                    </small>
                                @endif
                            </div>
                            <div class="text-xs text-muted mt-1">
                                {{ $operationalKPIs['staff_attendance']['schools_below_threshold'] }} schools below threshold
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-person-badge fs-2 text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pending Requests KPI -->
        <div class="col-xl-3 col-md-6">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Pending Requests
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $operationalKPIs['pending_requests']['total'] }}
                                @if($operationalKPIs['pending_requests']['urgent'] > 0)
                                    <small class="text-warning">
                                        <i class="bi bi-exclamation-triangle"></i> {{ $operationalKPIs['pending_requests']['urgent'] }} urgent
                                    </small>
                                @endif
                            </div>
                            <div class="text-xs text-muted mt-1">
                                {{ $operationalKPIs['pending_requests']['overdue'] }} overdue requests
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-clipboard-check fs-2 text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Transport Metrics KPI -->
        <div class="col-xl-3 col-md-6">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                               Active Transport Routes
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $operationalKPIs['transport_metrics']['active_routes'] }}
                                <small class="text-muted">Routes</small>
                            </div>
                            <div class="text-xs text-muted mt-1">
                                {{ $operationalKPIs['transport_metrics']['active_routes'] }} active routes
                                @if($operationalKPIs['transport_metrics']['incidents_today'] > 0)
                                    • {{ $operationalKPIs['transport_metrics']['incidents_today'] }} incidents today
                                @endif
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-bus-front fs-2 text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Secondary KPIs Row -->
    <div class="row g-3 mb-4">
        <!-- Hostel Occupancy -->
        <div class="col-lg-4">
            <div class="card shadow">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">Hostel Occupancy</h6>
                    <i class="bi bi-house"></i>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="text-sm font-weight-bold">Occupancy Rate</span>
                        <span class="badge bg-primary">{{ $operationalKPIs['hostel_occupancy']['occupancy_rate'] }}%</span>
                    </div>
                    <div class="progress mb-3">
                        <div class="progress-bar" role="progressbar" 
                             style="width: {{ $operationalKPIs['hostel_occupancy']['occupancy_rate'] }}%" 
                             aria-valuenow="{{ $operationalKPIs['hostel_occupancy']['occupancy_rate'] }}" 
                             aria-valuemin="0" aria-valuemax="100">
                        </div>
                    </div>
                    <div class="row text-center">
                        <div class="col">
                            <div class="text-xs text-muted text-uppercase">Capacity</div>
                            <div class="font-weight-bold">{{ number_format($operationalKPIs['hostel_occupancy']['total_capacity']) }}</div>
                        </div>
                        <div class="col">
                            <div class="text-xs text-muted text-uppercase">Occupied</div>
                            <div class="font-weight-bold">{{ number_format($operationalKPIs['hostel_occupancy']['current_occupancy']) }}</div>
                        </div>
                        <div class="col">
                            <div class="text-xs text-muted text-uppercase">Maintenance</div>
                            <div class="font-weight-bold text-warning">{{ $operationalKPIs['hostel_occupancy']['maintenance_requests'] }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Library Activity -->
        <div class="col-lg-4">
            <div class="card shadow">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">Library Activity</h6>
                    <i class="bi bi-book"></i>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col">
                            <div class="text-xs text-muted text-uppercase">Total Books</div>
                            <div class="h5 font-weight-bold text-success">{{ $operationalKPIs['library_activity']['books'] }}</div>
                        </div>
                        <div class="col">
                            <div class="text-xs text-muted text-uppercase">Issued Books</div>
                            <div class="h5 font-weight-bold text-warning">{{ $operationalKPIs['library_activity']['issued'] }}</div>
                        </div>
                    </div>
                    <hr>
                    <div class="text-center">
                        <div class="text-xs text-muted text-uppercase">Active Members</div>
                        <div class="h4 font-weight-bold text-primary">{{ number_format($operationalKPIs['library_activity']['active_members']) }}</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Teacher Duties -->
        <div class="col-lg-4">
            <div class="card shadow">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">Teacher on Duty</h6>
                    <i class="bi bi-person-check"></i>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="text-sm font-weight-bold">Compliance Rate</span>
                        <span class="badge bg-success">{{ $operationalKPIs['teacher_duties']['compliance_rate'] }}%</span>
                    </div>
                    <div class="progress mb-3">
                        <div class="progress-bar bg-success" role="progressbar" 
                             style="width: {{ $operationalKPIs['teacher_duties']['compliance_rate'] }}%" 
                             aria-valuenow="{{ $operationalKPIs['teacher_duties']['compliance_rate'] }}" 
                             aria-valuemin="0" aria-valuemax="100">
                        </div>
                    </div>
                    <div class="row text-center">
                        <div class="col">
                            <div class="text-xs text-muted text-uppercase">Assigned</div>
                            <div class="font-weight-bold">{{ $operationalKPIs['teacher_duties']['assigned_today'] }}</div>
                        </div>
                        <div class="col">
                            <div class="text-xs text-muted text-uppercase">Unassigned</div>
                            <div class="font-weight-bold text-danger">{{ $operationalKPIs['teacher_duties']['unassigned'] }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts and Alerts Row -->
    <!-- <div class="row mb-4">
        <div class="col-xl-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">Performance Trends (Last 30 Days)</h6>
                    <div class="dropdown">
                        <button class="btn btn-sm btn-outline-primary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                            <i class="bi bi-gear"></i>
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="#" onclick="updateChart('attendance')">Attendance Only</a></li>
                            <li><a class="dropdown-item" href="#" onclick="updateChart('transport')">Transport Only</a></li>
                            <li><a class="dropdown-item" href="#" onclick="updateChart('both')">Both Metrics</a></li>
                        </ul>
                    </div>
                </div>
                <div class="card-body">
                    <canvas id="performanceTrendsChart" width="100%" height="50"></canvas>
                </div>
            </div>
        </div>

       
        <div class="col-xl-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Operational Alerts</h6>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        @foreach($alertsData['critical'] as $alert)
                        <div class="list-group-item border-left-danger py-3">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h6 class="mb-1 text-danger">
                                        <i class="bi bi-exclamation-triangle me-1"></i>
                                        {{ $alert['school'] }}
                                    </h6>
                                    <p class="mb-1 text-sm">{{ $alert['message'] }}</p>
                                    <small class="text-muted">{{ $alert['timestamp'] }}</small>
                                </div>
                                @if($alert['action_required'])
                                <button class="btn btn-sm btn-outline-danger">Action</button>
                                @endif
                            </div>
                        </div>
                        @endforeach

                        @foreach($alertsData['warnings'] as $alert)
                        <div class="list-group-item border-left-warning py-3">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h6 class="mb-1 text-warning">
                                        <i class="bi bi-exclamation-circle me-1"></i>
                                        {{ $alert['school'] }}
                                    </h6>
                                    <p class="mb-1 text-sm">{{ $alert['message'] }}</p>
                                    <small class="text-muted">{{ $alert['timestamp'] }}</small>
                                </div>
                            </div>
                        </div>
                        @endforeach

                        @foreach($alertsData['info'] as $alert)
                        <div class="list-group-item py-3">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h6 class="mb-1 text-info">
                                        <i class="bi bi-info-circle me-1"></i>
                                        {{ $alert['school'] }}
                                    </h6>
                                    <p class="mb-1 text-sm">{{ $alert['message'] }}</p>
                                    <small class="text-muted">{{ $alert['timestamp'] }}</small>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div> -->

    <!-- Schools Overview -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Schools Operational Status</h6>
            <div class="d-flex gap-2">
                <div class="input-group" style="width: 250px;">
                    <input type="text" class="form-control" placeholder="Search schools..." id="schoolSearch">
                    <button class="btn btn-outline-secondary" type="button">
                        <i class="bi bi-search"></i>
                    </button>
                </div>
                <select class="form-select" style="width: 150px;" id="statusFilter">
                    <option value="">All Status</option>
                    <option value="excellent">Excellent</option>
                    <option value="good">Good</option>
                    <option value="average">Average</option>
                    <option value="needs_attention">Needs Attention</option>
                </select>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover" id="schoolsTable">
                    <thead>
                        <tr>
                            <th>School</th>
                            <th>Region</th>
                            <th>Type</th>
                            <th>Students</th>
                            <th>Staff</th>
                            <th>Attendance</th>
                            <th>Status</th>
                            <th>Last Activity</th>
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
                                            $statusIcon = match($school['operational_status']) {
                                                'excellent' => 'bi-check-circle-fill text-success',
                                                'good' => 'bi-check-circle text-success',
                                                'average' => 'bi-exclamation-circle text-warning',
                                                'needs_attention' => 'bi-x-circle text-danger',
                                                default => 'bi-question-circle text-muted'
                                            };
                                        @endphp
                                        <i class="bi {{ $statusIcon }}"></i>
                                    </div>
                                    <div>
                                        <div class="font-weight-bold">{{ $school['name'] }}</div>
                                        <!-- <small class="text-muted">{{ $school['code'] }}</small> -->
                                    </div>
                                </div>
                            </td>
                            <td>{{ $school['region'] }}</td>
                            <td>
                                <span class="badge bg-light text-dark">{{ $school['type'] }}</span>
                            </td>
                            <td>{{ number_format($school['student_count']) }}</td>
                            <td>{{ $school['staff_count'] }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <span class="me-2">{{ $school['attendance_rate'] }}%</span>
                                    <div class="progress" style="width: 60px; height: 8px;">
                                        <div class="progress-bar 
                                            @if($school['attendance_rate'] >= 90) bg-success
                                            @elseif($school['attendance_rate'] >= 80) bg-warning  
                                            @else bg-danger
                                            @endif"
                                             style="width: {{ $school['attendance_rate'] }}%">
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                @php
                                    $statusClass = match($school['operational_status']) {
                                        'excellent' => 'bg-success',
                                        'good' => 'bg-primary',
                                        'average' => 'bg-warning',
                                        'needs_attention' => 'bg-danger',
                                        default => 'bg-secondary'
                                    };
                                    $statusText = str_replace('_', ' ', ucwords($school['operational_status']));
                                @endphp
                                <span class="badge {{ $statusClass }}">{{ $statusText }}</span>
                            </td>
                            <td>{{ \Carbon\Carbon::parse($school['last_activity'])->diffForHumans() }}</td>
                            <!-- <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('operations.school', $school['id']) }}" 
                                       class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <button class="btn btn-sm btn-outline-secondary" 
                                            onclick="quickActions({{ $school['id'] }})">
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

    <!-- Regional Performance Chart -->
    <!-- <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Regional Performance Overview</h6>
        </div>
        <div class="card-body">
            <canvas id="regionalPerformanceChart" width="100%" height="40"></canvas>
        </div>
    </div> -->
</div>

<!-- Bulk Action Modal -->
<div class="modal fade" id="bulkActionModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Bulk Actions</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="bulkActionForm">
                    <div class="mb-3">
                        <label class="form-label">Select Action</label>
                        <select class="form-select" name="action" required>
                            <option value="">Choose an action...</option>
                            <option value="approve_requests">Approve Pending Requests</option>
                            <option value="push_routines">Push Class Routines</option>
                            <option value="update_settings">Update Operational Settings</option>
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
                    <div id="actionSpecificFields"></div>
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
                <h5 class="modal-title">Export Reports</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="exportForm">
                    <div class="mb-3">
                        <label class="form-label">Report Type</label>
                        <select class="form-select" name="module" required>
                            <option value="all">Complete Operations Report</option>
                            <option value="attendance">Attendance Summary</option>
                            <option value="transport">Transport Report</option>
                            <option value="hostel">Hostel Occupancy</option>
                            <option value="library">Library Activity</option>
                            <option value="teacher_duties">Teacher Duties</option>
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
// Chart.js configurations
const performanceData = @json($performanceData);

// Performance Trends Chart
const trendsCtx = document.getElementById('performanceTrendsChart').getContext('2d');
const performanceTrendsChart = new Chart(trendsCtx, {
    type: 'line',
    data: {
        labels: performanceData.dates,
        datasets: [{
            label: 'Student Attendance (%)',
            data: performanceData.attendance_trend,
            borderColor: '#4e73df',
            backgroundColor: 'rgba(78, 115, 223, 0.1)',
            borderWidth: 2,
            fill: true,
            tension: 0.3
        }, {
            label: 'Transport Punctuality (%)',
            data: performanceData.transport_punctuality,
            borderColor: '#1cc88a',
            backgroundColor: 'rgba(28, 200, 138, 0.1)',
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
                beginAtZero: false,
                min: 70,
                max: 100,
                ticks: {
                    callback: function(value) {
                        return value + '%';
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

// Regional Performance Chart
const regionalCtx = document.getElementById('regionalPerformanceChart').getContext('2d');
const regionalChart = new Chart(regionalCtx, {
    type: 'bar',
    data: {
        labels: Object.keys(performanceData.regional_performance),
        datasets: [{
            label: 'Operational Performance (%)',
            data: Object.values(performanceData.regional_performance),
            backgroundColor: [
                'rgba(78, 115, 223, 0.8)',
                'rgba(28, 200, 138, 0.8)',
                'rgba(54, 185, 204, 0.8)',
                'rgba(246, 194, 62, 0.8)',
                'rgba(231, 74, 59, 0.8)'
            ],
            borderColor: [
                '#4e73df',
                '#1cc88a',
                '#36b9cc',
                '#f6c23e',
                '#e74a3b'
            ],
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
                beginAtZero: false,
                min: 70,
                max: 100,
                ticks: {
                    callback: function(value) {
                        return value + '%';
                    }
                }
            }
        }
    }
});

// Dashboard Functions
function refreshDashboard() {
    location.reload();
}

function updateChart(type) {
    if (type === 'attendance') {
        performanceTrendsChart.data.datasets[1].hidden = true;
        performanceTrendsChart.data.datasets[0].hidden = false;
    } else if (type === 'transport') {
        performanceTrendsChart.data.datasets[0].hidden = true;
        performanceTrendsChart.data.datasets[1].hidden = false;
    } else {
        performanceTrendsChart.data.datasets[0].hidden = false;
        performanceTrendsChart.data.datasets[1].hidden = false;
    }
    performanceTrendsChart.update();
}

function quickActions(schoolId) {
    // Implementation for quick actions on individual schools
    alert('Quick actions for school ID: ' + schoolId);
}

function executeBulkAction() {
    const form = document.getElementById('bulkActionForm');
    const formData = new FormData(form);
    
    // Convert FormData to regular object
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
    
    fetch('/operations/bulk-action', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(data.message);
            bootstrap.Modal.getInstance(document.getElementById('bulkActionModal')).hide();
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
    
    fetch('/operations/export', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(data.message);
            bootstrap.Modal.getInstance(document.getElementById('exportModal')).hide();
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while exporting the report.');
    });
}

// Table filtering and search
document.getElementById('schoolSearch').addEventListener('input', function() {
    const searchTerm = this.value.toLowerCase();
    const table = document.getElementById('schoolsTable');
    const rows = table.getElementsByTagName('tbody')[0].getElementsByTagName('tr');
    
    for (let row of rows) {
        const schoolName = row.cells[0].textContent.toLowerCase();
        const region = row.cells[1].textContent.toLowerCase();
        
        if (schoolName.includes(searchTerm) || region.includes(searchTerm)) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    }
});

document.getElementById('statusFilter').addEventListener('change', function() {
    const filterValue = this.value;
    const table = document.getElementById('schoolsTable');
    const rows = table.getElementsByTagName('tbody')[0].getElementsByTagName('tr');
    
    for (let row of rows) {
        if (!filterValue) {
            row.style.display = '';
            continue;
        }
        
        const statusBadge = row.querySelector('.badge');
        const statusText = statusBadge.textContent.toLowerCase().replace(/\s+/g, '_');
        
        if (statusText === filterValue) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    }
});

// Export modal - select all functionality
document.getElementById('selectAllSchools').addEventListener('change', function() {
    const schoolCheckboxes = document.querySelectorAll('.school-checkbox');
    schoolCheckboxes.forEach(checkbox => {
        checkbox.checked = this.checked;
    });
});
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
    /* background-color: var(--background-color, #f8f9fc); */
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

.canvas-container {
    position: relative;
    height: 300px;
}

#performanceTrendsChart {
    height: 300px !important;
}

#regionalPerformanceChart {
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
    .col-xl-3 {
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
