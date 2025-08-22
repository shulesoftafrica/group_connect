# ShuleSoft Group Connect – Requirements Specification

This document outlines the requirements for building **ShuleSoft Group Connect**, a centralized, responsive web application for owners managing multiple schools. The platform aggregates group-level data and enables cross-school actions.

---

## 1. User Roles & Permissions

### 1.1 Roles
- **Owner**: Full access to all strategic, operational, and financial data.
- **Group Accountant**: Access to finance-related dashboards and reports only.
- **Group IT Officer**: Access to system usage and technical dashboards only.
- **Central Super Admin**: Manages users and schools within the group.
- **Group Academic**: Manages academic related dashboards and reports only.
- **--- Additional Roles (e.g., Principal, Teacher, etc.) ---**

### 1.2 Permissions
- Role-based access control (RBAC) to restrict data and actions per role.
- Centralized permission management by Super Admin.
- Users see only schools assigned to them.


## 2. Authentication & User Management

### 2.1 Single Sign-On (SSO)
- Central login page for Group Connect.
- SSO integration with ShuleSoft for seamless access to individual schools.

### 2.2 User Onboarding
- Super Admin can add users (name, email, role, assigned schools).
- Email invitation with password setup.
- --- Password policy details ---

### 2.3 School Onboarding
- **Add Existing School**: Link by entering ShuleSoft School Code.
- **Add New School**: Request creation via form (school name, location, contact, etc.).
- --- School data fields required ---


## 3. Main Navigation & Layout

### 3.1 Top-Level Menus (Owner View)
- Dashboard
- Schools Overview
- Academics
- Operations
- Finance & Accounts
- Human Resources
- Communications
- Digital Learning
- Settings & Control Panel
- Reports & Insights

#### Sub-menus
- Each menu reveals sub-menus with aggregated group data and drill-down to individual schools.

### 3.2 Role-Based Menus
- Menus adapt based on user role (see Accountant and IT Officer sections below).

---

## 4. Dashboards

### 4.1 Group Overview Dashboard (Owner)
- Summary KPIs (aggregated, filterable by date/region):
    - Total Students Enrolled
    - Average Attendance (Students & Staff)
    - Fees Collected vs. Outstanding
    - Top/Low Performing Schools (Academics & Revenue)
    - Payroll Summary
    - Budget Approval Pending
    - Active Communication Campaigns
- Interactive Map: All schools with hover stats.
- Trend Graphs: Enrollment, fee collection, academic performance.
- Quick Actions: Approve budgets, send group messages, push settings.

#### Owner Dashboard – Essential Reports

- **Group Financial Overview:**
    - Consolidated revenue and expenditure report (all schools, with drill-down)
    - Fees collection vs. outstanding balances by school and region
    - Profitability analysis per school and group-wide
    - Budget vs. actual spending summaries

- **Academic Performance:**
    - Group-wide academic performance summary (average grades, pass rates)
    - Top and bottom performing schools and subjects
    - Student progression and retention rates across the group

- **Attendance & HR:**
    - Attendance trends (students and staff) by school and region
    - Staff headcount, turnover, and leave summaries
    - Payroll summary and HR compliance status

- **Operational Efficiency:**
    - School operational status (transport, hostel, library usage)
    - Resource utilization and requests pending approval

- **Communications & Engagement:**
    - Group-wide communication campaign effectiveness
    - Feedback and response rates from schools

- **Alerts & Exceptions:**
    - Schools with declining performance or financial health
    - Outstanding compliance or reporting issues
    - Pending approvals (budgets, policies, requests)

- **Comparative & Trend Reports:**
    - Year-on-year and term-on-term comparisons (enrollment, revenue, performance)
    - Regional performance heatmaps and trends

- **Export & Sharing:**
    - Downloadable summary and detailed reports (Excel/PDF)
    - Scheduled email reports to stakeholders



### 4.2 Accountant Dashboard
- KPIs: Total Revenue, Outstanding Fees, Expenses, Payroll, Petty Cash.
- Visuals: Bar (fees by school), Pie (revenue by category), Trend (monthly revenue/expenses).
- Alerts: Schools with high outstanding fees, over-budget, missing reports.
#### Accountant Dashboard – Essential Reports

