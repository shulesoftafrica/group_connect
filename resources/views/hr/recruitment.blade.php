@extends('layouts.admin')

@section('title', 'Recruitment Management')

@section('content')
<div class="container-fluid px-4">
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('hr.index') }}">HR Dashboard</a></li>
                    <li class="breadcrumb-item active">Recruitment</li>
                </ol>
            </nav>
            <h1 class="h3 mb-0 text-gray-800">Recruitment Management</h1>
            <p class="text-muted mb-0">Manage job postings and applications across all schools</p>
        </div>
        <div class="d-flex gap-2">
            <button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#postJobModal">
                <i class="bi bi-plus me-1"></i> Post Job
            </button>
            <button class="btn btn-outline-success" onclick="exportRecruitmentReport()">
                <i class="bi bi-download me-1"></i> Export Report
            </button>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#bulkRecruitmentModal">
                <i class="bi bi-lightning me-1"></i> Bulk Actions
            </button>
        </div>
    </div>

    <!-- Recruitment Summary -->
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card text-center shadow">
                <div class="card-body">
                    <div class="h4 text-primary">{{ $recruitmentData['summary']['total_positions'] }}</div>
                    <div class="text-muted">Open Positions</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center shadow">
                <div class="card-body">
                    <div class="h4 text-info">{{ $recruitmentData['summary']['applications'] }}</div>
                    <div class="text-muted">Applications</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center shadow">
                <div class="card-body">
                    <div class="h4 text-warning">{{ $recruitmentData['summary']['in_progress'] }}</div>
                    <div class="text-muted">In Progress</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center shadow">
                <div class="card-body">
                    <div class="h4 text-success">{{ $recruitmentData['summary']['filled_positions'] }}</div>
                    <div class="text-muted">Filled Positions</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Row -->
    <div class="row">
        <!-- Left Column - Job Postings -->
        <div class="col-lg-8">
            <!-- Filters -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="bi bi-funnel me-2"></i>Filter Job Postings
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">School</label>
                            <select class="form-select" id="schoolFilter">
                                <option value="">All Schools</option>
                                @foreach($schools as $school)
                                <option value="{{ $school->id }}">{{ $school->settings['school_name'] ?? 'Unknown School' }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Position Type</label>
                            <select class="form-select" id="positionFilter">
                                <option value="">All Positions</option>
                                <option value="Teacher">Teacher</option>
                                <option value="Support Staff">Support Staff</option>
                                <option value="Administration">Administration</option>
                                <option value="Management">Management</option>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Status</label>
                            <select class="form-select" id="statusFilter">
                                <option value="">All Status</option>
                                <option value="Open">Open</option>
                                <option value="Interviewing">Interviewing</option>
                                <option value="Filled">Filled</option>
                                <option value="Closed">Closed</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <button class="btn btn-primary" onclick="applyRecruitmentFilters()">
                                <i class="bi bi-search me-1"></i> Apply Filters
                            </button>
                            <button class="btn btn-outline-secondary" onclick="clearRecruitmentFilters()">
                                <i class="bi bi-x me-1"></i> Clear
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Job Postings -->
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="bi bi-briefcase me-2"></i>Current Job Postings
                    </h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover" id="jobPostingsTable">
                            <thead>
                                <tr>
                                    <th><input type="checkbox" id="selectAllJobs"></th>
                                    <th>Position</th>
                                    <th>School</th>
                                    <th>Applications</th>
                                    <th>Status</th>
                                    <th>Posted Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recruitmentData['positions'] as $position)
                                <tr>
                                    <td><input type="checkbox" name="job_ids" value="{{ $position['id'] }}"></td>
                                    <td>
                                        <div class="font-weight-bold">{{ $position['title'] }}</div>
                                        <div class="text-xs text-muted">ID: {{ $position['id'] }}</div>
                                    </td>
                                    <td>{{ $position['school'] }}</td>
                                    <td>
                                        <span class="badge bg-info">{{ $position['applications'] }}</span>
                                        <div class="text-xs text-muted">applicants</div>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $position['status'] === 'Open' ? 'success' : ($position['status'] === 'Interviewing' ? 'warning' : 'secondary') }}">
                                            {{ $position['status'] }}
                                        </span>
                                    </td>
                                    <td>{{ \Carbon\Carbon::parse($position['posted_date'])->format('M d, Y') }}</td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <button class="btn btn-sm btn-outline-primary" onclick="viewApplications({{ $position['id'] }})">
                                                <i class="bi bi-people"></i>
                                            </button>
                                            <button class="btn btn-sm btn-outline-secondary" onclick="editJobPosting({{ $position['id'] }})">
                                                <i class="bi bi-pencil"></i>
                                            </button>
                                            <button class="btn btn-sm btn-outline-danger dropdown-toggle" data-bs-toggle="dropdown">
                                                <i class="bi bi-three-dots"></i>
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li><a class="dropdown-item" href="#" onclick="duplicatePosting({{ $position['id'] }})">Duplicate</a></li>
                                                <li><a class="dropdown-item" href="#" onclick="sharePosting({{ $position['id'] }})">Share</a></li>
                                                <li><a class="dropdown-item" href="#" onclick="viewAnalytics({{ $position['id'] }})">Analytics</a></li>
                                                <li><hr class="dropdown-divider"></li>
                                                <li><a class="dropdown-item text-danger" href="#" onclick="closePosting({{ $position['id'] }})">Close Position</a></li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Bulk Actions -->
                    <div class="row mt-3">
                        <div class="col-md-8">
                            <div class="d-flex align-items-center">
                                <label for="bulkJobAction" class="form-label me-2 mb-0">Bulk Action:</label>
                                <select class="form-select form-select-sm me-2" id="bulkJobAction" style="width: auto;">
                                    <option value="">Select Action</option>
                                    <option value="close_positions">Close Positions</option>
                                    <option value="extend_deadline">Extend Deadline</option>
                                    <option value="share_postings">Share Postings</option>
                                    <option value="generate_report">Generate Report</option>
                                </select>
                                <button class="btn btn-sm btn-primary" onclick="executeBulkJobAction()">Execute</button>
                            </div>
                        </div>
                        <div class="col-md-4 text-end">
                            <small class="text-muted">{{ count($recruitmentData['positions']) }} positions total</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column - Statistics & Quick Actions -->
        <div class="col-lg-4">
            <!-- Recruitment Pipeline -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="bi bi-funnel me-2"></i>Recruitment Pipeline
                    </h6>
                </div>
                <div class="card-body">
                    @foreach($recruitmentData['recruitment_pipeline'] as $stage => $count)
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <div class="font-weight-bold">{{ $stage }}</div>
                            <div class="text-xs text-muted">{{ $count }} candidates</div>
                        </div>
                        <div class="progress flex-grow-1 mx-3" style="height: 8px;">
                            <div class="progress-bar bg-primary" style="width: {{ ($count / 100) * 100 }}%"></div>
                        </div>
                        <span class="badge bg-primary">{{ $count }}</span>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Recent Applications -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="bi bi-clock me-2"></i>Recent Applications
                    </h6>
                </div>
                <div class="card-body">
                    <div class="timeline">
                        <div class="timeline-item mb-3">
                            <div class="timeline-marker bg-success"></div>
                            <div class="timeline-content">
                                <div class="font-weight-bold">John Mwalimu</div>
                                <div class="text-sm text-muted">Applied for Mathematics Teacher</div>
                                <div class="text-xs text-muted">2 hours ago</div>
                            </div>
                        </div>
                        <div class="timeline-item mb-3">
                            <div class="timeline-marker bg-info"></div>
                            <div class="timeline-content">
                                <div class="font-weight-bold">Mary Elimu</div>
                                <div class="text-sm text-muted">Applied for Science Teacher</div>
                                <div class="text-xs text-muted">5 hours ago</div>
                            </div>
                        </div>
                        <div class="timeline-item mb-3">
                            <div class="timeline-marker bg-warning"></div>
                            <div class="timeline-content">
                                <div class="font-weight-bold">Peter Fundisha</div>
                                <div class="text-sm text-muted">Applied for Librarian</div>
                                <div class="text-xs text-muted">1 day ago</div>
                            </div>
                        </div>
                    </div>
                    <div class="text-center mt-3">
                        <a href="#" class="btn btn-outline-primary btn-sm">View All Applications</a>
                    </div>
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
                        <button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#postJobModal">
                            <i class="bi bi-plus me-2"></i> Post New Job
                        </button>
                        <button class="btn btn-outline-success" onclick="reviewApplications()">
                            <i class="bi bi-eye me-2"></i> Review Applications
                        </button>
                        <button class="btn btn-outline-info" onclick="scheduleInterviews()">
                            <i class="bi bi-calendar-check me-2"></i> Schedule Interviews
                        </button>
                        <button class="btn btn-outline-warning" onclick="sendOffers()">
                            <i class="bi bi-envelope me-2"></i> Send Job Offers
                        </button>
                    </div>
                    
                    <hr>
                    
                    <div class="text-center">
                        <h6 class="text-muted">This Month</h6>
                        <div class="row text-center">
                            <div class="col-6">
                                <div class="h5 text-primary">23</div>
                                <div class="text-xs text-muted">New Applications</div>
                            </div>
                            <div class="col-6">
                                <div class="h5 text-success">5</div>
                                <div class="text-xs text-muted">Hires Made</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Post Job Modal -->
