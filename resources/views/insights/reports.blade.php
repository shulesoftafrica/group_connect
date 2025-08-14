@extends('layouts.admin')

@section('title', 'Custom Reports & Report Builder')

@section('page_title', 'Custom Reports & Analytics')

@section('breadcrumb')
<ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('insights.dashboard') }}">Insights</a></li>
    <li class="breadcrumb-item active">Reports</li>
</ol>
@endsection

@section('content')
<div class="reports-interface">
    <!-- Report Builder Header -->
    <div class="row mb-4">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-magic mr-2"></i>AI-Powered Report Builder
                    </h5>
                </div>
                <div class="card-body">
                    <div class="report-builder">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Report Type</label>
                                    <select class="form-control" id="reportType">
                                        <option value="executive_summary">Executive Summary</option>
                                        <option value="financial_analysis">Financial Analysis</option>
                                        <option value="academic_performance">Academic Performance</option>
                                        <option value="operational_metrics">Operational Metrics</option>
                                        <option value="regional_comparison">Regional Comparison</option>
                                        <option value="custom">Custom Report</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Time Period</label>
                                    <select class="form-control" id="timePeriod">
                                        <option value="current_month">Current Month</option>
                                        <option value="last_quarter">Last Quarter</option>
                                        <option value="last_6_months">Last 6 Months</option>
                                        <option value="current_year">Current Year</option>
                                        <option value="custom_range">Custom Date Range</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Schools to Include</label>
                                    <select class="form-control" id="schoolScope" multiple>
                                        <option value="all" selected>All Schools</option>
                                        <option value="greenfield">Greenfield Academy</option>
                                        <option value="sunrise">Sunrise International</option>
                                        <option value="heritage">Heritage School</option>
                                        <option value="excellence">Excellence Prep</option>
                                        <option value="victory">Victory Academy</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Output Format</label>
                                    <select class="form-control" id="outputFormat">
                                        <option value="interactive">Interactive Dashboard</option>
                                        <option value="pdf">PDF Report</option>
                                        <option value="excel">Excel Workbook</option>
                                        <option value="powerpoint">PowerPoint Presentation</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Natural Language Description (Optional)</label>
                            <textarea class="form-control" id="reportDescription" rows="2" 
                                placeholder="Describe what you want to analyze, e.g., 'Show me fee collection trends by region with comparisons to last year'"></textarea>
                        </div>
                        <div class="text-center">
                            <button class="btn btn-primary btn-lg" onclick="generateReport()">
                                <i class="fas fa-chart-bar mr-2"></i>Generate AI Report
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">Quick Report Templates</h6>
                </div>
                <div class="card-body p-0">
                    <div class="template-list">
                        <div class="template-item p-3 border-bottom clickable" onclick="loadTemplate('executive')">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-crown text-warning mr-3"></i>
                                <div>
                                    <strong>Executive Dashboard</strong>
                                    <br><small class="text-muted">High-level KPIs and trends</small>
                                </div>
                            </div>
                        </div>
                        
                        <div class="template-item p-3 border-bottom clickable" onclick="loadTemplate('financial')">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-dollar-sign text-success mr-3"></i>
                                <div>
                                    <strong>Financial Performance</strong>
                                    <br><small class="text-muted">Revenue, costs, profitability</small>
                                </div>
                            </div>
                        </div>
                        
                        <div class="template-item p-3 border-bottom clickable" onclick="loadTemplate('academic')">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-graduation-cap text-primary mr-3"></i>
                                <div>
                                    <strong>Academic Analysis</strong>
                                    <br><small class="text-muted">Student performance metrics</small>
                                </div>
                            </div>
                        </div>
                        
                        <div class="template-item p-3 clickable" onclick="loadTemplate('operational')">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-cogs text-info mr-3"></i>
                                <div>
                                    <strong>Operational Efficiency</strong>
                                    <br><small class="text-muted">Staff, resources, processes</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Pre-built Reports -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-folder mr-2"></i>Pre-built Reports Library
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-4 col-md-6 mb-3">
                            <div class="report-card">
                                <div class="card h-100">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center mb-3">
                                            <div class="report-icon bg-primary text-white rounded-circle mr-3">
                                                <i class="fas fa-chart-line"></i>
                                            </div>
                                            <div>
                                                <h6 class="mb-1">Monthly Performance Summary</h6>
                                                <small class="text-muted">Last updated: Today</small>
                                            </div>
                                        </div>
                                        <p class="text-muted small">Comprehensive overview of all key metrics across schools for the current month.</p>
                                        <div class="report-actions">
                                            <button class="btn btn-outline-primary btn-sm mr-1" onclick="viewReport('monthly_summary')">
                                                <i class="fas fa-eye mr-1"></i>View
                                            </button>
                                            <button class="btn btn-outline-secondary btn-sm" onclick="downloadReport('monthly_summary')">
                                                <i class="fas fa-download mr-1"></i>Download
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-lg-4 col-md-6 mb-3">
                            <div class="report-card">
                                <div class="card h-100">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center mb-3">
                                            <div class="report-icon bg-success text-white rounded-circle mr-3">
                                                <i class="fas fa-money-bill-wave"></i>
                                            </div>
                                            <div>
                                                <h6 class="mb-1">Financial Health Report</h6>
                                                <small class="text-muted">Last updated: Yesterday</small>
                                            </div>
                                        </div>
                                        <p class="text-muted small">Detailed financial analysis including revenue trends, expenses, and profitability by school.</p>
                                        <div class="report-actions">
                                            <button class="btn btn-outline-primary btn-sm mr-1" onclick="viewReport('financial_health')">
                                                <i class="fas fa-eye mr-1"></i>View
                                            </button>
                                            <button class="btn btn-outline-secondary btn-sm" onclick="downloadReport('financial_health')">
                                                <i class="fas fa-download mr-1"></i>Download
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-lg-4 col-md-6 mb-3">
                            <div class="report-card">
                                <div class="card h-100">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center mb-3">
                                            <div class="report-icon bg-info text-white rounded-circle mr-3">
                                                <i class="fas fa-users"></i>
                                            </div>
                                            <div>
                                                <h6 class="mb-1">Student Analytics</h6>
                                                <small class="text-muted">Last updated: 2 hours ago</small>
                                            </div>
                                        </div>
                                        <p class="text-muted small">Student enrollment, retention, academic performance, and progression analysis.</p>
                                        <div class="report-actions">
                                            <button class="btn btn-outline-primary btn-sm mr-1" onclick="viewReport('student_analytics')">
                                                <i class="fas fa-eye mr-1"></i>View
                                            </button>
                                            <button class="btn btn-outline-secondary btn-sm" onclick="downloadReport('student_analytics')">
                                                <i class="fas fa-download mr-1"></i>Download
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-lg-4 col-md-6 mb-3">
                            <div class="report-card">
                                <div class="card h-100">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center mb-3">
                                            <div class="report-icon bg-warning text-white rounded-circle mr-3">
                                                <i class="fas fa-map-marked-alt"></i>
                                            </div>
                                            <div>
                                                <h6 class="mb-1">Regional Performance</h6>
                                                <small class="text-muted">Last updated: This morning</small>
                                            </div>
                                        </div>
                                        <p class="text-muted small">Comparative analysis of performance metrics across different regions.</p>
                                        <div class="report-actions">
                                            <button class="btn btn-outline-primary btn-sm mr-1" onclick="viewReport('regional_performance')">
                                                <i class="fas fa-eye mr-1"></i>View
                                            </button>
                                            <button class="btn btn-outline-secondary btn-sm" onclick="downloadReport('regional_performance')">
                                                <i class="fas fa-download mr-1"></i>Download
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-lg-4 col-md-6 mb-3">
                            <div class="report-card">
                                <div class="card h-100">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center mb-3">
                                            <div class="report-icon bg-danger text-white rounded-circle mr-3">
                                                <i class="fas fa-exclamation-triangle"></i>
                                            </div>
                                            <div>
                                                <h6 class="mb-1">Risk Assessment Report</h6>
                                                <small class="text-muted">Last updated: 6 hours ago</small>
                                            </div>
                                        </div>
                                        <p class="text-muted small">Identifies schools and areas requiring immediate attention and intervention.</p>
                                        <div class="report-actions">
                                            <button class="btn btn-outline-primary btn-sm mr-1" onclick="viewReport('risk_assessment')">
                                                <i class="fas fa-eye mr-1"></i>View
                                            </button>
                                            <button class="btn btn-outline-secondary btn-sm" onclick="downloadReport('risk_assessment')">
                                                <i class="fas fa-download mr-1"></i>Download
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-lg-4 col-md-6 mb-3">
                            <div class="report-card">
                                <div class="card h-100">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center mb-3">
                                            <div class="report-icon bg-secondary text-white rounded-circle mr-3">
                                                <i class="fas fa-crystal-ball"></i>
                                            </div>
                                            <div>
                                                <h6 class="mb-1">Predictive Insights</h6>
                                                <small class="text-muted">Last updated: 1 hour ago</small>
                                            </div>
                                        </div>
                                        <p class="text-muted small">AI-powered forecasts and predictions for enrollment, revenue, and performance.</p>
                                        <div class="report-actions">
                                            <button class="btn btn-outline-primary btn-sm mr-1" onclick="viewReport('predictive_insights')">
                                                <i class="fas fa-eye mr-1"></i>View
                                            </button>
                                            <button class="btn btn-outline-secondary btn-sm" onclick="downloadReport('predictive_insights')">
                                                <i class="fas fa-download mr-1"></i>Download
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scheduled Reports -->
    <div class="row mb-4">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-clock mr-2"></i>Scheduled Reports
                    </h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th>Report Name</th>
                                    <th>Recipients</th>
                                    <th>Schedule</th>
                                    <th>Last Sent</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><strong>Weekly Executive Summary</strong></td>
                                    <td>CEO, COO, CFO</td>
                                    <td>Every Monday 8:00 AM</td>
                                    <td>Aug 12, 2025</td>
                                    <td><span class="badge badge-success">Active</span></td>
                                    <td>
                                        <button class="btn btn-sm btn-outline-primary" onclick="editScheduledReport('weekly_exec')">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="btn btn-sm btn-outline-danger" onclick="pauseScheduledReport('weekly_exec')">
                                            <i class="fas fa-pause"></i>
                                        </button>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Monthly Financial Report</strong></td>
                                    <td>Finance Team, Principals</td>
                                    <td>1st of every month</td>
                                    <td>Aug 1, 2025</td>
                                    <td><span class="badge badge-success">Active</span></td>
                                    <td>
                                        <button class="btn btn-sm btn-outline-primary" onclick="editScheduledReport('monthly_finance')">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="btn btn-sm btn-outline-danger" onclick="pauseScheduledReport('monthly_finance')">
                                            <i class="fas fa-pause"></i>
                                        </button>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Academic Performance Review</strong></td>
                                    <td>Academic Directors</td>
                                    <td>End of each term</td>
                                    <td>Jul 28, 2025</td>
                                    <td><span class="badge badge-warning">Paused</span></td>
                                    <td>
                                        <button class="btn btn-sm btn-outline-primary" onclick="editScheduledReport('academic_review')">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="btn btn-sm btn-outline-success" onclick="resumeScheduledReport('academic_review')">
                                            <i class="fas fa-play"></i>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer">
                    <button class="btn btn-primary" data-toggle="modal" data-target="#scheduleReportModal">
                        <i class="fas fa-plus mr-1"></i>Schedule New Report
                    </button>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">Report Generation History</h6>
                </div>
                <div class="card-body">
                    <div class="history-list">
                        <div class="history-item d-flex justify-content-between align-items-center mb-2">
                            <div>
                                <strong>Monthly Summary</strong>
                                <br><small class="text-muted">Generated 2 hours ago</small>
                            </div>
                            <button class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-download"></i>
                            </button>
                        </div>
                        
                        <div class="history-item d-flex justify-content-between align-items-center mb-2">
                            <div>
                                <strong>Financial Analysis</strong>
                                <br><small class="text-muted">Generated yesterday</small>
                            </div>
                            <button class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-download"></i>
                            </button>
                        </div>
                        
                        <div class="history-item d-flex justify-content-between align-items-center">
                            <div>
                                <strong>Academic Report</strong>
                                <br><small class="text-muted">Generated 3 days ago</small>
                            </div>
                            <button class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-download"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Schedule Report Modal -->
