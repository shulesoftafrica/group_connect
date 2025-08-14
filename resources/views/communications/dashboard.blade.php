@extends('layouts.admin')

@section('title', 'Communications Dashboard')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">Communications Dashboard</h1>
            <p class="mb-0 text-muted">Monitor and manage group-wide communications across all schools</p>
        </div>
        <div class="d-flex gap-2">
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#newCampaignModal">
                <i class="fas fa-bullhorn me-2"></i>New Campaign
            </button>
            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#quickMessageModal">
                <i class="fas fa-paper-plane me-2"></i>Quick Message
            </button>
        </div>
    </div>

    <!-- KPI Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Messages Sent
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ number_format($total_messages_sent) }}
                            </div>
                            <div class="text-xs text-success">
                                <i class="fas fa-arrow-up"></i> {{ $monthly_growth }}% this month
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-envelope fa-2x text-gray-300"></i>
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
                                Delivery Rate
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $delivery_rate }}%</div>
                            <div class="text-xs text-muted">{{ number_format($delivery_stats['delivered']) }} delivered</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
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
                                Active Campaigns
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $active_campaigns }}</div>
                            <div class="text-xs text-muted">Across {{ $schools_reached }} schools</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-bullhorn fa-2x text-gray-300"></i>
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
                                Engagement Rate
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $engagement_rate }}%</div>
                            <div class="text-xs text-muted">Avg response time: {{ $avg_response_time }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-chart-bar fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="row mb-4">
        <div class="col-xl-8 col-lg-7">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Communication Trends</h6>
                    <div class="dropdown no-arrow">
                        <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-bs-toggle="dropdown">
                            <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right">
                            <a class="dropdown-item" href="#">Last 7 days</a>
                            <a class="dropdown-item" href="#">Last 30 days</a>
                            <a class="dropdown-item" href="#">Last 3 months</a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <canvas id="communicationTrendsChart" width="100%" height="40"></canvas>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-lg-5">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Message Types Distribution</h6>
                </div>
                <div class="card-body">
                    <!-- <canvas id="messageTypesChart" width="100%" height="150"></canvas> -->
                    <div class="mt-3">
                        @foreach($message_types as $type => $data)
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="text-sm">{{ $type }}</span>
                            <div class="d-flex align-items-center">
                                <span class="text-sm font-weight-bold me-2">{{ number_format($data['count']) }}</span>
                                <span class="badge bg-primary">{{ $data['percentage'] }}%</span>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Active Campaigns and Recent Messages -->
    <div class="row mb-4">
        <div class="col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">Active Campaigns</h6>
                    <a href="{{ route('communications.campaigns') }}" class="btn btn-sm btn-outline-primary">View All</a>
                </div>
                <div class="card-body">
                    @foreach($campaigns as $campaign)
                    <div class="d-flex align-items-center justify-content-between p-3 border rounded mb-3">
                        <div>
                            <h6 class="mb-1">{{ $campaign['name'] }}</h6>
                            <p class="text-muted mb-1">{{ $campaign['type'] }} • {{ $campaign['target_schools'] }} schools</p>
                            <div class="d-flex gap-3">
                                <small class="text-success">
                                    <i class="fas fa-check-circle"></i> {{ $campaign['delivery_rate'] }}% delivered
                                </small>
                                <small class="text-info">
                                    <i class="fas fa-chart-line"></i> {{ $campaign['engagement_rate'] }}% engagement
                                </small>
                            </div>
                        </div>
                        <div class="text-center">
                            <span class="badge bg-{{ $campaign['status'] == 'Active' ? 'success' : ($campaign['status'] == 'Scheduled' ? 'warning' : 'secondary') }}">
                                {{ $campaign['status'] }}
                            </span>
                            <p class="text-sm text-muted mb-0 mt-1">{{ number_format($campaign['messages_sent']) }} sent</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">Recent Messages</h6>
                    <a href="{{ route('communications.messaging') }}" class="btn btn-sm btn-outline-primary">Send New</a>
                </div>
                <div class="card-body">
                    @foreach($recentMessages as $message)
                    <div class="d-flex align-items-center justify-content-between p-3 border rounded mb-3">
                        <div class="d-flex align-items-center">
                            <div class="me-3">
                                <i class="fas fa-{{ $message['type'] == 'SMS' ? 'sms' : ($message['type'] == 'Email' ? 'envelope' : 'comments') }} fa-lg text-primary"></i>
                            </div>
                            <div>
                                <h6 class="mb-1">{{ $message['subject'] }}</h6>
                                <p class="text-muted mb-1">{{ $message['recipients'] }} recipients • {{ $message['sent_at'] }}</p>
                                <small class="text-success">
                                    <i class="fas fa-check-circle"></i> {{ $message['delivery_rate'] }}% delivered
                                </small>
                            </div>
                        </div>
                        <span class="badge bg-{{ $message['status'] == 'Delivered' ? 'success' : 'warning' }}">
                            {{ $message['status'] }}
                        </span>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- Schools Communication Overview -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Schools Communication Overview</h6>
            <div class="d-flex gap-2">
                <div class="input-group" style="width: 250px;">
                    <input type="text" class="form-control form-control-sm" placeholder="Search schools..." id="schoolSearch">
                    <button class="btn btn-outline-secondary btn-sm" type="button">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
                <select class="form-select form-select-sm" style="width: 150px;" id="statusFilter">
                    <option value="">All Status</option>
                    <option value="Active">Active</option>
                    <option value="Moderate">Moderate</option>
                </select>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover" id="schoolsTable">
                    <thead class="table-light">
                        <tr>
                            <th>School Name</th>
                            <th>Location</th>
                            <th>Messages Sent</th>
                            <th>Delivery Rate</th>
                            <th>Engagement Rate</th>
                            <th>Active Campaigns</th>
                            <th>Last Message</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($schools as $school)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar avatar-sm me-2">
                                        <div class="avatar-title bg-primary rounded-circle">
                                            {{ substr($school['name'], 0, 1) }}
                                        </div>
                                    </div>
                                    <strong>{{ $school['name'] }}</strong>
                                </div>
                            </td>
                            <td class="text-muted">{{ $school['location'] }}</td>
                            <td><span class="badge bg-info">{{ number_format($school['messages_sent']) }}</span></td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <span class="me-2">{{ $school['delivery_rate'] }}</span>
                                    <div class="progress" style="width: 60px; height: 6px;">
                                        <div class="progress-bar bg-success" style="width: {{ $school['delivery_rate'] }}"></div>
                                    </div>
                                </div>
                            </td>
                            <td>{{ $school['engagement_rate'] }}</td>
                            <td><span class="badge bg-primary">{{ $school['active_campaigns'] }}</span></td>
                            <td class="text-muted">{{ $school['last_message'] }}</td>
                            <td>
                                <span class="badge bg-{{ $school['communication_status'] == 'Active' ? 'success' : 'warning' }}">
                                    {{ $school['communication_status'] }}
                                </span>
                            </td>
                            <td>
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown">
                                        Actions
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item" href="#"><i class="fas fa-paper-plane me-2"></i>Send Message</a></li>
                                        <li><a class="dropdown-item" href="#"><i class="fas fa-chart-line me-2"></i>View Analytics</a></li>
                                        <li><a class="dropdown-item" href="#"><i class="fas fa-cog me-2"></i>Communication Settings</a></li>
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
    <div class="modal-dialog modal-lg">
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
                                <label for="campaignName" class="form-label">Campaign Name</label>
                                <input type="text" class="form-control" id="campaignName" name="name" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="campaignType" class="form-label">Campaign Type</label>
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
                    <div class="mb-3">
                        <label for="targetAudience" class="form-label">Target Audience</label>
                        <select class="form-select" id="targetAudience" name="target_audience" required>
                            <option value="">Select Audience</option>
                            <option value="all_schools">All Schools</option>
                            <option value="principals">School Principals</option>
                            <option value="teachers">All Teachers</option>
                            <option value="custom">Custom Selection</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="campaignMessage" class="form-label">Message</label>
                        <textarea class="form-control" id="campaignMessage" name="message" rows="4" required></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="createCampaign()">Create Campaign</button>
            </div>
        </div>
    </div>
</div>

<!-- Quick Message Modal -->
<div class="modal fade" id="quickMessageModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Send Quick Message</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="quickMessageForm">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="messageType" class="form-label">Message Type</label>
                                <select class="form-select" id="messageType" name="message_type" required>
                                    <option value="">Select Type</option>
                                    <option value="sms">SMS</option>
                                    <option value="email">Email</option>
                                    <option value="whatsapp">WhatsApp</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="recipients" class="form-label">Recipients</label>
                                <select class="form-select" id="recipients" name="recipients" required>
                                    <option value="">Select Recipients</option>
                                    <option value="all_principals">All Principals</option>
                                    <option value="all_teachers">All Teachers</option>
                                    <option value="selected_schools">Selected Schools</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3" id="subjectField" style="display: none;">
                        <label for="messageSubject" class="form-label">Subject</label>
                        <input type="text" class="form-control" id="messageSubject" name="subject">
                    </div>
                    <div class="mb-3">
                        <label for="messageContent" class="form-label">Message</label>
                        <textarea class="form-control" id="messageContent" name="message" rows="4" required></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-success" onclick="sendQuickMessage()">Send Message</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Communication Trends Chart
const ctx1 = document.getElementById('communicationTrendsChart').getContext('2d');
new Chart(ctx1, {
    type: 'line',
    data: {
        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
        datasets: [{
            label: 'SMS',
            data: [1200, 1450, 1350, 1600, 1750, 1820],
            borderColor: 'rgb(75, 192, 192)',
            backgroundColor: 'rgba(75, 192, 192, 0.1)',
            tension: 0.1
        }, {
            label: 'Email',
            data: [800, 920, 850, 1100, 1200, 1250],
            borderColor: 'rgb(255, 99, 132)',
            backgroundColor: 'rgba(255, 99, 132, 0.1)',
            tension: 0.1
        }, {
            label: 'WhatsApp',
            data: [200, 280, 320, 450, 520, 580],
            borderColor: 'rgb(54, 162, 235)',
            backgroundColor: 'rgba(54, 162, 235, 0.1)',
            tension: 0.1
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});

// Message Types Chart
const ctx2 = document.getElementById('messageTypesChart').getContext('2d');
new Chart(ctx2, {
    type: 'doughnut',
    data: {
        labels: ['SMS', 'Email', 'WhatsApp'],
        datasets: [{
            data: [54.6, 33.9, 11.5],
            backgroundColor: [
                'rgb(75, 192, 192)',
                'rgb(255, 99, 132)',
                'rgb(54, 162, 235)'
            ]
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                display: false
            }
        }
    }
});

// Form handlers
document.getElementById('messageType').addEventListener('change', function() {
    const subjectField = document.getElementById('subjectField');
    if (this.value === 'email') {
        subjectField.style.display = 'block';
        document.getElementById('messageSubject').required = true;
    } else {
        subjectField.style.display = 'none';
        document.getElementById('messageSubject').required = false;
    }
});

function createCampaign() {
    const form = document.getElementById('newCampaignForm');
    const formData = new FormData(form);
    
    // Simulate campaign creation
    alert('Campaign created successfully!');
    $('#newCampaignModal').modal('hide');
    form.reset();
}

function sendQuickMessage() {
    const form = document.getElementById('quickMessageForm');
    const formData = new FormData(form);
    
    // Simulate message sending
    alert('Message sent successfully!');
    $('#quickMessageModal').modal('hide');
    form.reset();
}

// Search and filter functionality
document.getElementById('schoolSearch').addEventListener('keyup', function() {
    const searchTerm = this.value.toLowerCase();
    const rows = document.querySelectorAll('#schoolsTable tbody tr');
    
    rows.forEach(row => {
        const schoolName = row.cells[0].textContent.toLowerCase();
        if (schoolName.includes(searchTerm)) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
});

document.getElementById('statusFilter').addEventListener('change', function() {
    const filterValue = this.value;
    const rows = document.querySelectorAll('#schoolsTable tbody tr');
    
    rows.forEach(row => {
        const status = row.cells[7].textContent.trim();
        if (filterValue === '' || status === filterValue) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
});
</script>
@endpush
