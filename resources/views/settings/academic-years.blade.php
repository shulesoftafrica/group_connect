@extends('layouts.settings')

@section('title', 'Academic Years Management')
@section('page-title', 'Academic Years')tends('layouts.admin')

@section('title', 'Academic Year Management')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">
            <i class="bi bi-calendar-event me-2"></i>Academic Year Management
        </h1>
        <div class="btn-toolbar mb-2 mb-md-0">
            <div class="btn-group me-2">
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addAcademicYearModal">
                    <i class="bi bi-plus-circle"></i> Add Academic Year
                </button>
                <button type="button" class="btn btn-outline-secondary" onclick="bulkUpdate()">
                    <i class="bi bi-lightning-charge"></i> Bulk Update
                </button>
            </div>
        </div>
    </div>

    <!-- Current Academic Year Status -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-primary">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="bi bi-calendar-check me-2"></i>Current Academic Year Status
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="text-center">
                                <h4 class="text-primary">2024-2025</h4>
                                <p class="text-muted mb-0">Current Academic Year</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center">
                                <h4 class="text-success">{{ count($academicYears) }}</h4>
                                <p class="text-muted mb-0">Schools Configured</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center">
                                <h4 class="text-info">Term 1</h4>
                                <p class="text-muted mb-0">Current Term</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center">
                                <h4 class="text-warning">142</h4>
                                <p class="text-muted mb-0">Days Remaining</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Academic Years Table -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="bi bi-table me-2"></i>Academic Years by School
                    </h6>
                </div>
                <div class="card-body">
                    <!-- Filters -->
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <select class="form-select" id="schoolFilter">
                                <option value="">All Schools</option>
                                @foreach(collect($academicYears)->groupBy('school_name') as $schoolName => $years)
                                <option value="{{ $schoolName }}">{{ $schoolName }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <select class="form-select" id="yearFilter">
                                <option value="">All Years</option>
                                @foreach(collect($academicYears)->groupBy('year_name') as $yearName => $years)
                                <option value="{{ $yearName }}">{{ $yearName }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <input type="text" class="form-control" id="searchFilter" placeholder="Search schools...">
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover" id="academicYearsTable">
                            <thead class="table-light">
                                <tr>
                                    <th>
                                        <input type="checkbox" id="selectAll" class="form-check-input">
                                    </th>
                                    <th>School</th>
                                    <th>Academic Year</th>
                                    <th>Start Date</th>
                                    <th>End Date</th>
                                    <th>Status</th>
                                    <th>Progress</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($academicYears as $year)
                                <tr>
                                    <td>
                                        <input type="checkbox" class="form-check-input school-checkbox" 
                                               value="{{ $year->id }}">
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="school-avatar me-3">
                                                <i class="bi bi-building-fill"></i>
                                            </div>
                                            <div>
                                                <div class="fw-bold">{{ $year->school_name ?? 'Unknown School' }}</div>
                                                <div class="text-muted small">{{ $year->school_code ?? 'N/A' }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="fw-bold">{{ $year->year_name ?? 'Not Set' }}</span>
                                    </td>
                                    <td>
                                        <span class="text-muted">{{ $year->start_date ? \Carbon\Carbon::parse($year->start_date)->format('M d, Y') : 'Not Set' }}</span>
                                    </td>
                                    <td>
                                        <span class="text-muted">{{ $year->end_date ? \Carbon\Carbon::parse($year->end_date)->format('M d, Y') : 'Not Set' }}</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $year->status === 'active' ? 'success' : ($year->status === 'upcoming' ? 'primary' : 'secondary') }}">
                                            {{ ucfirst($year->status ?? 'inactive') }}
                                        </span>
                                    </td>
                                    <td>
                                        @php
                                            $progress = 0;
                                            if ($year->start_date && $year->end_date) {
                                                $start = \Carbon\Carbon::parse($year->start_date);
                                                $end = \Carbon\Carbon::parse($year->end_date);
                                                $now = now();
                                                if ($now >= $start && $now <= $end) {
                                                    $totalDays = $end->diffInDays($start);
                                                    $passedDays = $now->diffInDays($start);
                                                    $progress = $totalDays > 0 ? ($passedDays / $totalDays) * 100 : 0;
                                                } elseif ($now > $end) {
                                                    $progress = 100;
                                                }
                                            }
                                        @endphp
                                        <div class="progress" style="height: 10px;">
                                            <div class="progress-bar bg-primary" role="progressbar" 
                                                 style="width: {{ min(100, max(0, $progress)) }}%" 
                                                 aria-valuenow="{{ $progress }}" aria-valuemin="0" aria-valuemax="100">
                                            </div>
                                        </div>
                                        <small class="text-muted">{{ number_format($progress, 1) }}% complete</small>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <button type="button" class="btn btn-sm btn-outline-primary" 
                                                    onclick="editAcademicYear({{ $year->id }})" data-bs-toggle="tooltip" title="Edit">
                                                <i class="bi bi-pencil"></i>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-outline-info" 
                                                    onclick="viewDetails({{ $year->id }})" data-bs-toggle="tooltip" title="View Details">
                                                <i class="bi bi-eye"></i>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-outline-danger" 
                                                    onclick="deleteAcademicYear({{ $year->id }})" data-bs-toggle="tooltip" title="Delete">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center py-4">
                                        <div class="text-muted">
                                            <i class="bi bi-calendar-x fa-3x mb-3 d-block"></i>
                                            No academic years configured
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Academic Year Modal -->
<div class="modal fade" id="addAcademicYearModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="{{ route('settings.academic-years.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="bi bi-plus-circle me-2"></i>Add Academic Year
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="year_name" class="form-label">Academic Year <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="year_name" name="year_name" 
                                       placeholder="e.g., 2024-2025" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="year_description" class="form-label">Description</label>
                                <input type="text" class="form-control" id="year_description" name="year_description" 
                                       placeholder="Optional description">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="start_date" class="form-label">Start Date <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" id="start_date" name="start_date" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="end_date" class="form-label">End Date <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" id="end_date" name="end_date" required>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="target_schools" class="form-label">Apply to Schools</label>
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="radio" name="school_selection" id="all_schools" value="all" checked>
                            <label class="form-check-label" for="all_schools">
                                Apply to all connected schools
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="school_selection" id="selected_schools" value="selected">
                            <label class="form-check-label" for="selected_schools">
                                Apply to selected schools only
                            </label>
                        </div>
                    </div>

                    <div id="schoolSelectionDiv" class="mb-3" style="display: none;">
                        <label class="form-label">Select Schools</label>
                        <div class="border rounded p-3" style="max-height: 200px; overflow-y: auto;">
                            @foreach(collect($academicYears)->groupBy('school_name') as $schoolName => $years)
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="schools[]" 
                                       value="{{ $years->first()->uid }}" id="school_{{ $years->first()->uid }}">
                                <label class="form-check-label" for="school_{{ $years->first()->uid }}">
                                    {{ $schoolName }}
                                </label>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="alert alert-info">
                        <i class="bi bi-info-circle me-2"></i>
                        <strong>Note:</strong> This will create the academic year for the selected schools. 
                        Existing academic years will not be overwritten.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-plus-circle me-2"></i>Create Academic Year
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Bulk Update Modal -->
<div class="modal fade" id="bulkUpdateModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="bulkUpdateForm">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="bi bi-lightning-charge me-2"></i>Bulk Update Academic Years
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="bulk_action" class="form-label">Action</label>
                        <select class="form-select" id="bulk_action" name="bulk_action" required>
                            <option value="">Select Action</option>
                            <option value="update_dates">Update Start/End Dates</option>
                            <option value="change_status">Change Status</option>
                            <option value="set_current">Set as Current Year</option>
                        </select>
                    </div>

                    <div id="bulk_dates_section" style="display: none;">
                        <div class="row">
                            <div class="col-6">
                                <div class="mb-3">
                                    <label for="bulk_start_date" class="form-label">New Start Date</label>
                                    <input type="date" class="form-control" id="bulk_start_date" name="bulk_start_date">
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="mb-3">
                                    <label for="bulk_end_date" class="form-label">New End Date</label>
                                    <input type="date" class="form-control" id="bulk_end_date" name="bulk_end_date">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="bulk_status_section" style="display: none;">
                        <div class="mb-3">
                            <label for="bulk_status" class="form-label">New Status</label>
                            <select class="form-select" id="bulk_status" name="bulk_status">
                                <option value="active">Active</option>
                                <option value="upcoming">Upcoming</option>
                                <option value="completed">Completed</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>
                    </div>

                    <div class="alert alert-warning">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        <strong>Warning:</strong> This action will be applied to all selected schools. 
                        Please review your selection carefully.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-warning">
                        <i class="bi bi-lightning-charge me-2"></i>Apply Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
.school-avatar {
    width: 40px;
    height: 40px;
    border-radius: 8px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 16px;
}

.progress {
    border-radius: 10px;
}

.table th {
    font-weight: 600;
    color: #5a5c69;
    border-top: none;
}
</style>

<script>
// School selection toggle
document.addEventListener('DOMContentLoaded', function() {
    const allSchoolsRadio = document.getElementById('all_schools');
    const selectedSchoolsRadio = document.getElementById('selected_schools');
    const schoolSelectionDiv = document.getElementById('schoolSelectionDiv');

    selectedSchoolsRadio.addEventListener('change', function() {
        if (this.checked) {
            schoolSelectionDiv.style.display = 'block';
        }
    });

    allSchoolsRadio.addEventListener('change', function() {
        if (this.checked) {
            schoolSelectionDiv.style.display = 'none';
        }
    });

    // Bulk action handling
    const bulkActionSelect = document.getElementById('bulk_action');
    const bulkDatesSection = document.getElementById('bulk_dates_section');
    const bulkStatusSection = document.getElementById('bulk_status_section');

    bulkActionSelect.addEventListener('change', function() {
        bulkDatesSection.style.display = 'none';
        bulkStatusSection.style.display = 'none';
        
        if (this.value === 'update_dates') {
            bulkDatesSection.style.display = 'block';
        } else if (this.value === 'change_status') {
            bulkStatusSection.style.display = 'block';
        }
    });

    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Table filtering
    const schoolFilter = document.getElementById('schoolFilter');
    const yearFilter = document.getElementById('yearFilter');
    const searchFilter = document.getElementById('searchFilter');

    function filterTable() {
        const schoolValue = schoolFilter.value.toLowerCase();
        const yearValue = yearFilter.value.toLowerCase();
        const searchValue = searchFilter.value.toLowerCase();
        const rows = document.querySelectorAll('#academicYearsTable tbody tr');

        rows.forEach(row => {
            const schoolText = row.cells[1].textContent.toLowerCase();
            const yearText = row.cells[2].textContent.toLowerCase();
            const allText = row.textContent.toLowerCase();

            const schoolMatch = !schoolValue || schoolText.includes(schoolValue);
            const yearMatch = !yearValue || yearText.includes(yearValue);
            const searchMatch = !searchValue || allText.includes(searchValue);

            row.style.display = schoolMatch && yearMatch && searchMatch ? '' : 'none';
        });
    }

    schoolFilter.addEventListener('change', filterTable);
    yearFilter.addEventListener('change', filterTable);
    searchFilter.addEventListener('input', filterTable);

    // Select all functionality
    const selectAllCheckbox = document.getElementById('selectAll');
    const schoolCheckboxes = document.querySelectorAll('.school-checkbox');

    selectAllCheckbox.addEventListener('change', function() {
        schoolCheckboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
        });
    });
});

function bulkUpdate() {
    const checkedBoxes = document.querySelectorAll('.school-checkbox:checked');
    if (checkedBoxes.length === 0) {
        alert('Please select at least one academic year to update.');
        return;
    }
    new bootstrap.Modal(document.getElementById('bulkUpdateModal')).show();
}

function editAcademicYear(id) {
    // Implement edit functionality
    alert('Edit functionality will be implemented');
}

function viewDetails(id) {
    // Implement view details functionality
    alert('View details functionality will be implemented');
}

function deleteAcademicYear(id) {
    if (confirm('Are you sure you want to delete this academic year?')) {
        // Implement delete functionality
        alert('Delete functionality will be implemented');
    }
}
</script>
@endsection