<div class="modal fade" id="scheduleReportModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Schedule Automated Report</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <form id="scheduleReportForm">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Report Template</label>
                                <select class="form-control" name="template" required>
                                    <option value="">Select Template</option>
                                    <option value="executive_summary">Executive Summary</option>
                                    <option value="financial_analysis">Financial Analysis</option>
                                    <option value="academic_performance">Academic Performance</option>
                                    <option value="operational_metrics">Operational Metrics</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Report Name</label>
                                <input type="text" class="form-control" name="report_name" required>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Frequency</label>
                                <select class="form-control" name="frequency" required>
                                    <option value="">Select Frequency</option>
                                    <option value="daily">Daily</option>
                                    <option value="weekly">Weekly</option>
                                    <option value="monthly">Monthly</option>
                                    <option value="quarterly">Quarterly</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Delivery Time</label>
                                <input type="time" class="form-control" name="delivery_time" required>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label>Recipients (Email Addresses)</label>
                        <textarea class="form-control" name="recipients" rows="3" 
                            placeholder="Enter email addresses separated by commas"></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label>Format</label>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="format[]" value="pdf" id="formatPdf">
                                    <label class="form-check-label" for="formatPdf">PDF</label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="format[]" value="excel" id="formatExcel">
                                    <label class="form-check-label" for="formatExcel">Excel</label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="format[]" value="dashboard_link" id="formatDashboard">
                                    <label class="form-check-label" for="formatDashboard">Dashboard Link</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="saveScheduledReport()">Schedule Report</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
