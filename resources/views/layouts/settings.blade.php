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
    
    <title>@yield('title', 'Settings & Control') - {{ config('app.name', 'ShuleSoft Group Connect') }}</title>
    
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
            --settings-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --settings-secondary: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            
            /* Theme Variables */
            --bg-primary: #ffffff;
            --bg-secondary: #f8f9fa;
            --bg-tertiary: #e9ecef;
            --text-primary: #212529;
            --text-secondary: #6c757d;
            --text-muted: #adb5bd;
            --border-color: #dee2e6;
            --border-light: #e9ecef;
            
            /* Interactive Elements */
            --link-color: #0d6efd;
            --link-hover: #0b5ed7;
            --btn-primary: #0d6efd;
            --btn-primary-hover: #0b5ed7;
            --btn-secondary: #6c757d;
            --btn-secondary-hover: #5c636a;
            
            /* Status Colors */
            --success: #198754;
            --warning: #ffc107;
            --danger: #dc3545;
            --info: #0dcaf0;
            
            /* Shadows */
            --shadow-sm: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
            --shadow-md: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
            --shadow-lg: 0 1rem 3rem rgba(0, 0, 0, 0.175);
            
            /* Focus */
            --focus-ring: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
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
            
            /* Interactive Elements */
            --link-color: #66b3ff;
            --link-hover: #4da6ff;
            --btn-primary: #0d6efd;
            --btn-primary-hover: #0b5ed7;
            --btn-secondary: #495057;
            --btn-secondary-hover: #3d4144;
            
            /* Status Colors */
            --success: #20c997;
            --warning: #ffc107;
            --danger: #dc3545;
            --info: #0dcaf0;
            
            /* Shadows */
            --shadow-sm: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.3);
            --shadow-md: 0 0.5rem 1rem rgba(0, 0, 0, 0.4);
            --shadow-lg: 0 1rem 3rem rgba(0, 0, 0, 0.5);
            
            /* Focus */
            --focus-ring: 0 0 0 0.25rem rgba(102, 179, 255, 0.25);
            
            /* Settings Specific Dark Theme */
            --settings-gradient: linear-gradient(135deg, #4a5568 0%, #2d3748 100%);
            --settings-secondary: linear-gradient(135deg, #232631 0%, #2a2d3a 100%);
            
            /* Table Styles for Dark Theme */
            --table-bg: #232631;
            --table-text: #e9ecef;
            --table-border: #3d4144;
        }

        .table[data-theme="dark"] {
            background-color: var(--table-bg);
            color: var(--table-text);
        }

        .table[data-theme="dark"] th,
        .table[data-theme="dark"] td {
            border-color: var(--table-border);
        }

        /* Base Theme Support */
        * {
            transition: background-color 0.3s ease, color 0.3s ease, border-color 0.3s ease;
        }

        body {
            background-color: var(--bg-secondary);
            color: var(--text-primary);
        }

        /* Theme Toggle */
        .theme-toggle {
            position: fixed;
            top: 1rem;
            right: 1rem;
            z-index: 1200;
            background: var(--bg-primary);
            border: 2px solid var(--border-color);
            border-radius: 50%;
            width: 45px;
            height: 45px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: var(--shadow-md);
        }

        .theme-toggle:hover {
            transform: scale(1.1);
            border-color: var(--btn-primary);
            box-shadow: var(--shadow-lg);
        }

        .theme-toggle:focus {
            outline: none;
            box-shadow: var(--focus-ring);
        }

        .theme-toggle .icon {
            font-size: 1.1rem;
            color: var(--text-primary);
            transition: transform 0.3s ease;
        }

        .theme-toggle:hover .icon {
            transform: rotate(180deg);
        }

        [data-theme="light"] .theme-toggle .dark-icon { display: none; }
        [data-theme="light"] .theme-toggle .light-icon { display: block; }
        [data-theme="dark"] .theme-toggle .dark-icon { display: block; }
        [data-theme="dark"] .theme-toggle .light-icon { display: none; }
        
        /* Primary Sidebar (Main Navigation) */
        .primary-sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: var(--primary-sidebar-width);
            height: 100vh;
            background: var(--settings-gradient);
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
        
        /* Secondary Sidebar (Settings Sub-Navigation) */
        .secondary-sidebar {
            position: fixed;
            top: 0;
            left: var(--primary-sidebar-width);
            width: var(--secondary-sidebar-width);
            height: 100vh;
            background: var(--settings-secondary);
            border-right: 1px solid var(--border-color);
            z-index: 1050;
            transition: all 0.3s ease;
            box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1);
        }
        
        .secondary-sidebar .secondary-header {
            background: linear-gradient(135deg, #059669 0%, #10b981 100%);
            color: white;
            padding: 1.5rem 1rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .secondary-sidebar .nav-link {
            color: var(--text-primary);
            border-radius: 0.375rem;
            margin: 0.1rem 0.75rem;
            padding: 0.625rem 1rem;
            transition: all 0.3s ease;
            font-size: 0.9rem;
            position: relative;
        }
        
        .secondary-sidebar .nav-link:hover {
            color: #212529;
            background-color: rgba(5, 150, 105, 0.1);
            transform: translateX(3px);
        }
        
        .secondary-sidebar .nav-link.active {
            color: #059669;
            background-color: rgba(5, 150, 105, 0.15);
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
            background: #059669;
            border-radius: 0 2px 2px 0;
        }
        
        /* Main Content Area */
        .main-content {
            margin-left: calc(var(--primary-sidebar-width) + var(--secondary-sidebar-width));
            min-height: 100vh;
            background-color: var(--bg-secondary);
            transition: all 0.3s ease;
        }
        
        /* Top Navigation */
        .top-navbar {
            background: var(--bg-primary);
            border-bottom: 1px solid var(--border-color);
            padding: 1rem 2rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: var(--shadow-sm);
            color: var(--text-primary);
        }
        
        .content-wrapper {
            padding: 2rem;
        }

        /* Card Styles */
        .card {
            background: var(--bg-primary);
            border: 1px solid var(--border-color);
            color: var(--text-primary);
        }

        .card-body {
            color: var(--text-primary);
        }

        .card-header {
            background-color: var(--bg-secondary);
            border-bottom: 1px solid var(--border-color);
            color: var(--text-primary);
        }

        /* Form Elements */
        .form-control {
            background-color: var(--bg-primary);
            border-color: var(--border-color);
            color: var(--text-primary);
        }

        .form-control:focus {
            background-color: var(--bg-primary);
            border-color: var(--btn-primary);
            color: var(--text-primary);
            box-shadow: var(--focus-ring);
        }

        .form-select {
            background-color: var(--bg-primary);
            border-color: var(--border-color);
            color: var(--text-primary);
        }

        .form-select:focus {
            border-color: var(--btn-primary);
            box-shadow: var(--focus-ring);
        }

        /* ===== COMPREHENSIVE TABLE STYLES ===== */
        .table {
            background: var(--bg-primary) !important;
            color: var(--text-primary) !important;
            border-color: var(--border-color) !important;
        }

        .table th {
            background-color: var(--bg-secondary) !important;
            border-color: var(--border-color) !important;
            color: var(--text-primary) !important;
            font-weight: 600;
        }

        .table td {
            border-color: var(--border-light) !important;
            color: var(--text-primary) !important;
            background-color: var(--bg-primary) !important;
        }

        .table-hover tbody tr:hover {
            background-color: var(--bg-tertiary) !important;
            color: var(--text-primary) !important;
        }

        .table-hover tbody tr:hover td {
            background-color: var(--bg-tertiary) !important;
            color: var(--text-primary) !important;
        }

        /* Bootstrap table variants for dark mode */
        .table-light {
            background-color: var(--bg-secondary) !important;
            border-color: var(--border-color) !important;
            color: var(--text-primary) !important;
        }

        .table-light th,
        .table-light td {
            background-color: var(--bg-secondary) !important;
            border-color: var(--border-color) !important;
            color: var(--text-primary) !important;
        }

        /* Specific dark mode overrides */
        [data-theme="dark"] .table {
            background-color: var(--bg-primary) !important;
            color: var(--text-primary) !important;
        }

        [data-theme="dark"] .table th {
            background-color: var(--bg-secondary) !important;
            color: var(--text-primary) !important;
            border-color: var(--border-color) !important;
        }

        [data-theme="dark"] .table td {
            background-color: var(--bg-primary) !important;
            color: var(--text-primary) !important;
            border-color: var(--border-light) !important;
        }

        [data-theme="dark"] .table-hover tbody tr:hover {
            background-color: var(--bg-tertiary) !important;
        }

        [data-theme="dark"] .table-hover tbody tr:hover td {
            background-color: var(--bg-tertiary) !important;
            color: var(--text-primary) !important;
        }

        [data-theme="dark"] .table-striped tbody tr:nth-of-type(odd) {
            background-color: var(--bg-secondary) !important;
        }

        [data-theme="dark"] .table-striped tbody tr:nth-of-type(odd) td {
            background-color: var(--bg-secondary) !important;
        }

        /* ===== BOOTSTRAP TABLE-LIGHT OVERRIDES ===== */
        [data-theme="dark"] .table-light {
            --bs-table-color: var(--text-primary) !important;
            --bs-table-bg: var(--bg-secondary) !important;
            --bs-table-border-color: var(--border-color) !important;
            --bs-table-striped-bg: var(--bg-tertiary) !important;
            --bs-table-striped-color: var(--text-primary) !important;
            --bs-table-active-bg: var(--bg-tertiary) !important;
            --bs-table-active-color: var(--text-primary) !important;
            --bs-table-hover-bg: var(--bg-tertiary) !important;
            --bs-table-hover-color: var(--text-primary) !important;
            background-color: var(--bg-secondary) !important;
            color: var(--text-primary) !important;
        }

        [data-theme="dark"] .table-light th,
        [data-theme="dark"] .table-light td,
        [data-theme="dark"] .table-light tr {
            background-color: var(--bg-secondary) !important;
            color: var(--text-primary) !important;
            border-color: var(--border-color) !important;
        }

        [data-theme="dark"] thead.table-light th {
            background-color: var(--bg-secondary) !important;
            color: var(--text-primary) !important;
            border-color: var(--border-color) !important;
        }

        /* Table responsive wrapper */
        .table-responsive {
            background-color: var(--bg-primary) !important;
            border: 1px solid var(--border-color);
            border-radius: 0.375rem;
        }

        /* Aggressive overrides for stubborn Bootstrap classes */
        .table.table-hover,
        .table.table-striped,
        .table.table-bordered {
            background-color: var(--bg-primary) !important;
            color: var(--text-primary) !important;
        }

        .table.table-hover th,
        .table.table-striped th,
        .table.table-bordered th {
            background-color: var(--bg-secondary) !important;
            color: var(--text-primary) !important;
            border-color: var(--border-color) !important;
        }

        .table.table-hover td,
        .table.table-striped td,
        .table.table-bordered td {
            background-color: var(--bg-primary) !important;
            color: var(--text-primary) !important;
            border-color: var(--border-light) !important;
        }

        /* ===== UNIVERSAL TABLE DARK MODE ENFORCEMENT ===== */
        [data-theme="dark"] table,
        [data-theme="dark"] table *,
        [data-theme="dark"] .table,
        [data-theme="dark"] .table * {
            color: var(--text-primary) !important;
        }

        [data-theme="dark"] table th,
        [data-theme="dark"] .table th,
        [data-theme="dark"] thead th,
        [data-theme="dark"] thead.table-light th {
            background-color: var(--bg-secondary) !important;
            color: var(--text-primary) !important;
            border-color: var(--border-color) !important;
        }

        [data-theme="dark"] table td,
        [data-theme="dark"] .table td,
        [data-theme="dark"] tbody td {
            background-color: var(--bg-primary) !important;
            color: var(--text-primary) !important;
            border-color: var(--border-light) !important;
        }

        [data-theme="dark"] table tr,
        [data-theme="dark"] .table tr {
            background-color: var(--bg-primary) !important;
            color: var(--text-primary) !important;
        }

        /* Text Colors */
        .text-primary {
            color: var(--text-primary) !important;
        }

        .text-secondary {
            color: var(--text-secondary) !important;
        }

        .text-muted {
            color: var(--text-muted) !important;
        }

        /* Button Styles */
        .btn {
            transition: all 0.3s ease;
        }

        .btn-primary {
            background-color: var(--btn-primary);
            border-color: var(--btn-primary);
        }

        .btn-primary:hover {
            background-color: var(--btn-primary-hover);
            border-color: var(--btn-primary-hover);
        }

        .btn-secondary {
            background-color: var(--btn-secondary);
            border-color: var(--btn-secondary);
        }

        .btn-secondary:hover {
            background-color: var(--btn-secondary-hover);
            border-color: var(--btn-secondary-hover);
        }
        
        /* Breadcrumb */
        .settings-breadcrumb {
            background: linear-gradient(135deg, #059669 0%, #10b981 100%);
            color: white;
            padding: 1rem 2rem;
            margin: -2rem -2rem 2rem -2rem;
            border-radius: 0 0 1rem 1rem;
        }
        
        .settings-breadcrumb .breadcrumb {
            margin: 0;
            background: transparent;
        }
        
        .settings-breadcrumb .breadcrumb-item a {
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
        }
        
        .settings-breadcrumb .breadcrumb-item.active {
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

            .theme-toggle {
                top: 0.5rem;
                right: 0.5rem;
                width: 40px;
                height: 40px;
                z-index: 1300;
            }

            .theme-toggle .icon {
                font-size: 1rem;
            }
        }
        
        /* Utilities */
        .navbar-brand {
            color: white !important;
            font-weight: 600;
        }
        
        .btn-settings-primary {
            background: linear-gradient(135deg, #059669 0%, #10b981 100%);
            border: none;
            color: white;
            transition: all 0.3s ease;
        }
        
        .btn-settings-primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(5, 150, 105, 0.3);
            color: white;
        }

        /* Additional Dark Mode Support */
        .bg-light {
            background-color: var(--bg-secondary) !important;
        }

        .bg-white {
            background-color: var(--bg-primary) !important;
        }

        .border {
            border-color: var(--border-color) !important;
        }

        .border-bottom {
            border-bottom-color: var(--border-color) !important;
        }

        .border-top {
            border-top-color: var(--border-color) !important;
        }

        .list-group-item {
            background-color: var(--bg-primary);
            border-color: var(--border-color);
            color: var(--text-primary);
        }

        .list-group-item:hover {
            background-color: var(--bg-secondary);
        }

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

        .alert {
            border: 1px solid var(--border-color);
        }

        .alert-success {
            background-color: var(--success);
            color: white;
            border-color: var(--success);
        }

        .alert-danger {
            background-color: var(--danger);
            color: white;
            border-color: var(--danger);
        }

        .modal-content {
            background-color: var(--bg-primary);
            border: 1px solid var(--border-color);
        }

        .modal-header {
            border-bottom: 1px solid var(--border-color);
        }

        .modal-footer {
            border-top: 1px solid var(--border-color);
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
                <!-- <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('digital-learning.*') ? 'active' : '' }}" href="{{ route('digital-learning.index') }}">
                        <i class="fas fa-robot me-2"></i>
                        AI Digital Learning
                    </a>
                </li> -->
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

    <!-- Secondary Sidebar (Settings Sub-Navigation) -->
    <nav class="secondary-sidebar" id="secondarySidebar">
        <div class="secondary-header">
            <h5 class="mb-1">
                <i class="bi bi-gear-wide-connected me-2"></i>Settings & Control
            </h5>
            <small class="opacity-75">System Administration</small>
        </div>
        
        <div class="p-3">
            <ul class="nav nav-pills flex-column">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('settings.index') ? 'active' : '' }}" 
                       href="{{ route('settings.index') }}">
                        <i class="bi bi-house-gear me-2"></i>Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('settings.users') ? 'active' : '' }}" 
                       href="{{ route('settings.users') }}">
                        <i class="bi bi-people-fill me-2"></i>User Management
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('settings.schools') ? 'active' : '' }}" 
                       href="{{ route('settings.schools') }}">
                        <i class="bi bi-building-fill me-2"></i>School Management
                    </a>
                </li>
                <!-- <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('settings.academic-years') ? 'active' : '' }}" 
                       href="{{ route('settings.academic-years') }}">
                        <i class="bi bi-calendar-event me-2"></i>Academic Years
                    </a>
                </li> -->
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('settings.roles-permissions') ? 'active' : '' }}" 
                       href="{{ route('settings.roles-permissions') }}">
                        <i class="bi bi-shield-lock-fill me-2"></i>Roles & Permissions
                    </a>
                </li>
                <!-- <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('settings.system-config') ? 'active' : '' }}" 
                       href="{{ route('settings.system-config') }}">
                        <i class="bi bi-gear-wide-connected me-2"></i>System Config
                    </a>
                </li> -->
                <!-- <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('settings.bulk-operations') ? 'active' : '' }}" 
                       href="{{ route('settings.bulk-operations') }}">
                        <i class="bi bi-lightning-fill me-2"></i>Bulk Operations
                    </a>
                </li> -->
                <!-- <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('settings.audit-logs') ? 'active' : '' }}" 
                       href="{{ route('settings.audit-logs') }}">
                        <i class="bi bi-clipboard-data me-2"></i>Audit Logs
                    </a>
                </li> -->
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
                <h4 class="mb-0 text-primary">@yield('page-title', 'Settings & Control')</h4>
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
            <div class="settings-breadcrumb">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{ route('settings.index') }}">
                                <i class="bi bi-gear-wide-connected me-1"></i>Settings & Control
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
    </script>
    
    @stack('scripts')
</body>
</html>
