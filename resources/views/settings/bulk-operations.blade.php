@extends('layouts.settings')

@section('title', 'Bulk Operations')
@section('page-title', 'Bulk Operations')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">
            <i class="bi bi-lightning-fill me-2"></i>Bulk Operations
        </h1>
        <div class="btn-toolbar mb-2 mb-md-0">
            <div class="btn-group me-2">
                <button type="button" class="btn btn-outline-primary" onclick="viewHistory()">
                    <i class="bi bi-clock-history"></i> View History
                </button>
                <button type="button" class="btn btn-outline-secondary" onclick="exportTemplates()">
                    <i class="bi bi-download"></i> Templates
                </button>
            </div>
        </div>
    </div>

    <!-- Warning Notice -->
    <div class="alert alert-warning" role="alert">
        <i class="bi bi-exclamation-triangle-fill me-2"></i>
        <strong>Important:</strong> Bulk operations affect multiple schools simultaneously. 
        Please review your selections carefully before proceeding.
    </div>

    <div class="row">
        <!-- Operation Selection -->
        <div class="col-lg-8">
            <!-- Bulk Messaging -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="bi bi-broadcast me-2"></i>Bulk Messaging
                    </h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('settings.bulk-operations.process') }}" method="POST" id="bulkMessageForm">
                        @csrf
                        <input type="hidden" name="operation_type" value="message">

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="message_type" class="form-label">Message Type</label>
                                    <select class="form-select" id="message_type" name="message_type" required>
                                        <option value="">Select Message Type</option>
                                        <option value="notification">General Notification</option>
                                        <option value="announcement">Important Announcement</option>
                                        <option value="policy_update">Policy Update</option>
                                        <option value="system_maintenance">System Maintenance</option>
                                        <option value="urgent_alert">Urgent Alert</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="delivery_method" class="form-label">Delivery Method</label>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="send_email" name="delivery_methods[]" value="email" checked>
                                        <label class="form-check-label" for="send_email">Email</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="send_sms" name="delivery_methods[]" value="sms">
                                        <label class="form-check-label" for="send_sms">SMS</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="send_inapp" name="delivery_methods[]" value="in_app" checked>
                                        <label class="form-check-label" for="send_inapp">In-App Notification</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="message_subject" class="form-label">Subject/Title <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="message_subject" name="subject" 
                                   placeholder="Enter message subject..." required>
                        </div>

                        <div class="mb-3">
                            <label for="message_content" class="form-label">Message Content <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="message_content" name="content" rows="5" 
                                      placeholder="Enter your message content here..." required></textarea>
                            <div class="form-text">
                                <span id="charCount">0</span>/1000 characters
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Target Schools <span class="text-danger">*</span></label>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="school_selection" id="all_schools_msg" value="all" checked>
                                        <label class="form-check-label" for="all_schools_msg">
                                            All Connected Schools ({{ count($schools) }})
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="school_selection" id="selected_schools_msg" value="selected">
                                        <label class="form-check-label" for="selected_schools_msg">
                                            Selected Schools Only
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div id="schoolSelectionMsg" style="display: none;">
                                        <div class="border rounded p-3" style="max-height: 200px; overflow-y: auto;">
                                            @foreach($schools as $school)
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="target_schools[]" 
                                                       value="{{ $school->uid }}" id="msg_school_{{ $school->uid }}">
                                                <label class="form-check-label" for="msg_school_{{ $school->uid }}">
                                                    {{ $school->name }} ({{ $school->school_code }})
                                                </label>
                                            </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="schedule_message" name="schedule_message">
                                <label class="form-check-label" for="schedule_message">
                                    Schedule for later delivery
                                </label>
                            </div>
                        </div>

                        <div id="scheduleSection" class="mb-3" style="display: none;">
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="schedule_date" class="form-label">Schedule Date</label>
                                    <input type="date" class="form-control" id="schedule_date" name="schedule_date">
                                </div>
                                <div class="col-md-6">
                                    <label for="schedule_time" class="form-label">Schedule Time</label>
                                    <input type="time" class="form-control" id="schedule_time" name="schedule_time">
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between">
                            <button type="button" class="btn btn-outline-secondary" onclick="previewMessage()">
                                <i class="bi bi-eye me-2"></i>Preview
                            </button>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-send me-2"></i>Send Message
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Settings Update -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="bi bi-gear-fill me-2"></i>Bulk Settings Update
                    </h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('settings.bulk-operations.process') }}" method="POST" id="bulkSettingsForm">
                        @csrf
                        <input type="hidden" name="operation_type" value="settings_update">

                        <div class="mb-3">
                            <label for="setting_category" class="form-label">Settings Category</label>
                            <select class="form-select" id="setting_category" name="setting_category" required onchange="loadSettingOptions()">
                                <option value="">Select Category</option>
                                <option value="academic">Academic Settings</option>
                                <option value="financial">Financial Settings</option>
                                <option value="notification">Notification Settings</option>
                                <option value="security">Security Settings</option>
                                <option value="appearance">Appearance Settings</option>
                            </select>
                        </div>

                        <div id="settingOptionsContainer" class="mb-3" style="display: none;">
                            <!-- Dynamic content will be loaded here -->
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Target Schools <span class="text-danger">*</span></label>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="school_selection" id="all_schools_settings" value="all" checked>
                                        <label class="form-check-label" for="all_schools_settings">
                                            All Connected Schools
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="school_selection" id="selected_schools_settings" value="selected">
                                        <label class="form-check-label" for="selected_schools_settings">
                                            Selected Schools Only
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div id="schoolSelectionSettings" style="display: none;">
                                        <div class="border rounded p-3" style="max-height: 200px; overflow-y: auto;">
                                            @foreach($schools as $school)
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="target_schools[]" 
                                                       value="{{ $school->uid }}" id="settings_school_{{ $school->uid }}">
                                                <label class="form-check-label" for="settings_school_{{ $school->uid }}">
                                                    {{ $school->name }}
                                                </label>
                                            </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="alert alert-info">
                            <i class="bi bi-info-circle me-2"></i>
                            Settings changes will be applied immediately to selected schools. 
                            Schools will receive a notification about the changes.
                        </div>

                        <div class="d-flex justify-content-between">
                            <button type="button" class="btn btn-outline-secondary" onclick="validateSettings()">
                                <i class="bi bi-check-circle me-2"></i>Validate
                            </button>
                            <button type="submit" class="btn btn-warning">
                                <i class="bi bi-lightning-charge me-2"></i>Apply Settings
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Policy Distribution -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="bi bi-file-earmark-text me-2"></i>Policy Distribution
                    </h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('settings.bulk-operations.process') }}" method="POST" id="bulkPolicyForm" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="operation_type" value="policy_push">

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="policy_type" class="form-label">Policy Type</label>
                                    <select class="form-select" id="policy_type" name="policy_type" required>
                                        <option value="">Select Policy Type</option>
                                        <option value="academic">Academic Policy</option>
                                        <option value="financial">Financial Policy</option>
                                        <option value="hr">HR Policy</option>
                                        <option value="student_conduct">Student Conduct</option>
                                        <option value="safety">Safety & Security</option>
                                        <option value="data_protection">Data Protection</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="policy_priority" class="form-label">Priority Level</label>
                                    <select class="form-select" id="policy_priority" name="policy_priority" required>
                                        <option value="low">Low</option>
                                        <option value="medium" selected>Medium</option>
                                        <option value="high">High</option>
                                        <option value="urgent">Urgent</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="policy_title" class="form-label">Policy Title <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="policy_title" name="policy_title" required>
                        </div>

                        <div class="mb-3">
                            <label for="policy_content" class="form-label">Policy Content</label>
                            <textarea class="form-control" id="policy_content" name="content" rows="6"></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="policy_file" class="form-label">Policy Document</label>
                            <input type="file" class="form-control" id="policy_file" name="policy_file" accept=".pdf,.doc,.docx">
                            <div class="form-text">Upload PDF or Word document (optional)</div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Target Schools <span class="text-danger">*</span></label>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="school_selection" id="all_schools_policy" value="all" checked>
                                <label class="form-check-label" for="all_schools_policy">
                                    All Connected Schools
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="school_selection" id="selected_schools_policy" value="selected">
                                <label class="form-check-label" for="selected_schools_policy">
                                    Selected Schools Only
                                </label>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between">
                            <button type="button" class="btn btn-outline-secondary" onclick="saveDraft()">
                                <i class="bi bi-save me-2"></i>Save Draft
                            </button>
                            <button type="submit" class="btn btn-success">
                                <i class="bi bi-distribution-horizontal me-2"></i>Distribute Policy
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Quick Stats -->
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="bi bi-graph-up me-2"></i>Operation Statistics
                    </h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <div class="d-flex justify-content-between">
                            <span>Messages Sent Today</span>
                            <span class="fw-bold">24</span>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="d-flex justify-content-between">
                            <span>Settings Updated</span>
                            <span class="fw-bold">3</span>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="d-flex justify-content-between">
                            <span>Policies Distributed</span>
                            <span class="fw-bold">1</span>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="d-flex justify-content-between">
                            <span>Success Rate</span>
                            <span class="fw-bold text-success">98.5%</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Operations -->
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="bi bi-clock-history me-2"></i>Recent Operations
                    </h6>
                </div>
                <div class="card-body">
                    <div class="timeline">
                        <div class="timeline-item">
                            <div class="timeline-marker bg-success"></div>
                            <div class="timeline-content">
                                <h6 class="timeline-title">Policy Update Sent</h6>
                                <p class="timeline-info">
                                    <small>Data Protection Policy to 24 schools</small><br>
                                    <small class="text-muted">2 hours ago</small>
                                </p>
                            </div>
                        </div>
                        <div class="timeline-item">
                            <div class="timeline-marker bg-primary"></div>
                            <div class="timeline-content">
                                <h6 class="timeline-title">Bulk Message</h6>
                                <p class="timeline-info">
                                    <small>System maintenance notification</small><br>
                                    <small class="text-muted">5 hours ago</small>
                                </p>
                            </div>
                        </div>
                        <div class="timeline-item">
                            <div class="timeline-marker bg-warning"></div>
                            <div class="timeline-content">
                                <h6 class="timeline-title">Settings Update</h6>
                                <p class="timeline-info">
                                    <small>Academic calendar updated</small><br>
                                    <small class="text-muted">1 day ago</small>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Templates -->
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="bi bi-file-earmark-template me-2"></i>Message Templates
                    </h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <button class="btn btn-sm btn-outline-primary" onclick="loadTemplate('maintenance')">
                            System Maintenance
                        </button>
                        <button class="btn btn-sm btn-outline-primary" onclick="loadTemplate('policy_update')">
                            Policy Update
                        </button>
                        <button class="btn btn-sm btn-outline-primary" onclick="loadTemplate('urgent_alert')">
                            Urgent Alert
                        </button>
                        <button class="btn btn-sm btn-outline-primary" onclick="loadTemplate('welcome')">
                            Welcome Message
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.timeline {
    position: relative;
    padding-left: 2rem;
}
.timeline-item {
    position: relative;
    margin-bottom: 1.5rem;
}
.timeline-marker {
    position: absolute;
    left: -2rem;
    top: 0.2rem;
    width: 12px;
    height: 12px;
    border-radius: 50%;
}
.timeline-marker::before {
    content: '';
    position: absolute;
    left: 50%;
    top: 12px;
    width: 2px;
    height: 1.5rem;
    background: #e3e6f0;
    transform: translateX(-50%);
}
.timeline-item:last-child .timeline-marker::before {
    display: none;
}
.timeline-content {
    padding-left: 1rem;
}
.timeline-title {
    margin-bottom: 0.25rem;
    font-size: 0.9rem;
    font-weight: 600;
}
.timeline-info {
    margin-bottom: 0;
    font-size: 0.8rem;
}
</style>

