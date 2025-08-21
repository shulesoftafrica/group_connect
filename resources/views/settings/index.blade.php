@extends('layouts.settings')

@section('title', 'Settings & Control Panel')
@section('page-title', 'Dashboard')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2"><i class="bi bi-gear-fill me-2"></i>Settings & Control Panel</h1>
        <div class="btn-toolbar mb-2 mb-md-0">
            <div class="btn-group me-2">
                <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#systemStatusModal">
                    <i class="bi bi-activity"></i> System Status
                </button>
                <button type="button" class="btn btn-sm btn-outline-secondary" onclick="refreshDashboard()">
                    <i class="bi bi-arrow-clockwise"></i> Refresh
                </button>
            </div>
        </div>
    </div>

    <!-- System Overview Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Users</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($data['total_users']) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-people-fill fa-2x text-gray-300"></i>
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
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Connected Schools</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($data['total_schools']) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-building fa-2x text-gray-300"></i>
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
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Active Sessions</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($data['active_sessions']) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-activity fa-2x text-gray-300"></i>
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
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Pending Approvals</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($data['pending_approvals']) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-exclamation-triangle-fill fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Access Menu -->
    <div class="row mb-4">
        <div class="col-12">
            <h3 class="mb-3"><i class="bi bi-lightning-charge-fill me-2 text-primary"></i>Quick Access</h3>
            <div class="row">
                <!-- User Management -->
                <div class="col-lg-3 col-md-6 mb-3">
                    <div class="card h-100 shadow-sm hover-card">
                        <div class="card-body text-center">
                            <div class="mb-3">
                                <i class="bi bi-person-plus-fill fa-3x text-primary"></i>
                            </div>
                            <h5 class="card-title">User Management</h5>
                            <p class="card-text text-muted">Add, edit, or remove users and manage permissions</p>
                            <a href="{{ route('settings.users') }}" class="btn btn-primary btn-sm">
                                <i class="bi bi-arrow-right"></i> Manage Users
                            </a>
                        </div>
                    </div>
                </div>

                <!-- School Management -->
                <!-- <div class="col-lg-3 col-md-6 mb-3">
                    <div class="card h-100 shadow-sm hover-card">
                        <div class="card-body text-center">
                            <div class="mb-3">
                                <i class="bi bi-building-add fa-3x text-success"></i>
                            </div>
                            <h5 class="card-title">School Management</h5>
                            <p class="card-text text-muted">Link existing schools or request new school creation</p>
                            <a href="{{ route('settings.schools') }}" class="btn btn-success btn-sm">
                                <i class="bi bi-arrow-right"></i> Manage Schools
                            </a>
                        </div>
                    </div>
                </div> -->

                <!-- Academic Years -->
                <!-- <div class="col-lg-3 col-md-6 mb-3">
                    <div class="card h-100 shadow-sm hover-card">
                        <div class="card-body text-center">
                            <div class="mb-3">
                                <i class="bi bi-calendar-event fa-3x text-info"></i>
                            </div>
                            <h5 class="card-title">Academic Years</h5>
                            <p class="card-text text-muted">Set and update academic years across schools</p>
                            <a href="{{ route('settings.academic-years') }}" class="btn btn-info btn-sm">
                                <i class="bi bi-arrow-right"></i> Manage Years
                            </a>
                        </div>
                    </div>
                </div> -->

                <!-- Roles & Permissions -->
                <div class="col-lg-3 col-md-6 mb-3">
                    <div class="card h-100 shadow-sm hover-card">
                        <div class="card-body text-center">
                            <div class="mb-3">
                                <i class="bi bi-shield-lock-fill fa-3x text-warning"></i>
                            </div>
                            <h5 class="card-title">Roles & Permissions</h5>
                            <p class="card-text text-muted">Configure user roles and access permissions</p>
                            <a href="{{ route('settings.roles-permissions') }}" class="btn btn-warning btn-sm">
                                <i class="bi bi-arrow-right"></i> Manage Roles
                            </a>
                        </div>
                    </div>
                </div>

                <!-- System Configuration -->
                <!-- <div class="col-lg-3 col-md-6 mb-3">
                    <div class="card h-100 shadow-sm hover-card">
                        <div class="card-body text-center">
                            <div class="mb-3">
                                <i class="bi bi-gear-wide-connected fa-3x text-secondary"></i>
                            </div>
                            <h5 class="card-title">System Configuration</h5>
                            <p class="card-text text-muted">Configure branding, notifications, and integrations</p>
                            <a href="{{ route('settings.system-config') }}" class="btn btn-secondary btn-sm">
                                <i class="bi bi-arrow-right"></i> Configure
                            </a>
                        </div>
                    </div>
                </div> -->

                <!-- Bulk Operations -->
                <!-- <div class="col-lg-3 col-md-6 mb-3">
                    <div class="card h-100 shadow-sm hover-card">
                        <div class="card-body text-center">
                            <div class="mb-3">
                                <i class="bi bi-lightning-fill fa-3x text-danger"></i>
                            </div>
                            <h5 class="card-title">Bulk Operations</h5>
                            <p class="card-text text-muted">Send messages and update settings across schools</p>
                            <a href="{{ route('settings.bulk-operations') }}" class="btn btn-danger btn-sm">
                                <i class="bi bi-arrow-right"></i> Bulk Actions
                            </a>
                        </div>
                    </div>
                </div> -->

                <!-- Audit Logs -->
                <!-- <div class="col-lg-3 col-md-6 mb-3">
                    <div class="card h-100 shadow-sm hover-card">
                        <div class="card-body text-center">
                            <div class="mb-3">
                                <i class="bi bi-clipboard-data fa-3x text-dark"></i>
                            </div>
                            <h5 class="card-title">Audit Logs</h5>
                            <p class="card-text text-muted">View system activity and security logs</p>
                            <a href="{{ route('settings.audit-logs') }}" class="btn btn-dark btn-sm">
                                <i class="bi bi-arrow-right"></i> View Logs
                            </a>
                        </div>
                    </div>
                </div> -->

                <!-- Integration Settings -->
                <!-- <div class="col-lg-3 col-md-6 mb-3">
                    <div class="card h-100 shadow-sm hover-card">
                        <div class="card-body text-center">
                            <div class="mb-3">
                                <i class="bi bi-diagram-3-fill fa-3x text-primary"></i>
                            </div>
                            <h5 class="card-title">Integration Settings</h5>
                            <p class="card-text text-muted">Manage API integrations and external connections</p>
                            <a href="{{ url('settings.integrations') }}" class="btn btn-primary btn-sm">
                                <i class="bi bi-arrow-right"></i> Manage APIs
                            </a>
                        </div>
                    </div>
                </div> -->
            </div>
        </div>
    </div>

    <!-- Recent Activity -->
    
</div>

<!-- System Status Modal -->
<div class="modal fade" id="systemStatusModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="bi bi-activity me-2"></i>System Status Details
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    @foreach($data['system_status'] as $service => $status)
                    <div class="col-md-6 mb-3">
                        <div class="card">
                            <div class="card-body">
                                <h6 class="card-title">{{ str_replace('_', ' ', ucwords($service)) }}</h6>
                                <div class="d-flex align-items-center">
                                    <span class="badge bg-{{ $status === 'healthy' ? 'success' : 'danger' }} me-2">
                                        {{ ucfirst($status) }}
                                    </span>
                                    <small class="text-muted">Last checked: {{ now()->format('H:i:s') }}</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
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

.hover-card {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}
.hover-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
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
    background: #4e73df;
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
</style>

<script>
function refreshDashboard() {
    location.reload();
}

function downloadBackup() {
    // Implementation for backup download
    alert('Backup download will be implemented based on your backup system');
}
</script>
@endsection
