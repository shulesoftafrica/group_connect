@extends('layouts.admin')

@section('title', 'AI Exams Management')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">AI Exams Management</h1>
            <p class="mb-0 text-muted">Create, manage, and distribute AI-generated exams across all schools</p>
        </div>
        <div class="d-flex gap-2">
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createAIExamModal">
                <i class="fas fa-robot me-2"></i>Create AI Exam
            </button>
            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#bulkAssignModal">
                <i class="fas fa-tasks me-2"></i>Bulk Assign
            </button>
            <button class="btn btn-info" data-bs-toggle="modal" data-bs-target="#examTemplateModal">
                <i class="fas fa-layer-group me-2"></i>Exam Templates
            </button>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total AI Exams</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $total_exams }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-robot fa-2x text-gray-300"></i>
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
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Active Exams</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $active_exams }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-play fa-2x text-gray-300"></i>
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
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Total Participants</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($total_participants) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users fa-2x text-gray-300"></i>
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
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Avg Completion</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $avg_completion }}%</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-percentage fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters and Search -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <h6 class="m-0 font-weight-bold text-primary">Filter Exams</h6>
                </div>
                <div class="col-lg-6">
                    <div class="row">
                        <div class="col-md-4">
                            <select class="form-select form-select-sm" id="statusFilter">
                                <option value="">All Status</option>
                                <option value="Draft">Draft</option>
                                <option value="Scheduled">Scheduled</option>
                                <option value="In Progress">In Progress</option>
                                <option value="Completed">Completed</option>
                                <option value="Archived">Archived</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <select class="form-select form-select-sm" id="subjectFilter">
                                <option value="">All Subjects</option>
                                <option value="Mathematics">Mathematics</option>
                                <option value="English">English</option>
                                <option value="Science">Science</option>
                                <option value="History">History</option>
                                <option value="Geography">Geography</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <div class="input-group input-group-sm">
                                <input type="text" class="form-control" placeholder="Search exams..." id="examSearch">
                                <button class="btn btn-outline-secondary" type="button">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- AI Exams List -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">AI-Generated Exams</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover" id="examsTable">
                    <thead class="table-light">
                        <tr>
                            <th>
                                <input type="checkbox" class="form-check-input" id="selectAll">
                            </th>
                            <th>Exam Details</th>
                            <th>Class/Subject</th>
                            <th>Duration</th>
                            <th>Schedule</th>
                            <th>Schools Assigned</th>
                            <th>Participation</th>
                            <th>AI Generated</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($exams as $exam)
                        <tr>
                            <td>
                                <input type="checkbox" class="form-check-input exam-checkbox" value="{{ $exam['id'] }}">
                            </td>
                            <td>
                                <div>
                                    <h6 class="mb-1">{{ $exam['title'] }}</h6>
                                    <small class="text-muted">{{ $exam['description'] }}</small>
                                    <div class="mt-1">
                                        <span class="badge bg-secondary">{{ $exam['questions_count'] }} Questions</span>
                                        <span class="badge bg-info">{{ $exam['total_marks'] }} Marks</span>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="text-sm">
                                    <strong>{{ $exam['class_level'] }}</strong><br>
                                    <span class="text-muted">{{ $exam['subject'] }}</span>
                                </div>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-warning">{{ $exam['duration'] }}min</span>
                            </td>
                            <td>
                                <div class="text-sm">
                                    <strong>{{ $exam['exam_date'] }}</strong><br>
                                    <span class="text-muted">{{ $exam['exam_time'] }}</span>
                                </div>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-primary">{{ $exam['schools_count'] }}</span>
                                <div class="text-xs text-muted mt-1">schools</div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <span class="me-2">{{ $exam['participation_rate'] }}%</span>
                                    <div class="progress" style="width: 60px; height: 6px;">
                                        <div class="progress-bar bg-success" style="width: {{ $exam['participation_rate'] }}%"></div>
                                    </div>
                                </div>
                                <div class="text-xs text-muted">{{ $exam['participants'] }}/{{ $exam['eligible_students'] }}</div>
                            </td>
                            <td class="text-center">
                                <div>
                                    <i class="fas fa-robot text-primary"></i>
                                    <div class="text-xs text-muted">{{ $exam['ai_generated_at'] }}</div>
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-{{ $exam['status'] == 'Scheduled' ? 'warning' : ($exam['status'] == 'In Progress' ? 'info' : ($exam['status'] == 'Completed' ? 'success' : 'secondary')) }}">
                                    {{ $exam['status'] }}
                                </span>
                                @if($exam['status'] == 'Scheduled')
                                <div class="text-xs text-muted mt-1">{{ $exam['time_until_start'] }}</div>
                                @endif
                            </td>
                            <td>
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown">
                                        <i class="fas fa-cog"></i>
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item" href="#" onclick="viewExam('{{ $exam['id'] }}')">
                                            <i class="fas fa-eye me-2"></i>View Details
                                        </a></li>
                                        <li><a class="dropdown-item" href="#" onclick="editExam('{{ $exam['id'] }}')">
                                            <i class="fas fa-edit me-2"></i>Edit Exam
                                        </a></li>
                                        <li><a class="dropdown-item" href="#" onclick="duplicateExam('{{ $exam['id'] }}')">
                                            <i class="fas fa-copy me-2"></i>Duplicate
                                        </a></li>
                                        <li><a class="dropdown-item" href="#" onclick="assignToSchools('{{ $exam['id'] }}')">
                                            <i class="fas fa-school me-2"></i>Assign to Schools
                                        </a></li>
                                        @if($exam['status'] == 'Scheduled')
                                        <li><a class="dropdown-item" href="#" onclick="startExam('{{ $exam['id'] }}')">
                                            <i class="fas fa-play me-2"></i>Start Exam
                                        </a></li>
                                        @endif
                                        @if($exam['status'] == 'In Progress')
                                        <li><a class="dropdown-item" href="#" onclick="endExam('{{ $exam['id'] }}')">
                                            <i class="fas fa-stop me-2"></i>End Exam
                                        </a></li>
                                        @endif
                                        @if($exam['status'] == 'Completed')
                                        <li><a class="dropdown-item" href="#" onclick="viewResults('{{ $exam['id'] }}')">
                                            <i class="fas fa-chart-bar me-2"></i>View Results
                                        </a></li>
                                        @endif
                                        <li><hr class="dropdown-divider"></li>
                                        <li><a class="dropdown-item text-danger" href="#" onclick="deleteExam('{{ $exam['id'] }}')">
                                            <i class="fas fa-trash me-2"></i>Delete
                                        </a></li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Bulk Actions -->
    <div class="d-none" id="bulkActionsBar">
        <div class="card shadow mb-4">
            <div class="card-body py-2">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <span id="selectedCount">0</span> exams selected
                    </div>
                    <div class="d-flex gap-2">
                        <button class="btn btn-sm btn-outline-primary" onclick="bulkAssign()">
                            <i class="fas fa-school me-1"></i>Assign to Schools
                        </button>
                        <button class="btn btn-sm btn-outline-success" onclick="bulkSchedule()">
                            <i class="fas fa-calendar me-1"></i>Schedule
                        </button>
                        <button class="btn btn-sm btn-outline-warning" onclick="bulkArchive()">
                            <i class="fas fa-archive me-1"></i>Archive
                        </button>
                        <button class="btn btn-sm btn-outline-danger" onclick="bulkDelete()">
                            <i class="fas fa-trash me-1"></i>Delete
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Create AI Exam Modal -->
<div class="modal fade" id="createAIExamModal" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Create AI-Generated Exam</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="createAIExamForm">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="examTitle" class="form-label">Exam Title *</label>
                                        <input type="text" class="form-control" id="examTitle" name="exam_title" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="classLevel" class="form-label">Class Level *</label>
                                        <select class="form-select" id="classLevel" name="class_level" required>
                                            <option value="">Select Class</option>
                                            <option value="Grade 8">Grade 8</option>
                                            <option value="Grade 9">Grade 9</option>
                                            <option value="Grade 10">Grade 10</option>
                                            <option value="Grade 11">Grade 11</option>
                                            <option value="Grade 12">Grade 12</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="subject" class="form-label">Subject *</label>
                                        <select class="form-select" id="subject" name="subject" required>
                                            <option value="">Select Subject</option>
                                            <option value="Mathematics">Mathematics</option>
                                            <option value="English">English</option>
                                            <option value="Science">Science</option>
                                            <option value="History">History</option>
                                            <option value="Geography">Geography</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="examDuration" class="form-label">Duration (minutes) *</label>
                                        <input type="number" class="form-control" id="examDuration" name="duration" required>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="examDate" class="form-label">Exam Date *</label>
                                        <input type="date" class="form-control" id="examDate" name="exam_date" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="examTime" class="form-label">Exam Time *</label>
                                        <input type="time" class="form-control" id="examTime" name="exam_time" required>
                                    </div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="examDescription" class="form-label">Description</label>
                                <textarea class="form-control" id="examDescription" name="description" rows="3" placeholder="Brief description of the exam..."></textarea>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="mb-0">AI Generation Settings</h6>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label for="questionsCount" class="form-label">Number of Questions</label>
                                        <input type="number" class="form-control" id="questionsCount" name="questions_count" value="20" min="5" max="100">
                                    </div>
                                    <div class="mb-3">
                                        <label for="difficultyLevel" class="form-label">Difficulty Level</label>
                                        <select class="form-select" id="difficultyLevel" name="difficulty_level">
                                            <option value="Easy">Easy</option>
                                            <option value="Medium" selected>Medium</option>
                                            <option value="Hard">Hard</option>
                                            <option value="Mixed">Mixed</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Question Types</label>
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" id="multipleChoice" name="question_types[]" value="multiple_choice" checked>
                                            <label class="form-check-label" for="multipleChoice">Multiple Choice</label>
                                        </div>
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" id="trueFalse" name="question_types[]" value="true_false">
                                            <label class="form-check-label" for="trueFalse">True/False</label>
                                        </div>
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" id="shortAnswer" name="question_types[]" value="short_answer">
                                            <label class="form-check-label" for="shortAnswer">Short Answer</label>
                                        </div>
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" id="essay" name="question_types[]" value="essay">
                                            <label class="form-check-label" for="essay">Essay</label>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="topics" class="form-label">Specific Topics (Optional)</label>
                                        <textarea class="form-control" id="topics" name="topics" rows="3" placeholder="Enter specific topics to focus on..."></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="mb-0">School Assignment</h6>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label for="targetSchools" class="form-label">Target Schools *</label>
                                            <select class="form-select" id="targetSchools" name="target_schools[]" multiple required style="height: 150px;">
                                                @foreach($schools as $school)
                                                <option value="{{ $school['id'] }}">{{ $school['name'] }} ({{ $school['location'] }})</option>
                                                @endforeach
                                            </select>
                                            <div class="form-text">Hold Ctrl to select multiple schools</div>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Quick Select</label>
                                            <div class="d-grid gap-2">
                                                <button type="button" class="btn btn-outline-primary btn-sm" onclick="selectAllSchools()">Select All Schools</button>
                                                <button type="button" class="btn btn-outline-secondary btn-sm" onclick="selectByRegion('North')">Select North Region</button>
                                                <button type="button" class="btn btn-outline-secondary btn-sm" onclick="selectByRegion('South')">Select South Region</button>
                                                <button type="button" class="btn btn-outline-secondary btn-sm" onclick="selectByRegion('East')">Select East Region</button>
                                                <button type="button" class="btn btn-outline-secondary btn-sm" onclick="selectByRegion('West')">Select West Region</button>
                                                <button type="button" class="btn btn-outline-warning btn-sm" onclick="clearSelection()">Clear Selection</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="generateAIExam()">
                    <i class="fas fa-robot me-2"></i>Generate AI Exam
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Exam Templates Modal -->
<div class="modal fade" id="examTemplateModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">AI Exam Templates</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    @foreach($exam_templates as $template)
                    <div class="col-md-6 mb-3">
                        <div class="card border">
                            <div class="card-body">
                                <h6 class="card-title">{{ $template['name'] }}</h6>
                                <p class="card-text text-muted">{{ $template['description'] }}</p>
                                <div class="mb-2">
                                    <small class="text-muted">
                                        <i class="fas fa-clock me-1"></i>{{ $template['duration'] }}min •
                                        <i class="fas fa-question-circle me-1"></i>{{ $template['questions'] }} questions •
                                        <i class="fas fa-layer-group me-1"></i>{{ $template['difficulty'] }}
                                    </small>
                                </div>
                                <button class="btn btn-sm btn-primary" onclick="useTemplate('{{ $template['id'] }}')">
                                    Use Template
                                </button>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Selection handling
