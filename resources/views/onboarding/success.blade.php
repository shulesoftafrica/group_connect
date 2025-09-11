@extends('onboarding.layout')

@section('title', 'Welcome to ShuleSoft Group Connect!')

@php $currentStep = 5; @endphp

@section('content')
<div class="step-content text-center">
    <div class="success-animation mb-4">
        <i class="fas fa-check-circle text-success" style="font-size: 5rem;"></i>
    </div>
    
    <h2 class="mb-3 text-success">
        <i class="fas fa-party-horn me-2"></i>
        Welcome to ShuleSoft Group Connect!
    </h2>
    
    <p class="lead text-muted mb-4">
        Your account has been successfully created and your organization is now part of the ShuleSoft network.
    </p>
    
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card bg-light">
                <div class="card-body">
                    <h5 class="card-title">
                        <i class="fas fa-rocket me-2 text-primary"></i>
                        What's Next?
                    </h5>
                    <div class="row text-start">
                        <div class="col-md-6">
                            <div class="d-flex align-items-start mb-3">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-envelope-circle-check text-primary me-2 mt-1"></i>
                                </div>
                                <div>
                                    <strong>Check Your Email</strong><br>
                                    <small class="text-muted">We've sent a welcome message with important information about your account.</small>
                                </div>
                            </div>
                            
                            <div class="d-flex align-items-start mb-3">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-user-plus text-primary me-2 mt-1"></i>
                                </div>
                                <div>
                                    <strong>Add Team Members</strong><br>
                                    <small class="text-muted">Invite your colleagues to join your organization and manage schools together.</small>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="d-flex align-items-start mb-3">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-school text-primary me-2 mt-1"></i>
                                </div>
                                <div>
                                    <strong>School Setup</strong><br>
                                    <small class="text-muted">Our team will contact each school to begin the onboarding process.</small>
                                </div>
                            </div>
                            
                            <div class="d-flex align-items-start mb-3">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-chart-line text-primary me-2 mt-1"></i>
                                </div>
                                <div>
                                    <strong>Start Monitoring</strong><br>
                                    <small class="text-muted">Access real-time data and insights from all your schools in one dashboard.</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row justify-content-center mt-4">
        <div class="col-md-6">
            <div class="alert alert-info">
                <i class="fas fa-gift me-2"></i>
                <strong>30-Day Free Trial</strong><br>
                Your trial period has started! Explore all features and see how ShuleSoft Group Connect can transform your school network management.
            </div>
        </div>
    </div>
    
    <div class="row justify-content-center mt-4">
        <div class="col-md-8">
            <div class="card border-primary">
                <div class="card-body">
                    <h5 class="card-title text-primary">
                        <i class="fas fa-headset me-2"></i>
                        Need Help Getting Started?
                    </h5>
                    <p class="card-text">
                        Our support team is here to help you every step of the way. Contact us for personalized onboarding assistance.
                    </p>
                    <div class="row">
                        <div class="col-md-6">
                            <p class="mb-2">
                                <i class="fas fa-phone me-2 text-primary"></i>
                                <strong>Phone:</strong> +255 748 771 580
                            </p>
                            <p class="mb-2">
                                <i class="fas fa-envelope me-2 text-primary"></i>
                                <strong>Email:</strong> support@shulesoft.africa
                            </p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-2">
                                <i class="fab fa-whatsapp me-2 text-success"></i>
                                <strong>WhatsApp:</strong> +254 714 825 469
                            </p>
                            <p class="mb-2">
                                <i class="fas fa-clock me-2 text-primary"></i>
                                <strong>Hours:</strong> Mon-Fri, 8AM-6PM EAT
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="mt-5">
        <a href="{{ route('login') }}" class="btn btn-primary btn-lg me-3">
            <i class="fas fa-sign-in-alt me-2"></i>
            Login to Your Account
        </a>
        
        <a href="https://shulesoft.group/user-guide" target="_blank" class="btn btn-outline-primary btn-lg">
            <i class="fas fa-book me-2"></i>
            View User Guide
        </a>
    </div>
    
    <div class="mt-4">
        <small class="text-muted">
            Thank you for choosing ShuleSoft Group Connect. We're excited to be part of your educational journey!
        </small>
    </div>
</div>
@endsection

@section('styles')
<style>
    .success-animation {
        animation: bounceIn 1s ease-in-out;
    }
    
    @keyframes bounceIn {
        0% {
            transform: scale(0);
            opacity: 0;
        }
        50% {
            transform: scale(1.1);
            opacity: 0.8;
        }
        100% {
            transform: scale(1);
            opacity: 1;
        }
    }
    
    .card {
        transition: transform 0.2s ease;
    }
    
    .card:hover {
        transform: translateY(-2px);
    }
</style>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // Confetti effect (optional)
        setTimeout(function() {
            // You can add a confetti library here if desired
            console.log('Account created successfully!');
        }, 500);
        
        // Auto-focus on login button after 3 seconds
        setTimeout(function() {
            $('.btn-primary').first().focus();
        }, 3000);
    });
</script>
@endsection
