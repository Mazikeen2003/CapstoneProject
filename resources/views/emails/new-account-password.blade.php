<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Set Up Your New ProjectTracker Account</title>
</head>
<body style="margin:0; padding:0; background-color:#f4f6f8; font-family:Arial, Helvetica, sans-serif; color:#0f1e3d;">
    <div style="max-width:640px; margin:24px auto; background:#ffffff; border-radius:16px; overflow:hidden; box-shadow:0 10px 30px rgba(15,30,61,0.08);">
        <div style="background:linear-gradient(135deg, #0f1e3d 0%, #162347 100%); padding:28px 32px; text-align:center; color:#ffffff;">
            <div style="font-size:12px; letter-spacing:0.28em; text-transform:uppercase; color:#c9a84c; font-weight:700;">ProjectTracker</div>
            <h1 style="margin:10px 0 0; font-size:24px; line-height:1.3;">Set Up Your New Account</h1>
        </div>
        <div style="padding:32px;">
            <p style="margin:0 0 12px; font-size:16px;">Hello <strong>{{ $name }}</strong>,</p>
            <p style="margin:0 0 20px; font-size:15px; line-height:1.6; color:#475569;">
                Your account for the City Transparency Portal has been created. Use the secure link below to create your password.
                This link expires in 24 hours and can only be used once.
            </p>

            <div style="margin:0 0 20px; font-size:14px; line-height:1.6; color:#475569;">
                Create your password here:
                <div style="margin-top:10px;">
                    <a href="{{ $setupUrl }}" style="display:inline-block; color:#0f1e3d; font-weight:700; text-decoration:underline;">{{ $setupUrl }}</a>
                </div>
            </div>

            <div style="margin:24px 0; padding:20px; border:1px solid #e2e8f0; border-radius:14px; background:#f8fafc;">
                <div style="font-size:12px; letter-spacing:0.22em; text-transform:uppercase; color:#64748b; margin-bottom:10px;">Username</div>
                <div style="font-size:18px; font-weight:700; color:#0f1e3d; margin-bottom:16px;">{{ $username }}</div>

                <div style="font-size:12px; letter-spacing:0.22em; text-transform:uppercase; color:#64748b; margin-bottom:10px;">Password setup</div>
                <div style="font-size:16px; font-weight:700; color:#0f1e3d;">Use the secure setup link above</div>
            </div>

            <p style="margin:0; font-size:13px; line-height:1.6; color:#64748b;">
                After creating your password, sign in from the portal. If you did not expect this email, contact the administrator.
            </p>
        </div>
        <div style="background:#f8fafc; padding:20px 32px; text-align:center; font-size:12px; color:#64748b; border-top:1px solid #e2e8f0;">
            ProjectTracker &mdash; City Transparency Portal
        </div>
    </div>
</body>
</html>
