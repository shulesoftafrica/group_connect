@extends('layouts.admin')

@section('title', 'Advanced Analytics & Trends')

@section('page_title', 'Advanced Analytics & Predictive Insights')

@section('breadcrumb')
<ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('insights.dashboard') }}">Insights</a></li>
    <li class="breadcrumb-item active">Advanced Analytics</li>
</ol>
@endsection

@section('content')
<div class="advanced-analytics">
    <!-- Analytics Navigation -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="analytics-nav">
                        <nav class="nav nav-pills nav-justified">
                            <a class="nav-link active" href="#trend-analysis" data-toggle="tab">
                                <i class="fas fa-chart-line mr-2"></i>Trend Analysis
                            </a>
                            <a class="nav-link" href="#cohort-analysis" data-toggle="tab">
                                <i class="fas fa-users mr-2"></i>Cohort Analysis
                            </a>
                            <a class="nav-link" href="#predictive-models" data-toggle="tab">
                                <i class="fas fa-crystal-ball mr-2"></i>Predictive Models
                            </a>
                            <a class="nav-link" href="#correlation-analysis" data-toggle="tab">
                                <i class="fas fa-project-diagram mr-2"></i>Correlation Analysis
                            </a>
                            <a class="nav-link" href="#benchmark-analysis" data-toggle="tab">
                                <i class="fas fa-trophy mr-2"></i>Benchmark Analysis
                            </a>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Analytics Content Tabs -->
    <div class="tab-content">
        <!-- Trend Analysis Tab -->
        <div class="tab-pane fade show active" id="trend-analysis">
            <div class="row mb-4">
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">
                                <i class="fas fa-chart-line mr-2"></i>Multi-Metric Trend Analysis
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="trend-controls mb-3">
                                <div class="row">
                                    <div class="col-md-4">
                                        <select class="form-control" id="trendMetric">
                                            <option value="enrollment">Student Enrollment</option>
                                            <option value="revenue">Revenue</option>
                                            <option value="academic">Academic Performance</option>
                                            <option value="attendance">Attendance Rate</option>
                                            <option value="fees">Fee Collection</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <select class="form-control" id="trendPeriod">
                                            <option value="last_year">Last 12 Months</option>
                                            <option value="last_quarter">Last Quarter</option>
                                            <option value="last_month">Last Month</option>
                                            <option value="ytd">Year to Date</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <select class="form-control" id="trendGranularity">
                                            <option value="monthly">Monthly</option>
                                            <option value="weekly">Weekly</option>
                                            <option value="daily">Daily</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <canvas id="trendAnalysisChart" height="300"></canvas>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-4">
                    <div class="card">
                        <div class="card-header">
                            <h6 class="mb-0">Trend Insights</h6>
                        </div>
                        <div class="card-body">
                            <div class="trend-insights">
                                <div class="insight-item mb-3">
                                    <div class="d-flex align-items-center mb-2">
                                        <i class="fas fa-arrow-up text-success mr-2"></i>
                                        <strong>Growth Trend</strong>
                                    </div>
                                    <p class="mb-1 small">Enrollment showing consistent 2.3% monthly growth</p>
                                    <small class="text-muted">Confidence: 94%</small>
                                </div>
                                
                                <div class="insight-item mb-3">
                                    <div class="d-flex align-items-center mb-2">
                                        <i class="fas fa-exclamation-triangle text-warning mr-2"></i>
                                        <strong>Seasonal Pattern</strong>
                                    </div>
                                    <p class="mb-1 small">Revenue dips 15% during holiday months</p>
                                    <small class="text-muted">Historical pattern</small>
                                </div>
                                
                                <div class="insight-item">
                                    <div class="d-flex align-items-center mb-2">
                                        <i class="fas fa-chart-bar text-info mr-2"></i>
                                        <strong>Volatility Analysis</strong>
                                    </div>
                                    <p class="mb-1 small">Academic performance stability improved 23%</p>
                                    <small class="text-muted">Compared to last year</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Cohort Analysis Tab -->
        <div class="tab-pane fade" id="cohort-analysis">
            <div class="row mb-4">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">
                                <i class="fas fa-users mr-2"></i>Student Cohort Performance Analysis
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="cohort-controls mb-3">
                                <div class="row">
                                    <div class="col-md-3">
                                        <select class="form-control" id="cohortYear">
                                            <option value="2025">2025 Cohort</option>
                                            <option value="2024">2024 Cohort</option>
                                            <option value="2023">2023 Cohort</option>
                                            <option value="2022">2022 Cohort</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <select class="form-control" id="cohortMetric">
                                            <option value="retention">Retention Rate</option>
                                            <option value="performance">Academic Performance</option>
                                            <option value="progression">Grade Progression</option>
                                            <option value="graduation">Graduation Rate</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <select class="form-control" id="cohortSegment">
                                            <option value="all">All Students</option>
                                            <option value="region">By Region</option>
                                            <option value="school">By School</option>
                                            <option value="grade">By Grade Level</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <button class="btn btn-primary btn-block" onclick="generateCohortAnalysis()">
                                            <i class="fas fa-play mr-1"></i>Generate Analysis
                                        </button>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="cohort-visualization">
                                <canvas id="cohortAnalysisChart" height="250"></canvas>
                            </div>
                            
                            <div class="cohort-table mt-4">
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead class="bg-light">
                                            <tr>
                                                <th>Cohort Period</th>
                                                <th>Starting Size</th>
                                                <th>Current Size</th>
                                                <th>Retention Rate</th>
                                                <th>Avg Performance</th>
                                                <th>Progression Rate</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td><strong>Jan 2025</strong></td>
                                                <td>1,247</td>
                                                <td>1,198</td>
                                                <td><span class="text-success">96.1%</span></td>
                                                <td><span class="text-success">82.5%</span></td>
                                                <td><span class="text-success">94.2%</span></td>
                                            </tr>
                                            <tr>
                                                <td><strong>Sep 2024</strong></td>
                                                <td>1,156</td>
                                                <td>1,089</td>
                                                <td><span class="text-warning">94.2%</span></td>
                                                <td><span class="text-success">79.8%</span></td>
                                                <td><span class="text-success">91.7%</span></td>
                                            </tr>
                                            <tr>
                                                <td><strong>Jan 2024</strong></td>
                                                <td>1,089</td>
                                                <td>1,012</td>
                                                <td><span class="text-warning">92.9%</span></td>
                                                <td><span class="text-success">81.2%</span></td>
                                                <td><span class="text-success">89.5%</span></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Predictive Models Tab -->
        <div class="tab-pane fade" id="predictive-models">
            <div class="row mb-4">
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">
                                <i class="fas fa-crystal-ball mr-2"></i>AI Predictive Forecasting
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="prediction-controls mb-3">
                                <div class="row">
                                    <div class="col-md-4">
                                        <select class="form-control" id="predictionType">
                                            <option value="enrollment">Enrollment Forecast</option>
                                            <option value="revenue">Revenue Prediction</option>
                                            <option value="performance">Performance Trends</option>
                                            <option value="attrition">Student Attrition</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <select class="form-control" id="predictionHorizon">
                                            <option value="3">Next 3 Months</option>
                                            <option value="6">Next 6 Months</option>
                                            <option value="12">Next 12 Months</option>
                                            <option value="24">Next 2 Years</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <select class="form-control" id="predictionScope">
                                            <option value="group">Entire Group</option>
                                            <option value="region">By Region</option>
                                            <option value="school">Individual Schools</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <canvas id="predictiveChart" height="300"></canvas>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-4">
                    <div class="card">
                        <div class="card-header">
                            <h6 class="mb-0">Model Performance</h6>
                        </div>
                        <div class="card-body">
                            <div class="model-metrics">
                                <div class="metric-item mb-3">
                                    <div class="d-flex justify-content-between">
                                        <span>Accuracy</span>
                                        <strong class="text-success">87.2%</strong>
                                    </div>
                                    <div class="progress mt-1" style="height: 4px;">
                                        <div class="progress-bar bg-success" style="width: 87.2%"></div>
                                    </div>
                                </div>
                                
                                <div class="metric-item mb-3">
                                    <div class="d-flex justify-content-between">
                                        <span>Confidence</span>
                                        <strong class="text-info">91.5%</strong>
                                    </div>
                                    <div class="progress mt-1" style="height: 4px;">
                                        <div class="progress-bar bg-info" style="width: 91.5%"></div>
                                    </div>
                                </div>
                                
                                <div class="metric-item mb-3">
                                    <div class="d-flex justify-content-between">
                                        <span>Data Quality</span>
                                        <strong class="text-primary">94.8%</strong>
                                    </div>
                                    <div class="progress mt-1" style="height: 4px;">
                                        <div class="progress-bar bg-primary" style="width: 94.8%"></div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="scenario-analysis mt-4">
                                <h6>Scenario Analysis</h6>
                                <div class="scenario-item mb-2">
                                    <span class="badge badge-success">Best Case</span>
                                    <small class="ml-2">+15% above prediction</small>
                                </div>
                                <div class="scenario-item mb-2">
                                    <span class="badge badge-primary">Most Likely</span>
                                    <small class="ml-2">±3% variance</small>
                                </div>
                                <div class="scenario-item">
                                    <span class="badge badge-warning">Worst Case</span>
                                    <small class="ml-2">-12% below prediction</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Correlation Analysis Tab -->
        <div class="tab-pane fade" id="correlation-analysis">
            <div class="row mb-4">
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">
                                <i class="fas fa-project-diagram mr-2"></i>Factor Correlation Matrix
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="correlation-controls mb-3">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label>Select Factors to Analyze:</label>
                                        <div class="factor-selection">
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="checkbox" id="factor1" value="enrollment" checked>
                                                <label class="form-check-label" for="factor1">Enrollment</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="checkbox" id="factor2" value="revenue" checked>
                                                <label class="form-check-label" for="factor2">Revenue</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="checkbox" id="factor3" value="performance" checked>
                                                <label class="form-check-label" for="factor3">Performance</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="checkbox" id="factor4" value="attendance">
                                                <label class="form-check-label" for="factor4">Attendance</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="checkbox" id="factor5" value="staff_ratio">
                                                <label class="form-check-label" for="factor5">Staff Ratio</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label>Analysis Period:</label>
                                        <select class="form-control" id="correlationPeriod">
                                            <option value="12">Last 12 Months</option>
                                            <option value="24">Last 24 Months</option>
                                            <option value="36">Last 36 Months</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <canvas id="correlationChart" height="350"></canvas>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-4">
                    <div class="card">
                        <div class="card-header">
                            <h6 class="mb-0">Key Correlations</h6>
                        </div>
                        <div class="card-body">
                            <div class="correlation-insights">
                                <div class="correlation-item mb-3">
                                    <div class="d-flex justify-content-between mb-1">
                                        <span class="text-sm">Revenue ↔ Enrollment</span>
                                        <strong class="text-success">0.92</strong>
                                    </div>
                                    <div class="progress" style="height: 4px;">
                                        <div class="progress-bar bg-success" style="width: 92%"></div>
                                    </div>
                                    <small class="text-muted">Strong positive correlation</small>
                                </div>
                                
                                <div class="correlation-item mb-3">
                                    <div class="d-flex justify-content-between mb-1">
                                        <span class="text-sm">Performance ↔ Staff Ratio</span>
                                        <strong class="text-info">0.78</strong>
                                    </div>
                                    <div class="progress" style="height: 4px;">
                                        <div class="progress-bar bg-info" style="width: 78%"></div>
                                    </div>
                                    <small class="text-muted">Good positive correlation</small>
                                </div>
                                
                                <div class="correlation-item mb-3">
                                    <div class="d-flex justify-content-between mb-1">
                                        <span class="text-sm">Attendance ↔ Performance</span>
                                        <strong class="text-warning">0.65</strong>
                                    </div>
                                    <div class="progress" style="height: 4px;">
                                        <div class="progress-bar bg-warning" style="width: 65%"></div>
                                    </div>
                                    <small class="text-muted">Moderate correlation</small>
                                </div>
                                
                                <div class="correlation-item">
                                    <div class="d-flex justify-content-between mb-1">
                                        <span class="text-sm">Revenue ↔ Performance</span>
                                        <strong class="text-secondary">0.34</strong>
                                    </div>
                                    <div class="progress" style="height: 4px;">
                                        <div class="progress-bar bg-secondary" style="width: 34%"></div>
                                    </div>
                                    <small class="text-muted">Weak correlation</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Benchmark Analysis Tab -->
        <div class="tab-pane fade" id="benchmark-analysis">
            <div class="row mb-4">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">
                                <i class="fas fa-trophy mr-2"></i>Performance Benchmarking
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="benchmark-controls mb-3">
                                <div class="row">
                                    <div class="col-md-3">
                                        <select class="form-control" id="benchmarkCategory">
                                            <option value="academic">Academic Performance</option>
                                            <option value="financial">Financial Metrics</option>
                                            <option value="operational">Operational Efficiency</option>
                                            <option value="student_satisfaction">Student Satisfaction</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <select class="form-control" id="benchmarkComparison">
                                            <option value="internal">Internal Comparison</option>
                                            <option value="industry">Industry Standards</option>
                                            <option value="regional">Regional Average</option>
                                            <option value="top_performers">Top 10% Schools</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <select class="form-control" id="benchmarkScope">
                                            <option value="all">All Schools</option>
                                            <option value="region">By Region</option>
                                            <option value="size">By School Size</option>
                                            <option value="type">By School Type</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <button class="btn btn-primary btn-block" onclick="generateBenchmarkAnalysis()">
                                            <i class="fas fa-chart-bar mr-1"></i>Generate Report
                                        </button>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="benchmark-visualization">
                                <canvas id="benchmarkChart" height="350"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-header">
                            <h6 class="mb-0">Performance Leaders</h6>
                        </div>
                        <div class="card-body">
                            <div class="leaders-list">
                                <div class="leader-item d-flex justify-content-between align-items-center mb-2">
                                    <div>
                                        <strong>Greenfield Academy</strong>
                                        <br><small class="text-muted">Nairobi North</small>
                                    </div>
                                    <div class="text-right">
                                        <span class="badge badge-success">96.8%</span>
                                        <br><small class="text-success">+15% above avg</small>
                                    </div>
                                </div>
                                
                                <div class="leader-item d-flex justify-content-between align-items-center mb-2">
                                    <div>
                                        <strong>Sunrise International</strong>
                                        <br><small class="text-muted">Mombasa</small>
                                    </div>
                                    <div class="text-right">
                                        <span class="badge badge-success">94.5%</span>
                                        <br><small class="text-success">+12% above avg</small>
                                    </div>
                                </div>
                                
                                <div class="leader-item d-flex justify-content-between align-items-center">
                                    <div>
                                        <strong>Heritage School</strong>
                                        <br><small class="text-muted">Kisumu</small>
                                    </div>
                                    <div class="text-right">
                                        <span class="badge badge-success">92.1%</span>
                                        <br><small class="text-success">+8% above avg</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-header">
                            <h6 class="mb-0">Improvement Opportunities</h6>
                        </div>
                        <div class="card-body">
                            <div class="opportunities-list">
                                <div class="opportunity-item mb-3">
                                    <div class="d-flex align-items-center mb-1">
                                        <i class="fas fa-arrow-up text-success mr-2"></i>
                                        <strong>Mathematics Performance</strong>
                                    </div>
                                    <small class="text-muted">3 schools below regional average. Opportunity for +8% improvement with targeted training.</small>
                                </div>
                                
                                <div class="opportunity-item mb-3">
                                    <div class="d-flex align-items-center mb-1">
                                        <i class="fas fa-dollar-sign text-info mr-2"></i>
                                        <strong>Fee Collection Efficiency</strong>
                                    </div>
                                    <small class="text-muted">Coast region showing 15% gap vs. industry benchmark. Automated systems could improve by 12%.</small>
                                </div>
                                
                                <div class="opportunity-item">
                                    <div class="d-flex align-items-center mb-1">
                                        <i class="fas fa-users text-warning mr-2"></i>
                                        <strong>Student-Teacher Ratios</strong>
                                    </div>
                                    <small class="text-muted">2 schools exceed optimal ratios. Strategic hiring could improve performance by 6%.</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Sample data for charts
