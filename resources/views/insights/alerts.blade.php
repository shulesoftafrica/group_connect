@extends('layouts.admin')

@section('title', 'Alerts & Exception Management')

@section('page_title', 'Alerts & Exception Management')

@section('breadcrumb')
<ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('insights.dashboard') }}">Insights</a></li>
    <li class="breadcrumb-item active">Alerts</li>
</ol>
@endsection

@section('content')
<div class="alerts-management">
    <!-- Alert Summary Cards -->
    <div class="row mb-4">
        <div class="col-lg-3 col-md-6">
            <div class="card bg-danger text-white h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="mb-0">5</h4>
                            <small>Critical Alerts</small>
                        </div>
                        <div class="text-right">
                            <i class="fas fa-exclamation-circle fa-2x opacity-75"></i>
                            <div class="mt-1">
                                <small>2 new today</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-3 col-md-6">
            <div class="card bg-warning text-white h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="mb-0">12</h4>
                            <small>High Priority</small>
                        </div>
                        <div class="text-right">
                            <i class="fas fa-exclamation-triangle fa-2x opacity-75"></i>
                            <div class="mt-1">
                                <small>6 pending</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-3 col-md-6">
            <div class="card bg-info text-white h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="mb-0">8</h4>
                            <small>Medium Priority</small>
                        </div>
                        <div class="text-right">
                            <i class="fas fa-info-circle fa-2x opacity-75"></i>
                            <div class="mt-1">
                                <small>3 resolved</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-3 col-md-6">
            <div class="card bg-success text-white h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="mb-0">94.2%</h4>
                            <small>Resolution Rate</small>
                        </div>
                        <div class="text-right">
                            <i class="fas fa-check-circle fa-2x opacity-75"></i>
                            <div class="mt-1">
                                <small>24h average</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Alert Filters & Actions -->
    <div class="row mb-4">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-body">
                    <div class="alert-filters">
                        <div class="row">
                            <div class="col-md-3">
                                <select class="form-control" id="severityFilter">
                                    <option value="">All Severities</option>
                                    <option value="critical">Critical</option>
                                    <option value="high">High</option>
                                    <option value="medium">Medium</option>
                                    <option value="low">Low</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <select class="form-control" id="categoryFilter">
                                    <option value="">All Categories</option>
                                    <option value="financial">Financial</option>
                                    <option value="academic">Academic</option>
                                    <option value="operational">Operational</option>
                                    <option value="compliance">Compliance</option>
                                    <option value="hr">Human Resources</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <select class="form-control" id="statusFilter">
                                    <option value="">All Status</option>
                                    <option value="active">Active</option>
                                    <option value="investigating">Investigating</option>
                                    <option value="resolved">Resolved</option>
                                    <option value="dismissed">Dismissed</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <input type="text" class="form-control" placeholder="Search alerts..." id="searchAlerts">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="card">
                <div class="card-body">
                    <div class="quick-actions">
                        <button class="btn btn-primary btn-sm mr-2" data-toggle="modal" data-target="#createAlertRuleModal">
                            <i class="fas fa-plus mr-1"></i>New Rule
                        </button>
                        <button class="btn btn-outline-secondary btn-sm mr-2" onclick="markAllAsRead()">
                            <i class="fas fa-check mr-1"></i>Mark All Read
                        </button>
                        <button class="btn btn-outline-danger btn-sm" onclick="bulkDismiss()">
                            <i class="fas fa-times mr-1"></i>Bulk Dismiss
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Active Alerts -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-bell mr-2"></i>Active Alerts
                        <span class="badge badge-danger ml-2">25 Active</span>
                    </h5>
                </div>
                <div class="card-body p-0">
                    <div class="alerts-list">
                        <!-- Critical Alert -->
                        <div class="alert-item border-left-danger p-3 border-bottom" data-severity="critical" data-category="financial">
                            <div class="d-flex justify-content-between align-items-start">
                                <div class="alert-content flex-grow-1">
                                    <div class="alert-header d-flex align-items-center mb-2">
                                        <span class="badge badge-danger mr-2">Critical</span>
                                        <span class="badge badge-outline-secondary mr-2">Financial</span>
                                        <h6 class="mb-0">Fee Collection Crisis - Coast Academy</h6>
                                        <span class="alert-time ml-auto text-muted">2 hours ago</span>
                                    </div>
                                    <div class="alert-description mb-2">
                                        <p class="mb-1">Fee collection rate has dropped to 45% at Coast Academy, triggering critical threshold. Outstanding amount: $65,000.</p>
                                        <div class="alert-details">
                                            <small class="text-muted">
                                                <i class="fas fa-school mr-1"></i>Coast Academy, Lamu
                                                <span class="mx-2">•</span>
                                                <i class="fas fa-users mr-1"></i>250 students affected
                                                <span class="mx-2">•</span>
                                                <i class="fas fa-chart-line mr-1"></i>65% below target
                                            </small>
                                        </div>
                                    </div>
                                    <div class="alert-actions">
                                        <button class="btn btn-sm btn-outline-primary mr-1" onclick="investigateAlert('alert-1')">
                                            <i class="fas fa-search mr-1"></i>Investigate
                                        </button>
                                        <button class="btn btn-sm btn-outline-success mr-1" onclick="createActionPlan('alert-1')">
                                            <i class="fas fa-tasks mr-1"></i>Action Plan
                                        </button>
                                        <button class="btn btn-sm btn-outline-warning mr-1" onclick="escalateAlert('alert-1')">
                                            <i class="fas fa-level-up-alt mr-1"></i>Escalate
                                        </button>
                                        <button class="btn btn-sm btn-outline-secondary" onclick="dismissAlert('alert-1')">
                                            <i class="fas fa-times mr-1"></i>Dismiss
                                        </button>
                                    </div>
                                </div>
                                <div class="alert-indicator">
                                    <div class="status-dot bg-danger pulse"></div>
                                </div>
                            </div>
                        </div>

                        <!-- High Priority Alert -->
                        <div class="alert-item border-left-warning p-3 border-bottom" data-severity="high" data-category="academic">
                            <div class="d-flex justify-content-between align-items-start">
                                <div class="alert-content flex-grow-1">
                                    <div class="alert-header d-flex align-items-center mb-2">
                                        <span class="badge badge-warning mr-2">High</span>
                                        <span class="badge badge-outline-secondary mr-2">Academic</span>
                                        <h6 class="mb-0">Mathematics Performance Decline</h6>
                                        <span class="alert-time ml-auto text-muted">5 hours ago</span>
                                    </div>
                                    <div class="alert-description mb-2">
                                        <p class="mb-1">Mathematics scores have declined by 15% across 3 schools in North Eastern region over the past month.</p>
                                        <div class="alert-details">
                                            <small class="text-muted">
                                                <i class="fas fa-calculator mr-1"></i>Mathematics Subject
                                                <span class="mx-2">•</span>
                                                <i class="fas fa-map-marker-alt mr-1"></i>North Eastern Region
                                                <span class="mx-2">•</span>
                                                <i class="fas fa-trending-down mr-1"></i>15% decline
                                            </small>
                                        </div>
                                    </div>
                                    <div class="alert-actions">
                                        <button class="btn btn-sm btn-outline-primary mr-1" onclick="investigateAlert('alert-2')">
                                            <i class="fas fa-search mr-1"></i>Investigate
                                        </button>
                                        <button class="btn btn-sm btn-outline-success mr-1" onclick="scheduleTraining('alert-2')">
                                            <i class="fas fa-graduation-cap mr-1"></i>Schedule Training
                                        </button>
                                        <button class="btn btn-sm btn-outline-secondary" onclick="dismissAlert('alert-2')">
                                            <i class="fas fa-times mr-1"></i>Dismiss
                                        </button>
                                    </div>
                                </div>
                                <div class="alert-indicator">
                                    <div class="status-dot bg-warning"></div>
                                </div>
                            </div>
                        </div>

                        <!-- Medium Priority Alert -->
                        <div class="alert-item border-left-info p-3 border-bottom" data-severity="medium" data-category="operational">
                            <div class="d-flex justify-content-between align-items-start">
                                <div class="alert-content flex-grow-1">
                                    <div class="alert-header d-flex align-items-center mb-2">
                                        <span class="badge badge-info mr-2">Medium</span>
                                        <span class="badge badge-outline-secondary mr-2">Operational</span>
                                        <h6 class="mb-0">Teacher Shortage - Multiple Schools</h6>
                                        <span class="alert-time ml-auto text-muted">1 day ago</span>
                                    </div>
                                    <div class="alert-description mb-2">
                                        <p class="mb-1">5 schools report teacher shortages affecting student-teacher ratios above optimal levels.</p>
                                        <div class="alert-details">
                                            <small class="text-muted">
                                                <i class="fas fa-users mr-1"></i>5 schools affected
                                                <span class="mx-2">•</span>
                                                <i class="fas fa-user-tie mr-1"></i>12 positions vacant
                                                <span class="mx-2">•</span>
                                                <i class="fas fa-clock mr-1"></i>Urgent recruitment needed
                                            </small>
                                        </div>
                                    </div>
                                    <div class="alert-actions">
                                        <button class="btn btn-sm btn-outline-primary mr-1" onclick="startRecruitment('alert-3')">
                                            <i class="fas fa-user-plus mr-1"></i>Start Recruitment
                                        </button>
                                        <button class="btn btn-sm btn-outline-success mr-1" onclick="reallocateStaff('alert-3')">
                                            <i class="fas fa-exchange-alt mr-1"></i>Reallocate Staff
                                        </button>
                                        <button class="btn btn-sm btn-outline-secondary" onclick="dismissAlert('alert-3')">
                                            <i class="fas fa-times mr-1"></i>Dismiss
                                        </button>
                                    </div>
                                </div>
                                <div class="alert-indicator">
                                    <div class="status-dot bg-info"></div>
                                </div>
                            </div>
                        </div>

                        <!-- More alerts... -->
                        <div class="alert-item border-left-secondary p-3" data-severity="low" data-category="compliance">
                            <div class="d-flex justify-content-between align-items-start">
                                <div class="alert-content flex-grow-1">
                                    <div class="alert-header d-flex align-items-center mb-2">
                                        <span class="badge badge-secondary mr-2">Low</span>
                                        <span class="badge badge-outline-secondary mr-2">Compliance</span>
                                        <h6 class="mb-0">Monthly Reports Overdue</h6>
                                        <span class="alert-time ml-auto text-muted">3 days ago</span>
                                    </div>
                                    <div class="alert-description mb-2">
                                        <p class="mb-1">2 schools have not submitted monthly operational reports as per compliance requirements.</p>
                                        <div class="alert-details">
                                            <small class="text-muted">
                                                <i class="fas fa-file-alt mr-1"></i>Monthly Reports
                                                <span class="mx-2">•</span>
                                                <i class="fas fa-school mr-1"></i>2 schools overdue
                                                <span class="mx-2">•</span>
                                                <i class="fas fa-calendar mr-1"></i>Due 3 days ago
                                            </small>
                                        </div>
                                    </div>
                                    <div class="alert-actions">
                                        <button class="btn btn-sm btn-outline-primary mr-1" onclick="sendReminder('alert-4')">
                                            <i class="fas fa-bell mr-1"></i>Send Reminder
                                        </button>
                                        <button class="btn btn-sm btn-outline-warning mr-1" onclick="escalateCompliance('alert-4')">
                                            <i class="fas fa-exclamation-triangle mr-1"></i>Escalate
                                        </button>
                                        <button class="btn btn-sm btn-outline-secondary" onclick="dismissAlert('alert-4')">
                                            <i class="fas fa-times mr-1"></i>Dismiss
                                        </button>
                                    </div>
                                </div>
                                <div class="alert-indicator">
                                    <div class="status-dot bg-secondary"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="text-muted">Showing 4 of 25 alerts</span>
                        <nav>
                            <ul class="pagination pagination-sm mb-0">
                                <li class="page-item"><a class="page-link" href="#">Previous</a></li>
                                <li class="page-item active"><a class="page-link" href="#">1</a></li>
                                <li class="page-item"><a class="page-link" href="#">2</a></li>
                                <li class="page-item"><a class="page-link" href="#">3</a></li>
                                <li class="page-item"><a class="page-link" href="#">Next</a></li>
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Alert Rules & Settings -->
    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-cogs mr-2"></i>Alert Rules Configuration
                    </h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th>Rule Name</th>
                                    <th>Category</th>
                                    <th>Condition</th>
                                    <th>Severity</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><strong>Fee Collection Critical</strong></td>
                                    <td><span class="badge badge-outline-danger">Financial</span></td>
                                    <td><small>Collection rate < 50%</small></td>
                                    <td><span class="badge badge-danger">Critical</span></td>
                                    <td><span class="badge badge-success">Active</span></td>
                                    <td>
                                        <button class="btn btn-sm btn-outline-primary" onclick="editRule('rule-1')">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="btn btn-sm btn-outline-danger" onclick="disableRule('rule-1')">
                                            <i class="fas fa-pause"></i>
                                        </button>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Academic Performance Drop</strong></td>
                                    <td><span class="badge badge-outline-warning">Academic</span></td>
                                    <td><small>Grade avg decline > 10%</small></td>
                                    <td><span class="badge badge-warning">High</span></td>
                                    <td><span class="badge badge-success">Active</span></td>
                                    <td>
                                        <button class="btn btn-sm btn-outline-primary" onclick="editRule('rule-2')">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="btn btn-sm btn-outline-danger" onclick="disableRule('rule-2')">
                                            <i class="fas fa-pause"></i>
                                        </button>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Attendance Threshold</strong></td>
                                    <td><span class="badge badge-outline-info">Operational</span></td>
                                    <td><small>Attendance rate < 85%</small></td>
                                    <td><span class="badge badge-info">Medium</span></td>
                                    <td><span class="badge badge-success">Active</span></td>
                                    <td>
                                        <button class="btn btn-sm btn-outline-primary" onclick="editRule('rule-3')">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="btn btn-sm btn-outline-danger" onclick="disableRule('rule-3')">
                                            <i class="fas fa-pause"></i>
                                        </button>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Staff Shortage Alert</strong></td>
                                    <td><span class="badge badge-outline-secondary">HR</span></td>
                                    <td><small>Student:Teacher ratio > 35:1</small></td>
                                    <td><span class="badge badge-warning">High</span></td>
                                    <td><span class="badge badge-secondary">Paused</span></td>
                                    <td>
                                        <button class="btn btn-sm btn-outline-primary" onclick="editRule('rule-4')">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="btn btn-sm btn-outline-success" onclick="enableRule('rule-4')">
                                            <i class="fas fa-play"></i>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-chart-pie mr-2"></i>Alert Statistics
                    </h5>
                </div>
                <div class="card-body">
                    <canvas id="alertStatsChart" height="250"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Create Alert Rule Modal -->
