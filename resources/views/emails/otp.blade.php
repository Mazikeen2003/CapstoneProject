<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your ProjectTracker Login Verification Code</title>
</head>
<body style="margin:0; padding:0; background-color:#f4f6f8; font-family:Arial, Helvetica, sans-serif; color:#0f1e3d;">
    <div style="max-width:640px; margin:24px auto; background:#ffffff; border-radius:16px; overflow:hidden; box-shadow:0 10px 30px rgba(15,30,61,0.08);">
        <div style="background:linear-gradient(135deg, #0f1e3d 0%, #162347 100%); padding:28px 32px; text-align:center; color:#ffffff;">
            <div style="font-size:12px; letter-spacing:0.28em; text-transform:uppercase; color:#c9a84c; font-weight:700;">ProjectTracker</div>
            <h1 style="margin:10px 0 0; font-size:24px; line-height:1.3;">Login Verification Code</h1>
        </div>
        <div style="padding:32px;">
            <p style="margin:0 0 12px; font-size:16px;">Hello <strong>{{ $username }}</strong>,</p>
            <p style="margin:0 0 20px; font-size:15px; line-height:1.6; color:#475569;">Use the verification code below to complete your sign-in to the City Transparency Portal. This code will expire in 10 minutes.</p>

            <div style="text-align:center; margin:24px 0; padding:20px; border:1px solid #e2e8f0; border-radius:14px; background:#f8fafc;">
                <div style="font-size:12px; letter-spacing:0.22em; text-transform:uppercase; color:#64748b; margin-bottom:10px;">Your code</div>
                <div style="font-size:38px; font-weight:700; letter-spacing:0.25em; color:#0f1e3d;">{{ $code }}</div>
            </div>

            <p style="margin:0; font-size:13px; line-height:1.6; color:#64748b;">If you did not try to sign in, you can safely ignore this message.</p>
        </div>
        <div style="background:#f8fafc; padding:20px 32px; text-align:center; font-size:12px; color:#64748b; border-top:1px solid #e2e8f0;">
            ProjectTracker &mdash; City Transparency Portal
        </div>
    </div>
</body>
</html>
