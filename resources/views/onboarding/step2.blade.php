@extends('onboarding.layout')

@section('title', 'Step 2: School Information')

@php $currentStep = 2; @endphp

@section('content')
<div class="step-content">
    <h3 class="mb-3">
        <i class="fas fa-school me-2 text-primary"></i>
        School Network Information
    </h3>
    <p class="text-muted mb-4">Tell us about your school network and how they currently use school management systems.</p>
    
    <form action="{{ route('onboarding.save-step2') }}" method="POST" id="step2Form">
        @csrf
        
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label class="form-label" for="schools_count">
                        <i class="fas fa-hashtag me-1"></i>
                        Number of Schools *
                    </label>
                    <input type="number" 
                           class="form-control @error('schools_count') is-invalid @enderror" 
                           id="schools_count" 
                           name="schools_count" 
                           value="{{ old('schools_count', $data['schools_count'] ?? '') }}" 
                           min="2" 
                           max="100"
                           placeholder="5"
                           required>
                    @error('schools_count')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="form-text text-muted">Total number of schools in your network (minimum 2)</small>
                </div>
            </div>
        </div>
        
        <div class="form-group mt-4">
            <label class="form-label">
                <i class="fas fa-laptop me-1"></i>
                Current System Usage *
            </label>
            <div class="mt-3">
                <div class="card border-0 shadow-sm mb-3">
                    <div class="card-body">
                        <div class="form-check">
                            <input class="form-check-input" 
                                   type="radio" 
                                   name="usage_status" 
                                   id="usage_all" 
                                   value="all"
                                   {{ old('usage_status', $data['usage_status'] ?? '') == 'all' ? 'checked' : '' }}
                                   required>
                            <label class="form-check-label" for="usage_all">
                                <strong>All schools use ShuleSoft</strong>
                                <br>
                                <small class="text-muted">All schools in your network are already using ShuleSoft school management system</small>
                            </label>
                        </div>
                    </div>
                </div>
                
                <div class="card border-0 shadow-sm mb-3">
                    <div class="card-body">
                        <div class="form-check">
                            <input class="form-check-input" 
                                   type="radio" 
                                   name="usage_status" 
                                   id="usage_some" 
                                   value="some"
                                   {{ old('usage_status', $data['usage_status'] ?? '') == 'some' ? 'checked' : '' }}
                                   required>
                            <label class="form-check-label" for="usage_some">
                                <strong>Mixed environment</strong>
                                <br>
                                <small class="text-muted">Some schools use ShuleSoft, others use different systems or none at all</small>
                            </label>
                        </div>
                    </div>
                </div>
                
                <div class="card border-0 shadow-sm mb-3">
                    <div class="card-body">
                        <div class="form-check">
                            <input class="form-check-input" 
                                   type="radio" 
                                   name="usage_status" 
                                   id="usage_none" 
                                   value="none"
                                   {{ old('usage_status', $data['usage_status'] ?? '') == 'none' ? 'checked' : '' }}
                                   required>
                            <label class="form-check-label" for="usage_none">
                                <strong>No schools use ShuleSoft</strong>
                                <br>
                                <small class="text-muted">Schools use other systems or manual processes - we'll help them transition to ShuleSoft</small>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
            @error('usage_status')
                <div class="text-danger mt-2">{{ $message }}</div>
            @enderror
        </div>
        
        <div class="alert alert-info mt-4">
            <i class="fas fa-info-circle me-2"></i>
            <strong>What happens next?</strong><br>
            Based on your selection, we'll customize the next step to collect the appropriate information for your schools.
        </div>
        
        <div class="d-flex justify-content-between mt-5">
            <a href="{{ route('onboarding.step1') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-2"></i>
                Previous Step
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
        $('#step2Form').on('submit', function(e) {
            let isValid = true;
            
            // Check schools count
            const schoolsCount = $('#schools_count').val();
            if (!schoolsCount || parseInt(schoolsCount) < 2) {
                isValid = false;
                $('#schools_count').addClass('is-invalid');
            } else {
                $('#schools_count').removeClass('is-invalid');
            }
            
            // Check usage status selection
            if (!$('input[name="usage_status"]:checked').length) {
                isValid = false;
                $('.form-check-input[name="usage_status"]').addClass('is-invalid');
            } else {
                $('.form-check-input[name="usage_status"]').removeClass('is-invalid');
            }
            
            if (!isValid) {
                e.preventDefault();
                alert('Please complete all required fields.');
            }
        });
        
        // Real-time validation for schools count
        $('#schools_count').on('input', function() {
            const value = parseInt($(this).val());
            if (value >= 2) {
                $(this).removeClass('is-invalid');
            }
        });
        
        // Highlight selected usage option
        $('input[name="usage_status"]').on('change', function() {
            $('.card').removeClass('border-primary');
            $(this).closest('.card').addClass('border-primary');
        });
        
        // Initialize with selected option
        const selectedUsage = $('input[name="usage_status"]:checked');
        if (selectedUsage.length) {
            selectedUsage.closest('.card').addClass('border-primary');
        }
    });
</script>
@endsection