<script>
// Character count for message content
document.getElementById('message_content').addEventListener('input', function() {
    const charCount = this.value.length;
    document.getElementById('charCount').textContent = charCount;
    
    if (charCount > 1000) {
        this.classList.add('is-invalid');
    } else {
        this.classList.remove('is-invalid');
    }
});

// School selection toggles
document.addEventListener('DOMContentLoaded', function() {
    // Message form
    document.getElementById('selected_schools_msg').addEventListener('change', function() {
        document.getElementById('schoolSelectionMsg').style.display = this.checked ? 'block' : 'none';
    });
    document.getElementById('all_schools_msg').addEventListener('change', function() {
        document.getElementById('schoolSelectionMsg').style.display = 'none';
    });

    // Settings form
    document.getElementById('selected_schools_settings').addEventListener('change', function() {
        document.getElementById('schoolSelectionSettings').style.display = this.checked ? 'block' : 'none';
    });
    document.getElementById('all_schools_settings').addEventListener('change', function() {
        document.getElementById('schoolSelectionSettings').style.display = 'none';
    });

    // Schedule toggle
    document.getElementById('schedule_message').addEventListener('change', function() {
        document.getElementById('scheduleSection').style.display = this.checked ? 'block' : 'none';
    });
});

function loadSettingOptions() {
    const category = document.getElementById('setting_category').value;
    const container = document.getElementById('settingOptionsContainer');
    
    if (!category) {
        container.style.display = 'none';
        return;
    }

    // Simulate loading different setting options based on category
    let content = '';
    switch (category) {
        case 'academic':
            content = `
                <label class="form-label">Academic Settings</label>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="settings[auto_promotion]" value="1">
                    <label class="form-check-label">Enable automatic promotion</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="settings[grade_publication]" value="1">
                    <label class="form-check-label">Auto-publish grades</label>
                </div>
            `;
            break;
        case 'financial':
            content = `
                <label class="form-label">Financial Settings</label>
                <div class="mb-3">
                    <label class="form-label">Late Payment Fee (%)</label>
                    <input type="number" class="form-control" name="settings[late_payment_fee]" min="0" max="100">
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="settings[auto_invoice]" value="1">
                    <label class="form-check-label">Auto-generate invoices</label>
                </div>
            `;
            break;
        // Add more cases as needed
        default:
            content = '<p class="text-muted">Select a category to see available settings.</p>';
    }

    container.innerHTML = content;
    container.style.display = 'block';
}

