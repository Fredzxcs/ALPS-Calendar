<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<link rel="stylesheet" href="<?php echo e(asset('css/training-ui-redesign.css')); ?>">

<?php $__env->startSection('maincontent'); ?>
    <div class="training-page-wrapper">
        <div class="training-card-container">
            <!-- Header -->
            <div class="training-header training-header-unavailability">
                Add Unavailability
            </div>

            <!-- User Info Section -->
            <div style="background: #f8fafc; padding: 1.5rem; text-align: center; border-bottom: 1px solid #e2e8f0;">
                <h3 style="font-size: 1.2rem; font-weight: 700; color: #1e293b; margin-bottom: 0.5rem;"><?php echo e($user->name); ?></h3>
                <?php if($user->usertype === "admin"): ?>
                    <span style="display: inline-block; background: #fef3c7; color: #b45309; padding: 0.375rem 0.75rem; border-radius: 0.375rem; font-size: 0.8rem; font-weight: 600;">SYSTEM ADMIN</span>
                <?php elseif($user->usertype === "facilitator"): ?>
                    <span style="display: inline-block; background: #cffafe; color: #0369a1; padding: 0.375rem 0.75rem; border-radius: 0.375rem; font-size: 0.8rem; font-weight: 600;">FACILITATOR</span>
                <?php elseif($user->usertype === "coordinator"): ?>
                    <span style="display: inline-block; background: #dbeafe; color: #1e40af; padding: 0.375rem 0.75rem; border-radius: 0.375rem; font-size: 0.8rem; font-weight: 600;">COORDINATOR</span>
                <?php endif; ?>
            </div>

            <!-- Form Content -->
            <div class="training-form-content">
                <form id="add_unavailability_form">
                    <!-- Date Range -->
                    <div class="training-form-row full">
                        <div class="training-form-group">
                            <label for="add_unavailable_date">Date Range <span class="required">*</span></label>
                            <input type="text" id="add_unavailable_date" class="training-input" placeholder="Select Date" readonly>
                        </div>
                    </div>

                    <!-- Purpose -->
                    <div class="training-form-row full">
                        <div class="training-form-group">
                            <label for="add_unavailable_purpose">Purpose <span class="required">*</span></label>
                            <input type="text" id="add_unavailable_purpose" class="training-input" placeholder="Enter Purpose of Unavailability">
                        </div>
                    </div>

                    <!-- Buttons -->
                    <div class="training-button-group">
                        <a href="<?php echo e(route('calendar')); ?>" class="training-btn training-btn-secondary">CANCEL</a>
                        <button type="submit" id="add_unavailability_submit" class="training-btn training-btn-primary">SAVE</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
    <script>
        let user = <?php echo e($user->id); ?>

    </script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="<?php echo e(asset('js/add_unavailability.js')); ?>"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('global.layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\ALPs\ALPs Calendar\ALPS-Calendar\resources\views/unavailability/add_unavailability.blade.php ENDPATH**/ ?>