@extends('layouts.admin')

@section('title', 'Schools Overview')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center">
            <h1 class="h3 mb-0">Schools Overview</h1>
            <div>
                <!-- <button class="btn btn-outline-secondary me-2" data-bs-toggle="modal" data-bs-target="#filterModal">
                    <i class="bi bi-funnel me-1"></i>Filter
                </button> -->
                <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addSchoolModal">
                    <i class="bi bi-plus-lg me-1"></i>Add School
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Stats Cards -->
<div class="row mb-4">
    <div class="col-xl-3 col-md-6 mb-3">
        <div class="card stats-card border-0">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted text-uppercase fw-bold mb-1">Total Schools</h6>
                        <h3 class="mb-0">{{ $schools->count() }}</h3>
                    </div>
                    <div class="bg-primary bg-opacity-10 rounded-3 p-3">
                        <i class="bi bi-building text-primary fs-4"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- <div class="col-xl-3 col-md-6 mb-3">
        <div class="card stats-card border-0">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted text-uppercase fw-bold mb-1">Active Schools</h6>
                        <h3 class="mb-0">{{ $schools->where('status', 'active')->count() }}</h3>
                    </div>
                    <div class="bg-success bg-opacity-10 rounded-3 p-3">
                        <i class="bi bi-check-circle text-success fs-4"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6 mb-3">
        <div class="card stats-card border-0">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted text-uppercase fw-bold mb-1">Total Students</h6>
                        <h3 class="mb-0">{{ number_format($schools->sum('total_students')) }}</h3>
                    </div>
                    <div class="bg-info bg-opacity-10 rounded-3 p-3">
                        <i class="bi bi-people text-info fs-4"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6 mb-3">
        <div class="card stats-card border-0">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted text-uppercase fw-bold mb-1">Avg Performance</h6>
                        <h3 class="mb-0">{{ number_format($schools->avg('academic_index'), 1) }}%</h3>
                    </div>
                    <div class="bg-warning bg-opacity-10 rounded-3 p-3">
                        <i class="bi bi-graph-up text-warning fs-4"></i>
                    </div>
                </div>
            </div>
        </div>
    </div> -->
</div>

<!-- Schools Table -->
<div class="card stats-card border-0">
    <div class="card-header bg-transparent border-0">
        <div class="row align-items-center">
            <div class="col">
                <h5 class="card-title mb-0">All Schools</h5>
            </div>
            <div class="col-auto">
                <div class="input-group">
                    <input type="text" class="form-control" placeholder="Search schools..." id="searchInput">
                    <button class="btn btn-outline-secondary" type="button">
                        <i class="bi bi-search"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>School Name</th>
                        <th>Location</th>
                        <th>Students</th>
                        <th>Fee Collection</th>
                        <th>Total Staff</th>
                        <!-- <th>Academic Index</th>
                        <th>Status</th> -->
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($schools as $school)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                    <i class="bi bi-building text-primary"></i>
                                </div>
                                <div>
                                    <h6 class="mb-0">{{ $school->schoolSetting->sname }}</h6>
                                
                                </div>
                            </div>
                        </td>
                        <td>
                            <div>
                                <span>{{ $school->schoolSetting->address }}</span>
                                @if($school->schoolSetting->address)
                                    <br><small class="text-muted">{{ $school->schoolSetting->address }}</small>
                                @endif
                            </div>
                        </td>
                        <td>
                            <span class="fw-bold">{{ number_format($school->studentsCount()) }}</span>
                        </td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="progress me-2" style="width: 60px; height: 8px;">
                                    <div class="progress-bar bg-{{ $school->feeCollectionPercentage() >= 80 ? 'success' : ($school->feeCollectionPercentage() >= 60 ? 'warning' : 'danger') }}" 
                                         style="width: {{ $school->feeCollectionPercentage() }}%"></div>
                                </div>
                                <span class="small">{{ number_format($school->feeCollectionPercentage(), 1) }}%</span>
                            </div>
                        </td>
                        <td>
                            <div class="d-flex align-items-center">
                               
                                <span class="small">{{ number_format($school->totalStaff(), 1) }}</span>
                            </div>
                        </td>
                        <!-- <td>
                            <span class="badge bg-{{ $school->academic_index >= 85 ? 'success' : ($school->academic_index >= 70 ? 'warning' : 'danger') }}">
                                {{ number_format($school->academic_index, 1) }}%
                            </span>
                        </td>
                        <td>
                            <span class="badge bg-{{ $school->status == 'active' ? 'success' : ($school->status == 'pending' ? 'warning' : 'danger') }}">
                                {{ ucfirst($school->status) }}
                            </span>
                        </td> -->
                        <td>
                            <div class="btn-group">
                                <button type="button" class="btn btn-sm btn-outline-primary dropdown-toggle" data-bs-toggle="dropdown">
                                    Actions
                                </button>
                                <ul class="dropdown-menu">
                                    <!-- <li><a class="dropdown-item" href="#"><i class="bi bi-eye me-2"></i>View Details</a></li> -->
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <form action="{{ route('schools.destroy', $school->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to remove this school from your group?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="dropdown-item text-danger">
                                                <i class="bi bi-trash me-2"></i>Remove
                                            </button>
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-4">
                            <div class="text-muted">
                                <i class="bi bi-building fs-1 mb-3"></i>
                                <p>No schools found. Add your first school to get started.</p>
                                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addSchoolModal">
                                    <i class="bi bi-plus-lg me-1"></i>Add School
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Add School Modal -->
<div class="modal fade" id="addSchoolModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add School to a Group </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ route('schools.store') }}">
                @csrf
            
                <div class="modal-body">
                    <div class="alert alert-info mb-3" role="alert">
                        <strong>How to get your Group Connect Code:</strong><br>
                        Visit your ShuleSoft system (or ask a school admin). Go to <strong>System Settings</strong>, then click <strong>Miscellaneous Settings</strong> to find the <strong>ShuleSoft Group Code</strong>. Only school admins can view this code. Paste it below to connect the school to your group.
                    </div>
                    <div class="mb-3">
                        <label for="group_connect_code" class="form-label">Group Connect Code *</label>
                        <input type="text" class="form-control" id="group_connect_code" name="group_connect_code" required placeholder="Enter Group Connect Code">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Add School</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Filter Modal -->
<div class="modal fade" id="filterModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Filter Schools</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label for="filter_region" class="form-label">Region</label>
                    <select class="form-select" id="filter_region">
                        <option value="">All Regions</option>
                        <option value="Central">Central</option>
                        <option value="Northern">Northern</option>
                        <option value="Southern">Southern</option>
                        <option value="Eastern">Eastern</option>
                        <option value="Western">Western</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="filter_status" class="form-label">Status</label>
                    <select class="form-select" id="filter_status">
                        <option value="">All Status</option>
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                        <option value="pending">Pending</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="filter_performance" class="form-label">Performance Tier</label>
                    <select class="form-select" id="filter_performance">
                        <option value="">All Performance</option>
                        <option value="high">High (85%+)</option>
                        <option value="medium">Medium (70-84%)</option>
                        <option value="low">Low (<70%)</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary">Apply Filters</button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Search functionality
    const searchInput = document.getElementById('searchInput');
    const tableRows = document.querySelectorAll('tbody tr');
    
    searchInput.addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase();
        
        tableRows.forEach(row => {
            const schoolName = row.querySelector('h6')?.textContent.toLowerCase() || '';
            const location = row.cells[1]?.textContent.toLowerCase() || '';
            
            if (schoolName.includes(searchTerm) || location.includes(searchTerm)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    });
});
</script>
@endpush
