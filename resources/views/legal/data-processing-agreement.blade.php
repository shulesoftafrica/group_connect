<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="ShuleSoft Group Connect Data Processing Agreement - Compliance with Tanzania Data Protection Act 2022">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Data Processing Agreement - ShuleSoft Group Connect</title>
    
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
            --tanzania-blue: #00a0df;
            --tanzania-green: #009639;
            --tanzania-gold: #ffcc02;
            
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
            background: linear-gradient(135deg, var(--tanzania-blue), var(--tanzania-green));
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
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><polygon points="50,10 90,80 10,80" fill="rgba(255,204,2,0.1)"/><polygon points="30,20 70,20 50,60" fill="rgba(255,204,2,0.1)"/></svg>') repeat;
            animation: pulse 15s infinite ease-in-out;
        }

        @keyframes pulse {
            0%, 100% { opacity: 0.3; }
            50% { opacity: 0.6; }
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

        .tanzania-compliance {
            background: linear-gradient(135deg, rgba(0, 160, 223, 0.1), rgba(0, 150, 57, 0.1));
            border: 1px solid rgba(0, 160, 223, 0.3);
            border-radius: 12px;
            padding: 1.5rem;
            margin: 1.5rem 0;
        }

        .tanzania-compliance h4 {
            color: var(--tanzania-blue);
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
        }

        .tanzania-compliance h4 i {
            margin-right: 0.75rem;
            font-size: 1.3rem;
        }

        .legal-definition {
            background: var(--bg-secondary);
            border: 1px solid var(--border-color);
            border-radius: 8px;
            padding: 1rem;
            margin: 1rem 0;
            font-style: italic;
        }

        .legal-definition strong {
            color: var(--primary-color);
            font-style: normal;
        }

        .compliance-table {
            background: var(--bg-secondary);
            border: 1px solid var(--border-color);
            border-radius: 8px;
            overflow: hidden;
            margin: 1.5rem 0;
        }

        .compliance-table table {
            width: 100%;
            margin: 0;
            border-collapse: collapse;
        }

        .compliance-table th {
            background: var(--primary-color);
            color: white;
            padding: 1rem;
            text-align: left;
            font-weight: 600;
        }

        .compliance-table td {
            padding: 1rem;
            border-bottom: 1px solid var(--border-color);
            color: var(--text-primary);
        }

        .compliance-table tr:last-child td {
            border-bottom: none;
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

            .compliance-table {
                overflow-x: auto;
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
                    <h1><i class="fas fa-file-signature me-3"></i>Data Processing Agreement</h1>
                    <p>Compliance with Tanzania Data Protection Act 2022 and international standards</p>
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
                    <li class="breadcrumb-item active" aria-current="page">Data Processing Agreement</li>
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
                            <strong>Last Updated:</strong> {{ date('F j, Y') }} | 
                            <strong>Effective Date:</strong> {{ date('F j, Y') }}
                        </div>

                        <!-- Table of Contents -->
                        <div class="toc">
                            <h4><i class="fas fa-list me-2"></i>Table of Contents</h4>
                            <ul>
                                <li><a href="#definitions">1. Definitions</a></li>
                                <li><a href="#scope-agreement">2. Scope of Agreement</a></li>
                                <li><a href="#tanzania-compliance">3. Tanzania Data Protection Act Compliance</a></li>
                                <li><a href="#data-categories">4. Categories of Personal Data</a></li>
                                <li><a href="#processing-purposes">5. Purposes of Processing</a></li>
                                <li><a href="#legal-basis">6. Legal Basis for Processing</a></li>
                                <li><a href="#data-subject-rights">7. Data Subject Rights</a></li>
                                <li><a href="#security-measures">8. Technical and Organizational Security Measures</a></li>
                                <li><a href="#data-transfers">9. International Data Transfers</a></li>
                                <li><a href="#data-retention">10. Data Retention and Deletion</a></li>
                                <li><a href="#incident-management">11. Data Breach and Incident Management</a></li>
                                <li><a href="#audits-compliance">12. Audits and Compliance Monitoring</a></li>
                                <li><a href="#liability-indemnification">13. Liability and Indemnification</a></li>
                                <li><a href="#termination">14. Termination</a></li>
                                <li><a href="#contact-information">15. Contact Information</a></li>
                            </ul>
                        </div>

                        <!-- Content Sections -->
                        <section id="definitions">
                            <h2>1. Definitions</h2>
                            
                            <div class="legal-definition">
                                <strong>"Controller"</strong> means the educational institution or organization that determines the purposes and means of processing personal data through ShuleSoft Group Connect.
                            </div>

                            <div class="legal-definition">
                                <strong>"Processor"</strong> means ShuleSoft Africa Limited, which processes personal data on behalf of the Controller.
                            </div>

                            <div class="legal-definition">
                                <strong>"Personal Data"</strong> means any information relating to an identified or identifiable natural person, including students, staff, parents, and other individuals within the educational ecosystem.
                            </div>

                            <div class="legal-definition">
                                <strong>"Processing"</strong> means any operation performed on personal data, including collection, recording, organization, structuring, storage, adaptation, retrieval, consultation, use, disclosure, dissemination, alignment, combination, restriction, erasure, or destruction.
                            </div>

                            <div class="legal-definition">
                                <strong>"Data Subject"</strong> means the individual to whom personal data relates, including students, staff, parents, and other stakeholders.
                            </div>

                            <div class="legal-definition">
                                <strong>"Tanzania DPA"</strong> means the Data Protection Act, 2022 of the United Republic of Tanzania and any regulations made thereunder.
                            </div>

                            <div class="legal-definition">
                                <strong>"Special Categories of Personal Data"</strong> means personal data revealing racial or ethnic origin, political opinions, religious or philosophical beliefs, health data, biometric data, or data concerning a person's sex life or sexual orientation.
                            </div>
                        </section>

                        <section id="scope-agreement">
                            <h2>2. Scope of Agreement</h2>
                            
                            <h3>2.1 Agreement Purpose</h3>
                            <p>
                                This Data Processing Agreement ("DPA") governs the processing of personal data by ShuleSoft Africa Limited ("Processor") on behalf of educational institutions ("Controllers") using the ShuleSoft Group Connect platform. This agreement ensures compliance with the Tanzania Data Protection Act 2022 and establishes the rights and obligations of both parties.
                            </p>

                            <h3>2.2 Relationship Between Parties</h3>
                            <ul>
                                <li><strong>Controller Responsibilities:</strong> The educational institution determines the purposes and means of personal data processing</li>
                                <li><strong>Processor Responsibilities:</strong> ShuleSoft processes personal data only on documented instructions from the Controller</li>
                                <li><strong>Joint Obligations:</strong> Both parties cooperate to ensure compliance with applicable data protection laws</li>
                            </ul>

                            <h3>2.3 Subject Matter and Duration</h3>
                            <p>
                                This DPA covers all personal data processing activities within the ShuleSoft Group Connect platform and remains in effect for the duration of the service agreement between the parties, including any extension or renewal periods.
                            </p>
                        </section>

                        <section id="tanzania-compliance">
                            <h2>3. Tanzania Data Protection Act Compliance</h2>
                            
                            <div class="tanzania-compliance">
                                <h4><i class="fas fa-flag"></i>Tanzania Data Protection Act 2022 Compliance Framework</h4>
                                <p>
                                    ShuleSoft Group Connect is designed to fully comply with the Tanzania Data Protection Act 2022, ensuring that educational institutions can confidently use our platform while meeting their legal obligations under Tanzanian law.
                                </p>
                            </div>

                            <h3>3.1 Data Protection Principles</h3>
                            <p>We ensure compliance with the following principles under the Tanzania DPA:</p>
                            
                            <div class="compliance-table">
                                <table>
                                    <thead>
                                        <tr>
                                            <th>Principle</th>
                                            <th>Implementation</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td><strong>Lawfulness, Fairness, and Transparency</strong></td>
                                            <td>Processing is based on clear legal grounds with transparent practices</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Purpose Limitation</strong></td>
                                            <td>Data is collected for specific, explicit, and legitimate educational purposes</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Data Minimization</strong></td>
                                            <td>Only necessary data for educational management is collected and processed</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Accuracy</strong></td>
                                            <td>Mechanisms ensure data accuracy and enable corrections</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Storage Limitation</strong></td>
                                            <td>Data retention periods comply with educational and legal requirements</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Integrity and Confidentiality</strong></td>
                                            <td>Robust security measures protect data integrity and confidentiality</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Accountability</strong></td>
                                            <td>Comprehensive documentation and monitoring of compliance measures</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <h3>3.2 Data Localization Requirements</h3>
                            <p>In accordance with Tanzania DPA requirements:</p>
                            <ul>
                                <li><strong>Primary Storage:</strong> Personal data of Tanzanian residents is primarily stored within Tanzania or approved jurisdictions</li>
                                <li><strong>Cross-Border Transfers:</strong> Any international transfers comply with Tanzania DPA adequacy requirements</li>
                                <li><strong>Data Sovereignty:</strong> Controllers maintain sovereignty over their data regardless of storage location</li>
                                <li><strong>Regulatory Cooperation:</strong> Full cooperation with Tanzania Data Protection Commission</li>
                            </ul>

                            <h3>3.3 Registration and Notification</h3>
                            <ul>
                                <li><strong>Controller Registration:</strong> Educational institutions handle their own registration requirements with the Tanzania Data Protection Commission</li>
                                <li><strong>Processor Notification:</strong> ShuleSoft maintains appropriate registrations as a data processor</li>
                                <li><strong>Processing Records:</strong> Detailed records of processing activities are maintained as required</li>
                                <li><strong>Impact Assessments:</strong> Data Protection Impact Assessments are conducted for high-risk processing</li>
                            </ul>
                        </section>

                        <section id="data-categories">
                            <h2>4. Categories of Personal Data</h2>
                            
                            <h3>4.1 Student Data</h3>
                            <ul>
                                <li><strong>Identity Information:</strong> Names, student identification numbers, photographs</li>
                                <li><strong>Academic Records:</strong> Grades, test scores, academic performance data, attendance records</li>
                                <li><strong>Demographic Information:</strong> Age, gender, nationality, contact information</li>
                                <li><strong>Educational Progress:</strong> Course enrollment, academic progression, graduation status</li>
                                <li><strong>Disciplinary Records:</strong> Behavioral records, disciplinary actions (when applicable)</li>
                            </ul>

                            <h3>4.2 Staff Data</h3>
                            <ul>
                                <li><strong>Employment Information:</strong> Employee ID, job title, department, employment status</li>
                                <li><strong>Professional Data:</strong> Qualifications, certifications, professional development records</li>
                                <li><strong>Performance Data:</strong> Performance evaluations, training records, attendance</li>
                                <li><strong>Contact Information:</strong> Business and emergency contact details</li>
                                <li><strong>Payroll Information:</strong> Salary data, benefit information, tax details</li>
                            </ul>

                            <h3>4.3 Parent/Guardian Data</h3>
                            <ul>
                                <li><strong>Contact Information:</strong> Names, addresses, phone numbers, email addresses</li>
                                <li><strong>Relationship Data:</strong> Relationship to student, custody arrangements</li>
                                <li><strong>Communication Records:</strong> Messages, notifications, meeting records</li>
                                <li><strong>Financial Information:</strong> Fee payment records, billing information</li>
                            </ul>

                            <h3>4.4 Special Categories of Personal Data</h3>
                            <p>When processed with appropriate safeguards and legal basis:</p>
                            <ul>
                                <li><strong>Health Data:</strong> Medical conditions relevant to educational support, dietary requirements</li>
                                <li><strong>Religious Information:</strong> Religious preferences for educational or dietary accommodations</li>
                                <li><strong>Disability Information:</strong> Special educational needs, accessibility requirements</li>
                            </ul>
                        </section>

                        <section id="processing-purposes">
                            <h2>5. Purposes of Processing</h2>
                            
                            <h3>5.1 Educational Management</h3>
                            <ul>
                                <li>Student enrollment, registration, and academic record management</li>
                                <li>Academic performance tracking and progress monitoring</li>
                                <li>Curriculum delivery and educational program administration</li>
                                <li>Assessment, examination, and certification processes</li>
                                <li>Special educational needs support and accommodation</li>
                            </ul>

                            <h3>5.2 Administrative Functions</h3>
                            <ul>
                                <li>School operations management and resource allocation</li>
                                <li>Staff management, payroll, and human resources administration</li>
                                <li>Financial management, fee collection, and budget planning</li>
                                <li>Facility management and security administration</li>
                                <li>Transport and catering service management</li>
                            </ul>

                            <h3>5.3 Communication and Engagement</h3>
                            <ul>
                                <li>Communication with students, parents, and staff</li>
                                <li>Emergency notifications and safety communications</li>
                                <li>Parent engagement and community building</li>
                                <li>Alumni relations and ongoing engagement</li>
                            </ul>

                            <h3>5.4 Analytics and Improvement</h3>
                            <ul>
                                <li>Educational performance analysis and improvement</li>
                                <li>Operational efficiency optimization</li>
                                <li>Predictive analytics for educational outcomes</li>
                                <li>Research and development for educational enhancement</li>
                            </ul>

                            <h3>5.5 Legal and Regulatory Compliance</h3>
                            <ul>
                                <li>Compliance with educational regulations and standards</li>
                                <li>Safeguarding and child protection requirements</li>
                                <li>Financial audit and reporting obligations</li>
                                <li>Legal proceedings and dispute resolution</li>
                            </ul>
                        </section>

                        <section id="legal-basis">
                            <h2>6. Legal Basis for Processing</h2>
                            
                            <h3>6.1 Primary Legal Bases</h3>
                            <div class="compliance-table">
                                <table>
                                    <thead>
                                        <tr>
                                            <th>Processing Purpose</th>
                                            <th>Legal Basis</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>Student Academic Management</td>
                                            <td>Public task (education provision) / Contract (enrollment)</td>
                                        </tr>
                                        <tr>
                                            <td>Staff Employment Management</td>
                                            <td>Contract (employment) / Legal obligation (labor law)</td>
                                        </tr>
                                        <tr>
                                            <td>Parent Communication</td>
                                            <td>Legitimate interest / Consent</td>
                                        </tr>
                                        <tr>
                                            <td>Financial Management</td>
                                            <td>Contract (fee payment) / Legal obligation (accounting)</td>
                                        </tr>
                                        <tr>
                                            <td>Safety and Security</td>
                                            <td>Vital interests / Legal obligation</td>
                                        </tr>
                                        <tr>
                                            <td>Special Categories Data</td>
                                            <td>Explicit consent / Substantial public interest</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <h3>6.2 Consent Management</h3>
                            <p>Where consent is the legal basis for processing:</p>
                            <ul>
                                <li><strong>Informed Consent:</strong> Clear, specific information about processing purposes</li>
                                <li><strong>Freely Given:</strong> No conditioning of services on unnecessary consent</li>
                                <li><strong>Specific:</strong> Separate consent for different processing purposes</li>
                                <li><strong>Withdrawable:</strong> Easy mechanisms to withdraw consent</li>
                                <li><strong>Documented:</strong> Records of consent collection and withdrawal</li>
                            </ul>

                            <h3>6.3 Legitimate Interest Assessments</h3>
                            <p>For processing based on legitimate interests, we conduct assessments considering:</p>
                            <ul>
                                <li>The necessity of processing for the legitimate interest</li>
                                <li>The impact on data subjects' rights and freedoms</li>
                                <li>The balance between legitimate interests and privacy rights</li>
                                <li>Reasonable expectations of data subjects</li>
                                <li>Available safeguards and mitigation measures</li>
                            </ul>
                        </section>

                        <section id="data-subject-rights">
                            <h2>7. Data Subject Rights</h2>
                            
                            <h3>7.1 Rights Under Tanzania DPA</h3>
                            <p>Data subjects have the following rights, which we facilitate:</p>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <div class="principle-card">
                                        <h4><i class="fas fa-eye"></i>Right of Access</h4>
                                        <p>Obtain confirmation of processing and access to personal data</p>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="principle-card">
                                        <h4><i class="fas fa-edit"></i>Right to Rectification</h4>
                                        <p>Correct inaccurate or incomplete personal data</p>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="principle-card">
                                        <h4><i class="fas fa-trash"></i>Right to Erasure</h4>
                                        <p>Request deletion of personal data (subject to legal requirements)</p>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="principle-card">
                                        <h4><i class="fas fa-pause"></i>Right to Restriction</h4>
                                        <p>Limit the processing of personal data</p>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="principle-card">
                                        <h4><i class="fas fa-download"></i>Right to Portability</h4>
                                        <p>Receive personal data in a structured, machine-readable format</p>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="principle-card">
                                        <h4><i class="fas fa-ban"></i>Right to Object</h4>
                                        <p>Object to processing based on legitimate interests</p>
                                    </div>
                                </div>
                            </div>

                            <h3>7.2 Request Handling Process</h3>
                            <ul>
                                <li><strong>Receipt:</strong> Acknowledge receipt within 3 working days</li>
                                <li><strong>Verification:</strong> Verify identity of the data subject</li>
                                <li><strong>Processing:</strong> Process request within 30 days (extendable to 60 days for complex requests)</li>
                                <li><strong>Response:</strong> Provide clear response with any requested data or explanation</li>
                                <li><strong>Appeal:</strong> Information about appeal processes if request is refused</li>
                            </ul>

                            <h3>7.3 Special Considerations for Minors</h3>
                            <ul>
                                <li><strong>Parental Rights:</strong> Parents/guardians may exercise rights on behalf of minors</li>
                                <li><strong>Capacity Assessment:</strong> Consider the child's capacity to understand the implications</li>
                                <li><strong>Best Interests:</strong> Decisions made in the best interests of the child</li>
                                <li><strong>Educational Continuity:</strong> Balance rights with educational requirements</li>
                            </ul>
                        </section>

                        <section id="security-measures">
                            <h2>8. Technical and Organizational Security Measures</h2>
                            
                            <h3>8.1 Technical Security Measures</h3>
                            <ul>
                                <li><strong>Encryption:</strong> AES-256 encryption for data at rest and TLS 1.3 for data in transit</li>
                                <li><strong>Access Controls:</strong> Role-based access control with multi-factor authentication</li>
                                <li><strong>Network Security:</strong> Firewalls, intrusion detection, and network segmentation</li>
                                <li><strong>Database Security:</strong> Encrypted databases with access logging and monitoring</li>
                                <li><strong>Application Security:</strong> Secure coding practices, input validation, and output encoding</li>
                                <li><strong>Backup and Recovery:</strong> Secure, encrypted backups with tested recovery procedures</li>
                            </ul>

                            <h3>8.2 Organizational Security Measures</h3>
                            <ul>
                                <li><strong>Security Policies:</strong> Comprehensive information security policies and procedures</li>
                                <li><strong>Staff Training:</strong> Regular security awareness training for all personnel</li>
                                <li><strong>Access Management:</strong> Principle of least privilege and regular access reviews</li>
                                <li><strong>Incident Response:</strong> Formal incident response procedures and team</li>
                                <li><strong>Vendor Management:</strong> Security assessments of third-party service providers</li>
                                <li><strong>Compliance Monitoring:</strong> Regular compliance audits and security assessments</li>
                            </ul>

                            <h3>8.3 Physical Security Measures</h3>
                            <ul>
                                <li><strong>Data Center Security:</strong> Certified data centers with 24/7 physical security</li>
                                <li><strong>Environmental Controls:</strong> Climate control, fire suppression, and power management</li>
                                <li><strong>Access Controls:</strong> Biometric access controls and visitor management</li>
                                <li><strong>Equipment Security:</strong> Secure disposal of hardware and media</li>
                            </ul>

                            <h3>8.4 Security Certifications</h3>
                            <div class="row">
                                <div class="col-md-4 text-center mb-3">
                                    <div class="security-badge">
                                        <i class="fas fa-certificate"></i>
                                        ISO 27001 Certified
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
                                        <i class="fas fa-check-circle"></i>
                                        Tanzania DPA Compliant
                                    </div>
                                </div>
                            </div>
                        </section>

                        <section id="data-transfers">
                            <h2>9. International Data Transfers</h2>
                            
                            <h3>9.1 Transfer Principles</h3>
                            <p>Any international transfer of personal data is conducted in accordance with:</p>
                            <ul>
                                <li><strong>Tanzania DPA Requirements:</strong> Compliance with cross-border transfer provisions</li>
                                <li><strong>Adequacy Decisions:</strong> Transfers to countries with adequate protection levels</li>
                                <li><strong>Appropriate Safeguards:</strong> Standard contractual clauses or binding corporate rules</li>
                                <li><strong>Specific Situations:</strong> Limited transfers for specific legitimate purposes</li>
                            </ul>

                            <h3>9.2 Safeguards for International Transfers</h3>
                            <ul>
                                <li><strong>Data Processing Agreements:</strong> Comprehensive agreements with international processors</li>
                                <li><strong>Standard Contractual Clauses:</strong> EU Commission approved clauses where applicable</li>
                                <li><strong>Certification Schemes:</strong> Adherence to recognized international certification schemes</li>
                                <li><strong>Codes of Conduct:</strong> Compliance with approved codes of conduct</li>
                            </ul>

                            <h3>9.3 Transfer Impact Assessments</h3>
                            <p>Before any international transfer, we assess:</p>
                            <ul>
                                <li>The legal framework in the destination country</li>
                                <li>Potential access by foreign governments</li>
                                <li>Available legal remedies for data subjects</li>
                                <li>Additional safeguards that may be necessary</li>
                                <li>The necessity and proportionality of the transfer</li>
                            </ul>
                        </section>

                        <section id="data-retention">
                            <h2>10. Data Retention and Deletion</h2>
                            
                            <h3>10.1 Retention Principles</h3>
                            <ul>
                                <li><strong>Purpose Limitation:</strong> Data retained only as long as necessary for the original purpose</li>
                                <li><strong>Legal Requirements:</strong> Compliance with educational and legal retention requirements</li>
                                <li><strong>Regular Review:</strong> Periodic review of retention needs and data classification</li>
                                <li><strong>Secure Deletion:</strong> Secure and verifiable deletion when retention period expires</li>
                            </ul>

                            <h3>10.2 Retention Periods</h3>
                            <div class="compliance-table">
                                <table>
                                    <thead>
                                        <tr>
                                            <th>Data Category</th>
                                            <th>Retention Period</th>
                                            <th>Legal Basis</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>Student Academic Records</td>
                                            <td>7-10 years after graduation</td>
                                            <td>Educational regulations</td>
                                        </tr>
                                        <tr>
                                            <td>Financial Records</td>
                                            <td>7 years after transaction</td>
                                            <td>Accounting standards</td>
                                        </tr>
                                        <tr>
                                            <td>Staff Employment Records</td>
                                            <td>6 years after employment ends</td>
                                            <td>Labor law requirements</td>
                                        </tr>
                                        <tr>
                                            <td>Communication Records</td>
                                            <td>3 years after communication</td>
                                            <td>Operational necessity</td>
                                        </tr>
                                        <tr>
                                            <td>System Log Data</td>
                                            <td>12 months</td>
                                            <td>Security and troubleshooting</td>
                                        </tr>
                                        <tr>
                                            <td>Special Category Data</td>
                                            <td>As required by purpose</td>
                                            <td>Specific legal basis</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <h3>10.3 Deletion Procedures</h3>
                            <ul>
                                <li><strong>Automated Deletion:</strong> Automated systems for routine data deletion</li>
                                <li><strong>Secure Erasure:</strong> Multiple-pass secure erasure for sensitive data</li>
                                <li><strong>Backup Deletion:</strong> Deletion from all backup systems and archives</li>
                                <li><strong>Verification:</strong> Verification and documentation of deletion completion</li>
                                <li><strong>Exception Handling:</strong> Procedures for legal hold and exception cases</li>
                            </ul>
                        </section>

                        <section id="incident-management">
                            <h2>11. Data Breach and Incident Management</h2>
                            
                            <h3>11.1 Incident Response Procedures</h3>
                            <ul>
                                <li><strong>Detection:</strong> 24/7 monitoring and automated breach detection systems</li>
                                <li><strong>Assessment:</strong> Immediate assessment of breach scope and risk level</li>
                                <li><strong>Containment:</strong> Swift action to contain and mitigate the breach</li>
                                <li><strong>Investigation:</strong> Thorough investigation of causes and impact</li>
                                <li><strong>Notification:</strong> Timely notification to relevant parties and authorities</li>
                                <li><strong>Remediation:</strong> Implementation of corrective and preventive measures</li>
                            </ul>

                            <h3>11.2 Notification Timelines (Tanzania DPA)</h3>
                            <ul>
                                <li><strong>Internal Notification:</strong> Immediate internal escalation upon detection</li>
                                <li><strong>Controller Notification:</strong> Within 24 hours of breach confirmation</li>
                                <li><strong>Supervisory Authority:</strong> Within 72 hours as required by Tanzania DPA</li>
                                <li><strong>Data Subject Notification:</strong> Without undue delay if high risk to rights</li>
                                <li><strong>Documentation:</strong> Comprehensive incident documentation and lessons learned</li>
                            </ul>

                            <h3>11.3 Breach Risk Assessment</h3>
                            <p>We assess breach risk considering:</p>
                            <ul>
                                <li>Nature, sensitivity, and volume of data involved</li>
                                <li>Ease of identification of individuals</li>
                                <li>Severity of consequences for data subjects</li>
                                <li>Likelihood of consequences occurring</li>
                                <li>Special characteristics of data subjects (e.g., children)</li>
                            </ul>
                        </section>

                        <section id="audits-compliance">
                            <h2>12. Audits and Compliance Monitoring</h2>
                            
                            <h3>12.1 Audit Rights and Procedures</h3>
                            <ul>
                                <li><strong>Controller Audit Rights:</strong> Controllers may audit our compliance upon reasonable notice</li>
                                <li><strong>Third-Party Audits:</strong> Independent security and compliance audits</li>
                                <li><strong>Regulatory Audits:</strong> Cooperation with Tanzania Data Protection Commission audits</li>
                                <li><strong>Documentation Access:</strong> Provision of relevant compliance documentation</li>
                                <li><strong>Remediation:</strong> Prompt remediation of any identified issues</li>
                            </ul>

                            <h3>12.2 Compliance Monitoring</h3>
                            <ul>
                                <li><strong>Regular Assessments:</strong> Quarterly compliance assessments and reviews</li>
                                <li><strong>Policy Updates:</strong> Regular updates to policies and procedures</li>
                                <li><strong>Training Programs:</strong> Ongoing staff training on data protection</li>
                                <li><strong>Performance Metrics:</strong> Key performance indicators for compliance</li>
                                <li><strong>Continuous Improvement:</strong> Continuous improvement of data protection practices</li>
                            </ul>

                            <h3>12.3 Audit Documentation</h3>
                            <p>We maintain comprehensive documentation including:</p>
                            <ul>
                                <li>Records of processing activities</li>
                                <li>Data protection impact assessments</li>
                                <li>Consent records and withdrawal tracking</li>
                                <li>Data subject request handling logs</li>
                                <li>Security incident reports and responses</li>
                                <li>Staff training records and certifications</li>
                            </ul>
                        </section>

                        <section id="liability-indemnification">
                            <h2>13. Liability and Indemnification</h2>
                            
                            <h3>13.1 Liability Allocation</h3>
                            <ul>
                                <li><strong>Controller Liability:</strong> Controllers liable for determining lawful processing purposes</li>
                                <li><strong>Processor Liability:</strong> ShuleSoft liable for processing in accordance with instructions</li>
                                <li><strong>Joint Liability:</strong> Joint liability for joint processing activities</li>
                                <li><strong>Third-Party Claims:</strong> Procedures for handling third-party data protection claims</li>
                            </ul>

                            <h3>13.2 Indemnification</h3>
                            <ul>
                                <li><strong>Processor Indemnification:</strong> ShuleSoft indemnifies for breaches of this DPA</li>
                                <li><strong>Controller Indemnification:</strong> Controllers indemnify for unlawful processing instructions</li>
                                <li><strong>Mutual Cooperation:</strong> Cooperation in defending against third-party claims</li>
                                <li><strong>Insurance Coverage:</strong> Appropriate insurance coverage for data protection risks</li>
                            </ul>

                            <h3>13.3 Limitation of Liability</h3>
                            <p>
                                Liability limitations are subject to applicable data protection law requirements and may not apply to:
                            </p>
                            <ul>
                                <li>Willful misconduct or gross negligence</li>
                                <li>Violations of data protection laws</li>
                                <li>Breach of confidentiality obligations</li>
                                <li>Failure to implement required security measures</li>
                            </ul>
                        </section>

                        <section id="termination">
                            <h2>14. Termination</h2>
                            
                            <h3>14.1 Termination Events</h3>
                            <p>This DPA may be terminated upon:</p>
                            <ul>
                                <li>Termination of the main service agreement</li>
                                <li>Material breach of data protection obligations</li>
                                <li>Insolvency or cessation of business operations</li>
                                <li>Regulatory order or legal requirement</li>
                                <li>Mutual agreement of the parties</li>
                            </ul>

                            <h3>14.2 Data Return and Deletion</h3>
                            <p>Upon termination, ShuleSoft will:</p>
                            <ul>
                                <li><strong>Data Export:</strong> Provide data export in standard formats within 30 days</li>
                                <li><strong>Secure Deletion:</strong> Securely delete all personal data unless legal retention required</li>
                                <li><strong>Confirmation:</strong> Provide written confirmation of data deletion</li>
                                <li><strong>Backup Deletion:</strong> Delete data from all backup systems and archives</li>
                                <li><strong>Third-Party Notification:</strong> Ensure sub-processors also delete or return data</li>
                            </ul>

                            <h3>14.3 Survival of Provisions</h3>
                            <p>The following provisions survive termination:</p>
                            <ul>
                                <li>Confidentiality obligations</li>
                                <li>Data return and deletion requirements</li>
                                <li>Liability and indemnification clauses</li>
                                <li>Audit rights for completed processing</li>
                                <li>Governing law and dispute resolution</li>
                            </ul>
                        </section>

                        <section id="contact-information">
                            <h2>15. Contact Information</h2>
                            <div class="contact-info">
                                <h4><i class="fas fa-user-shield me-2"></i>Data Protection Officer</h4>
                                <div class="contact-item">
                                    <i class="fas fa-envelope"></i>
                                    <span>dpo@shulesoft.africa</span>
                                </div>
                                <div class="contact-item">
                                    <i class="fas fa-phone"></i>
                                    <span>+255 123 456 789</span>
                                </div>
                                <div class="contact-item">
                                    <i class="fas fa-fax"></i>
                                    <span>+255 123 456 790</span>
                                </div>
                                <div class="contact-item">
                                    <i class="fas fa-map-marker-alt"></i>
                                    <span>ShuleSoft Africa Limited<br>
                                    Data Protection Office<br>
                                    123 Education Street<br>
                                    Dar es Salaam, Tanzania</span>
                                </div>
                                <div class="contact-item">
                                    <i class="fas fa-globe"></i>
                                    <span><a href="https://shulesoft.africa/data-protection" target="_blank">Data Protection Portal</a></span>
                                </div>
                            </div>
                            
                            <div class="tanzania-compliance" style="margin-top: 2rem;">
                                <h4><i class="fas fa-landmark"></i>Tanzania Data Protection Commission</h4>
                                <p>
                                    For complaints or inquiries about data protection matters, you may also contact:
                                </p>
                                <div class="contact-item">
                                    <i class="fas fa-envelope"></i>
                                    <span>info@dataprotection.go.tz</span>
                                </div>
                                <div class="contact-item">
                                    <i class="fas fa-globe"></i>
                                    <span><a href="https://www.dataprotection.go.tz" target="_blank">www.dataprotection.go.tz</a></span>
                                </div>
                            </div>
                            
                            <p style="margin-top: 2rem;">
                                For any questions about this Data Processing Agreement, data protection compliance, or to exercise your data subject rights, please contact our Data Protection Officer. We are committed to addressing your inquiries promptly and in accordance with applicable data protection laws.
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
                Fully compliant with Tanzania Data Protection Act 2022.
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
