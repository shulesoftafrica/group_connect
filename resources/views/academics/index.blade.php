@extends('layouts.admin')

@section('title', 'Academics Dashboard')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center">
            <h1 class="h3 mb-0">Academics Dashboard</h1>
            <div>
                <button class="btn btn-outline-primary me-2">
                    <i class="bi bi-download me-1"></i>Export Report
                </button>
                <div class="btn-group">
                    <button type="button" class="btn btn-primary dropdown-toggle" data-bs-toggle="dropdown">
                        <i class="bi bi-calendar me-1"></i>Current Term
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="#">Term 1</a></li>
                        <li><a class="dropdown-item" href="#">Term 2</a></li>
                        <li><a class="dropdown-item" href="#">Term 3</a></li>
                        <li><a class="dropdown-item" href="#">Full Year</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Academic KPIs -->
<div class="row mb-4">
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card stats-card border-0 h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted text-uppercase fw-bold mb-1">Total Students</h6>
                        <h2 class="mb-0">{{ number_format($totalStudents) }}</h2>
                        <small class="text-info">
                            <i class="bi bi-info-circle"></i> Across {{ $schools->count() }} schools
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
                        <h6 class="text-muted text-uppercase fw-bold mb-1">Average Performance</h6>
                        <h2 class="mb-0">{{ number_format($avgPerformance, 1) }}%</h2>
                        <small class="text-{{ $avgPerformance >= 80 ? 'success' : ($avgPerformance >= 70 ? 'warning' : 'danger') }}">
                            <i class="bi bi-{{ $avgPerformance >= 80 ? 'arrow-up' : 'arrow-down' }}"></i> 
                            {{ $avgPerformance >= 80 ? 'Excellent' : ($avgPerformance >= 70 ? 'Good' : 'Needs Improvement') }}
                        </small>
                    </div>
                    <div class="bg-success bg-opacity-10 rounded-3 p-3">
                        <i class="bi bi-graph-up-arrow text-success fs-4"></i>
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
                        <h2 class="mb-0">{{ number_format($avgAttendance, 1) }}%</h2>
                        <small class="text-{{ $avgAttendance >= 90 ? 'success' : ($avgAttendance >= 80 ? 'warning' : 'danger') }}">
                            <i class="bi bi-calendar-check"></i> Group average
                        </small>
                    </div>
                    <div class="bg-info bg-opacity-10 rounded-3 p-3">
                        <i class="bi bi-calendar-check-fill text-info fs-4"></i>
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
                        <h6 class="text-muted text-uppercase fw-bold mb-1">Schools Needing Support</h6>
                        <h2 class="mb-0">{{ $lowPerformingSchools }}</h2>
                        <small class="text-{{ $lowPerformingSchools == 0 ? 'success' : 'warning' }}">
                            <i class="bi bi-exclamation-triangle"></i> Performance < 70%
                        </small>
                    </div>
                    <div class="bg-warning bg-opacity-10 rounded-3 p-3">
                        <i class="bi bi-exclamation-triangle text-warning fs-4"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Performance Overview -->
<div class="row mb-4">
    <div class="col-xl-8 mb-4">
        <div class="card stats-card border-0 h-100">
            <div class="card-header bg-transparent border-0 pb-0">
                <h5 class="card-title mb-0">Academic Performance Trends</h5>
            </div>
            <div class="card-body">
                <canvas id="performanceChart" height="300"></canvas>
            </div>
        </div>
    </div>
    
    <div class="col-xl-4 mb-4">
        <div class="card stats-card border-0 h-100">
            <div class="card-header bg-transparent border-0 pb-0">
                <h5 class="card-title mb-0">Performance Distribution</h5>
            </div>
            <div class="card-body">
                <canvas id="distributionChart" height="300"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- School Performance Table -->
