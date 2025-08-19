<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>@yield('title', 'Executive Insights') - {{ config('app.name', 'ShuleSoft Group Connect') }}</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <!-- Custom CSS -->
    <style>
        :root {
            --primary-sidebar-width: 280px;
            --secondary-sidebar-width: 250px;
            --primary-color: #0d6efd;
            --secondary-color: #6c757d;
            --insights-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --insights-secondary: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        }
        
        /* Primary Sidebar (Main Navigation) */
        .primary-sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: var(--primary-sidebar-width);
            height: 100vh;
            background: var(--insights-gradient);
            z-index: 1100;
            transition: all 0.3s ease;
        }
        
        .primary-sidebar .nav-link {
            color: rgba(255, 255, 255, 0.8);
            border-radius: 0.5rem;
            margin: 0.2rem 0.5rem;
            padding: 0.75rem 1rem;
            transition: all 0.3s ease;
        }
        
        .primary-sidebar .nav-link:hover,
        .primary-sidebar .nav-link.active {
            color: white;
            background-color: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(10px);
            transform: translateX(5px);
        }
        
        /* Secondary Sidebar (Executive Insights Sub-Navigation) */
        .secondary-sidebar {
            position: fixed;
            top: 0;
            left: var(--primary-sidebar-width);
            width: var(--secondary-sidebar-width);
            height: 100vh;
            background: var(--insights-secondary);
            border-right: 1px solid #dee2e6;
            z-index: 1050;
            transition: all 0.3s ease;
            box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1);
        }
        
        .secondary-sidebar .secondary-header {
            background: linear-gradient(135deg, #7c3aed 0%, #a855f7 100%);
            color: white;
            padding: 1.5rem 1rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .secondary-sidebar .nav-link {
            color: #495057;
            border-radius: 0.375rem;
            margin: 0.1rem 0.75rem;
            padding: 0.625rem 1rem;
            transition: all 0.3s ease;
            font-size: 0.9rem;
            position: relative;
        }
        
        .secondary-sidebar .nav-link:hover {
            color: #212529;
            background-color: rgba(124, 58, 237, 0.1);
            transform: translateX(3px);
        }
        
        .secondary-sidebar .nav-link.active {
            color: #7c3aed;
            background-color: rgba(124, 58, 237, 0.15);
            font-weight: 500;
        }
        
        .secondary-sidebar .nav-link.active::before {
            content: '';
            position: absolute;
            left: 0;
            top: 50%;
            transform: translateY(-50%);
            width: 3px;
            height: 60%;
            background: #7c3aed;
            border-radius: 0 2px 2px 0;
        }
        
        /* Main Content Area */
        .main-content {
            margin-left: calc(var(--primary-sidebar-width) + var(--secondary-sidebar-width));
            min-height: 100vh;
            background-color: #f8f9fa;
            transition: all 0.3s ease;
        }
        
        /* Top Navigation */
        .top-navbar {
            background: white;
            border-bottom: 1px solid #dee2e6;
            padding: 1rem 2rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.04);
        }
        
        .content-wrapper {
            padding: 2rem;
        }
        
        /* Breadcrumb */
        .insights-breadcrumb {
            background: linear-gradient(135deg, #7c3aed 0%, #a855f7 100%);
            color: white;
            padding: 1rem 2rem;
            margin: -2rem -2rem 2rem -2rem;
            border-radius: 0 0 1rem 1rem;
        }
        
        .insights-breadcrumb .breadcrumb {
            margin: 0;
            background: transparent;
        }
        
        .insights-breadcrumb .breadcrumb-item a {
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
        }
        
        .insights-breadcrumb .breadcrumb-item.active {
            color: white;
            font-weight: 500;
        }
        
        /* Mobile Responsiveness */
        @media (max-width: 768px) {
            .primary-sidebar {
                transform: translateX(-100%);
            }
            
            .primary-sidebar.show {
                transform: translateX(0);
            }
            
            .secondary-sidebar {
                left: 0;
                transform: translateX(-100%);
            }
            
            .secondary-sidebar.show {
                transform: translateX(0);
            }
            
            .main-content {
                margin-left: 0;
            }
        }
        
        /* Utilities */
        .navbar-brand {
            color: white !important;
            font-weight: 600;
        }
        
        .btn-insights-primary {
            background: linear-gradient(135deg, #7c3aed 0%, #a855f7 100%);
            border: none;
            color: white;
            transition: all 0.3s ease;
        }
        
        .btn-insights-primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(124, 58, 237, 0.3);
            color: white;
        }
    </style>
    
    @stack('styles')
</head>
<body>
    <!-- Primary Sidebar (Main Navigation) -->
    <nav class="primary-sidebar" id="primarySidebar">
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
                    <a class="nav-link {{ request()->routeIs('digital-learning.*') ? 'active' : '' }}" href="{{ route('digital-learning.index') }}">
                        <i class="fas fa-robot me-2"></i>
                        AI Digital Learning
                    </a>
                </li>
                @endif
                
                @if(auth()->user()->hasModuleAccess('insights'))
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('insights.*') ? 'active' : '' }}" href="{{ route('insights.dashboard') }}">
                        <i class="fas fa-brain me-2"></i>
                        Executive Insights
                    </a>
                </li>
                @endif
                
                @if(auth()->user()->hasModuleAccess('settings'))
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('settings.*') ? 'active' : '' }}" href="{{ route('settings.index') }}">
                        <i class="bi bi-gear-wide-connected me-2"></i>
                        Settings & Control
                    </a>
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

    <!-- Secondary Sidebar (Executive Insights Sub-Navigation) -->
    <nav class="secondary-sidebar" id="secondarySidebar">
        <div class="secondary-header">
            <h5 class="mb-1">
                <i class="fas fa-brain me-2"></i>Executive Insights
            </h5>
            <small class="opacity-75">Strategic Intelligence Platform</small>
        </div>
        
        <div class="p-3">
            <ul class="nav nav-pills flex-column">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('insights.dashboard') ? 'active' : '' }}" 
                       href="{{ route('insights.dashboard') }}">
                        <i class="fas fa-chart-line me-2"></i>Executive Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('insights.ai-chat') ? 'active' : '' }}" 
                       href="{{ route('insights.ai-chat') }}">
                        <i class="fas fa-comments me-2"></i>AI Assistant
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('insights.analytics') ? 'active' : '' }}" 
                       href="{{ route('insights.analytics') }}">
                        <i class="fas fa-project-diagram me-2"></i>Advanced Analytics
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('insights.alerts') ? 'active' : '' }}" 
                       href="{{ route('insights.alerts') }}">
                        <i class="fas fa-exclamation-triangle me-2"></i>Alerts & Exceptions
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('insights.reports') ? 'active' : '' }}" 
                       href="{{ route('insights.reports') }}">
                        <i class="fas fa-file-chart me-2"></i>Custom Reports
                    </a>
                </li>
            </ul>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Top Navigation -->
        <nav class="top-navbar">
            <div class="d-flex align-items-center">
                <button class="btn btn-outline-secondary d-md-none me-3" type="button" onclick="toggleSidebars()">
                    <i class="bi bi-list"></i>
                </button>
                <h4 class="mb-0 text-primary">@yield('page-title', 'Executive Insights')</h4>
            </div>
            
            <div class="d-flex align-items-center">
                <!-- User Dropdown -->
                <div class="dropdown">
                    <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="userDropdown" 
                       role="button" data-bs-toggle="dropdown">
                        <div class="me-3 text-end">
                            <div class="fw-bold">{{ auth()->user()->name }}</div>
                            <small class="text-muted">{{ auth()->user()->role->display_name }}</small>
                        </div>
                        <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center" 
                             style="width: 40px; height: 40px;">
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
        </nav>

        <!-- Page Content -->
        <div class="content-wrapper">
            <!-- Breadcrumb -->
            @if(!empty($breadcrumbs))
            <div class="insights-breadcrumb">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{ route('insights.dashboard') }}">
                                <i class="fas fa-brain me-1"></i>Executive Insights
                            </a>
                        </li>
                        @foreach($breadcrumbs as $crumb)
                            @if($loop->last)
                                <li class="breadcrumb-item active" aria-current="page">{{ $crumb['title'] }}</li>
                            @else
                                <li class="breadcrumb-item">
                                    <a href="{{ $crumb['url'] }}">{{ $crumb['title'] }}</a>
                                </li>
                            @endif
                        @endforeach
                    </ol>
                </nav>
            </div>
            @endif

            <!-- Flash Messages -->
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
        function toggleSidebars() {
            const primarySidebar = document.getElementById('primarySidebar');
            const secondarySidebar = document.getElementById('secondarySidebar');
            
            primarySidebar.classList.toggle('show');
            secondarySidebar.classList.toggle('show');
        }
        
        // Close sidebars when clicking outside on mobile
        document.addEventListener('click', function(event) {
            const primarySidebar = document.getElementById('primarySidebar');
            const secondarySidebar = document.getElementById('secondarySidebar');
            const toggleBtn = event.target.closest('[onclick="toggleSidebars()"]');
            
            if (!primarySidebar.contains(event.target) && 
                !secondarySidebar.contains(event.target) && 
                !toggleBtn && 
                window.innerWidth <= 768) {
                primarySidebar.classList.remove('show');
                secondarySidebar.classList.remove('show');
            }
        });
    </script>
    
    @stack('scripts')
</body>
</html>
