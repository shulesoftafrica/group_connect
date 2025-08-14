@extends('layouts.admin')

@section('title', 'School Academic Performance')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('academics.index') }}">Academic Dashboard</a></li>
                    <li class="breadcrumb-item active">{{ $settings['name'] }}</li>
                </ol>
            </nav>
            <h1 class="h3 mb-1 text-gray-800">{{ $settings['name'] }}</h1>
            <p class="text-muted">{{ $settings['location'] }} • {{ $settings['school_type'] }} School</p>
        </div>
        <div class="d-flex gap-2">
            <button class="btn btn-outline-primary" onclick="exportSchoolReport()">
                <i class="fas fa-download me-1"></i> Export Report
            </button>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#interventionModal">
                <i class="fas fa-hands-helping me-1"></i> Plan Intervention
            </button>
        </div>
    </div>

    <!-- School Overview Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Academic Index
                            </div>
                            <div class="h4 mb-0 font-weight-bold text-gray-800">
                                {{ $academicDetails['performance_overview']['academic_index'] }}%
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
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Attendance Rate
                            </div>
                            <div class="h4 mb-0 font-weight-bold text-gray-800">
                                {{ $academicDetails['performance_overview']['attendance_rate'] }}%
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
                                Total Students
                            </div>
                            <div class="h4 mb-0 font-weight-bold text-gray-800">
                                {{ number_format($academicDetails['performance_overview']['total_students']) }}
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
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Pass Rate
                            </div>
                            <div class="h4 mb-0 font-weight-bold text-gray-800">
                                {{ round($academicDetails['performance_overview']['pass_rate'], 1) }}%
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

    <!-- Performance Trends and Subject Analysis -->
    <div class="row mb-4">
        <!-- Performance Trends -->
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Performance Trends</h6>
                </div>
                <div class="card-body">
                    <div class="chart-area">
                        <canvas id="performanceTrendsChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Stats -->
        <div class="col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Quick Stats</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="small font-weight-bold">Performance vs Group Avg</span>
                            <span class="text-success">+5.2%</span>
                        </div>
                        <div class="progress">
                            <div class="progress-bar bg-success" style="width: 85%"></div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="small font-weight-bold">Attendance vs Group Avg</span>
                            <span class="text-warning">-2.1%</span>
                        </div>
                        <div class="progress">
                            <div class="progress-bar bg-warning" style="width: 75%"></div>
                        </div>
                    </div>

                    <hr>

                    <div class="text-center">
                        <div class="mb-2">
                            <span class="h4 font-weight-bold text-primary">{{ $settings['principal_name'] }}</span>
                        </div>
                        <div class="text-muted small">School Principal</div>
                        <div class="mt-3">
                            <button class="btn btn-outline-primary btn-sm">
                                <i class="fas fa-phone me-1"></i> Contact
                            </button>
                            <button class="btn btn-outline-secondary btn-sm">
                                <i class="fas fa-envelope me-1"></i> Email
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Subject Performance and Class Analysis -->
    <div class="row mb-4">
        <!-- Subject Performance -->
        <div class="col-lg-7">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Subject Performance Breakdown</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Subject</th>
                                    <th>Avg Score</th>
                                    <th>Pass Rate</th>
                                    <th>Students</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($academicDetails['subject_breakdown'] as $subject => $data)
                                <tr>
                                    <td class="font-weight-bold">{{ $subject }}</td>
                                    <td>
                                        <span class="badge badge-{{ $data['avg_score'] >= 75 ? 'success' : ($data['avg_score'] >= 60 ? 'warning' : 'danger') }}">
                                            {{ $data['avg_score'] }}%
                                        </span>
                                    </td>
                                    <td>{{ $data['pass_rate'] }}%</td>
                                    <td>{{ $data['students'] }}</td>
                                    <td>
                                        @if($data['avg_score'] >= 75)
                                            <i class="fas fa-check-circle text-success"></i> Good
                                        @elseif($data['avg_score'] >= 60)
                                            <i class="fas fa-exclamation-triangle text-warning"></i> Fair
                                        @else
                                            <i class="fas fa-times-circle text-danger"></i> Needs Help
                                        @endif
                                    </td>
                                    <td>
                                        <div class="dropdown">
                                            <button class="btn btn-sm btn-outline-primary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                                Actions
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li><a class="dropdown-item" href="#"><i class="fas fa-eye me-2"></i>View Details</a></li>
                                                <li><a class="dropdown-item" href="#"><i class="fas fa-users me-2"></i>Student List</a></li>
                                                <li><a class="dropdown-item" href="#"><i class="fas fa-chart-bar me-2"></i>Analytics</a></li>
                                                @if($data['avg_score'] < 75)
                                                <li><hr class="dropdown-divider"></li>
                                                <li><a class="dropdown-item text-warning" href="#"><i class="fas fa-hands-helping me-2"></i>Plan Support</a></li>
                                                @endif
                                            </ul>
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

        <!-- Class Performance -->
        <div class="col-lg-5">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Class Performance</h6>
                </div>
                <div class="card-body">
                    @foreach($academicDetails['class_performance'] as $class => $data)
                    <div class="mb-4">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h6 class="mb-0 font-weight-bold">{{ $class }}</h6>
                            <small class="text-muted">{{ $data['students'] }} students</small>
                        </div>
                        
                        <div class="row">
                            <div class="col-6">
                                <div class="text-center">
                                    <div class="text-primary font-weight-bold">{{ $data['avg_score'] }}%</div>
                                    <div class="text-xs text-muted">Avg Score</div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="text-center">
                                    <div class="text-success font-weight-bold">{{ $data['attendance'] }}%</div>
                                    <div class="text-xs text-muted">Attendance</div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="progress mt-2" style="height: 6px;">
                            <div class="progress-bar" style="width: {{ $data['avg_score'] }}%"></div>
                        </div>
                    </div>
                    @endforeach

                    <div class="mt-3">
                        <button class="btn btn-primary btn-block">
                            <i class="fas fa-chart-bar me-1"></i> Detailed Class Reports
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Contact Information and Actions -->
    <div class="row">
        <div class="col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">School Information</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="small font-weight-bold text-muted">Address</label>
                                <div>{{ $settings['address'] }}</div>
                            </div>
                            <div class="mb-3">
                                <label class="small font-weight-bold text-muted">Phone</label>
                                <div>{{ $settings['contact_phone'] }}</div>
                            </div>
                            <div class="mb-3">
                                <label class="small font-weight-bold text-muted">Email</label>
                                <div>{{ $settings['contact_email'] }}</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="small font-weight-bold text-muted">School Type</label>
                                <div>{{ $settings['school_type'] }}</div>
                            </div>
                            <div class="mb-3">
                                <label class="small font-weight-bold text-muted">Region</label>
                                <div>{{ $settings['region'] }}</div>
                            </div>
                            <div class="mb-3">
                                <label class="small font-weight-bold text-muted">Status</label>
                                <div>
                                    <span class="badge badge-success">{{ ucfirst($settings['status']) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Quick Actions</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <button class="btn btn-outline-primary btn-block">
                                <i class="fas fa-envelope-open-text me-1"></i>
                                Send Message
                            </button>
                        </div>
                        <div class="col-md-6 mb-3">
                            <button class="btn btn-outline-success btn-block">
                                <i class="fas fa-clipboard-check me-1"></i>
                                Schedule Assessment
                            </button>
                        </div>
                        <div class="col-md-6 mb-3">
                            <button class="btn btn-outline-warning btn-block">
                                <i class="fas fa-hands-helping me-1"></i>
                                Plan Intervention
                            </button>
                        </div>
                        <div class="col-md-6 mb-3">
                            <button class="btn btn-outline-info btn-block">
                                <i class="fas fa-file-upload me-1"></i>
                                Upload Resources
                            </button>
                        </div>
                    </div>
                    
                    <hr>
                    
                    <div class="text-center">
                        <a href="{{ route('schools.show', $school->id) }}" class="btn btn-primary">
                            <i class="fas fa-external-link-alt me-1"></i>
                            Full School Dashboard
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Intervention Planning Modal -->
<div class="modal fade" id="interventionModal" tabindex="-1" aria-labelledby="interventionModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="interventionModalLabel">Plan Academic Intervention</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="interventionForm">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="interventionType" class="form-label">Intervention Type</label>
                                <select class="form-select" id="interventionType" required>
                                    <option value="">Select intervention type...</option>
                                    <option value="remedial_classes">Remedial Classes</option>
                                    <option value="teacher_training">Teacher Training</option>
                                    <option value="curriculum_support">Curriculum Support</option>
                                    <option value="student_support">Student Support Program</option>
                                    <option value="resource_provision">Resource Provision</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="priority" class="form-label">Priority Level</label>
                                <select class="form-select" id="priority" required>
                                    <option value="high">High</option>
                                    <option value="medium" selected>Medium</option>
                                    <option value="low">Low</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="targetSubjects" class="form-label">Target Subjects</label>
                        <select class="form-select" id="targetSubjects" multiple>
                            @foreach($academicDetails['subject_breakdown'] as $subject => $data)
                            <option value="{{ $subject }}" {{ $data['avg_score'] < 75 ? 'selected' : '' }}>
                                {{ $subject }} ({{ $data['avg_score'] }}%)
                            </option>
                            @endforeach
                        </select>
                        <div class="form-text">Hold Ctrl/Cmd to select multiple subjects</div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="description" class="form-label">Description & Goals</label>
                        <textarea class="form-control" id="description" rows="4" 
                                  placeholder="Describe the intervention plan and expected outcomes..." required></textarea>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="startDate" class="form-label">Start Date</label>
                                <input type="date" class="form-control" id="startDate" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="endDate" class="form-label">Expected End Date</label>
                                <input type="date" class="form-control" id="endDate" required>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="saveIntervention()">Create Intervention Plan</button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Performance Trends Chart
const ctx = document.getElementById('performanceTrendsChart').getContext('2d');
const performanceTrendsChart = new Chart(ctx, {
    type: 'line',
    data: {
        labels: ['Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug'],
        datasets: [{
            label: 'Academic Performance',
            data: {!! json_encode($academicDetails['trends']['performance']) !!},
            borderColor: 'rgb(75, 192, 192)',
            backgroundColor: 'rgba(75, 192, 192, 0.2)',
            tension: 0.4
        }, {
            label: 'Attendance Rate',
            data: {!! json_encode($academicDetails['trends']['attendance']) !!},
            borderColor: 'rgb(255, 99, 132)',
            backgroundColor: 'rgba(255, 99, 132, 0.2)',
            tension: 0.4
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
            y: {
                beginAtZero: true,
                max: 100
            }
        },
        plugins: {
            legend: {
                position: 'top'
            },
            title: {
                display: true,
                text: 'Last 6 Months Performance Trends'
            }
        }
    }
});

function exportSchoolReport() {
    const button = event.target;
    const originalText = button.innerHTML;
    button.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Generating...';
    button.disabled = true;
    
    setTimeout(() => {
        // Simulate report generation
        showNotification('School report downloaded successfully!', 'success');
        button.innerHTML = originalText;
        button.disabled = false;
    }, 2000);
}

function saveIntervention() {
    const form = document.getElementById('interventionForm');
    const formData = new FormData(form);
    
    if (!form.checkValidity()) {
        form.reportValidity();
        return;
    }
    
    // Simulate saving intervention
    showNotification('Intervention plan created successfully!', 'success');
    bootstrap.Modal.getInstance(document.getElementById('interventionModal')).hide();
    form.reset();
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
    
    setTimeout(() => {
        if (notification.parentNode) {
            notification.parentNode.removeChild(notification);
        }
    }, 5000);
}
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

.btn-block {
    width: 100%;
}

.progress {
    height: 0.5rem;
}

.table-hover tbody tr:hover {
    background-color: rgba(0, 0, 0, 0.05);
}

.card {
    transition: all 0.3s ease;
}

.card:hover {
    transform: translateY(-2px);
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
}

.badge {
    font-size: 0.75rem;
}

@media (max-width: 768px) {
    .chart-area {
        height: 200px;
    }
    
    .btn-block {
        margin-bottom: 0.5rem;
    }
}
</style>
@endpush
