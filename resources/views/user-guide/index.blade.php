<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ShuleSoft Group Connect - User Guide</title>
    
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
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
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
            background-color: var(--bg-primary);
            color: var(--text-primary);
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

        .navbar {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 1rem 0;
        }

        .navbar-brand {
            font-weight: 600;
            font-size: 1.5rem;
        }

        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: 280px;
            height: 100vh;
            background: var(--bg-primary);
            border-right: 1px solid var(--border-color);
            z-index: 1000;
            overflow-y: auto;
            box-shadow: var(--shadow-md);
        }

        .sidebar-header {
            padding: 1.5rem;
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            color: white;
            text-align: center;
        }

        .sidebar-nav {
            padding: 1rem 0;
        }

        .nav-section {
            margin-bottom: 1rem;
        }

        .nav-section-title {
            padding: 0.5rem 1.5rem;
            font-size: 0.85rem;
            font-weight: 600;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .nav-link {
            display: block;
            padding: 0.75rem 1.5rem;
            color: var(--text-primary);
            text-decoration: none;
            transition: all 0.3s ease;
            border-left: 3px solid transparent;
        }

        .nav-link:hover,
        .nav-link.active {
            background-color: rgba(30, 186, 155, 0.1);
            color: var(--primary-color);
            border-left-color: var(--primary-color);
        }

        .nav-link i {
            width: 20px;
            text-align: center;
            margin-right: 0.75rem;
        }

        .main-content {
            margin-left: 280px;
            padding: 2rem;
            min-height: 100vh;
            background-color: var(--bg-secondary);
        }

        .search-box {
            position: sticky;
            top: 0;
            background: var(--bg-primary);
            padding: 1.5rem;
            border-radius: 10px;
            box-shadow: var(--shadow-md);
            margin-bottom: 2rem;
            z-index: 100;
            border: 1px solid var(--border-color);
        }

        .content-section {
            background: var(--bg-primary);
            border-radius: 10px;
            padding: 2rem;
            margin-bottom: 2rem;
            box-shadow: var(--shadow-sm);
            border: 1px solid var(--border-color);
            display: none;
        }

        .content-section.active {
            display: block;
        }

        .section-header {
            border-bottom: 2px solid var(--primary-color);
            padding-bottom: 1rem;
            margin-bottom: 1.5rem;
        }

        .section-title {
            color: var(--text-primary);
            font-weight: 600;
            margin-bottom: 0.5rem;
        }

        .feature-card {
            background: var(--bg-secondary);
            border-radius: 8px;
            padding: 1.5rem;
            margin-bottom: 1rem;
            border-left: 4px solid var(--primary-color);
            transition: transform 0.2s ease;
            border: 1px solid var(--border-color);
        }

        .feature-card:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
        }

        .step-number {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 30px;
            height: 30px;
            background: var(--primary-color);
            color: white;
            border-radius: 50%;
            font-weight: bold;
            margin-right: 0.75rem;
        }

        .alert-info {
            border-left: 4px solid var(--info);
            background-color: rgba(13, 202, 240, 0.1);
            border: 1px solid var(--border-color);
            background: var(--bg-tertiary);
            color: var(--text-primary);
        }

        .alert-warning {
            border-left: 4px solid var(--warning);
            background-color: rgba(255, 193, 7, 0.1);
            border: 1px solid var(--border-color);
            background: var(--bg-tertiary);
            color: var(--text-primary);
        }

        .alert-success {
            border-left: 4px solid var(--success);
            background-color: rgba(25, 135, 84, 0.1);
            border: 1px solid var(--border-color);
            background: var(--bg-tertiary);
            color: var(--text-primary);
        }

        .pricing-badge {
            display: inline-block;
            background: linear-gradient(45deg, #ffd700, #ff8c00);
            color: white;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            margin-left: 0.5rem;
        }

        .screenshot-placeholder {
            background: var(--bg-tertiary);
            border: 2px dashed var(--border-color);
            border-radius: 8px;
            height: 200px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--text-muted);
            font-style: italic;
            margin: 1rem 0;
        }

        .kbd {
            background-color: var(--bg-tertiary);
            color: var(--text-primary);
            padding: 0.2rem 0.4rem;
            border-radius: 0.2rem;
            font-size: 0.875em;
            border: 1px solid var(--border-color);
        }

        /* Form Controls */
        .form-control {
            background-color: var(--bg-secondary);
            border: 1px solid var(--border-color);
            color: var(--text-primary);
        }

        .form-control:focus {
            background-color: var(--bg-secondary);
            border-color: var(--primary-color);
            color: var(--text-primary);
            box-shadow: var(--focus-ring);
        }

        .form-control::placeholder {
            color: var(--text-muted);
        }

        /* Tables */
        .table {
            --bs-table-bg: var(--bg-primary);
            --bs-table-color: var(--text-primary);
            --bs-table-border-color: var(--border-color);
        }

        .table-bordered {
            border: 1px solid var(--border-color);
        }

        .table-bordered th,
        .table-bordered td {
            border: 1px solid var(--border-color);
        }

        /* Additional element styles */
        h1, h2, h3, h4, h5, h6 {
            color: var(--text-primary);
        }

        p, li {
            color: var(--text-secondary);
        }

        .text-muted {
            color: var(--text-muted) !important;
        }

        .lead {
            color: var(--text-secondary);
        }

        /* Accordion styles */
        .accordion {
            --bs-accordion-bg: var(--bg-primary);
            --bs-accordion-border-color: var(--border-color);
            --bs-accordion-btn-color: var(--text-primary);
        }

        .accordion-button {
            background-color: var(--bg-secondary);
            color: var(--text-primary);
        }

        .accordion-button:not(.collapsed) {
            background-color: var(--bg-tertiary);
            color: var(--primary-color);
        }

        .accordion-body {
            background-color: var(--bg-primary);
            color: var(--text-secondary);
        }

        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
                transition: transform 0.3s ease;
            }

            .sidebar.show {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0;
            }

            .mobile-toggle {
                display: block !important;
            }
        }
    </style>
