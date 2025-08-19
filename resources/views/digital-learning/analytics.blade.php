@extends('layouts.digital-learning')

@section('title', 'Digital Learning Analytics')
@section('page-title', 'Analytics')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">Digital Learning Analytics</h1>
            <p class="mb-0 text-muted">Comprehensive insights into digital learning performance and AI tool usage</p>
        </div>
        <div class="d-flex gap-2">
            <button class="btn btn-primary" onclick="exportReport()">
                <i class="fas fa-download me-2"></i>Export Report
            </button>
            <button class="btn btn-info" data-bs-toggle="modal" data-bs-target="#customReportModal">
                <i class="fas fa-chart-bar me-2"></i>Custom Report
            </button>
            <div class="dropdown">
                <button class="btn btn-secondary dropdown-toggle" data-bs-toggle="dropdown">
                    <i class="fas fa-calendar me-2"></i>Time Period
                </button>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="#" onclick="setTimePeriod('7days')">Last 7 Days</a></li>
                    <li><a class="dropdown-item" href="#" onclick="setTimePeriod('30days')">Last 30 Days</a></li>
                    <li><a class="dropdown-item" href="#" onclick="setTimePeriod('3months')">Last 3 Months</a></li>
                    <li><a class="dropdown-item" href="#" onclick="setTimePeriod('year')">This Year</a></li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Key Metrics Dashboard -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Digital Adoption Rate</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $digital_adoption_rate }}%</div>
                            <div class="text-xs text-success">
                                <i class="fas fa-arrow-up"></i> {{ $adoption_growth }}% vs last period
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-chart-line fa-2x text-gray-300"></i>
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
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">AI Tool Usage</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($ai_tool_usage) }}</div>
                            <div class="text-xs text-muted">{{ $ai_sessions_today }} sessions today</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-robot fa-2x text-gray-300"></i>
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
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Content Engagement</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $content_engagement }}%</div>
                            <div class="text-xs text-muted">Avg time: {{ $avg_engagement_time }}min</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-eye fa-2x text-gray-300"></i>
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
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Exam Performance</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $avg_exam_score }}%</div>
                            <div class="text-xs text-muted">{{ $exams_completed }} exams completed</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-graduation-cap fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Analytics Charts -->
    <div class="row mb-4">
        <div class="col-xl-8 col-lg-7">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Digital Learning Trends</h6>
                    <div class="dropdown no-arrow">
                        <a class="dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right">
                            <a class="dropdown-item" href="#" onclick="updateMainChart('usage')">Platform Usage</a>
                            <a class="dropdown-item" href="#" onclick="updateMainChart('engagement')">Engagement Rates</a>
                            <a class="dropdown-item" href="#" onclick="updateMainChart('performance')">Performance Scores</a>
                            <a class="dropdown-item" href="#" onclick="updateMainChart('ai_usage')">AI Tool Usage</a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <canvas id="mainAnalyticsChart" width="100%" height="40"></canvas>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-lg-5">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Learning Mode Distribution</h6>
                </div>
                <div class="card-body">
                    <canvas id="learningModeChart" width="100%" height="150"></canvas>
                    <div class="mt-3">
                        @foreach($learning_modes as $mode => $data)
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="text-sm">{{ $mode }}</span>
                            <div class="d-flex align-items-center">
                                <span class="text-sm font-weight-bold me-2">{{ $data['hours'] }}h</span>
                                <span class="badge bg-primary">{{ $data['percentage'] }}%</span>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- AI Tools Analytics -->
    <div class="row mb-4">
        <div class="col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">AI Tools Performance</h6>
                </div>
                <div class="card-body">
                    <canvas id="aiToolsChart" width="100%" height="200"></canvas>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">AI Tool Usage by Subject</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Subject</th>
                                    <th>AI Exams</th>
                                    <th>AI Notes</th>
                                    <th>Auto-Grading</th>
                                    <th>Total Usage</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($ai_usage_by_subject as $subject => $usage)
                                <tr>
                                    <td><strong>{{ $subject }}</strong></td>
                                    <td><span class="badge bg-info">{{ $usage['ai_exams'] }}</span></td>
                                    <td><span class="badge bg-warning">{{ $usage['ai_notes'] }}</span></td>
                                    <td><span class="badge bg-success">{{ $usage['auto_grading'] }}</span></td>
                                    <td><span class="badge bg-primary">{{ $usage['total'] }}</span></td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- School Performance Analytics -->
    <div class="row mb-4">
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">School Performance Comparison</h6>
                    <div class="d-flex gap-2">
                        <select class="form-select form-select-sm" id="performanceMetric" style="width: 150px;">
                            <option value="digital_adoption">Digital Adoption</option>
                            <option value="engagement">Engagement Rate</option>
                            <option value="exam_scores">Exam Scores</option>
                            <option value="ai_usage">AI Usage</option>
                        </select>
                        <select class="form-select form-select-sm" id="regionFilter" style="width: 120px;">
                            <option value="">All Regions</option>
                            <option value="North">North</option>
                            <option value="South">South</option>
                            <option value="East">East</option>
                            <option value="West">West</option>
                        </select>
                    </div>
                </div>
                <div class="card-body">
                    <canvas id="schoolPerformanceChart" width="100%" height="80"></canvas>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Top Performing Schools</h6>
                </div>
                <div class="card-body">
                    @foreach($top_schools as $index => $school)
                    <div class="d-flex align-items-center justify-content-between p-2 {{ $index < 3 ? 'bg-light' : '' }} rounded mb-2">
                        <div class="d-flex align-items-center">
                            <div class="me-2">
                                @if($index == 0)
                                <i class="fas fa-medal text-warning"></i>
                                @elseif($index == 1)
                                <i class="fas fa-medal text-muted"></i>
                                @elseif($index == 2)
                                <i class="fas fa-medal text-warning" style="color: #CD7F32 !important;"></i>
                                @else
                                <span class="text-muted">{{ $index + 1 }}.</span>
                                @endif
                            </div>
                            <div>
                                <h6 class="mb-0 text-sm">{{ $school['name'] }}</h6>
                                <small class="text-muted">{{ $school['location'] }}</small>
                            </div>
                        </div>
                        <div class="text-center">
                            <div class="h6 mb-0 text-success">{{ $school['score'] }}%</div>
                            <small class="text-muted">Overall</small>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- Detailed Analytics Tables -->
    <div class="row mb-4">
        <div class="col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Content Performance Analytics</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-sm">
                            <thead class="table-light">
                                <tr>
                                    <th>Content Type</th>
                                    <th>Total Items</th>
                                    <th>Avg Views</th>
                                    <th>Engagement</th>
                                    <th>Rating</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($content_analytics as $content)
                                <tr>
                                    <td>
                                        <i class="fas fa-{{ $content['icon'] }} text-primary me-2"></i>
                                        {{ $content['type'] }}
                                    </td>
                                    <td>{{ $content['total_items'] }}</td>
                                    <td>{{ number_format($content['avg_views']) }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <span class="me-2">{{ $content['engagement'] }}%</span>
                                            <div class="progress" style="width: 60px; height: 6px;">
                                                <div class="progress-bar bg-{{ $content['engagement'] > 70 ? 'success' : ($content['engagement'] > 50 ? 'warning' : 'danger') }}" 
                                                     style="width: {{ $content['engagement'] }}%"></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="text-warning">
                                            @for($i = 1; $i <= 5; $i++)
                                                @if($i <= $content['rating'])
                                                <i class="fas fa-star"></i>
                                                @else
                                                <i class="far fa-star"></i>
                                                @endif
                                            @endfor
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

        <div class="col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">AI Exam Analytics</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-sm">
                            <thead class="table-light">
                                <tr>
                                    <th>Subject</th>
                                    <th>AI Exams</th>
                                    <th>Participation</th>
                                    <th>Avg Score</th>
                                    <th>Completion</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($exam_analytics as $exam)
                                <tr>
                                    <td><strong>{{ $exam['subject'] }}</strong></td>
                                    <td>{{ $exam['ai_exams_count'] }}</td>
                                    <td>{{ number_format($exam['participation']) }}</td>
                                    <td>
                                        <span class="badge bg-{{ $exam['avg_score'] > 80 ? 'success' : ($exam['avg_score'] > 60 ? 'warning' : 'danger') }}">
                                            {{ $exam['avg_score'] }}%
                                        </span>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <span class="me-2">{{ $exam['completion_rate'] }}%</span>
                                            <div class="progress" style="width: 60px; height: 6px;">
                                                <div class="progress-bar bg-info" style="width: {{ $exam['completion_rate'] }}%"></div>
                                            </div>
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
    </div>

    <!-- Learning Progress Analytics -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Student Learning Progress</h6>
            <div class="d-flex gap-2">
                <select class="form-select form-select-sm" id="progressSubject" style="width: 150px;">
                    <option value="">All Subjects</option>
                    <option value="Mathematics">Mathematics</option>
                    <option value="English">English</option>
                    <option value="Science">Science</option>
                    <option value="History">History</option>
                </select>
                <select class="form-select form-select-sm" id="progressClass" style="width: 120px;">
                    <option value="">All Classes</option>
                    <option value="Grade 8">Grade 8</option>
                    <option value="Grade 9">Grade 9</option>
                    <option value="Grade 10">Grade 10</option>
                    <option value="Grade 11">Grade 11</option>
                    <option value="Grade 12">Grade 12</option>
                </select>
            </div>
        </div>
        <div class="card-body">
            <canvas id="learningProgressChart" width="100%" height="60"></canvas>
        </div>
    </div>

    <!-- Regional Performance Summary -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Regional Digital Learning Summary</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover" id="regionalTable">
                    <thead class="table-light">
                        <tr>
                            <th>Region</th>
                            <th>Schools</th>
                            <th>Digital Adoption</th>
                            <th>AI Tool Usage</th>
                            <th>Content Engagement</th>
                            <th>Exam Performance</th>
                            <th>Student Satisfaction</th>
                            <th>Overall Score</th>
                            <th>Trend</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($regional_summary as $region)
                        <tr>
                            <td><strong>{{ $region['region'] }}</strong></td>
                            <td>{{ $region['schools_count'] }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <span class="me-2">{{ $region['digital_adoption'] }}%</span>
                                    <div class="progress" style="width: 60px; height: 6px;">
                                        <div class="progress-bar bg-primary" style="width: {{ $region['digital_adoption'] }}%"></div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <span class="me-2">{{ $region['ai_usage'] }}%</span>
                                    <div class="progress" style="width: 60px; height: 6px;">
                                        <div class="progress-bar bg-warning" style="width: {{ $region['ai_usage'] }}%"></div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <span class="me-2">{{ $region['engagement'] }}%</span>
                                    <div class="progress" style="width: 60px; height: 6px;">
                                        <div class="progress-bar bg-info" style="width: {{ $region['engagement'] }}%"></div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-{{ $region['exam_performance'] > 80 ? 'success' : ($region['exam_performance'] > 60 ? 'warning' : 'danger') }}">
                                    {{ $region['exam_performance'] }}%
                                </span>
                            </td>
                            <td>
                                <div class="text-warning">
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($i <= $region['satisfaction_stars'])
                                        <i class="fas fa-star"></i>
                                        @else
                                        <i class="far fa-star"></i>
                                        @endif
                                    @endfor
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-{{ $region['overall_score'] > 85 ? 'success' : ($region['overall_score'] > 70 ? 'warning' : 'danger') }}">
                                    {{ $region['overall_score'] }}%
                                </span>
                            </td>
                            <td>
                                <i class="fas fa-arrow-{{ $region['trend'] == 'up' ? 'up text-success' : ($region['trend'] == 'down' ? 'down text-danger' : 'right text-warning') }}"></i>
                                <span class="text-{{ $region['trend'] == 'up' ? 'success' : ($region['trend'] == 'down' ? 'danger' : 'warning') }}">
                                    {{ $region['trend_percentage'] }}%
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Custom Report Modal -->
<div class="modal fade" id="customReportModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Generate Custom Report</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="customReportForm">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="reportName" class="form-label">Report Name</label>
                                <input type="text" class="form-control" id="reportName" name="report_name" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="reportType" class="form-label">Report Type</label>
                                <select class="form-select" id="reportType" name="report_type" required>
                                    <option value="">Select Type</option>
                                    <option value="performance">Performance Analysis</option>
                                    <option value="ai_usage">AI Usage Report</option>
                                    <option value="engagement">Engagement Report</option>
                                    <option value="comprehensive">Comprehensive Report</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="dateFrom" class="form-label">From Date</label>
                                <input type="date" class="form-control" id="dateFrom" name="date_from" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="dateTo" class="form-label">To Date</label>
                                <input type="date" class="form-control" id="dateTo" name="date_to" required>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Include Metrics</label>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" id="includeAdoption" name="metrics[]" value="adoption" checked>
                                    <label class="form-check-label" for="includeAdoption">Digital Adoption</label>
                                </div>
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" id="includeAI" name="metrics[]" value="ai_usage" checked>
                                    <label class="form-check-label" for="includeAI">AI Tool Usage</label>
                                </div>
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" id="includeEngagement" name="metrics[]" value="engagement" checked>
                                    <label class="form-check-label" for="includeEngagement">Content Engagement</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" id="includePerformance" name="metrics[]" value="performance" checked>
                                    <label class="form-check-label" for="includePerformance">Exam Performance</label>
                                </div>
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" id="includeProgress" name="metrics[]" value="progress">
                                    <label class="form-check-label" for="includeProgress">Learning Progress</label>
                                </div>
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" id="includeComparison" name="metrics[]" value="comparison">
                                    <label class="form-check-label" for="includeComparison">Regional Comparison</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="reportSchools" class="form-label">Schools (Optional)</label>
                        <select class="form-select" id="reportSchools" name="schools[]" multiple>
                            @foreach($schools as $school)
                            <option value="{{ $school['id'] }}">{{ $school['name'] }}</option>
                            @endforeach
                        </select>
                        <div class="form-text">Leave empty to include all schools</div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="generateCustomReport()">Generate Report</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Main Analytics Chart
const ctx1 = document.getElementById('mainAnalyticsChart').getContext('2d');
const mainChart = new Chart(ctx1, {
    type: 'line',
    data: {
        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
        datasets: [{
            label: 'Digital Adoption',
            data: [65, 70, 75, 78, 82, 85],
            borderColor: 'rgb(75, 192, 192)',
            backgroundColor: 'rgba(75, 192, 192, 0.1)',
            tension: 0.1
        }, {
            label: 'AI Tool Usage',
            data: [45, 52, 58, 62, 68, 75],
            borderColor: 'rgb(255, 99, 132)',
            backgroundColor: 'rgba(255, 99, 132, 0.1)',
            tension: 0.1
        }, {
            label: 'Engagement Rate',
            data: [78, 81, 84, 86, 88, 90],
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
                beginAtZero: true,
                max: 100
            }
        }
    }
});

// Learning Mode Distribution Chart
const ctx2 = document.getElementById('learningModeChart').getContext('2d');
new Chart(ctx2, {
    type: 'doughnut',
    data: {
        labels: ['Self-Paced', 'AI-Assisted', 'Live Sessions', 'Hybrid'],
        datasets: [{
            data: [35, 28, 22, 15],
            backgroundColor: [
                'rgb(75, 192, 192)',
                'rgb(255, 99, 132)',
                'rgb(54, 162, 235)',
                'rgb(255, 206, 86)'
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

// AI Tools Performance Chart
const ctx3 = document.getElementById('aiToolsChart').getContext('2d');
new Chart(ctx3, {
    type: 'bar',
    data: {
        labels: ['AI Exams', 'Auto-Grading', 'AI Notes', 'Smart Tutoring', 'Content Gen'],
        datasets: [{
            label: 'Usage Rate (%)',
            data: [75, 68, 82, 45, 56],
            backgroundColor: [
                'rgba(255, 99, 132, 0.8)',
                'rgba(54, 162, 235, 0.8)',
                'rgba(255, 206, 86, 0.8)',
                'rgba(75, 192, 192, 0.8)',
                'rgba(153, 102, 255, 0.8)'
            ],
            borderColor: [
                'rgb(255, 99, 132)',
                'rgb(54, 162, 235)',
                'rgb(255, 206, 86)',
                'rgb(75, 192, 192)',
                'rgb(153, 102, 255)'
            ],
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
            y: {
                beginAtZero: true,
                max: 100
            }
        }
    }
});

// School Performance Chart
const ctx4 = document.getElementById('schoolPerformanceChart').getContext('2d');
const schoolChart = new Chart(ctx4, {
    type: 'bar',
    data: {
        labels: ['Greenwood High', 'Sunset Academy', 'Oak Valley School', 'Riverside Prep', 'Mountain View'],
        datasets: [{
            label: 'Performance Score',
            data: [85, 78, 92, 67, 81],
            backgroundColor: 'rgba(54, 162, 235, 0.8)',
            borderColor: 'rgb(54, 162, 235)',
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
            y: {
                beginAtZero: true,
                max: 100
            }
        }
    }
});

// Learning Progress Chart
const ctx5 = document.getElementById('learningProgressChart').getContext('2d');
new Chart(ctx5, {
    type: 'line',
    data: {
        labels: ['Week 1', 'Week 2', 'Week 3', 'Week 4', 'Week 5', 'Week 6'],
        datasets: [{
            label: 'Average Progress',
            data: [20, 35, 48, 62, 75, 85],
            borderColor: 'rgb(75, 192, 192)',
            backgroundColor: 'rgba(75, 192, 192, 0.1)',
            tension: 0.1,
            fill: true
        }, {
            label: 'AI-Assisted Learning',
            data: [25, 42, 58, 72, 83, 92],
            borderColor: 'rgb(255, 99, 132)',
            backgroundColor: 'rgba(255, 99, 132, 0.1)',
            tension: 0.1,
            fill: true
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
            y: {
                beginAtZero: true,
                max: 100
            }
        }
    }
});

// Chart update functions
function updateMainChart(type) {
    console.log('Updating main chart to show:', type);
    // In a real implementation, this would update the chart data
}

function setTimePeriod(period) {
    console.log('Setting time period to:', period);
    // In a real implementation, this would refresh all data
}

function exportReport() {
    alert('Exporting comprehensive analytics report...');
}

function generateCustomReport() {
    const form = document.getElementById('customReportForm');
    if (!form.checkValidity()) {
        form.reportValidity();
        return;
    }
    
    alert('Generating custom report...');
    $('#customReportModal').modal('hide');
    form.reset();
}

// Filter change handlers
document.getElementById('performanceMetric').addEventListener('change', function() {
    const metric = this.value;
    console.log('Updating school performance chart for metric:', metric);
    // Update school performance chart based on selected metric
});

document.getElementById('regionFilter').addEventListener('change', function() {
    const region = this.value;
    console.log('Filtering schools by region:', region);
    // Filter school performance chart based on selected region
});

document.getElementById('progressSubject').addEventListener('change', function() {
    const subject = this.value;
    console.log('Updating progress chart for subject:', subject);
    // Update learning progress chart based on selected subject
});

document.getElementById('progressClass').addEventListener('change', function() {
    const classLevel = this.value;
    console.log('Updating progress chart for class:', classLevel);
    // Update learning progress chart based on selected class
});

// Initialize date inputs with default values
document.addEventListener('DOMContentLoaded', function() {
    const today = new Date();
    const thirtyDaysAgo = new Date(today);
    thirtyDaysAgo.setDate(today.getDate() - 30);
    
    document.getElementById('dateFrom').value = thirtyDaysAgo.toISOString().split('T')[0];
    document.getElementById('dateTo').value = today.toISOString().split('T')[0];
});
</script>
@endpush
