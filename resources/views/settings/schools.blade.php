@extends('layouts.settings')

@section('title', 'School Management')
@section('page-title', 'School Management')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">
            <i class="bi bi-building-fill me-2"></i>School Management
        </h1>
        <div class="btn-toolbar mb-2 mb-md-0">
            <div class="btn-group me-2">
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addSchoolModal">
                    <i class="bi bi-building-add"></i> Add School
                </button>
                <!-- <button type="button" class="btn btn-outline-secondary" onclick="exportSchools()">
                    <i class="bi bi-download"></i> Export
                </button> -->
            </div>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="row mb-4">
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Connected Schools</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ count($schools) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-building-fill fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Total Students</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ number_format(collect($schools)->sum('student_count')) }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-people-fill fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Annual Revenue</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                ${{ number_format(collect($schools)->sum('annual_revenue')) }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-currency-dollar fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Active Users</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ collect($schools)->sum('assigned_users') }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-person-check-fill fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Schools Grid -->
    <div class="row">
        @forelse($schools as $school)
        <div class="col-lg-4 col-md-6 mb-4">
            <div class="card school-card h-100 shadow hover-card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h6 class="mb-0 font-weight-bold text-primary">
                        <i class="bi bi-building me-2"></i>{{ $school->sname ?? 'Unnamed School' }}
                    </h6>
                    <div class="dropdown">
                        <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" 
                                data-bs-toggle="dropdown">
                            <i class="bi bi-three-dots-vertical"></i>
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="#" onclick="viewSchoolDetails({{ $school->id }})">
                                <i class="bi bi-eye me-2"></i>View Details
                            </a></li>
                            <li><a class="dropdown-item" href="#" onclick="editSchool({{ $school->id }})">
                                <i class="bi bi-pencil me-2"></i>Edit
                            </a></li>
                            <li><a class="dropdown-item" href="#" onclick="manageUsers({{ $school->id }})">
                                <i class="bi bi-people me-2"></i>Manage Users
                            </a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item text-danger" href="#" onclick="unlinkSchool({{ $school->id }})">
                                <i class="bi bi-unlink me-2"></i>Unlink School
                            </a></li>
                        </ul>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-4">
                            <div class="mb-2">
                                <i class="bi bi-people-fill text-primary fa-2x"></i>
                            </div>
                            <h5 class="mb-0">{{ number_format($school->student_count) }}</h5>
                            <small class="text-muted">Students</small>
                        </div>
                        <div class="col-4">
                            <div class="mb-2">
                                <i class="bi bi-currency-dollar text-success fa-2x"></i>
                            </div>
                            <h5 class="mb-0">${{ number_format($school->annual_revenue) }}</h5>
                            <small class="text-muted">Revenue</small>
                        </div>
                        <div class="col-4">
                            <div class="mb-2">
                                <i class="bi bi-person-check text-info fa-2x"></i>
                            </div>
                            <h5 class="mb-0">{{ $school->assigned_users }}</h5>
                            <small class="text-muted">Users</small>
                        </div>
                    </div>

                    <hr>

                    <div class="d-flex justify-content-between align-items-center">
                        <span class="badge bg-secondary">{{ $school->assigned_users }}</span>
                        <div class="btn-group btn-group-sm" role="group">
                            <a href="#" class="btn btn-outline-primary" onclick="viewDashboard({{ $school->id }})" 
                               data-bs-toggle="tooltip" title="View Dashboard">
                                <i class="bi bi-graph-up"></i>
                            </a>
                            <a href="#" class="btn btn-outline-success" onclick="viewFinance({{ $school->id }})"
                               data-bs-toggle="tooltip" title="View Finance">
                                <i class="bi bi-calculator"></i>
                            </a>
                            <a href="#" class="btn btn-outline-info" onclick="viewSettings({{ $school->id }})"
                               data-bs-toggle="tooltip" title="Settings">
                                <i class="bi bi-gear"></i>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-footer text-muted">
                    <small>
                        <i class="bi bi-geo-alt me-1"></i>{{ $school->location ?? 'Location not set' }}
                    </small>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12">
            <div class="card">
                <div class="card-body text-center py-5">
                    <i class="bi bi-building fa-4x text-muted mb-3"></i>
                    <h4 class="text-muted">No Schools Connected</h4>
                    <p class="text-muted mb-4">Start by linking an existing school or requesting a new school creation.</p>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addSchoolModal">
                        <i class="bi bi-building-add me-2"></i>Add Your First School
                    </button>
                </div>
            </div>
        </div>
        @endforelse
    </div>
</div>

