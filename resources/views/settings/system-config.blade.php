@extends('layouts.settings')

@section('title', 'System Configuration')
@section('page-title', 'System Config')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">
            <i class="bi bi-gear-wide-connected me-2"></i>System Configuration
        </h1>
        <!-- <div class="btn-toolbar mb-2 mb-md-0">
            <div class="btn-group me-2">
                <button type="button" class="btn btn-outline-primary" onclick="previewChanges()">
                    <i class="bi bi-eye"></i> Preview
                </button>
                <button type="button" class="btn btn-outline-secondary" onclick="resetToDefaults()">
                    <i class="bi bi-arrow-clockwise"></i> Reset
                </button>
            </div>
        </div> -->
    </div>

    <form action="{{ route('settings.system-config.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <!-- Main Configuration -->
            <div class="col-lg-8">
                <!-- General Settings -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="bi bi-gear-fill me-2"></i>General Settings
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="group_name" class="form-label">Group Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="group_name" name="group_name" 
                                           value="{{ $config->group_name ?? 'ShuleSoft Group' }}" required>
                                    <div class="form-text">This name will appear in headers and reports</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="group_code" class="form-label">Group Code</label>
                                    <input type="text" class="form-control" id="group_code" name="group_code" 
                                           value="{{ $config->group_code ?? 'SSG001' }}" placeholder="SSG001">
                                    <div class="form-text">Unique identifier for your group</div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="timezone" class="form-label">Timezone</label>
                                    <select class="form-select" id="timezone" name="timezone">
                                        <option value="UTC" {{ ($config->timezone ?? 'UTC') === 'UTC' ? 'selected' : '' }}>UTC</option>
                                        <option value="America/New_York" {{ ($config->timezone ?? '') === 'America/New_York' ? 'selected' : '' }}>Eastern Time</option>
                                        <option value="America/Chicago" {{ ($config->timezone ?? '') === 'America/Chicago' ? 'selected' : '' }}>Central Time</option>
                                        <option value="America/Denver" {{ ($config->timezone ?? '') === 'America/Denver' ? 'selected' : '' }}>Mountain Time</option>
                                        <option value="America/Los_Angeles" {{ ($config->timezone ?? '') === 'America/Los_Angeles' ? 'selected' : '' }}>Pacific Time</option>
                                        <option value="Europe/London" {{ ($config->timezone ?? '') === 'Europe/London' ? 'selected' : '' }}>London</option>
                                        <option value="Africa/Nairobi" {{ ($config->timezone ?? '') === 'Africa/Nairobi' ? 'selected' : '' }}>East Africa Time</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="currency" class="form-label">Default Currency</label>
                                    <select class="form-select" id="currency" name="currency">
                                        <option value="USD" {{ ($config->currency ?? 'USD') === 'USD' ? 'selected' : '' }}>US Dollar (USD)</option>
                                        <option value="EUR" {{ ($config->currency ?? '') === 'EUR' ? 'selected' : '' }}>Euro (EUR)</option>
                                        <option value="GBP" {{ ($config->currency ?? '') === 'GBP' ? 'selected' : '' }}>British Pound (GBP)</option>
                                        <option value="KES" {{ ($config->currency ?? '') === 'KES' ? 'selected' : '' }}>Kenyan Shilling (KES)</option>
                                        <option value="NGN" {{ ($config->currency ?? '') === 'NGN' ? 'selected' : '' }}>Nigerian Naira (NGN)</option>
                                        <option value="ZAR" {{ ($config->currency ?? '') === 'ZAR' ? 'selected' : '' }}>South African Rand (ZAR)</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Branding & Appearance -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="bi bi-palette-fill me-2"></i>Branding & Appearance
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="logo" class="form-label">Group Logo</label>
                                    <input type="file" class="form-control" id="logo" name="logo" accept="image/*">
                                    <div class="form-text">Recommended size: 200x60px, PNG or JPG</div>
                                    @if($config->logo_url ?? false)
                                    <div class="mt-2">
                                        <img src="{{ $config->logo_url }}" alt="Current Logo" class="img-thumbnail" style="height: 50px;">
                                        <small class="text-muted d-block">Current logo</small>
                                    </div>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="favicon" class="form-label">Favicon</label>
                                    <input type="file" class="form-control" id="favicon" name="favicon" accept="image/*">
                                    <div class="form-text">16x16px ICO or PNG file</div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="primary_color" class="form-label">Primary Color</label>
                                    <div class="input-group">
                                        <input type="color" class="form-control form-control-color" id="primary_color" 
                                               name="primary_color" value="{{ $config->primary_color ?? '#0d6efd' }}">
                                        <input type="text" class="form-control" value="{{ $config->primary_color ?? '#0d6efd' }}" 
                                               id="primary_color_text" onchange="updateColorPicker('primary')">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="secondary_color" class="form-label">Secondary Color</label>
                                    <div class="input-group">
                                        <input type="color" class="form-control form-control-color" id="secondary_color" 
                                               name="secondary_color" value="{{ $config->secondary_color ?? '#6c757d' }}">
                                        <input type="text" class="form-control" value="{{ $config->secondary_color ?? '#6c757d' }}" 
                                               id="secondary_color_text" onchange="updateColorPicker('secondary')">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Color Preview -->
                        <div class="alert alert-info">
                            <div class="d-flex align-items-center">
                                <div class="me-3">
                                    <div class="color-preview-primary" style="width: 30px; height: 30px; border-radius: 5px; background: {{ $config->primary_color ?? '#0d6efd' }};"></div>
                                </div>
                                <div class="me-3">
                                    <div class="color-preview-secondary" style="width: 30px; height: 30px; border-radius: 5px; background: {{ $config->secondary_color ?? '#6c757d' }};"></div>
                                </div>
                                <div>
                                    <strong>Color Preview:</strong> These colors will be applied to buttons, headers, and navigation elements.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Notification Settings -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="bi bi-bell-fill me-2"></i>Notification Settings
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="notification_email" class="form-label">System Notification Email <span class="text-danger">*</span></label>
                                    <input type="email" class="form-control" id="notification_email" name="notification_email" 
                                           value="{{ $config->notification_email ?? '' }}" required>
                                    <div class="form-text">Receives system alerts and error notifications</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="support_email" class="form-label">Support Email</label>
                                    <input type="email" class="form-control" id="support_email" name="support_email" 
                                           value="{{ $config->support_email ?? '' }}">
                                    <div class="form-text">Displayed to users for support requests</div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="email_provider" class="form-label">Email Provider</label>
                                    <select class="form-select" id="email_provider" name="email_provider">
                                        <option value="smtp" {{ ($config->email_provider ?? 'smtp') === 'smtp' ? 'selected' : '' }}>SMTP</option>
                                        <option value="sendgrid" {{ ($config->email_provider ?? '') === 'sendgrid' ? 'selected' : '' }}>SendGrid</option>
                                        <option value="mailgun" {{ ($config->email_provider ?? '') === 'mailgun' ? 'selected' : '' }}>Mailgun</option>
                                        <option value="ses" {{ ($config->email_provider ?? '') === 'ses' ? 'selected' : '' }}>Amazon SES</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="sms_provider" class="form-label">SMS Provider</label>
                                    <select class="form-select" id="sms_provider" name="sms_provider">
                                        <option value="" {{ ($config->sms_provider ?? '') === '' ? 'selected' : '' }}>None</option>
                                        <option value="twilio" {{ ($config->sms_provider ?? '') === 'twilio' ? 'selected' : '' }}>Twilio</option>
                                        <option value="africastalking" {{ ($config->sms_provider ?? '') === 'africastalking' ? 'selected' : '' }}>Africa's Talking</option>
                                        <option value="clickatell" {{ ($config->sms_provider ?? '') === 'clickatell' ? 'selected' : '' }}>Clickatell</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Security Settings -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="bi bi-shield-lock-fill me-2"></i>Security Settings
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="session_timeout" class="form-label">Session Timeout (minutes)</label>
                                    <input type="number" class="form-control" id="session_timeout" name="session_timeout" 
                                           value="{{ $config->session_timeout ?? 60 }}" min="15" max="480">
                                    <div class="form-text">Auto-logout after inactivity</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="password_expiry" class="form-label">Password Expiry (days)</label>
                                    <input type="number" class="form-control" id="password_expiry" name="password_expiry" 
                                           value="{{ $config->password_expiry ?? 90 }}" min="30" max="365">
                                    <div class="form-text">Force password change after this period</div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Two-Factor Authentication</label>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="require_2fa" name="require_2fa" 
                                               {{ ($config->require_2fa ?? false) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="require_2fa">
                                            Require 2FA for all users
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="require_2fa_admin" name="require_2fa_admin" 
                                               {{ ($config->require_2fa_admin ?? true) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="require_2fa_admin">
                                            Require 2FA for admin users
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Login Restrictions</label>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="ip_whitelist" name="ip_whitelist" 
                                               {{ ($config->ip_whitelist ?? false) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="ip_whitelist">
                                            Enable IP address whitelist
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="single_session" name="single_session" 
                                               {{ ($config->single_session ?? false) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="single_session">
                                            Allow only one active session per user
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar Configuration -->
            <div class="col-lg-4">
                <!-- Quick Actions -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h6 class="mb-0">
                            <i class="bi bi-lightning-charge-fill me-2"></i>Quick Actions
                        </h6>
                    </div>
                    <div class="card-body">
                        <!-- <div class="d-grid gap-2">
                            <button type="button" class="btn btn-outline-primary btn-sm" onclick="testEmail()">
                                <i class="bi bi-envelope-check me-2"></i>Test Email Settings
                            </button>
                            <button type="button" class="btn btn-outline-info btn-sm" onclick="testSMS()">
                                <i class="bi bi-phone-vibrate me-2"></i>Test SMS Settings
                            </button>
                            <button type="button" class="btn btn-outline-secondary btn-sm" onclick="clearCache()">
                                <i class="bi bi-arrow-clockwise me-2"></i>Clear System Cache
                            </button>
                            <button type="button" class="btn btn-outline-warning btn-sm" onclick="backupConfig()">
                                <i class="bi bi-download me-2"></i>Backup Configuration
                            </button>
                        </div> -->
                    </div>
                </div>

                <!-- System Information -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h6 class="mb-0">
                            <i class="bi bi-info-circle-fill me-2"></i>System Information
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="mb-2">
                            <small class="text-muted">Version:</small>
                            <div class="fw-bold">ShuleSoft Group Connect v2.1.0</div>
                        </div>
                        <div class="mb-2">
                            <small class="text-muted">Last Updated:</small>
                            <div>{{ now()->format('M d, Y H:i') }}</div>
                        </div>
                        <div class="mb-2">
                            <small class="text-muted">Database:</small>
                            <div>PostgreSQL 14.2</div>
                        </div>
                        <div class="mb-2">
                            <small class="text-muted">Server:</small>
                            <div>{{ request()->server('SERVER_SOFTWARE') ?? 'Apache/2.4' }}</div>
                        </div>
                    </div>
                </div>

                <!-- Configuration Backup -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h6 class="mb-0">
                            <i class="bi bi-cloud-arrow-up-fill me-2"></i>Configuration Backup
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <small class="text-muted">Last Backup:</small>
                            <div>{{ now()->subDays(2)->format('M d, Y H:i') }}</div>
                        </div>
                        <div class="mb-3">
                            <small class="text-muted">Auto-backup:</small>
                            <div>Daily at 2:00 AM</div>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="auto_backup" name="auto_backup" 
                                   {{ ($config->auto_backup ?? true) ? 'checked' : '' }}>
                            <label class="form-check-label" for="auto_backup">
                                Enable automatic backups
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Save Actions -->
                <div class="card">
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <!-- <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-lg me-2"></i>Save Configuration
                            </button> -->
                            <!-- <button type="button" class="btn btn-outline-secondary" onclick="previewChanges()">
                                <i class="bi bi-eye me-2"></i>Preview Changes
                            </button> -->
                            <!-- <a href="{{ route('settings.index') }}" class="btn btn-outline-danger">
                                <i class="bi bi-x-lg me-2"></i>Cancel
                            </a> -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<style>
.form-control-color {
    width: 60px;
    height: 38px;
    border-radius: 0.375rem 0 0 0.375rem;
}

.color-preview-primary,
.color-preview-secondary {
    transition: background-color 0.3s ease;
}
</style>

<script>
// Color picker synchronization
document.addEventListener('DOMContentLoaded', function() {
    const primaryColor = document.getElementById('primary_color');
    const primaryColorText = document.getElementById('primary_color_text');
    const secondaryColor = document.getElementById('secondary_color');
    const secondaryColorText = document.getElementById('secondary_color_text');

    primaryColor.addEventListener('change', function() {
        primaryColorText.value = this.value;
        document.querySelector('.color-preview-primary').style.background = this.value;
    });

    secondaryColor.addEventListener('change', function() {
        secondaryColorText.value = this.value;
        document.querySelector('.color-preview-secondary').style.background = this.value;
    });
});

function updateColorPicker(type) {
    const textInput = document.getElementById(`${type}_color_text`);
    const colorInput = document.getElementById(`${type}_color`);
    const preview = document.querySelector(`.color-preview-${type}`);
    
    colorInput.value = textInput.value;
    preview.style.background = textInput.value;
}

function testEmail() {
    fetch('/settings/test-email', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json',
        },
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Test email sent successfully!');
        } else {
            alert('Email test failed: ' + data.message);
        }
    });
}

function testSMS() {
    fetch('/settings/test-sms', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json',
        },
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Test SMS sent successfully!');
        } else {
            alert('SMS test failed: ' + data.message);
        }
    });
}

function clearCache() {
    if (confirm('Clear system cache? This may temporarily slow down the system.')) {
        fetch('/settings/clear-cache', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
            },
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('System cache cleared successfully!');
            } else {
                alert('Cache clear failed: ' + data.message);
            }
        });
    }
}

function backupConfig() {
    window.location.href = '/settings/backup-config';
}

function previewChanges() {
    // Show a modal with preview of changes
    alert('Preview functionality will be implemented');
}

function resetToDefaults() {
    if (confirm('Reset all settings to default values? This will lose all custom configurations.')) {
        window.location.href = '/settings/reset-to-defaults';
    }
}
</script>
@endsection