<div class="modal fade" id="postJobModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Post New Job</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="postJobForm">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Job Title *</label>
                            <input type="text" class="form-control" name="title" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">School *</label>
                            <select class="form-select" name="school_id" required>
                                <option value="">Select School</option>
                                @foreach($schools as $school)
                                <option value="{{ $school->id }}">{{ $school->settings['school_name'] ?? 'Unknown School' }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Position Type *</label>
                            <select class="form-select" name="position_type" required>
                                <option value="">Select Type</option>
                                <option value="Teacher">Teacher</option>
                                <option value="Support Staff">Support Staff</option>
                                <option value="Administration">Administration</option>
                                <option value="Management">Management</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Employment Type</label>
                            <select class="form-select" name="employment_type">
                                <option value="Full-time">Full-time</option>
                                <option value="Part-time">Part-time</option>
                                <option value="Contract">Contract</option>
                                <option value="Temporary">Temporary</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Salary Range</label>
                            <input type="text" class="form-control" name="salary_range" placeholder="e.g., 800,000 - 1,200,000">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Application Deadline</label>
                            <input type="date" class="form-control" name="deadline">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Job Description *</label>
                        <textarea class="form-control" name="description" rows="4" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Requirements</label>
                        <textarea class="form-control" name="requirements" rows="3"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Benefits</label>
                        <textarea class="form-control" name="benefits" rows="2"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="postJob()">Post Job</button>
            </div>
        </div>
    </div>
</div>

<!-- Bulk Recruitment Actions Modal -->
<div class="modal fade" id="bulkRecruitmentModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Bulk Recruitment Actions</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Select Action</label>
                    <select class="form-select" id="modalBulkRecruitmentAction">
                        <option value="">Choose Action</option>
                        <option value="close_positions">Close Selected Positions</option>
                        <option value="extend_deadline">Extend Application Deadline</option>
                        <option value="send_updates">Send Status Updates</option>
                        <option value="share_postings">Share Job Postings</option>
                        <option value="generate_report">Generate Recruitment Report</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Additional Details</label>
                    <textarea class="form-control" id="bulkRecruitmentMessage" rows="3" placeholder="Enter additional details or message..."></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="executeModalBulkRecruitmentAction()">Execute Action</button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
// Filter functions
function applyRecruitmentFilters() {
    const school = document.getElementById('schoolFilter').value;
    const position = document.getElementById('positionFilter').value;
    const status = document.getElementById('statusFilter').value;
    
    const rows = document.querySelectorAll('#jobPostingsTable tbody tr');
    
    rows.forEach(row => {
        let visible = true;
        const cells = row.querySelectorAll('td');
        const positionText = cells[1].textContent;
        const schoolText = cells[2].textContent;
        const statusText = cells[4].textContent;
        
        if (school && !schoolText.includes(school)) {
            visible = false;
        }
        
        if (position && !positionText.toLowerCase().includes(position.toLowerCase())) {
            visible = false;
        }
        
        if (status && !statusText.includes(status)) {
            visible = false;
        }
        
        row.style.display = visible ? '' : 'none';
    });
}

function clearRecruitmentFilters() {
    document.getElementById('schoolFilter').value = '';
    document.getElementById('positionFilter').value = '';
    document.getElementById('statusFilter').value = '';
    applyRecruitmentFilters();
}

// Job actions
function viewApplications(jobId) {
    alert(`Viewing applications for job ID: ${jobId}`);
}

function editJobPosting(jobId) {
    alert(`Editing job posting ID: ${jobId}`);
}

function duplicatePosting(jobId) {
    alert(`Duplicating job posting ID: ${jobId}`);
}

function sharePosting(jobId) {
    alert(`Sharing job posting ID: ${jobId}`);
}

function viewAnalytics(jobId) {
    alert(`Viewing analytics for job ID: ${jobId}`);
}

function closePosting(jobId) {
    if (confirm('Are you sure you want to close this position?')) {
        alert(`Closing job posting ID: ${jobId}`);
    }
}

function postJob() {
    const form = document.getElementById('postJobForm');
    if (form.checkValidity()) {
        alert('Job posted successfully!');
        bootstrap.Modal.getInstance(document.getElementById('postJobModal')).hide();
        form.reset();
    } else {
        form.reportValidity();
    }
}

// Quick actions
function reviewApplications() {
    alert('Opening application review interface...');
}

function scheduleInterviews() {
    alert('Opening interview scheduling interface...');
}

function sendOffers() {
    alert('Opening job offer management interface...');
}

// Bulk actions
function executeBulkJobAction() {
    const action = document.getElementById('bulkJobAction').value;
    const selected = document.querySelectorAll('input[name="job_ids"]:checked');
    
    if (!action) {
        alert('Please select an action');
        return;
    }
    
    if (selected.length === 0) {
        alert('Please select at least one job posting');
        return;
    }
    
    alert(`Executing ${action} for ${selected.length} job postings`);
}

function executeModalBulkRecruitmentAction() {
    const action = document.getElementById('modalBulkRecruitmentAction').value;
    const message = document.getElementById('bulkRecruitmentMessage').value;
    
    if (!action) {
        alert('Please select an action');
        return;
    }
    
    alert(`Executing ${action} with details: ${message}`);
    bootstrap.Modal.getInstance(document.getElementById('bulkRecruitmentModal')).hide();
}

function exportRecruitmentReport() {
    alert('Exporting recruitment report...');
}

// Select all functionality
document.getElementById('selectAllJobs').addEventListener('change', function() {
    const checkboxes = document.querySelectorAll('input[name="job_ids"]');
    checkboxes.forEach(checkbox => checkbox.checked = this.checked);
});
</script>
@endpush

@push('styles')
<style>
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

.font-weight-bold {
    font-weight: 700 !important;
}

.text-xs {
    font-size: 0.75rem;
}

.badge {
    font-size: 0.75rem;
}

.btn-group .btn {
    font-size: 0.75rem;
    padding: 0.25rem 0.5rem;
}

.timeline {
    position: relative;
    padding-left: 20px;
}

.timeline-item {
    position: relative;
}

.timeline-marker {
    position: absolute;
    left: -25px;
    top: 5px;
    width: 10px;
    height: 10px;
    border-radius: 50%;
}

.timeline-content {
    padding-left: 5px;
}

.progress {
    height: 8px;
    background-color: #f1f1f1;
}

@media (max-width: 768px) {
    .col-lg-8, .col-lg-4 {
        margin-bottom: 1rem;
    }
    
    .d-flex.gap-2 {
        flex-direction: column;
        gap: 0.5rem !important;
    }
    
    .btn-group .btn {
        font-size: 0.625rem;
        padding: 0.125rem 0.25rem;
    }
    
    .timeline {
        padding-left: 15px;
    }
    
    .timeline-marker {
        left: -20px;
    }
}
</style>
@endpush
