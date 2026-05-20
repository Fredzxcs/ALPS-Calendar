<?php $__env->startSection('maincontent'); ?>
    <div class="alps-calendar-shell d-flex flex-wrap justify-content-center gap-4 mt-20">
        <!-- Right Side: Calendar -->
        <div class="shadow-sm alps-card">
            <div class="d-flex justify-content-between align-items-center">

                <!-- Filter Button -->
                <div class="dropdown alps-calendar-filter-dropdown">
                <button class="btn btn-secondary btn-orange rounded-3 fw-boldest d-flex align-items-center btn-hover-rise dropdown-toggle"
                    data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false">
                    <i class="bi bi-funnel me-1"></i> FILTER
                </button>

                <!-- Filter Menu -->
                <div class="dropdown-menu menu menu-sub menu-sub-dropdown w-250px w-md-300px alps-calendar-filter-menu">
                    <!-- Menu Header -->
                    <div class="px-7 py-5">
                        <div class="fs-5 text-dark fw-bolder">Filter Options</div>
                    </div>

                    <!-- Separator -->
                    <div class="separator border-gray-200"></div>

                    <!-- Filter Form -->
                    <div class="px-7 py-5">
                        <form id="calendarFilterForm">
                            <!-- Show Dropdown -->
                            <div class="mb-10">
                                <label class="form-label fw-bold">Show in Calendar:</label>
                                <select id="filters" class="form-select form-select-solid" data-placeholder="Select option"
                                    data-allow-clear="true" id="calendarFilterSelect">
                                    <option value="all" selected>Show All</option>
                                    <option value="trainings">Trainings</option>
                                    <option value="unavailability">Unavailability</option>
                                </select>
                            </div>
                        </form>

                        <!-- Actions -->
                        <div class="d-flex justify-content-end">
                            <!-- <button type="reset" class="btn btn-sm btn-light btn-active-light-primary me-2" id="calendarFilterReset"
                                data-kt-menu-dismiss="true">RESET</button> -->
                            <button id="applyFilter" type="button" class="btn btn-sm btn-secondary btn-blue" id="calendarFilterApply">APPLY</button>
                        </div>
                    </div>
                </div>
                </div>
                <!-- End Filter Menu -->



                     <!--begin::Add Button-->
                    <button type="button" class="btn btn-primary btn-orange rounded-3 fw-boldest btn-hover-rise w-125px dropdown-toggle" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                        <span >ADD</span>
                    </button>
                    <!--end::Add Button-->

                <ul class="dropdown-menu px-2" aria-labelledby="dropdownMenuButton">
                    <?php if(Auth::check() && in_array(Auth::user()->usertype, ['admin', 'coordinator'])): ?>
                        <!--begin::Link item-->
                        <li class="py-2">
                            <a href="<?php echo e(route('add_training')); ?>" class="dropdown-item">
                                <i class="bi bi-pencil-square text-primary fs-6 me-2"></i>
                                <span class="text-gray-700 fw-bold">Training</span>
                            </a>
                        </li>
                        <!--end::Link item-->
                    <?php endif; ?>
                    <!--begin::Link item-->
                    <li>
                        <a href="<?php echo e(route('add_unavailability')); ?>" class="dropdown-item" id="event_view">
                            <i class="bi bi-calendar-event text-info fs-6 me-2"></i>
                            <span class="text-gray-700 fw-bold">Unavailability</span>
                        </a>
                    </li>
                    <!--end::Link item-->
                </ul>
            </div>
            <div class="card alps-calendar-inner-card">
                <div>
                    <div class="d-flex align-items-center justify-content-center">
                        <!-- Loader wrapper positioned above the calendar -->
                        <div id="loader-wrapper" class="position-absolute top-0 start-0 end-0 bottom-0 d-flex justify-content-center align-items-center mb-10 alps-loader-wrapper">
                            <div class="spinner-border alps-loader-spinner" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </div>
                    </div>
                    <div id="calendar" class="alps-calendar-root">
                        <!-- FullCalendar will be rendered here -->
                        <!-- Loader -->
                    </div>
                </div>
            </div>
        </div>
    </div>

        

    <style>
        #kt_modal_view_training .modal-content {
            border: 0;
            border-radius: 18px;
            overflow: hidden;
            box-shadow: 0 24px 70px rgba(17, 29, 51, 0.18);
        }

        #kt_modal_view_training .modal-header {
            padding: 1.25rem 1.9rem 0.95rem;
            border-bottom: 0;
        }

        #kt_modal_view_training .modal-body {
            padding: 0.2rem 1.9rem 1.55rem;
        }

        #kt_modal_view_training .modal-footer {
            padding: 0.65rem 1.9rem 1.05rem;
        }

        #kt_modal_view_training .alps-modal-course {
            margin: 0;
            color: #159de6;
            font-size: 1.45rem;
            line-height: 1.1;
            font-weight: 800;
        }

        #kt_modal_view_training .alps-modal-company {
            margin-top: 0.2rem;
            color: #8f1111;
            font-size: 0.98rem;
            font-weight: 800;
        }

        #kt_modal_view_training .alps-modal-dates {
            margin-top: 0.2rem;
            color: #1f2d4d;
            font-size: 0.98rem;
            line-height: 1.35;
            font-weight: 600;
        }

        #kt_modal_view_training .alps-modal-tabs {
            display: flex;
            gap: 0.25rem;
            margin: 1.2rem 0 1.35rem;
            padding: 0.25rem;
            border-radius: 999px;
            background: #eef4fb;
        }

        #kt_modal_view_training .alps-modal-tab {
            flex: 1;
            border: 0;
            border-radius: 999px;
            padding: 0.78rem 1rem;
            background: transparent;
            color: #1f3558;
            font-size: 0.86rem;
            font-weight: 800;
            letter-spacing: 0.02em;
            transition: background-color 0.18s ease, box-shadow 0.18s ease;
        }

        #kt_modal_view_training .alps-modal-tab.is-active {
            background: linear-gradient(180deg, #d8e8ff 0%, #bed8fb 100%);
            box-shadow: 0 8px 18px rgba(78, 119, 180, 0.16);
        }

        #kt_modal_view_training .alps-modal-panel {
            display: none;
        }

        #kt_modal_view_training .alps-modal-panel.is-active {
            display: block;
        }

        #kt_modal_view_training .alps-modal-section-title,
        #kt_modal_view_training .alps-modal-group-title {
            margin: 0.35rem 0 0.78rem;
            color: #8f1111;
            font-size: 0.92rem;
            font-weight: 800;
        }

        #kt_modal_view_training .alps-modal-row,
        #kt_modal_view_training .alps-modal-subrow {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            margin-bottom: 0.95rem;
        }

        #kt_modal_view_training .alps-modal-row:last-child,
        #kt_modal_view_training .alps-modal-subrow:last-child {
            margin-bottom: 0;
        }

        #kt_modal_view_training .alps-modal-label {
            display: flex;
            align-items: center;
            gap: 0.45rem;
            color: #1f2d4d;
            font-size: 0.95rem;
            font-weight: 700;
        }

        #kt_modal_view_training .alps-modal-label .bi {
            color: #1f2d4d;
        }

        #kt_modal_view_training .alps-modal-value {
            max-width: 55%;
            color: #1f2d4d;
            font-size: 0.95rem;
            font-weight: 700;
            text-align: right;
        }

        #kt_modal_view_training .alps-modal-group {
            margin: 0.2rem 0 1rem 1rem;
            padding-left: 0.95rem;
            border-left: 1.5px solid rgba(143, 17, 17, 0.28);
        }

        #kt_modal_view_training .separator {
            margin-left: 1.5rem;
            margin-right: 1.5rem;
        }

        @media (max-width: 575.98px) {
            #kt_modal_view_training .modal-header {
                padding: 1rem 1.15rem 0.75rem;
            }

            #kt_modal_view_training .modal-body {
                padding: 0.2rem 1.15rem 1.15rem;
            }

            #kt_modal_view_training .modal-footer {
                padding: 0.45rem 1.15rem 0.95rem;
            }

            #kt_modal_view_training .alps-modal-row,
            #kt_modal_view_training .alps-modal-subrow {
                align-items: flex-start;
                flex-direction: column;
                gap: 0.2rem;
            }

            #kt_modal_view_training .alps-modal-value {
                max-width: 100%;
                text-align: left;
            }
        }
    </style>

    <!--begin::Modal - View Training-->
    <div class="modal fade" id="kt_modal_view_training" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered mw-650px">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="alps-type-h3 alps-modal-heading" id="modal-title">View Training</h3>
                    <div class="btn btn-icon btn-sm btn-color-gray-500 btn-active-icon-primary" data-bs-toggle="tooltip" title="Close" data-bs-dismiss="modal">
                        <span class="svg-icon svg-icon-1">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                <rect opacity="0.5" x="6" y="17.3137" width="16" height="2" rx="1" transform="rotate(-45 6 17.3137)" fill="black" />
                                <rect x="7.41422" y="6" width="16" height="2" rx="1" transform="rotate(45 7.41422 6)" fill="black" />
                            </svg>
                        </span>
                    </div>
                </div>
                <div class="separator opacity-10 my-0"></div>
                <div class="modal-body">
                    <div class="text-center mb-2">
                        <h2 class="alps-modal-course" id="modal-course">Facilitation Foundations</h2>
                        <div class="alps-modal-company" id="modal-company">Northwind Group</div>
                        <div class="alps-modal-dates">
                            happening on <span id="modal-date" class="fw-bold"></span>
                            <br>
                            from <span id="modal-time" class="fw-bold"></span>
                        </div>
                    </div>

                    <div class="alps-modal-tabs" role="tablist" aria-label="Training details tabs">
                        <button type="button" class="alps-modal-tab is-active" data-modal-tab="training-details" role="tab" aria-selected="true">TRAINING DETAILS</button>
                        <button type="button" class="alps-modal-tab" data-modal-tab="driver-arrangement" role="tab" aria-selected="false">DRIVER ARRANGEMENT</button>
                    </div>

                    <div class="alps-modal-panel is-active" data-modal-panel="training-details" id="training-details-panel" role="tabpanel">
                        <div class="alps-modal-section-title">People</div>

                        <div class="alps-modal-row">
                            <div class="alps-modal-label"><i class="bi bi-person-workspace"></i><span>Account Manager</span></div>
                            <div class="alps-modal-value" id="modal-account-manager">N/A</div>
                        </div>

                        <div class="alps-modal-row">
                            <div class="alps-modal-label"><i class="bi bi-person-badge"></i><span>Facilitator</span></div>
                            <div class="alps-modal-value" id="modal-facilitator">N/A</div>
                        </div>

                        <div class="alps-modal-row">
                            <div class="alps-modal-label"><i class="bi bi-person-plus"></i><span>Assistant</span></div>
                            <div class="alps-modal-value" id="modal-assistant">N/A</div>
                        </div>

                        <div class="alps-modal-section-title">Setup</div>

                        <div class="alps-modal-row">
                            <div class="alps-modal-label"><i class="bi bi-chat-left-text-fill"></i><span>Mode of Training</span></div>
                            <div class="alps-modal-value" id="modal-mode-of-training">N/A</div>
                        </div>

                        <div class="alps-modal-row" id="in-person-row">
                            <div class="alps-modal-label"><i class="bi bi-person-fill"></i><span>In-person?</span></div>
                            <div class="alps-modal-value" id="modal-in-person">N/A</div>
                        </div>

                        <div class="alps-modal-row" id="location-row">
                            <div class="alps-modal-label"><i class="bi bi-geo-alt-fill"></i><span>Location</span></div>
                            <div class="alps-modal-value" id="modal-location">N/A</div>
                        </div>

                        <div class="alps-modal-row" id="hosting-account-row">
                            <div class="alps-modal-label"><i class="bi bi-easel-fill"></i><span>Hosting Account</span></div>
                            <div class="alps-modal-value text-break">
                                <div id="modal-credentials">N/A</div>
                                <div id="password-container" class="d-flex justify-content-end align-items-center gap-2 mt-1">
                                    <span class="password-display alps-password-toggle">*****</span>
                                    <span class="password-actual d-none alps-password-toggle" id="modal-password"></span>
                                </div>
                            </div>
                        </div>

                        <div class="alps-modal-row" id="platform-row">
                            <div class="alps-modal-label"><i class="bi bi-display"></i><span>Platform</span></div>
                            <div class="alps-modal-value" id="modal-platform">N/A</div>
                        </div>

                        <div class="alps-modal-row" id="conference-link-row">
                            <div class="alps-modal-label"><i class="bi bi-link-45deg"></i><span>Virtual Training Link</span></div>
                            <div class="alps-modal-value text-break" id="modal-conference-link">N/A</div>
                        </div>
                    </div>

                    <div class="alps-modal-panel d-none" data-modal-panel="driver-arrangement" id="driver-arrangement-panel" role="tabpanel">
                        <div class="alps-modal-row" id="transportation-needed-row">
                            <div class="alps-modal-label"><i class="bi bi-truck"></i><span>Transportation Needed?</span></div>
                            <div class="alps-modal-value" id="modal-transportation-needed">No</div>
                        </div>

                        <div class="alps-modal-group d-none" id="outbound-group">
                            <div class="alps-modal-group-title">Outbound</div>
                            <div class="alps-modal-subrow">
                                <div class="alps-modal-label"><i class="bi bi-clock-fill"></i><span>Pickup Time</span></div>
                                <div class="alps-modal-value" id="modal-outbound-pickup-time">N/A</div>
                            </div>
                            <div class="alps-modal-subrow">
                                <div class="alps-modal-label"><i class="bi bi-geo-alt-fill"></i><span>Pickup Location</span></div>
                                <div class="alps-modal-value" id="modal-outbound-pickup-location">N/A</div>
                            </div>
                            <div class="alps-modal-subrow">
                                <div class="alps-modal-label"><i class="bi bi-telephone-fill"></i><span>Contact Number</span></div>
                                <div class="alps-modal-value" id="modal-outbound-contact-number">N/A</div>
                            </div>
                            <div class="alps-modal-subrow">
                                <div class="alps-modal-label"><i class="bi bi-geo"></i><span>Drop Off Location</span></div>
                                <div class="alps-modal-value" id="modal-outbound-dropoff-location">N/A</div>
                            </div>
                        </div>

                        <div class="alps-modal-row d-none" id="return-trip-needed-row">
                            <div class="alps-modal-label"><i class="bi bi-arrow-repeat"></i><span>Return Trip Needed?</span></div>
                            <div class="alps-modal-value" id="modal-return-trip-needed">No</div>
                        </div>

                        <div class="alps-modal-group d-none" id="return-group">
                            <div class="alps-modal-group-title">Return Trip</div>
                            <div class="alps-modal-subrow">
                                <div class="alps-modal-label"><i class="bi bi-clock-history"></i><span>Pickup Time</span></div>
                                <div class="alps-modal-value" id="modal-return-pickup-time">N/A</div>
                            </div>
                            <div class="alps-modal-subrow">
                                <div class="alps-modal-label"><i class="bi bi-geo-alt-fill"></i><span>Pickup Location</span></div>
                                <div class="alps-modal-value" id="modal-return-pickup-location">N/A</div>
                            </div>
                            <div class="alps-modal-subrow">
                                <div class="alps-modal-label"><i class="bi bi-telephone"></i><span>Contact Number</span></div>
                                <div class="alps-modal-value" id="modal-return-contact-number">N/A</div>
                            </div>
                            <div class="alps-modal-subrow">
                                <div class="alps-modal-label"><i class="bi bi-geo"></i><span>Drop Off Location</span></div>
                                <div class="alps-modal-value" id="modal-return-dropoff-location">N/A</div>
                            </div>
                        </div>

                        <div class="alps-modal-section-title d-none" id="coordination-heading">Coordination</div>

                        <div class="alps-modal-row d-none" id="notify-coordinator-row">
                            <div class="alps-modal-label"><i class="bi bi-bell-fill"></i><span>Notify Coordinator?</span></div>
                            <div class="alps-modal-value" id="modal-notify-coordinator">No</div>
                        </div>

                        <div class="alps-modal-row d-none" id="coordinator-to-notify-row">
                            <div class="alps-modal-label"><i class="bi bi-person-badge-fill"></i><span>Coordinator to Notify</span></div>
                            <div class="alps-modal-value" id="modal-coordinator-to-notify">N/A</div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer w-100">

                    <?php if(Auth::check() && in_array(Auth::user()->usertype, ['admin', 'coordinator'])): ?>
                    <button type="button" class="btn btn-secondary btn-red deleteBtn me-auto">
                        <i class="bi bi-trash me-2"></i>DELETE
                    </button>
                    <?php endif; ?>

                    <?php if(Auth::check() && in_array(Auth::user()->usertype, ['admin', 'coordinator'])): ?>
                        <a href="#" id="edit-training-link" data-base-url="<?php echo e(url('calendar/edit_training')); ?>/" class="btn btn-primary btn-orange me-2">
                            <i class="bi bi-pencil-fill me-2"></i>EDIT
                        </a>
                    <?php endif; ?>
                    <button type="reset" class="btn btn-tertiary" data-bs-dismiss="modal">CLOSE</button>
                </div>
            </div>
        </div>
    </div>