function generateReport() {
    const reportType = document.getElementById('reportType').value;
    const timePeriod = document.getElementById('timePeriod').value;
    const schoolScope = document.getElementById('schoolScope').value;
    const outputFormat = document.getElementById('outputFormat').value;
    const description = document.getElementById('reportDescription').value;
    
    // Show loading state
    const btn = event.target;
    const originalText = btn.innerHTML;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Generating...';
    btn.disabled = true;
    
    // Simulate report generation
    setTimeout(() => {
        alert(`AI Report Generated Successfully!\n\nType: ${reportType}\nPeriod: ${timePeriod}\nFormat: ${outputFormat}`);
        btn.innerHTML = originalText;
        btn.disabled = false;
    }, 3000);
}

function loadTemplate(templateType) {
    const templates = {
        'executive': {
            type: 'executive_summary',
            description: 'Comprehensive overview of key performance indicators, trends, and insights across all schools with executive-level recommendations'
        },
        'financial': {
            type: 'financial_analysis',
            description: 'Detailed analysis of revenue streams, cost structures, profitability by school, and financial health indicators'
        },
        'academic': {
            type: 'academic_performance',
            description: 'Student performance metrics, grade distribution, subject-wise analysis, and comparative academic standings'
        },
        'operational': {
            type: 'operational_metrics',
            description: 'Staff utilization, resource efficiency, attendance patterns, and operational performance indicators'
        }
    };
    
    const template = templates[templateType];
    if (template) {
        document.getElementById('reportType').value = template.type;
        document.getElementById('reportDescription').value = template.description;
    }
}

