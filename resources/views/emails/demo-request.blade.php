<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>New Demo Request</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #007bff; color: white; padding: 20px; text-align: center; }
        .content { padding: 20px; background: #f9f9f9; }
        .details { background: white; padding: 15px; margin: 10px 0; border-radius: 5px; }
        .button { display: inline-block; background: #28a745; color: white; padding: 12px 24px; text-decoration: none; border-radius: 5px; margin: 20px 0; }
        .footer { text-align: center; padding: 20px; color: #666; font-size: 12px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🎯 New Demo Request</h1>
            <p>ShuleSoft Group Connect</p>
        </div>
        
        <div class="content">
            <p>Hello Sales Team,</p>
            
            <p>A new demo request has been submitted for ShuleSoft Group Connect. Please review the details below:</p>
            
            <div class="details">
                <h3>Organization Details</h3>
                <p><strong>Organization Name:</strong> {{ $organization_name }}</p>
                <p><strong>Organization Contact:</strong> {{ $organization_contact }}</p>
                <p><strong>Address:</strong> {{ $organization_address }}</p>
                <p><strong>Country:</strong> {{ $organization_country }}</p>
                <p><strong>Total Schools:</strong> {{ $total_schools }}</p>
            </div>
            
            <div class="details">
                <h3>Contact Person</h3>
                <p><strong>Name:</strong> {{ $contact_name }}</p>
                <p><strong>Email:</strong> {{ $contact_email }}</p>
                <p><strong>Phone:</strong> {{ $contact_phone }}</p>
            </div>
            
            <div class="details">
                <h3>Request Information</h3>
                <p><strong>Submitted:</strong> {{ $submitted_at }}</p>
                <p><strong>Status:</strong> Pending Approval</p>
            </div>
            
            <div style="text-align: center;">
                <a href="{{ $approval_url }}" class="button">✅ Approve Demo Request</a>
            </div>
            
            <p style="margin-top: 30px; font-size: 14px; color: #666;">
                <strong>Next Steps:</strong><br>
                1. Click the approval button above<br>
                2. Demo credentials will be automatically generated and sent to the applicant<br>
                3. Follow up with the contact person for demo scheduling
            </p>
        </div>
        
        <div class="footer">
            <p>This is an automated notification from ShuleSoft Group Connect</p>
            <p>© {{ date('Y') }} ShuleSoft. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
