@extends('layouts.admin')

@section('title', 'Communication Feedback')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">Communication Feedback</h1>
            <p class="mb-0 text-muted">View and manage feedback responses from schools and staff</p>
        </div>
        <div class="d-flex gap-2">
            <button class="btn btn-outline-primary" onclick="markAllAsRead()">
                <i class="fas fa-check-double me-2"></i>Mark All as Read
            </button>
            <button class="btn btn-primary" onclick="generateFeedbackReport()">
                <i class="fas fa-file-alt me-2"></i>Generate Report
            </button>
        </div>
    </div>

    <!-- Feedback Summary Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Feedback
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">245</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-comments fa-2x text-gray-300"></i>
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
                                Pending Review
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">18</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clock fa-2x text-gray-300"></i>
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
                                Response Rate
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">78.4%</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-percentage fa-2x text-gray-300"></i>
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
                                Avg Response Time
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">4.2h</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-stopwatch fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Feedback Filters -->
    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="statusFilter" class="form-label">Status</label>
                        <select class="form-select" id="statusFilter">
                            <option value="">All Status</option>
                            <option value="Pending Review">Pending Review</option>
                            <option value="Reviewed">Reviewed</option>
                            <option value="Acknowledged">Acknowledged</option>
                            <option value="Follow-up Required">Follow-up Required</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="typeFilter" class="form-label">Feedback Type</label>
                        <select class="form-select" id="typeFilter">
                            <option value="">All Types</option>
                            <option value="Acknowledgment">Acknowledgment</option>
                            <option value="Survey Response">Survey Response</option>
                            <option value="Complaint">Complaint</option>
                            <option value="Suggestion">Suggestion</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="schoolFilter" class="form-label">School</label>
                        <select class="form-select" id="schoolFilter">
                            <option value="">All Schools</option>
                            <option value="Greenfield Academy">Greenfield Academy</option>
                            <option value="Sunrise Primary">Sunrise Primary</option>
                            <option value="Bright Future High">Bright Future High</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="searchFeedback" class="form-label">Search</label>
                        <input type="text" class="form-control" id="searchFeedback" placeholder="Search feedback...">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Feedback List -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Feedback Messages</h6>
        </div>
        <div class="card-body">
            <div class="row" id="feedbackContainer">
                @foreach($feedback as $item)
                <div class="col-12 mb-3 feedback-item" data-status="{{ $item['status'] }}" data-type="{{ $item['feedback_type'] }}" data-school="{{ $item['school_name'] }}">
                    <div class="card border-left-{{ $item['status'] == 'Pending Review' ? 'warning' : 'success' }} h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <div class="d-flex align-items-center">
                                    <div class="avatar avatar-sm me-3">
                                        <div class="avatar-title bg-{{ $item['feedback_type'] == 'Acknowledgment' ? 'info' : ($item['feedback_type'] == 'Survey Response' ? 'primary' : 'warning') }} rounded-circle">
                                            <i class="fas fa-{{ $item['feedback_type'] == 'Acknowledgment' ? 'check' : ($item['feedback_type'] == 'Survey Response' ? 'poll' : 'comment') }}"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <h6 class="mb-1">{{ $item['campaign_name'] }}</h6>
                                        <p class="text-muted mb-0">
                                            <strong>{{ $item['school_name'] }}</strong> • {{ $item['respondent'] }}
                                        </p>
                                    </div>
                                </div>
                                <div class="text-end">
                                    <span class="badge bg-{{ $item['status'] == 'Pending Review' ? 'warning' : 'success' }}">
                                        {{ $item['status'] }}
                                    </span>
                                    <div class="text-muted small mt-1">{{ $item['received_at'] }}</div>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <span class="badge bg-{{ $item['feedback_type'] == 'Acknowledgment' ? 'info' : ($item['feedback_type'] == 'Survey Response' ? 'primary' : 'warning') }} me-2">
                                    {{ $item['feedback_type'] }}
                                </span>
                            </div>
                            
                            <div class="bg-light p-3 rounded mb-3">
                                <p class="mb-0">{{ $item['message'] }}</p>
                            </div>
                            
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="d-flex gap-2">
                                    @if($item['status'] == 'Pending Review')
                                    <button class="btn btn-sm btn-success" onclick="markAsReviewed('{{ $item['campaign_name'] }}', '{{ $item['school_name'] }}')">
                                        <i class="fas fa-check me-1"></i>Mark as Reviewed
                                    </button>
                                    @endif
                                    <button class="btn btn-sm btn-outline-primary" onclick="replyToFeedback('{{ $item['campaign_name'] }}', '{{ $item['school_name'] }}')">
                                        <i class="fas fa-reply me-1"></i>Reply
                                    </button>
                                    <button class="btn btn-sm btn-outline-secondary" onclick="viewDetails('{{ $item['campaign_name'] }}', '{{ $item['school_name'] }}')">
                                        <i class="fas fa-eye me-1"></i>Details
                                    </button>
                                </div>
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown">
                                        <i class="fas fa-ellipsis-v"></i>
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item" href="#" onclick="flagForFollowUp('{{ $item['campaign_name'] }}', '{{ $item['school_name'] }}')">
                                            <i class="fas fa-flag me-2"></i>Flag for Follow-up
                                        </a></li>
                                        <li><a class="dropdown-item" href="#" onclick="exportFeedback('{{ $item['campaign_name'] }}', '{{ $item['school_name'] }}')">
                                            <i class="fas fa-download me-2"></i>Export
                                        </a></li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li><a class="dropdown-item text-danger" href="#" onclick="deleteFeedback('{{ $item['campaign_name'] }}', '{{ $item['school_name'] }}')">
                                            <i class="fas fa-trash me-2"></i>Delete
                                        </a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
                
                <!-- Sample additional feedback items -->
                <div class="col-12 mb-3 feedback-item" data-status="Reviewed" data-type="Complaint" data-school="Valley School">
                    <div class="card border-left-success h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <div class="d-flex align-items-center">
                                    <div class="avatar avatar-sm me-3">
                                        <div class="avatar-title bg-danger rounded-circle">
                                            <i class="fas fa-exclamation"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <h6 class="mb-1">Fee Payment Alert</h6>
                                        <p class="text-muted mb-0">
                                            <strong>Valley School</strong> • Principal Sarah Johnson
                                        </p>
                                    </div>
                                </div>
                                <div class="text-end">
                                    <span class="badge bg-success">Reviewed</span>
                                    <div class="text-muted small mt-1">Yesterday 14:30</div>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <span class="badge bg-danger me-2">Complaint</span>
                            </div>
                            
                            <div class="bg-light p-3 rounded mb-3">
                                <p class="mb-0">Some parents are complaining about receiving multiple fee reminder messages. Could we reduce the frequency to once per week instead of daily?</p>
                            </div>
                            
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="d-flex gap-2">
                                    <button class="btn btn-sm btn-outline-primary" onclick="replyToFeedback('Fee Payment Alert', 'Valley School')">
                                        <i class="fas fa-reply me-1"></i>Reply
                                    </button>
                                    <button class="btn btn-sm btn-outline-secondary" onclick="viewDetails('Fee Payment Alert', 'Valley School')">
                                        <i class="fas fa-eye me-1"></i>Details
                                    </button>
                                </div>
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown">
                                        <i class="fas fa-ellipsis-v"></i>
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item" href="#" onclick="flagForFollowUp('Fee Payment Alert', 'Valley School')">
                                            <i class="fas fa-flag me-2"></i>Flag for Follow-up
                                        </a></li>
                                        <li><a class="dropdown-item" href="#" onclick="exportFeedback('Fee Payment Alert', 'Valley School')">
                                            <i class="fas fa-download me-2"></i>Export
                                        </a></li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li><a class="dropdown-item text-danger" href="#" onclick="deleteFeedback('Fee Payment Alert', 'Valley School')">
                                            <i class="fas fa-trash me-2"></i>Delete
                                        </a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-12 mb-3 feedback-item" data-status="Acknowledged" data-type="Suggestion" data-school="Mountain View School">
                    <div class="card border-left-success h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <div class="d-flex align-items-center">
                                    <div class="avatar avatar-sm me-3">
                                        <div class="avatar-title bg-info rounded-circle">
                                            <i class="fas fa-lightbulb"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <h6 class="mb-1">Parent-Teacher Meeting Reminder</h6>
                                        <p class="text-muted mb-0">
                                            <strong>Mountain View School</strong> • Principal Robert Brown
                                        </p>
                                    </div>
                                </div>
                                <div class="text-end">
                                    <span class="badge bg-success">Acknowledged</span>
                                    <div class="text-muted small mt-1">3 days ago</div>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <span class="badge bg-info me-2">Suggestion</span>
                            </div>
                            
                            <div class="bg-light p-3 rounded mb-3">
                                <p class="mb-0">Suggestion: Could we include a calendar link in future meeting reminders so parents can easily add the meeting to their calendars?</p>
                            </div>
                            
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="d-flex gap-2">
                                    <button class="btn btn-sm btn-outline-primary" onclick="replyToFeedback('Parent-Teacher Meeting Reminder', 'Mountain View School')">
                                        <i class="fas fa-reply me-1"></i>Reply
                                    </button>
                                    <button class="btn btn-sm btn-outline-secondary" onclick="viewDetails('Parent-Teacher Meeting Reminder', 'Mountain View School')">
                                        <i class="fas fa-eye me-1"></i>Details
                                    </button>
                                </div>
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown">
                                        <i class="fas fa-ellipsis-v"></i>
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item" href="#" onclick="flagForFollowUp('Parent-Teacher Meeting Reminder', 'Mountain View School')">
                                            <i class="fas fa-flag me-2"></i>Flag for Follow-up
                                        </a></li>
                                        <li><a class="dropdown-item" href="#" onclick="exportFeedback('Parent-Teacher Meeting Reminder', 'Mountain View School')">
                                            <i class="fas fa-download me-2"></i>Export
                                        </a></li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li><a class="dropdown-item text-danger" href="#" onclick="deleteFeedback('Parent-Teacher Meeting Reminder', 'Mountain View School')">
                                            <i class="fas fa-trash me-2"></i>Delete
                                        </a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Reply Modal -->