<!--begin::Modal - View Unavailability-->
<div class="modal fade" id="kt_modal_view_unavailability" tabindex="-1" aria-hidden="true">
    <!--begin::Modal dialog-->
    <div class="modal-dialog modal-dialog-centered mw-650px">
        <!--begin::Modal content-->
        <div class="modal-content">
            <!--begin::Modal header-->
            <div class="modal-header">
                <h3 class="alps-type-h3 alps-modal-heading" id="view-modal-title">View Unavailability</h3>
                <!--begin::Close-->
                <div class="btn btn-icon btn-sm btn-color-gray-500 btn-active-icon-primary" data-bs-toggle="tooltip" title="Close" data-bs-dismiss="modal">
                    <!--begin::Svg Icon | path: icons/duotune/arrows/arr061.svg-->
                    <span class="svg-icon svg-icon-1">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                            <rect opacity="0.5" x="6" y="17.3137" width="16" height="2" rx="1" transform="rotate(-45 6 17.3137)" fill="black" />
                            <rect x="7.41422" y="6" width="16" height="2" rx="1" transform="rotate(45 7.41422 6)" fill="black" />
                        </svg>
                    </span>
                    <!--end::Svg Icon-->
                </div>
                <!--end::Close-->
            </div>
            <hr class="my-2 opacity-10 mb-3 mt-1">
            <!--end::Modal header-->
            <!--begin::Modal body-->
            <div class="modal-body py-10 px-lg-17">
                <!-- Data Rows -->
                <!-- Course -->
                <div class="row mb-5 justify-content-between align-items-center text-center">
                    <h1 class="fs-1 fw-boldest text-primary" id="modal-user">USER</h1>
                    
                </div>
                <!-- Date -->
                <div class="row mb-5 justify-content-between align-items-center">
                    <div class="col-5">
                        <div class="fv-row">
                            <label class="fs-6 fw-bold mb-2">
                                <i class="bi bi-calendar-x-fill fs-3 me-5 alps-icon-accent"></i>Date Unavailable
                            </label>
                        </div>
                    </div>
                    <div class="col-7">
                        <div class="fv-row d-flex justify-content-end align-items-center">
                            <p class="lead fs-6" id="modal-date-unavailable"></p>
                        </div>
                    </div>
                </div>
                <!-- Purpose -->
                <div class="row mb-5 justify-content-between align-items-center">
                    <div class="col-5">
                        <div class="fv-row">
                            <label class="fs-6 fw-bold mb-2">
                                <i class="bi bi-patch-question-fill fs-3 me-5 alps-icon-accent"></i>Purpose
                            </label>
                        </div>
                    </div>
                    <div class="col-7">
                        <div class="fv-row d-flex justify-content-end align-items-center">
                            <p class="lead fs-6" id="modal-title">Team Building</p>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Modal Footer -->
            <div class="modal-footer w-100">
                <!-- Delete Button (Left) -->
                <button type="button" class="btn btn-secondary btn-red deleteBtnUnavailability me-auto">
                    <i class="bi bi-trash me-2"></i>DELETE
                </button>
                <!-- Close Buttons (Right) -->
                <button type="reset" class="btn btn-tertiary" data-bs-dismiss="modal">CLOSE</button>
            </div>

        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

    <?php $__env->startPush('scripts'); ?>

        <?php if(Auth::check() && in_array(Auth::user()->usertype, ['admin', 'coordinator', 'facilitator'])): ?>
            <script>

               let authenticated_user = <?php echo e(Auth::user()->id); ?>;
               let authenticated_usertype = "<?php echo e(Auth::user()->usertype); ?>";

            </script>
            <script src="<?php echo e(asset('js/calendar.js')); ?>"></script>
        <?php else: ?>
            <script src="<?php echo e(asset('js/unavailability_calendar.js')); ?>"></script>
        <?php endif; ?>

    <?php $__env->stopPush(); ?>

<?php echo $__env->make('global.layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\ALPs\ALPs Calendar\ALPS-Calendar\resources\views/main-content/calendar.blade.php ENDPATH**/ ?>