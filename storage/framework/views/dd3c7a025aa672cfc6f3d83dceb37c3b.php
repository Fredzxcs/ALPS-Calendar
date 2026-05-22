<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<link rel="stylesheet" href="<?php echo e(asset('css/training-ui-redesign.css')); ?>">

<?php $__env->startSection('maincontent'); ?>
    <div class="training-page-wrapper">
        <div class="training-card-container">
            <!-- Header -->
            <div class="training-header training-header-edit">
                Edit Training
            </div>

            <!-- Step Indicators -->
            <div class="training-step-indicators">
                <div class="training-step active" id="step-1-indicator">
                    <div class="training-step-badge">1</div>
                    <span>Step 1<br><small style="font-size: 0.8rem;">Training Details</small></span>
                </div>
                <div class="training-step" id="step-2-indicator">
                    <div class="training-step-badge">2</div>
                    <span>Step 2<br><small style="font-size: 0.8rem;">Account Creation</small></span>
                </div>
            </div>

            <!-- Form Content -->
            <div class="training-form-content">
                <form>
                    <?php
                        $currentUser = auth()->user();
                        $googleConnected = ($currentUser instanceof \App\Models\User && !empty($currentUser->google_refresh_token)) || session('google_connected', false);
                    ?>

                    <!-- STEP 1: Training Details -->
                    <div id="training-step-1">
                        <!-- Training Details Section -->
                        <div style="margin-bottom: 1.5rem;">
                            <div class="training-section-heading">Training Details</div>
                            <div class="training-section-subheading">Review and modify training information.</div>
                        </div>

                        <!-- Mode of Training -->
                        <div class="training-form-row full">
                            <div class="training-form-group">
                                <label>Mode of Training <span class="required">*</span></label>
                                <div class="training-radio-group">
                                    <div class="training-radio">
                                        <input type="radio" id="virtual" value="virtual" name="mode" 
                                            <?php echo e($training->mode == 'virtual' ? 'checked' : ''); ?>>
                                        <label for="virtual">Virtual</label>
                                    </div>
                                    <div class="training-radio">
                                        <input type="radio" id="face-to-face" value="face-to-face" name="mode"
                                            <?php echo e($training->mode == 'face-to-face' ? 'checked' : ''); ?>>
                                        <label for="face-to-face">Face-to-Face</label>
                                    </div>
                                    <div class="training-radio">
                                        <input type="radio" id="public-course" value="public-course" name="mode"
                                            <?php echo e($training->mode == 'public-course' ? 'checked' : ''); ?>>
                                        <label for="public-course">Public Course</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Account and Platform -->
                        <div class="training-form-row" id="credentials-container">
                            <div class="training-form-group">
                                <label for="credentials">Account <span class="required">*</span></label>
                                <select id="credentials" class="training-select">
                                    <option value="" disabled>Select Host Email Account</option>
                                    <?php $__currentLoopData = $accounts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $account): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($account->id); ?>" 
                                            <?php echo e($training->account_id == $account->id ? 'selected' : ''); ?>>
                                            <?php echo e($account->account_email); ?>

                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>

                            <div class="training-form-group">
                                <label for="platform">Platform</label>
                                <select id="platform" class="training-select">
                                    <option value="" disabled selected>Select Platform</option>
                                    <option value="Zoom" <?php echo e($training->platform == 'Zoom' ? 'selected' : ''); ?>>Zoom</option>
                                    <option value="Google Meet" <?php echo e($training->platform == 'Google Meet' ? 'selected' : ''); ?>>Google Meet</option>
                                    <option value="MS Teams" <?php echo e($training->platform == 'MS Teams' ? 'selected' : ''); ?>>MS Teams</option>
                                    <option value="other" <?php echo e($training->platform && !in_array($training->platform, ['Zoom', 'Google Meet', 'MS Teams']) ? 'selected' : ''); ?>>Other</option>
                                </select>
                                <input type="text" name="platform_other" id="platform_other" class="training-input" 
                                    style="margin-top: 0.5rem;"
                                    placeholder="Enter platform name"
                                    value="<?php echo e($training->platform && !in_array($training->platform, ['Zoom', 'Google Meet', 'MS Teams']) ? $training->platform : ''); ?>"
                                    <?php echo e($training->platform && !in_array($training->platform, ['Zoom', 'Google Meet', 'MS Teams']) ? '' : 'class=d-none'); ?>>
                            </div>
                        </div>

                        <!-- Virtual Training Link -->
                        <div class="training-form-row full" id="conference-link-container">
                            <div class="training-form-group">
                                <label for="conference_link"><span id="conference-link-label">Virtual Training Link</span><span id="conference-link-required" class="required d-none">*</span></label>
                                <input type="url" name="conference_link" id="conference_link" class="training-input"
                                    placeholder="Enter the virtual training link (e.g., https://zoom.us/j/...)"
                                    value="<?php echo e($training->conference_link ?? ''); ?>">
                            </div>
                        </div>

                        <!-- Location: Face-to-Face -->
                        <div class="training-form-row full" id="location-container">
                            <div class="training-form-group">
                                <label for="location">Location <span class="required">*</span></label>
                                <input type="text" id="location" class="training-input"
                                    placeholder="Enter Location"
                                    value="<?php echo e($training->location ?? ''); ?>">
                            </div>
                        </div>

                        <!-- Company and Course -->
                        <div class="training-form-row" id="company-course-container">
                            <div class="training-form-group">
                                <label for="company">Company <span class="required">*</span></label>
                                <select id="company" class="training-select">
                                    <option value="" disabled>Select Company</option>
                                    <?php $__currentLoopData = $companies; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $company): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($company->id); ?>"
                                            <?php echo e($training->company_id == $company->id ? 'selected' : ''); ?>>
                                            <?php echo e($company->company_name); ?>

                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <option value="other" <?php echo e($training->company_id && !$companies->contains($training->company_id) ? 'selected' : ''); ?>>Other</option>
                                </select>
                                <input type="text" id="enter-company" class="training-input" 
                                    style="margin-top: 0.5rem;" placeholder="Enter Company"
                                    value="<?php echo e($training->company_id && !$companies->contains($training->company_id) ? $training->company_name : ''); ?>"
                                    <?php echo e($training->company_id && !$companies->contains($training->company_id) ? '' : 'class=d-none'); ?>>
                            </div>

                            <div class="training-form-group">
                                <label for="course">Course <span class="required">*</span></label>
                                <select id="course" class="training-select">
                                    <option value="" disabled>Select Course</option>
                                    <?php $__currentLoopData = $courses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $course): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($course->id); ?>"
                                        <?php echo e($training->course_id == $course->id ? 'selected' : ''); ?>>
                                        <?php echo e($course->course_code ? $course->course_code . ' - ' : ''); ?><?php echo e($course->course_name); ?>

                                    </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                        </div>

                        <!-- Date and Time -->
                        <div class="training-form-row triple">
                            <div class="training-form-group">
                                <label for="date-range">Date Range <span class="required">*</span></label>
                                <input type="text" id="date-range" class="training-input" placeholder="Select Date" readonly
                                    value="<?php echo e($training->from_date && $training->to_date ? date('m-d-Y', strtotime($training->from_date)) . ' to ' . date('m-d-Y', strtotime($training->to_date)) : ''); ?>">
                            </div>
                            <div class="training-form-group">
                                <label for="time-start">Time Start <span class="required">*</span></label>
                                <input type="time" id="time-start" class="training-input"
                                    value="<?php echo e($training->from_time ?? ''); ?>">
                            </div>
                            <div class="training-form-group">
                                <label for="time-end">Time End <span class="required">*</span></label>
                                <input type="time" id="time-end" class="training-input"
                                    value="<?php echo e($training->to_time ?? ''); ?>">
                            </div>
                        </div>

                        <!-- Google Calendar Card -->
                        <div class="google-calendar-card">
                            <h3>Google Calendar Interaction (Active)</h3>
                            <p>All invitations for this session will be sent and managed from:</p>
                            <div style="background: #f3f4f6; padding: 1rem; border-radius: 0.5rem; margin: 1rem 0; font-size: 0.9rem;">
                                <strong><?php echo e($training->account_email ?? 'admin.user@alprograms.local'); ?></strong><br>
                                <small style="color: #64748b;">(Signed-in Google Account)</small>
                            </div>
                            <div style="font-size: 0.85rem; color: #64748b;">Receipts will see this account as the event organizer</div>
                        </div>

                        <!-- Facilitator and Assistant -->
                        <div class="training-form-row">
                            <div class="training-form-group">
                                <label for="facilitator">Facilitator</label>
                                <select id="facilitator" class="training-select">
                                    <option selected>Select Facilitator</option>
                                    <option value="">No Facilitator Yet</option>
                                    <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($user->id); ?>"
                                            <?php echo e($training->facilitator_id == $user->id ? 'selected' : ''); ?>>
                                            <?php echo e($user->name); ?>

                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>

                            <div class="training-form-group">
                                <label for="assistant_select">Assistant</label>
                                <div style="display: flex; gap: 0.5rem;">
                                    <select id="assistant_select" class="training-select" style="flex: 1;">
                                        <option value="" selected disabled>Select Assistant</option>
                                        <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($user->id); ?>"><?php echo e($user->name); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                    <button type="button" id="assistant_add_btn" class="training-btn training-btn-secondary-blue">ADD</button>
                                </div>
                                <div id="assistant_list_container" style="margin-top: 0.75rem;">
                                    <?php if($training->assistants): ?>
                                        <?php $__currentLoopData = $training->assistants; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $assistant): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <div class="d-flex align-items-center mb-2">
                                                <span style="padding: 0.5rem 1rem; background: #dbeafe; border-radius: 0.5rem; font-size: 0.9rem; flex: 1;">
                                                    <?php echo e($assistant->name); ?> <span class="remove-assistant-badge" style="cursor: pointer; margin-left: 0.5rem;">✕</span>
                                                </span>
                                            </div>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <?php endif; ?>
                                </div>
                                <input type="hidden" id="assistant_list" value="<?php echo e($training->assistants ? implode(', ', $training->assistants->pluck('id')->toArray()) : ''); ?>">
                                <div class="training-helper-text">Select one assistant and click Add to include multiple assistants.</div>
                            </div>
                        </div>
                    </div>

                    <!-- STEP 2: Account Creation (for Edit) -->
                    <div id="training-step-2" class="d-none">
                        <div style="margin-bottom: 1.5rem;">
                            <div class="training-section-heading">Account Creation</div>
                            <div class="training-section-subheading">Finalize your training setup.</div>
                        </div>

                        <div class="training-form-row full">
                            <div class="training-form-group">
                                <label>Confirm all details and save changes</label>
                                <p style="color: #64748b; font-size: 0.95rem;">Review your training information above and click "Save" to update the training event.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Buttons -->
                    <div class="training-button-group">
                        <a href="<?php echo e(route('calendar')); ?>" class="training-btn training-btn-secondary">CANCEL</a>
                        <button type="button" id="add_training_back" class="training-btn training-btn-secondary-blue d-none">BACK</button>
                        <button type="button" id="add_training_submit" class="training-btn training-btn-primary">CONTINUE</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script>
        window.isEditMode = true;
        window.trainingId = <?php echo e($training->id); ?>;
    </script>
    <script src="<?php echo e(asset('js/add_training.js')); ?>"></script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('global.layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Lagman\Desktop\Codes\ALPs Calendar\ALPS-Calendar\resources\views\add_training\edit_training_redesign.blade.php ENDPATH**/ ?>