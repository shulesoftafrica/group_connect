<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\School;
use App\Models\User;
use App\Models\Organization;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;


class CommunicationsController extends Controller
{
   

    public function dashboard()
    {
            $user = Auth::user();
            $dashboard = app()->make(\App\Http\Controllers\DashboardController::class);
            $schools = $dashboard->getUserSchools($user);
            $this->schemaNames = $dashboard->getSchemaNames($schools);

        $data = $this->calculateCommunicationKPIs();
        $data['schools'] = $this->getSchoolsCommunicationList($schools);
        $data['campaigns'] = $this->getActiveCampaigns();
        $data['recentMessages'] = $this->getRecentMessages();
        $data['communication_trends'] = $this->getCommunicationTrends();
        
        return view('communications.dashboard', $data);
    }

    public function campaigns()
    {
        $campaigns = $this->getCampaignsList();
        return view('communications.campaigns', compact('campaigns'));
    }

    public function messaging()
    {
        $schools = School::all();
        $messageTemplates = $this->getMessageTemplates();
        return view('communications.messaging', compact('schools', 'messageTemplates'));
    }

    public function getTrendsData(Request $request)
    {
        $user = Auth::user();
        $dashboard = app()->make(\App\Http\Controllers\DashboardController::class);
        $schools = $dashboard->getUserSchools($user);
        $this->schemaNames = $dashboard->getSchemaNames($schools);

        $period = $request->get('period', '6months'); // 6months, 3months, 1year
        
        return response()->json($this->getCommunicationTrendsByPeriod($period));
    }

    public function analytics()
    {
        $data = $this->getCommunicationAnalytics();
        return view('communications.analytics', $data);
    }

    public function feedback()
    {
        $feedback = $this->getFeedbackSummary();
        return view('communications.feedback', compact('feedback'));
    }

    public function sendMessage(Request $request)
    {
        // Handle message sending logic
        $request->validate([
            'message_type' => 'required|in:sms,email,whatsapp',
            'recipients' => 'required',
            'subject' => 'required_if:message_type,email',
            'message' => 'required',
        ]);

        // In a real implementation, integrate with SMS/Email/WhatsApp APIs
        // For now, we'll simulate the sending and store the campaign data

        return response()->json([
            'success' => true,
            'message' => 'Message sent successfully to ' . count($request->recipients) . ' recipients'
        ]);
    }