<div class="modal fade" id="replyModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Reply to Feedback</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <strong>Original Feedback:</strong>
                    <div class="bg-light p-3 rounded mt-2" id="originalFeedback">
                        <!-- Original feedback content will be loaded here -->
                    </div>
                </div>
                <form id="replyForm">
                    <div class="mb-3">
                        <label for="replyMessage" class="form-label">Your Reply</label>
                        <textarea class="form-control" id="replyMessage" name="reply" rows="4" placeholder="Type your reply here..." required></textarea>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="sendCopy" name="send_copy">
                        <label class="form-check-label" for="sendCopy">
                            Send a copy to the school's email
                        </label>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="sendReply()">Send Reply</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Filter functionality
function setupFilters() {
    const statusFilter = document.getElementById('statusFilter');
    const typeFilter = document.getElementById('typeFilter');
    const schoolFilter = document.getElementById('schoolFilter');
    const searchInput = document.getElementById('searchFeedback');

    [statusFilter, typeFilter, schoolFilter].forEach(filter => {
        filter.addEventListener('change', filterFeedback);
    });

    searchInput.addEventListener('keyup', filterFeedback);
}

function filterFeedback() {
    const statusFilter = document.getElementById('statusFilter').value;
    const typeFilter = document.getElementById('typeFilter').value;
    const schoolFilter = document.getElementById('schoolFilter').value;
    const searchTerm = document.getElementById('searchFeedback').value.toLowerCase();
    const feedbackItems = document.querySelectorAll('.feedback-item');

    feedbackItems.forEach(item => {
        const status = item.dataset.status;
        const type = item.dataset.type;
        const school = item.dataset.school;
        const content = item.textContent.toLowerCase();

        const statusMatch = !statusFilter || status === statusFilter;
        const typeMatch = !typeFilter || type === typeFilter;
        const schoolMatch = !schoolFilter || school === schoolFilter;
        const searchMatch = !searchTerm || content.includes(searchTerm);

        if (statusMatch && typeMatch && schoolMatch && searchMatch) {
            item.style.display = '';
        } else {
            item.style.display = 'none';
        }
    });
}

