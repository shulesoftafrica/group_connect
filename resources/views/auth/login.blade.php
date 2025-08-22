<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="ShuleSoft Group Connect - AI-powered school management platform for multi-school owners">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>ShuleSoft Group Connect - Manage All Your Schools in One Place</title>
    
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
            background-color: var(--bg-primary);
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
        
        /* ===== HEADER STYLES ===== */
        .navbar {
            background: var(--bg-primary);
            box-shadow: var(--shadow-md);
            padding: 1rem 0;
            border-bottom: 1px solid var(--border-color);
        }
        
        .navbar-brand {
            font-weight: 700;
            font-size: 1.5rem;
            color: var(--primary-color) !important;
        }
        
        .navbar-nav .nav-link {
            color: var(--text-primary) !important;
            font-weight: 500;
            margin: 0 0.5rem;
            transition: color 0.3s ease;
        }
        
        .navbar-nav .nav-link:hover {
            color: var(--primary-color) !important;
        }
        
        /* ===== BUTTONS ===== */
        .btn-primary {
            background-color: var(--btn-primary);
            border-color: var(--btn-primary);
            border-radius: 8px;
            padding: 0.5rem 1.5rem;
            font-weight: 600;
            transition: all 0.3s ease;
            color: white;
        }
        
        .btn-primary:hover {
            background-color: var(--btn-primary-hover);
            border-color: var(--btn-primary-hover);
            transform: translateY(-2px);
            color: white;
        }
        
        .btn-outline-primary {
            color: var(--primary-color);
            border-color: var(--primary-color);
            border-radius: 8px;
            padding: 0.5rem 1.5rem;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .btn-outline-primary:hover {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            color: white;
        }
        
        .btn-outline-light {
            color: var(--text-primary);
            border-color: var(--border-color);
            border-radius: 8px;
            padding: 0.5rem 1.5rem;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .btn-outline-light:hover {
            background-color: var(--bg-secondary);
            border-color: var(--border-color);
            color: var(--text-primary);
        }
        
        .btn-light {
            background-color: var(--bg-secondary);
            border-color: var(--border-color);
            color: var(--text-primary);
            border-radius: 8px;
            padding: 0.5rem 1.5rem;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .btn-light:hover {
            background-color: var(--bg-tertiary);
            border-color: var(--border-color);
            color: var(--text-primary);
        }
        
        .btn-secondary {
            background-color: var(--btn-secondary);
            border-color: var(--btn-secondary);
            color: white;
        }
        
        .btn-secondary:hover {
            background-color: var(--btn-secondary-hover);
            border-color: var(--btn-secondary-hover);
            color: white;
        }

        /* ===== HERO SECTION ===== */
        .hero {
            background: var(--hero-gradient);
            padding: 100px 0;
            min-height: 80vh;
            display: flex;
            align-items: center;
        }
        
        .hero h1 {
            font-size: 3.5rem;
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 1.5rem;
            line-height: 1.2;
        }
        
        .hero .lead {
            font-size: 1.3rem;
            color: var(--dark-gray);
            margin-bottom: 2rem;
        }
        
        .hero-image {
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 800 600"><rect width="800" height="600" fill="%23f8f9fa"/><rect x="50" y="50" width="700" height="500" fill="white" stroke="%23dee2e6" stroke-width="2" rx="10"/><rect x="70" y="70" width="660" height="60" fill="%231eba9b" rx="5"/><circle cx="120" cy="100" r="15" fill="white"/><rect x="160" y="90" width="100" height="20" fill="white" rx="10"/><rect x="90" y="160" width="200" height="80" fill="%23e9ecef" rx="5"/><rect x="320" y="160" width="200" height="80" fill="%23e9ecef" rx="5"/><rect x="550" y="160" width="200" height="80" fill="%23e9ecef" rx="5"/><rect x="90" y="270" width="620" height="200" fill="%23f8f9fa" rx="5"/></svg>') no-repeat center;
            background-size: contain;
            height: 400px;
            opacity: 0.8;
        }
        
        /* Features Section */
        .features {
            padding: 80px 0;
            background: var(--bg-primary);
        }
        
        .feature-card {
            text-align: center;
            padding: 2rem;
            border-radius: 12px;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            height: 100%;
            background: var(--bg-secondary);
            border: 1px solid var(--border-color);
        }
        
        .feature-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-lg);
        }
        
        .feature-icon {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            color: white;
            font-size: 2rem;
        }
        
        .feature-card h4 {
            font-weight: 600;
            margin-bottom: 1rem;
            color: var(--text-primary);
        }
        
        .feature-card p {
            color: var(--text-secondary);
        }
        
        /* How It Works Section */
        .how-it-works {
            padding: 80px 0;
            background: var(--bg-tertiary);
        }
        
        .step-card {
            text-align: center;
            padding: 2rem;
        }
        
        .step-card h4 {
            color: var(--text-primary);
        }
        
        .step-card p {
            color: var(--text-secondary);
        }
        
        .step-number {
            width: 60px;
            height: 60px;
            background: var(--primary-color);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            color: white;
            font-size: 1.5rem;
            font-weight: 700;
        }
        
        /* CTA Section */
        .cta-section {
            padding: 80px 0;
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            color: white;
            text-align: center;
        }
        
        .cta-section h2 {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
        }
        
        .btn-light {
            border-radius: 8px;
            padding: 0.75rem 2rem;
            font-weight: 600;
            margin: 0 0.5rem;
        }
        
        /* Testimonials */
        .testimonials {
            padding: 80px 0;
            background: var(--bg-primary);
        }
        
        .testimonials h2 {
            color: var(--text-primary);
        }
        
        .testimonial-card {
            background: var(--bg-secondary);
            padding: 2rem;
            border-radius: 12px;
            margin-bottom: 2rem;
            border: 1px solid var(--border-color);
        }
        
        .testimonial-card blockquote {
            color: var(--text-secondary);
        }
        
        .testimonial-card cite {
            color: var(--text-primary);
        }
        
        .testimonial-avatar {
            width: 60px;
            height: 60px;
            background: var(--primary-color);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 700;
            font-size: 1.2rem;
        }
        
        /* Trust Section */
        .trust-section {
            padding: 80px 0;
            background: var(--bg-secondary);
            text-align: center;
        }
        
        .trust-section h2 {
            color: var(--text-primary);
            font-size: 2.5rem;
            font-weight: 600;
            margin-bottom: 1rem;
        }
        
        .trust-section .lead {
            color: var(--text-secondary);
            max-width: 800px;
            margin: 0 auto 3rem;
            font-size: 1.2rem;
            line-height: 1.6;
        }
        
        .trust-logo-container {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-top: 2rem;
        }
        
        .trust-logo {
            max-height: 120px;
            max-width: 400px;
            width: auto;
            height: auto;
            filter: grayscale(0);
            transition: all 0.3s ease;
            opacity: 0.9;
        }
        
        .trust-logo:hover {
            opacity: 1;
            transform: scale(1.05);
        }
        
        [data-theme="dark"] .trust-logo {
            filter: brightness(0.9) grayscale(0.2);
        }
        
        [data-theme="dark"] .trust-logo:hover {
            filter: brightness(1) grayscale(0);
        }
        
        /* Responsive trust section */
        @media (max-width: 768px) {
            .trust-section h2 {
                font-size: 2rem;
            }
            
            .trust-section .lead {
                font-size: 1.1rem;
                margin-bottom: 2rem;
            }
            
            .trust-logo {
                max-height: 80px;
                max-width: 300px;
            }
        }

        /* FAQ Section */
        .faq {
            padding: 80px 0;
            background: var(--bg-tertiary);
        }
        
        .faq h2 {
            color: var(--text-primary);
        }
        
        .faq-item {
            background: var(--bg-primary);
            border-radius: 12px;
            margin-bottom: 1.5rem;
            border: 1px solid var(--border-color);
            overflow: hidden;
            transition: all 0.3s ease;
        }
        
        .faq-item:hover {
            box-shadow: var(--shadow-lg);
            transform: translateY(-2px);
        }
        
        .faq-question {
            background: var(--bg-secondary);
            border: none;
            padding: 1.5rem;
            width: 100%;
            text-align: left;
            font-weight: 600;
            font-size: 1.1rem;
            color: var(--text-primary);
            display: flex;
            justify-content: space-between;
            align-items: center;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .faq-question:hover {
            background: var(--bg-tertiary);
        }
        
        .faq-question:focus {
            outline: none;
            box-shadow: inset 0 0 0 2px var(--primary-color);
        }
        
        .faq-question .icon {
            color: var(--primary-color);
            transition: transform 0.3s ease;
            font-size: 1.2rem;
        }
        
        .faq-question[aria-expanded="true"] .icon {
            transform: rotate(180deg);
        }
        
        .faq-answer {
            padding: 0 1.5rem 1.5rem;
            color: var(--text-secondary);
            line-height: 1.6;
            background: var(--bg-primary);
        }
        
        .faq-answer p {
            margin-bottom: 0.75rem;
        }
        
        .faq-answer ul {
            padding-left: 1.25rem;
        }
        
        .faq-answer li {
            margin-bottom: 0.5rem;
        }
        
        .accordion-collapse {
            border-top: 1px solid var(--border-color);
        }
        
        /* Footer */
        .footer {
            background: var(--bg-secondary);
            color: var(--text-secondary);
            padding: 40px 0 20px;
            border-top: 1px solid var(--border-color);
        }
        
        .footer h5 {
            color: var(--text-primary);
        }
        
        .footer a {
            color: var(--text-secondary);
            text-decoration: none;
            transition: color 0.3s ease;
        }
        
        .footer a:hover {
            color: var(--primary-color);
        }
        
        /* Login Modal */
        .modal-content {
            border-radius: 12px;
            border: none;
            background: var(--bg-primary);
            color: var(--text-primary);
        }
        
        .modal-header {
            border-bottom: 1px solid var(--border-color);
            padding: 1.5rem;
        }
        
        .modal-title {
            color: var(--text-primary);
        }
        
        .modal-body {
            background: var(--bg-primary);
        }
        
        .form-control {
            border-radius: 8px;
            padding: 0.75rem;
            border: 1px solid var(--border-color);
            transition: border-color 0.3s ease;
            background: var(--bg-secondary);
            color: var(--text-primary);
        }
        
        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(30, 186, 155, 0.25);
            background: var(--bg-secondary);
            color: var(--text-primary);
        }
        
        .form-control::placeholder {
            color: var(--text-muted);
        }
        
        .form-label {
            color: var(--text-primary);
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
        
        .list-group-item {
            background: var(--bg-secondary);
            border: 1px solid var(--border-color);
            color: var(--text-primary);
        }
        
        .badge {
            background: var(--primary-color);
            color: white;
        }
        
        .alert {
            background: var(--bg-tertiary);
            border: 1px solid var(--border-color);
            color: var(--text-primary);
        }
        
        /* Close button for dark mode */
        .btn-close {
            filter: var(--close-btn-filter);
        }
        
        /* Animations */
        .fade-in {
            opacity: 0;
            transform: translateY(30px);
            transition: all 0.6s ease;
        }
        
        .fade-in.visible {
            opacity: 1;
            transform: translateY(0);
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .hero h1 {
                font-size: 2.5rem;
            }
            
            .hero .lead {
                font-size: 1.1rem;
            }
            
            .navbar-nav {
                text-align: center;
                margin-top: 1rem;
            }
            
            .hero-image {
                height: 250px;
                margin-top: 2rem;
            }
        }
        
        /* ===== ONBOARDING WIZARD STYLES ===== */
        .wizard-modal .modal-dialog {
            max-width: 900px;
        }
        
        .wizard-modal .modal-content {
            background: var(--bg-primary);
            border: none;
            border-radius: 16px;
            box-shadow: var(--shadow-xl);
        }
        
        .wizard-header {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            color: white;
            padding: 2rem;
            border-radius: 16px 16px 0 0;
            text-align: center;
        }
        
        .wizard-header h4 {
            margin: 0;
            font-weight: 600;
        }
        
        .wizard-header p {
            margin: 0.5rem 0 0;
            opacity: 0.9;
        }
        
        .wizard-modal .step-indicator {
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 2rem 0 1.5rem;
            padding: 0 2rem;
        }
        
        .wizard-modal .step {
            display: flex;
            align-items: center;
            position: relative;
        }
        
        .wizard-modal .step-number {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: var(--bg-tertiary);
            color: var(--text-muted);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            border: 2px solid var(--border-color);
            transition: all 0.3s ease;
        }
        
        .wizard-modal .step.active .step-number {
            background: var(--primary-color);
            color: white;
            border-color: var(--primary-color);
        }
        
        .wizard-modal .step.completed .step-number {
            background: var(--primary-color);
            color: white;
            border-color: var(--primary-color);
        }
        
        .wizard-modal .step.completed .step-number::before {
            content: '✓';
        }
        
        .wizard-modal .step-label {
            margin-left: 0.75rem;
            font-weight: 500;
            color: var(--text-secondary);
            transition: color 0.3s ease;
        }
        
        .wizard-modal .step.active .step-label {
            color: var(--primary-color);
            font-weight: 600;
        }
        
        .wizard-modal .step-connector {
            width: 60px;
            height: 2px;
            background: var(--border-color);
            margin: 0 1rem;
            transition: background 0.3s ease;
        }
        
        .wizard-modal .step.completed + .step-connector {
            background: var(--primary-color);
        }
        
        .wizard-body {
            padding: 2rem;
            min-height: 400px;
        }
        
        .wizard-modal .step-content {
            display: none;
        }
        
        .wizard-modal .step-content.active {
            display: block !important;
        }
        
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .wizard-modal .form-section {
            background: var(--bg-secondary);
            border: 1px solid var(--border-color);
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
        }
        
        .wizard-modal .form-section h6 {
            color: var(--primary-color);
            font-weight: 600;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
        }
        
        .wizard-modal .form-section h6 i {
            margin-right: 0.5rem;
        }
        
        .wizard-modal .school-entry {
            background: var(--bg-primary);
            border: 1px solid var(--border-light);
            border-radius: 8px;
            padding: 1.25rem;
            margin-bottom: 1rem;
            position: relative;
        }
        
        .wizard-modal .school-entry-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
        }
        
        .wizard-modal .school-entry-title {
            font-weight: 600;
            color: var(--text-primary);
        }
        
        .wizard-modal .remove-school {
            background: none;
            border: none;
            color: #dc3545;
            cursor: pointer;
            padding: 0.25rem;
            border-radius: 4px;
            transition: background 0.3s ease;
        }
        
        .wizard-modal .remove-school:hover {
            background: rgba(220, 53, 69, 0.1);
        }
        
        .wizard-modal .add-school-btn {
            border: 2px dashed var(--border-color);
            background: transparent;
            color: var(--primary-color);
            padding: 1rem;
            border-radius: 8px;
            width: 100%;
            transition: all 0.3s ease;
        }
        
        .wizard-modal .add-school-btn:hover {
            border-color: var(--primary-color);
            background: rgba(30, 186, 155, 0.05);
        }
        
        .wizard-modal .wizard-footer {
            padding: 1.5rem 2rem;
            border-top: 1px solid var(--border-color);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .wizard-modal .wizard-navigation {
            display: flex;
            gap: 1rem;
        }
        
        .wizard-modal .status-indicator {
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-size: 0.875rem;
            font-weight: 500;
        }
        
        .wizard-modal .status-premium {
            background: rgba(40, 167, 69, 0.1);
            color: #28a745;
            border: 1px solid rgba(40, 167, 69, 0.2);
        }
        
        .wizard-modal .status-freemium {
            background: rgba(255, 193, 7, 0.1);
            color: #ffc107;
            border: 1px solid rgba(255, 193, 7, 0.2);
        }
        
        .wizard-modal .status-none {
            background: rgba(108, 117, 125, 0.1);
            color: #6c757d;
            border: 1px solid rgba(108, 117, 125, 0.2);
        }
        
        .wizard-modal .upload-zone {
            border: 2px dashed var(--border-color);
            border-radius: 12px;
            padding: 2rem;
            text-align: center;
            transition: all 0.3s ease;
            cursor: pointer;
        }
        
        .wizard-modal .upload-zone:hover {
            border-color: var(--primary-color);
            background: rgba(30, 186, 155, 0.05);
        }
        
        .wizard-modal .upload-zone.dragover {
            border-color: var(--primary-color);
            background: rgba(30, 186, 155, 0.1);
        }
        
        .wizard-modal .upload-zone-small {
            border: 2px dashed var(--border-color);
            border-radius: 8px;
            padding: 1rem;
            text-align: center;
            transition: all 0.3s ease;
            cursor: pointer;
            font-size: 0.9rem;
        }
        
        .wizard-modal .upload-zone-small:hover {
            border-color: var(--primary-color);
            background: rgba(30, 186, 155, 0.05);
        }
        
        .wizard-modal .alert-sm {
            padding: 0.5rem 0.75rem;
            font-size: 0.875rem;
        }
        
        .wizard-modal .invoice-summary {
            background: var(--bg-secondary);
            border: 1px solid var(--border-color);
            border-radius: 12px;
            padding: 1.5rem;
        }
        
        .wizard-modal .invoice-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.75rem 0;
            border-bottom: 1px solid var(--border-light);
        }
        
        .wizard-modal .invoice-row:last-child {
            border-bottom: none;
            font-weight: 600;
            font-size: 1.125rem;
            color: var(--primary-color);
        }
        
        .wizard-modal .progress-indicator {
            margin-bottom: 1.5rem;
        }
        
        .wizard-modal .progress {
            height: 8px;
            background: var(--bg-tertiary);
            border-radius: 4px;
            overflow: hidden;
        }
        
        .wizard-modal .progress-bar {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            transition: width 0.3s ease;
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg fixed-top">
        <div class="container">
            <a class="navbar-brand" href="#home">
                <i class="fas fa-graduation-cap me-2"></i>ShuleSoft Group Connect
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav mx-auto">
                    <li class="nav-item"><a class="nav-link" href="#features">Features</a></li>
                    <li class="nav-item"><a class="nav-link" href="#how-it-works">How It Works</a></li>
                    <li class="nav-item"><a class="nav-link" href="#faq">FAQ</a></li>
                    <li class="nav-item"><a class="nav-link" href="#testimonials">Reviews</a></li>
                    <li class="nav-item"><a class="nav-link" href="#contact">Contact</a></li>
                </ul>
                
                <div class="d-flex align-items-center">
                    <button class="theme-toggle me-3" id="themeToggle" title="Toggle theme">
                        <i class="fas fa-moon icon dark-icon"></i>
                        <i class="fas fa-sun icon light-icon"></i>
                    </button>
                    <button class="btn btn-outline-primary me-2" data-bs-toggle="modal" data-bs-target="#loginModal">
                        Login
                    </button>
                    <a class="btn btn-primary" href="{{ route('onboarding.start') }}">Start Free Trial</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section id="home" class="hero">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <h1 class="fade-in">Manage All Your Schools in One Place</h1>
                    <p class="lead fade-in">AI-powered insights to help school owners make faster, smarter decisions across multiple institutions.</p>
                    
                    <div class="fade-in">
                        <a class="btn btn-primary btn-lg me-3"  href="{{ route('onboarding.start') }}">
                            <i class="fas fa-rocket me-2"></i>Start Free Trial
    </a>
                        <a href="#demo" class="btn btn-outline-primary btn-lg" data-bs-toggle="modal" data-bs-target="#demoRequestModal">
                            <i class="fas fa-play me-2"></i>Request Demo
                        </a>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="hero-image fade-in"></div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="features">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="fade-in">Powerful Features for Multi-School Management</h2>
                <p class="lead fade-in">Everything you need to manage multiple schools efficiently</p>
            </div>
            
            <div class="row">
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="feature-card fade-in">
                        <div class="feature-icon">
                            <i class="fas fa-network-wired"></i>
                        </div>
                        <h4>Group Management</h4>
                        <p>Link all your schools in one centralized account. Monitor performance across institutions from a single dashboard.</p>
                    </div>
                </div>
                
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="feature-card fade-in">
                        <div class="feature-icon">
                            <i class="fas fa-brain"></i>
                        </div>
                        <h4>AI Insights</h4>
                        <p>Ask questions in plain English and get instant reports. Our AI analyzes your data and provides actionable insights.</p>
                    </div>
                </div>
                
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="feature-card fade-in">
                        <div class="feature-icon">
                            <i class="fas fa-user-plus"></i>
                        </div>
                        <h4>Self-Service Onboarding</h4>
                        <p>School owners can add their institutions themselves. Quick setup with guided onboarding process.</p>
                    </div>
                </div>
                
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="feature-card fade-in">
                        <div class="feature-icon">
                            <i class="fas fa-shield-alt"></i>
                        </div>
                        <h4>Secure & Scalable</h4>
                        <p>Enterprise-grade security with role-based access control. Scale from 2 schools to 200+ institutions.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- How It Works Section -->
    <section id="how-it-works" class="how-it-works">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="fade-in">How It Works</h2>
                <p class="lead fade-in">Get started in just 3 simple steps</p>
            </div>
            
            <div class="row">
                <div class="col-lg-4 mb-4">
                    <div class="step-card fade-in">
                        <div class="step-number">1</div>
                        <h4>Sign Up & Add Your Schools</h4>
                        <p>Create your account and add all your schools to the platform. Import existing data or start fresh.</p>
                    </div>
                </div>
                
                <div class="col-lg-4 mb-4">
                    <div class="step-card fade-in">
                        <div class="step-number">2</div>
                        <h4>Explore Reports with AI</h4>
                        <p>Ask our AI assistant questions about your schools. Get instant insights on enrollment, revenue, and performance.</p>
                    </div>
                </div>
                
                <div class="col-lg-4 mb-4">
                    <div class="step-card fade-in">
                        <div class="step-number">3</div>
                        <h4>Manage Everything in One Dashboard</h4>
                        <p>Monitor all schools from one central location. Make data-driven decisions across your entire education network.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section id="cta" class="cta-section">
        <div class="container">
            <div class="text-center">
                <h2 class="fade-in">Try for Free, No Credit Card Required</h2>
                <p class="lead fade-in mb-4">Join hundreds of school owners who trust ShuleSoft Group Connect</p>
                
                <div class="fade-in">
                    <a class="btn btn-light btn-lg me-3" href="{{ route('onboarding.start') }}">
                        <i class="fas fa-calendar-check me-2"></i>Start 14-Day Free Trial
    </a>
                    <a href="#demo" class="btn btn-outline-light btn-lg" data-bs-toggle="modal" data-bs-target="#demoRequestModal">
                        <i class="fas fa-eye me-2"></i>See Live Demo
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials Section -->
    <section id="testimonials" class="testimonials">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="fade-in">Trusted by School Owners Worldwide</h2>
                <p class="lead fade-in">See what our customers say about ShuleSoft Group Connect</p>
            </div>
            
            <div class="row">
                <div class="col-lg-4 mb-4">
                    <div class="testimonial-card fade-in">
                        <div class="d-flex align-items-center mb-3">
                            <div class="testimonial-avatar me-3">JM</div>
                            <div>
                                <h6 class="mb-0">John Mwangi</h6>
                                <small class="text-muted">CEO, Mwangi Schools Group</small>
                            </div>
                        </div>
                        <p>"ShuleSoft Group Connect transformed how we manage our 8 schools. The AI insights help us identify trends we never saw before."</p>
                        <div class="text-warning">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-4 mb-4">
                    <div class="testimonial-card fade-in">
                        <div class="d-flex align-items-center mb-3">
                            <div class="testimonial-avatar me-3">AK</div>
                            <div>
                                <h6 class="mb-0">Anne Kariuki</h6>
                                <small class="text-muted">Director, Kariuki Education Network</small>
                            </div>
                        </div>
                        <p>"The consolidated reporting saves us hours every week. We can now focus on strategic decisions instead of data compilation."</p>
                        <div class="text-warning">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-4 mb-4">
                    <div class="testimonial-card fade-in">
                        <div class="d-flex align-items-center mb-3">
                            <div class="testimonial-avatar me-3">PM</div>
                            <div>
                                <h6 class="mb-0">Peter Mutua</h6>
                                <small class="text-muted">Owner, Mutua Academy Chain</small>
                            </div>
                        </div>
                        <p>"Best investment for our school network. The AI assistant is like having a data analyst available 24/7."</p>
                        <div class="text-warning">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Trust Section -->
    <section id="trust" class="trust-section">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <h2 class="fade-in">Backed by Global Trust</h2>
                    <p class="lead fade-in">
                        ShuleSoft is proudly supported by the Mastercard Foundation, a globally recognized institution dedicated to advancing education and innovation across Africa.
                    </p>
                    
                    <div class="trust-logo-container fade-in">
                        <img src="/media/mastercard-foundation.png" 
                             alt="Mastercard Foundation Logo" 
                             class="trust-logo"
                             loading="lazy">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- FAQ Section -->
    <section id="faq" class="faq">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="fade-in">Frequently Asked Questions</h2>
                <p class="lead fade-in">Common questions from school owners managing multiple institutions</p>
            </div>
            
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="accordion" id="faqAccordion">
                        
                        <!-- FAQ 1 -->
                        <div class="faq-item fade-in">
                            <button class="faq-question" type="button" data-bs-toggle="collapse" data-bs-target="#faq1" aria-expanded="false" aria-controls="faq1">
                                <span>How does ShuleSoft Group Connect integrate with my existing school management systems?</span>
                                <i class="fas fa-chevron-down icon"></i>
                            </button>
                            <div id="faq1" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="faq-answer">
                                    <p>ShuleSoft Group Connect works by combining data from all schools that use ShuleSoft into a centralized platform. This ensures real-time data access without the need for external APIs or integrations. Key features include:</p>
                                    <ul>
                                        <li><strong>Centralized Data Aggregation:</strong> All school data is automatically synchronized and consolidated within the ShuleSoft ecosystem.</li>
                                        <li><strong>Real-time Updates:</strong> Instant access to live data, including academics, finances, attendance, and operations, across all schools in the group.</li>
                                        <li><strong>Seamless Integration:</strong> Since all schools operate within the ShuleSoft platform, there is no need for third-party APIs or manual data imports.</li>
                                        <li><strong>Cross-School Insights:</strong> Effortlessly monitor and compare performance metrics, trends, and exceptions across your entire school network.</li>
                                    </ul>
                                    <p>This approach eliminates the complexities of external integrations, ensuring a streamlined and efficient management experience for school owners.</p>
                                </div>
                            </div>
                        </div>

                        <!-- FAQ 2 -->
                        <div class="faq-item fade-in">
                            <button class="faq-question" type="button" data-bs-toggle="collapse" data-bs-target="#faq2" aria-expanded="false" aria-controls="faq2">
                                <span>What kind of insights and analytics can I expect for managing multiple schools?</span>
                                <i class="fas fa-chevron-down icon"></i>
                            </button>
                            <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="faq-answer">
                                    <p>Our AI-powered analytics provide comprehensive insights across your entire school network:</p>
                                    <ul>
                                        <li><strong>Financial Performance:</strong> Revenue trends, cost analysis, and profitability comparison across schools</li>
                                        <li><strong>Student Performance:</strong> Academic achievement patterns, attendance trends, and graduation rates</li>
                                        <li><strong>Operational Efficiency:</strong> Staff utilization, resource allocation, and facility management metrics</li>
                                        <li><strong>Enrollment Insights:</strong> Student acquisition trends, retention rates, and demographic analysis</li>
                                        <li><strong>Predictive Analytics:</strong> Forecast enrollment, identify at-risk students, and predict resource needs</li>
                                        <li><strong>Benchmarking:</strong> Compare performance across your schools and against industry standards</li>
                                    </ul>
                                    <p>All insights are presented in intuitive dashboards with customizable reports and automated alerts for key metrics.</p>
                                </div>
                            </div>
                        </div>

                        <!-- FAQ 3 -->
                        <div class="faq-item fade-in">
                            <button class="faq-question" type="button" data-bs-toggle="collapse" data-bs-target="#faq3" aria-expanded="false" aria-controls="faq3">
                                <span>How does the platform ensure data security and privacy across multiple schools?</span>
                                <i class="fas fa-chevron-down icon"></i>
                            </button>
                            <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="faq-answer">
                                    <p>Security and privacy are our top priorities, especially when managing sensitive student and institutional data:</p>
                                    <ul>
                                        <li><strong>Enterprise-grade Encryption:</strong> AES-256 encryption for data at rest and TLS 1.3 for data in transit</li>
                                        <li><strong>Role-based Access Control:</strong> Granular permissions ensure staff only access relevant school data</li>
                                        <li><strong>Data Isolation:</strong> Each school's data is logically separated with strict access boundaries</li>
                                        <li><strong>Compliance Standards:</strong> FERPA, COPPA, and GDPR compliant with regular third-party audits</li>
                                        <li><strong>Audit Trails:</strong> Complete logging of all data access and modifications</li>
                                        <li><strong>Backup & Recovery:</strong> Automated daily backups with geographic redundancy</li>
                                    </ul>
                                    <p>We also provide detailed security reports and can accommodate additional compliance requirements specific to your region.</p>
                                </div>
                            </div>
                        </div>

                        <!-- FAQ 4 -->
                        <div class="faq-item fade-in">
                            <button class="faq-question" type="button" data-bs-toggle="collapse" data-bs-target="#faq4" aria-expanded="false" aria-controls="faq4">
                                <span>What level of training and support do you provide for my staff across all schools?</span>
                                <i class="fas fa-chevron-down icon"></i>
                            </button>
                            <div id="faq4" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="faq-answer">
                                    <p>We provide comprehensive training and ongoing support to ensure successful adoption across your entire school network:</p>
                                    <ul>
                                        <li><strong>Implementation Training:</strong> On-site or virtual training sessions for administrators and key staff</li>
                                        <li><strong>Role-specific Modules:</strong> Customized training for principals, teachers, finance staff, and IT personnel</li>
                                        <li><strong>Train-the-Trainer Programs:</strong> Empower your internal champions to train other staff members</li>
                                        <li><strong>24/7 Support Center:</strong> Multi-channel support via phone, email, chat, and ticketing system</li>
                                        <li><strong>Learning Resources:</strong> Video tutorials, documentation, webinars, and user community forums</li>
                                        <li><strong>Regular Check-ins:</strong> Quarterly business reviews and optimization sessions</li>
                                    </ul>
                                    <p>Our customer success team monitors usage patterns and proactively offers additional training where needed.</p>
                                </div>
                            </div>
                        </div>

                        <!-- FAQ 5 -->
                        <div class="faq-item fade-in">
                            <button class="faq-question" type="button" data-bs-toggle="collapse" data-bs-target="#faq5" aria-expanded="false" aria-controls="faq5">
                                <span>How scalable is the platform as I add more schools to my network?</span>
                                <i class="fas fa-chevron-down icon"></i>
                            </button>
                            <div id="faq5" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="faq-answer">
                                    <p>ShuleSoft Group Connect is built on cloud-native architecture designed to scale seamlessly with your growing school network:</p>
                                    <ul>
                                        <li><strong>Elastic Infrastructure:</strong> Automatically scales computing resources based on demand and usage</li>
                                        <li><strong>Multi-tenant Architecture:</strong> Efficiently manages resources across hundreds of schools</li>
                                        <li><strong>Rapid Onboarding:</strong> New schools can be added within seconds, not days or weeks</li>
                                        <li><strong>Performance Optimization:</strong> Advanced caching and CDN ensure fast performance regardless of school count</li>
                                        <li><strong>Cost Efficiency:</strong> Volume-based pricing that becomes more economical as you add schools</li>
                                        <li><strong>Global Reach:</strong> Multi-region deployment supports schools across different geographic locations</li>
                                    </ul>
                                    <p>Our largest clients successfully manage 200+ schools on the platform with consistent performance and reliability.</p>
                                </div>
                            </div>
                        </div>
                        <!-- FAQ 6 -->
                        <div class="faq-item fade-in">
                            <button class="faq-question" type="button" data-bs-toggle="collapse" data-bs-target="#faq6" aria-expanded="false" aria-controls="faq6">
                                <span>How does ShuleSoft Group Connect help me improve oversight and management across all my schools?</span>
                                <i class="fas fa-chevron-down icon"></i>
                            </button>
                            <div id="faq6" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="faq-answer">
                                    <p>ShuleSoft Group Connect provides a centralized platform that enhances oversight and management by:</p>
                                    <ul>
                                        <li><strong>Unified Dashboards:</strong> Access group-level KPIs and drill down into individual school performance.</li>
                                        <li><strong>Cross-School Comparisons:</strong> Benchmark schools against each other to identify trends and outliers.</li>
                                        <li><strong>Real-Time Data:</strong> Monitor academics, finances, and operations with live updates.</li>
                                        <li><strong>AI-Powered Insights:</strong> Receive actionable recommendations to address underperformance or inefficiencies.</li>
                                        <li><strong>Automated Alerts:</strong> Get notified of critical issues like declining performance or overdue tasks.</li>
                                    </ul>
                                    <p>This ensures you have complete visibility and control over your entire school network.</p>
                                </div>
                            </div>
                        </div>

                        <!-- FAQ 7 -->
                        <div class="faq-item fade-in">
                            <button class="faq-question" type="button" data-bs-toggle="collapse" data-bs-target="#faq7" aria-expanded="false" aria-controls="faq7">
                                <span>What are the cost savings I can expect by centralizing my school management with your platform?</span>
                                <i class="fas fa-chevron-down icon"></i>
                            </button>
                            <div id="faq7" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="faq-answer">
                                    <p>By centralizing school management with ShuleSoft Group Connect, you can achieve significant cost savings through:</p>
                                    <ul>
                                        <li><strong>Reduced Administrative Overhead:</strong> Automate repetitive tasks and streamline workflows.</li>
                                        <li><strong>Optimized Resource Allocation:</strong> Identify underutilized resources and redistribute them effectively.</li>
                                        <li><strong>Improved Fee Collection:</strong> Monitor outstanding balances and enhance collection efficiency.</li>
                                        <li><strong>Lower IT Costs:</strong> Eliminate the need for multiple systems and reduce maintenance expenses.</li>
                                        <li><strong>Data-Driven Decisions:</strong> Avoid costly mistakes by leveraging accurate, real-time insights.</li>
                                    </ul>
                                    <p>These efficiencies translate into measurable financial benefits for your school network.</p>
                                </div>
                            </div>
                        </div>

                        <!-- FAQ 8 -->
                        <div class="faq-item fade-in">
                            <button class="faq-question" type="button" data-bs-toggle="collapse" data-bs-target="#faq8" aria-expanded="false" aria-controls="faq8">
                                <span>How can the platform help me standardize operations and policies across all my institutions?</span>
                                <i class="fas fa-chevron-down icon"></i>
                            </button>
                            <div id="faq8" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="faq-answer">
                                    <p>ShuleSoft Group Connect enables you to standardize operations and policies by:</p>
                                    <ul>
                                        <li><strong>Centralized Policy Management:</strong> Push academic, financial, and operational policies to all schools.</li>
                                        <li><strong>Uniform Reporting:</strong> Ensure consistent data formats and metrics across institutions.</li>
                                        <li><strong>Template-Based Workflows:</strong> Use predefined templates for budgets, schedules, and reports.</li>
                                        <li><strong>Role-Based Access Control:</strong> Enforce standardized permissions and responsibilities for staff.</li>
                                        <li><strong>Automated Compliance Checks:</strong> Monitor adherence to group-wide policies and flag deviations.</li>
                                    </ul>
                                    <p>This fosters consistency and operational excellence across your entire school network.</p>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            
            <div class="text-center mt-5">
                <p class="fade-in">Still have questions? <a href="#contact" class="text-decoration-none text-primary fw-bold">Contact our sales team</a> for personalized answers.</p>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer id="contact" class="footer">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 mb-4">
                    <h5><i class="fas fa-graduation-cap me-2"></i>ShuleSoft Group Connect</h5>
                    <p>Empowering school owners with AI-driven insights and centralized management for educational excellence.</p>
                    <div class="social-links">
                        <a href="https://twitter.com/ShuleSoft" class="me-3" target="_blank"><i class="fab fa-twitter"></i></a>
                        <a href="https://www.linkedin.com/company/shulesoft-tz" class="me-3" target="_blank"><i class="fab fa-linkedin"></i></a>
                        <a href="https://www.facebook.com/ShuleSoft" class="me-3" target="_blank"><i class="fab fa-facebook"></i></a>
                    </div>
                </div>
                
                <div class="col-lg-2 col-md-6 mb-4">
                    <h6>Product</h6>
                    <ul class="list-unstyled">
                        <li><a href="#features">Features</a></li>
                        <li><a href="#faq">FAQ</a></li>
                        <li><a href="#demo">Demo</a></li>
                        <!-- <li><a href="#api">API</a></li> -->
                    </ul>
                </div>
                
                <div class="col-lg-2 col-md-6 mb-4">
                    <h6>Company</h6>
                    <ul class="list-unstyled">
                        <li><a href="https://www.shulesoft.africa/about" target="_blank">About</a></li>
                        <li><a href="https://www.shulesoft.africa/careers" target="_blank">Careers</a></li>
                        <li><a href="https://www.shulesoft.africa/blog" target="_blank">Blog</a></li>
                        <li><a href="https://www.shulesoft.africa/press" target="_blank">Press</a></li>
                    </ul>
                </div>
                
                <div class="col-lg-2 col-md-6 mb-4">
                    <h6>Support</h6>
                    <ul class="list-unstyled">
                        <li><a href="#help">Help Center</a></li>
                        <li><a href="#contact">Contact</a></li>
                        <li><a href="#status">Status</a></li>
                        <li><a href="#community">Community</a></li>
                    </ul>
                </div>
                
                <div class="col-lg-2 col-md-6 mb-4">
                    <h6>Legal</h6>
                    <ul class="list-unstyled">
                        <li><a href="{{ route('legal.privacy-policy') }}">Privacy Policy</a></li>
                        <li><a href="{{ route('legal.terms-of-service') }}">Terms of Service</a></li>
                        <li><a href="{{ route('legal.ai-policy-security') }}">AI Policy & Security</a></li>
                        <li><a href="{{ route('legal.data-processing-agreement') }}">Data Processing Agreement</a></li>
                    </ul>
                </div>
            </div>
            
            <hr class="my-4">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <p class="mb-0">&copy; 2025 ShuleSoft. All rights reserved.</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <p class="mb-0">Made with <i class="fas fa-heart text-danger"></i> for Education</p>
                </div>
            </div>
        </div>
    </footer>

    <!-- Login Modal -->
    <div class="modal fade" id="loginModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-graduation-cap me-2 text-primary"></i>
                        Login to ShuleSoft Group Connect
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <!-- Session Status -->
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <!-- Email Address -->
                        <div class="mb-3">
                            <label for="email" class="form-label">{{ __('Email') }}</label>
                            <input id="email" class="form-control" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username">
                            @error('email')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Password -->
                        <div class="mb-3">
                            <label for="password" class="form-label">{{ __('Password') }}</label>
                            <input id="password" class="form-control" type="password" name="password" required autocomplete="current-password">
                            @error('password')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Remember Me -->
                        <div class="form-check mb-3">
                            <input id="remember_me" type="checkbox" class="form-check-input" name="remember">
                            <label for="remember_me" class="form-check-label">{{ __('Remember me') }}</label>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-sign-in-alt me-2"></i>{{ __('Log in') }}
                            </button>
                        </div>

                        <div class="text-center mt-3">
                            @if (Route::has('password.request'))
                                <a class="text-decoration-none" href="{{ route('password.request') }}">
                                    {{ __('Forgot your password?') }}
                                </a>
                            @endif
                        </div>

                        <hr class="my-4">
                        
                        <div class="text-center">
                            <p class="mb-2">Don't have an account?</p>
                            <a href="{{ route('onboarding.start') }}" class="btn btn-outline-primary w-100">
                                <i class="fas fa-user-plus me-2"></i>Sign Up for Free Trial
                            </a>
                            <small class="text-muted d-block mt-2">
                                Join the ShuleSoft Group Connect network
                            </small>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Onboarding Wizard Modal -->
    <div class="modal fade wizard-modal" id="onboardingWizard" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <!-- Wizard Header -->
                <div class="wizard-header">
                    <h4>Welcome to ShuleSoft Group Connect</h4>
                    <p>Let's set up your multi-school management account in just a few steps</p>
                </div>

                <!-- Step Indicator -->
                <div class="step-indicator">
                    <div class="step active" data-step="1">
                        <div class="step-number">1</div>
                        <div class="step-label">Organization</div>
                    </div>
                    <div class="step-connector"></div>
                    <div class="step" data-step="2">
                        <div class="step-number">2</div>
                        <div class="step-label">Schools</div>
                    </div>
                    <div class="step-connector"></div>
                    <div class="step" data-step="3">
                        <div class="step-number">3</div>
                        <div class="step-label">Subscription</div>
                    </div>
                    <div class="step-connector"></div>
                    <div class="step" data-step="4">
                        <div class="step-number">4</div>
                        <div class="step-label">Finalize</div>
                    </div>
                </div>

                <!-- Progress Bar -->
                <div class="progress-indicator">
                    <div class="progress">
                        <div class="progress-bar" role="progressbar" style="width: 25%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                </div>

                <!-- Wizard Body -->
                <div class="wizard-body">
                    <form id="onboardingForm">
                        <!-- Step 1: Organization Setup -->
                        <div class="step-content active" data-step="1">
                            <h5 class="mb-4">
                                <i class="fas fa-building me-2 text-primary"></i>
                                Organization Information
                            </h5>
                            
                            <div class="form-section">
                                <h6><i class="fas fa-info-circle"></i>Basic Information</h6>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Organization Name <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="org_name" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Organization Email <span class="text-danger">*</span></label>
                                        <input type="email" class="form-control" name="org_email" required>
                                    </div>
                                </div>
                            </div>

                            <div class="form-section">
                                <h6><i class="fas fa-user"></i>Key Contact Person</h6>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Contact Person Name <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="contact_name" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Contact Person Email <span class="text-danger">*</span></label>
                                        <input type="email" class="form-control" name="contact_email" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Contact Person Phone <span class="text-danger">*</span></label>
                                        <input type="tel" class="form-control" name="contact_phone" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Number of Schools Owned <span class="text-danger">*</span></label>
                                        <input type="number" placeholder="Min, 2 schools" class="form-control" value="2" name="schools_count" min="2" required>
                                    </div>
                                </div>
                            </div>

                            <div class="form-section">
                                <h6><i class="fas fa-graduation-cap"></i>ShuleSoft Usage Status</h6>
                                <div class="mb-3">
                                    <div class="form-check mb-2">
                                        <input class="form-check-input shulesoft_status" type="radio" name="usage_status" id="status_all" value="all" required>
                                        <label class="form-check-label" for="status_all">
                                            <strong>All schools use ShuleSoft</strong>
                                            <br><small class="text-muted">All my schools are currently using ShuleSoft systems</small>
                                        </label>
                                    </div>
                                    <div class="form-check mb-2">
                                        <input class="form-check-input shulesoft_status" type="radio" name="usage_status" id="status_some" value="some" required>
                                        <label class="form-check-label" for="status_some">
                                            <strong>Some schools use ShuleSoft, others do not</strong>
                                            <br><small class="text-muted">Mixed environment with both ShuleSoft and non-ShuleSoft schools</small>
                                        </label>
                                    </div>
                                    <div class="form-check mb-2">
                                        <input class="form-check-input shulesoft_status" type="radio" name="usage_status" id="status_none" value="none" required>
                                        <label class="form-check-label" for="status_none">
                                            <strong>No school uses ShuleSoft</strong>
                                            <br><small class="text-muted">Looking to implement ShuleSoft across my school network</small>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Step 2: School Setup -->
                        <div class="step-content" data-step="2">
                            <h5 class="mb-4">
                                <i class="fas fa-school me-2 text-primary"></i>
                                School Configuration
                            </h5>

                            <!-- Case A: All Schools Use ShuleSoft -->
                            <div id="case-all" class="usage-case">
                                <div class="form-section" id="shulesoft-schools">
                                    <h6><i class="fas fa-graduation-cap"></i>Schools Using ShuleSoft</h6>
                                    <div class="alert alert-info">
                                        <i class="fas fa-info-circle me-2"></i>
                                        <strong>How to find your School Login Code:</strong><br>
                                        Login as Admin → Settings → Miscellaneous Tab → Copy Login Code
                                    </div>
                                    <div id="shulesoft-schools-container">
                                        <div class="school-entry">
                                            <div class="school-entry-header">
                                                <span class="school-entry-title">School #1</span>
                                                <button type="button" class="remove-school" style="display: none;">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label">School Login Code <span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control login_code_validation" name="shulesoft_schools[0][login_code]" required>
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label">School Name <span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control" name="shulesoft_schools[0][name]" required>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <button type="button" class="add-school-btn" id="add-shulesoft-school">
                                        <i class="fas fa-plus me-2"></i>Add Another School
                                    </button>
                                </div>
                            </div>

                            <!-- Case B: Mixed Environment -->
                            <div id="case-some" class="usage-case">
                                <div class="form-section">
                                    <h6><i class="fas fa-graduation-cap"></i>Schools Using ShuleSoft</h6>
                                    <div class="alert alert-info">
                                        <i class="fas fa-info-circle me-2"></i>
                                        <strong>How to find your School Login Code:</strong><br>
                                        Login as Admin → Settings → Miscellaneous Tab → Copy Login Code
                                    </div>
                                    <div id="mixed-shulesoft-container">
                                        <div class="school-entry">
                                            <div class="school-entry-header">
                                                <span class="school-entry-title">ShuleSoft School #1</span>
                                                <button type="button" class="remove-school" style="display: none;">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label">School Login Code <span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control" name="mixed_shulesoft[0][login_code]" required>
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label">School Name <span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control" name="mixed_shulesoft[0][name]" required>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <button type="button" class="add-school-btn" id="add-mixed-shulesoft">
                                        <i class="fas fa-plus me-2"></i>Add Another ShuleSoft School
                                    </button>
                                </div>

                                <div class="form-section">
                                    <h6><i class="fas fa-exclamation-triangle"></i>Schools NOT Using ShuleSoft</h6>
                                    <div class="alert alert-warning">
                                        <i class="fas fa-info-circle me-2"></i>
                                        <strong>System will contact your school representative to set up a system.</strong>
                                    </div>
                                    <div id="non-shulesoft-container">
                                        <div class="school-entry">
                                            <div class="school-entry-header">
                                                <span class="school-entry-title">Non-ShuleSoft School #1</span>
                                                <button type="button" class="remove-school" style="display: none;">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label">School Name <span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control" name="non_shulesoft[0][name]" required>
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label">School Contact Email <span class="text-danger">*</span></label>
                                                    <input type="email" class="form-control" name="non_shulesoft[0][email]" required>
                                                </div>
                                                <div class="col-md-4 mb-3">
                                                    <label class="form-label">Contact Person Name <span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control" name="non_shulesoft[0][contact_name]" required>
                                                </div>
                                                <div class="col-md-4 mb-3">
                                                    <label class="form-label">Contact Person Email <span class="text-danger">*</span></label>
                                                    <input type="email" class="form-control" name="non_shulesoft[0][contact_email]" required>
                                                </div>
                                                <div class="col-md-4 mb-3">
                                                    <label class="form-label">Contact Person Phone <span class="text-danger">*</span></label>
                                                    <input type="tel" class="form-control" name="non_shulesoft[0][contact_phone]" required>
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label">Estimated Number of Students <span class="text-danger">*</span></label>
                                                    <input type="number" class="form-control" name="non_shulesoft[0][students_count]" min="1" required>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <button type="button" class="add-school-btn" id="add-non-shulesoft">
                                        <i class="fas fa-plus me-2"></i>Add Another Non-ShuleSoft School
                                    </button>
                                </div>
                            </div>

                            <!-- Case C: No Schools Use ShuleSoft -->
                            <div id="case-none" class="usage-case">
                                <div class="form-section">
                                    <h6><i class="fas fa-exclamation-triangle"></i>Schools to be Setup with ShuleSoft</h6>
                                    <div class="alert alert-warning">
                                        <i class="fas fa-info-circle me-2"></i>
                                        <strong>System will contact your school representative to set up a system.</strong>
                                    </div>
                                    <div id="new-schools-container">
                                        <div class="school-entry">
                                            <div class="school-entry-header">
                                                <span class="school-entry-title">School #1</span>
                                                <button type="button" class="remove-school" style="display: none;">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label">School Name <span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control" name="new_schools[0][name]" required>
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label">School Contact Email <span class="text-danger">*</span></label>
                                                    <input type="email" class="form-control" name="new_schools[0][email]" required>
                                                </div>
                                                <div class="col-md-4 mb-3">
                                                    <label class="form-label">Contact Person Name <span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control" name="new_schools[0][contact_name]" required>
                                                </div>
                                                <div class="col-md-4 mb-3">
                                                    <label class="form-label">Contact Person Email <span class="text-danger">*</span></label>
                                                    <input type="email" class="form-control" name="new_schools[0][contact_email]" required>
                                                </div>
                                                <div class="col-md-4 mb-3">
                                                    <label class="form-label">Contact Person Phone <span class="text-danger">*</span></label>
                                                    <input type="tel" class="form-control" name="new_schools[0][contact_phone]" required>
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label">Estimated Number of Students <span class="text-danger">*</span></label>
                                                    <input type="number" class="form-control" name="new_schools[0][students_count]" min="1" required>
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label">Upload Document <span class="text-danger">*</span></label>
                                                    <div class="upload-zone-small" onclick="document.getElementById('proofUpload_0').click()">
                                                        <i class="fas fa-upload me-2"></i>
                                                        <span>Upload school registration certificate, tax document, or other valid proof</span>
                                                    </div>
                                                    <input type="file" id="proofUpload_0" name="new_schools[0][proof_document]" accept=".pdf,.jpg,.jpeg,.png,.doc,.docx" style="display: none;" required>
                                                    <div id="uploadedFile_0" class="mt-2"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <button type="button" class="add-school-btn" id="add-new-school">
                                        <i class="fas fa-plus me-2"></i>Add Another School
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Step 3: Subscription & Trial Setup -->
                        <div class="step-content" data-step="3">
                            <h5 class="mb-4">
                                <i class="fas fa-credit-card me-2 text-primary"></i>
                                Subscription Setup
                            </h5>
                            
                            <div id="subscription-content">
                                <!-- Content will be dynamically generated based on school status -->
                            </div>
                        </div>

                        <!-- Step 4: Finalize Account -->
                        <div class="step-content" data-step="4">
                            <h5 class="mb-4">
                                <i class="fas fa-key me-2 text-primary"></i>
                                Create Your Account
                            </h5>
                            
                            <div class="form-section">
                                <h6><i class="fas fa-lock"></i>Account Security</h6>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Password <span class="text-danger">*</span></label>
                                        <input type="password" class="form-control" name="password" id="wizardPassword" required minlength="8">
                                        <div class="form-text">Password must be at least 8 characters long</div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Confirm Password <span class="text-danger">*</span></label>
                                        <input type="password" class="form-control" name="password_confirmation" id="wizardPasswordConfirmation" required>
                                        <div id="password-match" class="form-text"></div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-section">
                                <h6><i class="fas fa-check-circle"></i>Account Summary</h6>
                                <div id="account-summary">
                                    <!-- Summary will be populated dynamically -->
                                </div>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Wizard Footer -->
                <div class="wizard-footer">
                    <div>
                        <span class="text-muted">Step <span id="currentStep">1</span> of 4</span>
                    </div>
                    <div class="wizard-navigation">
                        <button type="button" class="btn btn-outline-secondary" id="prevBtn" style="display: none;">
                            <i class="fas fa-arrow-left me-2"></i>Previous
                        </button>
                        <button type="button" class="btn btn-primary" id="nextBtn">
                            Next<i class="fas fa-arrow-right ms-2"></i>
                        </button>
                        <button type="button" class="btn btn-success" id="submitBtn" style="display: none;">
                            <i class="fas fa-check me-2"></i>Create Account
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Demo Request Modal -->
    <div class="modal fade" id="demoRequestModal" tabindex="-1" aria-labelledby="demoRequestModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="demoRequestModalLabel">
                        <i class="fas fa-rocket me-2 text-primary"></i>
                        Request Demo Access
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>Get exclusive demo access to ShuleSoft Group Connect!</strong><br>
                        Fill out the form below and our sales team will set up your personalized demo account.
                    </div>

                    <form id="demoRequestForm">
                        @csrf
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="organization_name" class="form-label">Organization Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="organization_name" name="organization_name" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="organization_contact" class="form-label">Organization Contact <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="organization_contact" name="organization_contact" required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="contact_name" class="form-label">Contact Person Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="contact_name" name="contact_name" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="contact_phone" class="form-label">Contact Phone <span class="text-danger">*</span></label>
                                <input type="tel" class="form-control" id="contact_phone" name="contact_phone" required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="contact_email" class="form-label">Contact Email <span class="text-danger">*</span></label>
                                <input type="email" class="form-control" id="contact_email" name="contact_email" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="organization_country" class="form-label">Country <span class="text-danger">*</span></label>
                                <select class="form-control" id="organization_country" name="organization_country" required>
                                    <option value="">Select Country</option>
                                    <option value="Kenya">Kenya</option>
                                    <option value="Uganda">Uganda</option>
                                    <option value="Tanzania">Tanzania</option>
                                    <option value="Rwanda">Rwanda</option>
                                    <option value="South Africa">South Africa</option>
                                    <option value="Nigeria">Nigeria</option>
                                    <option value="Ghana">Ghana</option>
                                    <option value="Other">Other</option>
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-8 mb-3">
                                <label for="organization_address" class="form-label">Organization Address <span class="text-danger">*</span></label>
                                <textarea class="form-control" id="organization_address" name="organization_address" rows="3" required></textarea>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="total_schools" class="form-label">Total Schools <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" id="total_schools" name="total_schools" min="1" required>
                            </div>
                        </div>

                        <div class="alert alert-warning">
                            <i class="fas fa-clock me-2"></i>
                            <strong>What happens next?</strong><br>
                            Our sales team will review your request and send you demo credentials within 24 hours.
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i>Cancel
                    </button>
                    <button type="button" class="btn btn-primary" id="submitDemoRequest">
                        <i class="fas fa-paper-plane me-2"></i>Submit Request
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- jQuery Validation Plugin -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/additional-methods.min.js"></script>
    <!-- Custom JavaScript -->
    <script>
        // Custom validation methods
        $.validator.addMethod("fullName", function(value, element) {
            return this.optional(element) || /^[a-zA-Z\s.'-]+$/.test(value);
        }, "Please enter a valid name (letters, spaces, dots, hyphens, and apostrophes only)");

        $.validator.addMethod("organizationName", function(value, element) {
            return this.optional(element) || /^[a-zA-Z0-9\s.&'-]+$/.test(value);
        }, "Please enter a valid organization name");

        $.validator.addMethod("phoneNumber", function(value, element) {
            return this.optional(element) || /^[\+]?[0-9\s\-\(\)]{10,}$/.test(value);
        }, "Please enter a valid phone number");

        // Form validation setup
        function setupFormValidation() {
            $("#onboardingForm").validate({
                rules: {
                    org_name: {
                        required: true,
                        organizationName: true,
                        minlength: 2,
                        maxlength: 255
                    },
                    org_email: {
                        required: true,
                        email: true,
                        maxlength: 255
                    },
                    contact_name: {
                        required: true,
                        fullName: true,
                        minlength: 2,
                        maxlength: 255
                    },
                    contact_email: {
                        required: true,
                        email: true,
                        maxlength: 255
                    },
                    contact_phone: {
                        required: true,
                        phoneNumber: true
                    },
                    schools_count: {
                        required: true,
                        min: 2,
                        digits: true
                    },
                    usage_status: {
                        required: true
                    },
                    password: {
                        required: true,
                        minlength: 8
                    },
                    password_confirmation: {
                        required: true,
                        equalTo: "#wizardPassword"
                    }
                },
                messages: {
                    org_name: {
                        required: "Organization name is required",
                        minlength: "Organization name must be at least 2 characters long"
                    },
                    org_email: {
                        required: "Organization email is required",
                        email: "Please enter a valid email address"
                    },
                    contact_name: {
                        required: "Contact person name is required",
                        minlength: "Name must be at least 2 characters long"
                    },
                    contact_email: {
                        required: "Contact email is required",
                        email: "Please enter a valid email address"
                    },
                    contact_phone: {
                        required: "Contact phone is required"
                    },
                    schools_count: {
                        required: "Number of schools is required",
                        min: "Minimum 2 schools required"
                    },
                    usage_status: {
                        required: "Please select your ShuleSoft usage status"
                    },
                    password: {
                        required: "Password is required",
                        minlength: "Password must be at least 8 characters long"
                    },
                    password_confirmation: {
                        required: "Please confirm your password",
                        equalTo: "Passwords do not match"
                    }
                },
                errorElement: 'div',
                errorClass: 'invalid-feedback',
                highlight: function(element) {
                    $(element).addClass('is-invalid');
                },
                unhighlight: function(element) {
                    $(element).removeClass('is-invalid').addClass('is-valid');
                },
                errorPlacement: function(error, element) {
                    if (element.attr('type') === 'radio') {
                        error.insertAfter(element.closest('.form-check'));
                    } else {
                        error.insertAfter(element);
                    }
                }
            });
        }

        $(document).ready(function () {
            // Initialize form validation
            setupFormValidation();
            
            // Hide all usage-case divs on page load
            $('.usage-case').hide();

            // Handle radio button change event - this will work alongside OnboardingWizard
            $('.shulesoft_status').on('change', function () {
                // Hide all usage-case divs
                $('.usage-case').hide();

                // Get the value of the selected radio button
                const selectedValue = $(this).val();

                // Show the corresponding div based on the selected value
                if (selectedValue === 'all') {
                    $('#case-all').show();
                } else if (selectedValue === 'some') {
                    $('#case-some').show();
                } else if (selectedValue === 'none') {
                    $('#case-none').show();
                }
                
                // Also trigger the OnboardingWizard's showUsageCase method if it exists
                if (window.onboardingWizard) {
                    window.onboardingWizard.showUsageCase();
                }
            });
        });

        $(document).on('blur', '.login_code_validation', function () {
            const input = $(this);
            const loginCode = input.val();

            if (loginCode.trim() === '') {
                input.addClass('is-invalid');
                input.after('<div class="text-danger mt-1">Login code cannot be empty.</div>');
                return;
            }

            $.ajax({
                url: '/settings/validate-login-code',
                method: 'POST',
                data: {
                    login_code: loginCode
                },
                 headers: {
                           // 'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                        },
                success: function (response) {
                    input.removeClass('is-invalid is-valid');
                    input.siblings('.validation-message').remove();

                    if (response.valid) {
                        input.addClass('is-valid');
                         document.getElementById('nextBtn').disabled = false;
                    } else {
                        input.addClass('is-invalid');
                        input.parent().append('<div class="text-danger mt-1 validation-message">This code is not valid. School cannot be onboarded.</div>');
                    }
                },
                error: function () {
                    input.removeClass('is-valid').addClass('is-invalid');
                    input.siblings('.validation-message').remove();
                    input.parent().append('<div class="text-danger mt-1 validation-message">An error occurred while validating the login code. Please try again.</div>');
                }
            });
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

        // Scroll animations
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('visible');
                }
            });
        }, observerOptions);

        // Observe all fade-in elements
        document.querySelectorAll('.fade-in').forEach(el => {
            observer.observe(el);
        });

        // Auto-open login modal if there are validation errors
        @if ($errors->any())
            document.addEventListener('DOMContentLoaded', function() {
                const loginModal = new bootstrap.Modal(document.getElementById('loginModal'));
                loginModal.show();
            });
        @endif

        // Add loading states to CTA buttons
        document.querySelectorAll('.btn').forEach(btn => {
            btn.addEventListener('click', function(e) {
                if (this.href && this.href.includes('#signup')) {
                    e.preventDefault();
                    this.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Loading...';
                    this.disabled = true;
                    
                    // Simulate loading for demo
                    setTimeout(() => {
                        this.innerHTML = '<i class="fas fa-rocket me-2"></i>Start Free Trial';
                        this.disabled = false;
                        alert('Thank you for your interest! Our team will contact you soon.');
                    }, 2000);
                }
            });
        });

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
                
                // Update navbar background for current theme
                this.updateNavbarBackground();
            }

            toggleTheme() {
                this.theme = this.theme === 'light' ? 'dark' : 'light';
                this.applyTheme();
            }

            updateNavbarBackground() {
                const navbar = document.querySelector('.navbar');
                if (navbar) {
                    if (window.scrollY > 50) {
                        const bgColor = this.theme === 'dark' ? 'rgba(16, 23, 42, 0.95)' : 'rgba(255, 255, 255, 0.95)';
                        navbar.style.background = bgColor;
                        navbar.style.backdropFilter = 'blur(10px)';
                    } else {
                        navbar.style.background = '';
                        navbar.style.backdropFilter = '';
                    }
                }
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
        window.themeManager = themeManager;

        // Update navbar background logic for theme awareness
        window.addEventListener('scroll', function() {
            const navbar = document.querySelector('.navbar');
            if (window.scrollY > 50) {
                const bgColor = themeManager.theme === 'dark' ? 'rgba(16, 23, 42, 0.95)' : 'rgba(255, 255, 255, 0.95)';
                navbar.style.background = bgColor;
                navbar.style.backdropFilter = 'blur(10px)';
            } else {
                navbar.style.background = '';
                navbar.style.backdropFilter = '';
            }
        });

        // ===== DEMO REQUEST FUNCTIONALITY =====
        $(document).ready(function() {
            // Demo request form submission
            $('#submitDemoRequest').on('click', function() {
                const form = $('#demoRequestForm');
                const btn = $(this);
                
                // Basic validation
                if (!form[0].checkValidity()) {
                    form[0].reportValidity();
                    return;
                }

                // Show loading state
                btn.prop('disabled', true);
                btn.html('<i class="fas fa-spinner fa-spin me-2"></i>Submitting...');

                const formData = {
                    _token: $('input[name="_token"]').val(),
                    organization_name: $('#organization_name').val(),
                    organization_contact: $('#organization_contact').val(),
                    contact_name: $('#contact_name').val(),
                    contact_phone: $('#contact_phone').val(),
                    contact_email: $('#contact_email').val(),
                    organization_address: $('#organization_address').val(),
                    organization_country: $('#organization_country').val(),
                    total_schools: $('#total_schools').val()
                };

                $.ajax({
                    url: '{{ route("demo.request") }}',
                    method: 'POST',
                    data: formData,
                    success: function(response) {
                        if (response.success) {
                            // Show success message
                            $('.modal-body').html(`
                                <div class="text-center py-4">
                                    <div class="mb-3">
                                        <i class="fas fa-check-circle text-success" style="font-size: 4rem;"></i>
                                    </div>
                                    <h4 class="text-success">Request Submitted Successfully!</h4>
                                    <p class="text-muted">${response.message}</p>
                                    <p class="small text-muted mt-3">
                                        <i class="fas fa-info-circle me-1"></i>
                                        You will receive an email with your demo credentials within 24 hours.
                                    </p>
                                </div>
                            `);
                            
                            // Hide footer buttons and show close button
                            $('.modal-footer').html(`
                                <button type="button" class="btn btn-primary" data-bs-dismiss="modal">
                                    <i class="fas fa-check me-2"></i>Done
                                </button>
                            `);
                        }
                    },
                    error: function(xhr) {
                        // Show error message
                        let errorMessage = 'An error occurred while submitting your request. Please try again.';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        }
                        
                        alert('Error: ' + errorMessage);
                        
                        // Reset button
                        btn.prop('disabled', false);
                        btn.html('<i class="fas fa-paper-plane me-2"></i>Submit Request');
                    }
                });
            });

            // Reset form when modal is closed
            $('#demoRequestModal').on('hidden.bs.modal', function() {
                // Reset form
                $('#demoRequestForm')[0].reset();
                
                // Reset modal content if it was changed
                if ($('.modal-body .text-center.py-4').length > 0) {
                    location.reload(); // Simple reload to reset modal
                }
            });
        });

        // ===== ONBOARDING WIZARD FUNCTIONALITY =====
        class OnboardingWizard {
            constructor() {
                console.log('OnboardingWizard initializing...');
                this.currentStep = 1;
                this.totalSteps = 4;
                this.schoolCounters = {
                    shulesoft: 1,
                    mixedShulesoft: 1,
                    nonShulesoft: 1,
                    newSchools: 1
                };
                this.init();
                console.log('OnboardingWizard initialized successfully');
            }

            init() {
                this.bindEvents();
                this.updateStepDisplay();
                // Only call showUsageCase for Step 2 and beyond
                if (this.currentStep >= 2) {
                    this.showUsageCase();
                }
            }

            bindEvents() {
                // Navigation buttons
                const nextBtn = document.getElementById('nextBtn');
                const prevBtn = document.getElementById('prevBtn');
                const submitBtn = document.getElementById('submitBtn');
                
                if (nextBtn) nextBtn.addEventListener('click', () => this.nextStep());
                if (prevBtn) prevBtn.addEventListener('click', () => this.prevStep());
                if (submitBtn) submitBtn.addEventListener('click', () => this.submitForm());

                // Usage status change
                document.querySelectorAll('input[name="usage_status"]').forEach(radio => {
                    radio.addEventListener('change', () => this.showUsageCase());
                });

                // Add school buttons with error handling
                const addShulesoftBtn = document.getElementById('add-shulesoft-school');
                const addMixedShulesoftBtn = document.getElementById('add-mixed-shulesoft');
                const addNonShulesoftBtn = document.getElementById('add-non-shulesoft');
                const addNewSchoolBtn = document.getElementById('add-new-school');
                
                if (addShulesoftBtn) {
                    addShulesoftBtn.addEventListener('click', () => {
                        console.log('Add ShuleSoft school clicked');
                        this.addSchool('shulesoft');
                    });
                }
                if (addMixedShulesoftBtn) addMixedShulesoftBtn.addEventListener('click', () => this.addSchool('mixedShulesoft'));
                if (addNonShulesoftBtn) addNonShulesoftBtn.addEventListener('click', () => this.addSchool('nonShulesoft'));
                if (addNewSchoolBtn) addNewSchoolBtn.addEventListener('click', () => this.addSchool('newSchools'));

                // File upload
                const uploadZone = document.getElementById('uploadZone');
                const fileInput = document.getElementById('proofUpload');
                
                if (uploadZone && fileInput) {
                    uploadZone.addEventListener('click', () => fileInput.click());
                    uploadZone.addEventListener('dragover', (e) => {
                        e.preventDefault();
                        uploadZone.classList.add('dragover');
                    });
                    uploadZone.addEventListener('dragleave', () => {
                        uploadZone.classList.remove('dragover');
                    });
                    uploadZone.addEventListener('drop', (e) => {
                        e.preventDefault();
                        uploadZone.classList.remove('dragover');
                        const files = e.dataTransfer.files;
                        if (files.length > 0) {
                            fileInput.files = files;
                            this.handleFileUpload(files[0]);
                        }
                    });

                    fileInput.addEventListener('change', (e) => {
                        if (e.target.files.length > 0) {
                            this.handleFileUpload(e.target.files[0]);
                        }
                    });
                }

                // Password validation
                const wizardPasswordConfirmation = document.getElementById('wizardPasswordConfirmation');
                if (wizardPasswordConfirmation) {
                    wizardPasswordConfirmation.addEventListener('input', () => this.validatePassword());
                }
                
                // Set up initial file upload handler for Case C first school
                this.setupFileUploadHandler(0);
            }

            showUsageCase() {
                const usageStatus = document.querySelector('input[name="usage_status"]:checked')?.value;
                
                // Hide all cases
                document.querySelectorAll('.usage-case').forEach(usageCase => {
                    usageCase.style.display = 'none';
                });

                // Show relevant case
                if (usageStatus) {
                    document.getElementById(`case-${usageStatus}`).style.display = 'block';
                }
            }

            addSchool(type) {
                console.log('Adding school of type:', type);
                
                const containers = {
                    shulesoft: 'shulesoft-schools-container',
                    mixedShulesoft: 'mixed-shulesoft-container',
                    nonShulesoft: 'non-shulesoft-container',
                    newSchools: 'new-schools-container'
                };

                console.log('Adding school to container:', containers[type]);
               var container = document.getElementById(containers[type]);
                if (!container) {
                    console.error('Container not found:', containers[type]);
                    return;
                }
                
                console.log('Container found:', container);

                const templates = {
                    shulesoft: (index) => `
                        <div class="school-entry">
                            <div class="school-entry-header">
                                <span class="school-entry-title">School #${index + 1}</span>
                                <button type="button" class="remove-school" onclick="this.closest('.school-entry').remove()">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">School Login Code <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="shulesoft_schools[${index}][login_code]" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">School Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="shulesoft_schools[${index}][name]" required>
                                </div>
                            </div>
                        </div>
                    `,
                    mixedShulesoft: (index) => `
                        <div class="school-entry">
                            <div class="school-entry-header">
                                <span class="school-entry-title">ShuleSoft School #${index + 1}</span>
                                <button type="button" class="remove-school" onclick="this.closest('.school-entry').remove()">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">School Login Code <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="mixed_shulesoft[${index}][login_code]" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">School Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="mixed_shulesoft[${index}][name]" required>
                                </div>
                            </div>
                        </div>
                    `,
                    nonShulesoft: (index) => `
                        <div class="school-entry">
                            <div class="school-entry-header">
                                <span class="school-entry-title">Non-ShuleSoft School #${index + 1}</span>
                                <button type="button" class="remove-school" onclick="this.closest('.school-entry').remove()">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">School Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="non_shulesoft[${index}][name]" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">School Contact Email <span class="text-danger">*</span></label>
                                    <input type="email" class="form-control" name="non_shulesoft[${index}][email]" required>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Contact Person Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="non_shulesoft[${index}][contact_name]" required>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Contact Person Email <span class="text-danger">*</span></label>
                                    <input type="email" class="form-control" name="non_shulesoft[${index}][contact_email]" required>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Contact Person Phone <span class="text-danger">*</span></label>
                                    <input type="tel" class="form-control" name="non_shulesoft[${index}][contact_phone]" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Estimated Number of Students <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control" name="non_shulesoft[${index}][students_count]" min="1" required>
                                </div>
                            </div>
                        </div>
                    `,
                    newSchools: (index) => `
                        <div class="school-entry">
                            <div class="school-entry-header">
                                <span class="school-entry-title">School #${index + 1}</span>
                                <button type="button" class="remove-school" onclick="this.closest('.school-entry').remove()">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">School Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="new_schools[${index}][name]" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">School Contact Email <span class="text-danger">*</span></label>
                                    <input type="email" class="form-control" name="new_schools[${index}][email]" required>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Contact Person Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="new_schools[${index}][contact_name]" required>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Contact Person Email <span class="text-danger">*</span></label>
                                    <input type="email" class="form-control" name="new_schools[${index}][contact_email]" required>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Contact Person Phone <span class="text-danger">*</span></label>
                                    <input type="tel" class="form-control" name="new_schools[${index}][contact_phone]" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Estimated Number of Students <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control" name="new_schools[${index}][students_count]" min="1" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Upload Document <span class="text-danger">*</span></label>
                                    <div class="upload-zone-small" onclick="document.getElementById('proofUpload_${index}').click()">
                                        <i class="fas fa-upload me-2"></i>
                                        <span>Upload school registration certificate, tax document, or other valid proof</span>
                                    </div>
                                    <input type="file" id="proofUpload_${index}" name="new_schools[${index}][proof_document]" accept=".pdf,.jpg,.jpeg,.png,.doc,.docx" style="display: none;" required>
                                    <div id="uploadedFile_${index}" class="mt-2"></div>
                                </div>
                            </div>
                        </div>
                    `
                };

                container = document.getElementById(containers[type]);
                const index = this.schoolCounters[type];
                
                container.insertAdjacentHTML('beforeend', templates[type](index));
                this.schoolCounters[type]++;

                // Show remove buttons for all entries when we have more than one
                const entries = container.querySelectorAll('.school-entry');
                entries.forEach(entry => {
                    const removeBtn = entry.querySelector('.remove-school');
                    if (removeBtn) {
                        removeBtn.style.display = entries.length > 1 ? 'block' : 'none';
                    }
                });

                // Set up file upload handlers for new schools (Case C)
                if (type === 'newSchools') {
                    this.setupFileUploadHandler(index);
                }
            }

            setupFileUploadHandler(index) {
                // Add a small delay to ensure DOM elements are created
                setTimeout(() => {
                    const fileInput = document.getElementById(`proofUpload_${index}`);
                    if (fileInput) {
                        fileInput.addEventListener('change', (e) => {
                            if (e.target.files.length > 0) {
                                this.handleIndividualFileUpload(e.target.files[0], index);
                            }
                        });
                    }
                }, 100);
            }

            handleIndividualFileUpload(file, index) {
                const uploadedFileDiv = document.getElementById(`uploadedFile_${index}`);
                if (uploadedFileDiv) {
                    uploadedFileDiv.innerHTML = `
                        <div class="alert alert-success alert-sm">
                            <i class="fas fa-check-circle me-2"></i>
                            <small><strong>File uploaded:</strong> ${file.name} (${this.formatFileSize(file.size)})</small>
                        </div>
                    `;
                }
            }

            handleFileUpload(file) {
                const uploadedFiles = document.getElementById('uploadedFiles');
                uploadedFiles.innerHTML = `
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle me-2"></i>
                        <strong>File uploaded:</strong> ${file.name} (${this.formatFileSize(file.size)})
                    </div>
                `;
            }

            formatFileSize(bytes) {
                if (bytes === 0) return '0 Bytes';
                const k = 1024;
                const sizes = ['Bytes', 'KB', 'MB', 'GB'];
                const i = Math.floor(Math.log(bytes) / Math.log(k));
                return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
            }

            validatePassword() {
                const password = document.getElementById('wizardPassword');
                const confirmation = document.getElementById('wizardPasswordConfirmation');
                const matchIndicator = document.getElementById('password-match');

                if (password && confirmation && matchIndicator && confirmation.value) {
                    if (password.value === confirmation.value) {
                        matchIndicator.textContent = 'Passwords match';
                        matchIndicator.className = 'form-text text-success';
                    } else {
                        matchIndicator.textContent = 'Passwords do not match';
                        matchIndicator.className = 'form-text text-danger';
                    }
                }
            }

            generateSubscriptionContent() {
                const usageStatus = document.querySelector('input[name="usage_status"]:checked')?.value;
                const content = document.getElementById('subscription-content');

                if (usageStatus === 'all') {
                    // Collect all login codes from the form
                    const loginCodes = [];
                    document.querySelectorAll('input[name^="shulesoft_schools"]').forEach(input => {
                        if (input.name.includes('[login_code]')) {
                            loginCodes.push(input.value.trim());
                        }
                    });

                    // Validate login codes via AJAX
                    fetch('/settings/validate-login-code', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({ login_codes: loginCodes })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.valid) {
                            content.innerHTML = `
                                <div class="alert alert-success">
                                    <i class="fas fa-check-circle me-2"></i>
                                    <h6>Great! All your schools are already using ShuleSoft.</h6>
                                    <p class="mb-0">We found all your schools. Click Next to finalize your Group Connect account.</p>
                                </div>
                            `;
                            document.getElementById('nextBtn').disabled = false;
                        } else {
                            content.innerHTML = `
                                <div class="alert alert-danger">
                                    <i class="fas fa-exclamation-circle me-2"></i>
                                    <h6>Some login codes are invalid.</h6>
                                    <p>Please go back to the previous step and correct the following invalid login codes:</p>
                                    <ul>
                                        ${data.invalid_codes.map(code => `<li>${code}</li>`).join('')}
                                    </ul>
                                </div>
                            `;
                            document.getElementById('nextBtn').disabled = true;

                            // Highlight invalid login codes
                            document.querySelectorAll('input[name^="shulesoft_schools"]').forEach(input => {
                                if (input.name.includes('[login_code]') && data.invalid_codes.includes(input.value.trim())) {
                                    input.classList.add('is-invalid');
                                } else {
                                    input.classList.remove('is-invalid');
                                }
                            });
                        }
                    })
                    .catch(error => {
                        console.error('Error validating login codes:', error);
                        content.innerHTML = `
                            <div class="alert alert-danger">
                                <i class="fas fa-exclamation-circle me-2"></i>
                                <h6>An error occurred while validating login codes.</h6>
                                <p>Please try again later.</p>
                            </div>
                        `;
                        document.getElementById('nextBtn').disabled = true;
                    });
                
                } else if (usageStatus === 'some') {
                    // Collect all login codes and new school data
                    const loginCodes = [];
                    const newSchools = [];
                    let totalStudents = 0;

                    document.querySelectorAll('input[name^="mixed_shulesoft"]').forEach(input => {
                        if (input.name.includes('[login_code]') && input.value.trim() !== '') {
                            loginCodes.push(input.value.trim());
                        }
                    });

                    document.querySelectorAll('input[name^="non_shulesoft"]').forEach(input => {
                        if (input.name.includes('[students_count]')) {
                            totalStudents += parseInt(input.value.trim() || '0', 10);
                        }
                    });

                    // Validate login codes via AJAX
                    fetch('/settings/validate-login-code', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({ login_codes: loginCodes })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.valid) {
                            // Calculate total cost for new schools
                            const costPerStudent = 10000; // Tsh 10,000
                            const totalCost = totalStudents * costPerStudent;

                            content.innerHTML = `
                                <div class="invoice-summary">
                                    <h6 class="mb-3">Subscription Calculation</h6>
                                    <div class="invoice-row">
                                        <span>ShuleSoft Premium for non-ShuleSoft schools</span>
                                        <span>${totalCost.toLocaleString()} Tsh</span>
                                    </div>
                                    <div class="invoice-row">
                                        <span>Group Connect Premium Access</span>
                                        <span>$149/month</span>
                                    </div>
                                    <div class="invoice-row">
                                        <strong>Total Monthly Cost</strong>
                                        <strong>${totalCost.toLocaleString()} Tsh + $149/month</strong>
                                    </div>
                                </div>
                                <div class="mt-4">
                                    <div class="alert alert-info">
                                        <i class="fas fa-info-circle me-2"></i>
                                        A 14-day free trial has been allocated for your schools. You can proceed to the next stage.
                                    </div>
                                </div>
                            `;
                            document.getElementById('nextBtn').disabled = false;
                        } else {
                            content.innerHTML = `
                                <div class="alert alert-danger">
                                    <i class="fas fa-exclamation-circle me-2"></i>
                                    <h6>Some login codes are invalid.</h6>
                                    <p>Please go back to the previous step and correct the following invalid login codes:</p>
                                    <ul>
                                        ${data.invalid_codes.map(code => `<li>${code}</li>`).join('')}
                                    </ul>
                                </div>
                            `;
                            document.getElementById('nextBtn').disabled = true;

                            // Highlight invalid login codes
                            document.querySelectorAll('input[name^="mixed_shulesoft"]').forEach(input => {
                                if (input.name.includes('[login_code]') && data.invalid_codes.includes(input.value.trim())) {
                                    input.classList.add('is-invalid');
                                } else {
                                    input.classList.remove('is-invalid');
                                }
                            });
                        }
                    })
                    .catch(error => {
                        console.error('Error validating login codes:', error);
                        content.innerHTML = `
                            <div class="alert alert-danger">
                                <i class="fas fa-exclamation-circle me-2"></i>
                                <h6>An error occurred while validating login codes.</h6>
                                <p>Please try again later.</p>
                            </div>
                        `;
                        document.getElementById('nextBtn').disabled = true;
                    });
                } else if (usageStatus === 'none') {
                    content.innerHTML = `
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            <h6>Welcome to ShuleSoft!</h6>
                            <p class="mb-3">Since you're new to ShuleSoft, we'll set up the entire system for your schools.</p>
                            <button type="button" class="btn btn-primary">
                                <i class="fas fa-rocket me-2"></i>Start 14-Day Free Trial
                            </button>
                        </div>
                        <div class="mt-4">
                            <h6>What happens next:</h6>
                            <ul class="list-unstyled">
                                <li><i class="fas fa-check text-success me-2"></i>Free trial account creation</li>
                                <li><i class="fas fa-check text-success me-2"></i>Our team contacts your schools for setup</li>
                                <li><i class="fas fa-check text-success me-2"></i>ShuleSoft installation and training</li>
                                <li><i class="fas fa-check text-success me-2"></i>Group Connect dashboard activation</li>
                            </ul>
                        </div>
                    `;
                }
            }

            generateAccountSummary() {
                const formData = new FormData(document.getElementById('onboardingForm'));
                const orgName = formData.get('org_name');
                const contactName = formData.get('contact_name');
                const schoolsCount = formData.get('schools_count');
                const usageStatus = formData.get('usage_status');

                const statusLabels = {
                    all: 'All schools use ShuleSoft',
                    some: 'Mixed environment',
                    none: 'New to ShuleSoft'
                };

                const summary = document.getElementById('account-summary');
                summary.innerHTML = `
                    <div class="row">
                        <div class="col-md-6">
                            <strong>Organization:</strong> ${orgName || 'Not specified'}<br>
                            <strong>Contact Person:</strong> ${contactName || 'Not specified'}<br>
                            <strong>Number of Schools:</strong> ${schoolsCount || 'Not specified'}
                        </div>
                        <div class="col-md-6">
                            <strong>Usage Status:</strong> ${statusLabels[usageStatus] || 'Not selected'}<br>
                            <strong>Account Type:</strong> ShuleSoft Group Connect<br>
                            <strong>Subscription:</strong> Free Trial (14 days)
                        </div>
                    </div>
                `;
            }

            validateStep(step) {
                const form = $('#onboardingForm');
                const stepContent = document.querySelector(`.wizard-modal [data-step="${step}"]`);
                
                // Special validation for step 2 - check usage status from step 1
                if (step === 2) {
                    const usageStatus = document.querySelector('input[name="usage_status"]:checked');
                    if (!usageStatus) {
                        alert('Please select a ShuleSoft usage status in Step 1 before proceeding.');
                        return false;
                    }
                }
                
                // Get all form elements in the current step
                const stepFields = $(stepContent).find('input, select, textarea');
                let isValid = true;

                // Validate each field in the current step
                stepFields.each(function() {
                    if ($(this).is(':visible') && $(this).attr('required')) {
                        if (!form.validate().element(this)) {
                            isValid = false;
                        }
                    }
                });

                // Special validation for dynamic school fields in step 2
                if (step === 2) {
                    const usageStatus = document.querySelector('input[name="usage_status"]:checked')?.value;
                    if (usageStatus) {
                        const visibleCase = document.getElementById(`case-${usageStatus}`);
                        if (visibleCase && visibleCase.style.display !== 'none') {
                            const schoolFields = $(visibleCase).find('input[required]');
                            schoolFields.each(function() {
                                if (!form.validate().element(this)) {
                                    isValid = false;
                                }
                            });
                        }
                    }
                }

                return isValid;
            }

            nextStep() {
                if (!this.validateStep(this.currentStep)) {
                    alert('Please fill in all required fields before proceeding.');
                    return;
                }
            
                if (this.currentStep < this.totalSteps) {
                    this.currentStep++;
                    this.updateStepDisplay();
                    
                    // Generate dynamic content for specific steps
                    if (this.currentStep === 3) {
                        const usageStatus = document.querySelector('input[name="usage_status"]:checked');
                        if (!usageStatus) {
                            alert('Please select a usage status before proceeding.');
                            this.currentStep--;
                            this.updateStepDisplay();
                            return;
                        }
                        this.generateSubscriptionContent();
                    } else if (this.currentStep === 4) {
                        this.generateAccountSummary();
                    }
                }
            }

            prevStep() {
                if (this.currentStep > 1) {
                    this.currentStep--;
                    this.updateStepDisplay();
                }
            }

            updateStepDisplay() {
                console.log('updateStepDisplay called, currentStep:', this.currentStep);
                
                // Update step indicator
                document.querySelectorAll('.wizard-modal .step').forEach((step, index) => {
                    const stepNum = index + 1;
                    step.classList.remove('active', 'completed');
                    
                    if (stepNum < this.currentStep) {
                        step.classList.add('completed');
                    } else if (stepNum === this.currentStep) {
                        step.classList.add('active');
                    }
                });

                // Update step content - be more selective
                document.querySelectorAll('.wizard-modal .step-content').forEach((content, index) => {
                    const stepNum = index + 1;
                    if (stepNum === this.currentStep) {
                        content.classList.add('active');
                        console.log('Showing step', stepNum);
                    } else {
                        content.classList.remove('active');
                    }
                });

                // Update progress bar
                const progress = (this.currentStep / this.totalSteps) * 100;
                document.querySelector('.wizard-modal .progress-bar').style.width = `${progress}%`;

                // Update navigation buttons
                document.getElementById('prevBtn').style.display = this.currentStep > 1 ? 'block' : 'none';
                document.getElementById('nextBtn').style.display = this.currentStep < this.totalSteps ? 'block' : 'none';
                document.getElementById('submitBtn').style.display = this.currentStep === this.totalSteps ? 'block' : 'none';

                // Update step counter
                document.getElementById('currentStep').textContent = this.currentStep;
            }

            submitForm() {
                if (!this.validateStep(this.currentStep)) {
                    this.showAlert('Please complete all required fields correctly.', 'warning');
                    return;
                }

                const submitBtn = document.getElementById('submitBtn');
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Creating Account...';
                submitBtn.disabled = true;

                // Remove any existing alerts
                $('.wizard-modal .alert').remove();

                // Prepare form data
                const formData = new FormData(document.getElementById('onboardingForm'));
                formData.append('_token', '{{ csrf_token() }}');

                // Submit form to server
                fetch('{{ route("onboarding.submit") }}', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        this.showAlert(`
                            <strong>Welcome to ShuleSoft Group Connect!</strong><br>
                            ${data.message}<br>
                            <small class="text-muted">Redirecting you to login page in 3 seconds...</small>
                        `, 'success');
                        
                        // Redirect after 3 seconds
                        setTimeout(() => {
                            window.location.href = data.redirect || '/login';
                        }, 3000);
                    } else {
                        this.showAlert(data.message || 'An error occurred. Please try again.', 'danger');
                        this.resetSubmitButton();
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    this.showAlert('A network error occurred. Please check your connection and try again.', 'danger');
                    this.resetSubmitButton();
                });
            }

            showAlert(message, type = 'info') {
                const iconMap = {
                    'success': 'fas fa-check-circle',
                    'danger': 'fas fa-exclamation-triangle',
                    'warning': 'fas fa-exclamation-circle',
                    'info': 'fas fa-info-circle'
                };

                const alertHtml = `
                    <div class="alert alert-${type} alert-dismissible fade show" role="alert" style="margin: 15px;">
                        <i class="${iconMap[type]} me-2"></i>
                        ${message}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                `;

                $('.wizard-modal .modal-body').prepend(alertHtml);
                
                // Auto-dismiss info and warning alerts after 5 seconds
                if (type === 'info' || type === 'warning') {
                    setTimeout(() => {
                        $('.wizard-modal .alert').fadeOut();
                    }, 5000);
                }
            }

            resetSubmitButton() {
                const submitBtn = document.getElementById('submitBtn');
                submitBtn.innerHTML = '<i class="fas fa-user-plus me-2"></i>Create Account';
                submitBtn.disabled = false;
            }
        }

        // Initialize wizard when DOM is ready
        let onboardingWizard;
        
        document.addEventListener('DOMContentLoaded', function() {
            console.log('DOM fully loaded, initializing OnboardingWizard...');
            onboardingWizard = new OnboardingWizard();
            window.onboardingWizard = onboardingWizard;
            console.log('OnboardingWizard initialization complete');
            
            // Also add event listener for when the modal is shown
            const wizardModal = document.getElementById('onboardingWizard');
            if (wizardModal) {
                wizardModal.addEventListener('shown.bs.modal', function () {
                    console.log('Onboarding modal shown, checking step content visibility...');
                    const activeContent = document.querySelector('.wizard-modal .step-content.active');
                    console.log('Active step content found:', activeContent);
                    if (activeContent) {
                        console.log('Active content display style:', window.getComputedStyle(activeContent).display);
                        console.log('Active content innerHTML length:', activeContent.innerHTML.length);
                    }
                    if (onboardingWizard) {
                        onboardingWizard.updateStepDisplay();
                    }
                });
            }
        });
     

    </script>
</body>
</html>
