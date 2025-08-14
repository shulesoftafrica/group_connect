@extends('layouts.admin')

@section('title', 'Communication Analytics')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">Communication Analytics</h1>
            <p class="mb-0 text-muted">Analyze communication performance and engagement across your school group</p>
        </div>
        <div class="d-flex gap-2">
            <select class="form-select" style="width: 200px;" id="timeRange">
                <option value="7">Last 7 days</option>
                <option value="30" selected>Last 30 days</option>
                <option value="90">Last 3 months</option>
                <option value="365">Last year</option>
            </select>
            <button class="btn btn-outline-primary" onclick="exportReport()">
                <i class="fas fa-download me-2"></i>Export Report
            </button>
        </div>
    </div>

    <!-- Key Metrics Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Messages
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">15,420</div>
                            <div class="text-xs text-success">
                                <i class="fas fa-arrow-up"></i> 12.5% from last month
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
                            <div class="h5 mb-0 font-weight-bold text-gray-800">98.5%</div>
                            <div class="text-xs text-success">
                                <i class="fas fa-arrow-up"></i> 0.8% improvement
                            </div>
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
                                Engagement Rate
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">76.3%</div>
                            <div class="text-xs text-warning">
                                <i class="fas fa-arrow-down"></i> 2.1% from last month
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
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Avg Response Time
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">2.4h</div>
                            <div class="text-xs text-success">
                                <i class="fas fa-arrow-down"></i> 0.3h faster
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clock fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row 1 -->
    <div class="row mb-4">
        <!-- Monthly Communication Trends -->
        <div class="col-xl-8 col-lg-7">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Monthly Communication Trends</h6>
                    <div class="dropdown">
                        <button class="btn btn-sm btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown">
                            View Options
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="#" onclick="updateChart('volume')">Message Volume</a></li>
                            <li><a class="dropdown-item" href="#" onclick="updateChart('delivery')">Delivery Rates</a></li>
                            <li><a class="dropdown-item" href="#" onclick="updateChart('engagement')">Engagement Rates</a></li>
                        </ul>
                    </div>
                </div>
                <div class="card-body">
                    <canvas id="trendsChart" width="100%" height="50"></canvas>
                </div>
            </div>
        </div>

        <!-- Communication Channels Distribution -->
        <div class="col-xl-4 col-lg-5">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Channel Distribution</h6>
                </div>
                <div class="card-body">
                    <canvas id="channelsChart" width="100%" height="150"></canvas>
                    <div class="mt-3">
                        @foreach($data['engagement_by_type'] ?? ['SMS' => 72.5, 'Email' => 68.3, 'WhatsApp' => 85.7] as $type => $rate)
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="text-sm">{{ $type }}</span>
                            <div class="d-flex align-items-center">
                                <span class="text-sm font-weight-bold me-2">{{ $rate }}%</span>
                                <div class="progress" style="width: 60px; height: 8px;">
                                    <div class="progress-bar bg-{{ $type == 'SMS' ? 'info' : ($type == 'Email' ? 'warning' : 'success') }}" style="width: {{ $rate }}%"></div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row 2 -->
    <div class="row mb-4">
        <!-- Peak Hours Analysis -->
        <div class="col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Peak Communication Hours</h6>
                </div>
                <div class="card-body">
                    <canvas id="peakHoursChart" width="100%" height="80"></canvas>
                </div>
            </div>
        </div>

        <!-- School Performance Comparison -->
        <div class="col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">School Engagement Comparison</h6>
                </div>
                <div class="card-body">
                    <canvas id="schoolComparisonChart" width="100%" height="80"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Detailed Analytics Tables -->
    <div class="row mb-4">
        <!-- Campaign Performance -->
        <div class="col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Top Performing Campaigns</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead class="table-light">
                                <tr>
                                    <th>Campaign</th>
                                    <th>Type</th>
                                    <th>Sent</th>
                                    <th>Engagement</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        <strong>Term Opening</strong>
                                        <div class="text-muted small">Announcement</div>
                                    </td>
                                    <td><span class="badge bg-info">Announcement</span></td>
                                    <td>1,250</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <span class="me-2">84.2%</span>
                                            <div class="progress" style="width: 50px; height: 6px;">
                                                <div class="progress-bar bg-success" style="width: 84.2%"></div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <strong>Fee Reminder</strong>
                                        <div class="text-muted small">Alert</div>
                                    </td>
                                    <td><span class="badge bg-warning">Alert</span></td>
                                    <td>2,340</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <span class="me-2">79.8%</span>
                                            <div class="progress" style="width: 50px; height: 6px;">
                                                <div class="progress-bar bg-success" style="width: 79.8%"></div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <strong>Parent Survey</strong>
                                        <div class="text-muted small">Survey</div>
                                    </td>
                                    <td><span class="badge bg-primary">Survey</span></td>
                                    <td>450</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <span class="me-2">76.4%</span>
                                            <div class="progress" style="width: 50px; height: 6px;">
                                                <div class="progress-bar bg-success" style="width: 76.4%"></div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Delivery Issues -->
        <div class="col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Delivery Analysis</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-6">
                            <div class="text-center">
                                <h4 class="text-success">15,185</h4>
                                <p class="text-muted mb-0">Delivered</p>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="text-center">
                                <h4 class="text-warning">145</h4>
                                <p class="text-muted mb-0">Pending</p>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="text-center">
                                <h4 class="text-danger">90</h4>
                                <p class="text-muted mb-0">Failed</p>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="mb-3">
                        <h6>Common Failure Reasons</h6>
                        <div class="d-flex justify-content-between">
                            <span>Invalid Number</span>
                            <span class="text-danger">45%</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span>Network Issues</span>
                            <span class="text-danger">30%</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span>Blocked/Spam</span>
                            <span class="text-danger">15%</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span>Other</span>
                            <span class="text-danger">10%</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- School-wise Performance Table -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">School-wise Communication Performance</h6>
            <div class="d-flex gap-2">
                <select class="form-select form-select-sm" style="width: 150px;" id="performanceMetric">
                    <option value="engagement">Engagement Rate</option>
                    <option value="delivery">Delivery Rate</option>
                    <option value="volume">Message Volume</option>
                </select>
                <button class="btn btn-sm btn-outline-primary" onclick="refreshTable()">
                    <i class="fas fa-sync-alt"></i>
                </button>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover" id="schoolPerformanceTable">
                    <thead class="table-light">
                        <tr>
                            <th>School</th>
                            <th>Total Messages</th>
                            <th>Delivery Rate</th>
                            <th>Engagement Rate</th>
                            <th>Avg Response Time</th>
                            <th>Last Activity</th>
                            <th>Trend</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar avatar-sm me-2">
                                        <div class="avatar-title bg-primary rounded-circle">G</div>
                                    </div>
                                    <div>
                                        <strong>Greenfield Academy</strong>
                                        <div class="text-muted small">Primary & Secondary</div>
                                    </div>
                                </div>
                            </td>
                            <td><span class="badge bg-info">1,245</span></td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <span class="me-2">98.2%</span>
                                    <div class="progress" style="width: 50px; height: 6px;">
                                        <div class="progress-bar bg-success" style="width: 98.2%"></div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <span class="me-2">82.4%</span>
                                    <div class="progress" style="width: 50px; height: 6px;">
                                        <div class="progress-bar bg-success" style="width: 82.4%"></div>
                                    </div>
                                </div>
                            </td>
                            <td>2.1 hours</td>
                            <td class="text-muted">2 hours ago</td>
                            <td>
                                <i class="fas fa-arrow-up text-success"></i>
                                <span class="text-success small">+5.2%</span>
                            </td>
                            <td><span class="badge bg-success">Excellent</span></td>
                        </tr>
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar avatar-sm me-2">
                                        <div class="avatar-title bg-warning rounded-circle">S</div>
                                    </div>
                                    <div>
                                        <strong>Sunrise Primary</strong>
                                        <div class="text-muted small">Primary School</div>
                                    </div>
                                </div>
                            </td>
                            <td><span class="badge bg-info">890</span></td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <span class="me-2">95.8%</span>
                                    <div class="progress" style="width: 50px; height: 6px;">
                                        <div class="progress-bar bg-success" style="width: 95.8%"></div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <span class="me-2">74.1%</span>
                                    <div class="progress" style="width: 50px; height: 6px;">
                                        <div class="progress-bar bg-warning" style="width: 74.1%"></div>
                                    </div>
                                </div>
                            </td>
                            <td>3.2 hours</td>
                            <td class="text-muted">1 day ago</td>
                            <td>
                                <i class="fas fa-arrow-down text-danger"></i>
                                <span class="text-danger small">-2.1%</span>
                            </td>
                            <td><span class="badge bg-warning">Good</span></td>
                        </tr>
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar avatar-sm me-2">
                                        <div class="avatar-title bg-info rounded-circle">B</div>
                                    </div>
                                    <div>
                                        <strong>Bright Future High</strong>
                                        <div class="text-muted small">Secondary School</div>
                                    </div>
                                </div>
                            </td>
                            <td><span class="badge bg-info">1,567</span></td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <span class="me-2">97.5%</span>
                                    <div class="progress" style="width: 50px; height: 6px;">
                                        <div class="progress-bar bg-success" style="width: 97.5%"></div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <span class="me-2">79.8%</span>
                                    <div class="progress" style="width: 50px; height: 6px;">
                                        <div class="progress-bar bg-success" style="width: 79.8%"></div>
                                    </div>
                                </div>
                            </td>
                            <td>1.8 hours</td>
                            <td class="text-muted">5 hours ago</td>
                            <td>
                                <i class="fas fa-arrow-up text-success"></i>
                                <span class="text-success small">+3.4%</span>
                            </td>
                            <td><span class="badge bg-success">Excellent</span></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Monthly Communication Trends Chart
