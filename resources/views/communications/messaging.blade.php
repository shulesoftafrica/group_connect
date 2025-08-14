@extends('layouts.admin')

@section('title', 'Messaging Center')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">Messaging Center</h1>
            <p class="mb-0 text-muted">Send targeted messages to schools and staff across your group</p>
        </div>
        <div class="d-flex gap-2">
            <button class="btn btn-outline-primary" onclick="loadTemplate()">
                <i class="fas fa-file-alt me-2"></i>Load Template
            </button>
            <button class="btn btn-success" onclick="sendMessage()">
                <i class="fas fa-paper-plane me-2"></i>Send Message
            </button>
        </div>
    </div>

    <div class="row">
        <!-- Message Composition -->
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Compose Message</h6>
                </div>
                <div class="card-body">
                    <form id="messageForm">
                        <!-- Message Type Selection -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="messageType" class="form-label">Message Type *</label>
                                <div class="btn-group d-flex" role="group">
                                    <input type="radio" class="btn-check" name="message_type" id="typeSMS" value="sms" checked>
                                    <label class="btn btn-outline-primary" for="typeSMS">
                                        <i class="fas fa-sms me-2"></i>SMS
                                    </label>

                                    <input type="radio" class="btn-check" name="message_type" id="typeEmail" value="email">
                                    <label class="btn btn-outline-primary" for="typeEmail">
                                        <i class="fas fa-envelope me-2"></i>Email
                                    </label>

                                    <input type="radio" class="btn-check" name="message_type" id="typeWhatsApp" value="whatsapp">
                                    <label class="btn btn-outline-primary" for="typeWhatsApp">
                                        <i class="fab fa-whatsapp me-2"></i>WhatsApp
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label for="messagePriority" class="form-label">Priority</label>
                                <select class="form-select" id="messagePriority" name="priority">
                                    <option value="normal">Normal</option>
                                    <option value="high">High</option>
                                    <option value="urgent">Urgent</option>
                                </select>
                            </div>
                        </div>

                        <!-- Subject (for Email) -->
                        <div class="mb-3" id="subjectField" style="display: none;">
                            <label for="messageSubject" class="form-label">Subject *</label>
                            <input type="text" class="form-control" id="messageSubject" name="subject" placeholder="Enter email subject">
                        </div>

                        <!-- Message Content -->
                        <div class="mb-3">
                            <label for="messageContent" class="form-label">Message Content *</label>
                            <textarea class="form-control" id="messageContent" name="message" rows="8" placeholder="Type your message here..." required></textarea>
                            <div class="form-text">
                                Available merge fields: [SCHOOL_NAME], [RECIPIENT_NAME], [DATE], [TIME]
                                <br>
                                <span id="charCount">0</span> characters (<span id="smsCount">0</span> SMS)
                            </div>
                        </div>

                        <!-- Message Templates -->
                        <div class="mb-3">
                            <label class="form-label">Quick Templates</label>
                            <div class="d-flex gap-2 flex-wrap">
                                <button type="button" class="btn btn-sm btn-outline-secondary" onclick="insertTemplate('greeting')">
                                    Dear [RECIPIENT_NAME],
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-secondary" onclick="insertTemplate('urgent')">
                                    URGENT: 
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-secondary" onclick="insertTemplate('reminder')">
                                    Reminder: 
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-secondary" onclick="insertTemplate('closing')">
                                    Best regards, [SCHOOL_NAME]
                                </button>
                            </div>
                        </div>

                        <!-- Scheduling -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="scheduleOption" class="form-label">Send Option</label>
                                <select class="form-select" id="scheduleOption" name="schedule_option">
                                    <option value="now">Send Now</option>
                                    <option value="schedule">Schedule for Later</option>
                                </select>
                            </div>
                            <div class="col-md-6" id="scheduleDateTimeField" style="display: none;">
                                <label for="scheduleDateTime" class="form-label">Schedule Date & Time</label>
                                <input type="datetime-local" class="form-control" id="scheduleDateTime" name="schedule_datetime">
                            </div>
                        </div>

                        <!-- Message Options -->
                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="requestDeliveryReport" name="delivery_report" checked>
                                <label class="form-check-label" for="requestDeliveryReport">
                                    Request delivery report
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="requestReadReceipt" name="read_receipt">
                                <label class="form-check-label" for="requestReadReceipt">
                                    Request read receipt (Email only)
                                </label>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Recipients Selection -->
        <div class="col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Recipients</h6>
                </div>
                <div class="card-body">
                    <!-- Quick Selection -->
                    <div class="mb-3">
                        <label class="form-label">Quick Selection</label>
                        <div class="d-grid gap-2">
                            <button type="button" class="btn btn-outline-primary btn-sm" onclick="selectAllPrincipals()">
                                <i class="fas fa-user-tie me-2"></i>All Principals ({{ count($schools) }})
                            </button>
                            <button type="button" class="btn btn-outline-info btn-sm" onclick="selectAllTeachers()">
                                <i class="fas fa-chalkboard-teacher me-2"></i>All Teachers
                            </button>
                            <button type="button" class="btn btn-outline-success btn-sm" onclick="selectAllAdminStaff()">
                                <i class="fas fa-users-cog me-2"></i>All Admin Staff
                            </button>
                        </div>
                    </div>

                    <hr>

                    <!-- School Selection -->
                    <div class="mb-3">
                        <label class="form-label">Select Schools</label>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="selectAllSchools" onchange="toggleAllSchools()">
                            <label class="form-check-label" for="selectAllSchools">
                                <strong>Select All Schools</strong>
                            </label>
                        </div>
                        <hr class="my-2">
                        <div class="max-height-200 overflow-auto">
                            @foreach($schools as $school)
                            <div class="form-check">
                                <input class="form-check-input school-checkbox" type="checkbox" name="schools[]" id="school{{ $school->id }}" value="{{ $school->id }}">
                                <label class="form-check-label" for="school{{ $school->id }}">
                                    {{ $school->name ?? 'School ' . $school->id }}
                                    <small class="text-muted d-block">{{ $school->address ?? 'Location ' . $school->id }}</small>
                                </label>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Role Selection -->
                    <div class="mb-3">
                        <label class="form-label">Select Roles</label>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="roles[]" id="rolePrincipal" value="principal">
                            <label class="form-check-label" for="rolePrincipal">Principals</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="roles[]" id="roleTeacher" value="teacher">
                            <label class="form-check-label" for="roleTeacher">Teachers</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="roles[]" id="roleAdmin" value="admin">
                            <label class="form-check-label" for="roleAdmin">Admin Staff</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="roles[]" id="roleAccountant" value="accountant">
                            <label class="form-check-label" for="roleAccountant">Accountants</label>
                        </div>
                    </div>

                    <!-- Custom Recipients -->
                    <div class="mb-3">
                        <label for="customRecipients" class="form-label">Custom Recipients</label>
                        <textarea class="form-control" id="customRecipients" name="custom_recipients" rows="3" placeholder="Enter phone numbers or emails, one per line"></textarea>
                        <div class="form-text">For SMS: +1234567890<br>For Email: user@example.com</div>
                    </div>

                    <!-- Recipients Summary -->
                    <div class="alert alert-info">
                        <strong>Selected Recipients:</strong>
                        <div id="recipientsSummary">0 recipients selected</div>
                    </div>
                </div>
            </div>

            <!-- Message Templates -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Message Templates</h6>
                </div>
                <div class="card-body">
                    @foreach($messageTemplates as $template)
                    <div class="border rounded p-2 mb-2 template-item" style="cursor: pointer;" onclick="loadMessageTemplate({{ $template['id'] }})">
                        <strong>{{ $template['name'] }}</strong>
                        <div class="text-muted small">{{ $template['type'] }}</div>
                        <div class="text-truncate small">{{ Str::limit($template['message'], 50) }}</div>
                    </div>
                    @endforeach
                    
                    <button class="btn btn-outline-primary btn-sm w-100 mt-2" data-bs-toggle="modal" data-bs-target="#templateModal">
                        <i class="fas fa-plus me-2"></i>Create New Template
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Template Modal -->
<div class="modal fade" id="templateModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Create Message Template</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="templateForm">
                    <div class="mb-3">
                        <label for="templateName" class="form-label">Template Name</label>
                        <input type="text" class="form-control" id="templateName" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="templateType" class="form-label">Template Type</label>
                        <select class="form-select" id="templateType" name="type" required>
                            <option value="">Select Type</option>
                            <option value="announcement">Announcement</option>
                            <option value="reminder">Reminder</option>
                            <option value="alert">Alert</option>
                            <option value="greeting">Greeting</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="templateSubject" class="form-label">Subject (for Email)</label>
                        <input type="text" class="form-control" id="templateSubject" name="subject">
                    </div>
                    <div class="mb-3">
                        <label for="templateMessage" class="form-label">Message</label>
                        <textarea class="form-control" id="templateMessage" name="message" rows="4" required></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="saveTemplate()">Save Template</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.max-height-200 {
    max-height: 200px;
}