<div class="modal fade" id="createAlertRuleModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Create New Alert Rule</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <form id="alertRuleForm">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Rule Name</label>
                                <input type="text" class="form-control" name="rule_name" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Category</label>
                                <select class="form-control" name="category" required>
                                    <option value="">Select Category</option>
                                    <option value="financial">Financial</option>
                                    <option value="academic">Academic</option>
                                    <option value="operational">Operational</option>
                                    <option value="compliance">Compliance</option>
                                    <option value="hr">Human Resources</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Metric</label>
                                <select class="form-control" name="metric" required>
                                    <option value="">Select Metric</option>
                                    <option value="fee_collection_rate">Fee Collection Rate</option>
                                    <option value="attendance_rate">Attendance Rate</option>
                                    <option value="academic_average">Academic Average</option>
                                    <option value="student_teacher_ratio">Student-Teacher Ratio</option>
                                    <option value="enrollment_change">Enrollment Change</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Condition</label>
                                <select class="form-control" name="condition" required>
                                    <option value="<">Less than</option>
                                    <option value=">">Greater than</option>
                                    <option value="=">Equals</option>
                                    <option value="!=">Not equals</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Threshold Value</label>
                                <input type="number" class="form-control" name="threshold" step="0.01" required>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Severity</label>
                                <select class="form-control" name="severity" required>
                                    <option value="">Select Severity</option>
                                    <option value="critical">Critical</option>
                                    <option value="high">High</option>
                                    <option value="medium">Medium</option>
                                    <option value="low">Low</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Check Frequency</label>
                                <select class="form-control" name="frequency" required>
                                    <option value="">Select Frequency</option>
                                    <option value="real-time">Real-time</option>
                                    <option value="hourly">Hourly</option>
                                    <option value="daily">Daily</option>
                                    <option value="weekly">Weekly</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label>Description</label>
                        <textarea class="form-control" name="description" rows="3" placeholder="Describe what this alert monitors..."></textarea>
                    </div>
                    
                    <div class="form-group">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="email_notifications" id="emailNotif">
                            <label class="form-check-label" for="emailNotif">
                                Send email notifications
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="auto_escalate" id="autoEscalate">
                            <label class="form-check-label" for="autoEscalate">
                                Auto-escalate after 24 hours
                            </label>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="saveAlertRule()">Create Rule</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Alert Statistics Chart
