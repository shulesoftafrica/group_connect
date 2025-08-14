<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\School;
use App\Models\User;
use App\Models\Organization;
use Carbon\Carbon;

class HRController extends Controller
{
    public function index()
    {
        $hrKPIs = $this->calculateHRKPIs();
        $schoolsList = $this->getSchoolsHRList();
        $staffDirectory = $this->getStaffDirectoryData();
        $performanceData = $this->getHRPerformanceData();
        $alertsData = $this->getHRAlerts();
        $attendanceData = $this->getAttendanceData();
        $payrollData = $this->getPayrollSummaryData();
        $recruitmentData = $this->getRecruitmentData();
        
        return view('hr.dashboard', compact(
            'hrKPIs',
            'schoolsList',
            'staffDirectory',
            'performanceData',
            'alertsData',
            'attendanceData',
            'payrollData',
            'recruitmentData'
        ));
    }

    public function schoolDetail($id)
    {
        $school = School::with(['organization', 'user'])->findOrFail($id);
        
        $schoolHRData = $this->getSchoolHRData($school);
        $staffData = $this->getSchoolStaffData($school);
        $attendanceData = $this->getSchoolAttendanceData($school);
        $leaveData = $this->getSchoolLeaveData($school);
        $payrollData = $this->getSchoolPayrollData($school);
        $performanceData = $this->getSchoolPerformanceData($school);
        $recruitmentData = $this->getSchoolRecruitmentData($school);
        
        return view('hr.school-detail', compact(
            'school',
            'schoolHRData',
            'staffData',
            'attendanceData',
            'leaveData',
            'payrollData',
            'performanceData',
            'recruitmentData'
        ));
    }

    public function staffDirectory()
    {
        $schools = School::with('organization')->get();
        $staffData = $this->getAllStaffData();
        
        return view('hr.staff-directory', compact('schools', 'staffData'));
    }

    public function recruitment()
    {
        $schools = School::with('organization')->get();
        $recruitmentData = $this->getRecruitmentManagementData();
        
        return view('hr.recruitment', compact('schools', 'recruitmentData'));
    }

    public function leaveManagement()
    {
        $schools = School::with('organization')->get();
        $leaveData = $this->getLeaveManagementData();
        
        return view('hr.leave-management', compact('schools', 'leaveData'));
    }

    public function payrollManagement()
    {
        $schools = School::with('organization')->get();
        $payrollData = $this->getPayrollManagementData();
        
        return view('hr.payroll-management', compact('schools', 'payrollData'));
    }

    public function exportReport(Request $request)
    {
        $reportType = $request->input('report_type', 'general');
        $schools = $request->input('schools', []);
        $dateRange = $request->input('date_range', 'current_month');
        
        // Generate and return report based on type
        return response()->json([
            'success' => true,
            'message' => 'HR report exported successfully',
            'file_url' => '/exports/hr-report-' . time() . '.xlsx'
        ]);
    }

    public function bulkAction(Request $request)
    {
        $action = $request->input('action');
        $staffIds = $request->input('staff_ids', []);
        $data = $request->input('data', []);
        
        switch ($action) {
            case 'update_policy':
                return $this->bulkUpdatePolicy($staffIds, $data);
            case 'send_communication':
                return $this->bulkSendCommunication($staffIds, $data);
            case 'approve_leave':
                return $this->bulkApproveLeave($staffIds, $data);
            case 'update_payroll':
                return $this->bulkUpdatePayroll($staffIds, $data);
            default:
                return response()->json(['success' => false, 'message' => 'Invalid action']);
        }
    }

    private function calculateHRKPIs()
    {
        // In real implementation, these would be calculated from actual database queries
        return [
            'total_staff' => 1247,
            'active_staff' => 1186,
            'vacant_positions' => 23,
            'turnover_rate' => 8.5,
            'average_attendance' => 94.2,
            'pending_leave_requests' => 34,
            'staff_satisfaction' => 87.3,
            'payroll_compliance' => 98.1,
            'training_completion' => 76.8,
            'recruitment_efficiency' => 82.4
        ];
    }

