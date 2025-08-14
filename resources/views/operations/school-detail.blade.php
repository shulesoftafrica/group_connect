@extends('layouts.admin')

@section('title', 'School Operations Detail - ' . $school->settings['school_name'] ?? 'Unknown School')

@section('content')
<div class="container-fluid px-4">
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('operations.index') }}">Operations</a></li>
                    <li class="breadcrumb-item active">{{ $school->settings['school_name'] ?? 'Unknown School' }}</li>
                </ol>
            </nav>
            <h1 class="h3 mb-0 text-gray-800">{{ $school->settings['school_name'] ?? 'Unknown School' }}</h1>
            <p class="text-muted mb-0">Detailed operational analysis and management</p>
        </div>
        <div class="d-flex gap-2">
            <button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#interventionModal">
                <i class="bi bi-lightning me-1"></i> Quick Actions
            </button>
            <button class="btn btn-outline-success" onclick="exportSchoolReport()">
                <i class="bi bi-download me-1"></i> Export Report
            </button>
            <a href="{{ route('operations.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left me-1"></i> Back to Dashboard
            </a>
        </div>
    </div>

    <!-- School Info Card -->
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
                            <label class="text-muted small">School Type</label>
                            <div class="font-weight-bold">{{ $school->settings['school_type'] ?? 'Primary' }}</div>
                        </div>
                        <div class="col-sm-6 mb-3">
                            <label class="text-muted small">Total Students</label>
                            <div class="font-weight-bold">{{ number_format($school->settings['total_students'] ?? 0) }}</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="text-center">
                        <div class="h5 mb-1">Operational Score</div>
                        <div class="h2 text-primary">{{ rand(75, 95) }}%</div>
                        <div class="progress mx-auto" style="width: 80%;">
                            <div class="progress-bar bg-primary" style="width: {{ rand(75, 95) }}%"></div>
                        </div>
                        <small class="text-muted">Overall Performance</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Key Metrics Row -->
    <div class="row g-3 mb-4">
        <!-- Attendance Metrics -->
        <div class="col-lg-6">
            <div class="card shadow h-100">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="bi bi-people me-2"></i>Attendance Overview
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-6">
                            <div class="border-end pe-3">
                                <h6 class="text-muted">Student Attendance</h6>
                                <div class="d-flex align-items-center mb-2">
                                    <div class="me-3">
                                        <div class="h4 mb-0 text-primary">{{ $attendanceData['student_attendance']['today'] }}%</div>
                                        <small class="text-muted">Today</small>
                                    </div>
                                    <div class="progress flex-grow-1" style="height: 10px;">
                                        <div class="progress-bar bg-primary" 
                                             style="width: {{ $attendanceData['student_attendance']['today'] }}%"></div>
                                    </div>
                                </div>
                                <div class="row text-center">
                                    <div class="col">
                                        <div class="text-sm text-muted">Week Avg</div>
                                        <div class="font-weight-bold">{{ $attendanceData['student_attendance']['this_week'] }}%</div>
                                    </div>
                                    <div class="col">
                                        <div class="text-sm text-muted">Month Avg</div>
                                        <div class="font-weight-bold">{{ $attendanceData['student_attendance']['this_month'] }}%</div>
                                    </div>
                                </div>
                                <hr>
                                <div class="text-sm">
                                    <span class="text-danger">{{ $attendanceData['student_attendance']['absent_today'] }} absent today</span><br>
                                    <span class="text-warning">{{ $attendanceData['student_attendance']['chronic_absentees'] }} chronic absentees</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="ps-3">
                                <h6 class="text-muted">Staff Attendance</h6>
                                <div class="d-flex align-items-center mb-2">
                                    <div class="me-3">
                                        <div class="h4 mb-0 text-success">{{ $attendanceData['staff_attendance']['today'] }}%</div>
                                        <small class="text-muted">Today</small>
                                    </div>
                                    <div class="progress flex-grow-1" style="height: 10px;">
                                        <div class="progress-bar bg-success" 
                                             style="width: {{ $attendanceData['staff_attendance']['today'] }}%"></div>
                                    </div>
                                </div>
                                <div class="row text-center">
                                    <div class="col">
                                        <div class="text-sm text-muted">Week Avg</div>
                                        <div class="font-weight-bold">{{ $attendanceData['staff_attendance']['this_week'] }}%</div>
                                    </div>
                                    <div class="col">
                                        <div class="text-sm text-muted">Month Avg</div>
                                        <div class="font-weight-bold">{{ $attendanceData['staff_attendance']['this_month'] }}%</div>
                                    </div>
                                </div>
                                <hr>
                                <div class="text-sm">
                                    <span class="text-danger">{{ $attendanceData['staff_attendance']['absent_today'] }} absent today</span><br>
                                    <span class="text-info">{{ $attendanceData['staff_attendance']['on_leave'] }} on leave</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Transport & Hostel -->
        <div class="col-lg-6">
            <div class="card shadow h-100">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="bi bi-bus-front me-2"></i>Transport & Accommodation
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-6">
                            <div class="border-end pe-3">
                                <h6 class="text-muted">Transport</h6>
                                <div class="row text-center mb-3">
                                    <div class="col">
                                        <div class="text-sm text-muted">Routes</div>
                                        <div class="h5 mb-0 text-info">{{ $transportData['routes'] }}</div>
                                    </div>
                                    <div class="col">
                                        <div class="text-sm text-muted">Vehicles</div>
                                        <div class="h5 mb-0 text-info">{{ $transportData['vehicles'] }}</div>
                                    </div>
                                </div>
                                <div class="mb-2">
                                    <div class="d-flex justify-content-between">
                                        <span class="text-sm">Punctuality</span>
                                        <span class="font-weight-bold">{{ $transportData['punctuality_rate'] }}%</span>
                                    </div>
                                    <div class="progress" style="height: 6px;">
                                        <div class="progress-bar bg-info" 
                                             style="width: {{ $transportData['punctuality_rate'] }}%"></div>
                                    </div>
                                </div>
                                <div class="text-sm">
                                    <span class="text-warning">{{ $transportData['incidents_this_month'] }} incidents this month</span><br>
                                    <span class="text-danger">{{ $transportData['maintenance_due'] }} vehicles need maintenance</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="ps-3">
                                <h6 class="text-muted">Hostel</h6>
                                <div class="text-center mb-3">
                                    <div class="h5 mb-0">{{ $hostelData['occupancy_rate'] }}%</div>
                                    <div class="text-sm text-muted">Occupancy Rate</div>
                                    <div class="progress mx-auto mt-2" style="width: 80%;">
                                        <div class="progress-bar bg-warning" 
                                             style="width: {{ $hostelData['occupancy_rate'] }}%"></div>
                                    </div>
                                </div>
                                <div class="row text-center">
                                    <div class="col">
                                        <div class="text-sm text-muted">Capacity</div>
                                        <div class="font-weight-bold">{{ $hostelData['capacity'] }}</div>
                                    </div>
                                    <div class="col">
                                        <div class="text-sm text-muted">Occupied</div>
                                        <div class="font-weight-bold">{{ $hostelData['occupied'] }}</div>
                                    </div>
                                </div>
                                <hr>
                                <div class="text-sm">
                                    <span class="text-warning">{{ $hostelData['maintenance_requests'] }} maintenance requests</span><br>
                                    <span class="text-info">{{ $hostelData['security_incidents'] }} security incidents</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Operational Details -->
    <div class="row mb-4">
        <!-- Class Routines & Requests -->
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="bi bi-calendar3 me-2"></i>Class Routines & Requests
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="text-muted">Class Routines</h6>
                            <div class="mb-3">
                                <div class="d-flex justify-content-between">
                                    <span>Total Classes</span>
                                    <span class="font-weight-bold">{{ $schoolOperationalData['routines']['total_classes'] }}</span>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <span>Scheduled Today</span>
                                    <span class="font-weight-bold">{{ $schoolOperationalData['routines']['scheduled_today'] }}</span>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <span>Conflicts</span>
                                    <span class="font-weight-bold text-warning">{{ $schoolOperationalData['routines']['conflicts'] }}</span>
                                </div>
                            </div>
                            <div class="mb-2">
                                <div class="d-flex justify-content-between">
                                    <span class="text-sm">Completion Rate</span>
                                    <span class="font-weight-bold">{{ $schoolOperationalData['routines']['completion_rate'] }}%</span>
                                </div>
                                <div class="progress" style="height: 6px;">
                                    <div class="progress-bar bg-success" 
                                         style="width: {{ $schoolOperationalData['routines']['completion_rate'] }}%"></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted">Operational Requests</h6>
                            <div class="mb-3">
                                <div class="d-flex justify-content-between">
                                    <span>Pending</span>
                                    <span class="font-weight-bold text-warning">{{ $schoolOperationalData['requests']['pending'] }}</span>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <span>Approved</span>
                                    <span class="font-weight-bold text-success">{{ $schoolOperationalData['requests']['approved'] }}</span>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <span>Rejected</span>
                                    <span class="font-weight-bold text-danger">{{ $schoolOperationalData['requests']['rejected'] }}</span>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <span>Overdue</span>
                                    <span class="font-weight-bold text-danger">{{ $schoolOperationalData['requests']['overdue'] }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col">
                            <div class="d-flex gap-2">
                                <button class="btn btn-sm btn-primary">Approve Pending</button>
                                <button class="btn btn-sm btn-outline-primary">Upload Routines</button>
                                <button class="btn btn-sm btn-outline-secondary">View Details</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Library & Calendar -->
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="bi bi-book me-2"></i>Library & Calendar
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="text-muted">Library Statistics</h6>
                            <div class="row text-center mb-3">
                                <div class="col">
                                    <div class="text-sm text-muted">Total Books</div>
                                    <div class="h6 mb-0">{{ number_format($libraryData['total_books']) }}</div>
                                </div>
                                <div class="col">
                                    <div class="text-sm text-muted">Issued</div>
                                    <div class="h6 mb-0 text-success">{{ $libraryData['books_issued'] }}</div>
                                </div>
                                <div class="col">
                                    <div class="text-sm text-muted">Overdue</div>
                                    <div class="h6 mb-0 text-warning">{{ $libraryData['overdue_books'] }}</div>
                                </div>
                            </div>
                            <div class="mb-2">
                                <div class="text-sm text-muted">Active Members: {{ number_format($libraryData['active_members']) }}</div>
                                <div class="text-sm text-muted">Popular Category: {{ $libraryData['most_popular_category'] }}</div>
                                <div class="text-sm text-muted">New Acquisitions: {{ $libraryData['new_acquisitions'] }}</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted">Upcoming Events</h6>
                            <div class="list-group list-group-flush">
                                @foreach($calendarData['upcoming_events'] as $event)
                                <div class="list-group-item px-0 py-2">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <div class="font-weight-bold">{{ $event['title'] }}</div>
                                            <small class="text-muted">{{ \Carbon\Carbon::parse($event['date'])->format('M d, Y') }}</small>
                                        </div>
                                        <span class="badge bg-{{ $event['type'] === 'academic' ? 'primary' : 'info' }}">
                                            {{ ucfirst($event['type']) }}
                                        </span>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Compliance & Alerts -->
        <div class="col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="bi bi-shield-check me-2"></i>Compliance Status
                    </h6>
                </div>
                <div class="card-body">
                    @foreach($schoolOperationalData['compliance'] as $area => $score)
                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-1">
                            <span class="text-sm">{{ str_replace('_', ' ', ucfirst($area)) }}</span>
                            <span class="font-weight-bold">{{ $score }}%</span>
                        </div>
                        <div class="progress" style="height: 6px;">
                            <div class="progress-bar 
                                @if($score >= 90) bg-success
                                @elseif($score >= 80) bg-warning
                                @else bg-danger
                                @endif" 
                                 style="width: {{ $score }}%"></div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="bi bi-lightning me-2"></i>Quick Actions
                    </h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <button class="btn btn-primary btn-sm" onclick="sendMessage()">
                            <i class="bi bi-chat-dots me-1"></i> Send Message
                        </button>
                        <button class="btn btn-outline-primary btn-sm" onclick="scheduleVisit()">
                            <i class="bi bi-calendar-plus me-1"></i> Schedule Visit
                        </button>
                        <button class="btn btn-outline-secondary btn-sm" onclick="viewReports()">
                            <i class="bi bi-file-text me-1"></i> View Full Reports
                        </button>
                        <button class="btn btn-outline-info btn-sm" onclick="updateSettings()">
                            <i class="bi bi-gear me-1"></i> Update Settings
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Intervention Planning Modal -->
<div class="modal fade" id="interventionModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Quick Actions - {{ $school->settings['school_name'] ?? 'School' }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <h6>Operational Actions</h6>
                        <div class="list-group">
                            <button type="button" class="list-group-item list-group-item-action">
                                <i class="bi bi-people me-2"></i> Review Attendance Issues
                            </button>
                            <button type="button" class="list-group-item list-group-item-action">
                                <i class="bi bi-bus-front me-2"></i> Update Transport Routes
                            </button>
                            <button type="button" class="list-group-item list-group-item-action">
                                <i class="bi bi-house me-2"></i> Manage Hostel Capacity
                            </button>
                            <button type="button" class="list-group-item list-group-item-action">
                                <i class="bi bi-book me-2"></i> Library Management
                            </button>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <h6>Administrative Actions</h6>
                        <div class="list-group">
                            <button type="button" class="list-group-item list-group-item-action">
                                <i class="bi bi-calendar3 me-2"></i> Update Class Routines
                            </button>
                            <button type="button" class="list-group-item list-group-item-action">
                                <i class="bi bi-clipboard-check me-2"></i> Process Requests
                            </button>
                            <button type="button" class="list-group-item list-group-item-action">
                                <i class="bi bi-person-badge me-2"></i> Staff Assignments
                            </button>
                            <button type="button" class="list-group-item list-group-item-action">
                                <i class="bi bi-gear me-2"></i> System Settings
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
function exportSchoolReport() {
    alert('Exporting detailed school report...');
}

function sendMessage() {
    alert('Opening messaging interface...');
}

function scheduleVisit() {
    alert('Opening visit scheduling...');
}

function viewReports() {
    alert('Loading full reports...');
}

function updateSettings() {
    alert('Opening settings panel...');
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