    public function createCampaign(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:announcement,survey,alert,reminder',
            'target_audience' => 'required',
            'message' => 'required',
        ]);

        // Create campaign logic here
        return response()->json(['success' => true, 'message' => 'Campaign created successfully']);
    }

    private function calculateCommunicationKPIs()
    {
        // Sample data - in real implementation, fetch from actual communication logs
        // Get actual data from database
        $totalMessages = DB::table('shulesoft.sms')
            ->whereIn('schema_name', $this->schemaNames)
            ->count();
            
        $messagesThisMonth = DB::table('shulesoft.sms')
            ->whereIn('schema_name', $this->schemaNames)
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();
            
        $deliveredMessages = DB::table('shulesoft.sms')
            ->whereIn('schema_name', $this->schemaNames)
            ->where('status', 1)
            ->count();
            
        $pendingMessages = DB::table('shulesoft.sms')
            ->whereIn('schema_name', $this->schemaNames)
            ->where('status', 0)
            ->count();
            
        $failedMessages = DB::table('shulesoft.sms')
            ->whereIn('schema_name', $this->schemaNames)
            ->where('status', 3)
            ->count();
            
        $qsmsCount = DB::table('shulesoft.sms')
            ->whereIn('schema_name', $this->schemaNames)
            ->where('sent_from', 'quicksms')
            ->count();

             $psmsCount = DB::table('shulesoft.sms')
            ->whereIn('schema_name', $this->schemaNames)
             ->whereRaw('LOWER(sent_from) = ?', ['phone-sms'])
            ->count();
            
        $emailCount = DB::table('shulesoft.sms')
            ->whereIn('schema_name', $this->schemaNames)
            ->whereRaw('LOWER(sent_from) = ?', ['email'])
            ->count();
            
        $whatsappCount = DB::table('shulesoft.sms')
            ->whereIn('schema_name', $this->schemaNames)
            ->whereRaw('LOWER(sent_from) = ?', ['whatsapp'])
            ->count();
            
        $schoolsReached = DB::table('shulesoft.sms')
            ->whereIn('schema_name', $this->schemaNames)
            ->distinct('schema_name')
            ->count();
            
        $deliveryRate = $totalMessages > 0 ? round(($deliveredMessages / $totalMessages) * 100, 1) : 0;
         
        $feedbackCount = DB::table('shulesoft.exam_comments')
                ->whereIn('schema_name', $this->schemaNames)
                ->count() + 
                DB::table('constant.feedback')
                ->whereIn('schema', $this->schemaNames)
                ->count();
                
            $engagementRate = $totalMessages > 0 ? round(($feedbackCount / $totalMessages) * 100, 1) : 0;

        return [
            'total_messages_sent' => $totalMessages,
            'messages_this_month' => $messagesThisMonth,
            'active_campaigns' => DB::table('shulesoft.sms_content')
                ->whereIn('schema_name', $this->schemaNames)
                ->count(),
            'delivery_rate' => $deliveryRate,
            // Calculate engagement rate from actual feedback data
            'engagement_rate' => $engagementRate,
            'schools_reached' => $schoolsReached,
            'avg_response_time' => '2.4 hours', // This would need response tracking
            'monthly_growth' => 12.5, // This would need previous month comparison
            
            // Message type breakdown
            'message_types' => [
            'Quick-SMS' => [
                'count' => $qsmsCount, 
                'percentage' => $totalMessages > 0 ? round(($qsmsCount / $totalMessages) * 100, 1) : 0
            ],
             'Phone-SMS' => [
                'count' => $psmsCount, 
                'percentage' => $totalMessages > 0 ? round(($psmsCount / $totalMessages) * 100, 1) : 0
            ],
            'Email' => [
                'count' => $emailCount, 
                'percentage' => $totalMessages > 0 ? round(($emailCount / $totalMessages) * 100, 1) : 0
            ],
            'WhatsApp' => [
                'count' => $whatsappCount, 
                'percentage' => $totalMessages > 0 ? round(($whatsappCount / $totalMessages) * 100, 1) : 0
            ]
            ],
            
            // Delivery status
            'delivery_stats' => [
            'delivered' => $deliveredMessages,
            'pending' => $pendingMessages,
            'failed' => $failedMessages
            ]
        ];
    }

    private function getSchoolsCommunicationList($schools)
    {
        return $schools->map(function ($school) {
            $total_sms_sent= $school->messageSentTotal();
            $deliveryMessage=DB::table('shulesoft.sms')->where('schema_name', $school->schoolSetting->schema_name)->where('status', 1)->count();
            $lastMessage = DB::table('shulesoft.sms')
                ->where('schema_name', $school->schoolSetting->schema_name)
                ->orderBy('created_at', 'desc')
                ->value('created_at');
                
            $feedbackCount = DB::table('shulesoft.exam_comments')
                ->where('schema_name', $school->schoolSetting->schema_name)
                ->count() + 
                DB::table('constant.feedback')
                ->where('schema', $school->schoolSetting->schema_name)
                ->count();
                
            $activeCampaigns = DB::table('shulesoft.sms_content')
                ->where('schema_name', $school->schoolSetting->schema_name)
                ->count();

            return [
                'id' => $school->id,
                'name' => $school->schoolSetting->sname ?? 'School ' . $school->id,
                'location' => $school->schoolSetting->address ?? 'Location ' . $school->id,
                'messages_sent' => $total_sms_sent,
                'delivery_rate' => $total_sms_sent > 0 ? round(($deliveryMessage/$total_sms_sent) * 100) . '%' : '0%',
                'last_message' => $lastMessage ? \Carbon\Carbon::parse($lastMessage)->format('d M Y') : 'No messages',
                'engagement_rate' => $total_sms_sent > 0 ? round(($feedbackCount / $total_sms_sent) * 100, 1) . '%' : '0%',
                'active_campaigns' => $activeCampaigns,
                'communication_status' => $this->getCommunicationStatus($lastMessage)
            ];
        });
    }
           private function getCommunicationStatus($lastMessage)
                {
                    if (!$lastMessage) {
                        return 'No Activity';
                    }
                    
                    $lastMessageDate = \Carbon\Carbon::parse($lastMessage);
                    $daysSinceLastMessage = $lastMessageDate->diffInDays(now());
                    
                    if ($daysSinceLastMessage <= 1) {
                        return 'Active';
                    } elseif ($daysSinceLastMessage <= 7) {
                        return 'Recent';
                    } elseif ($daysSinceLastMessage <= 30) {
                        return 'Moderate';
                    } else {
                        return 'Inactive';
                    }
                }
    private function getActiveCampaigns()
    {
        // Sample active campaigns data
        return [
            [
                'id' => 1,
                'name' => 'Term Opening Announcement',
                'type' => 'Announcement',
                'status' => 'Active',
                'target_schools' => 12,
                'messages_sent' => 1250,
                'delivery_rate' => 96.2,
                'engagement_rate' => 78.4,
                'start_date' => now()->subDays(3)->format('M d, Y'),
                'end_date' => now()->addDays(4)->format('M d, Y')
            ],
            [
                'id' => 2,
                'name' => 'Parent-Teacher Meeting Reminder',
                'type' => 'Reminder',
                'status' => 'Scheduled',
                'target_schools' => 8,
                'messages_sent' => 0,
                'delivery_rate' => 0,
                'engagement_rate' => 0,
                'start_date' => now()->addDays(2)->format('M d, Y'),
                'end_date' => now()->addDays(9)->format('M d, Y')
            ],
            [
                'id' => 3,
                'name' => 'Fee Payment Alert',
                'type' => 'Alert',
                'status' => 'Completed',
                'target_schools' => 15,
                'messages_sent' => 2340,
                'delivery_rate' => 98.1,
                'engagement_rate' => 82.7,
                'start_date' => now()->subDays(10)->format('M d, Y'),
                'end_date' => now()->subDays(3)->format('M d, Y')
            ]
        ];
    }

    private function getRecentMessages()
    {
        return [
            [
                'id' => 1,
                'type' => 'SMS',
                'subject' => 'Term Opening Notice',
                'recipients' => 450,
                'sent_at' => now()->subHours(2)->format('M d, Y H:i'),
                'status' => 'Delivered',
                'delivery_rate' => 98.2
            ],
            [
                'id' => 2,
                'type' => 'Email',
                'subject' => 'Monthly Academic Report',
                'recipients' => 25,
                'sent_at' => now()->subHours(6)->format('M d, Y H:i'),
                'status' => 'Delivered',
                'delivery_rate' => 100.0
            ],
            [
                'id' => 3,
                'type' => 'WhatsApp',
                'subject' => 'Emergency Alert - School Closure',
                'recipients' => 180,
                'sent_at' => now()->subDays(1)->format('M d, Y H:i'),
                'status' => 'Delivered',
                'delivery_rate' => 95.6
            ]
        ];
    }

    private function getCampaignsList()
    {
        return [
            [
                'id' => 1,
                'name' => 'Term Opening Announcement',
                'type' => 'Announcement',
                'status' => 'Active',
                'target_audience' => 'All School Staff',
                'schools_targeted' => 12,
                'messages_sent' => 1250,
                'delivery_rate' => 96.2,
                'engagement_rate' => 78.4,
                'responses' => 980,
                'created_at' => now()->subDays(3)->format('M d, Y'),
                'updated_at' => now()->subHours(2)->format('M d, Y H:i')
            ],
            [
                'id' => 2,
                'name' => 'Parent Survey - School Satisfaction',
                'type' => 'Survey',
                'status' => 'Completed',
                'target_audience' => 'School Principals',
                'schools_targeted' => 15,
                'messages_sent' => 15,
                'delivery_rate' => 100.0,
                'engagement_rate' => 86.7,
                'responses' => 13,
                'created_at' => now()->subDays(14)->format('M d, Y'),
                'updated_at' => now()->subDays(7)->format('M d, Y H:i')
            ]
        ];
    }

    private function getCommunicationAnalytics()
    {
        return [
            'monthly_trends' => [
                'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                'sms_data' => [1200, 1450, 1350, 1600, 1750, 1820],
                'email_data' => [800, 920, 850, 1100, 1200, 1250],
                'whatsapp_data' => [200, 280, 320, 450, 520, 580]
            ],
            'delivery_analytics' => [
                'labels' => ['Delivered', 'Pending', 'Failed'],
                'data' => [15185, 145, 90]
            ],
            'engagement_by_type' => [
                'SMS' => 72.5,
                'Email' => 68.3,
                'WhatsApp' => 85.7
            ],
            'peak_hours' => [
                'labels' => ['6AM', '8AM', '10AM', '12PM', '2PM', '4PM', '6PM', '8PM'],
                'data' => [150, 420, 380, 250, 380, 450, 520, 280]
            ]
        ];
    }

    private function getFeedbackSummary()
    {
        return [
            [
                'campaign_name' => 'Term Opening Announcement',
                'school_name' => 'Greenfield Academy',
                'feedback_type' => 'Acknowledgment',
                'message' => 'Received and noted. All staff have been informed.',
                'respondent' => 'Principal John Doe',
                'received_at' => now()->subHours(3)->format('M d, Y H:i'),
                'status' => 'Reviewed'
            ],
            [
                'campaign_name' => 'Parent Survey - School Satisfaction',
                'school_name' => 'Sunrise Primary',
                'feedback_type' => 'Survey Response',
                'message' => 'Survey completed. 85% parent satisfaction rate.',
                'respondent' => 'Principal Mary Smith',
                'received_at' => now()->subDays(2)->format('M d, Y H:i'),
                'status' => 'Pending Review'
            ]
        ];
    }

    private function getMessageTemplates()
    {
        return [
            [
                'id' => 1,
                'name' => 'General Announcement',
                'subject' => 'Important Announcement from [SCHOOL_NAME]',
                'message' => 'Dear [RECIPIENT_NAME], we would like to inform you about...',
                'type' => 'announcement'
            ],
            [
                'id' => 2,
                'name' => 'Fee Reminder',
                'subject' => 'Fee Payment Reminder - [SCHOOL_NAME]',
                'message' => 'Dear Parent/Guardian, this is a reminder that...',
                'type' => 'reminder'
            ],
            [
                'id' => 3,
                'name' => 'Emergency Alert',
                'subject' => 'URGENT: Emergency Notice from [SCHOOL_NAME]',
                'message' => 'Dear [RECIPIENT_NAME], we need to inform you urgently about...',
                'type' => 'alert'
            ]
        ];
    }

    private function getCommunicationTrendsByPeriod($period = '6months')
    {
        $months = [];
        $labels = [];
        $smsData = [];
        $emailData = [];
        $whatsappData = [];

        // Generate period based on request
        switch ($period) {
            case '3months':
                $periodCount = 3;
                break;
            case '1year':
                $periodCount = 12;
                break;
            default:
                $periodCount = 6;
                break;
        }

        // Generate months
        for ($i = $periodCount - 1; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $months[] = $month;
            $labels[] = $month->format('M Y');
        }

        // Get actual data for each month and message type
        foreach ($months as $month) {
            $monthStart = $month->startOfMonth()->toDateString();
            $monthEnd = $month->endOfMonth()->toDateString();

            // SMS data (including Quick-SMS and Phone-SMS)
            $smsCount = DB::table('shulesoft.sms')
                ->whereIn('schema_name', $this->schemaNames)
                ->whereBetween('created_at', [$monthStart, $monthEnd])
                ->whereIn('sent_from', ['quicksms', 'phone-sms'])
                ->count();
            $smsData[] = $smsCount;

            // Email data
            $emailCount = DB::table('shulesoft.sms')
                ->whereIn('schema_name', $this->schemaNames)
                ->whereBetween('created_at', [$monthStart, $monthEnd])
                ->where('sent_from', 'email')
                ->count();
            $emailData[] = $emailCount;

            // WhatsApp data
            $whatsappCount = DB::table('shulesoft.sms')
                ->whereIn('schema_name', $this->schemaNames)
                ->whereBetween('created_at', [$monthStart, $monthEnd])
                ->where('sent_from', 'whatsapp')
                ->count();
            $whatsappData[] = $whatsappCount;
        }

        return [
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'SMS',
                    'data' => $smsData,
                    'borderColor' => 'rgb(75, 192, 192)',
                    'backgroundColor' => 'rgba(75, 192, 192, 0.1)',
                    'tension' => 0.1
                ],
                [
                    'label' => 'Email',
                    'data' => $emailData,
                    'borderColor' => 'rgb(255, 99, 132)',
                    'backgroundColor' => 'rgba(255, 99, 132, 0.1)',
                    'tension' => 0.1
                ],
                [
                    'label' => 'WhatsApp',
                    'data' => $whatsappData,
                    'borderColor' => 'rgb(54, 162, 235)',
                    'backgroundColor' => 'rgba(54, 162, 235, 0.1)',
                    'tension' => 0.1
                ]
            ]
        ];
    }

    private function getCommunicationTrends()
    {
        return $this->getCommunicationTrendsByPeriod('6months');
    }
}