- **Group Financial Overview:**
    - Consolidated revenue and expenditure report (all schools, with drill-down)
    - Fees collection vs. outstanding balances by school and region
    - Profitability analysis per school and group-wide
    - Budget vs. actual spending summaries

- **Fees & Collections:**
    - School-wise and region-wise fee collection rates
    - Outstanding balances and overdue accounts
    - Payment trends and collection efficiency
    - Top fee defaulters and recovery status

- **Expenses & Budgeting:**
    - Expense breakdown by category (payroll, utilities, supplies, etc.)
    - Budget allocation and utilization per school and group
    - Over-budget alerts and variance analysis

- **Payroll & HR Costs:**
    - Payroll summary by school and group
    - Staff cost as a percentage of revenue
    - Payroll compliance and pending payments

- **Petty Cash & Transactions:**
    - Petty cash balances and usage by school
    - Transaction logs and approval status

- **Assets & Liabilities:**
    - Asset register and depreciation summary
    - Liabilities and outstanding obligations

- **Comparative & Trend Reports:**
    - Year-on-year and term-on-term financial comparisons
    - Revenue and expense trends across schools and regions

- **Alerts & Exceptions:**
    - Schools with high outstanding fees or over-budget spending
    - Missing or delayed financial reports
    - Unusual transactions or anomalies

- **Export & Sharing:**
    - Downloadable summary and detailed reports (Excel/PDF)
    - Scheduled email reports to stakeholders

These reports enable the accountant to monitor financial health, ensure compliance, and support informed financial decision-making across the group.

### 4.3 IT Officer Dashboard
- KPIs: Active users (by role), logins, most used modules, low activity schools.
- Visuals: Bar (active users/school), Heatmap (login times), Pie (module usage).
- Alerts: Inactive schools, failed logins, low communication activity.
#### IT Officer Dashboard – Essential Reports

- **System Usage Overview:**
    - Active users by school, role, and time period
    - Login frequency and peak usage times
    - Most and least used modules/features
    - Device and browser usage statistics

- **User Activity & Access:**
    - User login/logout logs (with timestamps and IP addresses)
    - Failed login attempts and account lockouts
    - Inactive users and schools (no logins in X days)
    - Recent password changes and resets

- **Module & Feature Analytics:**
    - Usage heatmaps for key modules (attendance, finance, academics, etc.)
    - Feature adoption rates across schools
    - Schools with low or no usage of critical modules

- **Communication & Notification Monitoring:**
    - Group and school-level communication activity (SMS, email, in-app)
    - Delivery and failure rates for messages
    - Schools with low communication engagement

- **System Health & Performance:**
    - API response times and error rates
    - Uptime/downtime logs for integrated services
    - Recent system updates and deployments

- **Security & Compliance:**
    - Audit logs for sensitive actions (user management, permissions changes)
    - Suspicious activity alerts (multiple failed logins, unusual access patterns)
    - Schools/users with outdated software or browsers

- **Support & Requests:**
    - Open and resolved IT support tickets by school
    - Common issues and resolution times
    - Pending integration or access requests

- **Alerts & Exceptions:**
    - Schools with persistent inactivity or technical issues
    - Failed integrations or data sync errors
    - Unusual spikes in login failures or access requests

- **Export & Sharing:**
    - Downloadable activity and usage reports (Excel/PDF)
    - Scheduled system health and usage summaries to IT stakeholders

These reports enable the IT Officer to monitor system adoption, ensure security and compliance, identify technical issues early, and support schools in maximizing the value of ShuleSoft Group Connect.

### 4.4 Academic Master Dashboard
#### Academic Master Dashboard

- **KPIs & Summaries:**
    - Group-wide average grades and pass rates
    - Top and bottom performing schools, subjects, and classes
    - Student progression and retention rates
    - Attendance rates (students and teachers)
    - Exam completion and grading turnaround times
    - Number of remedial/intervention programs running

