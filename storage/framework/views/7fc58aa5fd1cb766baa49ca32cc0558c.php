<!DOCTYPE html>
<html>
<head>
    <title>Training Notification</title>
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
            background-color: #007bff;
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
            background-color: #007bff;
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
            background-color: #28a745;
            color: #ffffff;
            text-decoration: none;
            font-size: 16px;
            font-weight: 500;
            border-radius: 5px;
        }
        .button:hover {
            background-color: #218838;
        }
    </style>
    <?php
        use Carbon\Carbon;
    ?>
</head>
<body>

<div class="email-container">
    <div class="header">
        Training Notification 📅
    </div>

    <div class="content">
        <p>Hello <strong><?php echo e($facilitator->name); ?></strong>,</p>

        <?php if(isset($training->is_updated)): ?>

            <?php if($training->is_updated == 1): ?>
                <p style="font-size: 18px; font-weight: bold; color: #d9534f; background-color: #f8d7da; padding: 10px; border-radius: 5px; text-align: center;">
                    ⚠️ Important Update: The training session details have been updated. Please review the changes.
                </p>
            <?php endif; ?>

        <?php endif; ?>

        <p>You have been assigned as a <strong>facilitator</strong> for the following training session:</p>

        <ul>
            <li><strong>Course:</strong> <?php echo e($training->course->course_name ?? 'N/A'); ?></li>
            <?php if(isset($training->company)): ?>
                <li><strong>Company:</strong> <?php echo e($training->company->company_name ?? 'N/A'); ?></li>
            <?php endif; ?>
            <li><strong>Date:</strong> <?php echo e(optional($training->schedule)->from_date ? \Carbon\Carbon::parse($training->schedule->from_date)->format('M d, Y') : 'N/A'); ?>

                to <?php echo e(optional($training->schedule)->to_date ? \Carbon\Carbon::parse($training->schedule->to_date)->format('M d, Y') : 'N/A'); ?>

            </li>
            <li><strong>Time:</strong> <?php echo e(optional($training->schedule)->from_time ? \Carbon\Carbon::parse($training->schedule->from_time)->format('h:i A') : 'N/A'); ?>

                - <?php echo e(optional($training->schedule)->to_time ? \Carbon\Carbon::parse($training->schedule->to_time)->format('h:i A') : 'N/A'); ?>

            </li>

            <li><strong>Mode:</strong> <?php echo e($training->mode); ?></li>
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
<?php /**PATH D:\ALPs\ALPs Calendar\ALPS-Calendar\resources\views/emails/training_notification.blade.php ENDPATH**/ ?>