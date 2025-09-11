<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Welcome to ShuleSoft Group Connect</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; margin: 0; padding: 0; }
        .container { max-width: 600px; margin: 0 auto; background: white; }
        .header { background: #007bff; color: white; padding: 30px 20px; text-align: center; }
        .header h1 { margin: 0; font-size: 28px; }
        .header p { margin: 10px 0 0 0; font-size: 16px; }
        .content { padding: 30px 20px; }
        .welcome-box { background: #e7f3ff; padding: 25px; margin: 20px 0; border-radius: 8px; border-left: 5px solid #007bff; }
        .feature-list { background: #f8f9fa; padding: 20px; border-radius: 8px; margin: 20px 0; }
        .feature-list ul { margin: 0; padding-left: 20px; }
        .feature-list li { margin: 10px 0; color: #555; }
        .login-section { background: #28a745; color: white; padding: 20px; border-radius: 8px; margin: 25px 0; text-align: center; }
        .login-button { display: inline-block; background: white; color: #28a745; padding: 15px 30px; text-decoration: none; border-radius: 5px; font-weight: bold; margin: 15px 0; }
        .credentials { background: #fff3cd; padding: 20px; border-radius: 8px; border-left: 5px solid #ffc107; margin: 20px 0; }
        .support-section { background: #6c757d; color: white; padding: 20px; border-radius: 8px; margin: 25px 0; text-align: center; }
        .footer { text-align: center; padding: 20px; color: #666; font-size: 14px; background: #f8f9fa; }
        .trial-badge { background: #17a2b8; color: white; padding: 10px 15px; border-radius: 20px; font-size: 14px; display: inline-block; margin: 10px 0; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🎉 Welcome to ShuleSoft Group Connect!</h1>
            <p>Your organization has been successfully registered</p>
        </div>
        
        <div class="content">
            <div class="welcome-box">
                <h2 style="margin: 0 0 15px 0; color: #007bff;">Dear {{ $contact_name }},</h2>
                <p style="margin: 0; font-size: 16px;">
                    Congratulations! Your organization <strong>"{{ $org_name }}"</strong> has been successfully registered with ShuleSoft Group Connect.
                </p>
                <div class="trial-badge">✨ 30-Day Free Trial Started</div>
            </div>

            <div class="feature-list">
                <h3 style="margin: 0 0 15px 0; color: #333;">🚀 You can now:</h3>
                <ul>
                    <li><strong>Manage your school network</strong> - Centralized control of all your schools</li>
                    <li><strong>Add team members</strong> - Collaborate with your staff seamlessly</li>
                    <li><strong>Configure school settings</strong> - Customize each school's preferences</li>
                    <li><strong>Access comprehensive reports</strong> - Get insights across your organization</li>
                    <li><strong>Monitor performance</strong> - Track key metrics and analytics</li>
                    <li><strong>Streamline operations</strong> - Automate routine administrative tasks</li>
                </ul>
            </div>

            <div class="login-section">
                <h3 style="margin: 0 0 10px 0;">🔑 Ready to Get Started?</h3>
                <p style="margin: 0 0 15px 0;">Access your dashboard using your registration credentials</p>
                <a href="{{ $login_url }}" class="login-button">Login to Your Dashboard</a>
            </div>

            <div class="credentials">
                <h4 style="margin: 0 0 15px 0; color: #856404;">📧 Your Login Details:</h4>
                <p style="margin: 5px 0;"><strong>Login URL:</strong> {{ $login_url }}</p>
                <p style="margin: 5px 0;"><strong>Email:</strong> {{ $contact_email }}</p>
                <p style="margin: 15px 0 0 0; font-size: 14px; color: #856404;">
                    <em>Use the password you created during registration</em>
                </p>
            </div>

            <div class="support-section">
                <h3 style="margin: 0 0 10px 0;">💬 Need Help?</h3>
                <p style="margin: 0;">Our support team is here to help you get the most out of ShuleSoft Group Connect.</p>
                <p style="margin: 10px 0 0 0; font-size: 14px;">
                    Contact us anytime for onboarding assistance, training, or technical support.
                </p>
            </div>

            <p style="margin: 30px 0 0 0; font-size: 16px; text-align: center;">
                <strong>Thank you for choosing ShuleSoft Group Connect!</strong><br>
                <em>Empowering educational excellence through technology</em>
            </p>
        </div>
        
        <div class="footer">
            <p style="margin: 0;">
                © {{ date('Y') }} ShuleSoft Group Connect. All rights reserved.<br>
                This email was sent to {{ $contact_email }} regarding your organization registration.
            </p>
        </div>
    </div>
</body>
</html>