function previewMessage() {
    const subject = document.getElementById('message_subject').value;
    const content = document.getElementById('message_content').value;
    
    if (!subject || !content) {
        alert('Please enter both subject and message content.');
        return;
    }

    // Show preview modal or window
    alert(`Preview:\n\nSubject: ${subject}\n\nContent: ${content}`);
}

function validateSettings() {
    alert('Settings validation functionality will be implemented.');
}

function saveDraft() {
    alert('Save draft functionality will be implemented.');
}

function loadTemplate(templateType) {
    const templates = {
        'maintenance': {
            subject: 'Scheduled System Maintenance',
            content: 'Dear School Administrator,\n\nWe will be performing scheduled system maintenance on [DATE] from [TIME] to [TIME]. During this period, the system may be temporarily unavailable.\n\nWe apologize for any inconvenience.\n\nBest regards,\nShuleSoft Support Team'
        },
        'policy_update': {
            subject: 'Important Policy Update',
            content: 'Dear School Administrator,\n\nWe have updated our [POLICY NAME] policy. Please review the changes and ensure compliance across your institution.\n\nThe updated policy is attached to this message.\n\nBest regards,\nShuleSoft Management'
        },
        'urgent_alert': {
            subject: 'URGENT: Immediate Action Required',
            content: 'Dear School Administrator,\n\nThis is an urgent notification that requires your immediate attention.\n\n[DESCRIBE THE ISSUE AND REQUIRED ACTION]\n\nPlease respond as soon as possible.\n\nBest regards,\nShuleSoft Support Team'
        },
        'welcome': {
            subject: 'Welcome to ShuleSoft Group Connect',
            content: 'Dear School Administrator,\n\nWelcome to ShuleSoft Group Connect! We are excited to have you as part of our growing network.\n\nYour school has been successfully connected to our platform. You can now access group-wide reports, participate in bulk communications, and benefit from shared resources.\n\nBest regards,\nShuleSoft Team'
        }
    };

    if (templates[templateType]) {
        document.getElementById('message_subject').value = templates[templateType].subject;
        document.getElementById('message_content').value = templates[templateType].content;
        
        // Update character count
        const charCount = templates[templateType].content.length;
        document.getElementById('charCount').textContent = charCount;
    }
}

function viewHistory() {
    window.location.href = '/settings/bulk-operations/history';
}

function exportTemplates() {
    window.location.href = '/settings/bulk-operations/templates/export';
}
</script>
@endsection