const trendsCtx = document.getElementById('trendsChart').getContext('2d');
const trendsChart = new Chart(trendsCtx, {
    type: 'line',
    data: {
        labels: @json($data['monthly_trends']['labels'] ?? ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun']),
        datasets: [{
            label: 'SMS',
            data: @json($data['monthly_trends']['sms_data'] ?? [1200, 1450, 1350, 1600, 1750, 1820]),
            borderColor: 'rgb(75, 192, 192)',
            backgroundColor: 'rgba(75, 192, 192, 0.1)',
            tension: 0.4
        }, {
            label: 'Email',
            data: @json($data['monthly_trends']['email_data'] ?? [800, 920, 850, 1100, 1200, 1250]),
            borderColor: 'rgb(255, 99, 132)',
            backgroundColor: 'rgba(255, 99, 132, 0.1)',
            tension: 0.4
        }, {
            label: 'WhatsApp',
            data: @json($data['monthly_trends']['whatsapp_data'] ?? [200, 280, 320, 450, 520, 580]),
            borderColor: 'rgb(54, 162, 235)',
            backgroundColor: 'rgba(54, 162, 235, 0.1)',
            tension: 0.4
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'top',
            }
        },
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});

// Channel Distribution Chart
const channelsCtx = document.getElementById('channelsChart').getContext('2d');
new Chart(channelsCtx, {
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

// Peak Hours Chart
const peakHoursCtx = document.getElementById('peakHoursChart').getContext('2d');
new Chart(peakHoursCtx, {
    type: 'bar',
    data: {
        labels: @json($data['peak_hours']['labels'] ?? ['6AM', '8AM', '10AM', '12PM', '2PM', '4PM', '6PM', '8PM']),
        datasets: [{
            label: 'Messages Sent',
            data: @json($data['peak_hours']['data'] ?? [150, 420, 380, 250, 380, 450, 520, 280]),
            backgroundColor: 'rgba(54, 162, 235, 0.6)',
            borderColor: 'rgb(54, 162, 235)',
            borderWidth: 1
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
                beginAtZero: true
            }
        }
    }
});

// School Comparison Chart
const schoolComparisonCtx = document.getElementById('schoolComparisonChart').getContext('2d');
new Chart(schoolComparisonCtx, {
    type: 'horizontalBar',
    data: {
        labels: ['Greenfield Academy', 'Sunrise Primary', 'Bright Future High', 'Valley School', 'Mountain View'],
        datasets: [{
            label: 'Engagement Rate (%)',
            data: [82.4, 74.1, 79.8, 68.5, 75.2],
            backgroundColor: [
                'rgba(75, 192, 192, 0.6)',
                'rgba(255, 206, 86, 0.6)',
                'rgba(54, 162, 235, 0.6)',
                'rgba(255, 99, 132, 0.6)',
                'rgba(153, 102, 255, 0.6)'
            ],
            borderColor: [
                'rgb(75, 192, 192)',
                'rgb(255, 206, 86)',
                'rgb(54, 162, 235)',
                'rgb(255, 99, 132)',
                'rgb(153, 102, 255)'
            ],
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        indexAxis: 'y',
        plugins: {
            legend: {
                display: false
            }
        },
        scales: {
            x: {
                beginAtZero: true,
                max: 100
            }
        }
    }
});

// Chart update functions
function updateChart(type) {
    // In a real implementation, this would fetch new data from the server
    console.log('Updating chart to show:', type);
    // Update the trendsChart data based on the selected type
}

function exportReport() {
    // In a real implementation, this would generate and download a report
    alert('Generating analytics report...');
}

function refreshTable() {
    // In a real implementation, this would refresh the table data
    console.log('Refreshing table data...');
}

// Time range filter
document.getElementById('timeRange').addEventListener('change', function() {
    const timeRange = this.value;
    console.log('Time range changed to:', timeRange, 'days');
    // In a real implementation, this would update all charts and data
});

// Performance metric filter
document.getElementById('performanceMetric').addEventListener('change', function() {
    const metric = this.value;
    console.log('Performance metric changed to:', metric);
    // In a real implementation, this would update the table sorting
});
</script>
@endpush
