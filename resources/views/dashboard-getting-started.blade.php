@extends('layouts.admin')

@section('title', 'Getting Started - Dashboard')

@section('content')
<!-- Phase 2: Getting Started Dashboard -->
<div class="row mb-4">
    <div class="col-12">
        <div class="welcome-banner bg-gradient-primary text-white rounded-4 p-4 mb-4">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <h1 class="h2 mb-2">👋 Welcome to ShuleSoft Group Connect, {{ $user->name }}!</h1>
                    <p class="mb-3 opacity-90">
                        Let's get your schools connected and start managing your educational organization effectively.
                    </p>
                    <div class="setup-progress mb-3">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="small">Setup Progress</span>
                            <span class="small">{{ $onboardingStatus['setup_completion_percentage'] }}% Complete</span>
                        </div>
                        <div class="progress" style="height: 8px;">
                            <div class="progress-bar bg-warning" 
                                 style="width: {{ $onboardingStatus['setup_completion_percentage'] }}%"
                                 role="progressbar"></div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 text-center">
                    <div class="setup-illustration">
                        <i class="bi bi-rocket-takeoff display-1 opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Quick Stats for New Users -->
<div class="row mb-4">
    <div class="col-xl-3 col-md-6 mb-3">
        <div class="card border-0 h-100">
            <div class="card-body text-center">
                <div class="bg-primary bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                    <i class="bi bi-building text-primary fs-4"></i>
                </div>
                <h3 class="mb-1">{{ $basicStats['total_schools'] }}</h3>
                <p class="text-muted mb-0">Connected Schools</p>
                @if($basicStats['total_schools'] === 0)
                    <small class="text-warning">
                        <i class="bi bi-exclamation-circle"></i> Add your first school
                    </small>
                @endif
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6 mb-3">
        <div class="card border-0 h-100">
            <div class="card-body text-center">
                <div class="bg-info bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                    <i class="bi bi-clock text-info fs-4"></i>
                </div>
                <h3 class="mb-1">{{ $basicStats['pending_requests'] }}</h3>
                <p class="text-muted mb-0">Pending Setup</p>
                @if($basicStats['pending_requests'] > 0)
                    <small class="text-info">
                        <i class="bi bi-info-circle"></i> In progress
                    </small>
                @endif
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6 mb-3">
        <div class="card border-0 h-100">
            <div class="card-body text-center">
                <div class="bg-success bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                    <i class="bi bi-people text-success fs-4"></i>
                </div>
                <h3 class="mb-1">{{ number_format($basicStats['total_students']) }}</h3>
                <p class="text-muted mb-0">Total Students</p>
                @if($basicStats['total_students'] === 0)
                    <small class="text-muted">Available after school setup</small>
                @endif
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6 mb-3">
        <div class="card border-0 h-100">
            <div class="card-body text-center">
                <div class="bg-warning bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                    <i class="bi bi-calendar-event text-warning fs-4"></i>
                </div>
                <h3 class="mb-1">{{ $onboardingStatus['days_since_registration'] }}</h3>
                <p class="text-muted mb-0">Days with us</p>
                @if($onboardingStatus['days_since_registration'] <= 1)
                    <small class="text-success">
                        <i class="bi bi-star"></i> Welcome aboard!
                    </small>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Next Steps Section -->
@if(count($onboardingStatus['next_steps']) > 0)
<div class="row mb-4">
    <div class="col-12">
        <div class="card border-0">
            <div class="card-body">
                <h5 class="card-title mb-4">
                    <i class="bi bi-list-check text-primary me-2"></i>
                    Your Next Steps
                </h5>
                
                <div class="row">
                    @foreach($onboardingStatus['next_steps'] as $step)
                    <div class="col-lg-6 mb-3">
                        <div class="next-step-card p-3 rounded-3 border 
                            {{ $step['priority'] === 'high' ? 'border-danger bg-danger bg-opacity-5' : 
                               ($step['priority'] === 'medium' ? 'border-warning bg-warning bg-opacity-5' : 
                                'border-info bg-info bg-opacity-5') }}">
                            <div class="d-flex align-items-start">
                                <div class="me-3">
                                    <i class="bi {{ $step['icon'] }} fs-4 
                                       {{ $step['priority'] === 'high' ? 'text-danger' : 
                                          ($step['priority'] === 'medium' ? 'text-warning' : 'text-info') }}"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="mb-1">{{ $step['title'] }}</h6>
                                    <p class="mb-2 text-muted small">{{ $step['description'] }}</p>
                                    <a href="{{ $step['action_url'] }}" class="btn 
                                       {{ $step['priority'] === 'high' ? 'btn-danger' : 
                                          ($step['priority'] === 'medium' ? 'btn-warning' : 'btn-info') }} 
                                       btn-sm">
                                        {{ $step['action_text'] }}
                                        <i class="bi bi-arrow-right ms-1"></i>
                                    </a>
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
@endif

