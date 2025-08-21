@extends('onboarding.layout')

@section('title', 'Step 1: Organization Information')

@php $currentStep = 1; @endphp

@section('content')
<div class="step-content">
    <h3 class="mb-3">
        <i class="fas fa-building me-2 text-primary"></i>
        Organization Information
    </h3>
    <p class="text-muted mb-4">Let's start by setting up your organization details. This information will be used across your school network.</p>
    
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
        
        <div class="d-flex justify-content-between mt-5">
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
        // Form validation
        $('#step1Form').on('submit', function(e) {
            let isValid = true;
            
            // Check required fields
            $(this).find('input[required]').each(function() {
                if (!$(this).val().trim()) {
                    isValid = false;
                    $(this).addClass('is-invalid');
                } else {
                    $(this).removeClass('is-invalid');
                }
            });
            
            // Email validation
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            $('input[type="email"]').each(function() {
                if ($(this).val() && !emailRegex.test($(this).val())) {
                    isValid = false;
                    $(this).addClass('is-invalid');
                }
            });
            
            if (!isValid) {
                e.preventDefault();
                alert('Please fix the highlighted fields.');
            }
        });
        
        // Real-time validation
        $('input').on('blur', function() {
            if ($(this).attr('required') && !$(this).val().trim()) {
                $(this).addClass('is-invalid');
            } else {
                $(this).removeClass('is-invalid');
            }
        });
    });
</script>
@endsection
