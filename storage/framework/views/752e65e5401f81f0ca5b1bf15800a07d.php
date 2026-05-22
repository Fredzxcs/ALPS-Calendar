<!DOCTYPE html>
<html>
<head>
    <title>Training Notification</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" />
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
            background:
                radial-gradient(circle at top left, rgba(100, 181, 246, 0.18), transparent 30%),
                radial-gradient(circle at top right, rgba(124, 58, 237, 0.14), transparent 28%),
                linear-gradient(180deg, #eef4fb 0%, #f7fafc 100%);
        }
        .page-shell {
            max-width: 780px;
            margin: 0 auto;
            padding: 24px 16px 28px;
        }
        .email-container {
            position: relative;
            overflow: hidden;
            border-radius: 26px;
            background: rgba(255, 255, 255, 0.72);
            border: 1px solid rgba(255, 255, 255, 0.66);
            box-shadow: 0 18px 50px rgba(15, 23, 42, 0.12);
            backdrop-filter: blur(14px);
            -webkit-backdrop-filter: blur(14px);
        }
        .email-container::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, rgba(255,255,255,0.35), rgba(255,255,255,0.08));
            pointer-events: none;
        }
        .top-banner {
            position: relative;
            height: 170px;
            background-image: url('https://storage.googleapis.com/flutterflow-io-6f20.appspot.com/projects/gbVaqEWhECmgkJR1uxXW/assets/xbha3n4r7yb1/Email_Bg_Top.png');
            background-repeat: no-repeat;
            background-position: center top;
            background-size: cover;
        }
        .top-banner::after {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(180deg, rgba(255,255,255,0.10) 0%, rgba(255,255,255,0.22) 100%);
        }
        .content {
            position: relative;
            z-index: 1;
            margin-top: -90px;
            padding: 0 28px 28px;
            color: #16324f;
            line-height: 1.65;
        }
        .hero-card {
            background: rgba(255, 255, 255, 0.82);
            border: 1px solid rgba(255, 255, 255, 0.7);
            border-radius: 22px;
            padding: 24px 22px 22px;
            box-shadow: 0 10px 30px rgba(15, 23, 42, 0.07);
        }
        .brand-pill {
            display: inline-block;
            padding: 8px 14px;
            border-radius: 999px;
            background: rgba(37, 99, 235, 0.08);
            color: #2457c5;
            font-size: 12px;
            font-weight: 700;
            letter-spacing: 0.9px;
            text-transform: uppercase;
            margin-bottom: 14px;
        }
        .greeting {
            margin: 0 0 10px 0;
            font-size: 24px;
            font-weight: 700;
            color: #0f2744;
        }
        .intro {
            margin: 0 0 18px 0;
            font-size: 16px;
            color: #35516d;
        }
        .update-alert {
            margin: 0 0 18px;
            padding: 18px 18px 18px 20px;
            border-radius: 18px;
            background: linear-gradient(135deg, rgba(239, 246, 255, 0.96), rgba(255, 255, 255, 0.88));
            border: 1px solid rgba(59, 130, 246, 0.14);
            border-left: 6px solid #2563eb;
            box-shadow: 0 12px 30px rgba(37, 99, 235, 0.08);
        }
        .update-alert-title {
            margin: 0 0 6px 0;
            color: #123b76;
            font-size: 15px;
            font-weight: 700;
            letter-spacing: 0.2px;
        }
        .update-alert-text {
            margin: 0;
            color: #34507a;
            font-size: 15px;
            line-height: 1.55;
        }
        .section-title {
            margin: 16px 0 10px 0;
            color: #0f2744;
            font-size: 16px;
            font-weight: 700;
        }
        .detail-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        .detail-item {
            display: flex;
            gap: 12px;
            align-items: flex-start;
            padding: 12px 0;
            border-top: 1px solid rgba(148, 163, 184, 0.18);
            color: #203a56;
            font-size: 15px;
        }
        .detail-item:first-child {
            border-top: 0;
            padding-top: 0;
        }
        .detail-label {
            min-width: 88px;
            color: #567089;
            font-weight: 700;
        }
        .hero-card strong {
            color: #0f2744;
        }
        .footer {
            padding: 0 28px 28px;
            color: #5f7286;
            font-size: 14px;
            line-height: 1.6;
        }
        .footer a {
            color: #2457c5;
            font-weight: 700;
            text-decoration: none;
        }
        .footer a:hover {
            text-decoration: underline;
        }
        .signature {
            margin-top: 18px;
            color: #17324d;
        }
        @media only screen and (max-width: 640px) {
            .page-shell {
                padding: 14px 10px 18px;
            }
            .content,
            .footer {
                padding-left: 18px;
                padding-right: 18px;
            }
            .content {
                margin-top: -32px;
            }
            .top-banner {
                height: 190px;
            }
            .greeting {
                font-size: 22px;
            }
            .detail-item {
                display: block;
            }
            .detail-label {
                display: block;
                min-width: 0;
                margin-bottom: 3px;
            }
        }
    </style>
    <?php
        use Carbon\Carbon;
    ?>
