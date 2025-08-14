@extends('layouts.admin')

@section('title', 'Roles & Permissions')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">
            <i class="bi bi-shield-lock-fill me-2"></i>Roles & Permissions Management
        </h1>
        <div class="btn-toolbar mb-2 mb-md-0">
            <div class="btn-group me-2">
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addRoleModal">
                    <i class="bi bi-plus-circle"></i> Add Role
                </button>
                <button type="button" class="btn btn-outline-secondary" onclick="exportRoles()">
                    <i class="bi bi-download"></i> Export
                </button>
            </div>
        </div>
    </div>

    <!-- Security Notice -->
    <div class="alert alert-warning" role="alert">
        <i class="bi bi-exclamation-triangle-fill me-2"></i>
        <strong>Security Notice:</strong> Only Central Super Admin can manage roles and permissions. 
        Changes to permissions take effect immediately and may impact user access across all schools.
    </div>

    <!-- Roles and Permissions Tabs -->
    <ul class="nav nav-tabs" id="rolesPermissionsTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="roles-tab" data-bs-toggle="tab" data-bs-target="#roles" 
                    type="button" role="tab">
                <i class="bi bi-people-fill me-2"></i>Roles Management
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="permissions-tab" data-bs-toggle="tab" data-bs-target="#permissions" 
                    type="button" role="tab">
                <i class="bi bi-key-fill me-2"></i>Permissions Matrix
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="audit-tab" data-bs-toggle="tab" data-bs-target="#audit" 
                    type="button" role="tab">
                <i class="bi bi-clipboard-data me-2"></i>Access Audit
            </button>
        </li>
    </ul>

    <div class="tab-content" id="rolesPermissionsTabsContent">
        <!-- Roles Management Tab -->
        <div class="tab-pane fade show active" id="roles" role="tabpanel">
            <div class="row mt-4">
                @forelse($roles as $role)
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="card role-card h-100 shadow-sm">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h6 class="mb-0 fw-bold text-primary">
                                <i class="bi bi-person-badge me-2"></i>{{ $role->name }}
                            </h6>
                            <div class="dropdown">
                                <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" 
                                        data-bs-toggle="dropdown">
                                    <i class="bi bi-three-dots-vertical"></i>
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="#" onclick="editRole({{ $role->id }})">
                                        <i class="bi bi-pencil me-2"></i>Edit Role
                                    </a></li>
                                    <li><a class="dropdown-item" href="#" onclick="managePermissions({{ $role->id }})">
                                        <i class="bi bi-key me-2"></i>Manage Permissions
                                    </a></li>
                                    <li><a class="dropdown-item" href="#" onclick="viewUsers({{ $role->id }})">
                                        <i class="bi bi-people me-2"></i>View Users
                                    </a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item text-danger" href="#" onclick="deleteRole({{ $role->id }})">
                                        <i class="bi bi-trash me-2"></i>Delete Role
                                    </a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="card-body">
                            <p class="card-text text-muted">{{ $role->description ?? 'No description provided' }}</p>
                            
                            <div class="row text-center">
                                <div class="col-6">
                                    <div class="mb-2">
                                        <i class="bi bi-people-fill text-primary fa-2x"></i>
                                    </div>
                                    <h5 class="mb-0">{{ $role->user_count ?? 0 }}</h5>
                                    <small class="text-muted">Users</small>
                                </div>
                                <div class="col-6">
                                    <div class="mb-2">
                                        <i class="bi bi-key-fill text-success fa-2x"></i>
                                    </div>
                                    <h5 class="mb-0">{{ $rolePermissions->where('role_id', $role->id)->count() }}</h5>
                                    <small class="text-muted">Permissions</small>
                                </div>
                            </div>

                            <hr>

                            <!-- Recent Permissions -->
                            <div class="mb-2">
                                <small class="text-muted fw-bold">Key Permissions:</small>
                            </div>
                            <div class="permission-tags">
                                @foreach($rolePermissions->where('role_id', $role->id)->take(3) as $rp)
                                <span class="badge bg-secondary me-1 mb-1">{{ $rp->permission_name }}</span>
                                @endforeach
                                @if($rolePermissions->where('role_id', $role->id)->count() > 3)
                                <span class="badge bg-light text-dark">+{{ $rolePermissions->where('role_id', $role->id)->count() - 3 }} more</span>
                                @endif
                            </div>
                        </div>
                        <div class="card-footer">
                            <div class="d-flex justify-content-between align-items-center">
                                <small class="text-muted">
                                    <i class="bi bi-clock me-1"></i>{{ $role->created_at ? \Carbon\Carbon::parse($role->created_at)->diffForHumans() : 'N/A' }}
                                </small>
                                <div class="btn-group btn-group-sm">
                                    <button class="btn btn-outline-primary" onclick="managePermissions({{ $role->id }})">
                                        <i class="bi bi-key"></i>
                                    </button>
                                    <button class="btn btn-outline-secondary" onclick="editRole({{ $role->id }})">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-12">
                    <div class="card">
                        <div class="card-body text-center py-5">
                            <i class="bi bi-person-badge fa-4x text-muted mb-3"></i>
                            <h4 class="text-muted">No Roles Defined</h4>
                            <p class="text-muted mb-4">Create your first role to start managing user permissions.</p>
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addRoleModal">
                                <i class="bi bi-plus-circle me-2"></i>Create First Role
                            </button>
                        </div>
                    </div>
                </div>
                @endforelse
            </div>
        </div>

        <!-- Permissions Matrix Tab -->
        <div class="tab-pane fade" id="permissions" role="tabpanel">
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="bi bi-grid-3x3-gap me-2"></i>Permissions Matrix
                    </h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-sm">
                            <thead class="table-dark">
                                <tr>
                                    <th>Permission</th>
                                    @foreach($roles as $role)
                                    <th class="text-center">{{ $role->name }}</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($permissions->groupBy('module') as $module => $modulePermissions)
                                <tr class="table-secondary">
                                    <td colspan="{{ count($roles) + 1 }}" class="fw-bold">
                                        <i class="bi bi-folder-fill me-2"></i>{{ ucfirst($module) }} Module
                                    </td>
                                </tr>
                                @foreach($modulePermissions as $permission)
                                <tr>
                                    <td>
                                        <span class="ms-3">{{ $permission->name }}</span>
                                        <br><small class="text-muted ms-3">{{ $permission->description ?? '' }}</small>
                                    </td>
                                    @foreach($roles as $role)
                                    <td class="text-center">
                                        @php
                                            $hasPermission = $rolePermissions->where('role_id', $role->id)
                                                                           ->where('permission_id', $permission->id)
                                                                           ->isNotEmpty();
                                        @endphp
                                        <div class="form-check d-flex justify-content-center">
                                            <input class="form-check-input permission-checkbox" type="checkbox" 
                                                   data-role="{{ $role->id }}" data-permission="{{ $permission->id }}"
                                                   {{ $hasPermission ? 'checked' : '' }}
                                                   onchange="togglePermission({{ $role->id }}, {{ $permission->id }}, this.checked)">
                                        </div>
                                    </td>
                                    @endforeach
                                </tr>
                                @endforeach
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Access Audit Tab -->
        <div class="tab-pane fade" id="audit" role="tabpanel">
            <div class="row mt-4">
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-header">
                            <h6 class="mb-0">
                                <i class="bi bi-clock-history me-2"></i>Recent Permission Changes
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="timeline">
                                <!-- Sample audit logs - replace with actual data -->
                                <div class="timeline-item">
                                    <div class="timeline-marker bg-primary"></div>
                                    <div class="timeline-content">
                                        <h6 class="timeline-title">Role "Group Accountant" created</h6>
                                        <p class="timeline-info">
                                            <i class="bi bi-person-fill me-1"></i>Super Admin
                                            <span class="ms-3"><i class="bi bi-clock me-1"></i>2 hours ago</span>
                                        </p>
                                    </div>
                                </div>
                                <div class="timeline-item">
                                    <div class="timeline-marker bg-success"></div>
                                    <div class="timeline-content">
                                        <h6 class="timeline-title">Permission "finance.view" granted to Group Accountant</h6>
                                        <p class="timeline-info">
                                            <i class="bi bi-person-fill me-1"></i>Super Admin
                                            <span class="ms-3"><i class="bi bi-clock me-1"></i>3 hours ago</span>
                                        </p>
                                    </div>
                                </div>
                                <div class="timeline-item">
                                    <div class="timeline-marker bg-warning"></div>
                                    <div class="timeline-content">
                                        <h6 class="timeline-title">User "john@school.com" role changed to IT Officer</h6>
                                        <p class="timeline-info">
                                            <i class="bi bi-person-fill me-1"></i>Super Admin
                                            <span class="ms-3"><i class="bi bi-clock me-1"></i>1 day ago</span>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="card">
                        <div class="card-header">
                            <h6 class="mb-0">
                                <i class="bi bi-shield-check me-2"></i>Security Summary
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <div class="d-flex justify-content-between">
                                    <span>Total Roles</span>
                                    <span class="fw-bold">{{ count($roles) }}</span>
                                </div>
                            </div>
                            <div class="mb-3">
                                <div class="d-flex justify-content-between">
                                    <span>Total Permissions</span>
                                    <span class="fw-bold">{{ count($permissions) }}</span>
                                </div>
                            </div>
                            <div class="mb-3">
                                <div class="d-flex justify-content-between">
                                    <span>Active Users</span>
                                    <span class="fw-bold">{{ $roles->sum('user_count') }}</span>
                                </div>
                            </div>
                            <hr>
                            <div class="mb-3">
                                <h6 class="text-muted">Most Privileged Roles</h6>
                                @foreach($roles->sortByDesc(function($role) use ($rolePermissions) { return $rolePermissions->where('role_id', $role->id)->count(); })->take(3) as $role)
                                <div class="d-flex justify-content-between">
                                    <span>{{ $role->name }}</span>
                                    <span class="badge bg-primary">{{ $rolePermissions->where('role_id', $role->id)->count() }}</span>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Role Modal -->