document.getElementById('selectAll').addEventListener('change', function() {
    const checkboxes = document.querySelectorAll('.exam-checkbox');
    checkboxes.forEach(checkbox => {
        checkbox.checked = this.checked;
    });
    updateBulkActions();
});

document.addEventListener('change', function(e) {
    if (e.target.classList.contains('exam-checkbox')) {
        updateBulkActions();
    }
});

function updateBulkActions() {
    const selectedCheckboxes = document.querySelectorAll('.exam-checkbox:checked');
    const bulkActionsBar = document.getElementById('bulkActionsBar');
    const selectedCount = document.getElementById('selectedCount');
    
    if (selectedCheckboxes.length > 0) {
        bulkActionsBar.classList.remove('d-none');
        selectedCount.textContent = selectedCheckboxes.length;
    } else {
        bulkActionsBar.classList.add('d-none');
    }
}

// School selection helpers
function selectAllSchools() {
    const select = document.getElementById('targetSchools');
    for (let option of select.options) {
        option.selected = true;
    }
}

function selectByRegion(region) {
    const select = document.getElementById('targetSchools');
    for (let option of select.options) {
        if (option.text.includes(region)) {
            option.selected = true;
        }
    }
}

function clearSelection() {
    const select = document.getElementById('targetSchools');
    for (let option of select.options) {
        option.selected = false;
    }
}

