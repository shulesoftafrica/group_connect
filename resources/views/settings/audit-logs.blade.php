@extends('layouts.admin')

@section('title', 'Audit Logs')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">
            <i class="bi bi-clipboard-data me-2"></i>System Audit Logs
        </h1>
        <div class="btn-toolbar mb-2 mb-md-0">
            <div class="btn-group me-2">
                <button type="button" class="btn btn-outline-primary" onclick="exportLogs()">
                    <i class="bi bi-download"></i> Export Logs
                </button>
                <button type="button" class="btn btn-outline-secondary" onclick="clearOldLogs()">
                    <i class="bi bi-trash"></i> Clear Old
                </button>
            </div>
        </div>
    </div>

    <!-- Security Notice -->
    <div class="alert alert-info" role="alert">
        <i class="bi bi-shield-check me-2"></i>
        <strong>Security Notice:</strong> All system activities are logged for security and compliance purposes. 
        Logs are retained for 12 months and automatically archived thereafter.
    </div>

    <!-- Filter Controls -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="bi bi-funnel-fill me-2"></i>Filter & Search Logs
                    </h6>
                </div>
                <div class="card-body">
                    <form id="filterForm" class="row g-3">
                        <div class="col-md-3">
                            <label for="date_from" class="form-label">From Date</label>
                            <input type="date" class="form-control" id="date_from" name="date_from" 
                                   value="{{ now()->subDays(7)->format('Y-m-d') }}">
                        </div>
                        <div class="col-md-3">
                            <label for="date_to" class="form-label">To Date</label>
                            <input type="date" class="form-control" id="date_to" name="date_to" 
                                   value="{{ now()->format('Y-m-d') }}">
                        </div>
                        <div class="col-md-2">
                            <label for="action_filter" class="form-label">Action Type</label>
                            <select class="form-select" id="action_filter" name="action_type">
                                <option value="">All Actions</option>
                                <option value="user_login">User Login</option>
                                <option value="user_logout">User Logout</option>
                                <option value="user_created">User Created</option>
                                <option value="user_updated">User Updated</option>
                                <option value="user_deleted">User Deleted</option>
                                <option value="role_created">Role Created</option>
                                <option value="role_updated">Role Updated</option>
                                <option value="permission_changed">Permission Changed</option>
                                <option value="school_linked">School Linked</option>
                                <option value="school_unlinked">School Unlinked</option>
                                <option value="settings_updated">Settings Updated</option>
                                <option value="bulk_operation">Bulk Operation</option>
                                <option value="policy_distributed">Policy Distributed</option>
                                <option value="system_backup">System Backup</option>
                                <option value="security_alert">Security Alert</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label for="user_filter" class="form-label">User</label>
                            <input type="text" class="form-control" id="user_filter" name="user" 
                                   placeholder="Username or email">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">&nbsp;</label>
                            <div class="d-grid">
                                <button type="button" class="btn btn-primary" onclick="applyFilters()">
                                    <i class="bi bi-search"></i> Search
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Logs Today</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">1,247</div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-journal-text fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Successful Actions</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">1,203</div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-check-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Security Alerts</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">12</div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-shield-exclamation fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card border-left-danger shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Failed Actions</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">32</div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-x-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Audit Logs Table -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="bi bi-list-ul me-2"></i>Audit Trail
                    </h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover" id="auditLogsTable">
                            <thead class="table-light">
                                <tr>
                                    <th>Timestamp</th>
                                    <th>User</th>
                                    <th>Action</th>
                                    <th>Resource</th>
                                    <th>Details</th>
                                    <th>IP Address</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($logs as $log)
                                <tr class="log-row" data-severity="{{ $log->severity ?? 'info' }}">
                                    <td>
                                        <div class="timestamp">
                                            <span class="fw-bold">{{ \Carbon\Carbon::parse($log->created_at)->format('M d, Y') }}</span>
                                            <small class="text-muted d-block">{{ \Carbon\Carbon::parse($log->created_at)->format('H:i:s') }}</small>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="user-info">
                                            <div class="fw-bold">{{ $log->user_name ?? 'System' }}</div>
                                            <small class="text-muted">{{ $log->user_email ?? 'system@shulesoft.com' }}</small>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="action-badge">
                                            <span class="badge bg-{{ $this->getActionBadgeColor($log->action) }}">
                                                {{ $log->action }}
                                            </span>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="resource-name">{{ $log->resource_type ?? 'System' }}</span>
                                        @if($log->resource_id)
                                        <small class="text-muted d-block">ID: {{ $log->resource_id }}</small>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="details-preview">
                                            {{ Str::limit($log->details ?? $log->description ?? 'No details', 50) }}
                                            @if(strlen($log->details ?? $log->description ?? '') > 50)
                                            <button class="btn btn-sm btn-link p-0" onclick="showFullDetails({{ $log->id }})">
                                                ...more
                                            </button>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        <span class="ip-address">{{ $log->ip_address ?? 'N/A' }}</span>
                                        @if($log->user_agent)
                                        <small class="text-muted d-block" title="{{ $log->user_agent }}">
                                            {{ $this->getBrowserInfo($log->user_agent) }}
                                        </small>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $log->status === 'success' ? 'success' : ($log->status === 'failed' ? 'danger' : 'warning') }}">
                                            {{ ucfirst($log->status ?? 'unknown') }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <button type="button" class="btn btn-sm btn-outline-primary" 
                                                    onclick="viewLogDetails({{ $log->id }})" data-bs-toggle="tooltip" title="View Details">
                                                <i class="bi bi-eye"></i>
                                            </button>
                                            @if($log->related_logs_count > 0)
                                            <button type="button" class="btn btn-sm btn-outline-info" 
                                                    onclick="viewRelatedLogs({{ $log->id }})" data-bs-toggle="tooltip" title="Related Logs">
                                                <i class="bi bi-link"></i>
                                            </button>
                                            @endif
                                            @if($log->severity === 'high' || $log->status === 'failed')
                                            <button type="button" class="btn btn-sm btn-outline-warning" 
                                                    onclick="flagForReview({{ $log->id }})" data-bs-toggle="tooltip" title="Flag for Review">
                                                <i class="bi bi-flag"></i>
                                            </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center py-4">
                                        <div class="text-muted">
                                            <i class="bi bi-inbox fa-3x mb-3 d-block"></i>
                                            No audit logs found for the selected criteria
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <div class="text-muted">
                            Showing 1-50 of 1,247 logs
                        </div>
                        <nav>
                            <ul class="pagination pagination-sm mb-0">
                                <li class="page-item disabled">
                                    <span class="page-link">Previous</span>
                                </li>
                                <li class="page-item active">
                                    <span class="page-link">1</span>
                                </li>
                                <li class="page-item">
                                    <a class="page-link" href="#">2</a>
                                </li>
                                <li class="page-item">
                                    <a class="page-link" href="#">3</a>
                                </li>
                                <li class="page-item">
                                    <a class="page-link" href="#">Next</a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Log Details Modal -->
<div class="modal fade" id="logDetailsModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="bi bi-info-circle me-2"></i>Audit Log Details
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="logDetailsBody">
                <!-- Content will be loaded dynamically -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="exportSingleLog()">
                    <i class="bi bi-download me-2"></i>Export Log
                </button>
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
.border-left-warning {
    border-left: 0.25rem solid #f6c23e !important;
}
.border-left-danger {
    border-left: 0.25rem solid #e74a3b !important;
}

.log-row[data-severity="high"] {
    background-color: rgba(220, 53, 69, 0.05);
}

.log-row[data-severity="medium"] {
    background-color: rgba(255, 193, 7, 0.05);
}

.timestamp {
    min-width: 120px;
}

.user-info {
    min-width: 150px;
}

.action-badge .badge {
    font-size: 0.75em;
}

.details-preview {
    max-width: 200px;
}

.ip-address {
    font-family: monospace;
    font-size: 0.9em;
}

.table th {
    font-weight: 600;
    color: #5a5c69;
    border-top: none;
    white-space: nowrap;
}

.btn-group .btn {
    margin-right: 2px;
}
</style>

<script>
function applyFilters() {
    const formData = new FormData(document.getElementById('filterForm'));
    const params = new URLSearchParams(formData);
    window.location.search = params.toString();
}

function viewLogDetails(logId) {
    fetch(`/settings/audit-logs/${logId}`)
        .then(response => response.json())
        .then(data => {
            document.getElementById('logDetailsBody').innerHTML = data.html;
            new bootstrap.Modal(document.getElementById('logDetailsModal')).show();
        });
}

function viewRelatedLogs(logId) {
    window.location.href = `/settings/audit-logs?related=${logId}`;
}

function flagForReview(logId) {
    if (confirm('Flag this log entry for security review?')) {
        fetch(`/settings/audit-logs/${logId}/flag`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
            },
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Log flagged for review successfully.');
            } else {
                alert('Error flagging log: ' + data.message);
            }
        });
    }
}

