@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center">
            <h1 class="h3 mb-0">Group Overview Dashboard</h1>
            <!-- <div>
                <button type="button" class="btn btn-outline-primary me-2">
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
            </div> -->
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
                    @foreach($topSchools as $school)
                        <div class="border-0 px-0 py-3">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="flex-grow-1">
                                    <div class="fw-bold fs-6 mb-1 text-capitalize">{{ ucfirst($school->schema_name) }}</div>
                                    <div class="row align-items-center">
                                        <div class="col-auto">
                                            <span class="d-flex align-items-center text-success fw-semibold">
                                                <span class="fs-6">Tsh {{ number_format($school->amount) }}</span>
                                                <small class="text-muted ms-2">collected</small>
                                            </span>
                                        </div>
                                        <div class="col-auto">
                                            <span class="badge bg-light text-dark border border-info px-2 py-1" style="font-size: 0.95em;">
                                                <i class="bi bi-person me-1 text-info"></i>
                                                Avg Rev Per Student: <span class="fw-semibold">Tsh {{ number_format($school->avg_per_student, 2) }}</span>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <span class="badge bg-primary rounded-pill fs-6 px-3 py-2 ms-3 shadow-sm">#{{ $school->rank }}</span>
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
    <div class="col-xl-8 mb-4">
        <div class="card stats-card border-0 h-100">
            <div class="card-header bg-transparent border-0 pb-0">
                <h5 class="card-title mb-0">Top 5 Schools with Poor Revenue Collections</h5>
            </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-sm table-hover mb-0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>School Name</th>
                            <th>Collected</th>
                            <th>Target</th>
                            <th>Collection %</th>
                        </tr>
                    </thead>
                    <tbody>

                        @forelse($poorRevenueSchools as $index => $school)

                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ ucfirst($school->schema_name) }}</td>
                                <td>{{ number_format($school->collected) }}</td>
                                <td>{{ number_format($school->target) }}</td>
                                <td>
                                    {{ $school->target > 0 ? number_format(($school->collected / $school->target) * 100, 1) : '0.0' }}%
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted">No data available</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
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
                    <div style="color: black !important;"  class="alert alert-warning alert-sm" role="alert" style="cursor:pointer;" data-bs-toggle="modal" data-bs-target="#unpublishedResultsModal" id="unpublishedResultsAlert">
                        <i  style="color: black !important;" class="bi bi-exclamation-triangle me-2"></i>
                        <strong  style="color: black !important;" >{{count($examResults)}} schools</strong>  published exam results
                    </div>

                    
                    <div style="color: black !important;" class="alert alert-info alert-sm" role="alert" style="cursor:pointer;" data-bs-toggle="modal" data-bs-target="#budgetApprovalsModal" id="pendingBudgetAlert">
                        <i style="color: black !important;"  class="bi bi-info-circle me-2"></i>
                        <strong  style="color: black !important;" >{{count($pendingBudgets)}} budget approvals</strong> pending review
                    </div>

                  
                    @php
                        $rate = $collection_rate ?? 0;
                        if ($rate > 80) {
                            $alertClass = 'alert-success';
                            $icon = 'bi-check-circle';
                            $message = 'Fee collection target achieved ';
                        } elseif ($rate > 50) {
                            $alertClass = 'alert-primary';
                            $icon = 'bi-info-circle';
                            $message = 'Fee collection rate is moderate ';
                        } elseif ($rate > 30) {
                            $alertClass = 'alert-warning';
                            $icon = 'bi-exclamation-triangle';
                            $message = 'Fee collection rate is low';
                        } else {
                            $alertClass = 'alert-danger';
                            $icon = 'bi-x-circle';
                            $message = 'Fee collection rate is critically low';
                        }
                    @endphp
                    <div  style="color: black !important;"  class="alert {{ $alertClass }} alert-sm" role="alert">
                        <i  style="color: black !important;" class="bi {{ $icon }} me-2"></i>
                        {{ $message }} ({{ number_format($rate, 1) }}%)
                    </div>
                </div>
                
                <!-- Quick Actions -->
                <!-- <div>
                    <h6 class="text-muted">Quick Actions</h6>
                    <div class="d-grid gap-2">
                        <button type="button" class="btn btn-primary btn-sm">
                            <i class="bi bi-chat-dots me-2"></i>Send Group Message
                        </button>
                        <button type="button" class="btn btn-success btn-sm">
                            <i class="bi bi-check2-all me-2"></i>Approve Budgets
                        </button>
                        <button type="button" class="btn btn-info btn-sm">
                            <i class="bi bi-gear me-2"></i>Push Settings Update
                        </button>
                        <button type="button" class="btn btn-secondary btn-sm">
                            <i class="bi bi-download me-2"></i>Generate Report
                        </button>
                    </div>
                </div> -->
            </div>
        </div>
    </div>
