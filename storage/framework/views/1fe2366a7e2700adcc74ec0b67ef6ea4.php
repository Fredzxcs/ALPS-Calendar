<!DOCTYPE html>
<html>
<head>
    <title>Driver Arrangement Notification</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" />
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
            background:
                radial-gradient(circle at top left, rgba(14, 165, 233, 0.14), transparent 30%),
                radial-gradient(circle at top right, rgba(20, 184, 166, 0.12), transparent 28%),
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
            background: rgba(20, 184, 166, 0.12);
            color: #0f766e;
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
            background: linear-gradient(135deg, rgba(236, 254, 255, 0.96), rgba(255, 255, 255, 0.9));
            border: 1px solid rgba(20, 184, 166, 0.14);
            border-left: 6px solid #14b8a6;
            box-shadow: 0 12px 30px rgba(20, 184, 166, 0.08);
        }
        .update-alert-title {
            margin: 0 0 6px 0;
            color: #0f766e;
            font-size: 15px;
            font-weight: 700;
            letter-spacing: 0.2px;
        }
        .update-alert-text {
            margin: 0;
            color: #115e59;
            font-size: 15px;
            line-height: 1.55;
        }
        .details-list {
            list-style: none;
            padding: 0;
            margin: 0 0 18px;
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
        .detail-block {
            margin-top: 8px;
            padding-top: 12px;
            border-top: 1px solid rgba(148, 163, 184, 0.18);
            color: #34516f;
        }
        .trip-list {
            display: block;
            margin: 0 0 18px;
        }
        .trip-card {
            padding: 16px 0;
            border-top: 1px solid rgba(148, 163, 184, 0.18);
        }
        .trip-card:first-child {
            border-top: 0;
            padding-top: 0;
        }
        .trip-title {
            margin: 0 0 8px 0;
            color: #0f2744;
            font-size: 16px;
            font-weight: 700;
        }
        .trip-meta {
            margin: 0 0 10px 0;
            color: #2563eb;
            font-size: 13px;
            font-weight: 700;
            letter-spacing: 0.2px;
            text-transform: uppercase;
        }
        .trip-grid {
            display: grid;
            grid-template-columns: 84px 1fr;
            gap: 6px 12px;
            font-size: 15px;
            color: #203a56;
        }
        .trip-label {
            font-weight: 700;
            color: #567089;
        }
        .trip-time {
            align-self: start;
            font-size: 18px;
            font-weight: 700;
            color: #0f2744;
            padding-top: 2px;
        }
        .trip-right {
            display: grid;
            gap: 2px;
            padding-left: 8px;
        }
        .trip-right-row {
            display: grid;
            grid-template-columns: auto 1fr;
            gap: 6px;
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
                margin-top: -42px;
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
</head>
<body>

<div class="page-shell">
    <div class="email-container">
        <div class="top-banner" role="img" aria-label="ALPs header background"></div>

        <div class="content">
            <div class="hero-card">
                <div class="brand-pill">Driver Arrangement</div>
                <p class="greeting">Hello <?php echo e($coordinator->name ?? 'Coordinator'); ?>,</p>

                <?php if(!empty($isUpdate)): ?>
                    <div class="update-alert">
                        <p class="update-alert-title">Update Notice</p>
                        <p class="update-alert-text">The following training's driver arrangement has been edited, and this is a separate update notification.</p>
                    </div>
                <?php else: ?>
                    <p class="intro">You have been assigned as the coordinator for the following training's driver arrangement:</p>
                <?php endif; ?>

                <p class="intro" style="margin-top: 0;">Training Details</p>

                <ul class="details-list">
                    <li class="detail-item"><span class="detail-label">Course</span><span><?php echo e($training->course->course_name ?? 'N/A'); ?></span></li>
                    <li class="detail-item"><span class="detail-label">Company</span><span><?php echo e($training->company->company_name ?? 'N/A'); ?></span></li>
                    <li class="detail-item">
                        <span class="detail-label">Facilitator</span>
                        <span>
                            <?php echo e($training->facilitator->name ?? 'N/A'); ?>

                            <?php $assistantCount = $training->assistants->count(); ?>
                            <?php if($assistantCount > 0): ?>
                                (+<?php echo e($assistantCount); ?> <?php echo e($assistantCount === 1 ? 'Assistant' : 'Assistants'); ?>)
                            <?php endif; ?>
                        </span>
                    </li>
                    <li class="detail-item"><span class="detail-label">Location</span><span><?php echo e($training->location ?? 'N/A'); ?></span></li>
                    <li class="detail-item">
                        <span class="detail-label">Schedule</span>
                        <span>
                            <?php echo e($training->schedule->from_date ?? ''); ?>

                            <?php echo e($training->schedule->from_time ?? ''); ?>

                            to
                            <?php echo e($training->schedule->to_date ?? ''); ?>

                            <?php echo e($training->schedule->to_time ?? ''); ?>

                        </span>
                    </li>
                </ul>

                <p class="intro" style="margin: 0 0 10px;">Driver Arrangement</p>

                <?php if($training->need_transportation): ?>
                    <?php
                        $returnTripNeeded = filter_var($training->return_trip_needed ?? false, FILTER_VALIDATE_BOOLEAN);
                    ?>
                    <div class="trip-list">
                        <div class="trip-card">
                            <p class="trip-title">Outbound Trip</p>
                            <p class="trip-meta"><?php echo e($training->outbound_pickup_location ?? 'Pickup location unavailable'); ?> to <?php echo e($training->outbound_dropoff_location ?? 'Dropoff location unavailable'); ?></p>
                            <div class="trip-grid">
                                <span class="trip-time"><?php echo e($training->outbound_pickup_time ?? 'N/A'); ?></span>
                                <div class="trip-right">
                                    <div class="trip-right-row"><span class="trip-label">Pickup</span><span><?php echo e($training->outbound_pickup_location ?? 'N/A'); ?></span></div>
                                    <div class="trip-right-row"><span class="trip-label">Dropoff</span><span><?php echo e($training->outbound_dropoff_location ?? 'N/A'); ?></span></div>
                                    <div class="trip-right-row"><span class="trip-label">Contact</span><span><?php echo e($training->outbound_contact_number ?? 'N/A'); ?></span></div>
                                </div>
                            </div>
                        </div>

                        <?php if($returnTripNeeded): ?>
                            <div class="trip-card">
                                <p class="trip-title">Return Trip</p>
                                <p class="trip-meta"><?php echo e($training->return_pickup_location ?? 'Pickup location unavailable'); ?> to <?php echo e($training->return_dropoff_location ?? 'Dropoff location unavailable'); ?></p>
                                <div class="trip-grid">
                                    <span class="trip-time"><?php echo e($training->return_pickup_time ?? 'N/A'); ?></span>
                                    <div class="trip-right">
                                        <div class="trip-right-row"><span class="trip-label">Pickup</span><span><?php echo e($training->return_pickup_location ?? 'N/A'); ?></span></div>
                                        <div class="trip-right-row"><span class="trip-label">Dropoff</span><span><?php echo e($training->return_dropoff_location ?? 'N/A'); ?></span></div>
                                        <div class="trip-right-row"><span class="trip-label">Contact</span><span><?php echo e($training->return_contact_number ?? 'N/A'); ?></span></div>
                                    </div>
                                </div>
                            </div>
                        <?php else: ?>
                            <div class="trip-card">
                                <p class="trip-title">Return Trip</p>
                                <div class="detail-block" style="margin-top: 0; padding-top: 0; border-top: 0;">
                                    No return transportation needed
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php else: ?>
                    <div class="detail-block" style="margin-top: 0;">
                        Transportation is not required for this training.
                    </div>
                <?php endif; ?>

                <div class="detail-block">
                    Please make the necessary arrangements for <strong><?php echo e($training->course->course_name ?? 'Training'); ?></strong>.
                </div>

                <p class="intro" style="margin: 16px 0 0;">For more details, you may visit the <a href="https://www.alpscalendar.com">ALPS Calendar</a></p>
            </div>
        </div>

        <div class="footer">
            <div class="signature">
                Best regards,<br>
                <strong>ALPS Calendar</strong>
            </div>
        </div>
    </div>
</div>

</body>
</html><?php /**PATH C:\Users\Lagman\Desktop\Codes\ALPs Calendar\ALPS-Calendar\resources\views\emails\driver_notification.blade.php ENDPATH**/ ?>