function showFullDetails(logId) {
    viewLogDetails(logId);
}

function exportLogs() {
    const formData = new FormData(document.getElementById('filterForm'));
    const params = new URLSearchParams(formData);
    window.location.href = `/settings/audit-logs/export?${params.toString()}`;
}

function exportSingleLog() {
    // Implementation for exporting single log
    alert('Single log export functionality will be implemented');
}

function clearOldLogs() {
    if (confirm('This will permanently delete audit logs older than 12 months. Continue?')) {
        fetch('/settings/audit-logs/clear-old', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
            },
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(`${data.deleted_count} old log entries deleted successfully.`);
                location.reload();
            } else {
                alert('Error clearing logs: ' + data.message);
            }
        });
    }
}

// Auto-refresh logs every 30 seconds
setInterval(() => {
    const currentUrl = new URL(window.location);
    if (!currentUrl.searchParams.has('refresh')) {
        currentUrl.searchParams.set('refresh', '1');
        fetch(currentUrl.toString())
            .then(response => response.text())
            .then(html => {
                // Update only the table body to avoid disrupting user interaction
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');
                const newTbody = doc.querySelector('#auditLogsTable tbody');
                if (newTbody) {
                    document.querySelector('#auditLogsTable tbody').innerHTML = newTbody.innerHTML;
                }
            });
    }
}, 30000);

// Initialize tooltips
document.addEventListener('DOMContentLoaded', function() {
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});

// Helper functions for controller (these would normally be in the controller)
function getActionBadgeColor(action) {
    const colorMap = {
        'user_login': 'success',
        'user_logout': 'secondary',
        'user_created': 'primary',
        'user_updated': 'info',
        'user_deleted': 'danger',
        'role_created': 'primary',
        'role_updated': 'warning',
        'permission_changed': 'warning',
        'school_linked': 'success',
        'school_unlinked': 'danger',
        'settings_updated': 'info',
        'bulk_operation': 'primary',
        'policy_distributed': 'success',
        'system_backup': 'secondary',
        'security_alert': 'danger'
    };
    return colorMap[action] || 'secondary';
}

function getBrowserInfo(userAgent) {
    if (userAgent.includes('Chrome')) return 'Chrome';
    if (userAgent.includes('Firefox')) return 'Firefox';
    if (userAgent.includes('Safari')) return 'Safari';
    if (userAgent.includes('Edge')) return 'Edge';
    return 'Unknown';
}
</script>
@endsection
