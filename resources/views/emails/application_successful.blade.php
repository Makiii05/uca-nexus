<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Application Submitted Successfully</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f6f7fb;
            margin: 0;
            padding: 0;
        }
        .wrapper {
            max-width: 600px;
            margin: 40px auto;
            background-color: #ffffff;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        }
        .header {
            background-color: #042042;
            padding: 32px 40px;
            text-align: center;
        }
        .header h1 {
            color: #eaea52;
            font-size: 22px;
            margin: 0;
            letter-spacing: 0.5px;
        }
        .header p {
            color: #cdd8e8;
            font-size: 13px;
            margin: 6px 0 0;
        }
        .body {
            padding: 36px 40px;
        }
        .greeting {
            font-size: 16px;
            color: #343434;
            margin-bottom: 12px;
        }
        .message {
            font-size: 14px;
            color: #555555;
            line-height: 1.7;
            margin-bottom: 24px;
        }
        .app-no-box {
            background-color: #f0faf4;
            border: 1px solid #86efac;
            border-left: 4px solid #22c55e;
            border-radius: 8px;
            padding: 20px 24px;
            margin-bottom: 24px;
        }
        .app-no-label {
            font-size: 12px;
            color: #6b7280;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 6px;
        }
        .app-no-value {
            font-size: 22px;
            font-family: 'Courier New', monospace;
            font-weight: 700;
            color: #16a34a;
        }
        .app-no-note {
            font-size: 12px;
            color: #9ca3af;
            margin-top: 6px;
        }
        .notice-box {
            background-color: #eff6ff;
            border: 1px solid #bfdbfe;
            border-radius: 8px;
            padding: 16px 20px;
            margin-bottom: 28px;
        }
        .notice-box p {
            font-size: 13px;
            color: #1e40af;
            margin: 0;
            line-height: 1.6;
        }
        .footer {
            background-color: #f6f7fb;
            border-top: 1px solid #e5e7eb;
            padding: 24px 40px;
            text-align: center;
        }
        .footer p {
            font-size: 12px;
            color: #9ca3af;
            margin: 4px 0;
        }
        .footer strong {
            color: #042042;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <!-- Header -->
        <div class="header">
            <h1>Enrollment System</h1>
            <p>Application Confirmation</p>
        </div>

        <!-- Body -->
        <div class="body">
            <p class="greeting">Dear <strong>{{ $applicantName }}</strong>,</p>

            <p class="message">
                @if($isNew)
                    Thank you for submitting your application. We have successfully received your application
                    and it is now <strong>under review</strong> by our admissions team. We will get in touch
                    with you as soon as there is an update on your application status.
                @else
                    Your application information has been <strong>successfully updated</strong>. Our admissions
                    team will review the changes and will contact you if further information is needed.
                @endif
            </p>

            <!-- Application Number -->
            <div class="app-no-box">
                <div class="app-no-label">Your Application Number</div>
                <div class="app-no-value">{{ $applicationNo }}</div>
                <div class="app-no-note">Please save this number — you will need it to track your application status.</div>
            </div>

            <!-- What's next -->
            <div class="notice-box">
                <p>
                    <strong>What happens next?</strong><br>
                    Our admissions office will review your submitted information. You may be called for an
                    interview or examination. Please check your email regularly for updates.
                </p>
            </div>

            <p class="message" style="margin-bottom:0;">
                If you have any questions or concerns, please don't hesitate to reach out to our admissions office.
            </p>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p><strong>Enrollment System</strong></p>
            <p>This is an automated message. Please do not reply to this email.</p>
            <p>&copy; {{ date('Y') }} All rights reserved.</p>
        </div>
    </div>
</body>
</html>
