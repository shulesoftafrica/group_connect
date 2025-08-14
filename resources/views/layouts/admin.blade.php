<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>@yield('title', 'Dashboard') - {{ config('app.name', 'ShuleSoft Group Connect') }}</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <!-- Custom CSS -->
    <style>
        :root {
            --sidebar-width: 280px;
            --primary-color: #0d6efd;
            --secondary-color: #6c757d;
        }
        
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: var(--sidebar-width);
            height: 100vh;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            z-index: 1000;
            transition: all 0.3s;
        }
        
        .sidebar .nav-link {
            color: rgba(255, 255, 255, 0.8);
            border-radius: 0.5rem;
            margin: 0.2rem 0.5rem;
            padding: 0.75rem 1rem;
            transition: all 0.3s;
        }
        
        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            color: white;
            background-color: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
        }
        
        .main-content {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
            background-color: #f8f9fa;
        }
        
        .navbar-brand {
            color: white !important;
            font-weight: 600;
        }
        
        .content-wrapper {
            padding: 2rem;
        }
        
        .stats-card {
            background: white;
            border-radius: 1rem;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
            transition: transform 0.2s, box-shadow 0.2s;
        }
        
        .stats-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        }
        
        .dropdown-menu {
            border: none;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        }

        .table {
            background: white;
            border-radius: 0.5rem;
            overflow: hidden;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        }

        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
            }
            
            .sidebar.show {
                transform: translateX(0);
            }
            
            .main-content {
                margin-left: 0;
            }
        }
    </style>
    
    @stack('styles')