<div class="row">
    <div class="col-xl-8 mb-4">
        <div class="card stats-card border-0 h-100">
            <div class="card-header bg-transparent border-0">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">School Performance Analysis</h5>
                    <div class="btn-group">
                        <button type="button" class="btn btn-sm btn-outline-primary dropdown-toggle" data-bs-toggle="dropdown">
                            Sort by Performance
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="#">Highest First</a></li>
                            <li><a class="dropdown-item" href="#">Lowest First</a></li>
                            <li><a class="dropdown-item" href="#">Alphabetical</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>School</th>
                                <th>Students</th>
                                <th>Performance</th>
                                <th>Attendance</th>
                                <th>Trend</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($schools->sortByDesc('academic_index') as $school)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 35px; height: 35px;">
                                            <i class="bi bi-building text-primary"></i>
                                        </div>
                                        <div>
                                            <h6 class="mb-0">{{ $school->name }}</h6>
                                            <small class="text-muted">{{ $school->region }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="fw-bold">{{ number_format($school->total_students) }}</span>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="progress me-2" style="width: 50px; height: 6px;">
                                            <div class="progress-bar bg-{{ $school->academic_index >= 85 ? 'success' : ($school->academic_index >= 70 ? 'warning' : 'danger') }}" 
                                                 style="width: {{ $school->academic_index }}%"></div>
                                        </div>
                                        <span class="small fw-bold">{{ number_format($school->academic_index, 1) }}%</span>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-{{ $school->attendance_percentage >= 90 ? 'success' : ($school->attendance_percentage >= 80 ? 'warning' : 'danger') }}">
                                        {{ number_format($school->attendance_percentage, 1) }}%
                                    </span>
                                </td>
                                <td>
                                    <i class="bi bi-arrow-{{ rand(0,1) ? 'up' : 'down' }} text-{{ rand(0,1) ? 'success' : 'danger' }}"></i>
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-eye me-1"></i>View Details
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
    
    <!-- Quick Actions & Alerts -->
    <div class="col-xl-4 mb-4">
        <div class="card stats-card border-0 h-100">
            <div class="card-header bg-transparent border-0 pb-0">
                <h5 class="card-title mb-0">Academic Actions</h5>
            </div>
            <div class="card-body">
                <!-- Academic Alerts -->
                <div class="mb-4">
                    <h6 class="text-muted">Recent Alerts</h6>
                    @if($lowPerformingSchools > 0)
                    <div class="alert alert-warning alert-sm" role="alert">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        <strong>{{ $lowPerformingSchools }} schools</strong> need academic support
                    </div>
                    @endif
                    <div class="alert alert-info alert-sm" role="alert">
                        <i class="bi bi-calendar-event me-2"></i>
                        Term exams scheduled for next week
                    </div>
                    <div class="alert alert-success alert-sm" role="alert">
                        <i class="bi bi-trophy me-2"></i>
                        3 schools exceeded performance targets
                    </div>
                </div>
                
                <!-- Quick Actions -->
                <div>
                    <h6 class="text-muted">Quick Actions</h6>
                    <div class="d-grid gap-2">
                        <button class="btn btn-primary btn-sm">
                            <i class="bi bi-upload me-2"></i>Upload Results
                        </button>
                        <button class="btn btn-success btn-sm">
                            <i class="bi bi-file-earmark-text me-2"></i>Generate Report Cards
                        </button>
                        <button class="btn btn-info btn-sm">
                            <i class="bi bi-calendar-plus me-2"></i>Schedule Assessments
                        </button>
                        <button class="btn btn-warning btn-sm">
                            <i class="bi bi-megaphone me-2"></i>Send Performance Alert
                        </button>
                        <button class="btn btn-secondary btn-sm">
                            <i class="bi bi-gear me-2"></i>Update Academic Policies
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
    // Performance Trends Chart
    const performanceCtx = document.getElementById('performanceChart').getContext('2d');
    new Chart(performanceCtx, {
        type: 'line',
        data: {
            labels: ['Term 1', 'Term 2', 'Term 3', 'Annual'],
            datasets: [
                {
                    label: 'Mathematics',
                    data: [78, 82, 85, 83],
                    borderColor: '#0d6efd',
                    backgroundColor: 'rgba(13, 110, 253, 0.1)',
                    tension: 0.4
                },
                {
                    label: 'English',
                    data: [82, 84, 87, 85],
                    borderColor: '#198754',
                    backgroundColor: 'rgba(25, 135, 84, 0.1)',
                    tension: 0.4
                },
                {
                    label: 'Science',
                    data: [75, 79, 82, 80],
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

    // Performance Distribution Chart
    const distributionCtx = document.getElementById('distributionChart').getContext('2d');
    new Chart(distributionCtx, {
        type: 'doughnut',
        data: {
            labels: ['Excellent (85%+)', 'Good (70-84%)', 'Needs Improvement (<70%)'],
            datasets: [{
                data: [
                    {{ $schools->where('academic_index', '>=', 85)->count() }},
                    {{ $schools->whereBetween('academic_index', [70, 84])->count() }},
                    {{ $schools->where('academic_index', '<', 70)->count() }}
                ],
                backgroundColor: ['#198754', '#ffc107', '#dc3545'],
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                }
            }
        }
    });
});
</script>
@endpush