<div class="modal fade" id="addRoleModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="{{ route('settings.roles.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="bi bi-plus-circle me-2"></i>Create New Role
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="role_name" class="form-label">Role Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="role_name" name="name" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="role_level" class="form-label">Role Level</label>
                                <select class="form-select" id="role_level" name="level">
                                    <option value="low">Low Access</option>
                                    <option value="medium">Medium Access</option>
                                    <option value="high">High Access</option>
                                    <option value="admin">Admin Access</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="role_description" class="form-label">Description <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="role_description" name="description" rows="3" 
                                  placeholder="Describe the purpose and scope of this role..." required></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Assign Permissions</label>
                        <div class="permission-groups" style="max-height: 300px; overflow-y: auto;">
                            @foreach($permissions->groupBy('module') as $module => $modulePermissions)
                            <div class="card mb-2">
                                <div class="card-header py-2">
                                    <div class="form-check">
                                        <input class="form-check-input module-checkbox" type="checkbox" 
                                               id="module_{{ $module }}" data-module="{{ $module }}">
                                        <label class="form-check-label fw-bold" for="module_{{ $module }}">
                                            {{ ucfirst($module) }} Module
                                        </label>
                                    </div>
                                </div>
                                <div class="card-body py-2">
                                    @foreach($modulePermissions as $permission)
                                    <div class="form-check">
                                        <input class="form-check-input permission-item" type="checkbox" 
                                               name="permissions[]" value="{{ $permission->id }}" 
                                               id="perm_{{ $permission->id }}" data-module="{{ $module }}">
                                        <label class="form-check-label" for="perm_{{ $permission->id }}">
                                            {{ $permission->name }}
                                            @if($permission->description)
                                            <small class="text-muted d-block">{{ $permission->description }}</small>
                                            @endif
                                        </label>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-plus-circle me-2"></i>Create Role
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
.role-card {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}
.role-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
}

.permission-tags .badge {
    font-size: 0.7rem;
}

.timeline {
    position: relative;
    padding-left: 2rem;
}
.timeline-item {
    position: relative;
    margin-bottom: 1.5rem;
}
.timeline-marker {
    position: absolute;
    left: -2rem;
    top: 0.5rem;
    width: 12px;
    height: 12px;
    border-radius: 50%;
}
.timeline-marker::before {
    content: '';
    position: absolute;
    left: 50%;
    top: 12px;
    width: 2px;
    height: 2rem;
    background: #e3e6f0;
    transform: translateX(-50%);
}
.timeline-item:last-child .timeline-marker::before {
    display: none;
}
.timeline-content {
    padding-left: 1rem;
}
.timeline-title {
    margin-bottom: 0.25rem;
    font-size: 0.9rem;
    font-weight: 600;
}
.timeline-info {
    margin-bottom: 0;
    font-size: 0.8rem;
    color: #6c757d;
}

.permission-checkbox {
    transform: scale(1.2);
}
</style>

<script>
// Module checkbox functionality
document.addEventListener('DOMContentLoaded', function() {
    const moduleCheckboxes = document.querySelectorAll('.module-checkbox');
    
    moduleCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const module = this.dataset.module;
            const permissionItems = document.querySelectorAll(`[data-module="${module}"].permission-item`);
            
            permissionItems.forEach(item => {
                item.checked = this.checked;
            });
        });
    });

    // Update module checkbox when individual permissions change
    const permissionItems = document.querySelectorAll('.permission-item');
    permissionItems.forEach(item => {
        item.addEventListener('change', function() {
            const module = this.dataset.module;
            const moduleCheckbox = document.querySelector(`[data-module="${module}"].module-checkbox`);
            const modulePermissions = document.querySelectorAll(`[data-module="${module}"].permission-item`);
            const checkedPermissions = document.querySelectorAll(`[data-module="${module}"].permission-item:checked`);
            
            if (checkedPermissions.length === modulePermissions.length) {
                moduleCheckbox.checked = true;
                moduleCheckbox.indeterminate = false;
            } else if (checkedPermissions.length > 0) {
                moduleCheckbox.checked = false;
                moduleCheckbox.indeterminate = true;
            } else {
                moduleCheckbox.checked = false;
                moduleCheckbox.indeterminate = false;
            }
        });
    });
});

