@extends('layouts.admin')

@section('title', 'Executive Insights Dashboard')

@section('page_title', 'Executive Insights & Analytics')

@section('breadcrumb')
<ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{ route('insights.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Executive Insights</li>
</ol>
@endsection

@section('content')
<div class="insights-dashboard">
    <!-- Executive Summary Cards -->
    <div class="row mb-4">
        <div class="col-lg-3 col-md-6">
            <div class="card bg-gradient-primary text-white h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="mb-0">{{ number_format($total_schools) }}</h4>
                            <small>Total Schools</small>
                        </div>
                        <div class="text-right">
                            <i class="fas fa-school fa-2x opacity-75"></i>
                            <div class="mt-1">
                                <span class="badge badge-light">{{ $monthly_growth }}% growth</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-3 col-md-6">
            <div class="card bg-gradient-success text-white h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="mb-0">{{ number_format($total_students) }}</h4>
                            <small>Total Students</small>
                        </div>
                        <div class="text-right">
                            <i class="fas fa-user-graduate fa-2x opacity-75"></i>
                            <div class="mt-1">
                                <small>{{ $attendance_rate }}% attendance</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-3 col-md-6">
            <div class="card bg-gradient-info text-white h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="mb-0">${{ number_format($total_revenue) }}</h4>
                            <small>Total Revenue</small>
                        </div>
                        <div class="text-right">
                            <i class="fas fa-dollar-sign fa-2x opacity-75"></i>
                            <div class="mt-1">
                                <small>{{ $fee_collection_rate }}% collected</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-3 col-md-6">
            <div class="card bg-gradient-warning text-white h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="mb-0">{{ $group_performance_score }}%</h4>
                            <small>Group Performance</small>
                        </div>
                        <div class="text-right">
                            <i class="fas fa-chart-line fa-2x opacity-75"></i>
                            <div class="mt-1">
                                <small>AI Score</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- AI Insights Section -->
    <div class="row mb-4">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header bg-dark text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-brain mr-2"></i>AI-Powered Insights
                        <span class="badge badge-light ml-2">Live</span>
                    </h5>
                </div>
                <div class="card-body p-0">
                    <div class="ai-insights-container">
                        @foreach($ai_insights as $insight)
                        <div class="insight-item border-bottom p-3">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <span class="badge badge-{{ $insight['impact'] == 'High' ? 'danger' : ($insight['impact'] == 'Medium' ? 'warning' : 'info') }} mb-2">
                                        {{ $insight['type'] }}
                                    </span>
                                    <p class="mb-1">{{ $insight['insight'] }}</p>
                                    <small class="text-muted">
                                        <i class="fas fa-lightbulb"></i> {{ $insight['action'] }}
                                    </small>
                                </div>
                                <div class="text-right">
                                    <div class="confidence-meter">
                                        <small class="text-muted">Confidence</small>
                                        <div class="progress" style="width: 60px; height: 4px;">
                                            <div class="progress-bar bg-success" style="width: {{ $insight['confidence'] }}%"></div>
                                        </div>
                                        <small class="text-success">{{ $insight['confidence'] }}%</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header bg-danger text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-exclamation-triangle mr-2"></i>Critical Alerts
                    </h5>
                </div>
                <div class="card-body p-0">
                    @foreach($critical_alerts as $alert)
                    <div class="alert-item p-3 border-bottom">
                        <div class="d-flex align-items-center">
                            <div class="alert-icon mr-3">
                                <i class="fas fa-{{ $alert['severity'] == 'Critical' ? 'exclamation-circle text-danger' : ($alert['severity'] == 'High' ? 'exclamation-triangle text-warning' : 'info-circle text-info') }}"></i>
                            </div>
                            <div class="flex-grow-1">
                                <span class="badge badge-{{ $alert['severity'] == 'Critical' ? 'danger' : ($alert['severity'] == 'High' ? 'warning' : 'info') }} badge-sm mb-1">
                                    {{ $alert['type'] }}
                                </span>
                                <p class="mb-1 small">{{ $alert['message'] }}</p>
                                <small class="text-muted">{{ $alert['time'] }}</small>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                <div class="card-footer">
                    <a href="{{ route('insights.alerts') }}" class="btn btn-outline-danger btn-sm">
                        View All Alerts <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Performance Charts -->
    <div class="row mb-4">
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Enrollment Trend</h5>
                </div>
                <div class="card-body">
                    <canvas id="enrollmentTrendChart" height="200"></canvas>
                </div>
            </div>
        </div>
        
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Revenue Growth</h5>
                </div>
                <div class="card-body">
                    <canvas id="revenueTrendChart" height="200"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- School Performance Tables -->
    <div class="row mb-4">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Top Performing Schools</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th>School Name</th>
                                    <th>Location</th>
                                    <th>Students</th>
                                    <th>Revenue</th>
                                    <th>Performance Score</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($top_performing_schools as $school)
                                <tr>
                                    <td>
                                        <strong>{{ $school['name'] }}</strong>
                                    </td>
                                    <td>
                                        <i class="fas fa-map-marker-alt text-muted mr-1"></i>
                                        {{ $school['location'] }}
                                    </td>
                                    <td>{{ number_format($school['students']) }}</td>
                                    <td>${{ number_format($school['revenue']) }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="progress mr-2" style="width: 60px; height: 8px;">
                                                <div class="progress-bar bg-success" style="width: {{ $school['score'] }}%"></div>
                                            </div>
                                            <span class="text-success font-weight-bold">{{ $school['score'] }}%</span>
                                        </div>
                                    </td>
                                    <td>
                                        <button class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Schools Needing Attention</h5>
                </div>
                <div class="card-body p-0">
                    @foreach($underperforming_schools as $school)
                    <div class="attention-school p-3 border-bottom">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h6 class="mb-1">{{ $school['name'] }}</h6>
                                <small class="text-muted">{{ $school['location'] }} • {{ $school['students'] }} students</small>
                                <div class="mt-2">
                                    @foreach($school['issues'] as $issue)
                                    <span class="badge badge-warning badge-sm mr-1">{{ $issue }}</span>
                                    @endforeach
                                </div>
                            </div>
                            <div class="text-right">
                                <span class="text-danger font-weight-bold">{{ $school['score'] }}%</span>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                <div class="card-footer">
                    <button class="btn btn-warning btn-sm btn-block">
                        <i class="fas fa-tools mr-1"></i>Create Action Plan
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Regional Performance & AI Recommendations -->
    <div class="row mb-4">
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Regional Performance Overview</h5>
                </div>
                <div class="card-body">
                    <canvas id="regionalPerformanceChart" height="300"></canvas>
                </div>
            </div>
        </div>
        
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-robot mr-2"></i>AI Recommendations
                    </h5>
                </div>
                <div class="card-body p-0">
                    @foreach($recommendations as $rec)
                    <div class="recommendation-item p-3 border-bottom">
                        <div class="d-flex align-items-start">
                            <div class="priority-indicator mr-3">
                                <span class="badge badge-{{ $rec['priority'] == 'High' ? 'danger' : ($rec['priority'] == 'Medium' ? 'warning' : 'info') }}">
                                    {{ $rec['priority'] }}
                                </span>
                            </div>
                            <div class="flex-grow-1">
                                <span class="badge badge-outline-secondary mb-1">{{ $rec['category'] }}</span>
                                <p class="mb-2">{{ $rec['recommendation'] }}</p>
                                <div class="impact-timeline">
                                    <small class="text-success">
                                        <i class="fas fa-chart-line mr-1"></i>{{ $rec['expected_impact'] }}
                                    </small>
                                    <span class="mx-2">•</span>
                                    <small class="text-muted">
                                        <i class="fas fa-clock mr-1"></i>{{ $rec['timeline'] }}
                                    </small>
                                </div>
                            </div>
                            <div>
                                <button class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-play"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- Financial Overview -->
    <div class="row mb-4">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Financial Performance</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <canvas id="revenueBySchoolChart" height="300"></canvas>
                        </div>
                        <div class="col-md-6">
                            <canvas id="expenseBreakdownChart" height="300"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Predictive Analytics</h5>
                </div>
                <div class="card-body">
                    <div class="prediction-item mb-3">
                        <h6>Enrollment Forecast</h6>
                        <div class="d-flex justify-content-between">
                            <span>Next Month</span>
                            <strong>{{ number_format($predictive_analytics['enrollment_forecast']['next_month']) }}</strong>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span>Next Quarter</span>
                            <strong>{{ number_format($predictive_analytics['enrollment_forecast']['next_quarter']) }}</strong>
                        </div>
                        <small class="text-muted">{{ $predictive_analytics['enrollment_forecast']['confidence'] }}% confidence</small>
                    </div>
                    
                    <div class="prediction-item mb-3">
                        <h6>Revenue Forecast</h6>
                        <div class="d-flex justify-content-between">
                            <span>Next Month</span>
                            <strong>${{ number_format($predictive_analytics['revenue_forecast']['next_month']) }}</strong>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span>Next Quarter</span>
                            <strong>${{ number_format($predictive_analytics['revenue_forecast']['next_quarter']) }}</strong>
                        </div>
                        <small class="text-muted">{{ $predictive_analytics['revenue_forecast']['confidence'] }}% confidence</small>
                    </div>
                    
                    <div class="risk-assessment">
                        <h6>Risk Assessment</h6>
                        <div class="risk-item d-flex justify-content-between">
                            <span>Financial Risk</span>
                            <span class="badge badge-success">{{ $predictive_analytics['risk_assessment']['financial_risk_score'] }}</span>
                        </div>
                        <div class="risk-item d-flex justify-content-between">
                            <span>Academic Risk</span>
                            <span class="badge badge-warning">{{ $predictive_analytics['risk_assessment']['academic_risk_score'] }}</span>
                        </div>
                        <div class="risk-item d-flex justify-content-between">
                            <span>Operational Risk</span>
                            <span class="badge badge-success">{{ $predictive_analytics['risk_assessment']['operational_risk_score'] }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions & AI Chat -->
    <div class="row">
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Quick Actions</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <a href="{{ route('insights.reports') }}" class="btn btn-outline-primary btn-block">
                                <i class="fas fa-file-alt mr-2"></i>Generate Report
                            </a>
                        </div>
                        <div class="col-md-6 mb-3">
                            <a href="{{ route('insights.analytics') }}" class="btn btn-outline-info btn-block">
                                <i class="fas fa-chart-bar mr-2"></i>Advanced Analytics
                            </a>
                        </div>
                        <div class="col-md-6 mb-3">
                            <button class="btn btn-outline-warning btn-block" data-toggle="modal" data-target="#exportModal">
                                <i class="fas fa-download mr-2"></i>Export Data
                            </button>
                        </div>
                        <div class="col-md-6 mb-3">
                            <a href="{{ route('insights.alerts') }}" class="btn btn-outline-danger btn-block">
                                <i class="fas fa-bell mr-2"></i>Manage Alerts
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header bg-gradient-dark text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-comments mr-2"></i>Ask AI Assistant
                    </h5>
                </div>
                <div class="card-body">
                    <div class="ai-chat-preview">
                        <div class="input-group">
                            <input type="text" class="form-control" placeholder="Ask me anything about your schools..." id="quickAIQuery">
                            <div class="input-group-append">
                                <button class="btn btn-primary" onclick="processQuickQuery()">
                                    <i class="fas fa-paper-plane"></i>
                                </button>
                            </div>
                        </div>
                        <div class="suggested-questions mt-3">
                            <small class="text-muted d-block mb-2">Suggested questions:</small>
                            <div class="question-tags">
                                <span class="badge badge-light mr-1 mb-1 clickable-badge" onclick="askQuestion('Which schools have the highest fee arrears?')">Fee arrears by school</span>
                                <span class="badge badge-light mr-1 mb-1 clickable-badge" onclick="askQuestion('What is the average class size across all schools?')">Average class sizes</span>
                                <span class="badge badge-light mr-1 mb-1 clickable-badge" onclick="askQuestion('Show me enrollment trends for this year')">Enrollment trends</span>
                            </div>
                        </div>
                        <div class="mt-3">
                            <a href="{{ route('insights.ai-chat') }}" class="btn btn-outline-dark btn-sm">
                                Open Full AI Assistant <i class="fas fa-external-link-alt ml-1"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Export Modal -->
<div class="modal fade" id="exportModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Export Data</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <form id="exportForm">
                    <div class="form-group">
                        <label>Report Type</label>
                        <select class="form-control" name="type">
                            <option value="executive_summary">Executive Summary</option>
                            <option value="financial_overview">Financial Overview</option>
                            <option value="academic_performance">Academic Performance</option>
                            <option value="regional_analysis">Regional Analysis</option>
                            <option value="complete_insights">Complete Insights</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Format</label>
                        <select class="form-control" name="format">
                            <option value="excel">Excel (.xlsx)</option>
                            <option value="pdf">PDF Report</option>
                            <option value="csv">CSV Data</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="exportReport()">Export</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Chart Data
const enrollmentData = @json($enrollment_trend);
const revenueData = @json($revenue_trend);
const revenueBySchool = @json($revenue_by_school);
const expenseBreakdown = @json($expense_breakdown);
const regionalPerformance = @json($regional_performance);

// Enrollment Trend Chart
const enrollmentCtx = document.getElementById('enrollmentTrendChart').getContext('2d');
new Chart(enrollmentCtx, {
    type: 'line',
    data: {
        labels: Object.keys(enrollmentData),
        datasets: [{
            label: 'Student Enrollment',
            data: Object.values(enrollmentData),
            borderColor: '#007bff',
            backgroundColor: 'rgba(0, 123, 255, 0.1)',
            borderWidth: 2,
            fill: true
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                display: false
            }
        },
        scales: {
            y: {
                beginAtZero: false
            }
        }
    }
});

// Revenue Trend Chart
const revenueCtx = document.getElementById('revenueTrendChart').getContext('2d');
new Chart(revenueCtx, {
    type: 'line',
    data: {
        labels: Object.keys(revenueData),
        datasets: [{
            label: 'Revenue',
            data: Object.values(revenueData),
            borderColor: '#28a745',
            backgroundColor: 'rgba(40, 167, 69, 0.1)',
            borderWidth: 2,
            fill: true
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                display: false
            }
        },
        scales: {
            y: {
                beginAtZero: false,
                ticks: {
                    callback: function(value) {
                        return '$' + value.toLocaleString();
                    }
                }
            }
        }
    }
});

// Revenue by School Chart
const revenueBySchoolCtx = document.getElementById('revenueBySchoolChart').getContext('2d');
new Chart(revenueBySchoolCtx, {
    type: 'bar',
    data: {
        labels: Object.keys(revenueBySchool).slice(0, 8),
        datasets: [{
            label: 'Revenue',
            data: Object.values(revenueBySchool).slice(0, 8),
            backgroundColor: '#007bff'
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                display: false
            }
        },
        scales: {
            y: {
                ticks: {
                    callback: function(value) {
                        return '$' + value.toLocaleString();
                    }
                }
            }
        }
    }
});

// Expense Breakdown Chart
const expenseCtx = document.getElementById('expenseBreakdownChart').getContext('2d');
new Chart(expenseCtx, {
    type: 'doughnut',
    data: {
        labels: Object.keys(expenseBreakdown),
        datasets: [{
            data: Object.values(expenseBreakdown),
            backgroundColor: [
                '#007bff', '#28a745', '#ffc107', '#dc3545',
                '#6f42c1', '#fd7e14', '#20c997', '#6c757d'
            ]
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

// Regional Performance Chart
const regionalCtx = document.getElementById('regionalPerformanceChart').getContext('2d');
const regions = Object.keys(regionalPerformance);
const academicScores = regions.map(region => regionalPerformance[region].academic);
const financialScores = regions.map(region => regionalPerformance[region].financial);
const operationalScores = regions.map(region => regionalPerformance[region].operational);

new Chart(regionalCtx, {
    type: 'radar',
    data: {
        labels: regions,
        datasets: [
            {
                label: 'Academic Performance',
                data: academicScores,
                borderColor: '#007bff',
                backgroundColor: 'rgba(0, 123, 255, 0.1)',
                borderWidth: 2
            },
            {
                label: 'Financial Performance',
                data: financialScores,
                borderColor: '#28a745',
                backgroundColor: 'rgba(40, 167, 69, 0.1)',
                borderWidth: 2
            },
            {
                label: 'Operational Performance',
                data: operationalScores,
                borderColor: '#ffc107',
                backgroundColor: 'rgba(255, 193, 7, 0.1)',
                borderWidth: 2
            }
        ]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'bottom'
            }
        },
        scales: {
            r: {
                beginAtZero: true,
                max: 100
            }
        }
    }
});

// AI Functions
function processQuickQuery() {
    const query = document.getElementById('quickAIQuery').value;
    if (query.trim()) {
        // Show loading state
        document.getElementById('quickAIQuery').placeholder = 'Processing...';
        
        fetch('/insights/ai-query', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ query: query })
        })
        .then(response => response.json())
        .then(data => {
            // Handle response - could show in modal or redirect to full AI chat
            alert('AI Response: ' + data.answer);
            document.getElementById('quickAIQuery').value = '';
            document.getElementById('quickAIQuery').placeholder = 'Ask me anything about your schools...';
        })
        .catch(error => {
            console.error('Error:', error);
            document.getElementById('quickAIQuery').placeholder = 'Ask me anything about your schools...';
        });
    }
}

function askQuestion(question) {
    document.getElementById('quickAIQuery').value = question;
    processQuickQuery();
}

function exportReport() {
    const form = document.getElementById('exportForm');
    const formData = new FormData(form);
    
    fetch('/insights/export', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: formData
    })
    .then(response => response.blob())
    .then(blob => {
        const url = window.URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.style.display = 'none';
        a.href = url;
        a.download = 'insights_report.' + formData.get('format');
        document.body.appendChild(a);
        a.click();
        window.URL.revokeObjectURL(url);
        $('#exportModal').modal('hide');
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Export failed. Please try again.');
    });
}

// Auto-refresh alerts every 5 minutes
setInterval(function() {
    location.reload();
}, 300000);

// Add click handlers for clickable badges
document.addEventListener('DOMContentLoaded', function() {
    const clickableBadges = document.querySelectorAll('.clickable-badge');
    clickableBadges.forEach(badge => {
        badge.style.cursor = 'pointer';
        badge.addEventListener('mouseenter', function() {
            this.style.backgroundColor = '#007bff';
            this.style.color = 'white';
        });
        badge.addEventListener('mouseleave', function() {
            this.style.backgroundColor = '';
            this.style.color = '';
        });
    });
});
</script>
@endsection

@section('styles')
<style>
.insights-dashboard .card {
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    border: 1px solid rgba(0, 0, 0, 0.125);
}

.insights-dashboard .bg-gradient-primary {
    background: linear-gradient(45deg, #007bff, #0056b3);
}

.insights-dashboard .bg-gradient-success {
    background: linear-gradient(45deg, #28a745, #1e7e34);
}

.insights-dashboard .bg-gradient-info {
    background: linear-gradient(45deg, #17a2b8, #117a8b);
}

.insights-dashboard .bg-gradient-warning {
    background: linear-gradient(45deg, #ffc107, #e0a800);
}

.insights-dashboard .bg-gradient-dark {
    background: linear-gradient(45deg, #343a40, #23272b);
}

.ai-insights-container {
    max-height: 400px;
    overflow-y: auto;
}

.confidence-meter .progress {
    margin: 2px 0;
}

.alert-item:last-child {
    border-bottom: none !important;
}

.recommendation-item:last-child {
    border-bottom: none !important;
}

.attention-school:last-child {
    border-bottom: none !important;
}

.prediction-item {
    padding: 15px;
    background: #f8f9fa;
    border-radius: 5px;
    margin-bottom: 15px;
}

.risk-assessment .risk-item {
    padding: 5px 0;
}

.question-tags .badge {
    cursor: pointer;
    transition: all 0.2s;
}

.question-tags .badge:hover {
    background-color: #007bff !important;
    color: white !important;
}

@media (max-width: 768px) {
    .insights-dashboard .card-body {
        padding: 1rem 0.75rem;
    }
    
    .ai-insights-container {
        max-height: 300px;
    }
}
</style>
@endsection