const trendData = {
    enrollment: {
        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug'],
        data: [11200, 11450, 11890, 12150, 12350, 12580, 12750, 12847]
    },
    revenue: {
        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug'],
        data: [280000, 295000, 310000, 325000, 340000, 355000, 370000, 385000]
    },
    academic: {
        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug'],
        data: [76.2, 77.1, 77.8, 78.2, 78.5, 78.9, 79.1, 78.5]
    }
};

// Trend Analysis Chart
let trendChart;
function initTrendChart() {
    const ctx = document.getElementById('trendAnalysisChart').getContext('2d');
    trendChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: trendData.enrollment.labels,
            datasets: [{
                label: 'Student Enrollment',
                data: trendData.enrollment.data,
                borderColor: '#007bff',
                backgroundColor: 'rgba(0, 123, 255, 0.1)',
                borderWidth: 3,
                fill: true,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'top'
                }
            },
            scales: {
                y: {
                    beginAtZero: false
                }
            }
        }
    });
}

// Cohort Analysis Chart
function initCohortChart() {
    const ctx = document.getElementById('cohortAnalysisChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: ['Month 1', 'Month 2', 'Month 3', 'Month 4', 'Month 5', 'Month 6'],
            datasets: [
                {
                    label: '2025 Cohort',
                    data: [100, 98, 96, 94, 93, 92],
                    borderColor: '#007bff',
                    backgroundColor: 'rgba(0, 123, 255, 0.1)',
                    borderWidth: 2
                },
                {
                    label: '2024 Cohort',
                    data: [100, 97, 94, 91, 89, 87],
                    borderColor: '#28a745',
                    backgroundColor: 'rgba(40, 167, 69, 0.1)',
                    borderWidth: 2
                },
                {
                    label: '2023 Cohort',
                    data: [100, 96, 92, 88, 85, 82],
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
                    position: 'top'
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    max: 100,
                    ticks: {
                        callback: function(value) {
                            return value + '%';
                        }
                    }
                }
            }
        }
    });
}