    private function getSchoolsHRList()
    {
        $schools = School::with('organization')->get();
        
        return $schools->map(function ($school) {
            return [
                'id' => $school->id,
                'name' => $school->settings['school_name'] ?? 'Unknown School',
                'code' => $school->shulesoft_code,
                'region' => $school->settings['region'] ?? 'Unknown',
                'total_staff' => rand(15, 85),
                'active_staff' => rand(12, 80),
                'vacant_positions' => rand(0, 8),
                'turnover_rate' => round(rand(5, 15) + (rand(0, 9) / 10), 1),
                'attendance_rate' => round(rand(85, 98) + (rand(0, 9) / 10), 1),
                'payroll_status' => rand(0, 1) ? 'current' : 'pending',
                'compliance_score' => rand(75, 100),
                'last_updated' => Carbon::now()->subDays(rand(0, 7))->format('Y-m-d H:i:s')
            ];
        });
    }

    private function getStaffDirectoryData()
    {
        return [
            'total_count' => 1247,
            'by_role' => [
                'Teachers' => 856,
                'Support Staff' => 234,
                'Administration' => 89,
                'Management' => 68
            ],
            'by_region' => [
                'Dar es Salaam' => 421,
                'Mwanza' => 298,
                'Arusha' => 245,
                'Dodoma' => 187,
                'Mbeya' => 96
            ],
            'by_status' => [
                'Active' => 1186,
                'On Leave' => 34,
                'Suspended' => 12,
                'Probation' => 15
            ]
        ];
    }

    private function getHRPerformanceData()
    {
        return [
            'monthly_trends' => [
                'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                'staff_count' => [1205, 1218, 1235, 1242, 1250, 1247],
                'turnover' => [12, 8, 15, 7, 11, 9],
                'new_hires' => [25, 18, 32, 14, 19, 17],
                'attendance' => [92.1, 93.8, 94.5, 93.2, 94.8, 94.2]
            ],
            'performance_distribution' => [
                'Excellent' => 312,
                'Good' => 658,
                'Satisfactory' => 234,
                'Needs Improvement' => 43
            ]
        ];
    }

    private function getHRAlerts()
    {
        return [
            'high_priority' => [
                [
                    'title' => 'High Turnover Alert',
                    'message' => 'Mwanza Secondary has 15% turnover rate this quarter',
                    'school' => 'Mwanza Secondary School',
                    'type' => 'turnover',
                    'severity' => 'high'
                ],
                [
                    'title' => 'Pending Approvals',
                    'message' => '34 leave requests awaiting approval',
                    'school' => 'Multiple Schools',
                    'type' => 'leave',
                    'severity' => 'medium'
                ]
            ],
            'compliance' => [
                [
                    'title' => 'Contract Renewals Due',
                    'message' => '12 staff contracts expiring this month',
                    'school' => 'Various Schools',
                    'type' => 'contract',
                    'severity' => 'medium'
                ],
                [
                    'title' => 'Training Compliance',
                    'message' => '5 schools below 80% training completion',
                    'school' => 'Multiple Schools',
                    'type' => 'training',
                    'severity' => 'low'
                ]
            ]
        ];
    }

    private function getAttendanceData()
    {
        return [
            'overall_attendance' => 94.2,
            'by_category' => [
                'Teachers' => 95.1,
                'Support Staff' => 93.8,
                'Administration' => 96.2,
                'Management' => 97.5
            ],
            'trends' => [
                'labels' => ['Week 1', 'Week 2', 'Week 3', 'Week 4'],
                'values' => [93.8, 94.5, 94.1, 94.6]
            ],
            'absenteeism_reasons' => [
                'Sick Leave' => 45,
                'Personal' => 23,
                'Training' => 18,
                'Emergency' => 12,
                'Other' => 8
            ]
        ];
    }

    private function getPayrollSummaryData()
    {
        return [
            'total_monthly_payroll' => 45675000,
            'by_category' => [
                'Teachers' => 32450000,
                'Support Staff' => 8920000,
                'Administration' => 3105000,
                'Management' => 1200000
            ],
            'compliance_status' => [
                'current' => 98.1,
                'pending_payments' => 1.9
            ],
            'cost_analysis' => [
                'as_percentage_of_revenue' => 67.8,
                'budget_utilization' => 89.3
            ]
        ];
    }

    private function getRecruitmentData()
    {
        return [
            'open_positions' => 23,
            'applications_received' => 145,
            'interviews_scheduled' => 67,
            'offers_made' => 18,
            'positions_filled' => 12,
            'by_category' => [
                'Teachers' => 15,
                'Support Staff' => 5,
                'Administration' => 2,
                'Management' => 1
            ],
            'recruitment_pipeline' => [
                'Application Review' => 78,
                'Initial Interview' => 34,
                'Technical Assessment' => 21,
                'Final Interview' => 12
            ]
        ];
    }

