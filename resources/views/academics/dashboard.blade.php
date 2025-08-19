@extends('layouts.admin')

@section('title', 'Academic Dashboard')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1 text-gray-800">Academic Dashboard</h1>
            <p class="text-muted">Group-wide academic performance overview and insights</p>
        </div>
        <!-- <div class="d-flex gap-2">
            <button class="btn btn-outline-primary" onclick="exportReport('excel')">
                <i class="fas fa-file-excel me-1"></i> Export Excel
            </button>
            <button class="btn btn-outline-danger" onclick="exportReport('pdf')">
                <i class="fas fa-file-pdf me-1"></i> Export PDF
            </button>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#bulkActionModal">
                <i class="fas fa-cogs me-1"></i> Bulk Actions
            </button>
        </div> -->
    </div>

    <!-- Academic KPIs Row -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Students
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ number_format($totalStudents) }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Total Exams Conducted
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $totalExamsConducted }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user-check fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Average Performance
                            </div>
                            <div class="row no-gutters align-items-center">
                                <div class="col-auto">
                                    <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800">
                                        {{ number_format($averageMark, 2) }}%
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="progress progress-sm mr-2">
                                        <div class="progress-bar bg-info" role="progressbar" aria-valuemin="0"
                                             aria-valuemax="100"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-chart-line fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Total Subjects
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $totalSubjects}}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-graduation-cap fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts and Performance Row -->
    <div class="row mb-4">
        <!-- Performance Trends Chart -->
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">Academic Performance Trends</h6>
                    <div class="dropdown">
                        <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                            <i class="fas fa-filter me-1"></i> Filter
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="#">Last 6 Months</a></li>
                            <li><a class="dropdown-item" href="#">This Year</a></li>
                            <li><a class="dropdown-item" href="#">Last Year</a></li>
                        </ul>
                    </div>
                </div>
                <div class="card-body">
                    <div class="chart-area">
                        <canvas id="performanceTrendsChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Regional Performance -->
        <div class="col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Performance by Region</h6>
                </div>
                <div class="card-body">
                    <div class="chart-pie pt-4 pb-2">
                        <canvas id="regionalPerformanceChart"></canvas>
                    </div>
                    <div class="mt-4 text-center small">
                        @foreach($performanceData['performance_by_region'] as $region => $data)
                        <span class="mr-2">
                            <i class="fas fa-circle text-primary"></i> {{ $region }}
                        </span>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Schools Performance and Alerts Row -->
    <div class="row mb-4">
        <!-- Top & Bottom Performing Schools -->
        <!-- <div class="col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">School Performance Overview</h6>
                </div>
                <div class="card-body"> -->
                    <!-- Top Performing Schools -->
                    <!-- <h6 class="text-success mb-3">
                        <i class="fas fa-trophy me-2"></i>Top Performing Schools
                    </h6>
                    @foreach($academicKPIs['top_performing_schools'] as $school)
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <div class="font-weight-bold">{{ $school->schoolSetting->sname }}</div>
                            <small class="text-muted">{{ $school->schoolSetting->address }}</small>
                        </div>
                        <div class="text-right">
                            <span class="badge badge-success">{{ $school->schoolSetting->name }}%</span>
                        </div>
                    </div>
                    @endforeach -->

                    <!-- <hr> -->

                    <!-- Bottom Performing Schools -->
                    <!-- <h6 class="text-warning mb-3">
                        <i class="fas fa-exclamation-triangle me-2"></i>Schools Needing Support
                    </h6>
                    @foreach($academicKPIs['bottom_performing_schools'] as $school)
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <div class="font-weight-bold">{{ $school->schoolSetting->sname }}</div>
                            <small class="text-muted">{{ $school->schoolSetting->location }}</small>
                        </div>
                        <div class="text-right">
                            <span class="badge badge-warning">{{ $school->schoolSetting->academic_index }}%</span>
                            <div>
                                <a href="#" class="btn btn-sm btn-outline-primary mt-1">
                                    <i class="fas fa-hands-helping"></i> Support
                                </a>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div> -->
            <!-- </div>
        </div> -->

        <!-- Academic Alerts -->
        <!-- <div class="col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">Academic Alerts & Notifications</h6>
                    <span class="badge badge-danger">{{ count($alerts) }}</span>
                </div>
                <div class="card-body" style="max-height: 400px; overflow-y: auto;">
                    @forelse($alerts as $alert)
                    <div class="alert alert-{{ $alert['type'] }} alert-dismissible fade show mb-2" role="alert">
                        <div class="d-flex align-items-start">
                            <div class="me-3">
                                <i class="{{ $alert['icon'] }}"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="alert-heading mb-1">{{ $alert['title'] }}</h6>
                                <p class="mb-1">{{ $alert['message'] }}</p>
                                <small class="text-muted">School: {{ $alert['school'] }}</small>
                                <div class="mt-2">
                                    <button class="btn btn-sm btn-outline-{{ $alert['type'] }}">
                                        {{ $alert['action'] }}
                                    </button>
                                </div>
                            </div>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                    @empty
                    <div class="text-center text-muted">
                        <i class="fas fa-check-circle fa-3x mb-3"></i>
                        <p>No academic alerts at this time.</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div> -->
    </div>

    <!-- Subject Performance and Teacher Stats -->
    <div class="row mb-4">
        <!-- Subject Performance Analysis -->
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Subject Performance Analysis</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>School</th>
                                    <th class="text-center">Average</th>
                                    <th class="text-center">Target</th>
                                    <th class="text-center">Last Year</th>
                                    <th class="text-center">YoY</th>
                                    <th>Progress</th>
                                    <!-- <th class="text-end">Actions</th> -->
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($academicKPIs['schools_performance'] as $school)
                                    @php
                                        $name   = $school['schema_name'] ?? ($school->schema_name ?? 'Unknown School');
                                        $avg    = (float) ($school['average'] ?? ($school->average ?? 0));
                                        $target = (float) ($school['target'] ?? ($school->target ?? 0));
                                        $last   = (float) ($school['last_year_average'] ?? ($school->last_year_average ?? 0));
                                        $yoy    = (float) ($school['yoy_growth_percent'] ?? ($school->yoy_growth_percent ?? 0));

                                        // Badge color: success if meeting/exceeding target, warning if within 90% of target, danger otherwise
                                        $badgeClass = $avg >= $target
                                            ? 'success'
                                            : ($target > 0 && $avg >= ($target * 0.9) ? 'success' : 'danger');

                                        // Trend icon
                                        $trendIcon = $yoy > 0 ? 'fa-arrow-up text-success' : ($yoy < 0 ? 'fa-arrow-down text-danger' : 'fa-minus text-muted');

                                        // Progress width relative to target (cap at 100)
                                        $progressPct = $target > 0 ? min(100, ($avg / $target) * 100) : min(100, $avg);
                                    @endphp

                                    <tr>
                                        <td class="font-weight-bold">{{ $name }}</td>

                                        <td class="text-center">
                                            <span class="badge alert-{{ $badgeClass }}">
                                                {{ number_format($avg, 1) }}%
                                            </span>
                                        </td>

                                        <td class="text-center">
                                            {{ number_format($target, 1) }}%
                                        </td>

                                        <td class="text-center">
                                            {{ number_format($last, 1) }}%
                                        </td>

                                        <td class="text-center">
                                            <i class="fas {{ $trendIcon }}" aria-hidden="true"></i>
                                            <span class="ms-1">{{ number_format($yoy, 1) }}%</span>
                                        </td>

                                        <td style="min-width:180px;">
                                            <div class="progress" style="height: 0.75rem;">
                                                <div class="progress-bar bg-{{ $badgeClass }}" role="progressbar"
                                                     style="width: {{ $progressPct }}%"
                                                     aria-valuenow="{{ $progressPct }}" aria-valuemin="0" aria-valuemax="100">
                                                </div>
                                            </div>
                                            <small class="text-muted">{{ number_format($progressPct, 0) }}% of target</small>
                                        </td>

                                        <!-- <td class="text-end">
                                            <button class="btn btn-sm btn-outline-primary" data-school="{{ $name }}" onclick="viewSchoolDetail(this.dataset.school)">
                                                <i class="fas fa-eye"></i> View
                                            </button>
                                        </td> -->
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Teacher Performance Stats -->
        <div class="col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Teacher Performance</h6>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-6">
                            <div class="text-center">
                                <div class="h4 font-weight-bold text-primary">{{ $academicKPIs['teacher_performance']['total_teachers'] }}</div>
                                <div class="text-xs text-uppercase text-muted">Total Teachers</div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="text-center">
                                <div class="h4 font-weight-bold text-success">{{ $academicKPIs['teacher_performance']['attendance_rate'] }}%</div>
                                <div class="text-xs text-uppercase text-muted">Attendance Rate</div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <div class="small mb-1">Average Workload</div>
                        <div class="progress">
                            <div class="progress-bar" role="progressbar" style="width: {{ $academicKPIs['teacher_performance']['avg_workload'] * 5 }}%">
                                {{ $academicKPIs['teacher_performance']['avg_workload'] }} subjects/teacher
                            </div>
                        </div>
                    </div>

                    <hr>

                    <div class="row text-center">
                        <div class="col-6">
                            <div class="text-success font-weight-bold">{{ $academicKPIs['teacher_performance']['high_performers'] }}</div>
                            <div class="text-xs text-muted">High Performers</div>
                        </div>
                        <div class="col-6">
                            <div class="text-warning font-weight-bold">{{ $academicKPIs['teacher_performance']['teachers_needing_support'] }}</div>
                            <div class="text-xs text-muted">Need Support</div>
                        </div>
                    </div>

                    <!-- <div class="mt-3">
                        <button class="btn btn-primary btn-block">
                            <i class="fas fa-chalkboard-teacher me-1"></i> Teacher Reports
                        </button>
                    </div> -->

                    
                </div>