<!-- Add School Modal -->
<div class="modal fade" id="addSchoolModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="bi bi-building-add me-2"></i>Add School
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <!-- Tab Navigation -->
                <ul class="nav nav-tabs" id="addSchoolTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="link-existing-tab" data-bs-toggle="tab" 
                                data-bs-target="#link-existing" type="button" role="tab">
                            <i class="bi bi-link me-2"></i>Link Existing School
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="create-new-tab" data-bs-toggle="tab" 
                                data-bs-target="#create-new" type="button" role="tab">
                            <i class="bi bi-plus-circle me-2"></i>Request New School
                        </button>
                    </li>
                </ul>

                <!-- Tab Content -->
                <div class="tab-content" id="addSchoolTabsContent">
                    <!-- Link Existing School -->
                    <div class="tab-pane fade show active" id="link-existing" role="tabpanel">
                        <form action="{{ route('settings.schools.store') }}" method="POST" class="pt-3">
                            @csrf
                            <input type="hidden" name="action_type" value="link_existing">
                            
                            <div class="mb-3">
                                <label for="school_code" class="form-label">ShuleSoft School Code <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="school_code" name="school_code" 
                                       placeholder="Enter the school's unique code" required>
                                <div class="form-text">
                                    Contact the school administrator to get their ShuleSoft school code.
                                </div>
                            </div>

                            <div class="alert alert-info">
                                <i class="bi bi-info-circle me-2"></i>
                                <strong>Note:</strong> The school must already be using ShuleSoft system. 
                                Once linked, you'll have access to their aggregated data in your group dashboard.
                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-link me-2"></i>Link School
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Create New School -->
                    <div class="tab-pane fade" id="create-new" role="tabpanel">
                        <form action="{{ route('settings.schools.store') }}" method="POST" class="pt-3">
                            @csrf
                            <input type="hidden" name="action_type" value="create_new">

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="school_name" class="form-label">School Name <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="school_name" name="school_name" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="location" class="form-label">Location <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="location" name="location" 
                                               placeholder="City, State/Region" required>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="contact_person" class="form-label">Contact Person <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="contact_person" name="contact_person" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="contact_email" class="form-label">Contact Email <span class="text-danger">*</span></label>
                                        <input type="email" class="form-control" id="contact_email" name="contact_email" required>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="contact_phone" class="form-label">Contact Phone <span class="text-danger">*</span></label>
                                        <input type="tel" class="form-control" id="contact_phone" name="contact_phone" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="expected_students" class="form-label">Expected Student Count</label>
                                        <input type="number" class="form-control" id="expected_students" name="expected_students" 
                                               placeholder="Approximate number">
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="additional_notes" class="form-label">Additional Information</label>
                                <textarea class="form-control" id="additional_notes" name="additional_notes" rows="3"
                                          placeholder="Any additional information about the school..."></textarea>
                            </div>

                            <div class="alert alert-warning">
                                <i class="bi bi-exclamation-triangle me-2"></i>
                                <strong>Note:</strong> This will submit a request for new school creation. 
                                Our team will review and set up the school within 1-2 business days.
                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-send me-2"></i>Submit Request
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- School Details Modal -->
<div class="modal fade" id="schoolDetailsModal" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="bi bi-building me-2"></i>School Details
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="schoolDetailsBody">
                <!-- Content will be loaded dynamically -->
            </div>
        </div>
    </div>
</div>

<style>
.border-left-primary {
    border-left: 0.25rem solid #4e73df !important;
}
.border-left-success {
    border-left: 0.25rem solid #1cc88a !important;
}
.border-left-info {
    border-left: 0.25rem solid #36b9cc !important;
}
.border-left-warning {
    border-left: 0.25rem solid #f6c23e !important;
}

.school-card {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}
.school-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
}

.hover-card {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}
.hover-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
}
</style>

<script>
function viewSchoolDetails(schoolUid) {
    fetch(`/settings/schools/${schoolUid}/details`)
        .then(response => response.json())
        .then(data => {
            document.getElementById('schoolDetailsBody').innerHTML = data.html;
            new bootstrap.Modal(document.getElementById('schoolDetailsModal')).show();
        });
}

function editSchool(schoolUid) {
    // Implement school editing
    alert('School editing functionality will be implemented');
}

function manageUsers(schoolUid) {
    // Redirect to user management with school filter
    window.location.href = `/settings/users?school=${schoolUid}`;
}

function unlinkSchool(schoolUid) {
    if (confirm('Are you sure you want to unlink this school? This will remove access to their data.')) {
        fetch(`/settings/schools/${schoolUid}/unlink`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
            },
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Error unlinking school: ' + data.message);
            }
        });
    }
}

function viewDashboard(schoolUid) {
    // Open school-specific dashboard
    window.open(`/school/${schoolUid}/dashboard`, '_blank');
}

function viewFinance(schoolUid) {
    // Open school-specific finance page
    window.open(`/school/${schoolUid}/finance`, '_blank');
}

function viewSettings(schoolUid) {
    // Open school-specific settings
    window.open(`/school/${schoolUid}/settings`, '_blank');
}

function exportSchools() {
    window.location.href = '/settings/schools/export';
}

// Initialize tooltips
document.addEventListener('DOMContentLoaded', function() {
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});
</script>
@endsection
