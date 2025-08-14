@extends('layouts.admin')

@section('title', 'Staff Directory')

@section('content')
<div class="container-fluid px-4">
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('hr.index') }}">HR Dashboard</a></li>
                    <li class="breadcrumb-item active">Staff Directory</li>
                </ol>
            </nav>
            <h1 class="h3 mb-0 text-gray-800">Staff Directory</h1>
            <p class="text-muted mb-0">Unified staff management across all schools</p>
        </div>
        <div class="d-flex gap-2">
            <button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#addStaffModal">
                <i class="bi bi-person-plus me-1"></i> Add Staff
            </button>
            <button class="btn btn-outline-success" onclick="exportStaffDirectory()">
                <i class="bi bi-download me-1"></i> Export Directory
            </button>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#bulkActionsModal">
                <i class="bi bi-lightning me-1"></i> Bulk Actions
            </button>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card text-center shadow">
                <div class="card-body">
                    <div class="h4 text-primary">{{ number_format($staffData->count()) }}</div>
                    <div class="text-muted">Total Staff</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center shadow">
                <div class="card-body">
                    <div class="h4 text-success">{{ $staffData->where('status', 'Active')->count() }}</div>
                    <div class="text-muted">Active Staff</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center shadow">
                <div class="card-body">
                    <div class="h4 text-warning">{{ $staffData->where('status', 'On Leave')->count() }}</div>
                    <div class="text-muted">On Leave</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center shadow">
                <div class="card-body">
                    <div class="h4 text-info">{{ $schools->count() }}</div>
                    <div class="text-muted">Schools</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters and Search -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="bi bi-funnel me-2"></i>Search & Filter Staff
            </h6>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-3 mb-3">
                    <label class="form-label">Search</label>
                    <input type="text" class="form-control" id="staffSearch" placeholder="Search by name, email, or ID...">
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label">School</label>
                    <select class="form-select" id="schoolFilter">
                        <option value="">All Schools</option>
                        @foreach($schools as $school)
                        <option value="{{ $school->id }}">{{ $school->settings['school_name'] ?? 'Unknown School' }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label">Role</label>
                    <select class="form-select" id="roleFilter">
                        <option value="">All Roles</option>
                        <option value="Teacher">Teacher</option>
                        <option value="Support Staff">Support Staff</option>
                        <option value="Administration">Administration</option>
                        <option value="Management">Management</option>
                    </select>
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label">Status</label>
                    <select class="form-select" id="statusFilter">
                        <option value="">All Status</option>
                        <option value="Active">Active</option>
                        <option value="On Leave">On Leave</option>
                        <option value="Probation">Probation</option>
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <button class="btn btn-primary" onclick="applyFilters()">
                        <i class="bi bi-search me-1"></i> Apply Filters
                    </button>
                    <button class="btn btn-outline-secondary" onclick="clearFilters()">
                        <i class="bi bi-x me-1"></i> Clear
                    </button>
                </div>
                <div class="col-md-6 text-end">
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="checkbox" id="selectAllStaff">
                        <label class="form-check-label" for="selectAllStaff">Select All</label>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Staff Directory Table -->
    <div class="card shadow">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="bi bi-people me-2"></i>Staff Members
            </h6>
            <div class="dropdown">
                <button class="btn btn-sm btn-outline-primary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                    View Options
                </button>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="#" onclick="setView('card')">Card View</a></li>
                    <li><a class="dropdown-item" href="#" onclick="setView('table')">Table View</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item" href="#" onclick="setView('detailed')">Detailed View</a></li>
                </ul>
            </div>
        </div>
        <div class="card-body">
            <!-- Table View -->
            <div id="tableView">
                <div class="table-responsive">
                    <table class="table table-hover" id="staffTable">
                        <thead>
                            <tr>
                                <th><input type="checkbox" id="selectAllTableStaff"></th>
                                <th>Staff ID</th>
                                <th>Name</th>
                                <th>Role</th>
                                <th>School</th>
                                <th>Contact</th>
                                <th>Status</th>
                                <th>Hire Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($staffData as $staff)
                            <tr data-school="{{ $staff['school_id'] }}" data-role="{{ $staff['role'] }}" data-status="{{ $staff['status'] }}">
                                <td><input type="checkbox" name="staff_ids" value="{{ $staff['id'] }}"></td>
                                <td><code>{{ $staff['id'] }}</code></td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="staff-avatar bg-primary text-white rounded-circle me-2 d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                                            {{ substr($staff['name'], 0, 1) }}
                                        </div>
                                        <div>
                                            <div class="font-weight-bold">{{ $staff['name'] }}</div>
                                            <div class="text-xs text-muted">{{ $staff['email'] }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-secondary">{{ $staff['role'] }}</span>
                                </td>
                                <td>
                                    <div class="text-sm">{{ $staff['school'] }}</div>
                                    <div class="text-xs text-muted">{{ $staff['department'] }}</div>
                                </td>
                                <td>
                                    <div class="text-sm">{{ $staff['contact'] }}</div>
                                </td>
                                <td>
                                    <span class="badge bg-{{ $staff['status'] === 'Active' ? 'success' : ($staff['status'] === 'On Leave' ? 'warning' : 'info') }}">
                                        {{ $staff['status'] }}
                                    </span>
                                </td>
                                <td>{{ \Carbon\Carbon::parse($staff['hire_date'])->format('M d, Y') }}</td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <button class="btn btn-sm btn-outline-primary" onclick="viewStaffProfile('{{ $staff['id'] }}')">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                        <button class="btn btn-sm btn-outline-secondary" onclick="editStaffProfile('{{ $staff['id'] }}')">
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                        <button class="btn btn-sm btn-outline-danger dropdown-toggle" data-bs-toggle="dropdown">
                                            <i class="bi bi-three-dots"></i>
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li><a class="dropdown-item" href="#" onclick="sendMessage('{{ $staff['id'] }}')">Send Message</a></li>
                                            <li><a class="dropdown-item" href="#" onclick="viewPayroll('{{ $staff['id'] }}')">View Payroll</a></li>
                                            <li><a class="dropdown-item" href="#" onclick="viewLeave('{{ $staff['id'] }}')">Leave History</a></li>
                                            <li><hr class="dropdown-divider"></li>
                                            <li><a class="dropdown-item text-danger" href="#" onclick="deactivateStaff('{{ $staff['id'] }}')">Deactivate</a></li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Card View -->
            <div id="cardView" style="display: none;">
                <div class="row">
                    @foreach($staffData as $staff)
                    <div class="col-xl-3 col-lg-4 col-md-6 mb-4" data-school="{{ $staff['school_id'] }}" data-role="{{ $staff['role'] }}" data-status="{{ $staff['status'] }}">
                        <div class="card h-100 shadow-sm">
                            <div class="card-body text-center">
                                <div class="staff-avatar bg-primary text-white rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center" style="width: 60px; height: 60px; font-size: 24px;">
                                    {{ substr($staff['name'], 0, 1) }}
                                </div>
                                <h6 class="card-title">{{ $staff['name'] }}</h6>
                                <p class="card-text text-muted">{{ $staff['role'] }}</p>
                                <p class="card-text">
                                    <small class="text-muted">{{ $staff['school'] }}</small>
                                </p>
                                <span class="badge bg-{{ $staff['status'] === 'Active' ? 'success' : ($staff['status'] === 'On Leave' ? 'warning' : 'info') }} mb-2">
                                    {{ $staff['status'] }}
                                </span>
                                <div class="btn-group w-100" role="group">
                                    <button class="btn btn-sm btn-outline-primary" onclick="viewStaffProfile('{{ $staff['id'] }}')">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                    <button class="btn btn-sm btn-outline-secondary" onclick="editStaffProfile('{{ $staff['id'] }}')">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                    <button class="btn btn-sm btn-outline-success" onclick="sendMessage('{{ $staff['id'] }}')">
                                        <i class="bi bi-chat"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Pagination and Summary -->
            <div class="row mt-4">
                <div class="col-md-6">
                    <div class="d-flex align-items-center">
                        <label for="pageSize" class="form-label me-2 mb-0">Show:</label>
                        <select class="form-select form-select-sm me-2" id="pageSize" style="width: auto;">
                            <option value="25">25</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                        </select>
                        <span class="text-muted">entries per page</span>
                    </div>
                </div>
                <div class="col-md-6 text-end">
                    <nav>
                        <ul class="pagination pagination-sm justify-content-end">
                            <li class="page-item disabled"><a class="page-link" href="#">Previous</a></li>
                            <li class="page-item active"><a class="page-link" href="#">1</a></li>
                            <li class="page-item"><a class="page-link" href="#">2</a></li>
                            <li class="page-item"><a class="page-link" href="#">3</a></li>
                            <li class="page-item"><a class="page-link" href="#">Next</a></li>
                        </ul>
                    </nav>
                </div>
            </div>
            
            <!-- Bulk Actions Footer -->
            <div class="row mt-3">
                <div class="col-md-8">
                    <div class="d-flex align-items-center">
                        <label for="bulkAction" class="form-label me-2 mb-0">Bulk Action:</label>
                        <select class="form-select form-select-sm me-2" id="bulkAction" style="width: auto;">
                            <option value="">Select Action</option>
                            <option value="send_communication">Send Communication</option>
                            <option value="update_status">Update Status</option>
                            <option value="assign_training">Assign Training</option>
                            <option value="export_data">Export Selected</option>
                        </select>
                        <button class="btn btn-sm btn-primary" onclick="executeBulkAction()">Execute</button>
                    </div>
                </div>
                <div class="col-md-4 text-end">
                    <small class="text-muted"><span id="selectedCount">0</span> staff selected</small>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Staff Modal -->
<div class="modal fade" id="addStaffModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New Staff Member</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="addStaffForm">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Full Name *</label>
                            <input type="text" class="form-control" name="name" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Email *</label>
                            <input type="email" class="form-control" name="email" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Phone Number</label>
                            <input type="tel" class="form-control" name="phone">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">School *</label>
                            <select class="form-select" name="school_id" required>
                                <option value="">Select School</option>
                                @foreach($schools as $school)
                                <option value="{{ $school->id }}">{{ $school->settings['school_name'] ?? 'Unknown School' }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Role *</label>
                            <select class="form-select" name="role" required>
                                <option value="">Select Role</option>
                                <option value="Teacher">Teacher</option>
                                <option value="Support Staff">Support Staff</option>
                                <option value="Administration">Administration</option>
                                <option value="Management">Management</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Department</label>
                            <select class="form-select" name="department">
                                <option value="">Select Department</option>
                                <option value="Academic">Academic</option>
                                <option value="Administration">Administration</option>
                                <option value="Support">Support</option>
                                <option value="Management">Management</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Hire Date</label>
                            <input type="date" class="form-control" name="hire_date">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Salary</label>
                            <input type="number" class="form-control" name="salary" step="1000">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Notes</label>
                        <textarea class="form-control" name="notes" rows="3"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="addStaff()">Add Staff Member</button>
            </div>
        </div>
    </div>
</div>

<!-- Bulk Actions Modal -->
<div class="modal fade" id="bulkActionsModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Bulk Actions</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Select Action</label>
                    <select class="form-select" id="modalBulkAction">
                        <option value="">Choose Action</option>
                        <option value="send_communication">Send Communication</option>
                        <option value="update_status">Update Status</option>
                        <option value="assign_training">Assign Training</option>
                        <option value="update_policy">Update HR Policy</option>
                        <option value="generate_report">Generate Report</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Message/Notes</label>
                    <textarea class="form-control" id="bulkActionMessage" rows="3" placeholder="Enter message or action details..."></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="executeModalBulkAction()">Execute Action</button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
let currentView = 'table';

// View switching
function setView(view) {
    currentView = view;
    if (view === 'table') {
        document.getElementById('tableView').style.display = 'block';
        document.getElementById('cardView').style.display = 'none';
    } else if (view === 'card') {
        document.getElementById('tableView').style.display = 'none';
        document.getElementById('cardView').style.display = 'block';
    }
}

// Filter functions
function applyFilters() {
    const search = document.getElementById('staffSearch').value.toLowerCase();
    const school = document.getElementById('schoolFilter').value;
    const role = document.getElementById('roleFilter').value;
    const status = document.getElementById('statusFilter').value;
    
    const rows = document.querySelectorAll('#staffTable tbody tr, #cardView .col-xl-3');
    
    rows.forEach(row => {
        let visible = true;
        
        if (search) {
            const text = row.textContent.toLowerCase();
            visible = visible && text.includes(search);
        }
        
        if (school) {
            visible = visible && row.dataset.school === school;
        }
        
        if (role) {
            visible = visible && row.dataset.role === role;
        }
        
        if (status) {
            visible = visible && row.dataset.status === status;
        }
        
        row.style.display = visible ? '' : 'none';
    });
}

function clearFilters() {
    document.getElementById('staffSearch').value = '';
    document.getElementById('schoolFilter').value = '';
    document.getElementById('roleFilter').value = '';
    document.getElementById('statusFilter').value = '';
    applyFilters();
}

// Staff actions
function viewStaffProfile(staffId) {
    alert(`Viewing profile for staff ID: ${staffId}`);
}

function editStaffProfile(staffId) {
    alert(`Editing profile for staff ID: ${staffId}`);
}

function sendMessage(staffId) {
    alert(`Sending message to staff ID: ${staffId}`);
}

function viewPayroll(staffId) {
    alert(`Viewing payroll for staff ID: ${staffId}`);
}

function viewLeave(staffId) {
    alert(`Viewing leave history for staff ID: ${staffId}`);
}

function deactivateStaff(staffId) {
    if (confirm('Are you sure you want to deactivate this staff member?')) {
        alert(`Deactivating staff ID: ${staffId}`);
    }
}

function addStaff() {
    const form = document.getElementById('addStaffForm');
    if (form.checkValidity()) {
        alert('Staff member added successfully!');
        bootstrap.Modal.getInstance(document.getElementById('addStaffModal')).hide();
        form.reset();
    } else {
        form.reportValidity();
    }
}

// Bulk actions
function executeBulkAction() {
    const action = document.getElementById('bulkAction').value;
    const selected = document.querySelectorAll('input[name="staff_ids"]:checked');
    
    if (!action) {
        alert('Please select an action');
        return;
    }
    
    if (selected.length === 0) {
        alert('Please select at least one staff member');
        return;
    }
    
    alert(`Executing ${action} for ${selected.length} staff members`);
}

function executeModalBulkAction() {
    const action = document.getElementById('modalBulkAction').value;
    const message = document.getElementById('bulkActionMessage').value;
    
    if (!action) {
        alert('Please select an action');
        return;
    }
    
    alert(`Executing ${action} with message: ${message}`);
    bootstrap.Modal.getInstance(document.getElementById('bulkActionsModal')).hide();
}

function exportStaffDirectory() {
    alert('Exporting staff directory...');
}

// Select all functionality
document.getElementById('selectAllStaff').addEventListener('change', function() {
    const checkboxes = document.querySelectorAll('input[name="staff_ids"]');
    checkboxes.forEach(checkbox => checkbox.checked = this.checked);
    updateSelectedCount();
});

document.getElementById('selectAllTableStaff').addEventListener('change', function() {
    const checkboxes = document.querySelectorAll('input[name="staff_ids"]');
    checkboxes.forEach(checkbox => checkbox.checked = this.checked);
    updateSelectedCount();
});

// Update selected count
function updateSelectedCount() {
    const selected = document.querySelectorAll('input[name="staff_ids"]:checked');
    document.getElementById('selectedCount').textContent = selected.length;
}

// Add event listeners to checkboxes
document.addEventListener('DOMContentLoaded', function() {
    const checkboxes = document.querySelectorAll('input[name="staff_ids"]');
    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', updateSelectedCount);
    });
});

// Search as you type
document.getElementById('staffSearch').addEventListener('input', function() {
    clearTimeout(this.searchTimeout);
    this.searchTimeout = setTimeout(applyFilters, 300);
});
</script>
@endpush

@push('styles')
<style>
.text-gray-800 {
    color: #5a5c69 !important;
}

.shadow {
    box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15) !important;
}

.staff-avatar {
    font-weight: bold;
}

.card-header {
    background-color: #f8f9fc;
    border-bottom: 1px solid #e3e6f0;
}

.font-weight-bold {
    font-weight: 700 !important;
}

.text-xs {
    font-size: 0.75rem;
}

.badge {
    font-size: 0.75rem;
}

.btn-group .btn {
    font-size: 0.75rem;
    padding: 0.25rem 0.5rem;
}

.table th {
    font-weight: 600;
    background-color: #f8f9fc;
    border-bottom: 2px solid #e3e6f0;
}

.card h-100 {
    transition: transform 0.2s;
}

.card:hover {
    transform: translateY(-2px);
}

@media (max-width: 768px) {
    .d-flex.gap-2 {
        flex-direction: column;
        gap: 0.5rem !important;
    }
    
    .col-xl-3, .col-lg-4, .col-md-6 {
        margin-bottom: 1rem;
    }
    
    .btn-group .btn {
        font-size: 0.625rem;
        padding: 0.125rem 0.25rem;
    }
    
    .table-responsive {
        font-size: 0.875rem;
    }
}
</style>
@endpush
