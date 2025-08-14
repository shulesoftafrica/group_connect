@extends('layouts.admin')

@section('title', 'Communication Campaigns')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">Communication Campaigns</h1>
            <p class="mb-0 text-muted">Manage and track all communication campaigns across your school group</p>
        </div>
        <div class="d-flex gap-2">
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#newCampaignModal">
                <i class="fas fa-plus me-2"></i>New Campaign
            </button>
            <button class="btn btn-outline-secondary" onclick="refreshCampaigns()">
                <i class="fas fa-sync-alt me-2"></i>Refresh
            </button>
        </div>
    </div>

    <!-- Campaign Stats Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Campaigns
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">24</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-bullhorn fa-2x text-gray-300"></i>
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
                                Active Campaigns
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">8</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-play-circle fa-2x text-gray-300"></i>
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
                                Avg Response Rate
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">78.4%</div>
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
                                Total Reach
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">15,420</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Campaign Filters -->
    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="statusFilter" class="form-label">Status</label>
                        <select class="form-select" id="statusFilter">
                            <option value="">All Status</option>
                            <option value="Active">Active</option>
                            <option value="Scheduled">Scheduled</option>
                            <option value="Completed">Completed</option>
                            <option value="Paused">Paused</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="typeFilter" class="form-label">Type</label>
                        <select class="form-select" id="typeFilter">
                            <option value="">All Types</option>
                            <option value="Announcement">Announcement</option>
                            <option value="Survey">Survey</option>
                            <option value="Alert">Alert</option>
                            <option value="Reminder">Reminder</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="dateFilter" class="form-label">Date Range</label>
                        <select class="form-select" id="dateFilter">
                            <option value="">All Time</option>
                            <option value="today">Today</option>
                            <option value="week">This Week</option>
                            <option value="month">This Month</option>
                            <option value="quarter">This Quarter</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="searchCampaigns" class="form-label">Search</label>
                        <input type="text" class="form-control" id="searchCampaigns" placeholder="Search campaigns...">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Campaigns List -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Campaign Management</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover" id="campaignsTable">
                    <thead class="table-light">
                        <tr>
                            <th>Campaign Name</th>
                            <th>Type</th>
                            <th>Status</th>
                            <th>Target Audience</th>
                            <th>Schools</th>
                            <th>Messages Sent</th>
                            <th>Delivery Rate</th>
                            <th>Engagement</th>
                            <th>Responses</th>
                            <th>Created</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($campaigns as $campaign)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar avatar-sm me-2">
                                        <div class="avatar-title bg-{{ $campaign['type'] == 'Announcement' ? 'info' : ($campaign['type'] == 'Survey' ? 'warning' : 'danger') }} rounded-circle">
                                            <i class="fas fa-{{ $campaign['type'] == 'Announcement' ? 'bullhorn' : ($campaign['type'] == 'Survey' ? 'poll' : 'exclamation') }}"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <strong>{{ $campaign['name'] }}</strong>
                                        <div class="text-muted small">ID: #{{ $campaign['id'] }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-{{ $campaign['type'] == 'Announcement' ? 'info' : ($campaign['type'] == 'Survey' ? 'warning' : 'danger') }}">
                                    {{ $campaign['type'] }}
                                </span>
                            </td>
                            <td>
                                <span class="badge bg-{{ $campaign['status'] == 'Active' ? 'success' : ($campaign['status'] == 'Scheduled' ? 'warning' : 'secondary') }}">
                                    {{ $campaign['status'] }}
                                </span>
                            </td>
                            <td class="text-muted">{{ $campaign['target_audience'] }}</td>
                            <td>
                                <span class="badge bg-primary">{{ $campaign['schools_targeted'] }}</span>
                            </td>
                            <td>{{ number_format($campaign['messages_sent']) }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <span class="me-2">{{ $campaign['delivery_rate'] }}%</span>
                                    <div class="progress" style="width: 50px; height: 6px;">
                                        <div class="progress-bar bg-success" style="width: {{ $campaign['delivery_rate'] }}%"></div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <span class="me-2">{{ $campaign['engagement_rate'] }}%</span>
                                    <div class="progress" style="width: 50px; height: 6px;">
                                        <div class="progress-bar bg-info" style="width: {{ $campaign['engagement_rate'] }}%"></div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-success">{{ number_format($campaign['responses']) }}</span>
                            </td>
                            <td class="text-muted">{{ $campaign['created_at'] }}</td>
                            <td>
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown">
                                        Actions
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item" href="#" onclick="viewCampaign({{ $campaign['id'] }})">
                                            <i class="fas fa-eye me-2"></i>View Details
                                        </a></li>
                                        @if($campaign['status'] == 'Active')
                                        <li><a class="dropdown-item" href="#" onclick="pauseCampaign({{ $campaign['id'] }})">
                                            <i class="fas fa-pause me-2"></i>Pause Campaign
                                        </a></li>
                                        @endif
                                        @if($campaign['status'] == 'Paused')
                                        <li><a class="dropdown-item" href="#" onclick="resumeCampaign({{ $campaign['id'] }})">
                                            <i class="fas fa-play me-2"></i>Resume Campaign
                                        </a></li>
                                        @endif
                                        <li><a class="dropdown-item" href="#" onclick="duplicateCampaign({{ $campaign['id'] }})">
                                            <i class="fas fa-copy me-2"></i>Duplicate
                                        </a></li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li><a class="dropdown-item text-danger" href="#" onclick="deleteCampaign({{ $campaign['id'] }})">
                                            <i class="fas fa-trash me-2"></i>Delete
                                        </a></li>
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

<!-- New Campaign Modal -->
<div class="modal fade" id="newCampaignModal" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Create New Campaign</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="newCampaignForm">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="campaignName" class="form-label">Campaign Name *</label>
                                <input type="text" class="form-control" id="campaignName" name="name" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="campaignType" class="form-label">Campaign Type *</label>
                                <select class="form-select" id="campaignType" name="type" required>
                                    <option value="">Select Type</option>
                                    <option value="announcement">Announcement</option>
                                    <option value="survey">Survey</option>
                                    <option value="alert">Alert</option>
                                    <option value="reminder">Reminder</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="targetAudience" class="form-label">Target Audience *</label>
                                <select class="form-select" id="targetAudience" name="target_audience" required>
                                    <option value="">Select Audience</option>
                                    <option value="all_schools">All Schools</option>
                                    <option value="principals">School Principals</option>
                                    <option value="teachers">All Teachers</option>
                                    <option value="admin_staff">Administrative Staff</option>
                                    <option value="custom">Custom Selection</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="messageChannel" class="form-label">Message Channel *</label>
                                <div class="form-check-container">
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="checkbox" id="channelSMS" name="channels[]" value="sms">
                                        <label class="form-check-label" for="channelSMS">SMS</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="checkbox" id="channelEmail" name="channels[]" value="email">
                                        <label class="form-check-label" for="channelEmail">Email</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="checkbox" id="channelWhatsApp" name="channels[]" value="whatsapp">
                                        <label class="form-check-label" for="channelWhatsApp">WhatsApp</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="scheduleType" class="form-label">Schedule</label>
                                <select class="form-select" id="scheduleType" name="schedule_type">
                                    <option value="immediate">Send Immediately</option>
                                    <option value="scheduled">Schedule for Later</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6" id="scheduleDateTime" style="display: none;">
                            <div class="mb-3">
                                <label for="scheduledTime" class="form-label">Scheduled Date & Time</label>
                                <input type="datetime-local" class="form-control" id="scheduledTime" name="scheduled_time">
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="campaignSubject" class="form-label">Subject (for Email)</label>
                        <input type="text" class="form-control" id="campaignSubject" name="subject">
                    </div>

                    <div class="mb-3">
                        <label for="campaignMessage" class="form-label">Message Content *</label>
                        <textarea class="form-control" id="campaignMessage" name="message" rows="6" required placeholder="Type your message here..."></textarea>
                        <div class="form-text">You can use merge fields like [SCHOOL_NAME], [RECIPIENT_NAME], [DATE]</div>
                    </div>

                    <div class="mb-3">
                        <label for="campaignPriority" class="form-label">Priority</label>
                        <select class="form-select" id="campaignPriority" name="priority">
                            <option value="normal">Normal</option>
                            <option value="high">High</option>
                            <option value="urgent">Urgent</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-outline-primary" onclick="saveDraft()">Save as Draft</button>
                <button type="button" class="btn btn-primary" onclick="createCampaign()">Create & Launch Campaign</button>
            </div>
        </div>
    </div>
</div>

<!-- Campaign Details Modal -->
<div class="modal fade" id="campaignDetailsModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Campaign Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="campaignDetailsContent">
                <!-- Campaign details will be loaded here -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Download Report</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Form handlers
document.getElementById('scheduleType').addEventListener('change', function() {
    const scheduleDateTime = document.getElementById('scheduleDateTime');
    if (this.value === 'scheduled') {
        scheduleDateTime.style.display = 'block';
        document.getElementById('scheduledTime').required = true;
    } else {
        scheduleDateTime.style.display = 'none';
        document.getElementById('scheduledTime').required = false;
    }
});

// Filter functionality
function setupFilters() {
    const statusFilter = document.getElementById('statusFilter');
    const typeFilter = document.getElementById('typeFilter');
    const dateFilter = document.getElementById('dateFilter');
    const searchInput = document.getElementById('searchCampaigns');

    [statusFilter, typeFilter, dateFilter].forEach(filter => {
        filter.addEventListener('change', filterCampaigns);
    });

    searchInput.addEventListener('keyup', filterCampaigns);
}

function filterCampaigns() {
    const statusFilter = document.getElementById('statusFilter').value;
    const typeFilter = document.getElementById('typeFilter').value;
    const searchTerm = document.getElementById('searchCampaigns').value.toLowerCase();
    const rows = document.querySelectorAll('#campaignsTable tbody tr');

    rows.forEach(row => {
        const status = row.cells[2].textContent.trim();
        const type = row.cells[1].textContent.trim();
        const name = row.cells[0].textContent.toLowerCase();

        const statusMatch = !statusFilter || status === statusFilter;
        const typeMatch = !typeFilter || type === typeFilter;
        const searchMatch = !searchTerm || name.includes(searchTerm);

        if (statusMatch && typeMatch && searchMatch) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
}

// Campaign actions
function viewCampaign(campaignId) {
    // Load campaign details
    const detailsContent = `
        <div class="row">
            <div class="col-md-6">
                <h6>Campaign Information</h6>
                <p><strong>Name:</strong> Term Opening Announcement</p>
                <p><strong>Type:</strong> Announcement</p>
                <p><strong>Status:</strong> <span class="badge bg-success">Active</span></p>
                <p><strong>Priority:</strong> Normal</p>
            </div>
            <div class="col-md-6">
                <h6>Performance Metrics</h6>
                <p><strong>Messages Sent:</strong> 1,250</p>
                <p><strong>Delivery Rate:</strong> 96.2%</p>
                <p><strong>Engagement Rate:</strong> 78.4%</p>
                <p><strong>Responses:</strong> 980</p>
            </div>
        </div>
        <hr>
        <h6>Message Content</h6>
        <div class="bg-light p-3 rounded">
            Dear [RECIPIENT_NAME], we are pleased to announce that the new academic term will begin on...
        </div>
    `;
    
    document.getElementById('campaignDetailsContent').innerHTML = detailsContent;
    $('#campaignDetailsModal').modal('show');
}

function pauseCampaign(campaignId) {
    if (confirm('Are you sure you want to pause this campaign?')) {
        alert('Campaign paused successfully!');
        location.reload();
    }
}

function resumeCampaign(campaignId) {
    if (confirm('Are you sure you want to resume this campaign?')) {
        alert('Campaign resumed successfully!');
        location.reload();
    }
}

function duplicateCampaign(campaignId) {
    alert('Campaign duplicated successfully!');
    location.reload();
}

function deleteCampaign(campaignId) {
    if (confirm('Are you sure you want to delete this campaign? This action cannot be undone.')) {
        alert('Campaign deleted successfully!');
        location.reload();
    }
}

function createCampaign() {
    const form = document.getElementById('newCampaignForm');
    const formData = new FormData(form);
    
    // Validate required fields
    if (!form.checkValidity()) {
        form.reportValidity();
        return;
    }
    
    // Simulate campaign creation
    alert('Campaign created and launched successfully!');
    $('#newCampaignModal').modal('hide');
    form.reset();
    location.reload();
}

function saveDraft() {
    const form = document.getElementById('newCampaignForm');
    alert('Campaign saved as draft!');
    $('#newCampaignModal').modal('hide');
    form.reset();
}

function refreshCampaigns() {
    location.reload();
}

// Initialize filters when page loads
document.addEventListener('DOMContentLoaded', function() {
    setupFilters();
});
</script>
@endpush
