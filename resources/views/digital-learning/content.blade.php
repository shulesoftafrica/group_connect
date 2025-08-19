@extends('layouts.digital-learning')

@section('title', 'Digital Content Management')
@section('page-title', 'Content Management')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">Digital Content Management</h1>
            <p class="mb-0 text-muted">Manage and distribute digital learning content across all schools</p>
        </div>
        <div class="d-flex gap-2">
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#uploadContentModal">
                <i class="fas fa-upload me-2"></i>Upload Content
            </button>
            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#generateAIContentModal">
                <i class="fas fa-magic me-2"></i>Generate AI Content
            </button>
            <button class="btn btn-info" data-bs-toggle="modal" data-bs-target="#bulkDistributeModal">
                <i class="fas fa-share-alt me-2"></i>Bulk Distribute
            </button>
            <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#contentLibraryModal">
                <i class="fas fa-book me-2"></i>Content Library
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
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Content</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($total_content) }}</div>
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
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">AI Generated</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $ai_generated_content }}</div>
                            <div class="text-xs text-muted">{{ $ai_content_percentage }}% of total</div>
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
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Downloads</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($total_downloads) }}</div>
                            <div class="text-xs text-muted">This month: {{ number_format($monthly_downloads) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-download fa-2x text-gray-300"></i>
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
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Storage Used</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $storage_used }}GB</div>
                            <div class="text-xs text-muted">{{ $storage_percentage }}% of {{ $storage_limit }}GB</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-hdd fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Content Overview Charts -->
    <div class="row mb-4">
        <div class="col-xl-8 col-lg-7">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Content Upload Trends</h6>
                    <div class="dropdown no-arrow">
                        <a class="dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right">
                            <a class="dropdown-item" href="#" onclick="updateContentChart('uploads')">Upload Count</a>
                            <a class="dropdown-item" href="#" onclick="updateContentChart('downloads')">Download Count</a>
                            <a class="dropdown-item" href="#" onclick="updateContentChart('engagement')">Engagement Rate</a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <canvas id="contentTrendsChart" width="100%" height="40"></canvas>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-lg-5">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Content Distribution</h6>
                </div>
                <div class="card-body">
                    <canvas id="contentDistributionChart" width="100%" height="150"></canvas>
                    <div class="mt-3">
                        @foreach($content_distribution as $type => $data)
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

    <!-- Content Filters -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <h6 class="m-0 font-weight-bold text-primary">Filter Content</h6>
                </div>
                <div class="col-lg-6">
                    <div class="row">
                        <div class="col-md-3">
                            <select class="form-select form-select-sm" id="contentTypeFilter">
                                <option value="">All Types</option>
                                <option value="Notes">Notes</option>
                                <option value="Videos">Videos</option>
                                <option value="Assignments">Assignments</option>
                                <option value="AI Notes">AI Notes</option>
                                <option value="Presentations">Presentations</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select class="form-select form-select-sm" id="subjectFilter">
                                <option value="">All Subjects</option>
                                <option value="Mathematics">Mathematics</option>
                                <option value="English">English</option>
                                <option value="Science">Science</option>
                                <option value="History">History</option>
                                <option value="Geography">Geography</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select class="form-select form-select-sm" id="classFilter">
                                <option value="">All Classes</option>
                                <option value="Grade 8">Grade 8</option>
                                <option value="Grade 9">Grade 9</option>
                                <option value="Grade 10">Grade 10</option>
                                <option value="Grade 11">Grade 11</option>
                                <option value="Grade 12">Grade 12</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <div class="input-group input-group-sm">
                                <input type="text" class="form-control" placeholder="Search content..." id="contentSearch">
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

    <!-- Content Grid/List Toggle -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h6 class="mb-0">Content Library</h6>
        </div>
        <div class="btn-group" role="group">
            <input type="radio" class="btn-check" name="viewMode" id="gridView" checked>
            <label class="btn btn-outline-secondary btn-sm" for="gridView">
                <i class="fas fa-th-large"></i> Grid
            </label>
            <input type="radio" class="btn-check" name="viewMode" id="listView">
            <label class="btn btn-outline-secondary btn-sm" for="listView">
                <i class="fas fa-list"></i> List
            </label>
        </div>
    </div>

    <!-- Content Grid View -->
    <div id="contentGridView">
        <div class="row" id="contentGrid">
            @foreach($content_items as $content)
            <div class="col-xl-3 col-lg-4 col-md-6 mb-4 content-item" 
                 data-type="{{ $content['type'] }}" 
                 data-subject="{{ $content['subject'] }}" 
                 data-class="{{ $content['class'] }}">
                <div class="card shadow h-100">
                    <div class="card-header d-flex justify-content-between align-items-center py-2">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-{{ $content['type'] == 'Videos' ? 'video' : ($content['type'] == 'AI Notes' ? 'robot' : 'file-alt') }} text-primary me-2"></i>
                            <span class="badge bg-{{ $content['type'] == 'AI Notes' ? 'warning' : 'info' }}">{{ $content['type'] }}</span>
                        </div>
                        <div class="dropdown">
                            <button class="btn btn-sm btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown">
                                <i class="fas fa-ellipsis-v"></i>
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="#" onclick="downloadContent('{{ $content['id'] }}')">
                                    <i class="fas fa-download me-2"></i>Download
                                </a></li>
                                <li><a class="dropdown-item" href="#" onclick="editContent('{{ $content['id'] }}')">
                                    <i class="fas fa-edit me-2"></i>Edit
                                </a></li>
                                <li><a class="dropdown-item" href="#" onclick="shareContent('{{ $content['id'] }}')">
                                    <i class="fas fa-share me-2"></i>Share
                                </a></li>
                                <li><a class="dropdown-item" href="#" onclick="viewAnalytics('{{ $content['id'] }}')">
                                    <i class="fas fa-chart-bar me-2"></i>Analytics
                                </a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item text-danger" href="#" onclick="deleteContent('{{ $content['id'] }}')">
                                    <i class="fas fa-trash me-2"></i>Delete
                                </a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="card-body p-3">
                        <h6 class="card-title mb-2">{{ $content['title'] }}</h6>
                        <p class="card-text text-muted small mb-2">{{ Str::limit($content['description'], 100) }}</p>
                        <div class="mb-2">
                            <small class="text-muted">
                                <i class="fas fa-graduation-cap me-1"></i>{{ $content['class'] }} •
                                <i class="fas fa-book me-1"></i>{{ $content['subject'] }}
                            </small>
                        </div>
                        <div class="mb-2">
                            <small class="text-muted">
                                <i class="fas fa-calendar me-1"></i>{{ $content['upload_date'] }} •
                                <i class="fas fa-user me-1"></i>{{ $content['uploaded_by'] }}
                            </small>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <small class="text-success">
                                    <i class="fas fa-download me-1"></i>{{ $content['downloads'] }} downloads
                                </small>
                            </div>
                            <div>
                                <small class="text-info">
                                    <i class="fas fa-eye me-1"></i>{{ $content['views'] }} views
                                </small>
                            </div>
                        </div>
                        @if($content['type'] == 'AI Notes')
                        <div class="mt-2">
                            <span class="badge bg-warning"><i class="fas fa-robot me-1"></i>AI Generated</span>
                        </div>
                        @endif
                    </div>
                    <div class="card-footer py-2">
                        <div class="d-flex justify-content-between align-items-center">
                            <small class="text-muted">{{ $content['schools_distributed'] }} schools</small>
                            <div class="btn-group btn-group-sm">
                                <button class="btn btn-outline-primary btn-sm" onclick="previewContent('{{ $content['id'] }}')">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button class="btn btn-outline-success btn-sm" onclick="distributeContent('{{ $content['id'] }}')">
                                    <i class="fas fa-share-alt"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Content List View -->
    <div id="contentListView" class="d-none">
        <div class="card shadow">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover" id="contentTable">
                        <thead class="table-light">
                            <tr>
                                <th><input type="checkbox" class="form-check-input" id="selectAllContent"></th>
                                <th>Content</th>
                                <th>Type</th>
                                <th>Subject/Class</th>
                                <th>Upload Date</th>
                                <th>Uploaded By</th>
                                <th>Size</th>
                                <th>Downloads</th>
                                <th>Schools</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($content_items as $content)
                            <tr class="content-row" 
                                data-type="{{ $content['type'] }}" 
                                data-subject="{{ $content['subject'] }}" 
                                data-class="{{ $content['class'] }}">
                                <td><input type="checkbox" class="form-check-input content-checkbox" value="{{ $content['id'] }}"></td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-{{ $content['type'] == 'Videos' ? 'video' : ($content['type'] == 'AI Notes' ? 'robot' : 'file-alt') }} text-primary me-2"></i>
                                        <div>
                                            <h6 class="mb-0">{{ $content['title'] }}</h6>
                                            <small class="text-muted">{{ Str::limit($content['description'], 60) }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-{{ $content['type'] == 'AI Notes' ? 'warning' : 'info' }}">
                                        {{ $content['type'] }}
                                    </span>
                                </td>
                                <td>
                                    <div class="text-sm">
                                        <strong>{{ $content['subject'] }}</strong><br>
                                        <span class="text-muted">{{ $content['class'] }}</span>
                                    </div>
                                </td>
                                <td>{{ $content['upload_date'] }}</td>
                                <td>{{ $content['uploaded_by'] }}</td>
                                <td>{{ $content['file_size'] }}</td>
                                <td>
                                    <span class="badge bg-success">{{ $content['downloads'] }}</span>
                                    <div class="text-xs text-muted">{{ $content['views'] }} views</div>
                                </td>
                                <td class="text-center">{{ $content['schools_distributed'] }}</td>
                                <td>
                                    <span class="badge bg-{{ $content['status'] == 'Active' ? 'success' : 'warning' }}">
                                        {{ $content['status'] }}
                                    </span>
                                </td>
                                <td>
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown">
                                            Actions
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li><a class="dropdown-item" href="#" onclick="previewContent('{{ $content['id'] }}')">
                                                <i class="fas fa-eye me-2"></i>Preview
                                            </a></li>
                                            <li><a class="dropdown-item" href="#" onclick="downloadContent('{{ $content['id'] }}')">
                                                <i class="fas fa-download me-2"></i>Download
                                            </a></li>
                                            <li><a class="dropdown-item" href="#" onclick="editContent('{{ $content['id'] }}')">
                                                <i class="fas fa-edit me-2"></i>Edit
                                            </a></li>
                                            <li><a class="dropdown-item" href="#" onclick="distributeContent('{{ $content['id'] }}')">
                                                <i class="fas fa-share-alt me-2"></i>Distribute
                                            </a></li>
                                            <li><a class="dropdown-item" href="#" onclick="viewAnalytics('{{ $content['id'] }}')">
                                                <i class="fas fa-chart-bar me-2"></i>Analytics
                                            </a></li>
                                            <li><hr class="dropdown-divider"></li>
                                            <li><a class="dropdown-item text-danger" href="#" onclick="deleteContent('{{ $content['id'] }}')">
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
    </div>

    <!-- Bulk Actions Bar -->
    <div class="d-none" id="bulkContentActions">
        <div class="card shadow mb-4">
            <div class="card-body py-2">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <span id="selectedContentCount">0</span> items selected
                    </div>
                    <div class="d-flex gap-2">
                        <button class="btn btn-sm btn-outline-primary" onclick="bulkDistribute()">
                            <i class="fas fa-share-alt me-1"></i>Distribute
                        </button>
                        <button class="btn btn-sm btn-outline-success" onclick="bulkDownload()">
                            <i class="fas fa-download me-1"></i>Download
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

<!-- Upload Content Modal -->
<div class="modal fade" id="uploadContentModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Upload New Content</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="uploadContentForm" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="contentTitle" class="form-label">Content Title *</label>
                                <input type="text" class="form-control" id="contentTitle" name="title" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="contentType" class="form-label">Content Type *</label>
                                <select class="form-select" id="contentType" name="type" required>
                                    <option value="">Select Type</option>
                                    <option value="Notes">Class Notes</option>
                                    <option value="Videos">Videos</option>
                                    <option value="Assignments">Assignments</option>
                                    <option value="Presentations">Presentations</option>
                                    <option value="Documents">Documents</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="contentSubject" class="form-label">Subject *</label>
                                <select class="form-select" id="contentSubject" name="subject" required>
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
                                <label for="contentClass" class="form-label">Class Level *</label>
                                <select class="form-select" id="contentClass" name="class" required>
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
                    <div class="mb-3">
                        <label for="contentDescription" class="form-label">Description</label>
                        <textarea class="form-control" id="contentDescription" name="description" rows="3" placeholder="Brief description of the content..."></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="contentFiles" class="form-label">Content Files *</label>
                        <input type="file" class="form-control" id="contentFiles" name="files[]" multiple required>
                        <div class="form-text">You can select multiple files. Supported formats: PDF, DOC, PPT, MP4, etc.</div>
                    </div>
                    <div class="mb-3">
                        <label for="targetSchools" class="form-label">Target Schools *</label>
                        <select class="form-select" id="targetSchools" name="schools[]" multiple required>
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
                <button type="button" class="btn btn-primary" onclick="uploadContent()">Upload Content</button>
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
                                <label for="aiContentType" class="form-label">Content Type *</label>
                                <select class="form-select" id="aiContentType" name="type" required>
                                    <option value="">Select Type</option>
                                    <option value="notes">Study Notes</option>
                                    <option value="revision">Revision Materials</option>
                                    <option value="exercises">Practice Exercises</option>
                                    <option value="quiz">Quiz Questions</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="aiSubject" class="form-label">Subject *</label>
                                <select class="form-select" id="aiSubject" name="subject" required>
                                    <option value="">Select Subject</option>
                                    <option value="Mathematics">Mathematics</option>
                                    <option value="English">English</option>
                                    <option value="Science">Science</option>
                                    <option value="History">History</option>
                                    <option value="Geography">Geography</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="aiClassLevel" class="form-label">Class Level *</label>
                                <select class="form-select" id="aiClassLevel" name="class" required>
                                    <option value="">Select Class</option>
                                    <option value="Grade 8">Grade 8</option>
                                    <option value="Grade 9">Grade 9</option>
                                    <option value="Grade 10">Grade 10</option>
                                    <option value="Grade 11">Grade 11</option>
                                    <option value="Grade 12">Grade 12</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="difficultyLevel" class="form-label">Difficulty Level</label>
                                <select class="form-select" id="difficultyLevel" name="difficulty">
                                    <option value="Basic">Basic</option>
                                    <option value="Intermediate" selected>Intermediate</option>
                                    <option value="Advanced">Advanced</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="contentTopic" class="form-label">Topic/Chapter *</label>
                        <input type="text" class="form-control" id="contentTopic" name="topic" placeholder="e.g., Algebra, Photosynthesis, World War II" required>
                    </div>
                    <div class="mb-3">
                        <label for="contentLength" class="form-label">Content Length</label>
                        <select class="form-select" id="contentLength" name="length">
                            <option value="Short">Short (1-2 pages)</option>
                            <option value="Medium" selected>Medium (3-5 pages)</option>
                            <option value="Long">Long (6+ pages)</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="aiTargetSchools" class="form-label">Target Schools *</label>
                        <select class="form-select" id="aiTargetSchools" name="schools[]" multiple required>
                            @foreach($schools as $school)
                            <option value="{{ $school['id'] }}">{{ $school['name'] }}</option>
                            @endforeach
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-success" onclick="generateAIContent()">
                    <i class="fas fa-magic me-2"></i>Generate Content
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Content Trends Chart
const ctx1 = document.getElementById('contentTrendsChart').getContext('2d');
new Chart(ctx1, {
    type: 'line',
    data: {
        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
        datasets: [{
            label: 'Uploads',
            data: [65, 78, 89, 95, 112, 128],
            borderColor: 'rgb(75, 192, 192)',
            backgroundColor: 'rgba(75, 192, 192, 0.1)',
            tension: 0.1
        }, {
            label: 'Downloads',
            data: [320, 395, 467, 523, 648, 789],
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

// Content Distribution Chart
const ctx2 = document.getElementById('contentDistributionChart').getContext('2d');
new Chart(ctx2, {
    type: 'doughnut',
    data: {
        labels: ['Notes', 'Videos', 'Assignments', 'AI Notes', 'Presentations'],
        datasets: [{
            data: [35, 25, 20, 12, 8],
            backgroundColor: [
                'rgb(75, 192, 192)',
                'rgb(255, 99, 132)',
                'rgb(54, 162, 235)',
                'rgb(255, 206, 86)',
                'rgb(153, 102, 255)'
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

// View toggle
document.getElementById('gridView').addEventListener('change', function() {
    if (this.checked) {
        document.getElementById('contentGridView').classList.remove('d-none');
        document.getElementById('contentListView').classList.add('d-none');
    }
});

document.getElementById('listView').addEventListener('change', function() {
    if (this.checked) {
        document.getElementById('contentGridView').classList.add('d-none');
        document.getElementById('contentListView').classList.remove('d-none');
    }
});

// Content actions
function previewContent(contentId) {
    alert('Opening preview for content ID: ' + contentId);
}

function downloadContent(contentId) {
    alert('Downloading content ID: ' + contentId);
}

function editContent(contentId) {
    alert('Opening editor for content ID: ' + contentId);
}

function shareContent(contentId) {
    alert('Opening share dialog for content ID: ' + contentId);
}

function distributeContent(contentId) {
    alert('Opening distribution settings for content ID: ' + contentId);
}

function viewAnalytics(contentId) {
    alert('Opening analytics for content ID: ' + contentId);
}

function deleteContent(contentId) {
    if (confirm('Are you sure you want to delete this content?')) {
        alert('Content deleted successfully!');
    }
}

// Upload content
function uploadContent() {
    const form = document.getElementById('uploadContentForm');
    if (!form.checkValidity()) {
        form.reportValidity();
        return;
    }
    
    alert('Content uploaded successfully!');
    $('#uploadContentModal').modal('hide');
    form.reset();
}

// Generate AI content
function generateAIContent() {
    const form = document.getElementById('generateAIContentForm');
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
        alert('AI Content generated and distributed successfully!');
        $('#generateAIContentModal').modal('hide');
        form.reset();
        button.innerHTML = originalText;
        button.disabled = false;
    }, 3000);
}

// Filters
function setupFilters() {
    const filters = ['contentTypeFilter', 'subjectFilter', 'classFilter'];
    filters.forEach(filterId => {
        document.getElementById(filterId).addEventListener('change', filterContent);
    });
    document.getElementById('contentSearch').addEventListener('keyup', filterContent);
}

function filterContent() {
    const typeFilter = document.getElementById('contentTypeFilter').value;
    const subjectFilter = document.getElementById('subjectFilter').value;
    const classFilter = document.getElementById('classFilter').value;
    const searchTerm = document.getElementById('contentSearch').value.toLowerCase();
    
    // Filter grid view
    const gridItems = document.querySelectorAll('.content-item');
    gridItems.forEach(item => {
        const type = item.dataset.type;
        const subject = item.dataset.subject;
        const classLevel = item.dataset.class;
        const title = item.querySelector('.card-title').textContent.toLowerCase();
        
        const matchesType = typeFilter === '' || type === typeFilter;
        const matchesSubject = subjectFilter === '' || subject === subjectFilter;
        const matchesClass = classFilter === '' || classLevel === classFilter;
        const matchesSearch = searchTerm === '' || title.includes(searchTerm);
        
        if (matchesType && matchesSubject && matchesClass && matchesSearch) {
            item.style.display = '';
        } else {
            item.style.display = 'none';
        }
    });
    
    // Filter list view
    const listRows = document.querySelectorAll('.content-row');
    listRows.forEach(row => {
        const type = row.dataset.type;
        const subject = row.dataset.subject;
        const classLevel = row.dataset.class;
        const title = row.querySelector('h6').textContent.toLowerCase();
        
        const matchesType = typeFilter === '' || type === typeFilter;
        const matchesSubject = subjectFilter === '' || subject === subjectFilter;
        const matchesClass = classFilter === '' || classLevel === classFilter;
        const matchesSearch = searchTerm === '' || title.includes(searchTerm);
        
        if (matchesType && matchesSubject && matchesClass && matchesSearch) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
}

// Bulk actions
function updateBulkContentActions() {
    const selectedCheckboxes = document.querySelectorAll('.content-checkbox:checked');
    const bulkActionsBar = document.getElementById('bulkContentActions');
    const selectedCount = document.getElementById('selectedContentCount');
    
    if (selectedCheckboxes.length > 0) {
        bulkActionsBar.classList.remove('d-none');
        selectedCount.textContent = selectedCheckboxes.length;
    } else {
        bulkActionsBar.classList.add('d-none');
    }
}

function bulkDistribute() {
    const selectedContent = document.querySelectorAll('.content-checkbox:checked');
    if (selectedContent.length === 0) {
        alert('Please select content to distribute.');
        return;
    }
    alert('Opening bulk distribution for ' + selectedContent.length + ' items');
}

function bulkDownload() {
    const selectedContent = document.querySelectorAll('.content-checkbox:checked');
    if (selectedContent.length === 0) {
        alert('Please select content to download.');
        return;
    }
    alert('Downloading ' + selectedContent.length + ' items');
}

function bulkArchive() {
    const selectedContent = document.querySelectorAll('.content-checkbox:checked');
    if (selectedContent.length === 0) {
        alert('Please select content to archive.');
        return;
    }
    if (confirm('Are you sure you want to archive ' + selectedContent.length + ' items?')) {
        alert('Content archived successfully!');
    }
}

function bulkDelete() {
    const selectedContent = document.querySelectorAll('.content-checkbox:checked');
    if (selectedContent.length === 0) {
        alert('Please select content to delete.');
        return;
    }
    if (confirm('Are you sure you want to delete ' + selectedContent.length + ' items? This action cannot be undone.')) {
        alert('Content deleted successfully!');
    }
}

// Initialize
document.addEventListener('DOMContentLoaded', function() {
    setupFilters();
    
    // Setup selection handling
    document.getElementById('selectAllContent').addEventListener('change', function() {
        const checkboxes = document.querySelectorAll('.content-checkbox');
        checkboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
        });
        updateBulkContentActions();
    });
    
    document.addEventListener('change', function(e) {
        if (e.target.classList.contains('content-checkbox')) {
            updateBulkContentActions();
        }
    });
});

function updateContentChart(type) {
    console.log('Updating chart to show:', type);
}
</script>
@endpush
