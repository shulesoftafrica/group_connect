@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center">
            <h1 class="h3 mb-0">Group Overview Dashboard</h1>
            <div>
                <button class="btn btn-outline-primary me-2">
                    <i class="bi bi-download me-1"></i>Export
                </button>
                <div class="btn-group">
                    <button type="button" class="btn btn-primary dropdown-toggle" data-bs-toggle="dropdown">
                        <i class="bi bi-calendar me-1"></i>This Month
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="#">This Week</a></li>
                        <li><a class="dropdown-item" href="#">This Month</a></li>
                        <li><a class="dropdown-item" href="#">This Quarter</a></li>
                        <li><a class="dropdown-item" href="#">This Year</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- KPI Cards -->
<div class="row mb-4">
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card stats-card border-0 h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted text-uppercase fw-bold mb-1">Total Students</h6>
                        <h2 class="mb-0">{{ number_format($totalStudents ?? 12543) }}</h2>
                        <small class="text-success">
                            <i class="bi bi-arrow-up"></i> 5.2% vs last month
                        </small>
                    </div>
                    <div class="bg-primary bg-opacity-10 rounded-3 p-3">
                        <i class="bi bi-people-fill text-primary fs-4"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card stats-card border-0 h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted text-uppercase fw-bold mb-1">Average Attendance</h6>
                        <h2 class="mb-0">{{ number_format($avgAttendance ?? 87.5, 1) }}%</h2>
                        <small class="text-success">
                            <i class="bi bi-arrow-up"></i> 2.1% vs last month
                        </small>
                    </div>
                    <div class="bg-success bg-opacity-10 rounded-3 p-3">
                        <i class="bi bi-calendar-check-fill text-success fs-4"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card stats-card border-0 h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted text-uppercase fw-bold mb-1">Fees Collected</h6>
                        <h2 class="mb-0">{{ number_format($feesCollected ?? 2847693) }}</h2>
                        <small class="text-success">
                            <i class="bi bi-arrow-up"></i> 12.8% vs last month
                        </small>
                    </div>
                    <div class="bg-warning bg-opacity-10 rounded-3 p-3">
                        <i class="bi bi-currency-dollar text-warning fs-4"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card stats-card border-0 h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted text-uppercase fw-bold mb-1">Active Schools</h6>
                        <h2 class="mb-0">{{ $activeSchools ?? 24 }}</h2>
                        <small class="text-info">
                            <i class="bi bi-info-circle"></i> {{ $totalSchools ?? 25 }} total schools
                        </small>
                    </div>
                    <div class="bg-info bg-opacity-10 rounded-3 p-3">
                        <i class="bi bi-building text-info fs-4"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Charts and Tables Row -->
<div class="row">
    <!-- Performance Chart -->
    <div class="col-xl-8 mb-4">
        <div class="card stats-card border-0 h-100">
            <div class="card-header bg-transparent border-0 pb-0">
                <h5 class="card-title mb-0">Performance Trends</h5>
            </div>
            <div class="card-body">
                <canvas id="performanceChart" height="300"></canvas>
            </div>
        </div>
    </div>
    
    <!-- Top Performing Schools -->
    <div class="col-xl-4 mb-4">
        <div class="card stats-card border-0 h-100">
            <div class="card-header bg-transparent border-0 pb-0">
                <h5 class="card-title mb-0">Top Performing Schools</h5>
            </div>
            <div class="card-body">
                <div class="list-group list-group-flush">
                    @foreach($topSchools ?? [
                        ['name' => 'Green Valley Academy', 'score' => 96.8, 'trend' => 'up'],
                        ['name' => 'Sunrise Secondary', 'score' => 94.2, 'trend' => 'up'],
                        ['name' => 'Mountain View School', 'score' => 92.5, 'trend' => 'down'],
                        ['name' => 'Riverside Primary', 'score' => 91.8, 'trend' => 'up'],
                        ['name' => 'Central High School', 'score' => 89.9, 'trend' => 'up']
                    ] as $school)
                    <div class="list-group-item border-0 px-0">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="mb-1">{{ $school['name'] }}</h6>
                                <small class="text-muted">Academic Score: {{ $school['score'] }}%</small>
                            </div>
                            <div class="text-{{ $school['trend'] == 'up' ? 'success' : 'danger' }}">
                                <i class="bi bi-arrow-{{ $school['trend'] }}"></i>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Schools Map and Alerts -->
<div class="row">
    <!-- Interactive Map -->
    <div class="col-xl-8 mb-4">
        <div class="card stats-card border-0 h-100">
            <div class="card-header bg-transparent border-0 pb-0">
                <h5 class="card-title mb-0">Schools Geographic Distribution</h5>
            </div>
            <div class="card-body">
                <div id="schoolsMap" style="height: 400px; background-color: #f8f9fa; border-radius: 0.5rem; display: flex; align-items: center; justify-content: center;">
                    <div class="text-center">
                        <i class="bi bi-geo-alt-fill text-primary fs-1 mb-3"></i>
                        <h5>Interactive Map</h5>
                        <p class="text-muted">School locations will be displayed here</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Alerts and Quick Actions -->
    <div class="col-xl-4 mb-4">
        <div class="card stats-card border-0 h-100">
            <div class="card-header bg-transparent border-0 pb-0">
                <h5 class="card-title mb-0">Alerts & Quick Actions</h5>
            </div>
            <div class="card-body">
                <!-- Alerts -->
                <div class="mb-4">
                    <h6 class="text-muted">Recent Alerts</h6>
                    <div class="alert alert-warning alert-sm" role="alert">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        <strong>3 schools</strong> have low attendance this week
                    </div>
                    <div class="alert alert-info alert-sm" role="alert">
                        <i class="bi bi-info-circle me-2"></i>
                        <strong>5 budget approvals</strong> pending review
                    </div>
                    <div class="alert alert-success alert-sm" role="alert">
                        <i class="bi bi-check-circle me-2"></i>
                        Fee collection target achieved for this month
                    </div>
                </div>
                
                <!-- Quick Actions -->
                <div>
                    <h6 class="text-muted">Quick Actions</h6>
                    <div class="d-grid gap-2">
                        <button class="btn btn-primary btn-sm">
                            <i class="bi bi-chat-dots me-2"></i>Send Group Message
                        </button>
                        <button class="btn btn-success btn-sm">
                            <i class="bi bi-check2-all me-2"></i>Approve Budgets
                        </button>
                        <button class="btn btn-info btn-sm">
                            <i class="bi bi-gear me-2"></i>Push Settings Update
                        </button>
                        <button class="btn btn-secondary btn-sm">
                            <i class="bi bi-download me-2"></i>Generate Report
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Performance Chart
    const ctx = document.getElementById('performanceChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
            datasets: [
                {
                    label: 'Academic Performance',
                    data: [85, 87, 86, 89, 91, 88],
                    borderColor: '#0d6efd',
                    backgroundColor: 'rgba(13, 110, 253, 0.1)',
                    tension: 0.4
                },
                {
                    label: 'Attendance Rate',
                    data: [82, 85, 87, 86, 88, 87],
                    borderColor: '#198754',
                    backgroundColor: 'rgba(25, 135, 84, 0.1)',
                    tension: 0.4
                },
                {
                    label: 'Fee Collection',
                    data: [78, 82, 85, 87, 89, 92],
                    borderColor: '#ffc107',
                    backgroundColor: 'rgba(255, 193, 7, 0.1)',
                    tension: 0.4
                }
            ]
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
                    beginAtZero: true,
                    max: 100
                }
            }
        }
    });
});
</script>
@endpush
