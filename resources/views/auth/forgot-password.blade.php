<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Reset your ShuleSoft Group Connect password">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Reset Password - ShuleSoft Group Connect</title>
    
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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
            
            /* Focus */
            --focus-ring: 0 0 0 0.25rem rgba(30, 186, 155, 0.25);
            
            /* Hero Gradient */
            --hero-gradient: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
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
            
            /* Focus */
            --focus-ring: 0 0 0 0.25rem rgba(30, 186, 155, 0.25);
            
            /* Hero Gradient */
            --hero-gradient: linear-gradient(135deg, #232631 0%, #2a2d3a 100%);
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
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
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

        /* ===== RESET CARD ===== */
        .reset-card {
            background: var(--bg-primary);
            border-radius: 20px;
            box-shadow: var(--shadow-lg);
            border: 1px solid var(--border-color);
            width: 100%;
            max-width: 480px;
            overflow: hidden;
            animation: slideInUp 0.6s ease-out;
        }

        .reset-header {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            color: white;
            padding: 2.5rem 2rem 2rem;
            text-align: center;
            position: relative;
        }

        .reset-header::before {
            content: '';
            position: absolute;
            bottom: -1px;
            left: 0;
            right: 0;
            height: 20px;
            background: var(--bg-primary);
            border-radius: 20px 20px 0 0;
        }

        .reset-icon {
            width: 80px;
            height: 80px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            font-size: 2rem;
            backdrop-filter: blur(10px);
        }

        .reset-header h1 {
            font-size: 1.75rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .reset-header p {
            opacity: 0.9;
            font-size: 0.95rem;
            margin: 0;
        }

        .reset-body {
            padding: 2rem;
        }

        .reset-description {
            background: var(--bg-secondary);
            border: 1px solid var(--border-color);
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 2rem;
            text-align: center;
        }

        .reset-description .icon {
            font-size: 2.5rem;
            color: var(--primary-color);
            margin-bottom: 1rem;
        }

        .reset-description h6 {
            color: var(--text-primary);
            font-weight: 600;
            margin-bottom: 0.75rem;
        }

        .reset-description p {
            color: var(--text-secondary);
            margin: 0;
            font-size: 0.9rem;
            line-height: 1.5;
        }

        /* ===== FORM STYLES ===== */
        .form-label {
            color: var(--text-primary);
            font-weight: 600;
            margin-bottom: 0.5rem;
        }

        .form-control {
            border-radius: 12px;
            padding: 0.875rem 1rem;
            border: 2px solid var(--border-color);
            transition: all 0.3s ease;
            background: var(--bg-secondary);
            color: var(--text-primary);
            font-size: 1rem;
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

        .form-control.is-invalid {
            border-color: var(--danger);
        }

        .form-control.is-valid {
            border-color: var(--success);
        }

        .invalid-feedback {
            color: var(--danger);
            font-size: 0.875rem;
            margin-top: 0.5rem;
        }

        /* ===== BUTTONS ===== */
        .btn-primary {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            border: none;
            border-radius: 12px;
            padding: 0.875rem 2rem;
            font-weight: 600;
            font-size: 1rem;
            transition: all 0.3s ease;
            color: white;
            box-shadow: var(--shadow-sm);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
            color: white;
        }

        .btn-primary:active {
            transform: translateY(0);
        }

        .btn-outline-primary {
            color: var(--primary-color);
            border: 2px solid var(--primary-color);
            border-radius: 12px;
            padding: 0.875rem 2rem;
            font-weight: 600;
            transition: all 0.3s ease;
            background: transparent;
        }

        .btn-outline-primary:hover {
            background: var(--primary-color);
            border-color: var(--primary-color);
            color: white;
            transform: translateY(-2px);
        }

        .btn-secondary {
            background: var(--btn-secondary);
            border: none;
            border-radius: 12px;
            padding: 0.875rem 2rem;
            font-weight: 600;
            color: white;
            transition: all 0.3s ease;
        }

        .btn-secondary:hover {
            background: var(--btn-secondary-hover);
            color: white;
        }

        /* ===== ALERTS ===== */
        .alert {
            border: none;
            border-radius: 12px;
            padding: 1rem 1.25rem;
            margin-bottom: 1.5rem;
            border-left: 4px solid;
        }

        .alert-success {
            background: rgba(25, 135, 84, 0.1);
            color: var(--success);
            border-left-color: var(--success);
        }

        .alert-danger {
            background: rgba(220, 53, 69, 0.1);
            color: var(--danger);
            border-left-color: var(--danger);
        }

        .alert-info {
            background: rgba(13, 202, 240, 0.1);
            color: var(--info);
            border-left-color: var(--info);
        }

        /* ===== LINKS ===== */
        .text-link {
            color: var(--link-color);
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s ease;
        }

        .text-link:hover {
            color: var(--link-hover);
            text-decoration: underline;
        }

        /* ===== FOOTER ===== */
        .reset-footer {
            background: var(--bg-secondary);
            border-top: 1px solid var(--border-color);
            padding: 1.5rem 2rem;
            text-align: center;
        }

        .reset-footer p {
            margin: 0;
            color: var(--text-secondary);
            font-size: 0.875rem;
        }

        .back-link {
            display: inline-flex;
            align-items: center;
            color: var(--link-color);
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .back-link:hover {
            color: var(--link-hover);
            transform: translateX(-3px);
        }

        .back-link i {
            margin-right: 0.5rem;
        }

        /* ===== ANIMATIONS ===== */
        @keyframes slideInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes pulse {
            0% {
                transform: scale(1);
            }
            50% {
                transform: scale(1.05);
            }
            100% {
                transform: scale(1);
            }
        }

        .pulse-animation {
            animation: pulse 2s infinite;
        }

        /* ===== RESPONSIVE ===== */
        @media (max-width: 576px) {
            body {
                padding: 10px;
            }

            .reset-card {
                max-width: 100%;
            }

            .reset-header {
                padding: 2rem 1.5rem 1.5rem;
            }

            .reset-body {
                padding: 1.5rem;
            }

            .reset-footer {
                padding: 1rem 1.5rem;
            }

            .reset-header h1 {
                font-size: 1.5rem;
            }

            .reset-icon {
                width: 60px;
                height: 60px;
                font-size: 1.5rem;
            }
        }

        /* ===== SECURITY INDICATORS ===== */
        .security-badge {
            display: inline-flex;
            align-items: center;
            background: rgba(25, 135, 84, 0.1);
            color: var(--success);
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 500;
            border: 1px solid rgba(25, 135, 84, 0.2);
        }

        .security-badge i {
            margin-right: 0.25rem;
        }

        /* ===== LOADING STATES ===== */
        .btn-loading {
            pointer-events: none;
            opacity: 0.8;
        }

        .btn-loading .spinner-border {
            width: 1rem;
            height: 1rem;
            margin-right: 0.5rem;
        }
    </style>
</head>
<body>
    <!-- Theme Toggle -->
    <button class="theme-toggle" id="themeToggle" title="Toggle theme">
        <i class="fas fa-moon icon dark-icon"></i>
        <i class="fas fa-sun icon light-icon"></i>
    </button>

    <!-- Password Reset Card -->
    <div class="reset-card">
        <!-- Header -->
        <div class="reset-header">
            <div class="reset-icon pulse-animation">
                <i class="fas fa-key"></i>
            </div>
            <h1>Reset Password</h1>
            <p>Secure password recovery for your account</p>
        </div>

        <!-- Body -->
        <div class="reset-body">
            <!-- Description -->
            <div class="reset-description">
                <div class="icon">
                    <i class="fas fa-envelope"></i>
                </div>
                <h6>Email Reset Instructions</h6>
                <p>
                    Enter your email address and we'll send you a secure link to reset your password. 
                    The link will expire in 60 minutes for your security.
                </p>
            </div>

            <!-- Session Status -->
            @if (session('status'))
                <div class="alert alert-success">
                    <i class="fas fa-check-circle me-2"></i>
                    {{ session('status') }}
                </div>
            @endif

            <!-- Reset Form -->
            <form method="POST" action="{{ route('password.email') }}" id="resetForm">
                @csrf

                <!-- Email Address -->
                <div class="mb-3">
                    <label for="email" class="form-label">
                        <i class="fas fa-envelope me-2"></i>Email Address
                    </label>
                    <input 
                        type="email" 
                        class="form-control @error('email') is-invalid @enderror" 
                        id="email" 
                        name="email" 
                        value="{{ old('email') }}" 
                        placeholder="Enter your email address"
                        required 
                        autofocus 
                        autocomplete="email"
                    >
                    @error('email')
                        <div class="invalid-feedback">
                            <i class="fas fa-exclamation-circle me-1"></i>
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <!-- Security Badge -->
                <div class="mb-4 text-center">
                    <span class="security-badge">
                        <i class="fas fa-shield-alt"></i>
                        Secure & Encrypted
                    </span>
                </div>

                <!-- Submit Button -->
                <div class="d-grid mb-3">
                    <button type="submit" class="btn btn-primary" id="submitBtn">
                        <i class="fas fa-paper-plane me-2"></i>
                        Send Reset Link
                    </button>
                </div>

                <!-- Back to Login -->
                <div class="text-center">
                    <a href="{{ route('login') }}" class="back-link">
                        <i class="fas fa-arrow-left"></i>
                        Back to Login
                    </a>
                </div>
            </form>
        </div>

        <!-- Footer -->
        <div class="reset-footer">
            <p>
                <i class="fas fa-info-circle me-1"></i>
                Having trouble? Contact our 
                <a href="mailto:support@shulesoft.africa" class="text-link">support team</a>
            </p>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Custom JavaScript -->
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
        const themeManager = new ThemeManager();

        // Form Enhancement
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('resetForm');
            const submitBtn = document.getElementById('submitBtn');
            const emailInput = document.getElementById('email');

            // Form submission handling
            form.addEventListener('submit', function(e) {
                submitBtn.classList.add('btn-loading');
                submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Sending...';
                submitBtn.disabled = true;
            });

            // Email validation enhancement
            emailInput.addEventListener('input', function() {
                const email = this.value;
                const isValid = /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
                
                if (email && isValid) {
                    this.classList.remove('is-invalid');
                    this.classList.add('is-valid');
                } else if (email) {
                    this.classList.remove('is-valid');
                    this.classList.add('is-invalid');
                } else {
                    this.classList.remove('is-valid', 'is-invalid');
                }
            });

            // Auto-focus on page load
            emailInput.focus();

            // Enter key handling
            emailInput.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    form.submit();
                }
            });
        });

        // Security enhancements
        document.addEventListener('DOMContentLoaded', function() {
            // Disable autocomplete for sensitive operations
            document.getElementById('email').setAttribute('autocomplete', 'username');
            
            // Add CSRF protection
            const csrfToken = document.querySelector('meta[name="csrf-token"]');
            if (csrfToken) {
                document.querySelector('input[name="_token"]').value = csrfToken.getAttribute('content');
            }
        });

        // Accessibility enhancements
        document.addEventListener('DOMContentLoaded', function() {
            // Add proper ARIA labels
            const emailInput = document.getElementById('email');
            emailInput.setAttribute('aria-describedby', 'email-help');
            
            // Add live region for status updates
            if (document.querySelector('.alert-success')) {
                document.querySelector('.alert-success').setAttribute('role', 'status');
                document.querySelector('.alert-success').setAttribute('aria-live', 'polite');
            }
        });
    </script>
</body>
</html>
