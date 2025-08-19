<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <!-- Prevent FOUC - Theme detection and application -->
    <script>
        (function() {
            // Check for saved theme preference or default to system preference
            const savedTheme = localStorage.getItem('theme');
            const systemPrefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
            
            let theme;
            if (savedTheme) {
                theme = savedTheme;
            } else {
                theme = systemPrefersDark ? 'dark' : 'light';
            }
            
            document.documentElement.setAttribute('data-theme', theme);
        })();
    </script>
    
    <title>@yield('title', 'AI Digital Learning') - {{ config('app.name', 'ShuleSoft Group Connect') }}</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <!-- Custom CSS with Theme Support -->
    <style>
        /* ===== THEME VARIABLES ===== */
        :root {
            /* Layout */
            --primary-sidebar-width: 280px;
            --secondary-sidebar-width: 250px;
            
            /* Light Theme Colors */
            --bg-primary: #ffffff;
            --bg-secondary: #f8f9fa;
            --bg-tertiary: #e9ecef;
            --text-primary: #212529;
            --text-secondary: #6c757d;
            --text-muted: #adb5bd;
            --border-color: #dee2e6;
            --border-light: #e9ecef;
            
            /* AI Digital Learning Theme */
            --ai-primary: #4285f4;
            --ai-secondary: #667eea;
            --ai-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --ai-secondary-gradient: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            --ai-accent: rgba(66, 133, 244, 0.1);
            
            /* Shadows */
            --shadow-sm: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
            --shadow-md: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
            --shadow-lg: 0 1rem 3rem rgba(0, 0, 0, 0.175);
            
            /* Focus */
            --focus-ring: 0 0 0 0.25rem rgba(66, 133, 244, 0.25);
        }

        /* Dark Theme */
        [data-theme="dark"] {
            /* Background Colors */
            --bg-primary: #1a1d29;
            --bg-secondary: #232631;
            --bg-tertiary: #2a2d3a;
            --text-primary: #e9ecef;
            --text-secondary: #adb5bd;
            --text-muted: #6c757d;
            --border-color: #3d4144;
            --border-light: #495057;
            
            /* AI Digital Learning Dark Theme */
            --ai-primary: #66b3ff;
            --ai-secondary: #4da6ff;
            --ai-gradient: linear-gradient(135deg, #4a5568 0%, #2d3748 100%);
            --ai-secondary-gradient: linear-gradient(135deg, #2a2d3a 0%, #232631 100%);
            --ai-accent: rgba(102, 179, 255, 0.1);
            
            /* Shadows */
            --shadow-sm: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.3);
            --shadow-md: 0 0.5rem 1rem rgba(0, 0, 0, 0.4);
            --shadow-lg: 0 1rem 3rem rgba(0, 0, 0, 0.5);
            
            /* Focus */
            --focus-ring: 0 0 0 0.25rem rgba(102, 179, 255, 0.25);
        }

        /* ===== BASE STYLES ===== */
        * {
            transition: background-color 0.3s ease, color 0.3s ease, border-color 0.3s ease;
        }

        body {
            background-color: var(--bg-secondary);
            color: var(--text-primary);
        }

        /* ===== THEME TOGGLE ===== */
        .theme-toggle {
            position: fixed;
            top: 1rem;
            right: 1rem;
            z-index: 1200;
            background: var(--bg-primary);
            border: 2px solid var(--border-color);
            border-radius: 50%;
            width: 50px;
            height: 50px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: var(--shadow-md);
        }

        .theme-toggle:hover {
            transform: scale(1.1);
            border-color: var(--ai-primary);
            box-shadow: var(--shadow-lg);
        }

        .theme-toggle:focus {
            outline: none;
            box-shadow: var(--focus-ring);
        }

        .theme-toggle .icon {
            font-size: 1.25rem;
            color: var(--ai-primary);
            transition: transform 0.3s ease;
        }

        .theme-toggle:hover .icon {
            transform: rotate(180deg);
        }

        [data-theme="light"] .theme-toggle .dark-icon { display: none; }
        [data-theme="light"] .theme-toggle .light-icon { display: block; }
        [data-theme="dark"] .theme-toggle .dark-icon { display: block; }
        [data-theme="dark"] .theme-toggle .light-icon { display: none; }

        /* ===== PRIMARY SIDEBAR ===== */
        .primary-sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: var(--primary-sidebar-width);
            height: 100vh;
            background: var(--ai-gradient);
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
        
        .navbar-brand {
            color: white !important;
            font-weight: 600;
        }

        /* ===== SECONDARY SIDEBAR ===== */
        .secondary-sidebar {
            position: fixed;
            top: 0;
            left: var(--primary-sidebar-width);
            width: var(--secondary-sidebar-width);
            height: 100vh;
            background: var(--ai-secondary-gradient);
            border-right: 1px solid var(--border-color);
            z-index: 1050;
            transition: all 0.3s ease;
            box-shadow: var(--shadow-md);
        }
        
        .secondary-sidebar .secondary-header {
            background: linear-gradient(135deg, var(--ai-primary) 0%, var(--ai-secondary) 100%);
            color: white;
            padding: 1.5rem 1rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .secondary-sidebar .nav-link {
            color: var(--text-secondary);
            border-radius: 0.375rem;
            margin: 0.1rem 0.75rem;
            padding: 0.625rem 1rem;
            transition: all 0.3s ease;
            font-size: 0.9rem;
            position: relative;
        }
        
        .secondary-sidebar .nav-link:hover {
            color: var(--text-primary);
            background-color: var(--ai-accent);
            transform: translateX(3px);
        }
        
        .secondary-sidebar .nav-link.active {
            color: var(--ai-primary);
            background-color: var(--ai-accent);
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
            background: var(--ai-primary);
            border-radius: 0 2px 2px 0;
        }

        /* ===== MAIN CONTENT ===== */
        .main-content {
            margin-left: calc(var(--primary-sidebar-width) + var(--secondary-sidebar-width));
            min-height: 100vh;
            background-color: var(--bg-secondary);
            transition: all 0.3s ease;
        }
        
        .top-navbar {
            background: var(--bg-primary);
            border-bottom: 1px solid var(--border-color);
            padding: 1rem 2rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.04);
        }
        
        .content-wrapper {
            padding: 2rem;
        }

        /* ===== BREADCRUMB ===== */
        .ai-breadcrumb {
            background: linear-gradient(135deg, var(--ai-primary) 0%, var(--ai-secondary) 100%);
            color: white;
            padding: 1rem 2rem;
            margin: -2rem -2rem 2rem -2rem;
            border-radius: 0 0 1rem 1rem;
        }
        
        .ai-breadcrumb .breadcrumb {
            margin: 0;
            background: transparent;
        }
        
        .ai-breadcrumb .breadcrumb-item a {
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
        }
        
        .ai-breadcrumb .breadcrumb-item.active {
            color: white;
            font-weight: 500;
        }

        /* ===== CARDS ===== */
        .ai-card, .card {
            background: var(--bg-primary);
            border: 1px solid var(--border-color);
            border-radius: 1rem;
            box-shadow: var(--shadow-sm);
            transition: all 0.3s ease;
        }
        
        .ai-card:hover, .card:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
        }

        /* ===== BUTTONS ===== */
        .btn-ai-primary {
            background: linear-gradient(135deg, var(--ai-primary) 0%, var(--ai-secondary) 100%);
            border: none;
            color: white;
            transition: all 0.3s ease;
        }
        
        .btn-ai-primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(66, 133, 244, 0.3);
            color: white;
        }

        /* ===== DROPDOWN ===== */
        .dropdown-menu {
            background: var(--bg-primary);
            border: 1px solid var(--border-color);
            box-shadow: var(--shadow-md);
        }

        .dropdown-item {
            color: var(--text-primary);
        }

        .dropdown-item:hover {
            background-color: var(--bg-secondary);
            color: var(--text-primary);
        }

        /* ===== ANIMATIONS ===== */
        @keyframes slideInRight {
            from {
                opacity: 0;
                transform: translateX(30px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }
        
        .content-wrapper > * {
            animation: slideInRight 0.4s ease;
        }

        /* ===== MOBILE RESPONSIVENESS ===== */
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

            .theme-toggle {
                top: 0.5rem;
                right: 0.5rem;
                width: 40px;
                height: 40px;
            }

            .theme-toggle .icon {
                font-size: 1rem;
            }
        }
    </style>
    
    @stack('styles')
</head>
<body>
    <!-- Theme Toggle Button -->
    <button class="theme-toggle" 
            id="themeToggle" 
            aria-label="Toggle theme" 
            aria-pressed="false" 
            type="button">
        <i class="bi bi-sun-fill icon light-icon" aria-hidden="true"></i>
        <i class="bi bi-moon-fill icon dark-icon" aria-hidden="true"></i>
    </button>

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

    <!-- Secondary Sidebar (AI Digital Learning Sub-Navigation) -->
    <nav class="secondary-sidebar" id="secondarySidebar">
        <div class="secondary-header">
            <h5 class="mb-1">
                <i class="fas fa-robot me-2"></i>AI Digital Learning
            </h5>
            <small class="opacity-75">Intelligent Education Platform</small>
        </div>
        
        <div class="p-3">
            <ul class="nav nav-pills flex-column">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('digital-learning.index') ? 'active' : '' }}" 
                       href="{{ route('digital-learning.index') }}">
                        <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('digital-learning.exams') ? 'active' : '' }}" 
                       href="{{ route('digital-learning.exams') }}">
                        <i class="fas fa-robot me-2"></i>AI Exams
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('digital-learning.content') ? 'active' : '' }}" 
                       href="{{ route('digital-learning.content') }}">
                        <i class="fas fa-file-alt me-2"></i>Content Management
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('digital-learning.analytics') ? 'active' : '' }}" 
                       href="{{ route('digital-learning.analytics') }}">
                        <i class="fas fa-chart-bar me-2"></i>Analytics
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('digital-learning.ai-tools') ? 'active' : '' }}" 
                       href="{{ route('digital-learning.ai-tools') }}">
                        <i class="fas fa-magic me-2"></i>AI Tools
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
                <h4 class="mb-0 text-primary">@yield('page-title', 'AI Digital Learning')</h4>
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
            <div class="ai-breadcrumb">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{ route('digital-learning.index') }}">
                                <i class="fas fa-robot me-1"></i>AI Digital Learning
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
    
    <!-- Theme System JS -->
    <script>
        class ThemeManager {
            constructor() {
                this.init();
            }

            init() {
                this.setupEventListeners();
                this.watchSystemTheme();
                this.updateToggleButton();
            }

            setupEventListeners() {
                const toggle = document.getElementById('themeToggle');
                if (toggle) {
                    toggle.addEventListener('click', () => this.toggleTheme());
                    toggle.addEventListener('keydown', (e) => {
                        if (e.key === 'Enter' || e.key === ' ') {
                            e.preventDefault();
                            this.toggleTheme();
                        }
                    });
                }
            }

            getCurrentTheme() {
                return document.documentElement.getAttribute('data-theme') || 'light';
            }

            setTheme(theme) {
                document.documentElement.setAttribute('data-theme', theme);
                localStorage.setItem('theme', theme);
                localStorage.setItem('themeUserSelected', 'true');
                this.updateToggleButton();
            }

            toggleTheme() {
                const currentTheme = this.getCurrentTheme();
                const newTheme = currentTheme === 'light' ? 'dark' : 'light';
                this.setTheme(newTheme);
            }

            updateToggleButton() {
                const toggle = document.getElementById('themeToggle');
                if (!toggle) return;

                const currentTheme = this.getCurrentTheme();
                const isDark = currentTheme === 'dark';
                
                toggle.setAttribute('aria-pressed', isDark.toString());
                toggle.setAttribute('aria-label', 
                    isDark ? 'Switch to light mode' : 'Switch to dark mode'
                );
            }

            watchSystemTheme() {
                const mediaQuery = window.matchMedia('(prefers-color-scheme: dark)');
                
                mediaQuery.addEventListener('change', (e) => {
                    // Only auto-update if user hasn't manually selected a theme
                    const userSelected = localStorage.getItem('themeUserSelected');
                    if (!userSelected) {
                        const newTheme = e.matches ? 'dark' : 'light';
                        document.documentElement.setAttribute('data-theme', newTheme);
                        this.updateToggleButton();
                    }
                });
            }
        }

        // Initialize theme manager when DOM is ready
        document.addEventListener('DOMContentLoaded', () => {
            new ThemeManager();
        });
    </script>

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

        // Smooth animations for navigation transitions
        document.addEventListener('DOMContentLoaded', function() {
            const navLinks = document.querySelectorAll('.secondary-sidebar .nav-link');
            
            navLinks.forEach(link => {
                link.addEventListener('click', function(e) {
                    // Add loading state
                    this.style.opacity = '0.7';
                    this.style.transform = 'translateX(5px)';
                    
                    // Reset after short delay (allows page navigation)
                    setTimeout(() => {
                        this.style.opacity = '';
                        this.style.transform = '';
                    }, 200);
                });
            });
        });
    </script>
    
    @stack('scripts')
</body>
</html>
