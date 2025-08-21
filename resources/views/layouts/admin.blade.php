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
    
    <title>@yield('title', 'Dashboard') - {{ config('app.name', 'ShuleSoft Group Connect') }}</title>
    
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
            --sidebar-width: 280px;
            
            /* Light Theme Colors */
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
            
            /* Sidebar Gradients */
            --sidebar-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --sidebar-text: rgba(255, 255, 255, 0.8);
            --sidebar-text-active: #ffffff;
            --sidebar-bg-hover: rgba(255, 255, 255, 0.1);
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
            
            /* Sidebar Gradients */
            --sidebar-gradient: linear-gradient(135deg, #4a5568 0%, #2d3748 100%);
            --sidebar-text: rgba(255, 255, 255, 0.8);
            --sidebar-text-active: #ffffff;
            --sidebar-bg-hover: rgba(255, 255, 255, 0.15);
        }

        /* ===== BASE STYLES ===== */
        * {
            transition: background-color 0.3s ease, color 0.3s ease, border-color 0.3s ease;
        }

        body {
            background-color: var(--bg-secondary);
            color: var(--text-primary);
        }

        /* ===== DARK MODE GLOBAL OVERRIDES ===== */
        /* Force proper text colors in dark mode to prevent white-on-white issues */
        [data-theme="dark"] {
            color-scheme: dark;
        }

        [data-theme="dark"] * {
            color: var(--text-primary) !important;
        }

        [data-theme="dark"] .table,
        [data-theme="dark"] .table * {
            background-color: var(--bg-primary) !important;
            color: var(--text-primary) !important;
        }

        [data-theme="dark"] .table th,
        [data-theme="dark"] .table td {
            background-color: var(--bg-primary) !important;
            color: var(--text-primary) !important;
            border-color: var(--border-color) !important;
        }

        /* Override Bootstrap's default table styling in dark mode */
        [data-theme="dark"] .table-striped tbody tr:nth-of-type(odd) td,
        [data-theme="dark"] .table-striped tbody tr:nth-of-type(odd) th {
            background-color: var(--bg-secondary) !important;
            color: var(--text-primary) !important;
        }

        /* Ensure table headers are distinct in dark mode */
        [data-theme="dark"] .table thead th {
            background-color: var(--bg-secondary) !important;
            color: var(--text-primary) !important;
            border-bottom: 2px solid var(--border-color) !important;
        }

        /* ===== THEME TOGGLE ===== */
        .theme-toggle {
            position: fixed;
            top: 1rem;
            right: 5rem;
            z-index: 1050;
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

        /* ===== SIDEBAR ===== */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: var(--sidebar-width);
            height: 100vh;
            background: var(--sidebar-gradient);
            z-index: 1000;
            transition: all 0.3s ease;
        }
        
        .sidebar .nav-link {
            color: var(--sidebar-text);
            border-radius: 0.5rem;
            margin: 0.2rem 0.5rem;
            padding: 0.75rem 1rem;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        
        .sidebar .nav-link::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.1), transparent);
            transition: left 0.5s ease;
        }
        
        .sidebar .nav-link:hover::before {
            left: 100%;
        }
        
        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            color: var(--sidebar-text-active);
            background-color: var(--sidebar-bg-hover);
            backdrop-filter: blur(10px);
            transform: translateX(5px);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }
        
        .navbar-brand {
            color: var(--sidebar-text-active) !important;
            font-weight: 600;
        }

        /* ===== MAIN CONTENT ===== */
        .main-content {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
            background-color: var(--bg-secondary);
        }
        
        .content-wrapper {
            padding: 2rem;
        }

        /* ===== CARDS ===== */
        .stats-card, .card {
            background: var(--bg-primary);
            border: 1px solid var(--border-color);
            border-radius: 1rem;
            box-shadow: var(--shadow-sm);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        
        .stats-card:hover, .card:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
        }

        /* ===== NAVIGATION ===== */
        .navbar {
            background: var(--bg-primary) !important;
            border-bottom: 1px solid var(--border-color);
        }

        .navbar .nav-link {
            color: var(--text-primary) !important;
        }

        .navbar .navbar-nav .nav-link {
            color: var(--text-primary) !important;
        }

        .navbar .fw-bold {
            color: var(--text-primary) !important;
        }

        .navbar .text-muted {
            color: var(--text-secondary) !important;
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

        .dropdown-divider {
            border-color: var(--border-color);
        }

        /* User Avatar */
        .bg-primary {
            background-color: var(--btn-primary) !important;
        }

        /* ===== TABLES ===== */
        .table {
            background: var(--bg-primary);
            border-radius: 0.5rem;
            overflow: hidden;
            box-shadow: var(--shadow-sm);
            color: var(--text-primary) !important;
        }

        .table th {
            background-color: var(--bg-secondary);
            border-color: var(--border-color);
            color: var(--text-primary) !important;
            font-weight: 600;
        }

        .table td {
            border-color: var(--border-light);
            color: var(--text-primary) !important;
        }

        .table-striped > tbody > tr:nth-of-type(odd) > td {
            background-color: var(--bg-secondary);
        }

        /* Table text elements */
        .table td *,
        .table th * {
            color: inherit !important;
        }

        /* Dark mode specific table styling - Force proper colors */
        [data-theme="dark"] .table {
            background: var(--bg-primary) !important;
            color: var(--text-primary) !important;
        }

        [data-theme="dark"] .table thead th {
            background-color: var(--bg-secondary) !important;
            border-color: var(--border-color) !important;
            color: var(--text-primary) !important;
        }

        [data-theme="dark"] .table tbody td {
            background-color: var(--bg-primary) !important;
            border-color: var(--border-color) !important;
            color: var(--text-primary) !important;
        }

        [data-theme="dark"] .table tbody tr {
            background-color: var(--bg-primary) !important;
            color: var(--text-primary) !important;
        }

        [data-theme="dark"] .table-striped > tbody > tr:nth-of-type(odd) {
            background-color: var(--bg-secondary) !important;
        }

        [data-theme="dark"] .table-striped > tbody > tr:nth-of-type(odd) > td {
            background-color: var(--bg-secondary) !important;
            color: var(--text-primary) !important;
        }

        [data-theme="dark"] .table tbody tr td {
            color: var(--text-primary) !important;
        }

        /* Ensure all nested elements in tables are visible in dark mode */
        [data-theme="dark"] .table td *,
        [data-theme="dark"] .table th *,
        [data-theme="dark"] .table tbody tr td *,
        [data-theme="dark"] .table thead tr th * {
            color: var(--text-primary) !important;
        }

        /* Override any Bootstrap text utilities that might interfere */
        [data-theme="dark"] .table .text-dark,
        [data-theme="dark"] .table .text-body,
        [data-theme="dark"] .table .text-black,
        [data-theme="dark"] .table .text-muted {
            color: var(--text-primary) !important;
        }

        /* Table hover effect - should be subtle in dark mode */
        [data-theme="dark"] .table tbody tr:hover {
            background-color: var(--bg-tertiary) !important;
        }

        [data-theme="dark"] .table tbody tr:hover td {
            background-color: var(--bg-tertiary) !important;
            color: var(--text-primary) !important;
        }

        /* Table badges and labels - ensure visibility in dark mode */
        .table .badge {
            color: #ffffff !important;
            font-weight: 600;
        }

        .table .badge-success,
        .table .bg-success {
            background-color: var(--success) !important;
            color: #ffffff !important;
        }

        .table .badge-danger,
        .table .bg-danger {
            background-color: var(--danger) !important;
            color: #ffffff !important;
        }

        .table .badge-warning,
        .table .bg-warning {
            background-color: var(--warning) !important;
            color: #000000 !important;
        }

        .table .badge-info,
        .table .bg-info {
            background-color: var(--info) !important;
            color: #000000 !important;
        }

        .table .badge-primary,
        .table .bg-primary {
            background-color: var(--btn-primary) !important;
            color: #ffffff !important;
        }

        .table .badge-secondary,
        .table .bg-secondary {
            background-color: var(--btn-secondary) !important;
            color: #ffffff !important;
        }

        /* Additional badge classes that might be used */
        .table .badge-light {
            background-color: var(--bg-tertiary) !important;
            color: var(--text-primary) !important;
        }

        .table .badge-dark {
            background-color: var(--text-primary) !important;
            color: var(--bg-primary) !important;
        }

        /* Red percentage badges specifically (as seen in your image) */
        .table .badge.bg-danger,
        .table .badge-danger,
        .table span[class*="bg-danger"],
        .table span[class*="badge-danger"] {
            background-color: #dc3545 !important;
            color: #ffffff !important;
            padding: 0.25em 0.6em !important;
            font-size: 0.875em !important;
            font-weight: 600 !important;
            border-radius: 0.375rem !important;
        }

        /* Green percentage badges */
        .table .badge.bg-success,
        .table .badge-success,
        .table span[class*="bg-success"],
        .table span[class*="badge-success"] {
            background-color: #198754 !important;
            color: #ffffff !important;
            padding: 0.25em 0.6em !important;
            font-size: 0.875em !important;
            font-weight: 600 !important;
            border-radius: 0.375rem !important;
        }

        /* Table links */
        .table a {
            color: var(--link-color) !important;
        }

        .table a:hover {
            color: var(--link-hover) !important;
        }

        /* Table row hover effect */
        .table tbody tr:hover {
            background-color: var(--bg-tertiary) !important;
        }

        /* Ensure all table content is visible */
        .table tbody tr td,
        .table thead tr th {
            vertical-align: middle;
        }

        /* Table responsive wrapper */
        .table-responsive {
            background: var(--bg-primary);
            border-radius: 0.5rem;
        }

        /* Custom table status indicators */
        .table .status-indicator {
            display: inline-block;
            width: 8px;
            height: 8px;
            border-radius: 50%;
            margin-right: 0.5rem;
        }

        .table .status-indicator.success {
            background-color: var(--success);
        }

        .table .status-indicator.danger {
            background-color: var(--danger);
        }

        .table .status-indicator.warning {
            background-color: var(--warning);
        }

        /* Table percentage values and numbers */
        .table .percentage,
        .table .number-value {
            font-weight: 600;
            color: var(--text-primary) !important;
        }

        /* Dark mode specific table overrides */
        [data-theme="dark"] .table {
            background: var(--bg-primary) !important;
        }

        [data-theme="dark"] .table th,
        [data-theme="dark"] .table td {
            border-color: var(--border-color) !important;
            color: var(--text-primary) !important;
        }

        [data-theme="dark"] .table tbody tr:hover td {
            background-color: var(--bg-tertiary) !important;
            color: var(--text-primary) !important;
        }

        [data-theme="dark"] .table-striped > tbody > tr:nth-of-type(odd) > td {
            background-color: var(--bg-secondary) !important;
            color: var(--text-primary) !important;
        }

        /* ===== BUTTONS ===== */
        .btn {
            transition: all 0.3s ease;
        }

        .btn:focus {
            box-shadow: var(--focus-ring);
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

        .btn-outline-secondary {
            color: var(--text-secondary);
            border-color: var(--border-color);
        }

        .btn-outline-secondary:hover {
            background-color: var(--btn-secondary);
            border-color: var(--btn-secondary);
        }

        /* ===== FORMS ===== */
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

        .form-control::placeholder {
            color: var(--text-muted);
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

        /* ===== ALERTS ===== */
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

        /* ===== LINKS ===== */
        a {
            color: var(--link-color);
        }

        a:hover {
            color: var(--link-hover);
        }

        /* ===== BOOTSTRAP TEXT UTILITIES OVERRIDE ===== */
        /* Ensure all Bootstrap text utilities work in dark mode */
        [data-theme="dark"] .text-dark {
            color: var(--text-primary) !important;
        }

        [data-theme="dark"] .text-muted {
            color: var(--text-secondary) !important;
        }

        [data-theme="dark"] .text-body {
            color: var(--text-primary) !important;
        }

        [data-theme="dark"] .text-black {
            color: var(--text-primary) !important;
        }

        [data-theme="dark"] .text-black-50 {
            color: var(--text-secondary) !important;
        }

        [data-theme="dark"] .text-white {
            color: var(--text-primary) !important;
        }

        /* Aggressive Bootstrap override for tables */
        [data-theme="dark"] .table-dark {
            background-color: var(--bg-primary) !important;
            color: var(--text-primary) !important;
        }

        [data-theme="dark"] .table-light {
            background-color: var(--bg-primary) !important;
            color: var(--text-primary) !important;
        }

        /* Force all table elements to use dark theme colors */
        [data-theme="dark"] .table,
        [data-theme="dark"] .table-responsive .table {
            --bs-table-bg: var(--bg-primary) !important;
            --bs-table-color: var(--text-primary) !important;
            --bs-table-border-color: var(--border-color) !important;
            --bs-table-striped-bg: var(--bg-secondary) !important;
            --bs-table-hover-bg: var(--bg-tertiary) !important;
            background-color: var(--bg-primary) !important;
            color: var(--text-primary) !important;
        }

        /* Ensure percentage badges and labels are visible */
        [data-theme="dark"] .badge,
        [data-theme="dark"] .label {
            color: #ffffff !important;
        }

        /* Override any inherited Bootstrap classes that might cause invisible text */
        [data-theme="dark"] .table .text-dark,
        [data-theme="dark"] .table .text-body,
        [data-theme="dark"] .table .text-black {
            color: var(--text-primary) !important;
        }

        /* Ensure all table cell content is visible */
        [data-theme="dark"] .table td > *,
        [data-theme="dark"] .table th > * {
            color: inherit !important;
        }

        /* Special handling for progress bars and percentage displays */
        [data-theme="dark"] .progress {
            background-color: var(--bg-tertiary) !important;
        }

        [data-theme="dark"] .progress-bar {
            color: #ffffff !important;
        }

        /* Additional table content overrides */
        [data-theme="dark"] .table tbody,
        [data-theme="dark"] .table thead,
        [data-theme="dark"] .table tfoot {
            background-color: var(--bg-primary) !important;
            color: var(--text-primary) !important;
        }

        [data-theme="dark"] .table tr {
            background-color: var(--bg-primary) !important;
            color: var(--text-primary) !important;
        }

        /* ===== MOBILE RESPONSIVENESS ===== */
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

            .theme-toggle {
                top: 0.5rem;
                right: 3.5rem;
                width: 40px;
                height: 40px;
                z-index: 1060;
            }

            .theme-toggle .icon {
                font-size: 1rem;
            }
        }

        /* Additional text color fixes */
        .text-primary {
            color: var(--text-primary) !important;
        }

        .text-secondary {
            color: var(--text-secondary) !important;
        }

        .text-muted {
            color: var(--text-muted) !important;
        }

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
                
                <!-- @if(auth()->user()->hasModuleAccess('digital_learning'))
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('digital-learning.*') ? 'active' : '' }}" href="{{ route('digital-learning.index') }}">
                        <i class="fas fa-robot me-2"></i>
                        AI Digital Learning
                    </a>
                </li>
                @endif -->
          
                
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
                            <!-- <li><a class="dropdown-item" href="#"><i class="bi bi-person me-2"></i>Profile</a></li>
                            <li><a class="dropdown-item" href="#"><i class="bi bi-gear me-2"></i>Settings</a></li> -->
                            <li><a class="dropdown-item" href="{{ route('user-guide') }}" target="_blank"><i class="bi bi-book me-2"></i>User Guide</a></li>
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

        // Add smooth transition effect when navigating to AI Digital Learning
        document.addEventListener('DOMContentLoaded', function() {
            const aiDigitalLearningLink = document.querySelector('a[href*="digital-learning"]');
            
            if (aiDigitalLearningLink) {
                aiDigitalLearningLink.addEventListener('click', function(e) {
                    // Add visual feedback
                    this.style.transform = 'translateX(8px) scale(1.02)';
                    this.style.boxShadow = '0 6px 20px rgba(0, 0, 0, 0.15)';
                    
                    // Create a smooth transition overlay
                    const overlay = document.createElement('div');
                    overlay.style.cssText = `
                        position: fixed;
                        top: 0;
                        left: 0;
                        width: 100%;
                        height: 100%;
                        background: linear-gradient(135deg, #4285f4 0%, #667eea 100%);
                        opacity: 0;
                        z-index: 9999;
                        transition: opacity 0.3s ease;
                        pointer-events: none;
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        color: white;
                        font-size: 1.2rem;
                        font-weight: 500;
                    `;
                    overlay.innerHTML = '<i class="fas fa-robot me-2"></i>Loading AI Digital Learning...';
                    
                    document.body.appendChild(overlay);
                    
                    // Animate overlay
                    setTimeout(() => {
                        overlay.style.opacity = '0.9';
                    }, 10);
                    
                    // Clean up after navigation starts
                    setTimeout(() => {
                        overlay.style.opacity = '0';
                        setTimeout(() => {
                            if (document.body.contains(overlay)) {
                                document.body.removeChild(overlay);
                            }
                        }, 300);
                    }, 500);
                });
            }
        });
    </script>
    
    @stack('scripts')
</body>
</html>
