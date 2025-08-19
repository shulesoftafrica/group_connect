@extends('layouts.digital-learning')

@section('title', 'AI Tools Management')
@section('page-title', 'AI Tools')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">AI Tools Management</h1>
            <p class="mb-0 text-muted">Manage and monitor AI-powered learning tools across all schools</p>
        </div>
        <div class="d-flex gap-2">
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#configureAIModal">
                <i class="fas fa-cog me-2"></i>Configure AI
            </button>
            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#aiTrainingModal">
                <i class="fas fa-brain me-2"></i>AI Training
            </button>
            <button class="btn btn-info" onclick="viewAILogs()">
                <i class="fas fa-list-alt me-2"></i>AI Logs
            </button>
        </div>
    </div>

    <!-- AI Tools Status Overview -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">AI Tools Active</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $ai_tools_active }}</div>
                            <div class="text-xs text-success">
                                <i class="fas fa-check-circle"></i> {{ $ai_uptime }}% uptime
                            </div>
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
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">AI Requests Today</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($ai_requests_today) }}</div>
                            <div class="text-xs text-muted">{{ $avg_response_time }}ms avg response</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-chart-bar fa-2x text-gray-300"></i>
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
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Content Generated</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $ai_content_generated }}</div>
                            <div class="text-xs text-muted">{{ $content_generated_today }} today</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-magic fa-2x text-gray-300"></i>
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
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">AI Accuracy</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $ai_accuracy }}%</div>
                            <div class="text-xs text-muted">Based on {{ $accuracy_samples }} samples</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-bullseye fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- AI Tools Categories -->
    <div class="row mb-4">
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">AI Tools Categories</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        @foreach($ai_tool_categories as $category)
                        <div class="col-md-6 mb-4">
                            <div class="card border-left-{{ $category['color'] }} h-100">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <h6 class="mb-0">{{ $category['name'] }}</h6>
                                        <span class="badge bg-{{ $category['status'] == 'Active' ? 'success' : 'warning' }}">{{ $category['status'] }}</span>
                                    </div>
                                    <p class="text-muted small mb-2">{{ $category['description'] }}</p>
                                    <div class="mb-2">
                                        <small class="text-muted">
                                            <i class="fas fa-tools me-1"></i>{{ $category['tools_count'] }} tools •
                                            <i class="fas fa-chart-line me-1"></i>{{ $category['usage_rate'] }}% usage
                                        </small>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <button class="btn btn-sm btn-outline-primary" onclick="manageCategory('{{ $category['id'] }}')">
                                            Manage Tools
                                        </button>
                                        <small class="text-{{ $category['performance'] > 80 ? 'success' : 'warning' }}">
                                            {{ $category['performance'] }}% performance
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">AI System Health</h6>
                </div>
                <div class="card-body">
                    <canvas id="aiHealthChart" width="100%" height="200"></canvas>
                    <div class="mt-3">
                        @foreach($ai_health_metrics as $metric => $data)
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="text-sm">{{ $metric }}</span>
                            <div class="d-flex align-items-center">
                                <span class="text-sm font-weight-bold me-2">{{ $data['value'] }}{{ $data['unit'] }}</span>
                                <span class="badge bg-{{ $data['status'] == 'Good' ? 'success' : ($data['status'] == 'Warning' ? 'warning' : 'danger') }}">
                                    {{ $data['status'] }}
                                </span>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Individual AI Tools Management -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">AI Tools Management</h6>
            <div class="d-flex gap-2">
                <select class="form-select form-select-sm" id="categoryFilter" style="width: 150px;">
                    <option value="">All Categories</option>
                    <option value="Content Generation">Content Generation</option>
                    <option value="Assessment">Assessment</option>
                    <option value="Tutoring">Tutoring</option>
                    <option value="Analytics">Analytics</option>
                </select>
                <select class="form-select form-select-sm" id="statusFilter" style="width: 120px;">
                    <option value="">All Status</option>
                    <option value="Active">Active</option>
                    <option value="Inactive">Inactive</option>
                    <option value="Maintenance">Maintenance</option>
                </select>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover" id="aiToolsTable">
                    <thead class="table-light">
                        <tr>
                            <th>AI Tool</th>
                            <th>Category</th>
                            <th>Usage Rate</th>
                            <th>Performance</th>
                            <th>Last Updated</th>
                            <th>Schools Using</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($ai_tools as $tool)
                        <tr data-category="{{ $tool['category'] }}" data-status="{{ $tool['status'] }}">
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="me-3">
                                        <i class="fas fa-{{ $tool['icon'] }} fa-lg text-{{ $tool['color'] }}"></i>
                                    </div>
                                    <div>
                                        <h6 class="mb-0">{{ $tool['name'] }}</h6>
                                        <small class="text-muted">{{ $tool['description'] }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-info">{{ $tool['category'] }}</span>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <span class="me-2">{{ $tool['usage_rate'] }}%</span>
                                    <div class="progress" style="width: 60px; height: 6px;">
                                        <div class="progress-bar bg-primary" style="width: {{ $tool['usage_rate'] }}%"></div>
                                    </div>
                                </div>
                                <small class="text-muted">{{ number_format($tool['total_requests']) }} requests</small>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <span class="me-2">{{ $tool['performance'] }}%</span>
                                    <div class="progress" style="width: 60px; height: 6px;">
                                        <div class="progress-bar bg-{{ $tool['performance'] > 90 ? 'success' : ($tool['performance'] > 70 ? 'warning' : 'danger') }}" 
                                             style="width: {{ $tool['performance'] }}%"></div>
                                    </div>
                                </div>
                                <small class="text-muted">{{ $tool['avg_response_time'] }}ms avg</small>
                            </td>
                            <td>
                                <div class="text-sm">{{ $tool['last_updated'] }}</div>
                                <small class="text-muted">v{{ $tool['version'] }}</small>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-secondary">{{ $tool['schools_using'] }}</span>
                                <div class="text-xs text-muted">of {{ $total_schools }}</div>
                            </td>
                            <td>
                                <span class="badge bg-{{ $tool['status'] == 'Active' ? 'success' : ($tool['status'] == 'Maintenance' ? 'warning' : 'danger') }}">
                                    {{ $tool['status'] }}
                                </span>
                                @if($tool['status'] == 'Maintenance')
                                <div class="text-xs text-muted">{{ $tool['maintenance_eta'] }}</div>
                                @endif
                            </td>
                            <td>
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown">
                                        <i class="fas fa-cog"></i>
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item" href="#" onclick="configureAITool('{{ $tool['id'] }}')">
                                            <i class="fas fa-cog me-2"></i>Configure
                                        </a></li>
                                        <li><a class="dropdown-item" href="#" onclick="viewAIAnalytics('{{ $tool['id'] }}')">
                                            <i class="fas fa-chart-bar me-2"></i>Analytics
                                        </a></li>
                                        <li><a class="dropdown-item" href="#" onclick="trainAITool('{{ $tool['id'] }}')">
                                            <i class="fas fa-brain me-2"></i>Train Model
                                        </a></li>
                                        <li><a class="dropdown-item" href="#" onclick="testAITool('{{ $tool['id'] }}')">
                                            <i class="fas fa-play me-2"></i>Test Tool
                                        </a></li>
                                        <li><hr class="dropdown-divider"></li>
                                        @if($tool['status'] == 'Active')
                                        <li><a class="dropdown-item text-warning" href="#" onclick="toggleAITool('{{ $tool['id'] }}', 'disable')">
                                            <i class="fas fa-pause me-2"></i>Disable
                                        </a></li>
                                        @else
                                        <li><a class="dropdown-item text-success" href="#" onclick="toggleAITool('{{ $tool['id'] }}', 'enable')">
                                            <i class="fas fa-play me-2"></i>Enable
                                        </a></li>
                                        @endif
                                        <li><a class="dropdown-item text-danger" href="#" onclick="resetAITool('{{ $tool['id'] }}')">
                                            <i class="fas fa-undo me-2"></i>Reset
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

    <!-- AI Usage Analytics -->
    <div class="row mb-4">
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">AI Usage Trends</h6>
                    <div class="dropdown no-arrow">
                        <a class="dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right">
                            <a class="dropdown-item" href="#" onclick="updateUsageChart('requests')">Request Volume</a>
                            <a class="dropdown-item" href="#" onclick="updateUsageChart('performance')">Performance</a>
                            <a class="dropdown-item" href="#" onclick="updateUsageChart('accuracy')">Accuracy</a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <canvas id="aiUsageTrendsChart" width="100%" height="40"></canvas>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">AI Tool Performance</h6>
                </div>
                <div class="card-body">
                    @foreach($top_performing_tools as $tool)
                    <div class="d-flex align-items-center justify-content-between p-2 border rounded mb-2">
                        <div class="d-flex align-items-center">
                            <div class="me-2">
                                <i class="fas fa-{{ $tool['icon'] }} text-{{ $tool['color'] }}"></i>
                            </div>
                            <div>
                                <h6 class="mb-0 text-sm">{{ $tool['name'] }}</h6>
                                <small class="text-muted">{{ $tool['category'] }}</small>
                            </div>
                        </div>
                        <div class="text-center">
                            <div class="h6 mb-0 text-success">{{ $tool['performance'] }}%</div>
                            <small class="text-muted">{{ number_format($tool['requests']) }} req</small>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- AI Error Monitoring -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">AI Error Monitoring & Alerts</h6>
            <button class="btn btn-sm btn-outline-info" onclick="refreshErrorLogs()">
                <i class="fas fa-sync-alt me-1"></i>Refresh
            </button>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-sm">
                    <thead class="table-light">
                        <tr>
                            <th>Timestamp</th>
                            <th>AI Tool</th>
                            <th>Error Type</th>
                            <th>Severity</th>
                            <th>Message</th>
                            <th>Affected Users</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($ai_errors as $error)
                        <tr class="table-{{ $error['severity'] == 'Critical' ? 'danger' : ($error['severity'] == 'Warning' ? 'warning' : 'light') }}">
                            <td>{{ $error['timestamp'] }}</td>
                            <td>{{ $error['tool_name'] }}</td>
                            <td>{{ $error['error_type'] }}</td>
                            <td>
                                <span class="badge bg-{{ $error['severity'] == 'Critical' ? 'danger' : ($error['severity'] == 'Warning' ? 'warning' : 'info') }}">
                                    {{ $error['severity'] }}
                                </span>
                            </td>
                            <td>{{ $error['message'] }}</td>
                            <td>{{ $error['affected_users'] }}</td>
                            <td>
                                <span class="badge bg-{{ $error['status'] == 'Resolved' ? 'success' : 'warning' }}">
                                    {{ $error['status'] }}
                                </span>
                            </td>
                            <td>
                                @if($error['status'] != 'Resolved')
                                <button class="btn btn-xs btn-outline-primary" onclick="resolveError('{{ $error['id'] }}')">
                                    Resolve
                                </button>
                                @endif
                                <button class="btn btn-xs btn-outline-info" onclick="viewErrorDetails('{{ $error['id'] }}')">
                                    Details
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Configure AI Modal -->
<div class="modal fade" id="configureAIModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Configure AI Settings</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="configureAIForm">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="aiProvider" class="form-label">AI Provider</label>
                                <select class="form-select" id="aiProvider" name="ai_provider">
                                    <option value="openai">OpenAI GPT</option>
                                    <option value="google">Google AI</option>
                                    <option value="azure">Azure AI</option>
                                    <option value="custom">Custom Model</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="aiModel" class="form-label">AI Model</label>
                                <select class="form-select" id="aiModel" name="ai_model">
                                    <option value="gpt-4">GPT-4</option>
                                    <option value="gpt-3.5-turbo">GPT-3.5 Turbo</option>
                                    <option value="gemini-pro">Gemini Pro</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="maxTokens" class="form-label">Max Tokens</label>
                                <input type="number" class="form-control" id="maxTokens" name="max_tokens" value="2048">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="temperature" class="form-label">Temperature (Creativity)</label>
                                <input type="range" class="form-range" id="temperature" name="temperature" min="0" max="1" step="0.1" value="0.7">
                                <div class="form-text">0.0 = Conservative, 1.0 = Creative</div>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Enabled AI Features</label>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" id="aiExams" name="features[]" value="ai_exams" checked>
                                    <label class="form-check-label" for="aiExams">AI Exam Generation</label>
                                </div>
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" id="aiGrading" name="features[]" value="ai_grading" checked>
                                    <label class="form-check-label" for="aiGrading">Auto-Grading</label>
                                </div>
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" id="aiNotes" name="features[]" value="ai_notes" checked>
                                    <label class="form-check-label" for="aiNotes">AI Note Generation</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" id="aiTutoring" name="features[]" value="ai_tutoring">
                                    <label class="form-check-label" for="aiTutoring">AI Tutoring</label>
                                </div>
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" id="aiAnalytics" name="features[]" value="ai_analytics" checked>
                                    <label class="form-check-label" for="aiAnalytics">AI Analytics</label>
                                </div>
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" id="aiChat" name="features[]" value="ai_chat">
                                    <label class="form-check-label" for="aiChat">AI Chat Support</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="apiKey" class="form-label">API Key</label>
                        <input type="password" class="form-control" id="apiKey" name="api_key" placeholder="Enter API key...">
                        <div class="form-text">Your API key will be encrypted and stored securely</div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="saveAIConfiguration()">Save Configuration</button>
            </div>
        </div>
    </div>
</div>

<!-- AI Training Modal -->
<div class="modal fade" id="aiTrainingModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">AI Model Training</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="aiTrainingForm">
                    <div class="mb-3">
                        <label for="trainingTool" class="form-label">AI Tool to Train</label>
                        <select class="form-select" id="trainingTool" name="training_tool" required>
                            <option value="">Select AI Tool</option>
                            <option value="exam_generator">AI Exam Generator</option>
                            <option value="content_creator">Content Creator</option>
                            <option value="auto_grader">Auto-Grader</option>
                            <option value="tutor_assistant">Tutor Assistant</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="trainingData" class="form-label">Training Data Source</label>
                        <select class="form-select" id="trainingData" name="training_data" required>
                            <option value="">Select Data Source</option>
                            <option value="existing_content">Existing School Content</option>
                            <option value="curriculum_standards">Curriculum Standards</option>
                            <option value="exam_history">Historical Exam Data</option>
                            <option value="custom_upload">Custom Upload</option>
                        </select>
                    </div>
                    <div class="mb-3" id="customUploadSection" style="display: none;">
                        <label for="trainingFiles" class="form-label">Upload Training Files</label>
                        <input type="file" class="form-control" id="trainingFiles" name="training_files" multiple>
                        <div class="form-text">Supported formats: PDF, DOC, TXT, JSON</div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="trainingSubjects" class="form-label">Focus Subjects</label>
                                <select class="form-select" id="trainingSubjects" name="subjects[]" multiple>
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
                                <label for="trainingLevels" class="form-label">Grade Levels</label>
                                <select class="form-select" id="trainingLevels" name="levels[]" multiple>
                                    <option value="Grade 8">Grade 8</option>
                                    <option value="Grade 9">Grade 9</option>
                                    <option value="Grade 10">Grade 10</option>
                                    <option value="Grade 11">Grade 11</option>
                                    <option value="Grade 12">Grade 12</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="trainingIntensity" class="form-label">Training Intensity</label>
                        <select class="form-select" id="trainingIntensity" name="training_intensity">
                            <option value="light">Light (Quick refinement)</option>
                            <option value="medium" selected>Medium (Balanced training)</option>
                            <option value="intensive">Intensive (Deep learning)</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="backupModel" name="backup_model" checked>
                            <label class="form-check-label" for="backupModel">
                                Create backup of current model before training
                            </label>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-success" onclick="startAITraining()">Start Training</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// AI Health Chart
const ctx1 = document.getElementById('aiHealthChart').getContext('2d');
new Chart(ctx1, {
    type: 'radar',
    data: {
        labels: ['CPU Usage', 'Memory', 'Response Time', 'Accuracy', 'Uptime'],
        datasets: [{
            label: 'AI System Health',
            data: [85, 72, 90, 95, 98],
            backgroundColor: 'rgba(54, 162, 235, 0.2)',
            borderColor: 'rgb(54, 162, 235)',
            pointBackgroundColor: 'rgb(54, 162, 235)',
            pointBorderColor: '#fff',
            pointHoverBackgroundColor: '#fff',
            pointHoverBorderColor: 'rgb(54, 162, 235)'
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
            r: {
                angleLines: {
                    display: false
                },
                suggestedMin: 0,
                suggestedMax: 100
            }
        }
    }
});

// AI Usage Trends Chart
const ctx2 = document.getElementById('aiUsageTrendsChart').getContext('2d');
new Chart(ctx2, {
    type: 'line',
    data: {
        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
        datasets: [{
            label: 'AI Requests (thousands)',
            data: [45, 52, 68, 78, 89, 95],
            borderColor: 'rgb(75, 192, 192)',
            backgroundColor: 'rgba(75, 192, 192, 0.1)',
            tension: 0.1
        }, {
            label: 'Content Generated',
            data: [25, 35, 42, 48, 58, 65],
            borderColor: 'rgb(255, 99, 132)',
            backgroundColor: 'rgba(255, 99, 132, 0.1)',
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

// AI Tool Management Functions
function manageCategory(categoryId) {
    alert('Opening management for category: ' + categoryId);
}

function configureAITool(toolId) {
    alert('Opening configuration for AI tool: ' + toolId);
}

function viewAIAnalytics(toolId) {
    alert('Opening analytics for AI tool: ' + toolId);
}

function trainAITool(toolId) {
    $('#aiTrainingModal').modal('show');
    document.getElementById('trainingTool').value = toolId;
}

function testAITool(toolId) {
    alert('Running test for AI tool: ' + toolId);
}

function toggleAITool(toolId, action) {
    const actionText = action === 'enable' ? 'enable' : 'disable';
    if (confirm(`Are you sure you want to ${actionText} this AI tool?`)) {
        alert(`AI tool ${action}d successfully!`);
    }
}

function resetAITool(toolId) {
    if (confirm('Are you sure you want to reset this AI tool? This will restore it to default settings.')) {
        alert('AI tool reset successfully!');
    }
}

function resolveError(errorId) {
    if (confirm('Mark this error as resolved?')) {
        alert('Error marked as resolved!');
    }
}

function viewErrorDetails(errorId) {
    alert('Opening error details for: ' + errorId);
}

function refreshErrorLogs() {
    alert('Refreshing error logs...');
}

function viewAILogs() {
    alert('Opening AI system logs...');
}

// Configuration Functions
function saveAIConfiguration() {
    const form = document.getElementById('configureAIForm');
    if (!form.checkValidity()) {
        form.reportValidity();
        return;
    }
    
    alert('AI configuration saved successfully!');
    $('#configureAIModal').modal('hide');
}

function startAITraining() {
    const form = document.getElementById('aiTrainingForm');
    if (!form.checkValidity()) {
        form.reportValidity();
        return;
    }
    
    // Show loading state
    const button = event.target;
    const originalText = button.innerHTML;
    button.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Starting Training...';
    button.disabled = true;
    
    // Simulate training start
    setTimeout(() => {
        alert('AI training started successfully! You will be notified when complete.');
        $('#aiTrainingModal').modal('hide');
        form.reset();
        button.innerHTML = originalText;
        button.disabled = false;
    }, 2000);
}

// Filter Functions
document.getElementById('categoryFilter').addEventListener('change', function() {
    filterAITools();
});

document.getElementById('statusFilter').addEventListener('change', function() {
    filterAITools();
});

function filterAITools() {
    const categoryFilter = document.getElementById('categoryFilter').value;
    const statusFilter = document.getElementById('statusFilter').value;
    const rows = document.querySelectorAll('#aiToolsTable tbody tr');
    
    rows.forEach(row => {
        const category = row.dataset.category;
        const status = row.dataset.status;
        
        const matchesCategory = categoryFilter === '' || category === categoryFilter;
        const matchesStatus = statusFilter === '' || status === statusFilter;
        
        if (matchesCategory && matchesStatus) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
}

// Training data source change handler
document.getElementById('trainingData').addEventListener('change', function() {
    const customUploadSection = document.getElementById('customUploadSection');
    if (this.value === 'custom_upload') {
        customUploadSection.style.display = 'block';
    } else {
        customUploadSection.style.display = 'none';
    }
});

function updateUsageChart(type) {
    console.log('Updating usage chart to show:', type);
}
</script>
@endpush