<div class="card shadow mb-12">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Subject Insights</h6>
    </div>
    <div class="card-body p-0">
        <div class="col-12 col-lg-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Top 5 Poor Performing Subjects</h6>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-sm mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Subject</th>
                                    <th class="text-center">Average</th>
                                    <th class="text-center">Schools</th>
                                    <th style="width:120px"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $poorList = $academicKPIs['poor_performing_subjects'] ?? ($poorSubjects ?? []);
                                    $poorSlice = is_array($poorList) ? array_slice($poorList, 0, 5) : (is_object($poorList) ? array_slice((array)$poorList, 0, 5) : []);
                                @endphp

                                @forelse($poorSlice as $subj)
                                    @php
                                        $name = $subj['name'] ?? ($subj->name ?? ($subj['subject'] ?? 'Unknown Subject'));
                                        $avg  = (float) ($subj['average'] ?? $subj->avg ?? $subj['average_score'] ?? 0);
                                        $schoolsAffected = $subj['schools'] ?? ($subj->affected_schools ?? []);
                                        $schoolsCount = is_array($schoolsAffected) ? count($schoolsAffected) : (is_object($schoolsAffected) ? count((array)$schoolsAffected) : ($subj['school_count'] ?? ($subj->schoolCount ?? 0)));
                                        $barClass = $avg < 40 ? 'danger' : ($avg < 60 ? 'warning' : 'secondary');
                                        $barWidth = min(100, max(0, $avg));
                                    @endphp

                                    <tr>
                                        <td class="align-middle">{{ $name }}</td>
                                        <td class="text-center align-middle">{{ number_format($avg, 1) }}%</td>
                                        <td class="text-center align-middle">{{ $schoolsCount }}</td>
                                        <td class="align-middle">
                                            <div class="progress" style="height:0.6rem;">
                                                <div class="progress-bar bg-{{ $barClass }}" role="progressbar"
                                                     style="width: {{ $barWidth }}%" aria-valuenow="{{ $barWidth }}" aria-valuemin="0" aria-valuemax="100"></div>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-muted py-3">No data for poor performing subjects.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- <div class="card-footer text-end">
                    <a href="#" class="btn btn-sm btn-outline-primary" onclick="showNotification('Opening subject diagnostics...', 'info')">
                        View Details
                    </a>
                </div> -->
            </div>
        </div>
                
            </div>
            
        </div>
       
    </div>

    <!-- Recent Academic Activities -->
    <!-- <div class="row">
        <div class="col-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Recent Academic Activities</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        @foreach($recentActivities as $activity)
                        <div class="col-md-6 col-lg-3 mb-3">
                            <div class="card border-left-{{ $activity['status'] === 'completed' ? 'success' : ($activity['status'] === 'pending' ? 'warning' : 'info') }} h-100">
                                <div class="card-body">
                                    <div class="d-flex align-items-center mb-2">
                                        <div class="me-3">
                                            <i class="{{ $activity['icon'] }} fa-2x text-gray-400"></i>
                                        </div>
                                        <div>
                                            <div class="font-weight-bold">{{ $activity['title'] }}</div>
                                            <div class="text-xs text-muted">{{ $activity['time'] }}</div>
                                        </div>
                                    </div>
                                    <p class="small text-muted mb-0">{{ $activity['description'] }}</p>
                                    <span class="badge badge-{{ $activity['status'] === 'completed' ? 'success' : ($activity['status'] === 'pending' ? 'warning' : 'info') }} mt-2">
                                        {{ ucfirst($activity['status']) }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div> -->
</div>

<!-- Bulk Actions Modal -->
<div class="modal fade" id="bulkActionModal" tabindex="-1" aria-labelledby="bulkActionModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="bulkActionModalLabel">Academic Bulk Actions</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label for="actionType" class="form-label">Select Action</label>
                    <select class="form-select" id="actionType">
                        <option value="">Choose an action...</option>
                        <option value="push_policy">Push Academic Policy</option>
                        <option value="schedule_assessment">Schedule Group Assessment</option>
                        <option value="send_communication">Send Communication</option>
                        <option value="update_grading">Update Grading Rules</option>
                    </select>
                </div>
                
                <div class="mb-3">
                    <label for="targetSchools" class="form-label">Target Schools</label>
                    <select class="form-select" id="targetSchools" multiple>
                        @foreach($schools as $school)
                        <option value="{{ $school->id }}">{{ $school->settings['name'] ?? 'Unknown School' }}</option>
                        @endforeach
                    </select>
                    <div class="form-text">Hold Ctrl/Cmd to select multiple schools</div>
                </div>

                <div class="mb-3">
                    <label for="actionDetails" class="form-label">Details</label>
                    <textarea class="form-control" id="actionDetails" rows="3" placeholder="Enter action details..."></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="executeBulkAction()">Execute Action</button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Performance Trends Chart
const performanceCtx = document.getElementById('performanceTrendsChart').getContext('2d');

// Map short month names to full names for display
const monthMap = {
    Jan: 'January', Feb: 'February', Mar: 'March', Apr: 'April',
    May: 'May', Jun: 'June', Jul: 'July', Aug: 'August',
    Sep: 'September', Oct: 'October', Nov: 'November', Dec: 'December'
};

// Get trends data from backend (should be in reverse order, Jan to Dec)
const trends = {!! json_encode($performanceData['attendance_trends']) !!};

// Sort trends by month order (Jan to Dec)
const monthOrder = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
const trendsSorted = monthOrder.map(m => trends.find(t => t.month === m) || {month: m, performance: null});

// Prepare labels and data
const labels = trendsSorted.map(item => monthMap[item.month]);
const data = trendsSorted.map(item => item.performance !== null ? item.performance : null);

const performanceTrendsChart = new Chart(performanceCtx, {
    type: 'line',
    data: {
        labels: labels,
        datasets: [{
            label: 'Academic Performance (%)',
            data: data,
            borderColor: 'rgb(75, 192, 192)',
            backgroundColor: 'rgba(75, 192, 192, 0.2)',
            tension: 0.3,
            spanGaps: false,
            pointRadius: 4,
            pointHoverRadius: 6,
            fill: true
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
            y: {
                beginAtZero: true,
                max: 100,
                title: {
                    display: true,
                    text: 'Performance (%)'
                }
            }
        },
        plugins: {
            legend: {
                display: true,
                position: 'top'
            },
            tooltip: {
                enabled: true
            }
        }
    }
});


// Regional Performance Chart
const regionalCtx = document.getElementById('regionalPerformanceChart').getContext('2d');
const regionalPerformanceChart = new Chart(regionalCtx, {
    type: 'doughnut',
    data: {
        labels: {!! json_encode(array_keys($performanceData['performance_by_region'])) !!},
        datasets: [{
            data: {!! json_encode(array_column($performanceData['performance_by_region'], 'total_students')) !!},
            backgroundColor: [
                '#4e73df',
                '#1cc88a', 
                '#36b9cc',
                '#f6c23e',
                '#e74a3b'
            ],
            hoverBackgroundColor: [
                '#2e59d9',
                '#17a673',
                '#2c9faf',
                '#f4b619',
                '#e02424'
            ]
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'bottom'
            }
        }
    }
});

// Functions
function exportReport(format) {
    // Show loading
    const button = event.target;
    const originalText = button.innerHTML;
    button.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Generating...';
    button.disabled = true;
    
    fetch(`/academics/export?format=${format}&type=overview`, {
        method: 'GET',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Create download link
            const link = document.createElement('a');
            link.href = data.download_url;
            link.download = `academic-report-${new Date().toISOString().split('T')[0]}.${format === 'excel' ? 'xlsx' : 'pdf'}`;
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
            
            // Show success message
            showNotification('Report downloaded successfully!', 'success');
        } else {
            showNotification('Failed to generate report', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('Error generating report', 'error');
    })
    .finally(() => {
        // Reset button
        button.innerHTML = originalText;
        button.disabled = false;
    });
}

function viewSubjectDetail(subject) {
    // Implement subject detail view
    console.log('Viewing details for subject:', subject);
    showNotification(`Opening ${subject} performance details...`, 'info');
}

function executeBulkAction() {
    const actionType = document.getElementById('actionType').value;
    const targetSchools = Array.from(document.getElementById('targetSchools').selectedOptions).map(option => option.value);
    const details = document.getElementById('actionDetails').value;
    
    if (!actionType || targetSchools.length === 0) {
        showNotification('Please select an action and target schools', 'warning');
        return;
    }
    
    // Close modal
    bootstrap.Modal.getInstance(document.getElementById('bulkActionModal')).hide();
    
    // Show success message
    showNotification(`${actionType.replace('_', ' ').toUpperCase()} executed for ${targetSchools.length} schools`, 'success');
}

function showNotification(message, type) {
    const alertClass = type === 'success' ? 'alert-success' : 
                     type === 'error' ? 'alert-danger' : 
                     type === 'warning' ? 'alert-warning' : 'alert-info';
    
    const notification = document.createElement('div');
    notification.className = `alert ${alertClass} alert-dismissible fade show position-fixed`;
    notification.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
    notification.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    `;
    
    document.body.appendChild(notification);
    
    // Auto-remove after 5 seconds
    setTimeout(() => {
        if (notification.parentNode) {
            notification.parentNode.removeChild(notification);
        }
    }, 5000);
}

// Auto-refresh data every 5 minutes
setInterval(() => {
    console.log('Auto-refreshing academic data...');
    // Implement auto-refresh logic here
}, 300000);
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

.chart-area {
    position: relative;
    height: 300px;
}

.chart-pie {
    position: relative;
    height: 200px;
}

.progress-sm {
    height: 0.5rem;
}

.badge {
    font-size: 0.75rem;
}

.alert-dismissible .btn-close {
    padding: 0.75rem 1.25rem;
}

.card {
    transition: all 0.3s ease;
}

.card:hover {
    transform: translateY(-2px);
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
}

.table-hover tbody tr:hover {
    background-color: rgba(0, 0, 0, 0.05);
}

.btn-block {
    width: 100%;
}

.position-fixed {
    position: fixed !important;
}

@media (max-width: 768px) {
    .chart-area {
        height: 200px;
    }
    
    .chart-pie {
        height: 150px;
    }
}
</style>
@endpush