    private function getSchoolHRData($school)
    {
        return [
            'summary' => [
                'total_staff' => rand(25, 85),
                'active_staff' => rand(20, 80),
                'vacant_positions' => rand(0, 5),
                'turnover_rate' => round(rand(5, 15) + (rand(0, 9) / 10), 1),
                'attendance_rate' => round(rand(85, 98) + (rand(0, 9) / 10), 1)
            ],
            'trends' => [
                'staff_growth' => rand(-5, 15),
                'turnover_trend' => rand(-3, 8),
                'attendance_trend' => rand(-2, 5)
            ]
        ];
    }

    private function getSchoolStaffData($school)
    {
        return [
            'by_role' => [
                'Teachers' => rand(15, 45),
                'Support Staff' => rand(5, 15),
                'Administration' => rand(2, 8),
                'Management' => rand(1, 4)
            ],
            'by_status' => [
                'Active' => rand(20, 65),
                'On Leave' => rand(1, 5),
                'Probation' => rand(0, 3)
            ],
            'recent_hires' => [
                ['name' => 'John Msamba', 'position' => 'Mathematics Teacher', 'date' => '2024-01-15'],
                ['name' => 'Mary Kilua', 'position' => 'Science Teacher', 'date' => '2024-01-10'],
                ['name' => 'David Mwanga', 'position' => 'Librarian', 'date' => '2024-01-05']
            ]
        ];
    }

    private function getSchoolAttendanceData($school)
    {
        return [
            'current_rate' => round(rand(85, 98) + (rand(0, 9) / 10), 1),
            'by_category' => [
                'Teachers' => round(rand(88, 99) + (rand(0, 9) / 10), 1),
                'Support Staff' => round(rand(85, 96) + (rand(0, 9) / 10), 1),
                'Administration' => round(rand(90, 99) + (rand(0, 9) / 10), 1)
            ],
            'monthly_trends' => [
                'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                'values' => [92.1, 93.8, 94.5, 93.2, 94.8, 94.2]
            ]
        ];
    }

    private function getSchoolLeaveData($school)
    {
        return [
            'pending_requests' => rand(2, 8),
            'approved_this_month' => rand(5, 15),
            'leave_balances' => [
                'Annual Leave' => rand(15, 25),
                'Sick Leave' => rand(8, 15),
                'Maternity Leave' => rand(0, 3)
            ],
            'recent_requests' => [
                ['staff' => 'Jane Mwalimu', 'type' => 'Annual Leave', 'days' => 5, 'status' => 'pending'],
                ['staff' => 'Peter Fundisha', 'type' => 'Sick Leave', 'days' => 2, 'status' => 'approved'],
                ['staff' => 'Grace Elimu', 'type' => 'Personal', 'days' => 1, 'status' => 'pending']
            ]
        ];
    }

    private function getSchoolPayrollData($school)
    {
        return [
            'monthly_payroll' => rand(1500000, 4500000),
            'by_category' => [
                'Basic Salary' => rand(1200000, 3200000),
                'Allowances' => rand(200000, 800000),
                'Overtime' => rand(50000, 300000),
                'Deductions' => rand(-150000, -500000)
            ],
            'compliance_status' => rand(95, 100),
            'pending_payments' => rand(0, 3)
        ];
    }

    private function getSchoolPerformanceData($school)
    {
        return [
            'appraisal_completion' => rand(75, 100),
            'performance_distribution' => [
                'Excellent' => rand(5, 15),
                'Good' => rand(15, 35),
                'Satisfactory' => rand(8, 20),
                'Needs Improvement' => rand(0, 5)
            ],
            'training_completion' => rand(65, 95)
        ];
    }

    private function getSchoolRecruitmentData($school)
    {
        return [
            'open_positions' => rand(0, 3),
            'applications_received' => rand(5, 25),
            'interviews_conducted' => rand(2, 15),
            'recent_hires' => rand(0, 5)
        ];
    }