function viewReport(reportId) {
    // Implementation for viewing report
    console.log('Viewing report:', reportId);
    alert('Opening report: ' + reportId);
}

function downloadReport(reportId) {
    // Implementation for downloading report
    console.log('Downloading report:', reportId);
    alert('Downloading report: ' + reportId);
}

function editScheduledReport(reportId) {
    // Implementation for editing scheduled report
    console.log('Editing scheduled report:', reportId);
    alert('Edit scheduled report: ' + reportId);
}

function pauseScheduledReport(reportId) {
    if (confirm('Pause this scheduled report?')) {
        console.log('Pausing scheduled report:', reportId);
        alert('Scheduled report paused: ' + reportId);
    }
}

function resumeScheduledReport(reportId) {
    console.log('Resuming scheduled report:', reportId);
    alert('Scheduled report resumed: ' + reportId);
}

function saveScheduledReport() {
    const form = document.getElementById('scheduleReportForm');
    const formData = new FormData(form);
    
    // Basic validation
    if (!formData.get('template') || !formData.get('report_name') || !formData.get('frequency')) {
        alert('Please fill in all required fields');
        return;
    }
    
    // Implementation for saving scheduled report
    console.log('Saving scheduled report:', Object.fromEntries(formData));
    alert('Scheduled report saved successfully');
    $('#scheduleReportModal').modal('hide');
    form.reset();
}

// Make school scope select multiple work
document.addEventListener('DOMContentLoaded', function() {
    const schoolScope = document.getElementById('schoolScope');
    if (schoolScope) {
        schoolScope.addEventListener('change', function() {
            if (this.value.includes('all')) {
                // If "All Schools" is selected, deselect others
                Array.from(this.options).forEach(option => {
                    if (option.value !== 'all') {
                        option.selected = false;
                    }
                });
            } else {
                // If any specific school is selected, deselect "All Schools"
                const allOption = Array.from(this.options).find(option => option.value === 'all');
                if (allOption) {
                    allOption.selected = false;
                }
            }
        });
    }
});
</script>
@endsection

@section('styles')
<style>
.reports-interface .report-builder {
    background: #f8f9fa;
    padding: 20px;
    border-radius: 8px;
    border: 1px solid #e9ecef;
}

.reports-interface .template-item {
    transition: background-color 0.2s;
    cursor: pointer;
}

.reports-interface .template-item:hover {
    background-color: #f8f9fa;
}

.reports-interface .clickable {
    cursor: pointer;
}

.reports-interface .report-card .card {
    transition: transform 0.2s, box-shadow 0.2s;
}

.reports-interface .report-card .card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}

.reports-interface .report-icon {
    width: 50px;
    height: 50px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
}

.reports-interface .history-item {
    padding: 10px;
    background: #f8f9fa;
    border-radius: 5px;
    margin-bottom: 10px;
}

.reports-interface .form-group label {
    font-weight: 600;
    color: #495057;
}

@media (max-width: 768px) {
    .reports-interface .report-builder {
        padding: 15px;
    }
    
    .reports-interface .template-item {
        text-align: center;
    }
    
    .reports-interface .report-actions {
        text-align: center;
        margin-top: 10px;
    }
}
</style>
@endsection