.template-item:hover {
    background-color: #f8f9fa;
}

.btn-check:checked + .btn {
    background-color: #0d6efd;
    border-color: #0d6efd;
    color: white;
}

.character-count {
    font-size: 0.875rem;
    color: #6c757d;
}
</style>
@endpush

@push('scripts')
<script>
let recipientCount = 0;

// Message type handling
document.querySelectorAll('input[name="message_type"]').forEach(radio => {
    radio.addEventListener('change', function() {
        const subjectField = document.getElementById('subjectField');
        const readReceiptOption = document.getElementById('requestReadReceipt');
        
        if (this.value === 'email') {
            subjectField.style.display = 'block';
            document.getElementById('messageSubject').required = true;
            readReceiptOption.disabled = false;
        } else {
            subjectField.style.display = 'none';
            document.getElementById('messageSubject').required = false;
            readReceiptOption.disabled = true;
            readReceiptOption.checked = false;
        }
        
        updateCharacterCount();
    });
});

// Schedule option handling
document.getElementById('scheduleOption').addEventListener('change', function() {
    const scheduleField = document.getElementById('scheduleDateTimeField');
    if (this.value === 'schedule') {
        scheduleField.style.display = 'block';
        document.getElementById('scheduleDateTime').required = true;
    } else {
        scheduleField.style.display = 'none';
        document.getElementById('scheduleDateTime').required = false;
    }
});

