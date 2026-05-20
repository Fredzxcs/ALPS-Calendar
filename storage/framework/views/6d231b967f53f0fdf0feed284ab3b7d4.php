<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<link rel="stylesheet" href="<?php echo e(asset('css/training-ui-redesign.css')); ?>">

<?php $__env->startSection('maincontent'); ?>
    <div class="d-flex justify-content-center align-items-center mt-20">
        <div class="container mt-5">
            <div class="shadow-sm border-0">
                <div class="alps-header-add rounded-top d-flex justify-content-center align-items-center py-4">
                    <h2 class="text-white fw-boldest m-0 fs-1">Add Training</h2>
                </div>
                <!--begin::Stepper-->
                <div class="stepper stepper-pills alps-card-glass-body p-md-10" id="kt_stepper_example_basic">
                    <!--begin::Nav-->
                    <div class="stepper-nav flex-center flex-wrap mb-10">
                        <div class="stepper-item mx-8 my-4 current" data-kt-stepper-element="nav">
                            <div class="stepper-wrapper d-flex align-items-center">
                                <div class="stepper-icon w-40px h-40px">
                                    <i class="stepper-check fas fa-check"></i>
                                    <span class="stepper-number">1</span>
                                </div>
                                <div class="stepper-label">
                                    <h3 class="stepper-title">Step 1</h3>
                                    <div class="stepper-desc">Training Details</div>
                                </div>
                            </div>
                            <div class="stepper-line h-40px"></div>
                        </div>

                        <div class="stepper-item mx-8 my-4" data-kt-stepper-element="nav">
                            <div class="stepper-wrapper d-flex align-items-center">
                                <div class="stepper-icon w-40px h-40px">
                                    <i class="stepper-check fas fa-check"></i>
                                    <span class="stepper-number">2</span>
                                </div>
                                <div class="stepper-label">
                                    <h3 class="stepper-title">Step 2</h3>
                                    <div class="stepper-desc">Driver Arrangement</div>
                                </div>
                            </div>
                            <div class="stepper-line h-40px"></div>
                        </div>
                    </div>
                    <!--end::Nav-->

                    <!--begin::Form-->
                    <form class="form mx-auto w-75 px-5" novalidate="novalidate" id="training-form">
                    <?php
                        $currentUser = auth()->user();
                        $googleConnected = ($currentUser instanceof \App\Models\User && !empty($currentUser->google_refresh_token)) || session('google_connected', false);
                    ?>

                    <div class="google-calendar-card mb-5">
                        <h3>Connect Account for Google Calendar Invites</h3>
                        <p>The connected account will be listed as the event organizer and used to send invites.</p>
                            <?php if($googleConnected): ?>
                            <div style="background: #d1fae5; color: #047857; padding: 0.75rem 1rem; border-radius: 0.5rem; font-size: 0.9rem; font-weight: 600;">
                                ✓ Connected - Ensure you are using your work profile.
                            </div>
                            <?php else: ?>
                            <a id="google_signin_btn" href="<?php echo e(route('google.redirect', ['from' => 'add_training'])); ?>" class="google-calendar-button">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/>
                                    <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
                                    <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/>
                                    <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
                                </svg>
                                SIGN IN WITH GOOGLE
                            </a>
                            <?php endif; ?>
                    </div>

                    <div class="mb-5">
                    <!-- STEP 1: Training Details -->
                    <div class="flex-column current" data-kt-stepper-element="content" id="training-step-1">
                        <!-- Mode of Training -->
                        <div class="training-form-row full">
                            <div class="training-form-group">
                                <label>Mode of Training <span class="required"></span></label>
                                <div class="training-radio-group">
                                    <div class="training-radio">
                                        <input type="radio" id="virtual" value="virtual" name="mode" checked>
                                        <label for="virtual">Virtual</label>
                                    </div>
                                    <div class="training-radio">
                                        <input type="radio" id="face-to-face" value="face-to-face" name="mode">
                                        <label for="face-to-face">Face-to-Face</label>
                                    </div>
                                    <div class="training-radio">
                                        <input type="radio" id="public-course" value="public-course" name="mode">
                                        <label for="public-course">Public Course</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Public Course: Course and In-person Training -->
                        <div class="training-form-row full d-none" id="public-course-container">
                            <div class="training-form-group">
                                <div style="display: flex; gap: 2rem; align-items: center;">
                                    <div class="training-checkbox">
                                        <input type="checkbox" id="inperson-training">
                                        <label for="inperson-training">In-person training?</label>
                                    </div>
                                    <div style="flex: 1;">
                                        <label for="public-course-select">Course <span class="required"></span></label>
                                        <select id="public-course-select" class="training-select" style="width: 100%; margin-top: 0.5rem;">
                                            <option value="" disabled selected>Select Course</option>
                                            <?php $__currentLoopData = $courses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $course): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($course->id); ?>">
                                                <?php echo e($course->course_code ? $course->course_code . ' - ' : ''); ?><?php echo e($course->course_name); ?>

                                            </option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Platform -->
                        <div class="training-form-row" id="platform-container">
                            <div class="training-form-group">
                                <label for="platform">Platform</label>
                                <select id="platform" class="training-select">
                                    <option value="" selected disabled>Select Platform</option>
                                    <option value="Zoom">Zoom</option>
                                    <option value="Google Meet">Google Meet</option>
                                    <option value="MS Teams">MS Teams</option>
                                    <option value="other">Other</option>
                                </select>
                                <input type="text" name="platform_other" id="platform_other" class="training-input d-none" style="margin-top: 0.5rem;"
                                    placeholder="Enter platform name">
                            </div>
                        </div>

                        <!-- Virtual Training Link -->
                        <div class="training-form-row full" id="conference-link-container">
                            <div class="training-form-group">
                                        <label for="conference_link"><span id="conference-link-label">Virtual Training Link</span><span id="conference-link-required" class="required d-none"></span></label>
                                <input type="url" name="conference_link" id="conference_link" class="training-input"
                                    placeholder="Enter the virtual training link (e.g., https://zoom.us/j/...)">
                            </div>
                        </div>

                        <!-- Account -->
                        <div class="training-form-row full d-none" id="credentials-container">
                            <div class="training-form-group">
                                <label for="credentials">Account</label>
                                <select id="credentials" class="training-select">
                                    <option value="" disabled selected>Select Host Email Account</option>
                                    <?php $__currentLoopData = $accounts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $account): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($account->id); ?>">
                                            <?php echo e($account->account_email); ?>

                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                        </div>

                        <!-- Location: Face-to-Face -->
                        <div class="training-form-row full d-none" id="location-container">
                            <div class="training-form-group">
                                <label for="location">Location <span class="required"></span></label>
                                <input type="text" id="location" class="training-input"
                                    placeholder="Enter Location">
                            </div>
                        </div>

                        <!-- Company and Course -->
                        <div class="training-form-row" id="company-course-container">
                            <div class="training-form-group">
                                <label for="company">Company <span class="required"></span></label>
                                <select id="company" class="training-select">
                                    <option value="" disabled selected>Select Company</option>
                                    <?php $__currentLoopData = $companies; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $company): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($company->id); ?>">
                                            <?php echo e($company->company_name); ?>

                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <option value="other">Other</option>
                                </select>
                                <input type="text" id="enter-company" class="training-input d-none" style="margin-top: 0.5rem;" placeholder="Enter Company">
                            </div>

                            <div class="training-form-group">
                                <label for="course">Course <span class="required"></span></label>
                                <select id="course" class="training-select">
                                    <option value="" disabled selected>Select Course</option>
                                    <?php $__currentLoopData = $courses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $course): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($course->id); ?>">
                                        <?php echo e($course->course_code ? $course->course_code . ' - ' : ''); ?><?php echo e($course->course_name); ?>

                                    </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                        </div>

                        <!-- Date and Time -->
                        <div class="training-form-row triple">
                            <div class="training-form-group">
                                <label for="date-range">Date Range <span class="required"></span></label>
                                <input type="text" id="date-range" class="training-input" placeholder="Select Date" readonly>
                            </div>
                            <div class="training-form-group">
                                <label for="time-start">Time Start <span class="required"></span></label>
                                <input type="time" id="time-start" class="training-input">
                            </div>
                            <div class="training-form-group">
                                <label for="time-end">Time End <span class="required"></span></label>
                                <input type="time" id="time-end" class="training-input">
                            </div>
                        </div>

                        <!-- People -->
                        <div class="training-form-row triple">
                            <div class="training-form-group">
                                <label for="facilitator">Facilitator <span class="required"></span></label>
                                <select id="facilitator" name="facilitator_id" class="training-select">
                                    <option disabled selected>Select Facilitator</option>
                                    <option value="">No Facilitator Yet</option>
                                    <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($user->id); ?>"><?php echo e($user->name); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>

                            <div class="training-form-group">
                                <label for="account_manager">Account Manager</label>
                                <select id="account_manager" name="account_manager_id" class="training-select">
                                    <option disabled selected>Select Account Manager</option>
                                    <option value="">No Account Manager Yet</option>
                                    <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($user->id); ?>"><?php echo e($user->name); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>

                            <div class="training-form-group">
                                <label for="assistant_select">Assistant</label>
                                <div style="display: flex; gap: 0.5rem; align-items: flex-start;">
                                    <select id="assistant_select" class="training-select" style="flex: 1;">
                                        <option value="" selected disabled>Select Assistant</option>
                                        <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($user->id); ?>"><?php echo e($user->name); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                    <button type="button" id="assistant_add_btn" class="training-btn training-btn-secondary-blue">ADD</button>
                                </div>
                                <div id="assistant_list_container" style="margin-top: 0.75rem; display: flex; flex-wrap: wrap; gap: 0.75rem; align-items: center; min-height: 0;"></div>
                                <input type="hidden" id="assistant_list" value="">
                                <div class="training-helper-text">Select one assistant and click Add to include multiple assistants.</div>
                            </div>
                        </div>
                    </div>

                    <!-- STEP 2: Driver Arrangement -->
                    <div class="flex-column" data-kt-stepper-element="content" id="training-step-2">
                        <div style="margin-bottom: 1.5rem;">
                            <div class="training-section-heading">Driver Arrangement</div>
                            <div class="training-section-subheading">Configure transportation only if needed.</div>
                        </div>

                        <!-- Transportation Needed -->
                        <div class="training-form-row full">
                            <div class="training-form-group">
                                <label>Do you need a transportation? <span class="required"></span></label>
                                <div class="training-radio-group">
                                    <div class="training-radio">
                                        <input type="radio" id="need_transportation_yes" name="need_transportation" value="yes">
                                        <label for="need_transportation_yes">Yes, I need a driver</label>
                                    </div>
                                    <div class="training-radio">
                                        <input type="radio" id="need_transportation_no" name="need_transportation" value="no" checked>
                                        <label for="need_transportation_no">No transportation needed</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Driver Arrangement Fields -->
                        <div id="driver-arrangement-fields" class="d-none">
                            <!-- Outbound Trip -->
                            <div class="trip-section">
                                <div class="trip-section-heading">Outbound Trip</div>
                                <div class="training-form-row quad">
                                    <div class="training-form-group">
                                        <label for="outbound_pickup_time">Pickup Time <span class="required"></span></label>
                                        <input type="time" id="outbound_pickup_time" class="training-input">
                                    </div>
                                    <div class="training-form-group">
                                        <label for="outbound_contact_number">Contact Number <span class="required"></span></label>
                                        <input type="text" id="outbound_contact_number" class="training-input" placeholder="Contact number">
                                    </div>
                                    <div class="training-form-group">
                                        <label for="outbound_pickup_location">Pickup Location <span class="required"></span></label>
                                        <input type="text" id="outbound_pickup_location" class="training-input" placeholder="Pickup location">
                                    </div>
                                    <div class="training-form-group">
                                        <label for="outbound_dropoff_location">Drop-off Location <span class="required"></span></label>
                                        <input type="text" id="outbound_dropoff_location" class="training-input" placeholder="Drop-off location">
                                    </div>
                                </div>
                            </div>

                            <!-- Return Trip -->
                            <div class="trip-section">
                                <div class="training-checkbox">
                                    <input type="checkbox" id="return_trip_needed">
                                    <label for="return_trip_needed">Return trip needed</label>
                                </div>

                                <div id="return-trip-fields" class="d-none" style="margin-top: 1rem;">
                                    <div class="trip-section-heading">Return Trip</div>
                                    <div class="training-form-row quad">
                                        <div class="training-form-group">
                                            <label for="return_pickup_time">Return Time <span class="required"></span></label>
                                            <input type="time" id="return_pickup_time" class="training-input">
                                        </div>
                                        <div class="training-form-group">
                                            <label for="return_contact_number">Contact Number <span class="required"></span></label>
                                            <input type="text" id="return_contact_number" class="training-input" placeholder="Contact number">
                                        </div>
                                        <div class="training-form-group">
                                            <label for="return_pickup_location">Pickup Location <span class="required"></span></label>
                                            <input type="text" id="return_pickup_location" class="training-input" placeholder="Pickup location">
                                        </div>
                                        <div class="training-form-group">
                                            <label for="return_dropoff_location">Drop-off Location <span class="required"></span></label>
                                            <input type="text" id="return_dropoff_location" class="training-input" placeholder="Drop-off location">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Notify Heads -->
                            <div class="trip-section" style="margin-top: 1.5rem;">
                                <div class="trip-section-heading">Notify Heads</div>
                                <div class="training-checkbox">
                                    <input type="checkbox" id="notify_coordinator">
                                    <label for="notify_coordinator">Notify Coordinator</label>
                                </div>

                                <div id="coordinator-to-notify-container" class="d-none" style="margin-top: 1rem;">
                                    <div class="training-form-group">
                                        <label for="coordinator_to_notify_select">Driver Coordinator <span class="required"></span></label>
                                        <div style="display: flex; gap: 0.5rem;">
                                            <select id="coordinator_to_notify_select" class="training-select" style="flex: 1;">
                                                <option value="" selected disabled>Select Coordinator</option>
                                                <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <option value="<?php echo e($user->id); ?>"><?php echo e($user->name); ?></option>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </select>
                                            <button type="button" id="coordinator_add_btn" class="training-btn training-btn-secondary-blue">ADD</button>
                                        </div>
                                        <div id="coordinator_list_container" style="margin-top: 0.75rem; display: flex; flex-wrap: wrap; gap: 0.75rem; align-items: center; min-height: 0;"></div>
                                        <input type="hidden" id="coordinator_to_notify_list" value="">
                                        <div class="training-helper-text">Select one coordinator and click Add to include multiple coordinators.</div>
                                    </div>
                                </div>

                                <!-- Removed specific-person notify checkboxes - only coordinator remains -->
                            </div>
                        </div>
                    </div>

                    <!-- Buttons -->
                    <div class="d-flex flex-stack mt-5">
                        <div class="me-2 d-flex gap-2">
                            <a href="<?php echo e(route('calendar')); ?>" class="btn btn-light btn-active-light-primary">CANCEL</a>
                            <button type="button" class="btn btn-light btn-active-light-primary" id="add_training_back" data-kt-stepper-action="previous">BACK</button>
                        </div>
                        <div>
                            <button type="button" class="btn btn-primary btn-green" id="add_training_submit" data-kt-stepper-action="submit">
                                <span class="indicator-label">SAVE</span>
                                <span class="indicator-progress">
                                    Please wait... <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                </span>
                            </button>
                            <button type="button" class="btn btn-primary" data-kt-stepper-action="next">
                                CONTINUE
                            </button>
                        </div>
                    </div>
                    </div>
                    </form>
                    <!--end::Form-->
                </div>
                <!--end::Stepper-->
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="<?php echo e(asset('js/add_training.js')); ?>"></script>
    <script src="<?php echo e(asset('plugins/custom/formrepeater/formrepeater.bundle.js')); ?>"></script>

    <script>
        $('#asst_repeat').repeater({
            initEmpty: false,
            defaultValues: {
                'text-input': 'foo'
            },
            show: function () {
                $(this).slideDown();
            },
            hide: function (deleteElement) {
                $(this).slideUp(deleteElement);
            }
        });
    </script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('global.layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\ALPs\ALPs Calendar\ALPS-Calendar\resources\views/add_training/add_training.blade.php ENDPATH**/ ?>