// Exam actions
function viewExam(examId) {
    alert('Opening exam details for exam ID: ' + examId);
}

function editExam(examId) {
    alert('Opening exam editor for exam ID: ' + examId);
}

function duplicateExam(examId) {
    if (confirm('Are you sure you want to duplicate this exam?')) {
        alert('Exam duplicated successfully!');
    }
}

function assignToSchools(examId) {
    alert('Opening school assignment for exam ID: ' + examId);
}

function startExam(examId) {
    if (confirm('Are you sure you want to start this exam? Students will be able to access it immediately.')) {
        alert('Exam started successfully!');
    }
}

function endExam(examId) {
    if (confirm('Are you sure you want to end this exam? This action cannot be undone.')) {
        alert('Exam ended successfully!');
    }
}

function viewResults(examId) {
    alert('Opening results dashboard for exam ID: ' + examId);
}

function deleteExam(examId) {
    if (confirm('Are you sure you want to delete this exam? This action cannot be undone.')) {
        alert('Exam deleted successfully!');
    }
}

// Bulk actions
function bulkAssign() {
    const selectedExams = document.querySelectorAll('.exam-checkbox:checked');
    if (selectedExams.length === 0) {
        alert('Please select at least one exam.');
        return;
    }
    alert('Opening bulk assignment for ' + selectedExams.length + ' exams');
}