// Character count and SMS calculation
document.getElementById('messageContent').addEventListener('input', updateCharacterCount);

function updateCharacterCount() {
    const content = document.getElementById('messageContent').value;
    const charCount = content.length;
    const smsCount = Math.ceil(charCount / 160);
    
    document.getElementById('charCount').textContent = charCount;
    document.getElementById('smsCount').textContent = smsCount;
    
    // Change color based on character count
    const charCountElement = document.getElementById('charCount');
    if (charCount > 160) {
        charCountElement.className = 'text-warning';
    } else if (charCount > 140) {
        charCountElement.className = 'text-info';
    } else {
        charCountElement.className = 'text-muted';
    }
}

// Recipients management
function toggleAllSchools() {
    const selectAll = document.getElementById('selectAllSchools');
    const schoolCheckboxes = document.querySelectorAll('.school-checkbox');
    
    schoolCheckboxes.forEach(checkbox => {
        checkbox.checked = selectAll.checked;
    });
    
    updateRecipientCount();
}

function selectAllPrincipals() {
    // Select all schools and principal role
    document.getElementById('selectAllSchools').checked = true;
    document.getElementById('rolePrincipal').checked = true;
    toggleAllSchools();
    updateRecipientCount();
}

function selectAllTeachers() {
    document.getElementById('selectAllSchools').checked = true;
    document.getElementById('roleTeacher').checked = true;
    toggleAllSchools();
    updateRecipientCount();
}