</head>
<body>

<div class="page-shell">
    <div class="email-container">
        <div class="top-banner" role="img" aria-label="ALPs header background"></div>

        <div class="content">
            <div class="hero-card">
                <div class="brand-pill">Training Notification</div>
                <p class="greeting">Hi <?php echo e($facilitator->name); ?>,</p>

                <?php if(isset($training->is_updated)): ?>
                    <?php if($training->is_updated == 1): ?>
                        <div class="update-alert">
                            <p class="update-alert-title">Important Update</p>
                            <p class="update-alert-text">The training session details have been updated. Please review the changes.</p>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>

                <p class="intro">You have been assigned as an <strong><?php echo e($roleLabel ?? 'Facilitator'); ?></strong> for the following training session:</p>

                <p class="section-title">Training Details</p>
                <ul class="detail-list">
                    <li class="detail-item"><span class="detail-label">Course</span><span><?php echo e($training->course->course_name ?? 'N/A'); ?></span></li>
                    <?php if(isset($training->company)): ?>
                        <li class="detail-item"><span class="detail-label">Company</span><span><?php echo e($training->company->company_name ?? 'N/A'); ?></span></li>
                    <?php endif; ?>
                    <li class="detail-item">
                        <span class="detail-label">Date</span>
                        <span>
                            <?php echo e(optional($training->schedule)->from_date ? \Carbon\Carbon::parse($training->schedule->from_date)->format('M d, Y') : 'N/A'); ?>

                            to <?php echo e(optional($training->schedule)->to_date ? \Carbon\Carbon::parse($training->schedule->to_date)->format('M d, Y') : 'N/A'); ?>

                        </span>
                    </li>
                    <li class="detail-item">
                        <span class="detail-label">Time</span>
                        <span>
                            <?php echo e(optional($training->schedule)->from_time ? \Carbon\Carbon::parse($training->schedule->from_time)->format('h:i A') : 'N/A'); ?>

                            - <?php echo e(optional($training->schedule)->to_time ? \Carbon\Carbon::parse($training->schedule->to_time)->format('h:i A') : 'N/A'); ?>

                        </span>
                    </li>
                    <li class="detail-item"><span class="detail-label">Mode</span><span><?php echo e($training->mode); ?></span></li>
                </ul>

                <p class="intro" style="margin-bottom: 0;">For more details, you may visit the <a href="https://www.alpscalendar.com">ALPS Calendar</a></p>
            </div>
        </div>

        <div class="footer">
            <div class="signature">
                Best regards,<br>
                <strong>ALPS Calendar Team</strong>
            </div>
        </div>
    </div>
</div>

</body>
</html>
<?php /**PATH C:\Users\Lagman\Desktop\Codes\ALPs Calendar\ALPS-Calendar\resources\views\emails\training_notification.blade.php ENDPATH**/ ?>