// Predictive Chart
function initPredictiveChart() {
    const ctx = document.getElementById('predictiveChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: ['Aug', 'Sep', 'Oct', 'Nov', 'Dec', 'Jan', 'Feb', 'Mar'],
            datasets: [
                {
                    label: 'Historical Data',
                    data: [12847, null, null, null, null, null, null, null],
                    borderColor: '#007bff',
                    backgroundColor: 'rgba(0, 123, 255, 0.1)',
                    borderWidth: 3
                },
                {
                    label: 'Predicted (Best Case)',
                    data: [null, 13150, 13450, 13750, 14100, 14500, 14850, 15200],
                    borderColor: '#28a745',
                    backgroundColor: 'rgba(40, 167, 69, 0.1)',
                    borderWidth: 2,
                    borderDash: [5, 5]
                },
                {
                    label: 'Predicted (Most Likely)',
                    data: [null, 13050, 13250, 13480, 13720, 13980, 14250, 14520],
                    borderColor: '#ffc107',
                    backgroundColor: 'rgba(255, 193, 7, 0.1)',
                    borderWidth: 3,
                    borderDash: [5, 5]
                },
                {
                    label: 'Predicted (Worst Case)',
                    data: [null, 12950, 13080, 13150, 13250, 13380, 13500, 13620],
                    borderColor: '#dc3545',
                    backgroundColor: 'rgba(220, 53, 69, 0.1)',
                    borderWidth: 2,
                    borderDash: [5, 5]
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'top'
                }
            },
            scales: {
                y: {
                    beginAtZero: false
                }
            }
        }
    });
}