const alertStatsCtx = document.getElementById('alertStatsChart').getContext('2d');
new Chart(alertStatsCtx, {
    type: 'doughnut',
    data: {
        labels: ['Critical', 'High', 'Medium', 'Low'],
        datasets: [{
            data: [5, 12, 8, 15],
            backgroundColor: ['#dc3545', '#ffc107', '#17a2b8', '#6c757d']
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

// Alert Management Functions
function investigateAlert(alertId) {
    // Implementation for investigating alert
    console.log('Investigating alert:', alertId);
    alert('Investigation started for alert ' + alertId);
}

function createActionPlan(alertId) {
    // Implementation for creating action plan
    console.log('Creating action plan for:', alertId);
    alert('Action plan created for alert ' + alertId);
}

function escalateAlert(alertId) {
    // Implementation for escalating alert
    console.log('Escalating alert:', alertId);
    alert('Alert ' + alertId + ' has been escalated');
}

function dismissAlert(alertId) {
    if (confirm('Are you sure you want to dismiss this alert?')) {
        // Implementation for dismissing alert
        console.log('Dismissing alert:', alertId);
        document.querySelector(`[onclick="dismissAlert('${alertId}')"]`).closest('.alert-item').remove();
    }
}

function markAllAsRead() {
    if (confirm('Mark all alerts as read?')) {
        // Implementation for marking all as read
        console.log('Marking all alerts as read');
        alert('All alerts marked as read');
    }
}

function bulkDismiss() {
    if (confirm('Dismiss all selected alerts?')) {
        // Implementation for bulk dismiss
        console.log('Bulk dismissing alerts');
        alert('Selected alerts dismissed');
    }
}

function editRule(ruleId) {
    // Implementation for editing rule
    console.log('Editing rule:', ruleId);
    alert('Edit rule ' + ruleId);
}

function disableRule(ruleId) {
    if (confirm('Disable this alert rule?')) {
        // Implementation for disabling rule
        console.log('Disabling rule:', ruleId);
        alert('Rule ' + ruleId + ' disabled');
    }
}

function enableRule(ruleId) {
    // Implementation for enabling rule
    console.log('Enabling rule:', ruleId);
    alert('Rule ' + ruleId + ' enabled');
}

function saveAlertRule() {
    const form = document.getElementById('alertRuleForm');
    const formData = new FormData(form);
    
    // Basic validation
    if (!formData.get('rule_name') || !formData.get('category') || !formData.get('metric')) {
        alert('Please fill in all required fields');
        return;
    }
    
    // Implementation for saving alert rule
    console.log('Saving alert rule:', Object.fromEntries(formData));
    alert('Alert rule created successfully');
    $('#createAlertRuleModal').modal('hide');
    form.reset();
}

// Search and Filter Functions
document.getElementById('searchAlerts').addEventListener('input', function(e) {
    const searchTerm = e.target.value.toLowerCase();
    const alerts = document.querySelectorAll('.alert-item');
    
    alerts.forEach(alert => {
        const text = alert.textContent.toLowerCase();
        alert.style.display = text.includes(searchTerm) ? 'block' : 'none';
    });
});

document.getElementById('severityFilter').addEventListener('change', function(e) {
    filterAlerts('severity', e.target.value);
});

document.getElementById('categoryFilter').addEventListener('change', function(e) {
    filterAlerts('category', e.target.value);
});

function filterAlerts(filterType, filterValue) {
    const alerts = document.querySelectorAll('.alert-item');
    
    alerts.forEach(alert => {
        if (filterValue === '') {
            alert.style.display = 'block';
        } else {
            const dataValue = alert.getAttribute(`data-${filterType}`);
            alert.style.display = dataValue === filterValue ? 'block' : 'none';
        }
    });
}

// Auto-refresh alerts every 2 minutes
setInterval(function() {
    // Implementation for auto-refresh
    console.log('Auto-refreshing alerts...');
}, 120000);

// Additional alert-specific actions
function scheduleTraining(alertId) {
    console.log('Scheduling training for:', alertId);
    alert('Training session scheduled for mathematics improvement');
}

function startRecruitment(alertId) {
    console.log('Starting recruitment for:', alertId);
    alert('Recruitment process initiated for teacher positions');
}

function reallocateStaff(alertId) {
    console.log('Reallocating staff for:', alertId);
    alert('Staff reallocation plan created');
}

function sendReminder(alertId) {
    console.log('Sending reminder for:', alertId);
    alert('Reminder sent to schools for report submission');
}

function escalateCompliance(alertId) {
    console.log('Escalating compliance issue:', alertId);
    alert('Compliance issue escalated to management');
}
</script>
@endsection

@section('styles')
<style>
.alerts-management .border-left-danger {
    border-left: 4px solid #dc3545 !important;
}

.alerts-management .border-left-warning {
    border-left: 4px solid #ffc107 !important;
}

.alerts-management .border-left-info {
    border-left: 4px solid #17a2b8 !important;
}

.alerts-management .border-left-secondary {
    border-left: 4px solid #6c757d !important;
}

.alerts-management .status-dot {
    width: 12px;
    height: 12px;
    border-radius: 50%;
}

.alerts-management .pulse {
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0% { opacity: 1; }
    50% { opacity: 0.5; }
    100% { opacity: 1; }
}

.alerts-management .alert-item {
    transition: background-color 0.2s, box-shadow 0.2s;
}

.alerts-management .alert-item:hover {
    background-color: #f8f9fa;
    box-shadow: 0 2px 5px rgba(0,0,0,0.1);
}

.alerts-management .alert-header h6 {
    color: #495057;
    font-weight: 600;
}

.alerts-management .alert-actions .btn {
    border-radius: 15px;
    font-size: 12px;
    padding: 4px 12px;
}

.alerts-management .badge-outline-secondary {
    color: #6c757d;
    border: 1px solid #6c757d;
    background: transparent;
}

.alerts-management .badge-outline-danger {
    color: #dc3545;
    border: 1px solid #dc3545;
    background: transparent;
}

.alerts-management .badge-outline-warning {
    color: #ffc107;
    border: 1px solid #ffc107;
    background: transparent;
}

.alerts-management .badge-outline-info {
    color: #17a2b8;
    border: 1px solid #17a2b8;
    background: transparent;
}

.alerts-management .quick-actions {
    text-align: center;
}

@media (max-width: 768px) {
    .alerts-management .alert-actions {
        margin-top: 10px;
    }
    
    .alerts-management .alert-actions .btn {
        margin-bottom: 5px;
    }
    
    .alerts-management .alert-header {
        flex-direction: column;
        align-items: flex-start !important;
    }
    
    .alerts-management .alert-time {
        margin-left: 0 !important;
        margin-top: 5px;
    }
}
</style>
@endsection
