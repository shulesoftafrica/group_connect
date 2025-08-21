@extends('onboarding.layout')

@section('title', 'Step 3: School Details')

@php $currentStep = 3; @endphp

@section('content')
<div class="step-content">
    <h3 class="mb-3">
        <i class="fas fa-list-alt me-2 text-primary"></i>
        School Details
    </h3>
    <p class="text-muted mb-4">
        @if(($data['usage_status'] ?? '') == 'all')
            Please provide the ShuleSoft login codes for your existing schools.
        @elseif(($data['usage_status'] ?? '') == 'some')
            Add details for each school - those using ShuleSoft and those that need onboarding.
        @else
            Add details for each school that will be onboarded to ShuleSoft.
        @endif
    </p>
    
    <form action="{{ route('onboarding.save-step3') }}" method="POST" id="step3Form">
        @csrf
        
        @if(($data['usage_status'] ?? '') == 'all')
            {{-- All schools use ShuleSoft --}}
            <div id="shulesoft-schools-section">
                <h5 class="mb-3">
                    <i class="fas fa-check-circle text-success me-2"></i>
                    ShuleSoft Schools
                </h5>
                <p class="text-muted mb-3">Enter the login codes for your existing ShuleSoft schools:</p>
                
                <div id="shulesoft-schools-container">
                    @php
                        $existingSchools = old('shulesoft_schools', $data['shulesoft_schools'] ?? []);
                        $schoolCount = max(1, count($existingSchools), $data['schools_count'] ?? 2);
                    @endphp
                    
                    @for($i = 0; $i < $schoolCount; $i++)
                        <div class="school-item card mb-3">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-8">
                                        <label class="form-label">School {{ $i + 1 }} Login Code *</label>
                                        <input type="text" 
                                               class="form-control @error('shulesoft_schools.'.$i.'.login_code') is-invalid @enderror" 
                                               name="shulesoft_schools[{{ $i }}][login_code]" 
                                               value="{{ $existingSchools[$i]['login_code'] ?? '' }}" 
                                               placeholder="e.g., ACADEMY001"
                                               required>
                                        @error('shulesoft_schools.'.$i.'.login_code')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-4 d-flex align-items-end">
                                        @if($i > 0)
                                            <button type="button" class="btn btn-outline-danger btn-sm remove-school">
                                                <i class="fas fa-trash"></i> Remove
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endfor
                </div>
                
                <button type="button" class="btn btn-outline-primary btn-sm" id="add-shulesoft-school">
                    <i class="fas fa-plus me-2"></i>Add Another School
                </button>
            </div>
            
        @elseif(($data['usage_status'] ?? '') == 'some')
            {{-- Mixed environment --}}
            <div id="mixed-schools-section">
                <h5 class="mb-3">
                    <i class="fas fa-layer-group text-warning me-2"></i>
                    Mixed School Environment
                </h5>
                
                <div id="mixed-schools-container">
                    @php
                        $existingMixed = old('mixed_schools', $data['mixed_schools'] ?? []);
                        $schoolCount = max(1, count($existingMixed), $data['schools_count'] ?? 2);
                    @endphp
                    
                    @for($i = 0; $i < $schoolCount; $i++)
                        <div class="school-item card mb-3">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4">
                                        <label class="form-label">School {{ $i + 1 }} Type *</label>
                                        <select class="form-control school-type" name="mixed_schools[{{ $i }}][type]" required>
                                            <option value="">Select Type</option>
                                            <option value="existing" {{ ($existingMixed[$i]['type'] ?? '') == 'existing' ? 'selected' : '' }}>
                                                Uses ShuleSoft
                                            </option>
                                            <option value="new" {{ ($existingMixed[$i]['type'] ?? '') == 'new' ? 'selected' : '' }}>
                                                Needs Onboarding
                                            </option>
                                        </select>
                                    </div>
                                    <div class="col-md-8">
                                        <div class="existing-school-fields" style="display: {{ ($existingMixed[$i]['type'] ?? '') == 'existing' ? 'block' : 'none' }}">
                                            <label class="form-label">ShuleSoft Login Code</label>
                                            <input type="text" 
                                                   class="form-control" 
                                                   name="mixed_schools[{{ $i }}][login_code]" 
                                                   value="{{ $existingMixed[$i]['login_code'] ?? '' }}" 
                                                   placeholder="e.g., ACADEMY001">
                                        </div>
                                        <div class="new-school-fields" style="display: {{ ($existingMixed[$i]['type'] ?? '') == 'new' ? 'block' : 'none' }}">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <label class="form-label">School Name</label>
                                                    <input type="text" 
                                                           class="form-control" 
                                                           name="mixed_schools[{{ $i }}][school_name]" 
                                                           value="{{ $existingMixed[$i]['school_name'] ?? '' }}" 
                                                           placeholder="School Name">
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label">Location</label>
                                                    <input type="text" 
                                                           class="form-control" 
                                                           name="mixed_schools[{{ $i }}][location]" 
                                                           value="{{ $existingMixed[$i]['location'] ?? '' }}" 
                                                           placeholder="City, County">
                                                </div>
                                            </div>
                                            <div class="row mt-2">
                                                <div class="col-md-4">
                                                    <label class="form-label">Contact Person</label>
                                                    <input type="text" 
                                                           class="form-control" 
                                                           name="mixed_schools[{{ $i }}][contact_person]" 
                                                           value="{{ $existingMixed[$i]['contact_person'] ?? '' }}" 
                                                           placeholder="Principal Name">
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label">Contact Email</label>
                                                    <input type="email" 
                                                           class="form-control" 
                                                           name="mixed_schools[{{ $i }}][contact_email]" 
                                                           value="{{ $existingMixed[$i]['contact_email'] ?? '' }}" 
                                                           placeholder="principal@school.edu">
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label">Contact Phone</label>
                                                    <input type="tel" 
                                                           class="form-control" 
                                                           name="mixed_schools[{{ $i }}][contact_phone]" 
                                                           value="{{ $existingMixed[$i]['contact_phone'] ?? '' }}" 
                                                           placeholder="+254712345678">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @if($i > 0)
                                    <div class="text-end mt-2">
                                        <button type="button" class="btn btn-outline-danger btn-sm remove-school">
                                            <i class="fas fa-trash"></i> Remove School
                                        </button>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endfor
                </div>
                
                <button type="button" class="btn btn-outline-primary btn-sm" id="add-mixed-school">
                    <i class="fas fa-plus me-2"></i>Add Another School
                </button>
            </div>
            
        @else
            {{-- No schools use ShuleSoft --}}
            <div id="new-schools-section">
                <h5 class="mb-3">
                    <i class="fas fa-plus-circle text-info me-2"></i>
                    Schools to Onboard
                </h5>
                <p class="text-muted mb-3">Provide details for each school that will be onboarded to ShuleSoft:</p>
                
                <div id="new-schools-container">
                    @php
                        $existingNew = old('new_schools', $data['new_schools'] ?? []);
                        $schoolCount = max(1, count($existingNew), $data['schools_count'] ?? 2);
                    @endphp
                    
                    @for($i = 0; $i < $schoolCount; $i++)
                        <div class="school-item card mb-3">
                            <div class="card-body">
                                <h6 class="card-title">School {{ $i + 1 }}</h6>
                                <div class="row">
                                    <div class="col-md-6">
                                        <label class="form-label">School Name *</label>
                                        <input type="text" 
                                               class="form-control @error('new_schools.'.$i.'.school_name') is-invalid @enderror" 
                                               name="new_schools[{{ $i }}][school_name]" 
                                               value="{{ $existingNew[$i]['school_name'] ?? '' }}" 
                                               placeholder="Academy Primary School"
                                               required>
                                        @error('new_schools.'.$i.'.school_name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Location *</label>
                                        <input type="text" 
                                               class="form-control @error('new_schools.'.$i.'.location') is-invalid @enderror" 
                                               name="new_schools[{{ $i }}][location]" 
                                               value="{{ $existingNew[$i]['location'] ?? '' }}" 
                                               placeholder="Nairobi, Kenya"
                                               required>
                                        @error('new_schools.'.$i.'.location')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col-md-4">
                                        <label class="form-label">Contact Person *</label>
                                        <input type="text" 
                                               class="form-control @error('new_schools.'.$i.'.contact_person') is-invalid @enderror" 
                                               name="new_schools[{{ $i }}][contact_person]" 
                                               value="{{ $existingNew[$i]['contact_person'] ?? '' }}" 
                                               placeholder="Principal Name"
                                               required>
                                        @error('new_schools.'.$i.'.contact_person')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Contact Email *</label>
                                        <input type="email" 
                                               class="form-control @error('new_schools.'.$i.'.contact_email') is-invalid @enderror" 
                                               name="new_schools[{{ $i }}][contact_email]" 
                                               value="{{ $existingNew[$i]['contact_email'] ?? '' }}" 
                                               placeholder="principal@school.edu"
                                               required>
                                        @error('new_schools.'.$i.'.contact_email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Contact Phone *</label>
                                        <input type="tel" 
                                               class="form-control @error('new_schools.'.$i.'.contact_phone') is-invalid @enderror" 
                                               name="new_schools[{{ $i }}][contact_phone]" 
                                               value="{{ $existingNew[$i]['contact_phone'] ?? '' }}" 
                                               placeholder="+254712345678"
                                               required>
                                        @error('new_schools.'.$i.'.contact_phone')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                @if($i > 0)
                                    <div class="text-end mt-2">
                                        <button type="button" class="btn btn-outline-danger btn-sm remove-school">
                                            <i class="fas fa-trash"></i> Remove School
                                        </button>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endfor
                </div>
                
                <button type="button" class="btn btn-outline-primary btn-sm" id="add-new-school">
                    <i class="fas fa-plus me-2"></i>Add Another School
                </button>
            </div>
        @endif
        
        <div class="d-flex justify-content-between mt-5">
            <a href="{{ route('onboarding.step2') }}" class="btn btn-secondary">
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
        let schoolCounter = {{ $schoolCount ?? 1 }};
        
        // Handle school type changes for mixed environment
        $(document).on('change', '.school-type', function() {
            const selectedType = $(this).val();
            const container = $(this).closest('.school-item');
            
            if (selectedType === 'existing') {
                container.find('.existing-school-fields').show();
                container.find('.new-school-fields').hide();
            } else if (selectedType === 'new') {
                container.find('.existing-school-fields').hide();
                container.find('.new-school-fields').show();
            } else {
                container.find('.existing-school-fields').hide();
                container.find('.new-school-fields').hide();
            }
        });
        
        // Add new ShuleSoft school
        $('#add-shulesoft-school').on('click', function() {
            const newSchool = `
                <div class="school-item card mb-3">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-8">
                                <label class="form-label">School ${schoolCounter + 1} Login Code *</label>
                                <input type="text" 
                                       class="form-control" 
                                       name="shulesoft_schools[${schoolCounter}][login_code]" 
                                       placeholder="e.g., ACADEMY00${schoolCounter + 1}"
                                       required>
                            </div>
                            <div class="col-md-4 d-flex align-items-end">
                                <button type="button" class="btn btn-outline-danger btn-sm remove-school">
                                    <i class="fas fa-trash"></i> Remove
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            `;
            $('#shulesoft-schools-container').append(newSchool);
            schoolCounter++;
        });
        
        // Add new mixed school
        $('#add-mixed-school').on('click', function() {
            const newSchool = `
                <div class="school-item card mb-3">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <label class="form-label">School ${schoolCounter + 1} Type *</label>
                                <select class="form-control school-type" name="mixed_schools[${schoolCounter}][type]" required>
                                    <option value="">Select Type</option>
                                    <option value="existing">Uses ShuleSoft</option>
                                    <option value="new">Needs Onboarding</option>
                                </select>
                            </div>
                            <div class="col-md-8">
                                <div class="existing-school-fields" style="display: none">
                                    <label class="form-label">ShuleSoft Login Code</label>
                                    <input type="text" class="form-control" name="mixed_schools[${schoolCounter}][login_code]" placeholder="e.g., ACADEMY001">
                                </div>
                                <div class="new-school-fields" style="display: none">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label class="form-label">School Name</label>
                                            <input type="text" class="form-control" name="mixed_schools[${schoolCounter}][school_name]" placeholder="School Name">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Location</label>
                                            <input type="text" class="form-control" name="mixed_schools[${schoolCounter}][location]" placeholder="City, County">
                                        </div>
                                    </div>
                                    <div class="row mt-2">
                                        <div class="col-md-4">
                                            <label class="form-label">Contact Person</label>
                                            <input type="text" class="form-control" name="mixed_schools[${schoolCounter}][contact_person]" placeholder="Principal Name">
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">Contact Email</label>
                                            <input type="email" class="form-control" name="mixed_schools[${schoolCounter}][contact_email]" placeholder="principal@school.edu">
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">Contact Phone</label>
                                            <input type="tel" class="form-control" name="mixed_schools[${schoolCounter}][contact_phone]" placeholder="+254712345678">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="text-end mt-2">
                            <button type="button" class="btn btn-outline-danger btn-sm remove-school">
                                <i class="fas fa-trash"></i> Remove School
                            </button>
                        </div>
                    </div>
                </div>
            `;
            $('#mixed-schools-container').append(newSchool);
            schoolCounter++;
        });
        
        // Add new school for onboarding
        $('#add-new-school').on('click', function() {
            const newSchool = `
                <div class="school-item card mb-3">
                    <div class="card-body">
                        <h6 class="card-title">School ${schoolCounter + 1}</h6>
                        <div class="row">
                            <div class="col-md-6">
                                <label class="form-label">School Name *</label>
                                <input type="text" class="form-control" name="new_schools[${schoolCounter}][school_name]" placeholder="Academy Primary School" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Location *</label>
                                <input type="text" class="form-control" name="new_schools[${schoolCounter}][location]" placeholder="Nairobi, Kenya" required>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-md-4">
                                <label class="form-label">Contact Person *</label>
                                <input type="text" class="form-control" name="new_schools[${schoolCounter}][contact_person]" placeholder="Principal Name" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Contact Email *</label>
                                <input type="email" class="form-control" name="new_schools[${schoolCounter}][contact_email]" placeholder="principal@school.edu" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Contact Phone *</label>
                                <input type="tel" class="form-control" name="new_schools[${schoolCounter}][contact_phone]" placeholder="+254712345678" required>
                            </div>
                        </div>
                        <div class="text-end mt-2">
                            <button type="button" class="btn btn-outline-danger btn-sm remove-school">
                                <i class="fas fa-trash"></i> Remove School
                            </button>
                        </div>
                    </div>
                </div>
            `;
            $('#new-schools-container').append(newSchool);
            schoolCounter++;
        });
        
        // Remove school
        $(document).on('click', '.remove-school', function() {
            $(this).closest('.school-item').remove();
        });
        
        // Form validation
        $('#step3Form').on('submit', function(e) {
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
            
            if (!isValid) {
                e.preventDefault();
                alert('Please complete all required fields.');
            }
        });
    });
</script>
@endsection
