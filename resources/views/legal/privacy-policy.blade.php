<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="ShuleSoft Group Connect Privacy Policy - How we protect and handle your data">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Privacy Policy - ShuleSoft Group Connect</title>
    
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
                    <h1><i class="fas fa-shield-alt me-3"></i>Privacy Policy</h1>
                    <p>How ShuleSoft protects and handles your personal data with transparency and care</p>
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
                    <li class="breadcrumb-item active" aria-current="page">Privacy Policy</li>
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
                                <li><a href="#introduction">1. Introduction</a></li>
                                <li><a href="#information-we-collect">2. Information We Collect</a></li>
                                <li><a href="#how-we-use-information">3. How We Use Your Information</a></li>
                                <li><a href="#information-sharing">4. Information Sharing and Disclosure</a></li>
                                <li><a href="#data-security">5. Data Security</a></li>
                                <li><a href="#ai-data-processing">6. AI and Data Processing</a></li>
                                <li><a href="#data-retention">7. Data Retention</a></li>
                                <li><a href="#your-rights">8. Your Privacy Rights</a></li>
                                <li><a href="#international-transfers">9. International Data Transfers</a></li>
                                <li><a href="#changes">10. Changes to This Policy</a></li>
                                <li><a href="#contact">11. Contact Information</a></li>
                            </ul>
                        </div>

                        <!-- Content Sections -->
                        <section id="introduction">
                            <h2>1. Introduction</h2>
                            <p>
                                Welcome to ShuleSoft Group Connect, an AI-powered school management platform designed for multi-school owners and educational institutions. This Privacy Policy explains how ShuleSoft Africa Limited ("ShuleSoft," "we," "us," or "our") collects, uses, processes, and protects your personal information when you use our Group Connect platform and related services.
                            </p>
                            <div class="highlight">
                                <strong>Our Commitment:</strong> We are committed to protecting your privacy and maintaining the highest standards of data protection in compliance with the Tanzania Data Protection Act 2022, GDPR principles, and international best practices.
                            </div>
                            <p>
                                By using ShuleSoft Group Connect, you acknowledge that you have read, understood, and agree to the practices described in this Privacy Policy.
                            </p>
                        </section>

                        <section id="information-we-collect">
                            <h2>2. Information We Collect</h2>
                            
                            <h3>2.1 Personal Information</h3>
                            <p>We collect the following types of personal information:</p>
                            <ul>
                                <li><strong>Account Information:</strong> Name, email address, phone number, job title, organization name</li>
                                <li><strong>Authentication Data:</strong> Login credentials, security questions, multi-factor authentication data</li>
                                <li><strong>Profile Information:</strong> User preferences, role assignments, school affiliations</li>
                                <li><strong>Contact Information:</strong> Business addresses, emergency contacts, communication preferences</li>
                            </ul>

                            <h3>2.2 Educational Data</h3>
                            <p>As an educational platform, we process:</p>
                            <ul>
                                <li><strong>Student Information:</strong> Names, identification numbers, academic records, attendance data</li>
                                <li><strong>Staff Information:</strong> Employee records, payroll data, performance metrics</li>
                                <li><strong>Financial Data:</strong> Fee structures, payment records, budget information</li>
                                <li><strong>Academic Data:</strong> Grades, exam results, curriculum information, assessment data</li>
                            </ul>

                            <h3>2.3 Technical Information</h3>
                            <ul>
                                <li><strong>Usage Data:</strong> Platform interactions, feature usage, session duration</li>
                                <li><strong>Device Information:</strong> IP addresses, browser types, operating systems</li>
                                <li><strong>Log Data:</strong> System logs, error reports, security events</li>
                                <li><strong>Cookies and Tracking:</strong> Session cookies, preference cookies, analytics data</li>
                            </ul>
                        </section>

                        <section id="how-we-use-information">
                            <h2>3. How We Use Your Information</h2>
                            
                            <h3>3.1 Primary Purposes</h3>
                            <ul>
                                <li><strong>Service Delivery:</strong> Providing access to Group Connect features and functionalities</li>
                                <li><strong>Account Management:</strong> Creating, maintaining, and securing user accounts</li>
                                <li><strong>Educational Services:</strong> Facilitating school management, academic tracking, and administrative tasks</li>
                                <li><strong>Communication:</strong> Sending important updates, notifications, and support communications</li>
                            </ul>

                            <h3>3.2 AI and Analytics</h3>
                            <ul>
                                <li><strong>Performance Analytics:</strong> Generating insights on academic performance and operational efficiency</li>
                                <li><strong>Predictive Analytics:</strong> Identifying trends, anomalies, and areas for improvement</li>
                                <li><strong>Automated Reporting:</strong> Creating data-driven reports for decision-making</li>
                                <li><strong>Personalization:</strong> Customizing user experience based on role and preferences</li>
                            </ul>

                            <h3>3.3 Legal Compliance</h3>
                            <ul>
                                <li>Complying with educational regulations and reporting requirements</li>
                                <li>Meeting audit and inspection obligations</li>
                                <li>Responding to legal requests and court orders</li>
                                <li>Protecting our rights and interests</li>
                            </ul>
                        </section>

                        <section id="information-sharing">
                            <h2>4. Information Sharing and Disclosure</h2>
                            
                            <h3>4.1 Within Your Organization</h3>
                            <p>Information is shared within your school group according to user roles and permissions:</p>
                            <ul>
                                <li><strong>Owner:</strong> Access to all strategic, operational, and financial data</li>
                                <li><strong>Group Accountant:</strong> Access to finance-related dashboards and reports</li>
                                <li><strong>Group IT Officer:</strong> Access to system usage and technical dashboards</li>
                                <li><strong>Central Super Admin:</strong> User and school management capabilities</li>
                                <li><strong>Group Academic:</strong> Access to academic dashboards and reports</li>
                            </ul>

                            <h3>4.2 Third-Party Service Providers</h3>
                            <p>We may share information with trusted service providers who assist us in:</p>
                            <ul>
                                <li>Cloud hosting and infrastructure services</li>
                                <li>Data backup and disaster recovery</li>
                                <li>Analytics and performance monitoring</li>
                                <li>Customer support and communication services</li>
                                <li>Payment processing and financial services</li>
                            </ul>

                            <h3>4.3 Legal Requirements</h3>
                            <p>We may disclose information when required by law or to:</p>
                            <ul>
                                <li>Comply with legal processes or government requests</li>
                                <li>Protect the rights, property, or safety of ShuleSoft, users, or others</li>
                                <li>Investigate potential violations of our terms of service</li>
                                <li>Respond to emergency situations</li>
                            </ul>
                        </section>

                        <section id="data-security">
                            <h2>5. Data Security</h2>
                            
                            <h3>5.1 Security Measures</h3>
                            <p>We implement comprehensive security measures including:</p>
                            <ul>
                                <li><strong>Encryption:</strong> Data encryption in transit and at rest using industry-standard protocols</li>
                                <li><strong>Access Controls:</strong> Role-based access controls and multi-factor authentication</li>
                                <li><strong>Network Security:</strong> Firewalls, intrusion detection, and regular security monitoring</li>
                                <li><strong>Regular Audits:</strong> Security assessments and vulnerability testing</li>
                                <li><strong>Staff Training:</strong> Regular security awareness training for all personnel</li>
                            </ul>

                            <h3>5.2 Data Centers</h3>
                            <p>Our data is hosted in secure, certified data centers with:</p>
                            <ul>
                                <li>24/7 physical security and monitoring</li>
                                <li>Redundant power and cooling systems</li>
                                <li>Regular backup and disaster recovery procedures</li>
                                <li>Compliance with international security standards</li>
                            </ul>

                            <h3>5.3 Incident Response</h3>
                            <p>In the event of a security incident, we will:</p>
                            <ul>
                                <li>Immediately assess and contain the incident</li>
                                <li>Notify affected users and authorities as required by law</li>
                                <li>Investigate the cause and implement corrective measures</li>
                                <li>Provide regular updates on the incident resolution</li>
                            </ul>
                        </section>

                        <section id="ai-data-processing">
                            <h2>6. AI and Data Processing</h2>
                            
                            <h3>6.1 AI-Powered Features</h3>
                            <p>Our platform uses artificial intelligence to:</p>
                            <ul>
                                <li>Generate insights and analytics from educational data</li>
                                <li>Detect anomalies and patterns in academic performance</li>
                                <li>Automate report generation and data visualization</li>
                                <li>Provide predictive analytics for decision-making</li>
                                <li>Optimize system performance and user experience</li>
                            </ul>

                            <h3>6.2 AI Data Processing Principles</h3>
                            <ul>
                                <li><strong>Transparency:</strong> Clear disclosure of AI usage and decision-making processes</li>
                                <li><strong>Fairness:</strong> Algorithms designed to avoid bias and discrimination</li>
                                <li><strong>Privacy by Design:</strong> AI systems built with privacy protection as a core principle</li>
                                <li><strong>Human Oversight:</strong> Human review and validation of AI-generated insights</li>
                                <li><strong>Data Minimization:</strong> Using only necessary data for AI processing</li>
                            </ul>

                            <h3>6.3 User Control</h3>
                            <p>You have the right to:</p>
                            <ul>
                                <li>Understand how AI affects decisions about your data</li>
                                <li>Request human review of AI-generated insights</li>
                                <li>Opt-out of certain AI-powered features</li>
                                <li>Access explanations of AI decision-making processes</li>
                            </ul>
                        </section>

                        <section id="data-retention">
                            <h2>7. Data Retention</h2>
                            
                            <h3>7.1 Retention Periods</h3>
                            <ul>
                                <li><strong>Account Data:</strong> Retained while your account is active and for 2 years after closure</li>
                                <li><strong>Educational Records:</strong> Retained according to educational regulations (typically 7-10 years)</li>
                                <li><strong>Financial Data:</strong> Retained for 7 years as required by accounting standards</li>
                                <li><strong>Log Data:</strong> Retained for 12 months for security and troubleshooting purposes</li>
                                <li><strong>Communication Data:</strong> Retained for 3 years for compliance and support purposes</li>
                            </ul>

                            <h3>7.2 Secure Deletion</h3>
                            <p>When data reaches the end of its retention period, we:</p>
                            <ul>
                                <li>Securely delete or anonymize the data</li>
                                <li>Remove data from all backup systems</li>
                                <li>Provide confirmation of deletion when requested</li>
                                <li>Maintain logs of deletion activities for compliance</li>
                            </ul>
                        </section>

                        <section id="your-rights">
                            <h2>8. Your Privacy Rights</h2>
                            
                            <h3>8.1 Access and Portability</h3>
                            <ul>
                                <li><strong>Right to Access:</strong> Request copies of your personal data</li>
                                <li><strong>Data Portability:</strong> Receive your data in a structured, machine-readable format</li>
                                <li><strong>Transparency:</strong> Understand how your data is processed</li>
                            </ul>

                            <h3>8.2 Correction and Deletion</h3>
                            <ul>
                                <li><strong>Right to Rectification:</strong> Correct inaccurate or incomplete data</li>
                                <li><strong>Right to Erasure:</strong> Request deletion of your personal data (subject to legal requirements)</li>
                                <li><strong>Right to Restriction:</strong> Limit how we process your data</li>
                            </ul>

                            <h3>8.3 Objection and Withdrawal</h3>
                            <ul>
                                <li><strong>Right to Object:</strong> Object to certain types of data processing</li>
                                <li><strong>Withdraw Consent:</strong> Withdraw consent for data processing (where applicable)</li>
                                <li><strong>Opt-out:</strong> Unsubscribe from marketing communications</li>
                            </ul>

                            <h3>8.4 Exercising Your Rights</h3>
                            <p>To exercise your privacy rights, contact us at:</p>
                            <ul>
                                <li>Email: <a href="mailto:privacy@shulesoft.africa">privacy@shulesoft.africa</a></li>
                                <li>Phone: +255 123 456 789</li>
                                <li>Written request to our office address</li>
                            </ul>
                            <p>We will respond to your request within 30 days and may require identity verification.</p>
                        </section>

                        <section id="international-transfers">
                            <h2>9. International Data Transfers</h2>
                            
                            <h3>9.1 Data Localization</h3>
                            <p>In compliance with the Tanzania Data Protection Act 2022:</p>
                            <ul>
                                <li>Primary data storage is maintained within Tanzania or approved jurisdictions</li>
                                <li>Cross-border transfers are conducted only when necessary and with appropriate safeguards</li>
                                <li>We maintain data processing agreements with international service providers</li>
                                <li>Regular compliance audits ensure adherence to local data protection requirements</li>
                            </ul>

                            <h3>9.2 Transfer Safeguards</h3>
                            <p>When international transfers occur, we ensure:</p>
                            <ul>
                                <li>Adequate level of protection in the destination country</li>
                                <li>Standard contractual clauses or binding corporate rules</li>
                                <li>Encryption and security measures during transfer</li>
                                <li>Regular monitoring and compliance verification</li>
                            </ul>
                        </section>

                        <section id="changes">
                            <h2>10. Changes to This Policy</h2>
                            <p>
                                We may update this Privacy Policy periodically to reflect changes in our practices, technology, or legal requirements. When we make material changes, we will:
                            </p>
                            <ul>
                                <li>Notify you via email or in-app notification</li>
                                <li>Post the updated policy on our website</li>
                                <li>Provide a summary of key changes</li>
                                <li>Allow you to review and accept the updated policy</li>
                            </ul>
                            <p>
                                Your continued use of ShuleSoft Group Connect after the effective date of the updated policy constitutes acceptance of the changes.
                            </p>
                        </section>

                        <section id="contact">
                            <h2>11. Contact Information</h2>
                            <div class="contact-info">
                                <h4><i class="fas fa-address-book me-2"></i>Data Protection Officer</h4>
                                <div class="contact-item">
                                    <i class="fas fa-envelope"></i>
                                    <span>privacy@shulesoft.africa</span>
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
                                If you have any questions, concerns, or complaints about this Privacy Policy or our data practices, please don't hesitate to contact us. We are committed to addressing your concerns promptly and transparently.
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
                Committed to protecting your privacy and data security.
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