</head>
<body>
    <!-- Sidebar -->
    <nav class="sidebar" id="sidebar">
        <div class="p-3">
            <a class="navbar-brand d-flex align-items-center mb-3" href="{{ route('dashboard') }}">
                <i class="bi bi-mortarboard-fill me-2 fs-4"></i>
                <span class="fs-5">ShuleSoft Group</span>
            </a>
            
            <ul class="nav nav-pills flex-column">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                        <i class="bi bi-speedometer2 me-2"></i>
                        Dashboard
                    </a>
                </li>
                
                @if(auth()->user()->hasModuleAccess('schools'))
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('schools.*') ? 'active' : '' }}" href="{{ route('schools.index') }}">
                        <i class="bi bi-building me-2"></i>
                        Schools Overview
                    </a>
                </li>
                @endif
                
                @if(auth()->user()->hasModuleAccess('academics'))
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('academics.*') ? 'active' : '' }}" href="{{ route('academics.index') }}">
                        <i class="bi bi-book me-2"></i>
                        Academics
                    </a>
                </li>
                @endif
                
                @if(auth()->user()->hasModuleAccess('operations'))
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('operations.*') ? 'active' : '' }}" href="{{ route('operations.index') }}">
                        <i class="bi bi-gear me-2"></i>
                        Operations
                    </a>
                </li>
                @endif
                
                @if(auth()->user()->hasModuleAccess('finance'))
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('finance.*') ? 'active' : '' }}" href="{{ route('finance.index') }}">
                        <i class="bi bi-calculator me-2"></i>
                        Finance & Accounts
                    </a>
                </li>
                @endif
                
                @if(auth()->user()->hasModuleAccess('hr'))
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('hr.*') ? 'active' : '' }}" href="{{ route('hr.index') }}">
                        <i class="bi bi-people me-2"></i>
                        Human Resources
                    </a>
                </li>
                @endif
                
                @if(auth()->user()->hasModuleAccess('communications'))
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('communications.*') ? 'active' : '' }}" href="{{ route('communications.index') }}">
                        <i class="bi bi-chat-dots me-2"></i>
                        Communications
                    </a>
                </li>
                @endif
                
                @if(auth()->user()->hasModuleAccess('digital_learning'))
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('digital-learning.*') ? 'active' : '' }}" 
                       data-bs-toggle="collapse" href="#digitalLearningSubmenu" role="button" 
                       aria-expanded="{{ request()->routeIs('digital-learning.*') ? 'true' : 'false' }}" aria-controls="digitalLearningSubmenu">
                        <i class="fas fa-robot me-2"></i>
                        AI Digital Learning
                        <i class="fas fa-chevron-down ms-auto"></i>
                    </a>
                    <div class="collapse {{ request()->routeIs('digital-learning.*') ? 'show' : '' }}" id="digitalLearningSubmenu">
                        <ul class="nav flex-column ms-3">
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('digital-learning.index') ? 'active' : '' }}" href="{{ route('digital-learning.index') }}">
                                    <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('digital-learning.exams') ? 'active' : '' }}" href="{{ route('digital-learning.exams') }}">
                                    <i class="fas fa-robot me-2"></i>AI Exams
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('digital-learning.content') ? 'active' : '' }}" href="{{ route('digital-learning.content') }}">
                                    <i class="fas fa-file-alt me-2"></i>Content Management
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('digital-learning.analytics') ? 'active' : '' }}" href="{{ route('digital-learning.analytics') }}">
                                    <i class="fas fa-chart-bar me-2"></i>Analytics
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('digital-learning.ai-tools') ? 'active' : '' }}" href="{{ route('digital-learning.ai-tools') }}">
                                    <i class="fas fa-magic me-2"></i>AI Tools
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
                @endif
                
                @if(auth()->user()->hasModuleAccess('insights'))
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('insights.*') ? 'active' : '' }}" 
                       data-bs-toggle="collapse" href="#insightsSubmenu" role="button" 
                       aria-expanded="{{ request()->routeIs('insights.*') ? 'true' : 'false' }}" aria-controls="insightsSubmenu">
                        <i class="fas fa-brain me-2"></i>
                        Executive Insights
                        <i class="fas fa-chevron-down ms-auto"></i>
                    </a>
                    <div class="collapse {{ request()->routeIs('insights.*') ? 'show' : '' }}" id="insightsSubmenu">
                        <ul class="nav flex-column ms-3">
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('insights.dashboard') ? 'active' : '' }}" href="{{ route('insights.dashboard') }}">
                                    <i class="fas fa-chart-line me-2"></i>Executive Dashboard
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('insights.ai-chat') ? 'active' : '' }}" href="{{ route('insights.ai-chat') }}">
                                    <i class="fas fa-comments me-2"></i>AI Assistant
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('insights.analytics') ? 'active' : '' }}" href="{{ route('insights.analytics') }}">
                                    <i class="fas fa-project-diagram me-2"></i>Advanced Analytics
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('insights.alerts') ? 'active' : '' }}" href="{{ route('insights.alerts') }}">
                                    <i class="fas fa-exclamation-triangle me-2"></i>Alerts & Exceptions
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('insights.reports') ? 'active' : '' }}" href="{{ route('insights.reports') }}">
                                    <i class="fas fa-file-chart me-2"></i>Custom Reports
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
                @endif
                
                @if(auth()->user()->hasModuleAccess('settings'))
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('settings.*') ? 'active' : '' }}" 
                       data-bs-toggle="collapse" href="#settingsSubmenu" role="button" 
                       aria-expanded="{{ request()->routeIs('settings.*') ? 'true' : 'false' }}" aria-controls="settingsSubmenu">
                        <i class="bi bi-gear-wide-connected me-2"></i>
                        Settings & Control
                        <i class="fas fa-chevron-down ms-auto"></i>
                    </a>
                    <div class="collapse {{ request()->routeIs('settings.*') ? 'show' : '' }}" id="settingsSubmenu">
                        <ul class="nav flex-column ms-3">
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('settings.index') ? 'active' : '' }}" href="{{ route('settings.index') }}">
                                    <i class="bi bi-house-gear me-2"></i>Dashboard
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('settings.users') ? 'active' : '' }}" href="{{ route('settings.users') }}">
                                    <i class="bi bi-people-fill me-2"></i>User Management
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('settings.schools') ? 'active' : '' }}" href="{{ route('settings.schools') }}">
                                    <i class="bi bi-building-fill me-2"></i>School Management
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('settings.academic-years') ? 'active' : '' }}" href="{{ route('settings.academic-years') }}">
                                    <i class="bi bi-calendar-event me-2"></i>Academic Years
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('settings.roles-permissions') ? 'active' : '' }}" href="{{ route('settings.roles-permissions') }}">
                                    <i class="bi bi-shield-lock-fill me-2"></i>Roles & Permissions
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('settings.system-config') ? 'active' : '' }}" href="{{ route('settings.system-config') }}">
                                    <i class="bi bi-gear-wide-connected me-2"></i>System Config
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('settings.bulk-operations') ? 'active' : '' }}" href="{{ route('settings.bulk-operations') }}">
                                    <i class="bi bi-lightning-fill me-2"></i>Bulk Operations
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('settings.audit-logs') ? 'active' : '' }}" href="{{ route('settings.audit-logs') }}">
                                    <i class="bi bi-clipboard-data me-2"></i>Audit Logs
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
                @endif
                
                @if(auth()->user()->hasModuleAccess('reports'))
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('reports.*') ? 'active' : '' }}" href="{{ route('insights.dashboard') }}">
                        <i class="bi bi-graph-up me-2"></i>
                        Reports & Insights
                    </a>
                </li>
                @endif
            </ul>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Top Navigation -->
        <nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom">
            <div class="container-fluid">
                <button class="btn btn-outline-secondary d-md-none" type="button" onclick="toggleSidebar()">
                    <i class="bi bi-list"></i>
                </button>
                
                <div class="navbar-nav ms-auto">
                    <!-- User Dropdown -->
                    <div class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown">
                            <div class="me-3 text-end">
                                <div class="fw-bold">{{ auth()->user()->name }}</div>
                                <small class="text-muted">{{ auth()->user()->role->display_name }}</small>
                            </div>
                            <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                <i class="bi bi-person-fill text-white"></i>
                            </div>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="#"><i class="bi bi-person me-2"></i>Profile</a></li>
                            <li><a class="dropdown-item" href="#"><i class="bi bi-gear me-2"></i>Settings</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item">
                                        <i class="bi bi-box-arrow-right me-2"></i>Logout
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Page Content -->
        <div class="content-wrapper">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @yield('content')
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Custom JS -->
    <script>
        function toggleSidebar() {
            document.getElementById('sidebar').classList.toggle('show');
        }
        
        // Close sidebar when clicking outside on mobile
        document.addEventListener('click', function(event) {
            const sidebar = document.getElementById('sidebar');
            const toggleBtn = event.target.closest('[onclick="toggleSidebar()"]');
            
            if (!sidebar.contains(event.target) && !toggleBtn && window.innerWidth <= 768) {
                sidebar.classList.remove('show');
            }
        });
    </script>
    
    @stack('scripts')
</body>
</html>