- **Visualizations:**
    - Trend graphs: Academic performance over time, attendance trends, subject-wise performance
    - Heatmaps: Performance by region, school, or subject
    - Comparative charts: School vs. group averages, class performance comparisons

- **Reports:**
    - School-wise and class-wise academic performance reports
    - Subject performance analysis (top/bottom subjects, improvement areas)
    - Teacher performance and workload reports
    - Student at-risk lists (low grades, chronic absenteeism)
    - Remedial program effectiveness reports
    - Exam analysis (pass/fail rates, grade distributions, question/item analysis)
    - Curriculum coverage and completion status

- **Quick Actions:**
    - Push academic policies or curriculum updates to all/selected schools
    - Schedule or assign group-wide assessments
    - Initiate intervention programs for underperforming schools/classes
    - Communicate with academic heads or teachers across schools

- **Alerts & Notifications:**
    - Schools/classes with declining performance
    - Subjects with consistently low pass rates
    - Delayed grading or report submissions
    - Attendance below threshold

- **Export & Sharing:**
    - Download reports in Excel/PDF
    - Share dashboards with school academic heads


## 5. Key Functional Modules

### 5.1 Schools Overview
- Aggregated list: Name, location, student count, fee collection %, academic index, attendance, quick actions.
- Filters: Region, performance tier, school type.

### 5.2 Academics
- Reports: Average grades, pass/fail rates, top/bottom subjects/schools.
- Drill-down: School → Class → Student.
- Bulk actions: Upload schedules, update grading, push policies.

### 5.3 Operations
- Attendance summary (students/staff), transport, hostel, library stats.
- Push routines, approve operational requests.

### 5.4 Finance & Accounts
- Aggregated fee collection, revenue, expenses, invoices, budgets.
- Profitability, expense breakdown, planning tools.
- Group-wide financial settings.

### 5.5 Human Resources
- Staff count by role, leave, payroll, HR policies, hiring requests.

### 5.6 Communications
- Send SMS/Email/Letters to all/selected schools.
- Templates, campaign tracking, feedback aggregation.

### 5.7 Digital Learning
- E-learning usage, online exam stats, bulk material push.

### 5.8 Settings & Control Panel
- Academic year/term, grading rules, templates, access control, instant updates.

### 5.9 Reports & Insights
- Pre-built reports: Academic, financial, attendance, operations.
- AI-powered insights, anomaly detection.

---

## 6. Accountant-Specific Features

- Menu: Dashboard, Finance Reports, Fees Summary, Outstanding Balances, Revenue vs Expenditure, Petty Cash, Budget vs Actual, Multi-School Comparison, Assets & Liabilities, Payroll, Settings.
- Drill-down: School, term, year, transaction.
- Export: PDF/Excel.
- Alerts: Outstanding fees, over-budget, missing reports.
- Scheduled email reports.

---

## 7. IT Officer-Specific Features

- Menu: Dashboard, System Usage, User Activity, Module Usage, Login Trends, Communication Monitoring, Device & Access Tracking, Support Requests, Settings.
- Drill-down: School → User.
- Export: PDF/Excel.
- Alerts: Inactivity, failed logins, low usage.
- Scheduled reports.

---

## 8. Common Functionalities

- Single login for all assigned schools.
- Cross-school reporting and drill-down.
- Bulk actions (approve, message, update settings).
- Automated alerts (underperformance, missed targets).
- Custom filters (region, type, performance).
- Integration with existing ShuleSoft accounts.
- Export/share reports (Excel/PDF, email).
- Color-coded indicators for activity/health.

---

## 9. Technical & UI Requirements

- Responsive web application (desktop, tablet, mobile).
- Modern UI/UX (--- preferred framework/library ---).
- Secure authentication and data handling.
- --- API integration details with ShuleSoft ---
- --- Hosting/deployment requirements ---
- --- Accessibility standards ---

---

## 10. Open Questions / To Be Defined

- --- List of all user roles and their permissions ---
- --- Final list of KPIs and data fields per module ---
- --- Data sources and integration endpoints ---
- --- Notification and alerting rules ---
- --- Branding and color scheme ---
- --- Any additional modules or features ---

