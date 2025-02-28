<!DOCTYPE html>
<html>
<head>
    <title>Training Reassignment Notification</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" />
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }
        .email-container {
            max-width: 600px;
            background-color: #ffffff;
            margin: auto;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }
        .header {
            background-color: #dc3545;
            padding: 20px;
            text-align: center;
            border-top-left-radius: 8px;
            border-top-right-radius: 8px;
            color: #ffffff;
            font-size: 20px;
            font-weight: 600;
        }
        .content {
            padding: 20px;
            color: #333333;
            font-size: 16px;
            line-height: 1.6;
        }
        .content ul {
            padding-left: 20px;
        }
        .footer {
            background-color: #dc3545;
            padding: 15px;
            text-align: center;
            color: #ffffff;
            font-size: 14px;
            border-bottom-left-radius: 8px;
            border-bottom-right-radius: 8px;
        }
        .button {
            display: inline-block;
            padding: 12px 20px;
            margin-top: 15px;
            background-color: #007bff;
            color: #ffffff;
            text-decoration: none;
            font-size: 16px;
            font-weight: 500;
            border-radius: 5px;
        }
        .button:hover {
            background-color: #0056b3;
        }
    </style>
    @php
        use Carbon\Carbon;
    @endphp
</head>
<body>

<div class="email-container">
    <div class="header">
        Training Cancellation Notification 🗑️
    </div>

    <div class="content">
        <p>Hello <strong>{{ $facilitator->name }}</strong>,</p>

        <p>Please be informed that the following training session which was previously assigned to you has been <strong>cancelled</strong>:</p>

        <ul>
            <li><strong>Course:</strong> {{ $training->course->course_name ?? 'N/A' }}</li>
            <li><strong>Date:</strong> {{ optional($training->schedule)->from_date ? \Carbon\Carbon::parse($training->schedule->from_date)->format('M d, Y') : 'N/A' }}
                to {{ optional($training->schedule)->to_date ? \Carbon\Carbon::parse($training->schedule->to_date)->format('M d, Y') : 'N/A' }}
            </li>
            <li><strong>Time:</strong> {{ optional($training->schedule)->from_time ? \Carbon\Carbon::parse($training->schedule->from_time)->format('h:i A') : 'N/A' }}
                - {{ optional($training->schedule)->to_time ? \Carbon\Carbon::parse($training->schedule->to_time)->format('h:i A') : 'N/A' }}
            </li>
            <li><strong>Mode:</strong> {{ $training->mode }}</li>
        </ul>

        <p>For more details, you may visit the <a href="https://www.alpscalendar.com">ALPS Calendar</a></p>
    </div>

    <div class="footer">
        Best regards,<br>
        <strong>ALPS Calendar Team</strong>
    </div>
</div>

</body>
</html>
