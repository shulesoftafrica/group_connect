@extends('onboarding.layout')

@section('title', 'Step 1: Organization Information')

@php $currentStep = 1; @endphp

@section('content')
<div class="step-content">
    <h3 class="mb-3">
        <i class="fas fa-building me-2 text-primary"></i>
        Organization Information
    </h3>
    <p class="text-muted mb-4">
        Let's start by setting up your organization details. This information will be used across your school network.
        <span class="badge bg-info ms-2">
            <i class="fas fa-shield-alt me-1"></i>Real-time validation enabled
        </span>
    </p>
    
    <form action="{{ route('onboarding.save-step1') }}" method="POST" id="step1Form">
        @csrf
        
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label class="form-label" for="org_name">
                        <i class="fas fa-building me-1"></i>
                        Organization Name *
                    </label>
                    <input type="text" 
                           class="form-control @error('org_name') is-invalid @enderror" 
                           id="org_name" 
                           name="org_name" 
                           value="{{ old('org_name', $data['org_name'] ?? '') }}" 
                           placeholder="e.g., Academy Group Schools"
                           required>
                    @error('org_name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="form-text text-muted">The official name of your organization</small>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="form-group">
                    <label class="form-label" for="org_email">
                        <i class="fas fa-envelope me-1"></i>
                        Organization Email *
                    </label>
                    <input type="email" 
                           class="form-control @error('org_email') is-invalid @enderror" 
                           id="org_email" 
                           name="org_email" 
                           value="{{ old('org_email', $data['org_email'] ?? '') }}" 
                           placeholder="info@academygroup.edu"
                           required>
                    @error('org_email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="form-text text-muted">Main contact email for your organization</small>
                </div>
            </div>
        </div>
        
        <h5 class="mt-4 mb-3">
            <i class="fas fa-user me-2 text-primary"></i>
            Primary Contact Person
        </h5>
        
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label class="form-label" for="contact_name">
                        <i class="fas fa-user me-1"></i>
                        Full Name *
                    </label>
                    <input type="text" 
                           class="form-control @error('contact_name') is-invalid @enderror" 
                           id="contact_name" 
                           name="contact_name" 
                           value="{{ old('contact_name', $data['contact_name'] ?? '') }}" 
                           placeholder="John Doe"
                           required>
                    @error('contact_name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="form-group">
                    <label class="form-label" for="contact_email">
                        <i class="fas fa-envelope me-1"></i>
                        Email Address *
                    </label>
                    <input type="email" 
                           class="form-control @error('contact_email') is-invalid @enderror" 
                           id="contact_email" 
                           name="contact_email" 
                           value="{{ old('contact_email', $data['contact_email'] ?? '') }}" 
                           placeholder="john.doe@academygroup.edu"
                           required>
                    @error('contact_email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="form-text text-muted">This will be your login email</small>
                </div>
            </div>
        </div>
        
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label class="form-label" for="contact_phone">
                        <i class="fas fa-phone me-1"></i>
                        Phone Number *
                    </label>
                    <input type="tel" 
                           class="form-control @error('contact_phone') is-invalid @enderror" 
                           id="contact_phone" 
                           name="contact_phone" 
                           value="{{ old('contact_phone', $data['contact_phone'] ?? '') }}" 
                           placeholder="+254712345678"
                           required>
                    @error('contact_phone')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="form-text text-muted">Include country code</small>
                </div>
            </div>
        </div>
        
        <!-- Validation Status Panel -->
        <div class="validation-status mt-4 p-3 bg-light rounded d-none" id="validationStatus">
            <h6 class="mb-2"><i class="fas fa-clipboard-check me-2"></i>Validation Status</h6>
            <div class="row">
                <div class="col-md-4">
                    <div class="validation-item" data-field="org_name">
                        <i class="fas fa-building me-2"></i>Organization: <span class="status">Pending</span>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="validation-item" data-field="contact_email">
                        <i class="fas fa-envelope me-2"></i>Email: <span class="status">Pending</span>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="validation-item" data-field="contact_phone">
                        <i class="fas fa-phone me-2"></i>Phone: <span class="status">Pending</span>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="d-flex justify-content-between mt-4">
            <a href="{{ route('login') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-2"></i>
                Back to Login
            </a>
            
            <button type="submit" class="btn btn-primary">
                Next Step
                <i class="fas fa-arrow-right ms-2"></i>
            </button>
        </div>
    </form>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // Handle field highlighting from server-side errors
        @if(session('highlight_field'))
            const highlightField = '{{ session('highlight_field') }}';
            const $field = $('#' + highlightField);
            if ($field.length) {
                $field.addClass('is-invalid').focus();
                // Add visual emphasis
                $field.closest('.form-group').addClass('highlight-error');
                setTimeout(() => {
                    $field.closest('.form-group').removeClass('highlight-error');
                }, 3000);
            }
        @endif
        
        // AJAX validation function
        function validateField(field, endpoint, data) {
            const $field = $(field);
            const $parent = $field.closest('.form-group');
            const $feedback = $parent.find('.ajax-feedback');
            const $spinner = $parent.find('.ajax-spinner');
            const fieldId = $field.attr('id');
            
            // Show validation status panel
            $('#validationStatus').removeClass('d-none');
            updateValidationStatus(fieldId, 'checking');
            
            // Show spinner
            if ($spinner.length === 0) {
                $field.after('<div class="ajax-spinner ms-2 d-inline-block"><i class="fas fa-spinner fa-spin"></i></div>');
            } else {
                $spinner.show();
            }
            
            // Clear previous feedback
            $feedback.remove();
            $field.removeClass('is-valid is-invalid');
            
            $.ajax({
                url: endpoint,
                method: 'POST',
                data: {
                    ...data,
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    $parent.find('.ajax-spinner').hide();
                    
                    if (response.valid) {
                        $field.addClass('is-valid');
                        $field.after('<div class="ajax-feedback valid-feedback"><i class="fas fa-check me-1"></i>' + response.message + '</div>');
                        updateValidationStatus(fieldId, 'valid');
                    } else {
                        $field.addClass('is-invalid');
                        $field.after('<div class="ajax-feedback invalid-feedback"><i class="fas fa-exclamation-circle me-1"></i>' + response.message + '</div>');
                        updateValidationStatus(fieldId, 'invalid');
                    }
                },
                error: function(xhr) {
                    $parent.find('.ajax-spinner').hide();
                    $field.addClass('is-invalid');
                    $field.after('<div class="ajax-feedback invalid-feedback"><i class="fas fa-exclamation-triangle me-1"></i>Validation error. Please try again.</div>');
                    updateValidationStatus(fieldId, 'error');
                }
            });
        }
        
        // Update validation status panel
        function updateValidationStatus(fieldId, status) {
            let $statusItem;
            let statusText = '';
            let statusClass = '';
            
            // Map field IDs to status items
            if (fieldId === 'org_name') {
                $statusItem = $('[data-field="org_name"] .status');
            } else if (fieldId === 'contact_email') {
                $statusItem = $('[data-field="contact_email"] .status');
            } else if (fieldId === 'contact_phone') {
                $statusItem = $('[data-field="contact_phone"] .status');
            }
            
            if (!$statusItem) return;
            
            switch (status) {
                case 'checking':
                    statusText = '<i class="fas fa-spinner fa-spin"></i> Checking...';
                    statusClass = 'text-warning';
                    break;
                case 'valid':
                    statusText = '<i class="fas fa-check"></i> Available';
                    statusClass = 'text-success';
                    break;
                case 'invalid':
                    statusText = '<i class="fas fa-times"></i> Invalid';
                    statusClass = 'text-danger';
                    break;
                case 'error':
                    statusText = '<i class="fas fa-exclamation-triangle"></i> Error';
                    statusClass = 'text-danger';
                    break;
                default:
                    statusText = 'Pending';
                    statusClass = 'text-muted';
            }
            
            $statusItem.html(statusText).removeClass('text-success text-danger text-warning text-muted').addClass(statusClass);
        }
        
        // Real-time validation for organization name
        let orgNameTimeout;
        $('#org_name').on('blur', function() {
            const value = $(this).val().trim();
            if (value.length >= 2) {
                clearTimeout(orgNameTimeout);
                orgNameTimeout = setTimeout(() => {
                    validateField(this, '{{ route("onboarding.validate-organization") }}', {
                        org_name: value
                    });
                }, 300);
            }
        });
        
        // Real-time validation for contact email
        let emailTimeout;
        $('#contact_email').on('blur', function() {
            const value = $(this).val().trim();
            if (value && /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value)) {
                clearTimeout(emailTimeout);
                emailTimeout = setTimeout(() => {
                    validateField(this, '{{ route("onboarding.validate-email") }}', {
                        email: value
                    });
                }, 300);
            }
        });
        
        // Real-time validation for phone number
        let phoneTimeout;
        $('#contact_phone').on('blur', function() {
            const value = $(this).val().trim();
            if (value && value.length >= 10) {
                clearTimeout(phoneTimeout);
                phoneTimeout = setTimeout(() => {
                    validateField(this, '{{ route("onboarding.validate-phone") }}', {
                        phone: value
                    });
                }, 300);
            }
        });
        
        // Form validation on submit
        $('#step1Form').on('submit', function(e) {
            let isValid = true;
            let hasAjaxErrors = false;
            
            // Check for AJAX validation errors
            $('.is-invalid').each(function() {
                if ($(this).siblings('.ajax-feedback').length > 0) {
                    hasAjaxErrors = true;
                }
            });
            
            // Check required fields
            $(this).find('input[required]').each(function() {
                if (!$(this).val().trim()) {
                    isValid = false;
                    $(this).addClass('is-invalid');
                    
                    // Add error message if not exists
                    const $parent = $(this).closest('.form-group');
                    if ($parent.find('.required-feedback').length === 0) {
                        $(this).after('<div class="required-feedback invalid-feedback">This field is required</div>');
                    }
                } else {
                    $(this).removeClass('is-invalid');
                    $(this).siblings('.required-feedback').remove();
                }
            });
            
            // Email format validation
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            $('input[type="email"]').each(function() {
                if ($(this).val() && !emailRegex.test($(this).val())) {
                    isValid = false;
                    $(this).addClass('is-invalid');
                    
                    const $parent = $(this).closest('.form-group');
                    if ($parent.find('.format-feedback').length === 0) {
                        $(this).after('<div class="format-feedback invalid-feedback">Please enter a valid email address</div>');
                    }
                } else {
                    $(this).siblings('.format-feedback').remove();
                }
            });
            
            if (!isValid || hasAjaxErrors) {
                e.preventDefault();
                
                if (hasAjaxErrors) {
                    alert('Please fix the highlighted validation errors before proceeding.');
                } else {
                    alert('Please fill in all required fields correctly.');
                }
                
                // Focus on first error field
                $('.is-invalid').first().focus();
            }
        });
        
        // Clean up validation on input
        $('input').on('input', function() {
            // Remove validation classes when user starts typing
            $(this).removeClass('is-valid is-invalid');
            $(this).siblings('.ajax-feedback, .required-feedback, .format-feedback').remove();
            $(this).siblings('.ajax-spinner').hide();
        });
        
        // Enhanced styling for validation feedback
        $('<style>')
            .prop('type', 'text/css')
            .html(`
                .ajax-spinner { color: #6c757d; font-size: 0.875rem; }
                .ajax-feedback { display: block; width: 100%; margin-top: 0.25rem; font-size: 0.875rem; }
                .ajax-feedback.valid-feedback { color: #198754; }
                .ajax-feedback.invalid-feedback { color: #dc3545; }
                .form-control.is-valid { border-color: #198754; }
                .form-control.is-invalid { border-color: #dc3545; }
                .highlight-error { 
                    background: rgba(220, 53, 69, 0.1); 
                    padding: 10px; 
                    border-radius: 5px; 
                    transition: all 0.3s ease;
                }
                .highlight-error .form-label {
                    color: #dc3545;
                    font-weight: bold;
                }
                .validation-status {
                    border: 1px solid #e9ecef;
                    transition: all 0.3s ease;
                }
                .validation-item {
                    padding: 5px 0;
                    font-size: 0.9rem;
                }
                .validation-item .status {
                    font-weight: 500;
                }
                .badge {
                    animation: pulse 2s infinite;
                }
                @keyframes pulse {
                    0% { opacity: 1; }
                    50% { opacity: 0.7; }
                    100% { opacity: 1; }
                }
            `)
            .appendTo('head');
    });
</script>
@endsection
