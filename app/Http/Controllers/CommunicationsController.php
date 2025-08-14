<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\School;
use App\Models\User;
use App\Models\Organization;
use Illuminate\Support\Facades\DB;

class CommunicationsController extends Controller
{
    public function dashboard()
    {
        $data = $this->calculateCommunicationKPIs();
        $data['schools'] = $this->getSchoolsCommunicationList();
        $data['campaigns'] = $this->getActiveCampaigns();
        $data['recentMessages'] = $this->getRecentMessages();
        
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
        return [
            'total_messages_sent' => 15420,
            'messages_this_month' => 2340,
            'active_campaigns' => 8,
            'delivery_rate' => 98.5,
            'engagement_rate' => 76.3,
            'schools_reached' => School::count(),
            'avg_response_time' => '2.4 hours',
            'monthly_growth' => 12.5,
            
            // Message type breakdown
            'message_types' => [
                'SMS' => ['count' => 8420, 'percentage' => 54.6],
                'Email' => ['count' => 5230, 'percentage' => 33.9],
                'WhatsApp' => ['count' => 1770, 'percentage' => 11.5]
            ],
            
            // Delivery status
            'delivery_stats' => [
                'delivered' => 15185,
                'pending' => 145,
                'failed' => 90
            ]
        ];
    }

    private function getSchoolsCommunicationList()
    {
        $schools = School::all();
        
        return $schools->map(function ($school) {
            return [
                'id' => $school->id,
                'name' => $school->name ?? 'School ' . $school->id,
                'location' => $school->address ?? 'Location ' . $school->id,
                'messages_sent' => rand(150, 500),
                'delivery_rate' => rand(85, 99) . '%',
                'last_message' => now()->subDays(rand(1, 7))->format('M d, Y'),
                'engagement_rate' => rand(60, 85) . '%',
                'active_campaigns' => rand(1, 5),
                'communication_status' => rand(0, 1) ? 'Active' : 'Moderate'
            ];
        });
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
}
