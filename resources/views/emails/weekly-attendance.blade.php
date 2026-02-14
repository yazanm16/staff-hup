<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Weekly Attendance Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background-color: #4F46E5;
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 5px 5px 0 0;
        }
        .content {
            background-color: #f9fafb;
            padding: 30px;
            border: 1px solid #e5e7eb;
            border-radius: 0 0 5px 5px;
        }
        .button {
            display: inline-block;
            padding: 12px 24px;
            background-color: #4F46E5;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 20px;
        }
        .footer {
            text-align: center;
            margin-top: 20px;
            color: #6b7280;
            font-size: 14px;
        }
        .info-box {
            background-color: #EEF2FF;
            border-left: 4px solid #4F46E5;
            padding: 15px;
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>📊 Weekly Attendance Report</h1>
    </div>
    
    <div class="content">
        <h2>Hello Admin,</h2>
        
        <p>The weekly attendance report for <strong>{{ now()->subWeek()->format('Y-m-d') }}</strong> to <strong>{{ now()->format('Y-m-d') }}</strong> is now ready.</p>
        
        <div class="info-box">
            <p><strong>📎 Report Details:</strong></p>
            <ul>
                <li>Report Period: Last Week</li>
                <li>Generated: {{ now()->format('Y-m-d H:i:s') }}</li>
                <li>Format: Excel (.xlsx)</li>
            </ul>
        </div>
        
        <p>The detailed attendance report is attached to this email. Please review the report for any attendance issues or irregularities.</p>
        
        <p>If you have any questions or need additional information, please don't hesitate to contact the HR department.</p>
        
        <p>Best regards,<br>
        <strong>Staff Hub System</strong></p>
    </div>
    
    <div class="footer">
        <p>This is an automated message from Staff Hub. Please do not reply to this email.</p>
        <p>&copy; {{ date('Y') }} Staff Hub. All rights reserved.</p>
    </div>
</body>
</html>