</div>


  <!-- Budget Approvals Modal -->
                    <div class="modal fade" id="budgetApprovalsModal" tabindex="-1" aria-labelledby="budgetApprovalsModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg modal-dialog-scrollable">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="budgetApprovalsModalLabel">Pending Budget Approvals</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="table-responsive">
                                        <table class="table table-sm table-hover align-middle">
                                            <thead>
                                                <tr>
                                                    <th>School Name</th>
                                                    <th>Budget Prepared</th>
                                                    <th>Budget from</th>
                                                    <th>Budget to</th>
                                                    <th>Total Amount</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($pendingBudgets as $budget)
                                                    <tr>
                                                        <td>{{ ucfirst($budget->schema_name) }}</td>
                                                        <td>
                                                            @if($budget->is_prepared)
                                                                <span class="badge bg-success">Yes</span>
                                                            @else
                                                                <span class="badge bg-secondary">No</span>
                                                            @endif
                                                        </td>
                                                        <td>{{ $budget->budget_from }}</td>
                                                        <td>{{ $budget->budget_to }}</td>
                                                        <td>{{ number_format($budget->total_amount, 2) }}</td>
                                                        <td>
                                                            <!-- <a href="{{ url('budgets.show', $budget->schema_name) }}" class="btn btn-info btn-sm" target="_blank">
                                                                <i class="bi bi-eye"></i> View
                                                            </a> -->
                                                            @if($budget->is_prepared)
                                                                <form action="{{ url('budgets.approve', $budget->schema_name) }}" method="POST" class="d-inline">
                                                                    @csrf
                                                                    <button type="submit" class="btn btn-success btn-sm">
                                                                        <i class="bi bi-check2"></i> Approve
                                                                    </button>
                                                                </form>
                                                                <form action="{{ url('budgets.reject', $budget->schema_name) }}" method="POST" class="d-inline ms-1">
                                                                    @csrf
                                                                    <button type="submit" class="btn btn-danger btn-sm">
                                                                        <i class="bi bi-x"></i> Reject
                                                                    </button>
                                                                </form>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="5" class="text-center text-muted">No pending budgets</td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Unpublished Results Modal -->
                    <div class="modal fade" id="unpublishedResultsModal" tabindex="-1" aria-labelledby="unpublishedResultsModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg modal-dialog-scrollable">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="unpublishedResultsModalLabel">Schools with Unpublished Exam Results</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="table-responsive">
                                        <table class="table table-sm table-hover align-middle">
                                            <thead>
                                                <tr>
                                                    <th>School Name</th>
                                                    <th>Exam Details</th>
                                                    <th>Last Exam Date</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($examResults as $result)
                                                    <tr>
                                                        <td>{{ ucfirst($result->schema_name) }}</td>
                                                        <td>{{ $result->total_exams }}</td>
                                                        <td>{{ $result->last_exam_date }}</td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="3" class="text-center text-muted">No data available</td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </div>
                    </div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Prepare data from backend (passed as JSON)
    const feeCollectionData = @json($feeCollectionTrend ?? []);
    const months = @json($months ?? []);
    console.log(feeCollectionData, months);
    const ctx = document.getElementById('performanceChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: months.length ? months : ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
            datasets: [
                {
                    label: 'Payment Collected',
                    data: feeCollectionData.length ? feeCollectionData : [78000, 82000, 85000, 87000, 89000, 92000],
                    borderColor: '#ffc107',
                    backgroundColor: 'rgba(255, 193, 7, 0.1)',
                    tension: 0.4,
                    fill: true
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
                    beginAtZero: true
                }
            }
        }
    });
});
</script>
@endpush