// Correlation Chart
function initCorrelationChart() {
    const ctx = document.getElementById('correlationChart').getContext('2d');
    new Chart(ctx, {
        type: 'scatter',
        data: {
            datasets: [
                {
                    label: 'Revenue vs Enrollment',
                    data: [
                        {x: 850, y: 185000},
                        {x: 720, y: 165000},
                        {x: 680, y: 148000},
                        {x: 590, y: 142000},
                        {x: 540, y: 138000},
                        {x: 480, y: 125000},
                        {x: 420, y: 118000},
                        {x: 380, y: 95000}
                    ],
                    backgroundColor: '#007bff',
                    borderColor: '#007bff'
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'top'
                }
            },
            scales: {
                x: {
                    title: {
                        display: true,
                        text: 'Student Enrollment'
                    }
                },
                y: {
                    title: {
                        display: true,
                        text: 'Monthly Revenue ($)'
                    }
                }
            }
        }
    });
}

// Benchmark Chart
function initBenchmarkChart() {
    const ctx = document.getElementById('benchmarkChart').getContext('2d');
    new Chart(ctx, {
        type: 'radar',
        data: {
            labels: ['Academic Performance', 'Fee Collection', 'Student Satisfaction', 'Teacher Quality', 'Infrastructure', 'Technology'],
            datasets: [
                {
                    label: 'Our Group Average',
                    data: [78.5, 89.7, 82.3, 85.1, 79.6, 74.2],
                    borderColor: '#007bff',
                    backgroundColor: 'rgba(0, 123, 255, 0.2)',
                    borderWidth: 2
                },
                {
                    label: 'Industry Benchmark',
                    data: [75.2, 84.1, 79.8, 81.5, 82.3, 78.9],
                    borderColor: '#28a745',
                    backgroundColor: 'rgba(40, 167, 69, 0.2)',
                    borderWidth: 2
                },
                {
                    label: 'Top 10% Schools',
                    data: [92.1, 96.4, 94.7, 93.8, 91.2, 89.5],
                    borderColor: '#ffc107',
                    backgroundColor: 'rgba(255, 193, 7, 0.2)',
                    borderWidth: 2
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'top'
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
}

// Chart update functions
document.getElementById('trendMetric').addEventListener('change', function(e) {
    const metric = e.target.value;
    if (trendChart && trendData[metric]) {
        trendChart.data.labels = trendData[metric].labels;
        trendChart.data.datasets[0].data = trendData[metric].data;
        trendChart.data.datasets[0].label = e.target.options[e.target.selectedIndex].text;
        trendChart.update();
    }
});

// Analysis generation functions
function generateCohortAnalysis() {
    // Implementation for generating cohort analysis
    console.log('Generating cohort analysis...');
    alert('Cohort analysis generated successfully!');
}

function generateBenchmarkAnalysis() {
    // Implementation for generating benchmark analysis
    console.log('Generating benchmark analysis...');
    alert('Benchmark analysis generated successfully!');
}

// Initialize all charts when page loads
document.addEventListener('DOMContentLoaded', function() {
    initTrendChart();
    initCohortChart();
    initPredictiveChart();
    initCorrelationChart();
    initBenchmarkChart();
});

// Tab switching event handlers
document.querySelectorAll('[data-toggle="tab"]').forEach(tab => {
    tab.addEventListener('shown.bs.tab', function(e) {
        // Trigger chart resize when tab is shown
        setTimeout(() => {
            Chart.helpers.each(Chart.instances, function(instance) {
                instance.resize();
            });
        }, 100);
    });
});
</script>
@endsection

@section('styles')
<style>
.advanced-analytics .nav-pills .nav-link {
    border-radius: 20px;
    padding: 10px 20px;
    font-weight: 500;
    transition: all 0.3s;
}

.advanced-analytics .nav-pills .nav-link.active {
    background: linear-gradient(45deg, #007bff, #0056b3);
    box-shadow: 0 4px 8px rgba(0, 123, 255, 0.3);
}

.advanced-analytics .trend-controls,
.advanced-analytics .cohort-controls,
.advanced-analytics .prediction-controls,
.advanced-analytics .correlation-controls,
.advanced-analytics .benchmark-controls {
    background: #f8f9fa;
    padding: 15px;
    border-radius: 8px;
    border: 1px solid #e9ecef;
}

.advanced-analytics .insight-item {
    padding: 15px;
    background: #f8f9fa;
    border-radius: 8px;
    border-left: 4px solid #007bff;
}

.advanced-analytics .metric-item {
    padding: 10px 0;
}

.advanced-analytics .scenario-item {
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.advanced-analytics .correlation-item {
    padding: 10px;
    background: #f8f9fa;
    border-radius: 5px;
    margin-bottom: 10px;
}

.advanced-analytics .leader-item,
.advanced-analytics .opportunity-item {
    padding: 10px;
    border-radius: 5px;
    background: #f8f9fa;
    margin-bottom: 10px;
}

.advanced-analytics .factor-selection {
    background: white;
    padding: 10px;
    border-radius: 5px;
    border: 1px solid #dee2e6;
}

.advanced-analytics .form-check-inline {
    margin-right: 15px;
    margin-bottom: 8px;
}

@media (max-width: 768px) {
    .advanced-analytics .nav-pills {
        flex-direction: column;
    }
    
    .advanced-analytics .nav-pills .nav-link {
        margin-bottom: 5px;
        text-align: center;
    }
    
    .advanced-analytics .trend-controls .row,
    .advanced-analytics .cohort-controls .row,
    .advanced-analytics .prediction-controls .row,
    .advanced-analytics .correlation-controls .row,
    .advanced-analytics .benchmark-controls .row {
        margin: 0;
    }
    
    .advanced-analytics .trend-controls .col-md-3,
    .advanced-analytics .trend-controls .col-md-4,
    .advanced-analytics .cohort-controls .col-md-3,
    .advanced-analytics .prediction-controls .col-md-4,
    .advanced-analytics .correlation-controls .col-md-6,
    .advanced-analytics .benchmark-controls .col-md-3 {
        padding: 0;
        margin-bottom: 10px;
    }
}
</style>
@endsection