function bulkSchedule() {
    const selectedExams = document.querySelectorAll('.exam-checkbox:checked');
    if (selectedExams.length === 0) {
        alert('Please select at least one exam.');
        return;
    }
    alert('Opening bulk scheduler for ' + selectedExams.length + ' exams');
}

function bulkArchive() {
    const selectedExams = document.querySelectorAll('.exam-checkbox:checked');
    if (selectedExams.length === 0) {
        alert('Please select at least one exam.');
        return;
    }
    if (confirm('Are you sure you want to archive ' + selectedExams.length + ' exams?')) {
        alert('Exams archived successfully!');
    }
}

function bulkDelete() {
    const selectedExams = document.querySelectorAll('.exam-checkbox:checked');
    if (selectedExams.length === 0) {
        alert('Please select at least one exam.');
        return;
    }
    if (confirm('Are you sure you want to delete ' + selectedExams.length + ' exams? This action cannot be undone.')) {
        alert('Exams deleted successfully!');
    }
}

// Generate AI Exam
function generateAIExam() {
    const form = document.getElementById('createAIExamForm');
    if (!form.checkValidity()) {
        form.reportValidity();
        return;
    }
    
    // Show loading state
    const button = event.target;
    const originalText = button.innerHTML;
    button.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Generating...';
    button.disabled = true;
    
    // Simulate AI generation
    setTimeout(() => {
        alert('AI Exam generated and assigned successfully!');
        $('#createAIExamModal').modal('hide');
        form.reset();
        button.innerHTML = originalText;
        button.disabled = false;
        // Refresh page to show new exam
        location.reload();
    }, 3000);
}

// Use template
function useTemplate(templateId) {
    alert('Loading template: ' + templateId);
    $('#examTemplateModal').modal('hide');
    $('#createAIExamModal').modal('show');
}

// Filters
document.getElementById('statusFilter').addEventListener('change', function() {
    filterTable();
});

document.getElementById('subjectFilter').addEventListener('change', function() {
    filterTable();
});

document.getElementById('examSearch').addEventListener('keyup', function() {
    filterTable();
});

function filterTable() {
    const statusFilter = document.getElementById('statusFilter').value;
    const subjectFilter = document.getElementById('subjectFilter').value;
    const searchTerm = document.getElementById('examSearch').value.toLowerCase();
    const rows = document.querySelectorAll('#examsTable tbody tr');
    
    rows.forEach(row => {
        const status = row.cells[8].textContent.trim();
        const subject = row.cells[2].textContent.toLowerCase();
        const title = row.cells[1].textContent.toLowerCase();
        
        const matchesStatus = statusFilter === '' || status === statusFilter;
        const matchesSubject = subjectFilter === '' || subject.includes(subjectFilter.toLowerCase());
        const matchesSearch = searchTerm === '' || title.includes(searchTerm);
        
        if (matchesStatus && matchesSubject && matchesSearch) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
}
</script>
@endpush
