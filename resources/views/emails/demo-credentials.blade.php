<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Your Demo Access</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #007bff; color: white; padding: 20px; text-align: center; }
        .content { padding: 20px; background: #f9f9f9; }
        .credentials { background: #e7f3ff; padding: 20px; margin: 20px 0; border-radius: 5px; border-left: 4px solid #007bff; }
        .button { display: inline-block; background: #007bff; color: white; padding: 12px 24px; text-decoration: none; border-radius: 5px; margin: 20px 0; }
        .footer { text-align: center; padding: 20px; color: #666; font-size: 12px; }
        .warning { background: #fff3cd; padding: 15px; border-radius: 5px; border-left: 4px solid #ffc107; margin: 20px 0; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🎉 Welcome to ShuleSoft Group Connect</h1>
            <p>Your Demo Access is Ready!</p>
        </div>
        
        <div class="content">
            <p>Dear {{ $contact_name }},</p>
            
            <p>Great news! Your demo request for <strong>{{ $organization_name }}</strong> has been approved. You now have access to explore ShuleSoft Group Connect.</p>
            
            <div class="credentials">
                <h3>🔑 Your Demo Login Credentials</h3>
                <p><strong>Username:</strong> {{ $username }}</p>
                <p><strong>Password:</strong> {{ $password }}</p>
                <p><strong>Login URL:</strong> <a href="{{ $login_url }}">{{ $login_url }}</a></p>
            </div>
            
            <div class="warning">
                <h4>⚠️ Important Security Notice</h4>
                <p>Please change your password after your first login for security purposes. Keep these credentials secure and do not share them with unauthorized personnel.</p>
            </div>
            
            <h3>🚀 What You Can Explore</h3>
            <ul>
                <li><strong>Dashboard:</strong> Overview of multi-school management features</li>
                <li><strong>AI Insights:</strong> Ask questions about your school network in plain English</li>
                <li><strong>Reports:</strong> Comprehensive analytics across academics, finance, and operations</li>
                <li><strong>School Management:</strong> Centralized control of multiple institutions</li>
                <li><strong>User Guide:</strong> Complete documentation and tutorials</li>
            </ul>
            
            <div style="text-align: center;">
                <a href="{{ $login_url }}" class="button">🎯 Start Your Demo</a>
            </div>
            
            <h3>📞 Need Help?</h3>
            <p>Our support team is here to assist you:</p>
            <ul>
                <li><strong>Email:</strong> support@shulesoft.africa</li>
                <li><strong>Phone:</strong> +254 (0) 700 000 000</li>
                <li><strong>Documentation:</strong> Available in the User Guide section</li>
            </ul>
            
            <p style="margin-top: 30px;">
                We're excited to show you how ShuleSoft Group Connect can transform your multi-school management experience!
            </p>
            
            <p>Best regards,<br>
            <strong>The ShuleSoft Team</strong></p>
        </div>
        
        <div class="footer">
            <p>This demo account is valid for 30 days from the date of approval.</p>
            <p>© {{ date('Y') }} ShuleSoft. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