function togglePermission(roleId, permissionId, granted) {
    fetch('/settings/roles/toggle-permission', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            role_id: roleId,
            permission_id: permissionId,
            granted: granted
        })
    })
    .then(response => response.json())
    .then(data => {
        if (!data.success) {
            alert('Error updating permission: ' + data.message);
            // Revert checkbox state
            document.querySelector(`[data-role="${roleId}"][data-permission="${permissionId}"]`).checked = !granted;
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error updating permission');
        // Revert checkbox state
        document.querySelector(`[data-role="${roleId}"][data-permission="${permissionId}"]`).checked = !granted;
    });
}

function editRole(roleId) {
    // Implement edit role functionality
    alert('Edit role functionality will be implemented');
}

function managePermissions(roleId) {
    // Switch to permissions tab and highlight the role
    document.getElementById('permissions-tab').click();
    // You could add highlighting logic here
}

function viewUsers(roleId) {
    // Redirect to users page with role filter
    window.location.href = `/settings/users?role=${roleId}`;
}

function deleteRole(roleId) {
    if (confirm('Are you sure you want to delete this role? Users with this role will lose their permissions.')) {
        fetch(`/settings/roles/${roleId}`, {
            method: 'DELETE',
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
                alert('Error deleting role: ' + data.message);
            }
        });
    }
}

function exportRoles() {
    window.location.href = '/settings/roles/export';
}
</script>
@endsection
