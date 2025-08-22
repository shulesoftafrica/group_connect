<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="ShuleSoft Group Connect Terms of Service - Legal terms and conditions for using our platform">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Terms of Service - ShuleSoft Group Connect</title>
    
    <!-- Prevent FOUC - Theme detection and application -->
    <script>
        (function() {
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
            
            /* Shadows */
            --shadow-sm: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
            --shadow-md: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
            --shadow-lg: 0 1rem 3rem rgba(0, 0, 0, 0.175);
            
            /* Focus */
            --focus-ring: 0 0 0 0.25rem rgba(30, 186, 155, 0.25);
        }

        /* Dark Theme */
        [data-theme="dark"] {
            --bg-primary: #1a1d29;
            --bg-secondary: #232631;
            --bg-tertiary: #2a2d3a;
            --text-primary: #e9ecef;
            --text-secondary: #adb5bd;
            --text-muted: #6c757d;
            --border-color: #3d4144;
            --border-light: #495057;
            --shadow-sm: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.3);
            --shadow-md: 0 0.5rem 1rem rgba(0, 0, 0, 0.4);
            --shadow-lg: 0 1rem 3rem rgba(0, 0, 0, 0.5);
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
            line-height: 1.7;
            color: var(--text-primary);
            background: var(--bg-secondary);
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

        /* ===== HEADER ===== */
        .legal-header {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            color: white;
            padding: 4rem 0 2rem;
            margin-bottom: 0;
        }

        .legal-header h1 {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
        }

        .legal-header p {
            font-size: 1.1rem;
            opacity: 0.9;
            margin: 0;
        }

        .breadcrumb-nav {
            background: var(--bg-primary);
            border-bottom: 1px solid var(--border-color);
            padding: 1rem 0;
        }

        .breadcrumb {
            background: none;
            padding: 0;
            margin: 0;
            font-size: 0.9rem;
        }

        .breadcrumb-item a {
            color: var(--link-color);
            text-decoration: none;
        }

        .breadcrumb-item a:hover {
            color: var(--link-hover);
            text-decoration: underline;
        }

        .breadcrumb-item.active {
            color: var(--text-secondary);
        }

        /* ===== CONTENT AREA ===== */
        .legal-content {
            background: var(--bg-primary);
            border-radius: 12px;
            box-shadow: var(--shadow-md);
            border: 1px solid var(--border-color);
            overflow: hidden;
        }

        .content-body {
            padding: 3rem;
        }

        .last-updated {
            background: var(--bg-secondary);
            border: 1px solid var(--border-color);
            border-radius: 8px;
            padding: 1rem;
            margin-bottom: 2rem;
            font-size: 0.9rem;
            color: var(--text-secondary);
        }

        .toc {
            background: var(--bg-secondary);
            border: 1px solid var(--border-color);
            border-radius: 8px;
            padding: 1.5rem;
            margin-bottom: 2rem;
        }

        .toc h4 {
            color: var(--text-primary);
            margin-bottom: 1rem;
            font-size: 1.1rem;
        }

        .toc ul {
            list-style: none;
            padding: 0;
        }

        .toc li {
            margin-bottom: 0.5rem;
        }

        .toc a {
            color: var(--link-color);
            text-decoration: none;
            font-size: 0.9rem;
        }

        .toc a:hover {
            color: var(--link-hover);
            text-decoration: underline;
        }

        /* ===== TYPOGRAPHY ===== */
        h2 {
            color: var(--text-primary);
            font-size: 1.8rem;
            font-weight: 600;
            margin: 2.5rem 0 1.5rem;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid var(--primary-color);
        }

        h3 {
            color: var(--text-primary);
            font-size: 1.4rem;
            font-weight: 600;
            margin: 2rem 0 1rem;
        }

        h4 {
            color: var(--text-primary);
            font-size: 1.2rem;
            font-weight: 600;
            margin: 1.5rem 0 0.75rem;
        }

        p {
            margin-bottom: 1.2rem;
            color: var(--text-primary);
        }

        ul, ol {
            margin-bottom: 1.2rem;
            padding-left: 1.5rem;
        }

        li {
            margin-bottom: 0.5rem;
            color: var(--text-primary);
        }

        .highlight {
            background: rgba(30, 186, 155, 0.1);
            border-left: 4px solid var(--primary-color);
            padding: 1rem;
            border-radius: 0 8px 8px 0;
            margin: 1.5rem 0;
        }

        .warning-box {
            background: rgba(220, 53, 69, 0.1);
            border-left: 4px solid #dc3545;
            padding: 1rem;
            border-radius: 0 8px 8px 0;
            margin: 1.5rem 0;
            color: var(--text-primary);
        }

        /* ===== CONTACT INFO ===== */
        .contact-info {
            background: var(--bg-secondary);
            border: 1px solid var(--border-color);
            border-radius: 12px;
            padding: 2rem;
            margin-top: 3rem;
        }

        .contact-info h4 {
            color: var(--primary-color);
            margin-bottom: 1rem;
        }

        .contact-item {
            display: flex;
            align-items: center;
            margin-bottom: 0.75rem;
        }

        .contact-item i {
            color: var(--primary-color);
            margin-right: 0.75rem;
            width: 20px;
        }

        /* ===== FOOTER ===== */
        .legal-footer {
            background: var(--bg-tertiary);
            border-top: 1px solid var(--border-color);
            padding: 2rem 0;
            margin-top: 3rem;
            text-align: center;
        }

        .footer-links {
            display: flex;
            justify-content: center;
            gap: 2rem;
            margin-bottom: 1rem;
            flex-wrap: wrap;
        }

        .footer-links a {
            color: var(--link-color);
            text-decoration: none;
            font-weight: 500;
            font-size: 0.9rem;
        }

        .footer-links a:hover {
            color: var(--link-hover);
            text-decoration: underline;
        }

        .footer-text {
            color: var(--text-secondary);
            font-size: 0.9rem;
            margin: 0;
        }

        /* ===== BACK TO TOP ===== */
        .back-to-top {
            position: fixed;
            bottom: 2rem;
            right: 2rem;
            background: var(--primary-color);
            color: white;
            border: none;
            border-radius: 50%;
            width: 50px;
            height: 50px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: var(--shadow-lg);
            cursor: pointer;
            transition: all 0.3s ease;
            opacity: 0;
            visibility: hidden;
        }

        .back-to-top.visible {
            opacity: 1;
            visibility: visible;
        }

        .back-to-top:hover {
            background: var(--primary-dark);
            transform: translateY(-3px);
        }

        /* ===== RESPONSIVE ===== */
        @media (max-width: 768px) {
            .legal-header h1 {
                font-size: 2rem;
            }

            .content-body {
                padding: 2rem 1.5rem;
            }

            .footer-links {
                flex-direction: column;
                gap: 1rem;
            }
        }
    </style>
</head>
<body>
    <!-- Theme Toggle -->
    <button class="theme-toggle" id="themeToggle" title="Toggle theme">
        <i class="fas fa-moon icon dark-icon"></i>
        <i class="fas fa-sun icon light-icon"></i>
    </button>

    <!-- Header -->
    <div class="legal-header">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8 text-center">
                    <h1><i class="fas fa-file-contract me-3"></i>Terms of Service</h1>
                    <p>Legal terms and conditions governing your use of ShuleSoft Group Connect</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Breadcrumb -->
    <div class="breadcrumb-nav">
        <div class="container">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('login') }}">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Terms of Service</li>
                </ol>
            </nav>
        </div>
    </div>

    <!-- Main Content -->
    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="legal-content">
                    <div class="content-body">
                        <!-- Last Updated -->
                        <div class="last-updated">
                            <i class="fas fa-calendar me-2"></i>
                            <strong>Last Updated:</strong> {{ date('F j, Y') }}
                        </div>

                        <!-- Table of Contents -->
                        <div class="toc">
                            <h4><i class="fas fa-list me-2"></i>Table of Contents</h4>
                            <ul>
                                <li><a href="#acceptance">1. Acceptance of Terms</a></li>
                                <li><a href="#service-description">2. Service Description</a></li>
                                <li><a href="#user-accounts">3. User Accounts and Registration</a></li>
                                <li><a href="#permitted-use">4. Permitted Use</a></li>
                                <li><a href="#prohibited-activities">5. Prohibited Activities</a></li>
                                <li><a href="#data-responsibilities">6. Data and Content Responsibilities</a></li>
                                <li><a href="#subscription-payment">7. Subscription and Payment Terms</a></li>
                                <li><a href="#intellectual-property">8. Intellectual Property Rights</a></li>
                                <li><a href="#service-availability">9. Service Availability and Support</a></li>
                                <li><a href="#limitation-liability">10. Limitation of Liability</a></li>
                                <li><a href="#indemnification">11. Indemnification</a></li>
                                <li><a href="#termination">12. Termination</a></li>
                                <li><a href="#governing-law">13. Governing Law</a></li>
                                <li><a href="#changes-terms">14. Changes to Terms</a></li>
                                <li><a href="#contact-information">15. Contact Information</a></li>
                            </ul>
                        </div>

                        <!-- Content Sections -->
                        <section id="acceptance">
                            <h2>1. Acceptance of Terms</h2>
                            <p>
                                By accessing, registering for, or using ShuleSoft Group Connect ("the Service"), you agree to be bound by these Terms of Service ("Terms"). These Terms constitute a legally binding agreement between you (individually or on behalf of an entity) and ShuleSoft Africa Limited ("ShuleSoft," "we," "us," or "our").
                            </p>
                            <div class="warning-box">
                                <strong>Important:</strong> If you do not agree to these Terms, you must not use the Service. Your use of the Service constitutes acceptance of these Terms.
                            </div>
                            <p>
                                These Terms apply to all users, including but not limited to school owners, administrators, staff, and any other individuals accessing the Service through your organization's account.
                            </p>
                        </section>

                        <section id="service-description">
                            <h2>2. Service Description</h2>
                            
                            <h3>2.1 ShuleSoft Group Connect Overview</h3>
                            <p>
                                ShuleSoft Group Connect is an AI-powered, cloud-based school management platform designed for multi-school owners and educational institutions. The Service provides:
                            </p>
                            <ul>
                                <li>Centralized management of multiple schools and educational institutions</li>
                                <li>Role-based access control for various user types (Owner, Accountant, IT Officer, Academic Master, etc.)</li>
                                <li>Comprehensive dashboards for academics, finance, operations, and human resources</li>
                                <li>AI-powered analytics and insights for educational performance and operational efficiency</li>
                                <li>Integration capabilities with existing ShuleSoft school management systems</li>
                                <li>Communication tools for multi-school coordination</li>
                            </ul>

                            <h3>2.2 Service Features</h3>
                            <p>Key features include:</p>
                            <ul>
                                <li><strong>Dashboard Management:</strong> Customizable dashboards for different user roles</li>
                                <li><strong>Academic Management:</strong> Cross-school academic performance tracking and reporting</li>
                                <li><strong>Financial Management:</strong> Consolidated financial reporting and budget management</li>
                                <li><strong>Operational Oversight:</strong> Attendance, transport, hostel, and facility management</li>
                                <li><strong>HR Management:</strong> Staff management across multiple schools</li>
                                <li><strong>Communication Tools:</strong> Bulk messaging and notification systems</li>
                                <li><strong>AI Analytics:</strong> Predictive analytics and automated insights</li>
                                <li><strong>Reporting Tools:</strong> Comprehensive reporting and data export capabilities</li>
                            </ul>

                            <h3>2.3 Service Modifications</h3>
                            <p>
                                We reserve the right to modify, update, or discontinue any aspect of the Service at any time. We will provide reasonable notice of material changes that affect functionality or user experience.
                            </p>
                        </section>

                        <section id="user-accounts">
                            <h2>3. User Accounts and Registration</h2>
                            
                            <h3>3.1 Account Creation</h3>
                            <p>To use the Service, you must:</p>
                            <ul>
                                <li>Provide accurate, complete, and current registration information</li>
                                <li>Maintain and update your account information</li>
                                <li>Be at least 18 years old or have legal authority to enter into this agreement</li>
                                <li>Have authority to bind your organization to these Terms</li>
                            </ul>

                            <h3>3.2 Account Security</h3>
                            <p>You are responsible for:</p>
                            <ul>
                                <li>Maintaining the confidentiality of your login credentials</li>
                                <li>All activities that occur under your account</li>
                                <li>Immediately notifying us of any unauthorized access or security breaches</li>
                                <li>Implementing appropriate security measures within your organization</li>
                            </ul>

                            <h3>3.3 User Roles and Permissions</h3>
                            <p>The Service supports various user roles with different access levels:</p>
                            <ul>
                                <li><strong>Owner:</strong> Full access to all strategic, operational, and financial data</li>
                                <li><strong>Group Accountant:</strong> Access to finance-related dashboards and reports</li>
                                <li><strong>Group IT Officer:</strong> Access to system usage and technical dashboards</li>
                                <li><strong>Central Super Admin:</strong> User and school management capabilities</li>
                                <li><strong>Group Academic:</strong> Access to academic dashboards and reports</li>
                            </ul>
                            <p>
                                You are responsible for properly assigning roles and managing user permissions within your organization.
                            </p>
                        </section>

                        <section id="permitted-use">
                            <h2>4. Permitted Use</h2>
                            
                            <h3>4.1 Authorized Use</h3>
                            <p>You may use the Service to:</p>
                            <ul>
                                <li>Manage educational institutions and related operations</li>
                                <li>Store, process, and analyze educational data</li>
                                <li>Generate reports and insights for decision-making</li>
                                <li>Communicate with staff, students, and stakeholders</li>
                                <li>Integrate with other authorized educational tools and systems</li>
                            </ul>

                            <h3>4.2 Compliance Requirements</h3>
                            <p>Your use of the Service must comply with:</p>
                            <ul>
                                <li>All applicable local, national, and international laws</li>
                                <li>Educational regulations and standards</li>
                                <li>Data protection and privacy laws</li>
                                <li>These Terms of Service and related policies</li>
                            </ul>

                            <h3>4.3 User Conduct</h3>
                            <p>You agree to use the Service in a manner that:</p>
                            <ul>
                                <li>Respects the rights and privacy of others</li>
                                <li>Does not interfere with the Service's operation</li>
                                <li>Maintains the security and integrity of the platform</li>
                                <li>Follows professional and ethical standards</li>
                            </ul>
                        </section>

                        <section id="prohibited-activities">
                            <h2>5. Prohibited Activities</h2>
                            
                            <h3>5.1 Strictly Prohibited</h3>
                            <p>You must not:</p>
                            <ul>
                                <li><strong>Unauthorized Access:</strong> Attempt to access accounts, data, or systems not assigned to you</li>
                                <li><strong>Security Violations:</strong> Circumvent security measures or attempt to breach system security</li>
                                <li><strong>Data Misuse:</strong> Use data for purposes beyond authorized educational management</li>
                                <li><strong>System Abuse:</strong> Overload, crash, or impair the Service's operation</li>
                                <li><strong>Illegal Activities:</strong> Use the Service for any unlawful purpose</li>
                                <li><strong>Intellectual Property Infringement:</strong> Violate copyrights, trademarks, or other IP rights</li>
                            </ul>

                            <h3>5.2 Data and Content Restrictions</h3>
                            <p>You must not upload, store, or transmit:</p>
                            <ul>
                                <li>Malicious software, viruses, or harmful code</li>
                                <li>Content that violates applicable laws or regulations</li>
                                <li>Inappropriate, offensive, or discriminatory material</li>
                                <li>Personal data without proper authorization</li>
                                <li>Content that infringes on third-party rights</li>
                            </ul>

                            <h3>5.3 Commercial Restrictions</h3>
                            <p>Without explicit written permission, you may not:</p>
                            <ul>
                                <li>Resell, redistribute, or commercialize the Service</li>
                                <li>Use the Service for competitive analysis or benchmarking</li>
                                <li>Reverse engineer or attempt to extract source code</li>
                                <li>Create derivative works based on the Service</li>
                            </ul>
                        </section>

                        <section id="data-responsibilities">
                            <h2>6. Data and Content Responsibilities</h2>
                            
                            <h3>6.1 Your Data Ownership</h3>
                            <p>
                                You retain ownership of all data you input into the Service ("Customer Data"). This includes educational records, financial information, personal data, and any other content you upload or create through the Service.
                            </p>

                            <h3>6.2 Data Accuracy and Legality</h3>
                            <p>You are responsible for ensuring that:</p>
                            <ul>
                                <li>All data is accurate, complete, and current</li>
                                <li>You have the legal right to process and use the data</li>
                                <li>Data collection and processing complies with applicable laws</li>
                                <li>You have obtained necessary consents for data processing</li>
                            </ul>

                            <h3>6.3 Data Security Obligations</h3>
                            <p>You must:</p>
                            <ul>
                                <li>Implement appropriate organizational security measures</li>
                                <li>Train users on data protection and security practices</li>
                                <li>Monitor and audit data access within your organization</li>
                                <li>Report security incidents or data breaches promptly</li>
                            </ul>

                            <h3>6.4 Our Data Processing</h3>
                            <p>We may process your data to:</p>
                            <ul>
                                <li>Provide and improve the Service</li>
                                <li>Generate analytics and insights</li>
                                <li>Ensure system security and performance</li>
                                <li>Comply with legal obligations</li>
                            </ul>
                            <p>
                                Detailed information about our data processing practices is available in our <a href="{{ route('legal.privacy-policy') }}">Privacy Policy</a> and <a href="{{ route('legal.data-processing-agreement') }}">Data Processing Agreement</a>.
                            </p>
                        </section>

                        <section id="subscription-payment">
                            <h2>7. Subscription and Payment Terms</h2>
                            
                            <h3>7.1 Subscription Plans</h3>
                            <p>
                                The Service is offered through various subscription plans with different features, user limits, and pricing. Current plans and pricing are available on our website and subject to change with notice.
                            </p>

                            <h3>7.2 Payment Terms</h3>
                            <ul>
                                <li><strong>Billing Cycles:</strong> Subscriptions are billed monthly or annually as selected</li>
                                <li><strong>Payment Due:</strong> Payment is due in advance for each billing period</li>
                                <li><strong>Currency:</strong> All fees are quoted in Tanzanian Shillings (TZS) unless otherwise stated</li>
                                <li><strong>Taxes:</strong> Fees exclude applicable taxes, which you are responsible for paying</li>
                            </ul>

                            <h3>7.3 Refunds and Cancellations</h3>
                            <ul>
                                <li><strong>Cancellation:</strong> You may cancel your subscription at any time</li>
                                <li><strong>Effect of Cancellation:</strong> Service access continues until the end of the current billing period</li>
                                <li><strong>Refunds:</strong> Fees are generally non-refundable except as required by law</li>
                                <li><strong>Data Access:</strong> We provide data export capabilities upon cancellation</li>
                            </ul>

                            <h3>7.4 Price Changes</h3>
                            <p>
                                We may change subscription prices with 30 days' advance notice. Price changes will take effect at your next billing cycle. Continued use of the Service after price changes constitutes acceptance of the new pricing.
                            </p>
                        </section>

                        <section id="intellectual-property">
                            <h2>8. Intellectual Property Rights</h2>
                            
                            <h3>8.1 Our Intellectual Property</h3>
                            <p>
                                ShuleSoft retains all rights to the Service, including software, algorithms, user interfaces, documentation, trademarks, and other intellectual property. The Service is protected by copyright, trademark, and other intellectual property laws.
                            </p>

                            <h3>8.2 License to Use</h3>
                            <p>
                                We grant you a limited, non-exclusive, non-transferable license to use the Service in accordance with these Terms. This license does not include the right to:
                            </p>
                            <ul>
                                <li>Modify, reverse engineer, or create derivative works</li>
                                <li>Remove or alter proprietary notices</li>
                                <li>Transfer or sublicense the Service</li>
                                <li>Use the Service beyond your subscription terms</li>
                            </ul>

                            <h3>8.3 User-Generated Content</h3>
                            <p>
                                While you retain ownership of your data, you grant us a license to process, store, and use your content as necessary to provide the Service. This includes the right to:
                            </p>
                            <ul>
                                <li>Store and backup your data</li>
                                <li>Process data to provide Service features</li>
                                <li>Create aggregated, anonymized analytics</li>
                                <li>Ensure system security and performance</li>
                            </ul>

                            <h3>8.4 Feedback and Suggestions</h3>
                            <p>
                                Any feedback, suggestions, or ideas you provide about the Service become our property and may be used to improve the Service without compensation or attribution.
                            </p>
                        </section>

                        <section id="service-availability">
                            <h2>9. Service Availability and Support</h2>
                            
                            <h3>9.1 Service Level Commitment</h3>
                            <p>
                                We strive to maintain high service availability but cannot guarantee uninterrupted access. The Service may be temporarily unavailable due to:
                            </p>
                            <ul>
                                <li>Scheduled maintenance and updates</li>
                                <li>Emergency repairs or security measures</li>
                                <li>Third-party service provider issues</li>
                                <li>Force majeure events beyond our control</li>
                            </ul>

                            <h3>9.2 Support Services</h3>
                            <p>Support is provided according to your subscription plan:</p>
                            <ul>
                                <li><strong>Documentation:</strong> Online help resources and user guides</li>
                                <li><strong>Email Support:</strong> Technical support via email</li>
                                <li><strong>Phone Support:</strong> Available for premium plans</li>
                                <li><strong>Training:</strong> Onboarding and training services as applicable</li>
                            </ul>

                            <h3>9.3 Maintenance and Updates</h3>
                            <p>
                                We regularly update the Service to improve functionality, security, and performance. Updates may be deployed automatically, and we will notify users of significant changes.
                            </p>
                        </section>

                        <section id="limitation-liability">
                            <h2>10. Limitation of Liability</h2>
                            
                            <h3>10.1 Service Disclaimers</h3>
                            <p>
                                The Service is provided "as is" and "as available" without warranties of any kind, either express or implied. We disclaim all warranties, including but not limited to merchantability, fitness for a particular purpose, and non-infringement.
                            </p>

                            <h3>10.2 Limitation of Damages</h3>
                            <p>
                                To the maximum extent permitted by law, ShuleSoft shall not be liable for any indirect, incidental, special, consequential, or punitive damages, including but not limited to:
                            </p>
                            <ul>
                                <li>Loss of profits, revenue, or business opportunities</li>
                                <li>Loss of data or information</li>
                                <li>Business interruption or operational delays</li>
                                <li>Cost of substitute services</li>
                                <li>Reputational harm or goodwill loss</li>
                            </ul>

                            <h3>10.3 Liability Cap</h3>
                            <p>
                                Our total liability for any claims arising from or related to the Service shall not exceed the amount you paid for the Service in the 12 months preceding the claim.
                            </p>

                            <h3>10.4 Essential Purpose</h3>
                            <p>
                                These limitations are fundamental elements of the agreement between us. The Service would not be provided without these limitations.
                            </p>
                        </section>

                        <section id="indemnification">
                            <h2>11. Indemnification</h2>
                            
                            <h3>11.1 Your Indemnification Obligations</h3>
                            <p>
                                You agree to indemnify, defend, and hold harmless ShuleSoft and its affiliates from any claims, damages, losses, or expenses arising from:
                            </p>
                            <ul>
                                <li>Your use of the Service in violation of these Terms</li>
                                <li>Your violation of applicable laws or regulations</li>
                                <li>Infringement of third-party rights by your content or data</li>
                                <li>Unauthorized access to the Service by your users</li>
                                <li>Any negligent or wrongful acts by your organization</li>
                            </ul>

                            <h3>11.2 Our Indemnification</h3>
                            <p>
                                We will defend you against claims that the Service infringes third-party intellectual property rights, provided you notify us promptly and allow us to control the defense.
                            </p>

                            <h3>11.3 Conditions</h3>
                            <p>
                                Indemnification obligations are subject to prompt notification of claims, cooperation in defense, and our sole control over defense and settlement decisions.
                            </p>
                        </section>

                        <section id="termination">
                            <h2>12. Termination</h2>
                            
                            <h3>12.1 Termination by You</h3>
                            <p>
                                You may terminate your subscription at any time by canceling through your account settings or contacting our support team. Termination is effective at the end of your current billing period.
                            </p>

                            <h3>12.2 Termination by Us</h3>
                            <p>
                                We may terminate or suspend your access immediately if you:
                            </p>
                            <ul>
                                <li>Violate these Terms or our policies</li>
                                <li>Fail to pay subscription fees</li>
                                <li>Engage in harmful or illegal activities</li>
                                <li>Compromise system security or integrity</li>
                            </ul>

                            <h3>12.3 Effect of Termination</h3>
                            <p>
                                Upon termination:
                            </p>
                            <ul>
                                <li>Your access to the Service will cease</li>
                                <li>We will provide data export capabilities for 30 days</li>
                                <li>Your data may be deleted after the retention period</li>
                                <li>Outstanding fees remain due and payable</li>
                                <li>Provisions that should survive termination will continue to apply</li>
                            </ul>

                            <h3>12.4 Data Retrieval</h3>
                            <p>
                                Upon termination, you may request export of your data in standard formats. Data export must be requested within 30 days of termination. After this period, we may delete your data in accordance with our retention policies.
                            </p>
                        </section>

                        <section id="governing-law">
                            <h2>13. Governing Law and Dispute Resolution</h2>
                            
                            <h3>13.1 Governing Law</h3>
                            <p>
                                These Terms are governed by the laws of the United Republic of Tanzania. Any disputes arising from these Terms or the Service will be subject to the exclusive jurisdiction of the courts of Tanzania.
                            </p>

                            <h3>13.2 Dispute Resolution</h3>
                            <p>
                                We encourage resolution of disputes through negotiation. Before initiating legal proceedings, you agree to:
                            </p>
                            <ul>
                                <li>Notify us in writing of the dispute</li>
                                <li>Participate in good faith negotiations for 30 days</li>
                                <li>Consider mediation or arbitration if negotiations fail</li>
                            </ul>

                            <h3>13.3 Class Action Waiver</h3>
                            <p>
                                You agree to resolve disputes individually and waive the right to participate in class actions or collective proceedings.
                            </p>
                        </section>

                        <section id="changes-terms">
                            <h2>14. Changes to These Terms</h2>
                            
                            <h3>14.1 Modification Rights</h3>
                            <p>
                                We may modify these Terms at any time to reflect changes in the Service, legal requirements, or business practices. Material changes will be communicated through:
                            </p>
                            <ul>
                                <li>Email notification to your registered address</li>
                                <li>In-app notifications within the Service</li>
                                <li>Prominent notices on our website</li>
                                <li>Updated version posted with revision date</li>
                            </ul>

                            <h3>14.2 Acceptance of Changes</h3>
                            <p>
                                Continued use of the Service after the effective date of modified Terms constitutes acceptance. If you do not agree to the changes, you must discontinue use of the Service.
                            </p>

                            <h3>14.3 Version Control</h3>
                            <p>
                                We maintain previous versions of these Terms for reference. The current version supersedes all previous versions.
                            </p>
                        </section>

                        <section id="contact-information">
                            <h2>15. Contact Information</h2>
                            <div class="contact-info">
                                <h4><i class="fas fa-address-book me-2"></i>Legal Department</h4>
                                <div class="contact-item">
                                    <i class="fas fa-envelope"></i>
                                    <span>legal@shulesoft.africa</span>
                                </div>
                                <div class="contact-item">
                                    <i class="fas fa-phone"></i>
                                    <span>+255 123 456 789</span>
                                </div>
                                <div class="contact-item">
                                    <i class="fas fa-map-marker-alt"></i>
                                    <span>ShuleSoft Africa Limited<br>
                                    123 Education Street<br>
                                    Dar es Salaam, Tanzania</span>
                                </div>
                                <div class="contact-item">
                                    <i class="fas fa-globe"></i>
                                    <span><a href="https://shulesoft.africa" target="_blank">www.shulesoft.africa</a></span>
                                </div>
                            </div>
                            
                            <p style="margin-top: 2rem;">
                                For questions about these Terms, legal matters, or compliance issues, please contact our legal department. For technical support or account issues, please use our standard support channels.
                            </p>
                        </section>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <div class="legal-footer">
        <div class="container">
            <div class="footer-links">
                <a href="{{ route('legal.privacy-policy') }}">Privacy Policy</a>
                <a href="{{ route('legal.terms-of-service') }}">Terms of Service</a>
                <a href="{{ route('legal.ai-policy-security') }}">AI Policy & Security</a>
                <a href="{{ route('legal.data-processing-agreement') }}">Data Processing Agreement</a>
                <a href="{{ route('login') }}">Back to Login</a>
            </div>
            <p class="footer-text">
                © {{ date('Y') }} ShuleSoft Africa Limited. All rights reserved. | 
                Protecting your rights and our platform's integrity.
            </p>
        </div>
    </div>

    <!-- Back to Top Button -->
    <button class="back-to-top" id="backToTop" title="Back to top">
        <i class="fas fa-arrow-up"></i>
    </button>

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
            }
        }

        // Initialize theme manager
        const themeManager = new ThemeManager();

        // Back to Top functionality
        document.addEventListener('DOMContentLoaded', function() {
            const backToTop = document.getElementById('backToTop');
            
            window.addEventListener('scroll', function() {
                if (window.pageYOffset > 300) {
                    backToTop.classList.add('visible');
                } else {
                    backToTop.classList.remove('visible');
                }
            });

            backToTop.addEventListener('click', function() {
                window.scrollTo({
                    top: 0,
                    behavior: 'smooth'
                });
            });
        });

        // Smooth scrolling for anchor links
        document.addEventListener('DOMContentLoaded', function() {
            const links = document.querySelectorAll('a[href^="#"]');
            
            links.forEach(link => {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    
                    const targetId = this.getAttribute('href').substring(1);
                    const targetElement = document.getElementById(targetId);
                    
                    if (targetElement) {
                        targetElement.scrollIntoView({
                            behavior: 'smooth',
                            block: 'start'
                        });
                    }
                });
            });
        });
    </script>
</body>
</html>
