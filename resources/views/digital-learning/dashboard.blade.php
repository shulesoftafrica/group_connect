@extends('layouts.digital-learning')

@section('title', 'AI Digital Learning Dashboard')
@section('page-title', 'Dashboard')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">AI Digital Learning Dashboard</h1>
            <p class="mb-0 text-muted">Monitor and manage digital learning and AI-powered academic tools across all schools</p>
        </div>
        <div class="d-flex gap-2">
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createAIExamModal">
                <i class="fas fa-robot me-2"></i>Create AI Exam
            </button>
            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#generateAIContentModal">
                <i class="fas fa-magic me-2"></i>Generate AI Content
            </button>
            <button class="btn btn-info" data-bs-toggle="modal" data-bs-target="#bulkContentModal">
                <i class="fas fa-upload me-2"></i>Bulk Content Push
            </button>
        </div>
    </div>

    <!-- KPI Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Digital Content
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ number_format($total_digital_content) }}
                            </div>
                            <div class="text-xs text-success">
                                <i class="fas fa-arrow-up"></i> {{ $monthly_growth }}% this month
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-file-alt fa-2x text-gray-300"></i>
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
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Active AI Exams
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $active_ai_exams }}</div>
                            <div class="text-xs text-muted">{{ $avg_exam_completion }}% avg completion</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-robot fa-2x text-gray-300"></i>
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
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Engagement Rate
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $avg_engagement_rate }}%</div>
                            <div class="text-xs text-muted">Across {{ $schools_using_digital }} schools</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-chart-line fa-2x text-gray-300"></i>
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
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                AI-Generated Content
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $ai_generated_content }}</div>
                            <div class="text-xs text-muted">{{ $content_uploads_this_month }} uploads this month</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-magic fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="row mb-4">
        <div class="col-xl-8 col-lg-7">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Digital Learning Trends</h6>
                    <div class="dropdown no-arrow">
                        <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-bs-toggle="dropdown">
                            <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right">
                            <a class="dropdown-item" href="#" onclick="updateChart('content')">Content Uploads</a>
                            <a class="dropdown-item" href="#" onclick="updateChart('engagement')">Engagement Rates</a>
                            <a class="dropdown-item" href="#" onclick="updateChart('ai')">AI Usage</a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <!-- <canvas id="digitalLearningTrendsChart" width="100%" height="40"></canvas> -->
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-lg-5">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Content Types Distribution</h6>
                </div>
                <div class="card-body">
                    <!-- <canvas id="contentTypesChart" width="100%" height="150"></canvas> -->
                    <div class="mt-3">
                        <?php $content_types =[]?>
                        @foreach($content_types as $type => $data)
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="text-sm">{{ $type }}</span>
                            <div class="d-flex align-items-center">
                                <span class="text-sm font-weight-bold me-2">{{ number_format($data['count']) }}</span>
                                <span class="badge bg-primary">{{ $data['percentage'] }}%</span>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- AI Exams and Content Statistics -->
    <div class="row mb-4">
        <div class="col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">Active AI Exams</h6>
                    <a href="{{ route('digital-learning.exams') }}" class="btn btn-sm btn-outline-primary">View All</a>
                </div>
                <div class="card-body">
                    @foreach($aiExams as $exam)
                    <div class="d-flex align-items-center justify-content-between p-3 border rounded mb-3">
                        <div class="d-flex align-items-center">
                            <div class="me-3">
                                <i class="fas fa-{{ $exam['status'] == 'Scheduled' ? 'clock' : ($exam['status'] == 'In Progress' ? 'play' : 'check') }} fa-lg text-{{ $exam['status'] == 'Scheduled' ? 'warning' : ($exam['status'] == 'In Progress' ? 'info' : 'success') }}"></i>
                            </div>
                            <div>
                                <h6 class="mb-1">{{ $exam['title'] }}</h6>
                                <p class="text-muted mb-1">{{ $exam['class_level'] }} • {{ $exam['subject'] }}</p>
                                <small class="text-muted">{{ $exam['exam_date'] }} at {{ $exam['exam_time'] }}</small>
                            </div>
                        </div>
                        <div class="text-center">
                            <span class="badge bg-{{ $exam['status'] == 'Scheduled' ? 'warning' : ($exam['status'] == 'In Progress' ? 'info' : 'success') }}">
                                {{ $exam['status'] }}
                            </span>
                            <p class="text-sm text-muted mb-0 mt-1">{{ $exam['schools_assigned'] }} schools</p>
                            @if($exam['completion_rate'] > 0)
                            <small class="text-success">{{ $exam['completion_rate'] }}% completed</small>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">Recent Content Activity</h6>
                    <a href="{{ route('digital-learning.content') }}" class="btn btn-sm btn-outline-primary">Manage Content</a>
                </div>
                <div class="card-body">
                    @foreach($contentStats['recent_uploads'] as $content)
                    <div class="d-flex align-items-center justify-content-between p-3 border rounded mb-3">
                        <div class="d-flex align-items-center">
                            <div class="me-3">
                                <i class="fas fa-{{ $content['type'] == 'AI Notes' ? 'robot' : ($content['type'] == 'Video' ? 'video' : 'file-alt') }} fa-lg text-primary"></i>
                            </div>
                            <div>
                                <h6 class="mb-1">{{ $content['title'] }}</h6>
                                <p class="text-muted mb-1">{{ $content['subject'] }} • {{ $content['class'] }}</p>
                                <small class="text-muted">{{ $content['upload_date'] }}</small>
                            </div>
                        </div>
                        <div class="text-center">
                            <span class="badge bg-{{ $content['type'] == 'AI Notes' ? 'warning' : 'info' }}">
                                {{ $content['type'] }}
                            </span>
                            <p class="text-sm text-muted mb-0 mt-1">{{ $content['schools_distributed'] }} schools</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- School Engagement Overview -->
    <div class="row mb-4">
        <div class="col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Engagement Levels</h6>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="text-sm">High Engagement</span>
                        <div class="d-flex align-items-center">
                            <span class="badge bg-success me-2">{{ $engagement_stats['high'] }}</span>
                            <div class="progress" style="width: 80px; height: 8px;">
                                <div class="progress-bar bg-success" style="width: {{ ($engagement_stats['high'] / array_sum($engagement_stats)) * 100 }}%"></div>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="text-sm">Medium Engagement</span>
                        <div class="d-flex align-items-center">
                            <span class="badge bg-warning me-2">{{ $engagement_stats['medium'] }}</span>
                            <div class="progress" style="width: 80px; height: 8px;">
                                <div class="progress-bar bg-warning" style="width: {{ ($engagement_stats['medium'] / array_sum($engagement_stats)) * 100 }}%"></div>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="text-sm">Low Engagement</span>
                        <div class="d-flex align-items-center">
                            <span class="badge bg-danger me-2">{{ $engagement_stats['low'] }}</span>
                            <div class="progress" style="width: 80px; height: 8px;">
                                <div class="progress-bar bg-danger" style="width: {{ ($engagement_stats['low'] / array_sum($engagement_stats)) * 100 }}%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Quick AI Actions</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <button class="btn btn-outline-primary w-100 p-3" onclick="quickAIExam()">
                                <i class="fas fa-robot fa-2x mb-2"></i>
                                <div>Generate Quick AI Exam</div>
                                <small class="text-muted">Create exam for any subject</small>
                            </button>
                        </div>
                        <div class="col-md-6 mb-3">
                            <button class="btn btn-outline-success w-100 p-3" onclick="quickAINotes()">
                                <i class="fas fa-magic fa-2x mb-2"></i>
                                <div>Generate AI Notes</div>
                                <small class="text-muted">Create study materials</small>
                            </button>
                        </div>
                        <div class="col-md-6 mb-3">
                            <button class="btn btn-outline-info w-100 p-3" onclick="viewAnalytics()">
                                <i class="fas fa-chart-bar fa-2x mb-2"></i>
                                <div>AI Performance Analytics</div>
                                <small class="text-muted">View detailed insights</small>
                            </button>
                        </div>
                        <div class="col-md-6 mb-3">
                            <button class="btn btn-outline-warning w-100 p-3" onclick="bulkContentPush()">
                                <i class="fas fa-upload fa-2x mb-2"></i>
                                <div>Bulk Content Distribution</div>
                                <small class="text-muted">Push to multiple schools</small>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Schools Digital Learning Overview -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Schools Digital Learning Overview</h6>
            <div class="d-flex gap-2">
                <div class="input-group" style="width: 250px;">
                    <input type="text" class="form-control form-control-sm" placeholder="Search schools..." id="schoolSearch">
                    <button class="btn btn-outline-secondary btn-sm" type="button">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
                <select class="form-select form-select-sm" style="width: 150px;" id="engagementFilter">
                    <option value="">All Engagement</option>
                    <option value="Active">Active</option>
                    <option value="Moderate">Moderate</option>
                </select>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover" id="schoolsTable">
                    <thead class="table-light">
                        <tr>
                            <th>School Name</th>
                            <th>Location</th>
                            <th>Digital Adoption</th>
                            <th>Content Uploads</th>
                            <th>AI Exam Participation</th>
                            <th>Engagement Rate</th>
                            <th>AI Tools Usage</th>
                            <th>Last Activity</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($schools as $school)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar avatar-sm me-2">
                                        <div class="avatar-title bg-primary rounded-circle">
                                            {{ substr($school['name'], 0, 1) }}
                                        </div>
                                    </div>
                                    <strong>{{ $school['name'] }}</strong>
                                </div>
                            </td>
                            <td class="text-muted">{{ $school['location'] }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <span class="me-2">{{ $school['digital_adoption'] }}</span>
                                    <div class="progress" style="width: 60px; height: 6px;">
                                        <div class="progress-bar bg-info" style="width: {{ $school['digital_adoption'] }}"></div>
                                    </div>
                                </div>
                            </td>
                            <td><span class="badge bg-info">{{ $school['content_uploads'] }}</span></td>
                            <td>{{ $school['ai_exam_participation'] }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <span class="me-2">{{ $school['engagement_rate'] }}</span>
                                    <div class="progress" style="width: 60px; height: 6px;">
                                        <div class="progress-bar bg-success" style="width: {{ $school['engagement_rate'] }}"></div>
                                    </div>
                                </div>
                            </td>
                            <td>{{ $school['ai_tools_usage'] }}</td>
                            <td class="text-muted">{{ $school['last_activity'] }}</td>
                            <td>
                                <span class="badge bg-{{ $school['digital_status'] == 'Active' ? 'success' : 'warning' }}">
                                    {{ $school['digital_status'] }}
                                </span>
                            </td>
                            <td>
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown">
                                        Actions
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item" href="#"><i class="fas fa-robot me-2"></i>Create AI Exam</a></li>
                                        <li><a class="dropdown-item" href="#"><i class="fas fa-upload me-2"></i>Push Content</a></li>
                                        <li><a class="dropdown-item" href="#"><i class="fas fa-chart-line me-2"></i>View Analytics</a></li>
                                        <li><a class="dropdown-item" href="#"><i class="fas fa-cog me-2"></i>Digital Settings</a></li>
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
</div>

<!-- Create AI Exam Modal -->
<div class="modal fade" id="createAIExamModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Create AI-Generated Exam</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="createAIExamForm">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="examTitle" class="form-label">Exam Title</label>
                                <input type="text" class="form-control" id="examTitle" name="exam_title" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="classLevel" class="form-label">Class Level</label>
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
                                <label for="subject" class="form-label">Subject</label>
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
                                <label for="examDuration" class="form-label">Duration (minutes)</label>
                                <input type="number" class="form-control" id="examDuration" name="duration" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="examDate" class="form-label">Exam Date</label>
                                <input type="date" class="form-control" id="examDate" name="exam_date" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="examTime" class="form-label">Exam Time</label>
                                <input type="time" class="form-control" id="examTime" name="exam_time" required>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="targetSchools" class="form-label">Target Schools</label>
                        <select class="form-select" id="targetSchools" name="target_schools[]" multiple required>
                            @foreach($schools as $school)
                            <option value="{{ $school['id'] }}">{{ $school['name'] }}</option>
                            @endforeach
                        </select>
                        <div class="form-text">Hold Ctrl to select multiple schools</div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="createAIExam()">Create AI Exam</button>
            </div>
        </div>
    </div>
</div>

<!-- Generate AI Content Modal -->
<div class="modal fade" id="generateAIContentModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Generate AI Content</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="generateAIContentForm">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="aiSubject" class="form-label">Subject</label>
                                <select class="form-select" id="aiSubject" name="subject" required>
                                    <option value="">Select Subject</option>
                                    <option value="Mathematics">Mathematics</option>
                                    <option value="English">English</option>
                                    <option value="Science">Science</option>
                                    <option value="History">History</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="aiClassLevel" class="form-label">Class Level</label>
                                <select class="form-select" id="aiClassLevel" name="class_level" required>
                                    <option value="">Select Class</option>
                                    <option value="Grade 8">Grade 8</option>
                                    <option value="Grade 9">Grade 9</option>
                                    <option value="Grade 10">Grade 10</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="topic" class="form-label">Topic</label>
                        <input type="text" class="form-control" id="topic" name="topic" placeholder="e.g., Algebra, Photosynthesis, etc." required>
                    </div>
                    <div class="mb-3">
                        <label for="contentType" class="form-label">Content Type</label>
                        <select class="form-select" id="contentType" name="content_type" required>
                            <option value="">Select Type</option>
                            <option value="notes">Study Notes</option>
                            <option value="revision">Revision Materials</option>
                            <option value="exercises">Practice Exercises</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="aiTargetSchools" class="form-label">Target Schools</label>
                        <select class="form-select" id="aiTargetSchools" name="target_schools[]" multiple required>
                            @foreach($schools as $school)
                            <option value="{{ $school['id'] }}">{{ $school['name'] }}</option>
                            @endforeach
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-success" onclick="generateAIContent()">Generate Content</button>
            </div>
        </div>
    </div>
</div>

<!-- Bulk Content Push Modal -->
<div class="modal fade" id="bulkContentModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Bulk Content Distribution</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="bulkContentForm">
                    <div class="mb-3">
                        <label for="bulkContentType" class="form-label">Content Type</label>
                        <select class="form-select" id="bulkContentType" name="content_type" required>
                            <option value="">Select Type</option>
                            <option value="notes">Class Notes</option>
                            <option value="videos">Videos</option>
                            <option value="assignments">Assignments</option>
                            <option value="exams">Exams</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="contentFiles" class="form-label">Content Files</label>
                        <input type="file" class="form-control" id="contentFiles" name="content_files" multiple required>
                        <div class="form-text">Select multiple files to upload</div>
                    </div>
                    <div class="mb-3">
                        <label for="bulkTargetSchools" class="form-label">Target Schools</label>
                        <select class="form-select" id="bulkTargetSchools" name="target_schools[]" multiple required>
                            @foreach($schools as $school)
                            <option value="{{ $school['id'] }}">{{ $school['name'] }}</option>
                            @endforeach
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-info" onclick="bulkContentPushAction()">Push Content</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Digital Learning Trends Chart
const ctx1 = document.getElementById('digitalLearningTrendsChart').getContext('2d');
new Chart(ctx1, {
    type: 'line',
    data: {
        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
        datasets: [{
            label: 'Content Uploads',
            data: [120, 145, 180, 160, 195, 220],
            borderColor: 'rgb(75, 192, 192)',
            backgroundColor: 'rgba(75, 192, 192, 0.1)',
            tension: 0.1
        }, {
            label: 'AI Content',
            data: [15, 28, 35, 42, 56, 89],
            borderColor: 'rgb(255, 99, 132)',
            backgroundColor: 'rgba(255, 99, 132, 0.1)',
            tension: 0.1
        }, {
            label: 'Exam Participation',
            data: [85, 88, 92, 89, 94, 96],
            borderColor: 'rgb(54, 162, 235)',
            backgroundColor: 'rgba(54, 162, 235, 0.1)',
            tension: 0.1
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});

// Content Types Chart
const ctx2 = document.getElementById('contentTypesChart').getContext('2d');
new Chart(ctx2, {
    type: 'doughnut',
    data: {
        labels: ['Class Notes', 'Videos', 'Assignments', 'AI-Generated'],
        datasets: [{
            data: [48.4, 26.4, 16.3, 8.9],
            backgroundColor: [
                'rgb(75, 192, 192)',
                'rgb(255, 99, 132)',
                'rgb(54, 162, 235)',
                'rgb(255, 206, 86)'
            ]
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                display: false
            }
        }
    }
});

// Quick action functions
function quickAIExam() {
    $('#createAIExamModal').modal('show');
}

function quickAINotes() {
    $('#generateAIContentModal').modal('show');
}

function viewAnalytics() {
    window.location.href = "{{ route('digital-learning.analytics') }}";
}

function bulkContentPush() {
    $('#bulkContentModal').modal('show');
}

// Form submission functions
function createAIExam() {
    const form = document.getElementById('createAIExamForm');
    if (!form.checkValidity()) {
        form.reportValidity();
        return;
    }
    
    alert('AI Exam created successfully!');
    $('#createAIExamModal').modal('hide');
    form.reset();
}

function generateAIContent() {
    const form = document.getElementById('generateAIContentForm');
    if (!form.checkValidity()) {
        form.reportValidity();
        return;
    }
    
    alert('AI Content generated and distributed successfully!');
    $('#generateAIContentModal').modal('hide');
    form.reset();
}

function bulkContentPushAction() {
    const form = document.getElementById('bulkContentForm');
    if (!form.checkValidity()) {
        form.reportValidity();
        return;
    }
    
    alert('Content pushed to selected schools successfully!');
    $('#bulkContentModal').modal('hide');
    form.reset();
}

// Search and filter functionality
document.getElementById('schoolSearch').addEventListener('keyup', function() {
    const searchTerm = this.value.toLowerCase();
    const rows = document.querySelectorAll('#schoolsTable tbody tr');
    
    rows.forEach(row => {
        const schoolName = row.cells[0].textContent.toLowerCase();
        if (schoolName.includes(searchTerm)) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
});

document.getElementById('engagementFilter').addEventListener('change', function() {
    const filterValue = this.value;
    const rows = document.querySelectorAll('#schoolsTable tbody tr');
    
    rows.forEach(row => {
        const status = row.cells[8].textContent.trim();
        if (filterValue === '' || status === filterValue) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
});

function updateChart(type) {
    console.log('Updating chart to show:', type);
    // In a real implementation, this would update the chart data
}
</script>
@endpush
