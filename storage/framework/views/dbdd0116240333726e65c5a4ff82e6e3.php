<p>Hello <?php echo e($coordinator->name ?? 'Coordinator'); ?>,</p>

<?php if(!empty($isUpdate)): ?>
<p><strong>Update:</strong> The following training's driver arrangement has been edited, and this is a separate update notification.</p>
<?php else: ?>
<p>You have been assigned as the coordinator for the following training's driver arrangement:</p>
<?php endif; ?>

<ul>
    <li><strong>Course:</strong> <?php echo e($training->course->course_name ?? 'N/A'); ?></li>
    <li><strong>Company:</strong> <?php echo e($training->company->company_name ?? 'N/A'); ?></li>
    <li><strong>Facilitator:</strong> <?php echo e($training->facilitator->name ?? 'N/A'); ?></li>
    <li><strong>Location:</strong> <?php echo e($training->location ?? 'N/A'); ?></li>
    <li>
        <strong>Schedule:</strong>
        <?php echo e($training->schedule->from_date ?? ''); ?>

        <?php echo e($training->schedule->from_time ?? ''); ?>

        to
        <?php echo e($training->schedule->to_date ?? ''); ?>

        <?php echo e($training->schedule->to_time ?? ''); ?>

    </li>
</ul>

<h4>Driver Arrangement</h4>

<ul>
    <li>
        <strong>Transportation Needed:</strong>
        <?php echo e($training->need_transportation ? 'Yes' : 'No'); ?>

    </li>

    <?php if($training->need_transportation): ?>

        <?php if(!empty($training->outbound_pickup_time)): ?>
            <li>
                <strong>Outbound Pickup Time:</strong>
                <?php echo e($training->outbound_pickup_time); ?>

            </li>
        <?php endif; ?>

        <?php if(!empty($training->outbound_contact_number)): ?>
            <li>
                <strong>Outbound Contact Number:</strong>
                <?php echo e($training->outbound_contact_number); ?>

            </li>
        <?php endif; ?>

        <?php if(!empty($training->outbound_pickup_location)): ?>
            <li>
                <strong>Outbound Pickup Location:</strong>
                <?php echo e($training->outbound_pickup_location); ?>

            </li>
        <?php endif; ?>

        <?php if(!empty($training->outbound_dropoff_location)): ?>
            <li>
                <strong>Outbound Dropoff Location:</strong>
                <?php echo e($training->outbound_dropoff_location); ?>

            </li>
        <?php endif; ?>

        <li>
            <strong>Return Trip Needed:</strong>
            <?php echo e($training->return_trip_needed ? 'Yes' : 'No'); ?>

        </li>

        <?php if($training->return_trip_needed): ?>

            <?php if(!empty($training->return_pickup_time)): ?>
                <li>
                    <strong>Return Pickup Time:</strong>
                    <?php echo e($training->return_pickup_time); ?>

                </li>
            <?php endif; ?>

            <?php if(!empty($training->return_contact_number)): ?>
                <li>
                    <strong>Return Contact Number:</strong>
                    <?php echo e($training->return_contact_number); ?>

                </li>
            <?php endif; ?>

            <?php if(!empty($training->return_pickup_location)): ?>
                <li>
                    <strong>Return Pickup Location:</strong>
                    <?php echo e($training->return_pickup_location); ?>

                </li>
            <?php endif; ?>

            <?php if(!empty($training->return_dropoff_location)): ?>
                <li>
                    <strong>Return Dropoff Location:</strong>
                    <?php echo e($training->return_dropoff_location); ?>

                </li>
            <?php endif; ?>

        <?php endif; ?>

    <?php endif; ?>
</ul>

<p>
    Please make the necessary arrangements for:
    <strong><?php echo e($training->course->course_name ?? 'Training'); ?></strong>.
</p>

<p>
    Regards,<br>
    ALPS Calendar
</p><?php /**PATH D:\ALPs\ALPs Calendar\ALPS-Calendar\resources\views/emails/driver_notification.blade.php ENDPATH**/ ?>