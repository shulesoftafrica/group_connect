@extends('onboarding.layout')

@section('title', 'Step 4: Account Setup')

@php $currentStep = 4; @endphp

@section('content')
<div class="step-content">
    <h3 class="mb-3">
        <i class="fas fa-key me-2 text-primary"></i>
        Account Setup
    </h3>
    <p class="text-muted mb-4">Create your secure password to complete the account setup process.</p>
    
    <form action="{{ route('onboarding.complete') }}" method="POST" id="step4Form">
        @csrf
        
        <!-- Summary of Information -->
        <div class="card bg-light mb-4">
            <div class="card-body">
                <h5 class="card-title">
                    <i class="fas fa-clipboard-check me-2"></i>
                    Account Summary
                </h5>
                <div class="row">
                    <div class="col-md-6">
                        <p class="mb-2">
                            <strong>Organization:</strong><br>
                            {{ $data['org_name'] ?? 'N/A' }}
                        </p>
                        <p class="mb-2">
                            <strong>Contact Person:</strong><br>
                            {{ $data['contact_name'] ?? 'N/A' }}
                        </p>
                        <p class="mb-2">
                            <strong>Email:</strong><br>
                            {{ $data['contact_email'] ?? 'N/A' }}
                        </p>
                    </div>
                    <div class="col-md-6">
                        <p class="mb-2">
                            <strong>Phone:</strong><br>
                            {{ $data['contact_phone'] ?? 'N/A' }}
                        </p>
                        <p class="mb-2">
                            <strong>Number of Schools:</strong><br>
                            {{ $data['schools_count'] ?? 'N/A' }}
                        </p>
                        <p class="mb-2">
                            <strong>Usage Status:</strong><br>
                            @if(($data['usage_status'] ?? '') == 'all')
                                All schools use ShuleSoft
                            @elseif(($data['usage_status'] ?? '') == 'some')
                                Mixed environment
                            @elseif(($data['usage_status'] ?? '') == 'none')
                                No schools use ShuleSoft
                            @else
                                N/A
                            @endif
                        </p>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Password Setup -->
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title mb-4">
                            <i class="fas fa-shield-alt me-2 text-primary"></i>
                            Password Security
                        </h5>
                        
                        <div class="form-group mb-3">
                            <label class="form-label" for="password">
                                <i class="fas fa-lock me-1"></i>
                                Password *
                            </label>
                            <div class="input-group">
                                <input type="password" 
                                       class="form-control @error('password') is-invalid @enderror" 
                                       id="password" 
                                       name="password" 
                                       placeholder="Enter a strong password"
                                       required
                                       autocomplete="new-password">
                                <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                    <i class="fas fa-eye" id="togglePasswordIcon"></i>
                                </button>
                            </div>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="password-strength mt-2">
                                <div class="progress" style="height: 5px;">
                                    <div class="progress-bar" role="progressbar" style="width: 0%"></div>
                                </div>
                                <small class="password-feedback text-muted mt-1"></small>
                            </div>
                        </div>
                        
                        <div class="form-group mb-3">
                            <label class="form-label" for="password_confirmation">
                                <i class="fas fa-lock me-1"></i>
                                Confirm Password *
                            </label>
                            <input type="password" 
                                   class="form-control @error('password_confirmation') is-invalid @enderror" 
                                   id="password_confirmation" 
                                   name="password_confirmation" 
                                   placeholder="Confirm your password"
                                   required
                                   autocomplete="new-password">
                            @error('password_confirmation')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="password-match-feedback mt-1"></div>
                        </div>
                        
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            <strong>Password Requirements:</strong>
                            <ul class="mb-0 mt-2">
                                <li>At least 8 characters long</li>
                                <li>Contains uppercase and lowercase letters</li>
                                <li>Contains at least one number</li>
                                <li>Contains at least one special character</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Terms and Conditions -->
        <div class="row justify-content-center mt-4">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="terms_accepted" required>
                            <label class="form-check-label" for="terms_accepted">
                                <strong>I agree to the Terms and Conditions</strong>
                            </label>
                        </div>
                        <div class="mt-2">
                            <small class="text-muted">
                                By creating an account, you agree to our 
                                <a href="#" class="text-decoration-none">Terms of Service</a> and 
                                <a href="#" class="text-decoration-none">Privacy Policy</a>. 
                                Your 30-day free trial will begin immediately upon account creation.
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="d-flex justify-content-between mt-5">
            <a href="{{ route('onboarding.step3') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-2"></i>
                Previous Step
            </a>
            
            <button type="submit" class="btn btn-primary btn-lg" id="createAccountBtn">
                <i class="fas fa-user-plus me-2"></i>
                Create Account
            </button>
        </div>
    </form>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // Password visibility toggle
        $('#togglePassword').on('click', function() {
            const passwordField = $('#password');
            const passwordIcon = $('#togglePasswordIcon');
            
            if (passwordField.attr('type') === 'password') {
                passwordField.attr('type', 'text');
                passwordIcon.removeClass('fa-eye').addClass('fa-eye-slash');
            } else {
                passwordField.attr('type', 'password');
                passwordIcon.removeClass('fa-eye-slash').addClass('fa-eye');
            }
        });
        
        // Password strength checker
        $('#password').on('input', function() {
            const password = $(this).val();
            const strength = checkPasswordStrength(password);
            updatePasswordStrength(strength);
        });
        
        // Password confirmation checker
        $('#password_confirmation').on('input', function() {
            const password = $('#password').val();
            const confirmation = $(this).val();
            const feedback = $('.password-match-feedback');
            
            if (confirmation === '') {
                feedback.html('');
                return;
            }
            
            if (password === confirmation) {
                feedback.html('<small class="text-success"><i class="fas fa-check me-1"></i>Passwords match</small>');
            } else {
                feedback.html('<small class="text-danger"><i class="fas fa-times me-1"></i>Passwords do not match</small>');
            }
        });
        
        // Form validation
        $('#step4Form').on('submit', function(e) {
            let isValid = true;
            
            // Check password strength
            const password = $('#password').val();
            if (password.length < 8) {
                isValid = false;
                $('#password').addClass('is-invalid');
            }
            
            // Check password confirmation
            const confirmation = $('#password_confirmation').val();
            if (password !== confirmation) {
                isValid = false;
                $('#password_confirmation').addClass('is-invalid');
            }
            
            // Check terms acceptance
            if (!$('#terms_accepted').is(':checked')) {
                isValid = false;
                alert('Please accept the Terms and Conditions to continue.');
            }
            
            if (!isValid) {
                e.preventDefault();
                return;
            }
            
            // Show loading state
            const submitBtn = $('#createAccountBtn');
            submitBtn.prop('disabled', true);
            submitBtn.html('<i class="fas fa-spinner fa-spin me-2"></i>Creating Account...');
        });
        
        function checkPasswordStrength(password) {
            let score = 0;
            let feedback = [];
            
            if (password.length >= 8) score += 20;
            else feedback.push('At least 8 characters');
            
            if (/[a-z]/.test(password)) score += 20;
            else feedback.push('Lowercase letter');
            
            if (/[A-Z]/.test(password)) score += 20;
            else feedback.push('Uppercase letter');
            
            if (/[0-9]/.test(password)) score += 20;
            else feedback.push('Number');
            
            if (/[^a-zA-Z0-9]/.test(password)) score += 20;
            else feedback.push('Special character');
            
            return {
                score: score,
                feedback: feedback
            };
        }
        
        function updatePasswordStrength(strength) {
            const progressBar = $('.progress-bar');
            const feedbackEl = $('.password-feedback');
            
            let className = '';
            let text = '';
            
            if (strength.score <= 20) {
                className = 'bg-danger';
                text = 'Very Weak';
            } else if (strength.score <= 40) {
                className = 'bg-warning';
                text = 'Weak';
            } else if (strength.score <= 60) {
                className = 'bg-info';
                text = 'Fair';
            } else if (strength.score <= 80) {
                className = 'bg-primary';
                text = 'Good';
            } else {
                className = 'bg-success';
                text = 'Strong';
            }
            
            progressBar.removeClass('bg-danger bg-warning bg-info bg-primary bg-success')
                      .addClass(className)
                      .css('width', strength.score + '%');
            
            if (strength.feedback.length > 0) {
                feedbackEl.html(`${text} - Missing: ${strength.feedback.join(', ')}`);
            } else {
                feedbackEl.html(`${text} - Password meets all requirements`);
            }
        }
    });
</script>
@endsection