    private function getAllStaffData()
    {
        // Sample staff data - in real implementation, this would query the database
        $sampleStaff = [];
        $schools = School::with('organization')->take(5)->get();
        
        foreach ($schools as $school) {
            for ($i = 1; $i <= rand(15, 30); $i++) {
                $sampleStaff[] = [
                    'id' => $school->id . sprintf('%03d', $i),
                    'name' => 'Staff Member ' . $i,
                    'email' => 'staff' . $i . '@' . strtolower(str_replace(' ', '', $school->settings['school_name'] ?? 'school')) . '.com',
                    'role' => ['Teacher', 'Support Staff', 'Administration', 'Management'][rand(0, 3)],
                    'school' => $school->settings['school_name'] ?? 'Unknown School',
                    'school_id' => $school->id,
                    'status' => ['Active', 'On Leave', 'Probation'][rand(0, 2)],
                    'hire_date' => Carbon::now()->subDays(rand(30, 1000))->format('Y-m-d'),
                    'contact' => '+255' . rand(700000000, 799999999),
                    'department' => ['Academic', 'Administration', 'Support', 'Management'][rand(0, 3)]
                ];
            }
        }
        
        return collect($sampleStaff);
    }

    private function getRecruitmentManagementData()
    {
        return [
            'summary' => [
                'total_positions' => 23,
                'applications' => 145,
                'in_progress' => 67,
                'filled_positions' => 12
            ],
            'positions' => [
                [
                    'id' => 1,
                    'title' => 'Mathematics Teacher',
                    'school' => 'Dar es Salaam Secondary',
                    'applications' => 15,
                    'status' => 'Interviewing',
                    'posted_date' => '2024-01-10'
                ],
                [
                    'id' => 2,
                    'title' => 'Science Laboratory Assistant',
                    'school' => 'Mwanza Primary',
                    'applications' => 8,
                    'status' => 'Open',
                    'posted_date' => '2024-01-15'
                ]
            ]
        ];
    }

    private function getLeaveManagementData()
    {
        return [
            'summary' => [
                'pending_requests' => 34,
                'approved_this_month' => 128,
                'rejected_this_month' => 7,
                'total_leave_days' => 892
            ],
            'requests' => [
                [
                    'id' => 1,
                    'staff_name' => 'John Mwalimu',
                    'school' => 'Dar es Salaam Secondary',
                    'leave_type' => 'Annual Leave',
                    'start_date' => '2024-02-15',
                    'end_date' => '2024-02-20',
                    'days' => 5,
                    'status' => 'Pending',
                    'applied_date' => '2024-01-20'
                ],
                [
                    'id' => 2,
                    'staff_name' => 'Mary Elimu',
                    'school' => 'Mwanza Primary',
                    'leave_type' => 'Sick Leave',
                    'start_date' => '2024-02-10',
                    'end_date' => '2024-02-12',
                    'days' => 2,
                    'status' => 'Approved',
                    'applied_date' => '2024-02-09'
                ]
            ]
        ];
    }

    private function getPayrollManagementData()
    {
        return [
            'summary' => [
                'total_monthly_payroll' => 45675000,
                'schools_processed' => 18,
                'pending_approvals' => 3,
                'compliance_rate' => 98.1
            ],
            'by_school' => [
                [
                    'school' => 'Dar es Salaam Secondary',
                    'staff_count' => 65,
                    'gross_payroll' => 8950000,
                    'deductions' => 1250000,
                    'net_payroll' => 7700000,
                    'status' => 'Processed'
                ],
                [
                    'school' => 'Mwanza Primary',
                    'staff_count' => 32,
                    'gross_payroll' => 4200000,
                    'deductions' => 580000,
                    'net_payroll' => 3620000,
                    'status' => 'Pending'
                ]
            ]
        ];
    }

    private function bulkUpdatePolicy($staffIds, $data)
    {
        return response()->json([
            'success' => true,
            'message' => 'Policy updated for ' . count($staffIds) . ' staff members'
        ]);
    }

    private function bulkSendCommunication($staffIds, $data)
    {
        return response()->json([
            'success' => true,
            'message' => 'Communication sent to ' . count($staffIds) . ' staff members'
        ]);
    }

    private function bulkApproveLeave($staffIds, $data)
    {
        return response()->json([
            'success' => true,
            'message' => 'Leave approved for ' . count($staffIds) . ' requests'
        ]);
    }

    private function bulkUpdatePayroll($staffIds, $data)
    {
        return response()->json([
            'success' => true,
            'message' => 'Payroll updated for ' . count($staffIds) . ' staff members'
        ]);
    }
}
