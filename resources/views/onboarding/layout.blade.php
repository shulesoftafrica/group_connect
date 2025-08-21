<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'ShuleSoft Group Connect - Onboarding')</title>
    
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
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        /* ===== THEME VARIABLES ===== */
        :root {
            /* Brand Colors */
            --primary-color: #1eba9b;
            --primary-dark: #16a085;
            --secondary-color: #2c3e50;
            
            /* Light Theme Colors */
            --bg-primary: #ffffff;
            --bg-secondary: #f8f9fa;
            --bg-tertiary: #e9ecef;
            --text-primary: #2c3e50;
            --text-secondary: #6c757d;
            --text-muted: #adb5bd;
            --border-color: #dee2e6;
            --border-light: #e9ecef;
            
            /* Interactive Elements */
            --link-color: #1eba9b;
            --link-hover: #16a085;
            --btn-primary: #1eba9b;
            --btn-primary-hover: #16a085;
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
            --shadow-xl: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            
            /* Focus */
            --focus-ring: 0 0 0 0.25rem rgba(30, 186, 155, 0.25);
            
            /* Hero Gradient */
            --hero-gradient: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            --onboarding-gradient: linear-gradient(135deg, #1eba9b 0%, #16a085 100%);
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
            --link-color: #1eba9b;
            --link-hover: #16a085;
            --btn-primary: #1eba9b;
            --btn-primary-hover: #16a085;
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
            --shadow-xl: 0 25px 50px -12px rgba(0, 0, 0, 0.6);
            
            /* Focus */
            --focus-ring: 0 0 0 0.25rem rgba(30, 186, 155, 0.25);
            
            /* Hero Gradient */
            --hero-gradient: linear-gradient(135deg, #232631 0%, #2a2d3a 100%);
            --onboarding-gradient: linear-gradient(135deg, #1eba9b 0%, #16a085 100%);
        }

        /* ===== BASE STYLES ===== */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            transition: background-color 0.3s ease, color 0.3s ease, border-color 0.3s ease;
        }
        
        body {
            font-family: 'Inter', sans-serif;
            line-height: 1.6;
            color: var(--text-primary);
            background: var(--hero-gradient);
            min-height: 100vh;
        }
        
        /* ===== THEME TOGGLE ===== */
        .theme-toggle {
            position: fixed;
            top: 1rem;
            right: 1rem;
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
        
        .onboarding-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        
        .onboarding-card {
            background: var(--bg-primary);
            border-radius: 20px;
            box-shadow: var(--shadow-xl);
            overflow: hidden;
            max-width: 800px;
            width: 100%;
            border: 1px solid var(--border-color);
        }
        
        .card-header {
            background: var(--onboarding-gradient);
            color: white;
            padding: 30px;
            text-align: center;
        }
        
        .step-indicator {
            display: flex;
            justify-content: center;
            margin-bottom: 20px;
        }
        
        .step {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 10px;
            background: rgba(255,255,255,0.3);
            color: white;
            font-weight: bold;
            position: relative;
        }
        
        .step.active {
            background: white;
            color: var(--primary-color);
        }
        
        .step.completed {
            background: var(--success);
            color: white;
        }
        
        .step:not(:last-child)::after {
            content: '';
            position: absolute;
            right: -20px;
            top: 50%;
            transform: translateY(-50%);
            width: 20px;
            height: 2px;
            background: rgba(255,255,255,0.3);
        }
        
        .step.completed:not(:last-child)::after {
            background: var(--success);
        }
        
        .card-body {
            padding: 40px;
            background: var(--bg-primary);
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-label {
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 8px;
        }
        
        .form-control {
            border-radius: 10px;
            border: 2px solid var(--border-color);
            padding: 12px 15px;
            transition: all 0.3s ease;
            background: var(--bg-secondary);
            color: var(--text-primary);
        }
        
        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: var(--focus-ring);
            background: var(--bg-secondary);
            color: var(--text-primary);
        }
        
        .form-control::placeholder {
            color: var(--text-muted);
        }
        
        .btn-primary {
            background: var(--onboarding-gradient);
            border: none;
            border-radius: 10px;
            padding: 12px 30px;
            font-weight: 600;
            transition: transform 0.2s ease;
            color: white;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            background: linear-gradient(135deg, var(--primary-dark) 0%, #138f6b 100%);
            color: white;
        }
        
        .btn-secondary {
            background: var(--btn-secondary);
            border: 1px solid var(--border-color);
            border-radius: 10px;
            padding: 12px 30px;
            font-weight: 600;
            color: var(--text-primary);
            transition: all 0.3s ease;
        }
        
        .btn-secondary:hover {
            background: var(--btn-secondary-hover);
            border-color: var(--border-color);
            color: var(--text-primary);
        }
        
        .alert {
            border-radius: 10px;
            border: 1px solid var(--border-color);
            background: var(--bg-secondary);
            color: var(--text-primary);
        }
        
        .alert-success {
            background: rgba(32, 201, 151, 0.1);
            border-color: var(--success);
            color: var(--success);
        }
        
        .alert-danger {
            background: rgba(220, 53, 69, 0.1);
            border-color: var(--danger);
            color: var(--danger);
        }
        
        .brand-logo {
            font-size: 2rem;
            font-weight: bold;
            margin-bottom: 10px;
        }
        
        .brand-subtitle {
            opacity: 0.9;
            font-size: 1.1rem;
        }
        
        /* Additional element styles */
        h1, h2, h3, h4, h5, h6 {
            color: var(--text-primary);
        }
        
        p {
            color: var(--text-secondary);
        }
        
        .text-muted {
            color: var(--text-muted) !important;
        }
        
        .text-primary {
            color: var(--primary-color) !important;
        }
        
        .card {
            background: var(--bg-secondary);
            border: 1px solid var(--border-color);
            color: var(--text-primary);
        }
        
        .card-header {
            background: var(--bg-tertiary);
            border-bottom: 1px solid var(--border-color);
            color: var(--text-primary);
        }
        
        .card-body {
            background: var(--bg-secondary);
            color: var(--text-primary);
        }
        
        .border-primary {
            border-color: var(--primary-color) !important;
        }
        
        .bg-light {
            background: var(--bg-secondary) !important;
        }
        
        .btn-outline-primary {
            color: var(--primary-color);
            border-color: var(--primary-color);
            background: transparent;
        }
        
        .btn-outline-primary:hover {
            background: var(--primary-color);
            border-color: var(--primary-color);
            color: white;
        }
        
        .btn-outline-secondary {
            color: var(--text-secondary);
            border-color: var(--border-color);
            background: transparent;
        }
        
        .btn-outline-secondary:hover {
            background: var(--bg-secondary);
            border-color: var(--border-color);
            color: var(--text-primary);
        }
        
        .btn-outline-danger {
            color: var(--danger);
            border-color: var(--danger);
            background: transparent;
        }
        
        .btn-outline-danger:hover {
            background: var(--danger);
            border-color: var(--danger);
            color: white;
        }
        
        .invalid-feedback {
            color: var(--danger);
        }
        
        .form-control.is-invalid {
            border-color: var(--danger);
        }
        
        .form-control.is-valid {
            border-color: var(--success);
        }
        
        /* Close button for dark mode */
        .btn-close {
            filter: invert(1) grayscale(100%) brightness(200%);
        }
        
        [data-theme="light"] .btn-close {
            filter: none;
        }
        
        /* Small text */
        .form-text {
            color: var(--text-muted);
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .onboarding-card {
                max-width: 100%;
                margin: 10px;
            }
            
            .card-body {
                padding: 20px;
            }
            
            .card-header {
                padding: 20px;
            }
            
            .brand-logo {
                font-size: 1.5rem;
            }
        }
    </style>
    
    @yield('styles')
</head>
<body>
    <!-- Theme Toggle Button -->
    <button class="theme-toggle" id="themeToggle" title="Toggle theme">
        <i class="fas fa-moon icon dark-icon"></i>
        <i class="fas fa-sun icon light-icon"></i>
    </button>
    
    <div class="onboarding-container">
        <div class="onboarding-card">
            <div class="card-header">
                <div class="brand-logo">
                    <i class="fas fa-graduation-cap me-2"></i>
                    ShuleSoft Group Connect
                </div>
                <div class="brand-subtitle">Welcome to Your School Management Network</div>
                
                <div class="step-indicator mt-4">
                    @php
                        $steps = [
                            1 => 'Organization',
                            2 => 'Schools',
                            3 => 'Details',
                            4 => 'Account'
                        ];
                        $currentStep = $currentStep ?? 1;
                    @endphp
                    
                    @foreach($steps as $stepNumber => $stepName)
                        <div class="step 
                            @if($stepNumber == $currentStep) active
                            @elseif($stepNumber < $currentStep) completed
                            @endif">
                            @if($stepNumber < $currentStep)
                                <i class="fas fa-check"></i>
                            @else
                                {{ $stepNumber }}
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
            
            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle me-2"></i>
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
                
                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
                
                @if($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <strong>Please fix the following errors:</strong>
                        <ul class="mb-0 mt-2">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
                
                @yield('content')
            </div>
        </div>
    </div>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <script>
        // Theme Management
        class ThemeManager {
            constructor() {
                this.theme = this.getTheme();
                this.applyTheme();
                this.initToggle();
            }

            getTheme() {
                const savedTheme = localStorage.getItem('theme');
                if (savedTheme) return savedTheme;
                
                return window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
            }

            applyTheme() {
                document.documentElement.setAttribute('data-theme', this.theme);
                localStorage.setItem('theme', this.theme);
            }

            toggleTheme() {
                this.theme = this.theme === 'light' ? 'dark' : 'light';
                this.applyTheme();
            }

            initToggle() {
                const toggleBtn = document.getElementById('themeToggle');
                if (toggleBtn) {
                    toggleBtn.addEventListener('click', () => this.toggleTheme());
                }

                // Listen for system theme changes
                window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', (e) => {
                    if (!localStorage.getItem('theme')) {
                        this.theme = e.matches ? 'dark' : 'light';
                        this.applyTheme();
                    }
                });
            }
        }

        // Initialize theme manager
        document.addEventListener('DOMContentLoaded', function() {
            const themeManager = new ThemeManager();
            window.themeManager = themeManager;
        });
    </script>
    
    @yield('scripts')
</body>
</html>
