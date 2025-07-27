<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Academic Transcript</title>
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
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .content {
            background-color: #ffffff;
            padding: 20px;
            border: 1px solid #dee2e6;
            border-radius: 5px;
        }
        .footer {
            margin-top: 20px;
            padding: 15px;
            background-color: #f8f9fa;
            border-radius: 5px;
            font-size: 12px;
            color: #6c757d;
        }
    </style>
</head>
<body>
    <div class="header">
        <h2>Academic Transcript</h2>
        <p>Dear {{ $biodata->Surname }} {{ $biodata->Othernames }},</p>
    </div>

    <div class="content">
        <p>Your academic transcript has been approved and is attached to this email.</p>
        
        <h3>Student Information:</h3>
        <ul>
            <li><strong>Name:</strong> {{ $biodata->Surname }} {{ $biodata->Othernames }}</li>
            <li><strong>Matric Number:</strong> {{ $biodata->matric }}</li>
            <li><strong>Degree Awarded:</strong> {{ $degreeAwarded ?? 'N/A' }}</li>
            <li><strong>CGPA:</strong> {{ $cgpa ?? 'N/A' }}</li>
            <li><strong>Date Awarded:</strong> {{ $dateAward ?? 'N/A' }}</li>
        </ul>

        <p>Please find attached:</p>
        <ul>
            <li>Your complete academic transcript</li>
            <li>Transcript letter</li>
        </ul>

        <p>If you have any questions or concerns, please contact the academic office.</p>
    </div>

    <div class="footer">
        <p>This is an automated message. Please do not reply to this email.</p>
        <p>Generated on: {{ now()->format('F j, Y \a\t g:i A') }}</p>
    </div>
</body>
</html> 