</head>
<body>
    <!-- Theme Toggle Button -->
    <button class="theme-toggle" onclick="toggleTheme()" title="Toggle dark/light mode">
        <i class="fas fa-moon icon dark-icon"></i>
        <i class="fas fa-sun icon light-icon"></i>
    </button>

    <!-- Sidebar Navigation -->
    <div class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <h4><i class="bi bi-mortarboard-fill me-2"></i>ShuleSoft Guide</h4>
            <p class="mb-0 small opacity-75">Complete User Manual</p>
        </div>
        
        <nav class="sidebar-nav">
            <!-- Getting Started -->
            <div class="nav-section">
                <div class="nav-section-title">Getting Started</div>
                <a href="#overview" class="nav-link active" onclick="showSection('overview')">
                    <i class="bi bi-house-door"></i>Overview & Introduction
                </a>
                <a href="#account-setup" class="nav-link" onclick="showSection('account-setup')">
                    <i class="bi bi-person-plus"></i>Account Setup
                </a>
                <a href="#school-onboarding" class="nav-link" onclick="showSection('school-onboarding')">
                    <i class="bi bi-building-add"></i>School Onboarding
                </a>
                <a href="#pricing-plans" class="nav-link" onclick="showSection('pricing-plans')">
                    <i class="bi bi-credit-card"></i>Pricing & Plans
                </a>
            </div>

            <!-- Navigation Guide -->
            <div class="nav-section">
                <div class="nav-section-title">Navigation Guide</div>
                <a href="#dashboard" class="nav-link" onclick="showSection('dashboard')">
                    <i class="bi bi-speedometer2"></i>Dashboard Overview
                </a>
                <a href="#schools-overview" class="nav-link" onclick="showSection('schools-overview')">
                    <i class="bi bi-building"></i>Schools Overview
                </a>
                <a href="#academics" class="nav-link" onclick="showSection('academics')">
                    <i class="bi bi-book"></i>Academics
                </a>
                <a href="#operations" class="nav-link" onclick="showSection('operations')">
                    <i class="bi bi-gear"></i>Operations
                </a>
                <a href="#finance" class="nav-link" onclick="showSection('finance')">
                    <i class="bi bi-calculator"></i>Finance & Accounts
                </a>
                <a href="#hr" class="nav-link" onclick="showSection('hr')">
                    <i class="bi bi-people"></i>Human Resources
                </a>
                <a href="#communications" class="nav-link" onclick="showSection('communications')">
                    <i class="bi bi-chat-dots"></i>Communications
                </a>
            </div>

            <!-- Core Features -->
            <div class="nav-section">
                <div class="nav-section-title">Core Features</div>
                <a href="#group-dashboard" class="nav-link" onclick="showSection('group-dashboard')">
                    <i class="bi bi-graph-up"></i>Group Dashboard
                </a>
                <a href="#performance-trends" class="nav-link" onclick="showSection('performance-trends')">
                    <i class="bi bi-graph-up-arrow"></i>Performance Trends
                </a>
                <a href="#ai-insights" class="nav-link" onclick="showSection('ai-insights')">
                    <i class="fas fa-brain"></i>AI Insights
                </a>
                <a href="#reports-analytics" class="nav-link" onclick="showSection('reports-analytics')">
                    <i class="bi bi-file-earmark-bar-graph"></i>Reports & Analytics
                </a>
            </div>

            <!-- Support & Help -->
            <div class="nav-section">
                <div class="nav-section-title">Support & Help</div>
                <a href="#account-billing" class="nav-link" onclick="showSection('account-billing')">
                    <i class="bi bi-credit-card-2-front"></i>Account & Billing
                </a>
                <a href="#support" class="nav-link" onclick="showSection('support')">
                    <i class="bi bi-question-circle"></i>Support & Contact
                </a>
                <a href="#faqs" class="nav-link" onclick="showSection('faqs')">
                    <i class="bi bi-patch-question"></i>FAQs
                </a>
            </div>
        </nav>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Search Box -->
        <div class="search-box">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-search"></i></span>
                        <input type="text" class="form-control" id="searchInput" placeholder="Search user guide..." onkeyup="searchContent()">
                    </div>
                </div>
                <div class="col-md-4 text-end">
                    <button class="btn btn-outline-primary mobile-toggle d-none" onclick="toggleSidebar()">
                        <i class="bi bi-list"></i> Menu
                    </button>
                </div>
            </div>
        </div>

        <!-- Overview & Introduction -->
        <div id="overview" class="content-section active">
            <div class="section-header">
                <h1 class="section-title"><i class="bi bi-house-door me-2"></i>Welcome to ShuleSoft Group Connect</h1>
                <p class="lead">Your comprehensive platform for managing multiple schools from a single dashboard</p>
            </div>

            <div class="row">
                <div class="col-lg-8">
                    <h3>What is ShuleSoft Group Connect?</h3>
                    <p>ShuleSoft Group Connect is a powerful, cloud-based management platform designed specifically for education groups, chains, and networks managing multiple schools. Our platform centralizes data from all your schools, providing real-time insights, streamlined operations, and intelligent decision-making tools.</p>

                    <h4>Key Benefits for School Owners & Administrators</h4>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="feature-card">
                                <h5><i class="bi bi-speedometer2 text-primary me-2"></i>Centralized Management</h5>
                                <p class="mb-0">Monitor all schools from one unified dashboard with real-time data aggregation.</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="feature-card">
                                <h5><i class="fas fa-brain text-primary me-2"></i>AI-Powered Insights</h5>
                                <p class="mb-0">Get intelligent recommendations and predictive analytics for better decision making.</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="feature-card">
                                <h5><i class="bi bi-graph-up text-primary me-2"></i>Performance Tracking</h5>
                                <p class="mb-0">Track academic, financial, and operational performance across all schools.</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="feature-card">
                                <h5><i class="bi bi-shield-check text-primary me-2"></i>Secure & Reliable</h5>
                                <p class="mb-0">Enterprise-grade security with 99.9% uptime guarantee.</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="alert alert-info">
                        <h5><i class="bi bi-lightbulb me-2"></i>Quick Start Tip</h5>
                        <p class="mb-0">New to ShuleSoft Group Connect? Start with the <strong>Account Setup</strong> section to get your platform configured in minutes.</p>
                    </div>
                    
                    <div class="screenshot-placeholder">
                        <span>Dashboard Preview Screenshot</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Account Setup -->
        <div id="account-setup" class="content-section">
            <div class="section-header">
                <h1 class="section-title"><i class="bi bi-person-plus me-2"></i>Account Setup</h1>
                <p class="lead">Get started with your ShuleSoft Group Connect account</p>
            </div>

            <div class="row">
                <div class="col-lg-8">
                    <h3>Creating Your Account</h3>
                    
                    <div class="feature-card">
                        <h5><span class="step-number">1</span>Registration Process</h5>
                        <ul>
                            <li>Visit the ShuleSoft Group Connect registration page</li>
                            <li>Provide your organization details (Name, Location, Contact Information)</li>
                            <li>Set up your administrator credentials</li>
                            <li>Verify your email address</li>
                        </ul>
                    </div>

                    <div class="feature-card">
                        <h5><span class="step-number">2</span>Initial Configuration</h5>
                        <ul>
                            <li>Complete your organization profile</li>
                            <li>Set up user roles and permissions</li>
                            <li>Configure basic system preferences</li>
                            <li>Choose your subscription plan</li>
                        </ul>
                    </div>

                    <div class="feature-card">
                        <h5><span class="step-number">3</span>First Login</h5>
                        <ul>
                            <li>Access your dashboard at <code>shulesoft.group</code></li>
                            <li>Use your registered email and password</li>
                            <li>Complete the welcome wizard</li>
                            <li>Start adding your schools</li>
                        </ul>
                    </div>

                    <h3>User Management</h3>
                    <p>As an administrator, you can invite and manage users across your organization:</p>
                    
                    <h4>Available User Roles</h4>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="table-primary">
                                <tr>
                                    <th>Role</th>
                                    <th>Access Level</th>
                                    <th>Key Permissions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><strong>Owner</strong></td>
                                    <td>Full Access</td>
                                    <td>All strategic, operational, and financial data</td>
                                </tr>
                                <tr>
                                    <td><strong>Group Accountant</strong></td>
                                    <td>Finance Only</td>
                                    <td>Finance dashboards, reports, and budgets</td>
                                </tr>
                                <tr>
                                    <td><strong>Group Academic</strong></td>
                                    <td>Academic Only</td>
                                    <td>Academic performance, reports, and curriculum</td>
                                </tr>
                                <tr>
                                    <td><strong>Group IT Officer</strong></td>
                                    <td>Technical Only</td>
                                    <td>System usage, user activity, and technical reports</td>
                                </tr>
                                <tr>
                                    <td><strong>Central Super Admin</strong></td>
                                    <td>Administrative</td>
                                    <td>User management and school configuration</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="alert alert-warning">
                        <h5><i class="bi bi-shield-exclamation me-2"></i>Security Note</h5>
                        <p class="mb-0">Always use strong passwords and enable two-factor authentication for enhanced security.</p>
                    </div>
                    
                    <div class="screenshot-placeholder">
                        <span>Registration Form Screenshot</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- School Onboarding -->
        <div id="school-onboarding" class="content-section">
            <div class="section-header">
                <h1 class="section-title"><i class="bi bi-building-add me-2"></i>School Onboarding</h1>
                <p class="lead">Connect and manage your schools efficiently</p>
            </div>

            <div class="row">
                <div class="col-lg-8">
                    <h3>Adding Schools to Your Network</h3>
                    
                    <div class="feature-card">
                        <h5><span class="step-number">1</span>Link Existing Schools</h5>
                        <p>If your schools are already using ShuleSoft:</p>
                        <ul>
                            <li>Navigate to <strong>Settings & Control → School Miscellaneous settings</strong></li>
                            <li>Click <strong>"View Login Code"</strong></li>
                            <li>Copy the <strong>ShuleSoft School Code provided</strong></li>
                            <li>Go back to group connect and confirm connection</li>
                        </ul>
                        <div class="alert alert-info">
                            <strong>School Login Code Format:</strong> Usually 36  characters (e.g., b5d8945a-2c35-477f-bcxz-866806835852 )
                        </div>
                    </div>

                    <div class="feature-card">
                        <h5><span class="step-number">2</span>Add New Schools</h5>
                        <p>For schools not yet on ShuleSoft:</p>
                        <ul>
                            <li>Click <strong>"Request New School"</strong></li>
                            <li>Fill in the school registration form:
                                <ul>
                                    <li>School name and location</li>
                                    <li>Contact information</li>
                                    <li>School type (Primary, Secondary, etc.)</li>
                                    <li>Principal/Head teacher details</li>
                                </ul>
                            </li>
                            <li>Submit request for ShuleSoft team review</li>
                            <li>Receive confirmation and school code within 24-48 hours</li>
                        </ul>
                    </div>

                    <div class="feature-card">
                        <h5><span class="step-number">3</span>School Verification</h5>
                        <ul>
                            <li>All school connections require verification</li>
                            <li>You'll receive email confirmation for each successful connection</li>
                            <li>Schools appear in your dashboard once verified</li>
                            <li>Initial data sync may take 2-4 hours</li>
                        </ul>
                    </div>

                    <h3>Managing Multiple Schools</h3>
                    <p>Once connected, you can:</p>
                    <ul>
                        <li><strong>Group by Region:</strong> Organize schools geographically</li>
                        <li><strong>Set Performance Targets:</strong> Define KPIs for each school</li>
                        <li><strong>Assign Managers:</strong> Delegate access to specific schools</li>
                        <li><strong>Bulk Operations:</strong> Apply settings across multiple schools</li>
                    </ul>
                </div>
                <div class="col-lg-4">
                    <div class="alert alert-success">
                        <h5><i class="bi bi-check-circle me-2"></i>Pro Tip</h5>
                        <p class="mb-0">Start with 2-3 schools to familiarize yourself with the platform before adding your entire network.</p>
                    </div>
                    
                    <div class="screenshot-placeholder">
                        <span>School Setup Interface</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pricing & Plans -->
        <div id="pricing-plans" class="content-section">
            <div class="section-header">
                <h1 class="section-title"><i class="bi bi-credit-card me-2"></i>Pricing & Plans</h1>
                <p class="lead">Flexible pricing designed for education networks of all sizes</p>
            </div>

            <div class="row">
                <div class="col-lg-8">
                    <h3>Free Trial & Paid Plans</h3>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="feature-card">
                                <h5><i class="bi bi-gift text-success me-2"></i>Free Trial</h5>
                                <ul>
                                    <li><strong>Duration:</strong> 14 days</li>
                                    <li><strong>AI Reports:</strong> 3 reports per month</li>
                                    <li><strong>Schools:</strong> Up to 2 schools</li>
                                    <li><strong>Basic Features:</strong> All core functionality</li>
                                    <li><strong>Support:</strong> Email support</li>
                                </ul>
                                <p class="text-muted mb-0"><small>No credit card required to start</small></p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="feature-card">
                                <h5><i class="bi bi-star text-warning me-2"></i>Paid Plan <span class="pricing-badge">Most Popular</span></h5>
                                <ul>
                                    <li><strong>Price:</strong> Standard to premium plan or Tsh 150,000 per school per month</li>
                                    <li><strong>AI Reports:</strong> Unlimited</li>
                                    <li><strong>Schools:</strong> Unlimited</li>
                                    <li><strong>Advanced Features:</strong> All premium features</li>
                                    <li><strong>Support:</strong> Priority support</li>
                                </ul>
                                <p class="text-muted mb-0"><small>Billed monthly or annually</small></p>
                            </div>
                        </div>
                    </div>

                    <h3>What's Included in Paid Plans</h3>
                    <div class="row">
                        <div class="col-md-6">
                            <h4>Core Features</h4>
                            <ul>
                                <li>Unlimited school connections</li>
                                <li>Real-time data synchronization</li>
                                <li>Comprehensive dashboards</li>
                                <li>Standard reports and analytics</li>
                                <li>User management and permissions</li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <h4>Premium Features</h4>
                            <ul>
                                <li>Unlimited AI-powered insights</li>
                                <li>Advanced predictive analytics</li>
                                <li>Custom report generation</li>
                                <li>API access for integrations</li>
                                <li>Priority customer support</li>
                            </ul>
                        </div>
                    </div>

                    <h3>Billing & Payment</h3>
                    <div class="feature-card">
                        <h5><i class="bi bi-credit-card-2-front text-primary me-2"></i>Payment Methods</h5>
                        <ul>
                            <li><strong>Mobile Money:</strong> M-Pesa, Airtel Money, Tigo Pesa</li>
                            <li><strong>Bank Transfer:</strong> Direct bank deposits</li>
                            <li><strong>Credit/Debit Cards:</strong> Visa, Mastercard</li>
                        </ul>
                    </div>

                    <div class="feature-card">
                        <h5><i class="bi bi-calendar-check text-primary me-2"></i>Billing Cycle</h5>
                        <ul>
                            <li><strong>Monthly:</strong> Billed on the same date each month</li>
                            <li><strong>Annual:</strong> Save 15% with annual billing</li>
                            <li><strong>Pro-rated:</strong> New schools are pro-rated for the first month</li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="alert alert-info">
                        <h5><i class="bi bi-calculator me-2"></i>Cost Calculator</h5>
                        <p><strong>Example:</strong> For 5 schools</p>
                        <p>Monthly: 5 × Tsh 150,000 = <strong>Tsh 750,000</strong></p>
                        <p>Annual: Tsh 750,000 × 12 × 0.85 = <strong>Tsh 7,650,000</strong></p>
                        <p class="mb-0"><small>Annual billing saves you Tsh 1,350,000!</small></p>
                    </div>
                    
                    <div class="screenshot-placeholder">
                        <span>Billing Dashboard Screenshot</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Dashboard Overview -->
        <div id="dashboard" class="content-section">
            <div class="section-header">
                <h1 class="section-title"><i class="bi bi-speedometer2 me-2"></i>Dashboard Overview</h1>
                <p class="lead">Your command center for managing multiple schools</p>
            </div>

            <div class="row">
                <div class="col-lg-8">
                    <h3>Main Dashboard Features</h3>
                    
                    <div class="feature-card">
                        <h5><i class="bi bi-graph-up text-primary me-2"></i>Key Performance Indicators (KPIs)</h5>
                        <p>Monitor essential metrics at a glance:</p>
                        <ul>
                            <li><strong>Total Students:</strong> Aggregate enrollment across all schools</li>
                            <li><strong>Attendance Rate:</strong> Average attendance percentage</li>
                            <li><strong>Fees Collected:</strong> Total revenue and collection rates</li>
                            <li><strong>Number of Schools:</strong> Active schools in your network</li>
                        </ul>
                    </div>

                    <div class="feature-card">
                        <h5><i class="bi bi-bar-chart text-primary me-2"></i>Performance Trends</h5>
                        <p>Visual analytics showing:</p>
                        <ul>
                            <li><strong>Enrollment Trends:</strong> Student growth over time</li>
                            <li><strong>Revenue Trends:</strong> Financial performance tracking</li>
                            <li><strong>Academic Performance:</strong> Grade averages and pass rates</li>
                            <li><strong>Regional Comparisons:</strong> Performance by geographic area</li>
                        </ul>
                    </div>

                    <div class="feature-card">
                        <h5><i class="bi bi-trophy text-primary me-2"></i>Top Performing Schools</h5>
                        <p>Identifies excellence and areas for improvement:</p>
                        <ul>
                            <li>Academic performance rankings</li>
                            <li>Financial performance metrics</li>
                            <li>Operational efficiency scores</li>
                            <li>Growth rate comparisons</li>
                        </ul>
                    </div>

                    <h3>Dashboard Customization</h3>
                    <p>Personalize your dashboard experience:</p>
                    <ul>
                        <li><strong>Widget Selection:</strong> Choose which metrics to display</li>
                        <li><strong>Date Ranges:</strong> Filter data by specific time periods</li>
                        <li><strong>School Filtering:</strong> Focus on specific schools or regions</li>
                        <li><strong>Export Options:</strong> Download reports in PDF or Excel format</li>
                    </ul>
                </div>
                <div class="col-lg-4">
                    <div class="alert alert-success">
                        <h5><i class="bi bi-lightbulb me-2"></i>Dashboard Tips</h5>
                        <ul class="mb-0">
                            <li>Use the search bar to quickly find specific schools</li>
                            <li>Click on any chart for detailed drill-down data</li>
                            <li>Set up automated email reports for regular updates</li>
                        </ul>
                    </div>
                    
                    <div class="screenshot-placeholder">
                        <span>Main Dashboard Screenshot</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Schools Overview -->
        <div id="schools-overview" class="content-section">
            <div class="section-header">
                <h1 class="section-title"><i class="bi bi-building me-2"></i>Schools Overview</h1>
                <p class="lead">Comprehensive view of all schools in your network</p>
            </div>

            <div class="row">
                <div class="col-lg-8">
                    <h3>Schools List & Management</h3>
                    
                    <div class="feature-card">
                        <h5><i class="bi bi-list-ul text-primary me-2"></i>School Directory</h5>
                        <p>Complete overview of each school including:</p>
                        <ul>
                            <li><strong>Basic Information:</strong> Name, location, and contact details</li>
                            <li><strong>Student Count:</strong> Current enrollment numbers</li>
                            <li><strong>Fee Collection %:</strong> Current collection rate</li>
                            <li><strong>Academic Index:</strong> Overall academic performance score</li>
                            <li><strong>Attendance %:</strong> Average attendance rate</li>
                            <li><strong>Quick Actions:</strong> Direct access to school-specific features</li>
                        </ul>
                    </div>

                    <div class="feature-card">
                        <h5><i class="bi bi-funnel text-primary me-2"></i>Filtering & Search</h5>
                        <p>Find schools quickly using various filters:</p>
                        <ul>
                            <li><strong>By Region:</strong> Geographic location filtering</li>
                            <li><strong>By Performance:</strong> Academic or financial performance tiers</li>
                            <li><strong>By School Type:</strong> Primary, secondary, or mixed schools</li>
                            <li><strong>By Status:</strong> Active, inactive, or under review</li>
                            <li><strong>Search Bar:</strong> Find schools by name or code</li>
                        </ul>
                    </div>

                    <div class="feature-card">
                        <h5><i class="bi bi-gear text-primary me-2"></i>Bulk Operations</h5>
                        <p>Manage multiple schools simultaneously:</p>
                        <ul>
                            <li><strong>Policy Updates:</strong> Apply new policies across selected schools</li>
                            <li><strong>Settings Configuration:</strong> Update system settings in bulk</li>
                            <li><strong>Communication:</strong> Send messages to multiple schools</li>
                            <li><strong>Report Generation:</strong> Create comparative reports</li>
                        </ul>
                    </div>

                    <h3>School Performance Indicators</h3>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="table-primary">
                                <tr>
                                    <th>Indicator</th>
                                    <th>Excellent</th>
                                    <th>Good</th>
                                    <th>Average</th>
                                    <th>Needs Improvement</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Academic Index</td>
                                    <td>90%+</td>
                                    <td>80-89%</td>
                                    <td>70-79%</td>
                                    <td>&lt;70%</td>
                                </tr>
                                <tr>
                                    <td>Fee Collection</td>
                                    <td>95%+</td>
                                    <td>85-94%</td>
                                    <td>75-84%</td>
                                    <td>&lt;75%</td>
                                </tr>
                                <tr>
                                    <td>Attendance Rate</td>
                                    <td>95%+</td>
                                    <td>88-94%</td>
                                    <td>80-87%</td>
                                    <td>&lt;80%</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="alert alert-info">
                        <h5><i class="bi bi-eye me-2"></i>Quick View</h5>
                        <p class="mb-0">Click on any school name to open a detailed performance summary with drill-down capabilities to student-level data.</p>
                    </div>
                    
                    <div class="screenshot-placeholder">
                        <span>Schools Overview Screenshot</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Academics -->
        <div id="academics" class="content-section">
            <div class="section-header">
                <h1 class="section-title"><i class="bi bi-book me-2"></i>Academics</h1>
                <p class="lead">Academic performance tracking and curriculum management</p>
            </div>

            <div class="row">
                <div class="col-lg-8">
                    <h3>Academic Performance Tracking</h3>
                    
                    <div class="feature-card">
                        <h5><span class="step-number">1</span>Examination Results Analysis</h5>
                        <p>Track academic performance across all schools:</p>
                        <ul>
                            <li><strong>Exam Scores:</strong> Aggregated from `shulesoft.mark` table</li>
                            <li><strong>Subject Performance:</strong> Average marks by subject across schools</li>
                            <li><strong>Grade Distribution:</strong> Statistical analysis of student grades</li>
                            <li><strong>Comparative Analysis:</strong> School-to-school performance comparison</li>
                        </ul>
                        <div class="alert alert-info">
                            <strong>Data Source:</strong> DashboardController.getExamResults() calculates average marks from the `shulesoft.mark` table, filtering by schema_name to separate school data.
                        </div>
                    </div>

                    <div class="feature-card">
                        <h5><span class="step-number">2</span>Student Progression Tracking</h5>
                        <p>Monitor student academic journey:</p>
                        <ul>
                            <li><strong>Promotion Rates:</strong> Percentage of students advancing to next level</li>
                            <li><strong>Dropout Analysis:</strong> Students with inactive status in `shulesoft.student`</li>
                            <li><strong>Academic Improvement:</strong> Term-over-term performance trends</li>
                            <li><strong>At-Risk Students:</strong> Early warning system for academic struggles</li>
                        </ul>
                    </div>

                    <h3>Academic Data Sources</h3>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Component</th>
                                    <th>Database Table</th>
                                    <th>Key Metrics</th>
                                    <th>Calculation Logic</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Student Marks</td>
                                    <td>shulesoft.mark</td>
                                    <td>Average score, Pass rate</td>
                                    <td>AVG(mark), COUNT(mark >= pass_mark)</td>
                                </tr>
                                <tr>
                                    <td>Student Info</td>
                                    <td>shulesoft.student</td>
                                    <td>Enrollment, Active students</td>
                                    <td>COUNT(*) WHERE status = 1</td>
                                </tr>
                                <tr>
                                    <td>Class Management</td>
                                    <td>shulesoft.classes</td>
                                    <td>Class sizes, Grade distribution</td>
                                    <td>Student count per class</td>
                                </tr>
                                <tr>
                                    <td>Subject Performance</td>
                                    <td>shulesoft.subject</td>
                                    <td>Subject-wise averages</td>
                                    <td>AVG(mark) GROUP BY subject</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="alert alert-success">
                        <h5><i class="bi bi-lightbulb me-2"></i>Academic Insights</h5>
                        <p class="mb-0">The system automatically identifies high-performing and underperforming schools, subjects, and students to help you focus improvement efforts.</p>
                    </div>
                    
                    <div class="screenshot-placeholder">
                        <span>Academic Performance Dashboard</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Operations -->
        <div id="operations" class="content-section">
            <div class="section-header">
                <h1 class="section-title"><i class="bi bi-gear me-2"></i>Operations</h1>
                <p class="lead">Daily operations management and monitoring</p>
            </div>

            <div class="row">
                <div class="col-lg-8">
                    <h3>Operational KPIs & Monitoring</h3>
                    
                    <div class="feature-card">
                        <h5><span class="step-number">1</span>Attendance Management</h5>
                        <p>Monitor attendance across all schools:</p>
                        <ul>
                            <li><strong>Student Attendance:</strong> Calculated from `shulesoft.sattendances` table</li>
                            <li><strong>Staff Attendance:</strong> Tracked via `shulesoft.tattendances` (if available)</li>
                            <li><strong>Attendance Trends:</strong> Monthly and weekly patterns</li>
                            <li><strong>Low Attendance Alerts:</strong> Schools below threshold notifications</li>
                        </ul>
                        <div class="alert alert-info">
                            <strong>Calculation Method:</strong> OperationsController.calculateOperationalKPIs() computes attendance as (SUM(present=1) / COUNT(*)) × 100 for each schema, then averages across all schools.
                        </div>
                    </div>

                    <div class="feature-card">
                        <h5><span class="step-number">2</span>Infrastructure Management</h5>
                        <p>Track operational infrastructure:</p>
                        <ul>
                            <li><strong>Transport Routes:</strong> Active routes from `shulesoft.transport_routes`</li>
                            <li><strong>Hostel Occupancy:</strong> Calculated from `shulesoft.hostels` and `shulesoft.hmembers`</li>
                            <li><strong>Library Activity:</strong> Book circulation from `shulesoft.issue` and `shulesoft.book_quantity`</li>
                            <li><strong>Maintenance Requests:</strong> Pending operational issues</li>
                        </ul>
                    </div>

                    <h3>Operations Data Computation</h3>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Metric</th>
                                    <th>Source Table</th>
                                    <th>Calculation</th>
                                    <th>Purpose</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Student Attendance</td>
                                    <td>sattendances</td>
                                    <td>AVG(present) × 100 by schema</td>
                                    <td>Daily operations monitoring</td>
                                </tr>
                                <tr>
                                    <td>Staff Attendance</td>
                                    <td>tattendances</td>
                                    <td>AVG(present) × 100 by schema</td>
                                    <td>Staff performance tracking</td>
                                </tr>
                                <tr>
                                    <td>Hostel Occupancy</td>
                                    <td>hostels, hmembers</td>
                                    <td>(Current occupied / Total capacity) × 100</td>
                                    <td>Resource utilization</td>
                                </tr>
                                <tr>
                                    <td>Transport Routes</td>
                                    <td>transport_routes</td>
                                    <td>COUNT(*) active routes</td>
                                    <td>Transport efficiency</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="alert alert-warning">
                        <h5><i class="bi bi-exclamation-triangle me-2"></i>Data Availability</h5>
                        <p class="mb-0">Some operational metrics depend on specific table structures. The system gracefully handles missing tables and provides fallback values when data is unavailable.</p>
                    </div>
                    
                    <div class="screenshot-placeholder">
                        <span>Operations Dashboard</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Finance & Accounts -->
        <div id="finance" class="content-section">
            <div class="section-header">
                <h1 class="section-title"><i class="bi bi-calculator me-2"></i>Finance & Accounts</h1>
                <p class="lead">Financial management and reporting across all schools</p>
            </div>

            <div class="row">
                <div class="col-lg-8">
                    <h3>Financial KPIs & Analytics</h3>
                    
                    <div class="feature-card">
                        <h5><span class="step-number">1</span>Revenue Management</h5>
                        <p>Track revenue across all schools:</p>
                        <ul>
                            <li><strong>Total Revenue:</strong> Sum of all payments from `shulesoft.payments` table</li>
                            <li><strong>Fee Collection Rate:</strong> (Collected fees ÷ Expected fees) × 100</li>
                            <li><strong>Revenue Trends:</strong> Monthly and quarterly growth patterns</li>
                            <li><strong>Outstanding Fees:</strong> Calculated from `shulesoft.material_invoice_balance`</li>
                        </ul>
                        <div class="alert alert-info">
                            <strong>Calculation Method:</strong> FinanceController.calculateFinancialKPIs() aggregates payments data using SUM(amount) grouped by school schema and time periods.
                        </div>
                    </div>

                    <div class="feature-card">
                        <h5><span class="step-number">2</span>Expense Tracking</h5>
                        <p>Monitor expenses and budget compliance:</p>
                        <ul>
                            <li><strong>Total Expenses:</strong> Aggregated from `shulesoft.expenses` table</li>
                            <li><strong>Category Breakdown:</strong> Expenses grouped by category field</li>
                            <li><strong>Budget Variance:</strong> Actual vs planned expenses comparison</li>
                            <li><strong>Payroll Costs:</strong> Staff salary calculations</li>
                        </ul>
                    </div>

                    <div class="feature-card">
                        <h5><span class="step-number">3</span>Bank Reconciliation</h5>
                        <p>Automated reconciliation features:</p>
                        <ul>
                            <li><strong>Statement Import:</strong> CSV/Excel bank statement processing</li>
                            <li><strong>Transaction Matching:</strong> Automatic matching with recorded payments</li>
                            <li><strong>Discrepancy Reports:</strong> Unmatched transactions identification</li>
                            <li><strong>Multi-School Banking:</strong> Separate bank accounts per school</li>
                        </ul>
                    </div>

                    <h3>Financial Data Sources</h3>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Financial Component</th>
                                    <th>Database Table</th>
                                    <th>Key Fields</th>
                                    <th>Calculation</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Fee Payments</td>
                                    <td>shulesoft.payments</td>
                                    <td>amount, date, student_id</td>
                                    <td>SUM(amount) by schema</td>
                                </tr>
                                <tr>
                                    <td>Expected Fees</td>
                                    <td>shulesoft.fees_installments_classes</td>
                                    <td>amount, start_date, end_date</td>
                                    <td>SUM(amount) for period</td>
                                </tr>
                                <tr>
                                    <td>Expenses</td>
                                    <td>shulesoft.expenses</td>
                                    <td>amount, category, date</td>
                                    <td>SUM(amount) GROUP BY category</td>
                                </tr>
                                <tr>
                                    <td>Revenue Sources</td>
                                    <td>shulesoft.revenues</td>
                                    <td>amount, payment_method</td>
                                    <td>SUM(amount) by method</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="alert alert-success">
                        <h5><i class="bi bi-graph-up me-2"></i>Financial Insights</h5>
                        <p class="mb-0">The system provides automated financial analytics including profit margins, cash flow projections, and collection rate optimization suggestions.</p>
                    </div>
                    
                    <div class="screenshot-placeholder">
                        <span>Finance Dashboard</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Human Resources -->
        <div id="hr" class="content-section">
            <div class="section-header">
                <h1 class="section-title"><i class="bi bi-people me-2"></i>Human Resources</h1>
                <p class="lead">Staff management and HR analytics across your school network</p>
            </div>

            <div class="row">
                <div class="col-lg-8">
                    <h3>HR Management & Analytics</h3>
                    
                    <div class="feature-card">
                        <h5><span class="step-number">1</span>Staff Directory</h5>
                        <p>Comprehensive staff management:</p>
                        <ul>
                            <li><strong>Teacher Records:</strong> Data from `shulesoft.teacher` table</li>
                            <li><strong>Staff Categories:</strong> Teachers, administrative staff, support staff</li>
                            <li><strong>Qualifications:</strong> Education and certification tracking</li>
                            <li><strong>Employment Status:</strong> Active, inactive, on leave</li>
                        </ul>
                        <div class="alert alert-info">
                            <strong>Data Source:</strong> HRController.getAllStaffData() aggregates staff information from teacher and user tables, filtering by schema_name for multi-school access.
                        </div>
                    </div>

                    <div class="feature-card">
                        <h5><span class="step-number">2</span>Attendance & Performance</h5>
                        <p>Track staff performance metrics:</p>
                        <ul>
                            <li><strong>Staff Attendance:</strong> Calculated from `shulesoft.tattendances` table</li>
                            <li><strong>Performance Reviews:</strong> Annual and periodic evaluations</li>
                            <li><strong>Leave Management:</strong> Track sick leave, vacation, and other absences</li>
                            <li><strong>Professional Development:</strong> Training and certification progress</li>
                        </ul>
                    </div>

                    <div class="feature-card">
                        <h5><span class="step-number">3</span>Payroll & Benefits</h5>
                        <p>Salary and benefits management:</p>
                        <ul>
                            <li><strong>Salary Processing:</strong> Automated payroll calculations</li>
                            <li><strong>Benefits Administration:</strong> Health insurance, retirement plans</li>
                            <li><strong>Tax Compliance:</strong> Automated tax calculations and reporting</li>
                            <li><strong>Expense Reimbursements:</strong> Travel and professional expenses</li>
                        </ul>
                    </div>

                    <h3>HR Data Sources</h3>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>HR Component</th>
                                    <th>Database Table</th>
                                    <th>Key Metrics</th>
                                    <th>Calculation Method</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Teacher Count</td>
                                    <td>shulesoft.teacher</td>
                                    <td>Active staff count</td>
                                    <td>COUNT(*) WHERE active = true</td>
                                </tr>
                                <tr>
                                    <td>Staff Attendance</td>
                                    <td>shulesoft.tattendances</td>
                                    <td>Attendance percentage</td>
                                    <td>AVG(present) × 100</td>
                                </tr>
                                <tr>
                                    <td>Leave Requests</td>
                                    <td>shulesoft.leave_applications</td>
                                    <td>Approved/Pending leaves</td>
                                    <td>COUNT(*) GROUP BY status</td>
                                </tr>
                                <tr>
                                    <td>Salary Costs</td>
                                    <td>shulesoft.expenses</td>
                                    <td>Total salary expenses</td>
                                    <td>SUM(amount) WHERE category = 'salary'</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="alert alert-info">
                        <h5><i class="bi bi-person-check me-2"></i>Staff Analytics</h5>
                        <p class="mb-0">The HR module provides insights into staff turnover, performance trends, and optimal staffing levels for each school in your network.</p>
                    </div>
                    
                    <div class="screenshot-placeholder">
                        <span>HR Dashboard</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Communications -->
        <div id="communications" class="content-section">
            <div class="section-header">
                <h1 class="section-title"><i class="bi bi-chat-dots me-2"></i>Communications</h1>
                <p class="lead">Multi-channel communication management across your school network</p>
            </div>

            <div class="row">
                <div class="col-lg-8">
                    <h3>Communication Channels & Analytics</h3>
                    
                    <div class="feature-card">
                        <h5><span class="step-number">1</span>Message Management</h5>
                        <p>Multi-channel communication system:</p>
                        <ul>
                            <li><strong>SMS Messaging:</strong> Bulk SMS to parents, students, and staff</li>
                            <li><strong>Email Campaigns:</strong> Automated and manual email sending</li>
                            <li><strong>WhatsApp Integration:</strong> Business messaging via WhatsApp API</li>
                            <li><strong>In-App Notifications:</strong> System notifications and announcements</li>
                        </ul>
                        <div class="alert alert-info">
                            <strong>Implementation:</strong> CommunicationsController.sendMessage() handles multi-channel message dispatch with recipient validation and delivery tracking.
                        </div>
                    </div>

                    <div class="feature-card">
                        <h5><span class="step-number">2</span>Campaign Management</h5>
                        <p>Organized communication campaigns:</p>
                        <ul>
                            <li><strong>Announcement Campaigns:</strong> School-wide or network-wide announcements</li>
                            <li><strong>Survey Distribution:</strong> Feedback collection from stakeholders</li>
                            <li><strong>Alert Systems:</strong> Emergency and urgent communications</li>
                            <li><strong>Reminder Services:</strong> Fee payments, events, and deadlines</li>
                        </ul>
                    </div>

                    <div class="feature-card">
                        <h5><span class="step-number">3</span>Communication Analytics</h5>
                        <p>Track communication effectiveness:</p>
                        <ul>
                            <li><strong>Delivery Rates:</strong> Message delivery success rates by channel</li>
                            <li><strong>Response Tracking:</strong> Parent and student engagement metrics</li>
                            <li><strong>Channel Performance:</strong> Effectiveness of SMS vs Email vs WhatsApp</li>
                            <li><strong>Communication Trends:</strong> Optimal timing and frequency analysis</li>
                        </ul>
                    </div>

                    <h3>Communication Data Sources</h3>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Communication Type</th>
                                    <th>Data Source</th>
                                    <th>Key Metrics</th>
                                    <th>Tracking Method</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>SMS Messages</td>
                                    <td>SMS API Logs</td>
                                    <td>Sent, Delivered, Failed</td>
                                    <td>API response tracking</td>
                                </tr>
                                <tr>
                                    <td>Email Campaigns</td>
                                    <td>Email Service Logs</td>
                                    <td>Open rate, Click rate</td>
                                    <td>Email service analytics</td>
                                </tr>
                                <tr>
                                    <td>WhatsApp Messages</td>
                                    <td>WhatsApp Business API</td>
                                    <td>Delivery status, Read receipts</td>
                                    <td>WhatsApp webhooks</td>
                                </tr>
                                <tr>
                                    <td>Parent Contacts</td>
                                    <td>shulesoft.student</td>
                                    <td>Phone numbers, Email addresses</td>
                                    <td>Student record linkage</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="alert alert-success">
                        <h5><i class="bi bi-megaphone me-2"></i>Communication Insights</h5>
                        <p class="mb-0">The system provides insights into optimal communication timing, preferred channels by demographic, and message effectiveness to improve parent engagement.</p>
                    </div>
                    
                    <div class="screenshot-placeholder">
                        <span>Communications Dashboard</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Other sections will continue with similar structure... -->
        <!-- For brevity, I'll include a few more key sections -->

        <!-- AI Insights -->
        <div id="ai-insights" class="content-section">
            <div class="section-header">
                <h1 class="section-title"><i class="fas fa-brain me-2"></i>AI Insights</h1>
                <p class="lead">Intelligent analytics and recommendations for your school network</p>
            </div>

            <div class="row">
                <div class="col-lg-8">
                    <h3>How to Use AI Chat</h3>
                    
                    <div class="feature-card">
                        <h5><span class="step-number">1</span>Accessing AI Chat</h5>
                        <ul>
                            <li>Navigate to <strong>Executive Insights → AI Chat</strong></li>
                            <li>Or click the AI brain icon in any dashboard</li>
                        </ul>
                    </div>

                    <div class="feature-card">
                        <h5><span class="step-number">2</span>Asking Questions</h5>
                        <p>Type your questions in natural language. The AI understands:</p>
                        <ul>
                            <li><strong>Performance Queries:</strong> "Which schools have the highest attendance?"</li>
                            <li><strong>Financial Questions:</strong> "Show me fees collected by each school last month"</li>
                            <li><strong>Comparative Analysis:</strong> "Compare academic performance across regions"</li>
                            <li><strong>Trend Analysis:</strong> "What are the enrollment trends this year?"</li>
                        </ul>
                    </div>

                    <div class="feature-card">
                        <h5><span class="step-number">3</span>Interpreting Results</h5>
                        <p>AI responses include:</p>
                        <ul>
                            <li><strong>Direct Answers:</strong> Clear, actionable insights</li>
                            <li><strong>Visual Charts:</strong> Graphs and charts when relevant</li>
                            <li><strong>Data Sources:</strong> Transparency about data used</li>
                            <li><strong>Recommendations:</strong> Suggested actions based on findings</li>
                        </ul>
                    </div>

                    <h3>Example AI Queries</h3>
                    <div class="row">
                        <div class="col-md-6">
                            <h4>Financial Analysis</h4>
                            <ul>
                                <li>"Show me revenue trends for Q1 2024"</li>
                                <li>"Which schools have outstanding fees above 20%?"</li>
                                <li>"Calculate profit margins by school"</li>
                                <li>"Predict next quarter's revenue"</li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <h4>Academic Performance</h4>
                            <ul>
                                <li>"Which subjects need improvement across all schools?"</li>
                                <li>"Compare exam results by grade level"</li>
                                <li>"Show student progression rates"</li>
                                <li>"Identify underperforming schools"</li>
                            </ul>
                        </div>
                    </div>

                    <h3>AI Insights Limitations</h3>
                    <div class="alert alert-warning">
                        <h5><i class="bi bi-exclamation-triangle me-2"></i>Free Plan Restrictions</h5>
                        <ul class="mb-0">
                            <li><strong>3 reports per month</strong> on the free plan</li>
                            <li><strong>Upgrade to paid plan</strong> for unlimited AI insights</li>
                            <li><strong>Premium features</strong> available with subscription</li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="alert alert-success">
                        <h5><i class="bi bi-robot me-2"></i>AI Tips</h5>
                        <ul class="mb-0">
                            <li>Be specific in your questions for better results</li>
                            <li>Use date ranges for time-based queries</li>
                            <li>Ask follow-up questions to drill deeper</li>
                            <li>Save important insights for future reference</li>
                        </ul>
                    </div>
                    
                    <div class="screenshot-placeholder">
                        <span>AI Chat Interface Screenshot</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Support & Contact -->
        <div id="support" class="content-section">
            <div class="section-header">
                <h1 class="section-title"><i class="bi bi-question-circle me-2"></i>Support & Contact</h1>
                <p class="lead">Get help when you need it</p>
            </div>

            <div class="row">
                <div class="col-lg-8">
                    <h3>How to Get Support</h3>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="feature-card">
                                <h5><i class="bi bi-envelope text-primary me-2"></i>Email Support</h5>
                                <p><strong>support@shulesoft.africa</strong></p>
                                <p>Response time: 4-6 hours</p>
                                <p class="mb-0">Available for all users</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="feature-card">
                                <h5><i class="bi bi-telephone text-primary me-2"></i>Phone Support</h5>
                                <p><strong>+255 655 406 004</strong></p>
                                <p>Mon-Fri: 8AM - 6PM EAT</p>
                                <p class="mb-0">Premium subscribers only</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="feature-card">
                                <h5><i class="bi bi-chat-dots text-primary me-2"></i>Live Chat</h5>
                                <p>Available in your dashboard</p>
                                <p>Mon-Fri: 9AM - 5PM EAT</p>
                                <p class="mb-0">Instant responses</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="feature-card">
                                <h5><i class="bi bi-calendar-check text-primary me-2"></i>Schedule Demo</h5>
                                <p>Book a personalized demo</p>
                                <p>Available for new users</p>
                                <p class="mb-0">30-minute sessions</p>
                            </div>
                        </div>
                    </div>

                    <h3>Before Contacting Support</h3>
                    <div class="feature-card">
                        <h5><i class="bi bi-checklist text-primary me-2"></i>Troubleshooting Steps</h5>
                        <ol>
                            <li>Check this user guide for solutions</li>
                            <li>Review the FAQ section below</li>
                            <li>Clear your browser cache and cookies</li>
                            <li>Try accessing from a different browser</li>
                            <li>Check your internet connection</li>
                        </ol>
                    </div>

                    <h3>What to Include in Support Requests</h3>
                    <ul>
                        <li><strong>Account Information:</strong> Your organization name and email</li>
                        <li><strong>Problem Description:</strong> Detailed explanation of the issue</li>
                        <li><strong>Steps to Reproduce:</strong> What you were doing when the problem occurred</li>
                        <li><strong>Browser/Device:</strong> What browser and device you're using</li>
                        <li><strong>Screenshots:</strong> Visual evidence of the problem (if applicable)</li>
                    </ul>
                </div>
                <div class="col-lg-4">
                    <div class="alert alert-info">
                        <h5><i class="bi bi-clock me-2"></i>Support Hours</h5>
                        <p><strong>Monday - Friday:</strong> 8:00 AM - 6:00 PM EAT</p>
                        <p><strong>Saturday:</strong> 9:00 AM - 1:00 PM EAT</p>
                        <p class="mb-0"><strong>Sunday:</strong> Closed</p>
                    </div>
                    
                    <div class="alert alert-success">
                        <h5><i class="bi bi-award me-2"></i>Premium Support</h5>
                        <p class="mb-0">Paid subscribers get priority support with faster response times and dedicated account managers.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- FAQs -->
        <div id="faqs" class="content-section">
            <div class="section-header">
                <h1 class="section-title"><i class="bi bi-patch-question me-2"></i>Frequently Asked Questions</h1>
                <p class="lead">Quick answers to common questions</p>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <div class="accordion" id="faqAccordion">
                        <!-- General Questions -->
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="heading1">
                                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapse1">
                                    How many schools can I connect to ShuleSoft Group Connect?
                                </button>
                            </h2>
                            <div id="collapse1" class="accordion-collapse collapse show" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    There's no limit to the number of schools you can connect. You pay Tsh 50,000 per school per month for the full subscription. During the free trial, you can connect up to 2 schools.
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item">
                            <h2 class="accordion-header" id="heading2">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse2">
                                    What happens to my data if I cancel my subscription?
                                </button>
                            </h2>
                            <div id="collapse2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    Your data remains safe for 90 days after cancellation. You can export all your reports and data during this grace period. After 90 days, data is permanently deleted for security and privacy reasons.
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item">
                            <h2 class="accordion-header" id="heading3">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse3">
                                    Can I integrate with other school management systems?
                                </button>
                            </h2>
                            <div id="collapse3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    Currently, ShuleSoft Group Connect is designed to work with ShuleSoft school management systems. We're working on integrations with other popular systems. Contact our sales team for specific integration requirements.
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item">
                            <h2 class="accordion-header" id="heading4">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse4">
                                    How secure is my school data?
                                </button>
                            </h2>
                            <div id="collapse4" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    We use enterprise-grade security including SSL encryption, regular backups, and secure data centers. All data is encrypted in transit and at rest. We're ISO 27001 compliant and conduct regular security audits.
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item">
                            <h2 class="accordion-header" id="heading5">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse5">
                                    Can I customize reports and dashboards?
                                </button>
                            </h2>
                            <div id="collapse5" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    Yes! Paid subscribers can create custom reports, personalize dashboards, and set up automated report delivery. You can also export data in various formats (PDF, Excel, CSV) for further analysis.
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item">
                            <h2 class="accordion-header" id="heading6">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse6">
                                    How does the AI insights feature work?
                                </button>
                            </h2>
                            <div id="collapse6" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    Our AI analyzes your school data to provide insights and recommendations. You can ask questions in natural language, and the AI provides answers with supporting data and visualizations. Free users get 3 AI reports per month; paid subscribers get unlimited access.
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item">
                            <h2 class="accordion-header" id="heading7">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse7">
                                </button>
                            </h2>
                            <div id="collapse7" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    Contact our support team at support@shulesoft.africa or use the live chat feature. We also offer personalized training sessions for new subscribers to help you get the most out of the platform.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Additional sections would continue here... -->
        
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Show/Hide sections
        function showSection(sectionId) {
            // Hide all sections
            document.querySelectorAll('.content-section').forEach(section => {
                section.classList.remove('active');
            });
            
            // Remove active class from all nav links
            document.querySelectorAll('.nav-link').forEach(link => {
                link.classList.remove('active');
            });
            
            // Show target section
            document.getElementById(sectionId).classList.add('active');
            
            // Add active class to clicked nav link
            document.querySelector(`[onclick="showSection('${sectionId}')"]`).classList.add('active');
            
            // Scroll to top of content
            document.querySelector('.main-content').scrollTop = 0;
        }

        // Search functionality
        function searchContent() {
            const searchTerm = document.getElementById('searchInput').value.toLowerCase();
            const sections = document.querySelectorAll('.content-section');
            
            if (searchTerm === '') {
                // Show overview section if search is empty
                showSection('overview');
                return;
            }
            
            let foundMatch = false;
            
            sections.forEach(section => {
                const content = section.textContent.toLowerCase();
                if (content.includes(searchTerm)) {
                    if (!foundMatch) {
                        // Show first matching section
                        showSection(section.id);
                        foundMatch = true;
                    }
                    // Highlight matching text (simplified implementation)
                    highlightText(section, searchTerm);
                }
            });
        }

        // Simple text highlighting
        function highlightText(element, searchTerm) {
            // This is a simplified implementation
            // In production, you'd want a more sophisticated highlighting system
        }

        // Mobile sidebar toggle
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            sidebar.classList.toggle('show');
        }

        // Close sidebar when clicking outside on mobile
        document.addEventListener('click', function(event) {
            const sidebar = document.getElementById('sidebar');
            const mobileToggle = document.querySelector('.mobile-toggle');
            
            if (window.innerWidth <= 768 && 
                !sidebar.contains(event.target) && 
                !mobileToggle.contains(event.target)) {
                sidebar.classList.remove('show');
            }
        });

        // Handle window resize
        window.addEventListener('resize', function() {
            const sidebar = document.getElementById('sidebar');
            if (window.innerWidth > 768) {
                sidebar.classList.remove('show');
            }
        });

        // Smooth scrolling for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });

        // Initialize page
        document.addEventListener('DOMContentLoaded', function() {
            // Show overview section by default
            showSection('overview');
        });

        // ===== THEME MANAGEMENT =====
        class ThemeManager {
            constructor() {
                this.theme = document.documentElement.getAttribute('data-theme') || 'light';
                this.init();
            }

            init() {
                // Listen for system theme changes
                window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', (e) => {
                    if (!localStorage.getItem('theme')) {
                        this.setTheme(e.matches ? 'dark' : 'light');
                    }
                });
            }

            setTheme(theme) {
                this.theme = theme;
                document.documentElement.setAttribute('data-theme', theme);
                localStorage.setItem('theme', theme);
                
                // Dispatch custom event for other components
                window.dispatchEvent(new CustomEvent('themeChanged', { 
                    detail: { theme: theme } 
                }));
            }

            toggle() {
                const newTheme = this.theme === 'light' ? 'dark' : 'light';
                this.setTheme(newTheme);
            }

            getTheme() {
                return this.theme;
            }
        }

        // Initialize theme manager
        const themeManager = new ThemeManager();
        window.themeManager = themeManager;

        // Theme toggle function
        function toggleTheme() {
            themeManager.toggle();
        }
    </script>
</body>
</html>
