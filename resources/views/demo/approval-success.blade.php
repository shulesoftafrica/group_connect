<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Demo Request Approved - ShuleSoft Group Connect</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 100vh; }
        .success-container { display: flex; align-items: center; justify-content: center; min-height: 100vh; padding: 20px; }
        .success-card { background: white; border-radius: 15px; padding: 60px 40px; text-align: center; box-shadow: 0 20px 40px rgba(0,0,0,0.1); max-width: 600px; }
        .success-icon { font-size: 4rem; color: #28a745; margin-bottom: 30px; }
        .success-title { color: #333; margin-bottom: 20px; }
        .success-message { color: #666; margin-bottom: 40px; line-height: 1.6; }
        .btn-dashboard { background: #007bff; border: none; padding: 12px 30px; font-weight: 600; }
        .btn-dashboard:hover { background: #0056b3; transform: translateY(-2px); transition: all 0.3s; }
    </style>
</head>
<body>
    <div class="success-container">
        <div class="success-card">
            <div class="success-icon">
                <i class="fas fa-check-circle"></i>
            </div>
            
            <h1 class="success-title">Demo Request Approved!</h1>
            
            <p class="success-message">
                The demo request has been successfully approved. Demo credentials have been automatically generated and sent to the applicant's email address.
            </p>
            
            <div class="d-flex justify-content-center gap-3">
                <a href="{{ route('login') }}" class="btn btn-primary btn-dashboard">
                    <i class="fas fa-tachometer-alt me-2"></i>Back to Dashboard
                </a>
                <a href="{{ route('login') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-home me-2"></i>Home
                </a>
            </div>
            
            <div class="mt-4 text-muted">
                <small>
                    <i class="fas fa-info-circle me-1"></i>
                    The applicant will receive their login credentials via email within a few minutes.
                </small>
            </div>
        </div>
    </div>
</body>
</html>
