<!DOCTYPE html>
<html>
<head>
    <title>Trustee Verification Request</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
    <h2>Trustee Verification Request</h2>
    <p>Dear {{ $applicant->trustee_name }},</p>
    
    <p>An application has been submitted by <strong>{{ $applicant->name }}</strong> citing you as a trustee of <strong>{{ $applicant->temple_address }}</strong>.</p>
    
    <p>Before we can proceed with verifying their application, we need you to confirm that this applicant is genuinely associated with your temple/trust.</p>
    
    <p>Please click the button below to review the application details and approve or reject the request:</p>
    
    <p style="text-align: center; margin: 30px 0;">
        <a href="{{ $verificationUrl }}" style="background-color: #4f46e5; color: white; padding: 12px 24px; text-decoration: none; border-radius: 5px; font-weight: bold;">Review Application</a>
    </p>
    
    <p>If the button above allows doesn't work, copy and paste this link into your browser:</p>
    <p>{{ $verificationUrl }}</p>
    
    <p>Thank you,<br>Certificate Management System</p>
</body>
</html>