function selectAllAdminStaff() {
    document.getElementById('selectAllSchools').checked = true;
    document.getElementById('roleAdmin').checked = true;
    toggleAllSchools();
    updateRecipientCount();
}

function updateRecipientCount() {
    const selectedSchools = document.querySelectorAll('.school-checkbox:checked').length;
    const selectedRoles = document.querySelectorAll('input[name="roles[]"]:checked').length;
    const customRecipients = document.getElementById('customRecipients').value.split('\n').filter(line => line.trim()).length;
    
    // Simulate recipient calculation
    let estimatedRecipients = selectedSchools * selectedRoles * 10; // Assuming 10 people per role per school
    estimatedRecipients += customRecipients;
    
    document.getElementById('recipientsSummary').innerHTML = `
        <div>${estimatedRecipients} recipients estimated</div>
        <small class="text-muted">
            ${selectedSchools} schools × ${selectedRoles} roles + ${customRecipients} custom
        </small>
    `;
    
    recipientCount = estimatedRecipients;
}

// Template functions
function insertTemplate(type) {
    const messageContent = document.getElementById('messageContent');
    const currentContent = messageContent.value;
    const cursorPos = messageContent.selectionStart;
    
    let templateText = '';
    switch(type) {
        case 'greeting':
            templateText = 'Dear [RECIPIENT_NAME], ';
            break;
        case 'urgent':
            templateText = 'URGENT: ';
            break;
        case 'reminder':
            templateText = 'Reminder: ';
            break;
        case 'closing':
            templateText = '\n\nBest regards,\n[SCHOOL_NAME]';
            break;
    }
    
    const newContent = currentContent.slice(0, cursorPos) + templateText + currentContent.slice(cursorPos);
    messageContent.value = newContent;
    messageContent.focus();
    messageContent.setSelectionRange(cursorPos + templateText.length, cursorPos + templateText.length);
    
    updateCharacterCount();
}

function loadMessageTemplate(templateId) {
    // In a real implementation, you would fetch the template from the server
    const templates = @json($messageTemplates);
    const template = templates.find(t => t.id === templateId);
    
    if (template) {
        document.getElementById('messageSubject').value = template.subject || '';
        document.getElementById('messageContent').value = template.message;
        updateCharacterCount();
    }
}

function saveTemplate() {
    const form = document.getElementById('templateForm');
    const formData = new FormData(form);
    
    // Simulate template saving
    alert('Template saved successfully!');
    $('#templateModal').modal('hide');
    form.reset();
}

function sendMessage() {
    const form = document.getElementById('messageForm');
    
    if (!form.checkValidity()) {
        form.reportValidity();
        return;
    }
    
    if (recipientCount === 0) {
        alert('Please select at least one recipient.');
        return;
    }
    
    const messageType = document.querySelector('input[name="message_type"]:checked').value;
    const scheduleOption = document.getElementById('scheduleOption').value;
    
    let confirmMessage = `Send ${messageType.toUpperCase()} message to ${recipientCount} recipients`;
    if (scheduleOption === 'schedule') {
        const scheduleTime = document.getElementById('scheduleDateTime').value;
        confirmMessage += ` scheduled for ${scheduleTime}`;
    } else {
        confirmMessage += ' now';
    }
    confirmMessage += '?';
    
    if (confirm(confirmMessage)) {
        // Simulate message sending
        alert('Message sent successfully!');
        form.reset();
        updateRecipientCount();
        updateCharacterCount();
    }
}

// Event listeners for recipient count updates
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.school-checkbox, input[name="roles[]"]').forEach(checkbox => {
        checkbox.addEventListener('change', updateRecipientCount);
    });
    
    document.getElementById('customRecipients').addEventListener('input', updateRecipientCount);
    
    updateRecipientCount();
    updateCharacterCount();
});
</script>
@endpush
