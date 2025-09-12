<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>School Registration Request - ShuleSoft Group Connect</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; margin: 0; padding: 0; }
        .container { max-width: 600px; margin: 0 auto; background: white; }
        .header { background: #007bff; color: white; padding: 30px 20px; text-align: center; }
        .header h1 { margin: 0; font-size: 26px; }
        .header p { margin: 10px 0 0 0; font-size: 16px; }
        .content { padding: 30px 20px; }
        .welcome-box { background: #e8f5e8; padding: 25px; margin: 20px 0; border-radius: 8px; border-left: 5px solid #28a745; }
        .info-box { background: #f8f9fa; padding: 20px; border-radius: 8px; margin: 20px 0; }
        .next-steps { background: #fff3cd; padding: 20px; border-radius: 8px; border-left: 5px solid #ffc107; margin: 20px 0; }
        .next-steps ul { margin: 10px 0; padding-left: 20px; }
        .next-steps li { margin: 8px 0; color: #856404; }
        .contact-section { background: #17a2b8; color: white; padding: 20px; border-radius: 8px; margin: 25px 0; text-align: center; }
        .footer { text-align: center; padding: 20px; color: #666; font-size: 14px; background: #f8f9fa; }
        .school-details { background: #e7f3ff; padding: 15px; border-radius: 5px; margin: 15px 0; }
        .status-badge { background: #28a745; color: white; padding: 8px 15px; border-radius: 20px; font-size: 14px; display: inline-block; margin: 10px 0; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🏫 School Registration Request</h1>
            <p>Your school is being onboarded to ShuleSoft Group Connect</p>
        </div>
        
        <div class="content">
            <div class="welcome-box">
                <h2 style="margin: 0 0 15px 0; color: #28a745;">Dear {{ $contact_person }},</h2>
                <p style="margin: 0; font-size: 16px;">
                    We're excited to inform you that <strong>"{{ $school_name }}"</strong> has been requested to join 
                    <strong>{{ $organization_name }}</strong> on the ShuleSoft Group Connect platform.
                </p>
                <div class="status-badge">✅ Registration Request Received</div>
            </div>

            <div class="school-details">
                <h3 style="margin: 0 0 15px 0; color: #007bff;">📋 School Details Submitted:</h3>
                <p style="margin: 5px 0;"><strong>School Name:</strong> {{ $school_name }}</p>
                <p style="margin: 5px 0;"><strong>Location:</strong> {{ $location }}</p>
                <p style="margin: 5px 0;"><strong>Contact Person:</strong> {{ $contact_person }}</p>
                <p style="margin: 5px 0;"><strong>Email:</strong> {{ $contact_email }}</p>
                <p style="margin: 5px 0;"><strong>Phone:</strong> {{ $contact_phone }}</p>
                <p style="margin: 5px 0;"><strong>Organization:</strong> {{ $organization_name }}</p>
            </div>

            <div class="next-steps">
                <h3 style="margin: 0 0 15px 0; color: #856404;">🔄 What happens next?</h3>
                <ul>
                    <li><strong>Review Process:</strong> Our team will review your school's registration details</li>
                    <li><strong>Setup & Configuration:</strong> We'll prepare your school's access to the platform</li>
                    <li><strong>Account Creation:</strong> You'll receive login credentials once setup is complete</li>
                    <li><strong>Training Support:</strong> We'll provide onboarding assistance to get you started</li>
                    <li><strong>Go Live:</strong> Your school will be ready to use ShuleSoft Group Connect</li>
                </ul>
            </div>

            <div class="info-box">
                <h3 style="margin: 0 0 15px 0; color: #333;">🎯 What to Expect:</h3>
                <p style="margin: 0 0 10px 0;">
                    <strong>Timeline:</strong> School setup typically takes 2-3 business days from registration.
                </p>
                <p style="margin: 0 0 10px 0;">
                    <strong>Access:</strong> You'll receive an email with login instructions once your school is ready.
                </p>
                <p style="margin: 0;">
                    <strong>Support:</strong> Our team will be available to help during the onboarding process.
                </p>
            </div>

            <div class="contact-section">
                <h3 style="margin: 0 0 10px 0;">💬 Questions or Need Help?</h3>
                <p style="margin: 0;">If you have any questions about this registration or need assistance,</p>
                <p style="margin: 5px 0 0 0; font-size: 14px;">
                    please don't hesitate to contact our support team.
                </p>
                <p style="margin: 15px 0 0 0; font-size: 16px;">
                    <strong>📧 support@shulesoft.com</strong> | <strong>📞 Contact Support</strong>
                </p>
            </div>

            <p style="margin: 30px 0 0 0; font-size: 16px; text-align: center;">
                <strong>Welcome to the ShuleSoft Group Connect family!</strong><br>
                <em>Empowering educational excellence through technology</em>
            </p>
        </div>
        
        <div class="footer">
            <p style="margin: 0;">
                © {{ date('Y') }} ShuleSoft Group Connect. All rights reserved.<br>
                This email was sent to {{ $contact_email }} regarding your school's registration request.
            </p>
            <p style="margin: 10px 0 0 0; font-size: 12px; color: #999;">
                Reference ID: {{ $request_id ?? 'N/A' }} | Organization: {{ $organization_name }}
            </p>
        </div>
    </div>
</body>
</html>