// Feedback actions
function markAsReviewed(campaign, school) {
    if (confirm('Mark this feedback as reviewed?')) {
        alert('Feedback marked as reviewed!');
        location.reload();
    }
}

function replyToFeedback(campaign, school) {
    document.getElementById('originalFeedback').innerHTML = `
        <strong>Campaign:</strong> ${campaign}<br>
        <strong>School:</strong> ${school}<br>
        <strong>Feedback:</strong> Sample feedback content...
    `;
    $('#replyModal').modal('show');
}

function sendReply() {
    const form = document.getElementById('replyForm');
    const replyMessage = document.getElementById('replyMessage').value;
    
    if (!replyMessage.trim()) {
        alert('Please enter a reply message.');
        return;
    }
    
    alert('Reply sent successfully!');
    $('#replyModal').modal('hide');
    form.reset();
}

function viewDetails(campaign, school) {
    alert(`Viewing details for feedback from ${school} regarding "${campaign}"`);
}

function flagForFollowUp(campaign, school) {
    if (confirm('Flag this feedback for follow-up?')) {
        alert('Feedback flagged for follow-up!');
    }
}

function exportFeedback(campaign, school) {
    alert(`Exporting feedback from ${school} regarding "${campaign}"`);
}

function deleteFeedback(campaign, school) {
    if (confirm('Are you sure you want to delete this feedback? This action cannot be undone.')) {
        alert('Feedback deleted successfully!');
        location.reload();
    }
}

function markAllAsRead() {
    if (confirm('Mark all pending feedback as reviewed?')) {
        alert('All feedback marked as reviewed!');
        location.reload();
    }
}

function generateFeedbackReport() {
    alert('Generating comprehensive feedback report...');
}

// Initialize filters when page loads
document.addEventListener('DOMContentLoaded', function() {
    setupFilters();
});
</script>
@endpush