<!-- Schools Status Section -->
@if($schools->count() > 0)
<div class="row mb-4">
    <div class="col-12">
        <div class="card border-0">
            <div class="card-body">
                <h5 class="card-title mb-4">
                    <i class="bi bi-buildings text-primary me-2"></i>
                    Your Schools
                </h5>
                
                <div class="row">
                    @foreach($schools as $school)
                    <div class="col-lg-4 col-md-6 mb-3">
                        <div class="card border school-card h-100">
                            <div class="card-body">
                                <div class="d-flex align-items-center mb-2">
                                    <div class="bg-primary bg-opacity-10 rounded-circle p-2 me-3">
                                        <i class="bi bi-building text-primary"></i>
                                    </div>
                                    <div>
                                        <h6 class="mb-0">{{ $school->name }}</h6>
                                        <small class="text-muted">{{ $school->location ?? 'Location not set' }}</small>
                                    </div>
                                </div>
                                
                                <div class="school-status">
                                    @if($school->status === 'active')
                                        <span class="badge bg-success">
                                            <i class="bi bi-check-circle me-1"></i>Active
                                        </span>
                                    @elseif($school->status === 'pending')
                                        <span class="badge bg-warning">
                                            <i class="bi bi-clock me-1"></i>Setting Up
                                        </span>
                                    @else
                                        <span class="badge bg-secondary">
                                            <i class="bi bi-pause-circle me-1"></i>{{ ucfirst($school->status) }}
                                        </span>
                                    @endif
                                </div>
                                
                                @if($school->status === 'active')
                                <div class="mt-3">
                                    <a href="{{ route('schools.show', $school->id) }}" class="btn btn-outline-primary btn-sm">
                                        View Details
                                        <i class="bi bi-arrow-right ms-1"></i>
                                    </a>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endif

<!-- Help and Resources Section -->
<div class="row">
    <div class="col-lg-8">
        <div class="card border-0 bg-light">
            <div class="card-body">
                <h5 class="card-title">
                    <i class="bi bi-question-circle text-info me-2"></i>
                    Need Help Getting Started?
                </h5>
                <p class="text-muted mb-3">
                    Our team is here to help you set up your schools and get the most out of ShuleSoft Group Connect.
                </p>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="d-flex align-items-center mb-3">
                            <i class="bi bi-book text-primary me-3 fs-5"></i>
                            <div>
                                <h6 class="mb-0">User Guide</h6>
                                <small class="text-muted">Step-by-step instructions</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex align-items-center mb-3">
                            <i class="bi bi-headset text-success me-3 fs-5"></i>
                            <div>
                                <h6 class="mb-0">Support Team</h6>
                                <small class="text-muted">Get help from our experts</small>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="mt-3">
                    <a href="{{ route('user-guide') }}" class="btn btn-outline-primary me-2">
                        <i class="bi bi-book me-1"></i>View Guide
                    </a>
                    <a href="mailto:support@shulesoft.com" class="btn btn-outline-success">
                        <i class="bi bi-envelope me-1"></i>Contact Support
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <div class="card border-0 bg-primary text-white">
            <div class="card-body text-center">
                <i class="bi bi-lightbulb display-4 mb-3 opacity-75"></i>
                <h5>Quick Tip</h5>
                <p class="mb-3 small opacity-90">
                    Start by adding your most active school first. This will give you immediate access to student data and reporting features.
                </p>
                <a href="{{ route('onboarding.start') }}" class="btn btn-light text-primary">
                    Add First School
                </a>
            </div>
        </div>
    </div>
</div>

<style>
.welcome-banner {
    background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
}

.next-step-card {
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.next-step-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}

.school-card {
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.school-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}

.setup-progress .progress {
    background-color: rgba(255,255,255,0.2);
}

.stats-card {
    transition: transform 0.2s ease;
}

.stats-card:hover {
    transform: translateY(-2px);
}
</style>
@endsection
