<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="ShuleSoft Group Connect AI Policy & Security - Our approach to artificial intelligence and data security">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>AI Policy & Security - ShuleSoft Group Connect</title>
    
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
            --ai-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            
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
            background: var(--ai-gradient);
            color: white;
            padding: 4rem 0 2rem;
            margin-bottom: 0;
            position: relative;
            overflow: hidden;
        }

        .legal-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><circle cx="20" cy="20" r="2" fill="rgba(255,255,255,0.1)"/><circle cx="80" cy="40" r="1.5" fill="rgba(255,255,255,0.1)"/><circle cx="40" cy="80" r="1" fill="rgba(255,255,255,0.1)"/><circle cx="90" cy="80" r="2.5" fill="rgba(255,255,255,0.1)"/></svg>') repeat;
            animation: float 20s infinite linear;
        }

        @keyframes float {
            0% { transform: translateY(0px) translateX(0px); }
            100% { transform: translateY(-100px) translateX(-100px); }
        }

        .legal-header h1 {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
            position: relative;
            z-index: 1;
        }

        .legal-header p {
            font-size: 1.1rem;
            opacity: 0.9;
            margin: 0;
            position: relative;
            z-index: 1;
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

        .ai-feature-box {
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.1), rgba(118, 75, 162, 0.1));
            border: 1px solid rgba(102, 126, 234, 0.3);
            border-radius: 12px;
            padding: 1.5rem;
            margin: 1.5rem 0;
        }

        .security-badge {
            display: inline-flex;
            align-items: center;
            background: rgba(25, 135, 84, 0.1);
            color: var(--success);
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
            border: 1px solid rgba(25, 135, 84, 0.2);
            margin: 0.25rem;
        }

        .security-badge i {
            margin-right: 0.5rem;
        }

        .principle-card {
            background: var(--bg-secondary);
            border: 1px solid var(--border-color);
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            transition: all 0.3s ease;
        }

        .principle-card:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
        }

        .principle-card h4 {
            color: var(--primary-color);
            margin-bottom: 0.75rem;
            display: flex;
            align-items: center;
        }

        .principle-card h4 i {
            margin-right: 0.75rem;
            font-size: 1.3rem;
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

            .principle-card {
                margin-bottom: 1rem;
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
                    <h1><i class="fas fa-robot me-3"></i>AI Policy & Security</h1>
                    <p>Our commitment to responsible AI and comprehensive security in educational technology</p>
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
                    <li class="breadcrumb-item active" aria-current="page">AI Policy & Security</li>
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
                                <li><a href="#ai-principles">2. AI Principles and Ethics</a></li>
                                <li><a href="#ai-applications">3. AI Applications in ShuleSoft</a></li>
                                <li><a href="#data-security">4. Data Security Framework</a></li>
                                <li><a href="#ai-transparency">5. AI Transparency and Explainability</a></li>
                                <li><a href="#bias-fairness">6. Bias Prevention and Fairness</a></li>
                                <li><a href="#user-control">7. User Control and Consent</a></li>
                                <li><a href="#security-measures">8. Technical Security Measures</a></li>
                                <li><a href="#incident-response">9. Security Incident Response</a></li>
                                <li><a href="#compliance">10. Regulatory Compliance</a></li>
                                <li><a href="#continuous-improvement">11. Continuous Improvement</a></li>
                                <li><a href="#contact">12. Contact Information</a></li>
                            </ul>
                        </div>

                        <!-- Content Sections -->
                        <section id="introduction">
                            <h2>1. Introduction</h2>
                            <p>
                                At ShuleSoft, we believe in harnessing the power of artificial intelligence to transform education while maintaining the highest standards of security, privacy, and ethical responsibility. This AI Policy & Security document outlines our approach to developing, deploying, and governing AI technologies within the ShuleSoft Group Connect platform.
                            </p>
                            <div class="highlight">
                                <strong>Our Commitment:</strong> We are committed to developing AI that is transparent, fair, secure, and beneficial to educational communities while respecting privacy rights and maintaining data security.
                            </div>
                            <p>
                                This policy applies to all AI-powered features, algorithms, and automated decision-making systems within our platform, ensuring they align with our values and meet regulatory requirements.
                            </p>
                        </section>

                        <section id="ai-principles">
                            <h2>2. AI Principles and Ethics</h2>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <div class="principle-card">
                                        <h4><i class="fas fa-balance-scale"></i>Fairness & Non-Discrimination</h4>
                                        <p>Our AI systems are designed to treat all users fairly, regardless of background, ensuring equitable access to educational opportunities and insights.</p>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="principle-card">
                                        <h4><i class="fas fa-eye"></i>Transparency & Explainability</h4>
                                        <p>We provide clear explanations of how our AI systems work and make decisions, enabling users to understand and trust our technology.</p>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="principle-card">
                                        <h4><i class="fas fa-shield-alt"></i>Privacy by Design</h4>
                                        <p>Privacy protection is built into our AI systems from the ground up, ensuring personal data is handled with utmost care and respect.</p>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="principle-card">
                                        <h4><i class="fas fa-user-check"></i>Human Oversight</h4>
                                        <p>Human judgment remains central to critical decisions, with AI serving as a tool to enhance rather than replace human expertise.</p>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="principle-card">
                                        <h4><i class="fas fa-cog"></i>Reliability & Safety</h4>
                                        <p>Our AI systems are rigorously tested and monitored to ensure reliable performance and safe operation in educational environments.</p>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="principle-card">
                                        <h4><i class="fas fa-graduation-cap"></i>Educational Benefit</h4>
                                        <p>All AI applications are designed to genuinely improve educational outcomes and administrative efficiency, not for their own sake.</p>
                                    </div>
                                </div>
                            </div>
                        </section>

                        <section id="ai-applications">
                            <h2>3. AI Applications in ShuleSoft</h2>
                            
                            <h3>3.1 Academic Performance Analytics</h3>
                            <div class="ai-feature-box">
                                <h4><i class="fas fa-chart-line me-2"></i>Intelligent Performance Insights</h4>
                                <ul>
                                    <li><strong>Academic Trend Analysis:</strong> AI identifies patterns in student performance across subjects and time periods</li>
                                    <li><strong>Early Warning Systems:</strong> Predictive models identify students at risk of academic difficulties</li>
                                    <li><strong>Personalized Recommendations:</strong> AI suggests targeted interventions and support strategies</li>
                                    <li><strong>Comparative Analytics:</strong> Cross-school performance comparisons with contextual insights</li>
                                </ul>
                            </div>

                            <h3>3.2 Operational Efficiency AI</h3>
                            <div class="ai-feature-box">
                                <h4><i class="fas fa-brain me-2"></i>Smart Operations Management</h4>
                                <ul>
                                    <li><strong>Attendance Pattern Analysis:</strong> AI detects unusual attendance patterns and trends</li>
                                    <li><strong>Resource Optimization:</strong> Predictive models for resource allocation and planning</li>
                                    <li><strong>Anomaly Detection:</strong> Automated identification of operational irregularities</li>
                                    <li><strong>Predictive Maintenance:</strong> AI-driven facility and equipment maintenance scheduling</li>
                                </ul>
                            </div>

                            <h3>3.3 Financial Intelligence</h3>
                            <div class="ai-feature-box">
                                <h4><i class="fas fa-calculator me-2"></i>AI-Powered Financial Management</h4>
                                <ul>
                                    <li><strong>Revenue Forecasting:</strong> Predictive models for fee collection and revenue planning</li>
                                    <li><strong>Expense Analysis:</strong> AI identifies spending patterns and optimization opportunities</li>
                                    <li><strong>Budget Anomalies:</strong> Automated detection of unusual financial activities</li>
                                    <li><strong>Financial Health Scoring:</strong> AI-generated school financial health assessments</li>
                                </ul>
                            </div>

                            <h3>3.4 Communication Intelligence</h3>
                            <div class="ai-feature-box">
                                <h4><i class="fas fa-comments me-2"></i>Smart Communication Systems</h4>
                                <ul>
                                    <li><strong>Message Optimization:</strong> AI suggests optimal timing and content for communications</li>
                                    <li><strong>Sentiment Analysis:</strong> Understanding community sentiment from feedback and communications</li>
                                    <li><strong>Auto-Translation:</strong> AI-powered translation for multi-language support</li>
                                    <li><strong>Engagement Analytics:</strong> AI measures and improves communication effectiveness</li>
                                </ul>
                            </div>
                        </section>

                        <section id="data-security">
                            <h2>4. Data Security Framework</h2>
                            
                            <h3>4.1 Security Standards</h3>
                            <p>Our comprehensive security framework includes:</p>
                            <div class="row">
                                <div class="col-md-4 text-center mb-3">
                                    <div class="security-badge">
                                        <i class="fas fa-lock"></i>
                                        AES-256 Encryption
                                    </div>
                                </div>
                                <div class="col-md-4 text-center mb-3">
                                    <div class="security-badge">
                                        <i class="fas fa-certificate"></i>
                                        ISO 27001 Compliant
                                    </div>
                                </div>
                                <div class="col-md-4 text-center mb-3">
                                    <div class="security-badge">
                                        <i class="fas fa-shield-virus"></i>
                                        SOC 2 Type II
                                    </div>
                                </div>
                                <div class="col-md-4 text-center mb-3">
                                    <div class="security-badge">
                                        <i class="fas fa-user-secret"></i>
                                        Zero Trust Architecture
                                    </div>
                                </div>
                                <div class="col-md-4 text-center mb-3">
                                    <div class="security-badge">
                                        <i class="fas fa-cloud-upload-alt"></i>
                                        Secure Cloud Infrastructure
                                    </div>
                                </div>
                                <div class="col-md-4 text-center mb-3">
                                    <div class="security-badge">
                                        <i class="fas fa-history"></i>
                                        Continuous Monitoring
                                    </div>
                                </div>
                            </div>

                            <h3>4.2 Data Protection Layers</h3>
                            <ul>
                                <li><strong>Encryption at Rest:</strong> All stored data is encrypted using industry-standard AES-256 encryption</li>
                                <li><strong>Encryption in Transit:</strong> TLS 1.3 encryption for all data transmission</li>
                                <li><strong>Application Layer Security:</strong> Input validation, output encoding, and secure coding practices</li>
                                <li><strong>Database Security:</strong> Encrypted databases with access controls and audit logging</li>
                                <li><strong>Network Security:</strong> Firewalls, intrusion detection, and network segmentation</li>
                                <li><strong>Infrastructure Security:</strong> Hardened servers, security patches, and configuration management</li>
                            </ul>

                            <h3>4.3 Access Control and Authentication</h3>
                            <ul>
                                <li><strong>Multi-Factor Authentication (MFA):</strong> Required for all administrative accounts</li>
                                <li><strong>Role-Based Access Control (RBAC):</strong> Granular permissions based on user roles</li>
                                <li><strong>Single Sign-On (SSO):</strong> Secure authentication across integrated systems</li>
                                <li><strong>Session Management:</strong> Secure session handling with automatic timeout</li>
                                <li><strong>Audit Logging:</strong> Comprehensive logging of all access and activities</li>
                            </ul>
                        </section>

                        <section id="ai-transparency">
                            <h2>5. AI Transparency and Explainability</h2>
                            
                            <h3>5.1 Algorithmic Transparency</h3>
                            <p>We ensure transparency in our AI systems through:</p>
                            <ul>
                                <li><strong>Clear Documentation:</strong> Detailed descriptions of AI model purposes and capabilities</li>
                                <li><strong>Decision Explanations:</strong> Plain-language explanations of AI-generated insights</li>
                                <li><strong>Model Limitations:</strong> Clear communication of what our AI can and cannot do</li>
                                <li><strong>Data Sources:</strong> Transparency about data used for training and inference</li>
                                <li><strong>Performance Metrics:</strong> Regular reporting on AI system accuracy and effectiveness</li>
                            </ul>

                            <h3>5.2 User Understanding</h3>
                            <p>We help users understand AI through:</p>
                            <ul>
                                <li><strong>Visual Indicators:</strong> Clear labeling of AI-generated content and recommendations</li>
                                <li><strong>Educational Resources:</strong> Training materials and documentation on AI features</li>
                                <li><strong>Contextual Help:</strong> In-app explanations and guidance for AI features</li>
                                <li><strong>Support Channels:</strong> Dedicated support for AI-related questions and concerns</li>
                            </ul>

                            <h3>5.3 Explainable AI (XAI)</h3>
                            <p>Our commitment to explainable AI includes:</p>
                            <ul>
                                <li><strong>Feature Importance:</strong> Showing which factors most influence AI decisions</li>
                                <li><strong>Confidence Scores:</strong> Indicating how certain the AI is about its predictions</li>
                                <li><strong>Alternative Scenarios:</strong> Showing how changes in data might affect outcomes</li>
                                <li><strong>Reasoning Paths:</strong> Step-by-step explanation of AI decision-making processes</li>
                            </ul>
                        </section>

                        <section id="bias-fairness">
                            <h2>6. Bias Prevention and Fairness</h2>
                            
                            <h3>6.1 Bias Detection and Mitigation</h3>
                            <p>We actively work to prevent and address bias through:</p>
                            <ul>
                                <li><strong>Diverse Training Data:</strong> Ensuring representative datasets across different demographics</li>
                                <li><strong>Bias Testing:</strong> Regular testing for unfair bias in AI model outputs</li>
                                <li><strong>Fairness Metrics:</strong> Measuring and monitoring fairness across different groups</li>
                                <li><strong>Algorithm Audits:</strong> Independent reviews of AI systems for bias and fairness</li>
                                <li><strong>Corrective Actions:</strong> Implementing fixes when bias is detected</li>
                            </ul>

                            <h3>6.2 Inclusive Design</h3>
                            <p>Our inclusive design approach ensures:</p>
                            <ul>
                                <li><strong>Diverse Teams:</strong> Multidisciplinary teams developing AI with diverse perspectives</li>
                                <li><strong>Stakeholder Input:</strong> Regular feedback from educational communities</li>
                                <li><strong>Cultural Sensitivity:</strong> AI systems that respect local educational contexts</li>
                                <li><strong>Accessibility:</strong> AI features designed for users with diverse needs</li>
                            </ul>

                            <h3>6.3 Fairness Principles</h3>
                            <ul>
                                <li><strong>Equal Treatment:</strong> AI provides consistent quality of service to all users</li>
                                <li><strong>Equal Opportunity:</strong> AI recommendations don't disadvantage any group</li>
                                <li><strong>Demographic Parity:</strong> AI outcomes are fair across different demographics</li>
                                <li><strong>Individual Fairness:</strong> Similar individuals receive similar AI treatment</li>
                            </ul>
                        </section>

                        <section id="user-control">
                            <h2>7. User Control and Consent</h2>
                            
                            <h3>7.1 User Control Options</h3>
                            <p>Users have control over AI features through:</p>
                            <ul>
                                <li><strong>Feature Toggle:</strong> Ability to enable or disable specific AI features</li>
                                <li><strong>Customization:</strong> Adjusting AI parameters and preferences</li>
                                <li><strong>Data Preferences:</strong> Controlling which data is used for AI processing</li>
                                <li><strong>Notification Settings:</strong> Managing AI-generated alerts and recommendations</li>
                                <li><strong>Feedback Mechanisms:</strong> Providing input on AI performance and accuracy</li>
                            </ul>

                            <h3>7.2 Informed Consent</h3>
                            <p>We ensure informed consent through:</p>
                            <ul>
                                <li><strong>Clear Disclosure:</strong> Transparent communication about AI usage</li>
                                <li><strong>Purpose Limitation:</strong> AI is used only for stated educational purposes</li>
                                <li><strong>Opt-in Approach:</strong> Users actively choose to enable AI features</li>
                                <li><strong>Regular Updates:</strong> Informing users about changes to AI capabilities</li>
                                <li><strong>Withdrawal Rights:</strong> Easy process to withdraw consent and disable AI</li>
                            </ul>

                            <h3>7.3 Human Override</h3>
                            <p>Human oversight is maintained through:</p>
                            <ul>
                                <li><strong>Manual Review:</strong> All critical AI decisions can be reviewed by humans</li>
                                <li><strong>Override Capabilities:</strong> Users can override AI recommendations</li>
                                <li><strong>Appeal Process:</strong> Mechanism to contest AI-generated decisions</li>
                                <li><strong>Expert Review:</strong> Domain experts validate AI insights and recommendations</li>
                            </ul>
                        </section>

                        <section id="security-measures">
                            <h2>8. Technical Security Measures</h2>
                            
                            <h3>8.1 Infrastructure Security</h3>
                            <ul>
                                <li><strong>Cloud Security:</strong> Secure cloud infrastructure with global data centers</li>
                                <li><strong>Network Isolation:</strong> Segmented networks with strict access controls</li>
                                <li><strong>DDoS Protection:</strong> Advanced protection against distributed attacks</li>
                                <li><strong>Redundancy:</strong> Multiple backup systems and failover mechanisms</li>
                                <li><strong>Monitoring:</strong> 24/7 security monitoring and threat detection</li>
                            </ul>

                            <h3>8.2 Application Security</h3>
                            <ul>
                                <li><strong>Secure Development:</strong> Security-first development lifecycle (SDLC)</li>
                                <li><strong>Code Reviews:</strong> Regular security code reviews and static analysis</li>
                                <li><strong>Penetration Testing:</strong> Regular security assessments by third parties</li>
                                <li><strong>Vulnerability Management:</strong> Proactive identification and remediation</li>
                                <li><strong>API Security:</strong> Secure API design and implementation</li>
                            </ul>

                            <h3>8.3 Data Security Specific to AI</h3>
                            <ul>
                                <li><strong>Model Security:</strong> Protection of AI models from theft and tampering</li>
                                <li><strong>Training Data Protection:</strong> Secure handling of data used for AI training</li>
                                <li><strong>Inference Security:</strong> Secure processing of data during AI inference</li>
                                <li><strong>Model Versioning:</strong> Secure management of AI model versions</li>
                                <li><strong>Adversarial Protection:</strong> Defense against adversarial attacks on AI models</li>
                            </ul>
                        </section>

                        <section id="incident-response">
                            <h2>9. Security Incident Response</h2>
                            
                            <h3>9.1 Incident Response Plan</h3>
                            <p>Our comprehensive incident response includes:</p>
                            <ul>
                                <li><strong>Detection:</strong> Automated monitoring and alert systems</li>
                                <li><strong>Assessment:</strong> Rapid evaluation of incident severity and impact</li>
                                <li><strong>Containment:</strong> Immediate steps to limit damage and exposure</li>
                                <li><strong>Investigation:</strong> Thorough analysis of incident causes and scope</li>
                                <li><strong>Recovery:</strong> Restoration of normal operations and services</li>
                                <li><strong>Communication:</strong> Timely notification to affected parties</li>
                            </ul>

                            <h3>9.2 AI-Specific Incident Types</h3>
                            <ul>
                                <li><strong>Model Bias Events:</strong> Detection of unfair or discriminatory AI behavior</li>
                                <li><strong>Data Poisoning:</strong> Malicious manipulation of training data</li>
                                <li><strong>Model Theft:</strong> Unauthorized access to proprietary AI models</li>
                                <li><strong>Adversarial Attacks:</strong> Attempts to fool or manipulate AI systems</li>
                                <li><strong>Privacy Breaches:</strong> Unauthorized access to AI-processed personal data</li>
                            </ul>

                            <h3>9.3 Response Timeline</h3>
                            <ul>
                                <li><strong>Immediate (0-1 hour):</strong> Detection, initial assessment, and containment</li>
                                <li><strong>Short-term (1-24 hours):</strong> Investigation, stakeholder notification</li>
                                <li><strong>Medium-term (1-7 days):</strong> Resolution, recovery, and validation</li>
                                <li><strong>Long-term (ongoing):</strong> Lessons learned, process improvement</li>
                            </ul>
                        </section>

                        <section id="compliance">
                            <h2>10. Regulatory Compliance</h2>
                            
                            <h3>10.1 Data Protection Compliance</h3>
                            <p>We comply with relevant data protection regulations:</p>
                            <ul>
                                <li><strong>Tanzania Data Protection Act 2022:</strong> Full compliance with local data protection requirements</li>
                                <li><strong>GDPR Principles:</strong> Implementation of GDPR best practices</li>
                                <li><strong>Educational Data Standards:</strong> Compliance with educational data protection guidelines</li>
                                <li><strong>International Standards:</strong> Adherence to ISO 27001 and SOC 2 standards</li>
                            </ul>

                            <h3>10.2 AI Governance Framework</h3>
                            <ul>
                                <li><strong>AI Ethics Committee:</strong> Internal committee overseeing AI development and deployment</li>
                                <li><strong>Regular Audits:</strong> Periodic reviews of AI systems and processes</li>
                                <li><strong>Policy Updates:</strong> Regular updates to reflect regulatory changes</li>
                                <li><strong>Training Programs:</strong> Staff training on AI ethics and compliance</li>
                            </ul>

                            <h3>10.3 Industry Standards</h3>
                            <p>We follow recognized AI and security standards:</p>
                            <ul>
                                <li><strong>IEEE Standards:</strong> AI system design and evaluation standards</li>
                                <li><strong>NIST AI Framework:</strong> Risk management framework for AI systems</li>
                                <li><strong>ISO/IEC Standards:</strong> Information security and AI governance standards</li>
                                <li><strong>Educational Technology Standards:</strong> Sector-specific guidelines and best practices</li>
                            </ul>
                        </section>

                        <section id="continuous-improvement">
                            <h2>11. Continuous Improvement</h2>
                            
                            <h3>11.1 Monitoring and Evaluation</h3>
                            <ul>
                                <li><strong>Performance Metrics:</strong> Regular monitoring of AI system performance</li>
                                <li><strong>User Feedback:</strong> Continuous collection and analysis of user feedback</li>
                                <li><strong>Bias Monitoring:</strong> Ongoing assessment of fairness and bias</li>
                                <li><strong>Security Reviews:</strong> Regular security assessments and updates</li>
                                <li><strong>Effectiveness Studies:</strong> Research on AI impact and effectiveness</li>
                            </ul>

                            <h3>11.2 Innovation and Research</h3>
                            <ul>
                                <li><strong>R&D Investment:</strong> Continued investment in AI research and development</li>
                                <li><strong>Academic Partnerships:</strong> Collaboration with educational institutions</li>
                                <li><strong>Industry Collaboration:</strong> Participation in AI and educational technology communities</li>
                                <li><strong>Best Practice Sharing:</strong> Contributing to industry knowledge and standards</li>
                            </ul>

                            <h3>11.3 Future Developments</h3>
                            <p>Our roadmap for AI development includes:</p>
                            <ul>
                                <li><strong>Enhanced Personalization:</strong> More sophisticated individualized learning insights</li>
                                <li><strong>Predictive Analytics:</strong> Advanced forecasting for educational outcomes</li>
                                <li><strong>Natural Language Processing:</strong> Improved communication and content analysis</li>
                                <li><strong>Computer Vision:</strong> Visual analysis for educational content and facilities</li>
                                <li><strong>Federated Learning:</strong> Privacy-preserving collaborative AI development</li>
                            </ul>
                        </section>

                        <section id="contact">
                            <h2>12. Contact Information</h2>
                            <div class="contact-info">
                                <h4><i class="fas fa-robot me-2"></i>AI Ethics & Security Team</h4>
                                <div class="contact-item">
                                    <i class="fas fa-envelope"></i>
                                    <span>ai-ethics@shulesoft.africa</span>
                                </div>
                                <div class="contact-item">
                                    <i class="fas fa-shield-alt"></i>
                                    <span>security@shulesoft.africa</span>
                                </div>
                                <div class="contact-item">
                                    <i class="fas fa-phone"></i>
                                    <span>+255 123 456 789</span>
                                </div>
                                <div class="contact-item">
                                    <i class="fas fa-map-marker-alt"></i>
                                    <span>ShuleSoft Africa Limited<br>
                                    AI & Security Division<br>
                                    123 Education Street<br>
                                    Dar es Salaam, Tanzania</span>
                                </div>
                                <div class="contact-item">
                                    <i class="fas fa-globe"></i>
                                    <span><a href="https://shulesoft.africa/ai-policy" target="_blank">AI Policy Portal</a></span>
                                </div>
                            </div>
                            
                            <p style="margin-top: 2rem;">
                                For questions about our AI systems, security practices, or to report AI-related concerns, please contact our dedicated AI Ethics & Security team. We are committed to addressing your inquiries promptly and transparently.
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
                Advancing education through responsible AI and robust security.
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
