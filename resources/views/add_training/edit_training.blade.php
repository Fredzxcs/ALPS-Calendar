@extends('global.layout')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<link rel="stylesheet" href="{{ asset('css/training-ui-redesign.css') }}">

@section('maincontent')
    <div class="d-flex justify-content-center align-items-center mt-20">
        <div class="container mt-5">
            <div class="shadow-sm border-0">
                <div class="alps-header-add rounded-top d-flex justify-content-center align-items-center py-4" style="background: linear-gradient(90deg, #1D4A8A 0%, #2C66B3 52%, #1B4785 100%);">
                    <h2 class="text-white fw-boldest m-0 fs-1">Edit Training</h2>
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
                    @php
                        $currentUser = auth()->user();
                        $googleConnected = ($currentUser instanceof \App\Models\User && !empty($currentUser->google_refresh_token)) || session('google_connected', false);
                        
                        // Safe accessors for training properties
                        $t_mode = $training->mode ?? 'virtual';
                        $t_course_id = $training->course_id ?? null;
                        $t_account_id = $training->account_id ?? null;
                        $t_platform = $training->platform ?? null;
                        $t_conference_link = $training->conference_link ?? null;
                        $t_location = $training->location ?? null;
                        $t_company_id = $training->company_id ?? null;
                        $t_company_name = $training->company_name ?? null;
                        $t_from_date = $training->from_date ?? null;
                        $t_to_date = $training->to_date ?? null;
                        $t_from_time = $training->from_time ?? null;
                        $t_to_time = $training->to_time ?? null;
                        $t_facilitator_id = $training->facilitator_id ?? null;
                        $t_account_manager_id = $training->account_manager_id ?? null;
                        $t_assistants = collect($training->assistants ?? []);
                        $t_need_transportation = filter_var($training->need_transportation ?? false, FILTER_VALIDATE_BOOLEAN) || ($training->need_transportation ?? '') === 'yes' ? 'yes' : 'no';
                    @endphp

                    <div class="mb-5">
                    <!-- STEP 1: Training Details -->
                    <div class="flex-column current" data-kt-stepper-element="content" id="training-step-1">
                        <!-- Mode of Training -->
                        <div class="training-form-row full">
                            <div class="training-form-group">
                                <label>Mode of Training <span class="required"></span></label>
                                <div class="training-radio-group">
                                    <div class="training-radio">
                                        <input type="radio" id="virtual" value="virtual" name="mode" {{ $t_mode == 'virtual' ? 'checked' : '' }}>
                                        <label for="virtual">Virtual</label>
                                    </div>
                                    <div class="training-radio">
                                        <input type="radio" id="face-to-face" value="face-to-face" name="mode" {{ $t_mode == 'face-to-face' ? 'checked' : '' }}>
                                        <label for="face-to-face">Face-to-Face</label>
                                    </div>
                                    <div class="training-radio">
                                        <input type="radio" id="public-course" value="public-course" name="mode" {{ $t_mode == 'public-course' ? 'checked' : '' }}>
                                        <label for="public-course">Public Course</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Public Course: Course and In-person Training -->
                        <div class="training-form-row full {{ $t_mode != 'public-course' ? 'd-none' : '' }}" id="public-course-container">
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
                                            @foreach ($courses as $course)
                                            <option value="{{ $course->id }}" {{ $t_course_id == $course->id ? 'selected' : '' }}>
                                                {{ $course->course_code ? $course->course_code . ' - ' : '' }}{{ $course->course_name }}
                                            </option>
                                            @endforeach
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
                                    <option value="Zoom" {{ $t_platform == 'Zoom' ? 'selected' : '' }}>Zoom</option>
                                    <option value="Google Meet" {{ $t_platform == 'Google Meet' ? 'selected' : '' }}>Google Meet</option>
                                    <option value="MS Teams" {{ $t_platform == 'MS Teams' ? 'selected' : '' }}>MS Teams</option>
                                    <option value="other" {{ $t_platform && !in_array($t_platform, ['Zoom', 'Google Meet', 'MS Teams']) ? 'selected' : '' }}>Other</option>
                                </select>
                                <input type="text" name="platform_other" id="platform_other" class="training-input {{ $t_platform && !in_array($t_platform, ['Zoom', 'Google Meet', 'MS Teams']) ? '' : 'd-none' }}" style="margin-top: 0.5rem;"
                                    placeholder="Enter platform name" value="{{ $t_platform && !in_array($t_platform, ['Zoom', 'Google Meet', 'MS Teams']) ? $t_platform : '' }}">
                            </div>
                        </div>

                        <!-- Virtual Training Link -->
                        <div class="training-form-row full {{ $t_mode == 'public-course' ? 'd-none' : '' }}" id="conference-link-container">
                            <div class="training-form-group">
                                        <label for="conference_link"><span id="conference-link-label">Virtual Training Link</span><span id="conference-link-required" class="required d-none"></span></label>
                                <input type="url" name="conference_link" id="conference_link" class="training-input"
                                    placeholder="Enter the virtual training link (e.g., https://zoom.us/j/...)" value="{{ $t_conference_link ?? '' }}">
                            </div>
                        </div>

                        <!-- Account -->
                        <div class="training-form-row full d-none" id="credentials-container">
                            <div class="training-form-group">
                                <label for="credentials">Account</label>
                                <select id="credentials" class="training-select">
                                    <option value="" disabled>Select Host Email Account</option>
                                    @foreach ($accounts as $account)
                                        <option value="{{ $account->id }}" {{ $t_account_id == $account->id ? 'selected' : '' }}>
                                            {{ $account->account_email }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- Location: Face-to-Face -->
                        <div class="training-form-row full {{ $t_mode != 'face-to-face' ? 'd-none' : '' }}" id="location-container">
                            <div class="training-form-group">
                                <label for="location">Location <span class="required"></span></label>
                                <input type="text" id="location" class="training-input"
                                    placeholder="Enter Location" value="{{ $t_location ?? '' }}">
                            </div>
                        </div>

                        <!-- Company and Course -->
                        <div class="training-form-row" id="company-course-container">
                            <div class="training-form-group">
                                <label for="company">Company <span class="required"></span></label>
                                <select id="company" class="training-select">
                                    <option value="" disabled>Select Company</option>
                                    @foreach ($companies as $company)
                                        <option value="{{ $company->id }}" {{ (string)$t_company_id === (string)$company->id ? 'selected' : '' }}>
                                            {{ $company->company_name }}
                                        </option>
                                    @endforeach
                                    <option value="other" {{ $t_company_id && !$companies->contains('id', $t_company_id) ? 'selected' : '' }}>Other</option>
                                </select>
                                <input type="text" id="enter-company" class="training-input {{ $t_company_id && !$companies->contains('id', $t_company_id) ? '' : 'd-none' }}" style="margin-top: 0.5rem;" placeholder="Enter Company" value="{{ $t_company_id && !$companies->contains('id', $t_company_id) ? $t_company_name : '' }}">
                            </div>

                            <div class="training-form-group">
                                <label for="course">Course <span class="required"></span></label>
                                <select id="course" class="training-select">
                                    <option value="" disabled>Select Course</option>
                                    @foreach ($courses as $course)
                                    <option value="{{ $course->id }}" {{ (string)$t_course_id === (string)$course->id ? 'selected' : '' }}>
                                        {{ $course->course_code ? $course->course_code . ' - ' : '' }}{{ $course->course_name }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- Date and Time -->
                        @php
                            // Grab the schedule data related to this training session
                            $sched = $training->schedule instanceof \Illuminate\Database\Eloquent\Collection 
                                        ? $training->schedule->sortByDesc('id')->first() 
                                        : $training->schedule;
                        @endphp

                        <div class="training-form-row triple">
                            <div class="training-form-group">
                                <label for="date-range">Date Range <span class="required"></span></label>
                                <input type="text" id="date-range" class="training-input" placeholder="Select Date" readonly
                                    value="{{ !empty($sched->from_date) && !empty($sched->to_date) ? \Carbon\Carbon::parse($sched->from_date)->format('m-d-Y') . ' to ' . \Carbon\Carbon::parse($sched->to_date)->format('m-d-Y') : '' }}">
                            </div>
                            
                            <div class="training-form-group">
                                <label for="time-start">Time Start <span class="required"></span></label>
                                <input type="time" id="time-start" class="training-input" 
                                    value="{{ !empty($sched->from_time) ? \Carbon\Carbon::parse($sched->from_time)->format('H:i') : '' }}">
                            </div>
                            
                            <div class="training-form-group">
                                <label for="time-end">Time End <span class="required"></span></label>
                                <input type="time" id="time-end" class="training-input" 
                                    value="{{ !empty($sched->to_time) ? \Carbon\Carbon::parse($sched->to_time)->format('H:i') : '' }}">
                            </div>
                        </div>

                        <!-- Google Calendar Card -->
                        <div class="google-calendar-card">
                            <h3>Connect Account for Google Calendar Invites</h3>
                            <p>The connected account will be listed as the event organizer and used to send invites.</p>
                                @if ($googleConnected)
                                <div style="background: #d1fae5; color: #047857; padding: 0.75rem 1rem; border-radius: 0.5rem; font-size: 0.9rem; font-weight: 600;">
                                    ✓ Connected - Ensure you are using your work profile.
                                </div>
                                @else
                                <a id="google_signin_btn" href="{{ route('google.redirect', ['from' => 'add_training']) }}" class="google-calendar-button">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor">
                                        <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/>
                                        <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
                                        <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/>
                                        <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
                                    </svg>
                                    SIGN IN WITH GOOGLE
                                </a>
                                @endif
                        </div>

                        <!-- People -->
                        <div class="training-form-row triple">
                            <div class="training-form-group">
                                <label for="facilitator">Facilitator <span class="required"></span></label>
                                <select id="facilitator" name="facilitator_id" class="training-select">
                                    <option disabled>Select Facilitator</option>
                                    <option value="">No Facilitator Yet</option>
                                    @foreach ($facilitators as $user)
                                        <option value="{{ $user->id }}" {{ $t_facilitator_id == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="training-form-group">
                                <label for="account_manager">Account Manager</label>
                                <select id="account_manager" name="account_manager_id" class="training-select">
                                    <option disabled selected>Select Account Manager</option>
                                    <option value="">No Account Manager Yet</option>
                                    @foreach ($facilitators as $user)
                                        <option value="{{ $user->id }}" {{ $t_account_manager_id == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="training-form-group">
                                <label for="assistant_select">Assistant</label>
                                <div style="display: flex; gap: 0.5rem; align-items: flex-start;">
                                    <select id="assistant_select" class="training-select" style="flex: 1;">
                                        <option value="" selected disabled>Select Assistant</option>
                                        @foreach ($facilitators as $user)
                                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                                        @endforeach
                                    </select>
                                    <button type="button" id="assistant_add_btn" class="training-btn training-btn-secondary-blue">ADD</button>
                                </div>
                                <div id="assistant_list_container" style="margin-top: 0.75rem; display: flex; flex-wrap: wrap; gap: 0.75rem; align-items: center; min-height: 0;">
                                    @if($t_assistants->count())
                                        @foreach($t_assistants as $assistant)
                                            <div class="assistant-item" data-id="{{ $assistant->id }}" style="background: #dbeafe; border: 1px solid #bfdbfe; color: #1e40af; padding: 0.5rem 0.75rem; border-radius: 9999px; display: inline-flex; align-items: center; gap: 0.5rem; font-size: 0.875rem; font-weight: 500; white-space: nowrap;">
                                                <span>{{ $assistant->name }}</span>
                                                <button type="button" class="remove-assistant" data-id="{{ $assistant->id }}" style="background: transparent; border: none; color: #1e40af; cursor: pointer; font-size: 1.1rem; font-weight: bold; padding: 0; display: flex; align-items: center; justify-content: center; width: 1.1rem; height: 1.1rem; margin-left: 0.25rem; line-height: 1;">×</button>
                                            </div>
                                        @endforeach
                                    @endif
                                </div>
                                <input type="hidden" id="assistant_list" value="{{ $t_assistants->count() ? implode(', ', $t_assistants->pluck('id')->toArray()) : '' }}">
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
                                        <input type="radio" id="need_transportation_yes" name="need_transportation" value="yes" {{ $t_need_transportation == 'yes' ? 'checked' : '' }}>
                                        <label for="need_transportation_yes">Yes, I need a driver</label>
                                    </div>
                                    <div class="training-radio">
                                        <input type="radio" id="need_transportation_no" name="need_transportation" value="no" {{ $t_need_transportation != 'yes' ? 'checked' : '' }}>
                                        <label for="need_transportation_no">No transportation needed</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Driver Arrangement Fields -->
                        <div id="driver-arrangement-fields" class="{{ $t_need_transportation == 'yes' ? '' : 'd-none' }}">
                            <!-- Outbound Trip -->
                            <div class="trip-section">
                                <div class="trip-section-heading">Outbound Trip</div>
                                <div class="training-form-row quad">
                                    <div class="training-form-group">
                                        <label for="outbound_pickup_date">Pick-Up Date</label>
                                        <input type="date" id="outbound_pickup_date" name="outbound_pickup_date" class="training-input" 
                                            value="{{ !empty($training->outbound_pickup_date) ? \Carbon\Carbon::parse($training->outbound_pickup_date)->format('Y-m-d') : '' }}">
                                        
                                        <div style="display:flex; justify-content:space-between; align-items:center; gap:1rem; margin-top:0.5rem; flex-wrap:wrap;">
                                            <div class="form-check form-check-sm m-0">
                                                <input class="form-check-input" type="checkbox" id="outbound_date_na" name="outbound_date_na" value="1">
                                                <label class="form-check-label text-muted fs-7" for="outbound_date_na">Not Applicable</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="training-form-group">
                                        <label for="outbound_pickup_time">Pickup Time <span class="required"></span></label>
                                        <input type="time" id="outbound_pickup_time" class="training-input" value="{{ $training->outbound_pickup_time ?? '' }}">
                                    </div>
                                    <div class="training-form-group">
                                        <label for="outbound_contact_number">Contact Number <span class="required"></span></label>
                                        <input type="text" id="outbound_contact_number" class="training-input" placeholder="Contact number" value="{{ $training->outbound_contact_number ?? '' }}">
                                    </div>
                                    <div class="training-form-group">
                                        <label for="outbound_pickup_location">Pickup Location <span class="required"></span></label>
                                        <input type="text" id="outbound_pickup_location" class="training-input" placeholder="Pickup location" value="{{ $training->outbound_pickup_location ?? '' }}">
                                    </div>
                                    <div class="training-form-group">
                                        <label for="outbound_dropoff_location">Drop-off Location <span class="required"></span></label>
                                        <input type="text" id="outbound_dropoff_location" class="training-input" placeholder="Drop-off location" value="{{ $training->outbound_dropoff_location ?? '' }}">
                                    </div>
                                    <div class="training-form-group" style="display:flex; align-items:flex-end; justify-content:flex-end;">
                                        <button type="button" class="training-btn training-btn-secondary-blue" data-trip-add="outbound" style="padding:0.5rem 1rem; font-size:0.8rem;">
                                            Add Outbound Trip
                                        </button>
                                    </div>
                                </div>

                                <div id="outbound-trip-entries" style="margin-top:1rem; display:flex; flex-direction:column; gap:1rem;"></div>
                            </div>

                            <!-- Return Trip -->
                            <div class="trip-section">
                                <div class="training-checkbox">
                                    <input type="checkbox" id="return_trip_needed" {{ $training->return_trip_needed ? 'checked' : '' }}>
                                    <label for="return_trip_needed">Return trip needed</label>
                                </div>

                                <div id="return-trip-fields" class="{{ $training->return_trip_needed ? '' : 'd-none' }}" style="margin-top: 1rem;">
                                    <div class="trip-section-heading">Return Trip</div>
                                    <div class="training-form-row quad">
                                        <div class="training-form-group">
                                            <label for="return_pickup_date">Pick-Up Date</label>
                                            <input type="date" id="return_pickup_date" name="return_pickup_date" class="training-input" 
                                                value="{{ !empty($training->return_pickup_date) ? \Carbon\Carbon::parse($training->return_pickup_date)->format('Y-m-d') : '' }}">
                                            
                                            <div style="display:flex; justify-content:space-between; align-items:center; gap:1rem; margin-top:0.5rem; flex-wrap:wrap;">
                                                <div class="form-check form-check-sm m-0">
                                                    <input class="form-check-input" type="checkbox" id="return_date_na" name="return_date_na" value="1">
                                                    <label class="form-check-label text-muted fs-7" for="return_date_na">Not Applicable</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="training-form-group">
                                            <label for="return_pickup_time">Return Time <span class="required"></span></label>
                                            <input type="time" id="return_pickup_time" class="training-input" value="{{ $training->return_pickup_time ?? '' }}">
                                        </div>
                                        <div class="training-form-group">
                                            <label for="return_contact_number">Contact Number <span class="required"></span></label>
                                            <input type="text" id="return_contact_number" class="training-input" placeholder="Contact number" value="{{ $training->return_contact_number ?? '' }}">
                                        </div>
                                        <div class="training-form-group">
                                            <label for="return_pickup_location">Pickup Location <span class="required"></span></label>
                                            <input type="text" id="return_pickup_location" class="training-input" placeholder="Pickup location" value="{{ $training->return_pickup_location ?? '' }}">
                                        </div>
                                        <div class="training-form-group">
                                            <label for="return_dropoff_location">Drop-off Location <span class="required"></span></label>
                                            <input type="text" id="return_dropoff_location" class="training-input" placeholder="Drop-off location" value="{{ $training->return_dropoff_location ?? '' }}">
                                        </div>
                                        <div class="training-form-group" style="display:flex; align-items:flex-end; justify-content:flex-end;">
                                            <button type="button" class="training-btn training-btn-secondary-blue" data-trip-add="return" style="padding:0.5rem 1rem; font-size:0.8rem;">
                                                Add Return Trip
                                            </button>
                                        </div>
                                    </div>

                                    <div id="return-trip-entries" style="margin-top:1rem; display:flex; flex-direction:column; gap:1rem;"></div>
                                </div>
                            </div>

                            <template id="outbound-trip-template">
                                <div class="dynamic-trip-card" data-trip-section="outbound" style="border:1px solid #e2e8f0; border-radius:0.75rem; padding:1rem; background:#fff; box-shadow:0 1px 3px rgba(15,23,42,0.06);">
                                    <div style="display:flex; justify-content:space-between; align-items:center; gap:1rem; margin-bottom:1rem; flex-wrap:wrap;">
                                        <div class="trip-section-heading" style="margin-bottom:0;">Outbound Trip</div>
                                        <button type="button" class="training-btn training-btn-secondary" data-trip-remove style="padding:0.45rem 0.9rem; font-size:0.8rem;">Remove</button>
                                    </div>
                                    <div class="training-form-row quad">
                                        <div class="training-form-group">
                                            <label>Pick-Up Date</label>
                                            <input type="date" class="training-input trip-pickup-date">
                                            <div style="display:flex; justify-content:space-between; align-items:center; gap:1rem; margin-top:0.5rem; flex-wrap:wrap;">
                                                <div class="form-check form-check-sm m-0">
                                                    <input class="form-check-input trip-date-na" type="checkbox" value="1">
                                                    <label class="form-check-label text-muted fs-7">Not Applicable</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="training-form-group">
                                            <label>Pickup Time <span class="required"></span></label>
                                            <input type="time" class="training-input trip-pickup-time">
                                        </div>
                                        <div class="training-form-group">
                                            <label>Contact Number <span class="required"></span></label>
                                            <input type="text" class="training-input trip-contact-number" placeholder="Contact number">
                                        </div>
                                        <div class="training-form-group">
                                            <label>Pickup Location <span class="required"></span></label>
                                            <input type="text" class="training-input trip-pickup-location" placeholder="Pickup location">
                                        </div>
                                        <div class="training-form-group">
                                            <label>Drop-off Location <span class="required"></span></label>
                                            <input type="text" class="training-input trip-dropoff-location" placeholder="Drop-off location">
                                        </div>
                                    </div>
                                </div>
                            </template>

                            <template id="return-trip-template">
                                <div class="dynamic-trip-card" data-trip-section="return" style="border:1px solid #e2e8f0; border-radius:0.75rem; padding:1rem; background:#fff; box-shadow:0 1px 3px rgba(15,23,42,0.06);">
                                    <div style="display:flex; justify-content:space-between; align-items:center; gap:1rem; margin-bottom:1rem; flex-wrap:wrap;">
                                        <div class="trip-section-heading" style="margin-bottom:0;">Return Trip</div>
                                        <button type="button" class="training-btn training-btn-secondary" data-trip-remove style="padding:0.45rem 0.9rem; font-size:0.8rem;">Remove</button>
                                    </div>
                                    <div class="training-form-row quad">
                                        <div class="training-form-group">
                                            <label>Pick-Up Date</label>
                                            <input type="date" class="training-input trip-pickup-date">
                                            <div style="display:flex; justify-content:space-between; align-items:center; gap:1rem; margin-top:0.5rem; flex-wrap:wrap;">
                                                <div class="form-check form-check-sm m-0">
                                                    <input class="form-check-input trip-date-na" type="checkbox" value="1">
                                                    <label class="form-check-label text-muted fs-7">Not Applicable</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="training-form-group">
                                            <label>Return Time <span class="required"></span></label>
                                            <input type="time" class="training-input trip-pickup-time">
                                        </div>
                                        <div class="training-form-group">
                                            <label>Contact Number <span class="required"></span></label>
                                            <input type="text" class="training-input trip-contact-number" placeholder="Contact number">
                                        </div>
                                        <div class="training-form-group">
                                            <label>Pickup Location <span class="required"></span></label>
                                            <input type="text" class="training-input trip-pickup-location" placeholder="Pickup location">
                                        </div>
                                        <div class="training-form-group">
                                            <label>Drop-off Location <span class="required"></span></label>
                                            <input type="text" class="training-input trip-dropoff-location" placeholder="Drop-off location">
                                        </div>
                                    </div>
                                </div>
                            </template>

                            <!-- Notify Heads -->
                            <div class="trip-section" style="margin-top: 1.5rem;">
                                <div class="trip-section-heading">Notify Heads</div>
                                <div class="training-checkbox">
                                    <input type="checkbox" id="notify_coordinator" {{ $training->notify_coordinator ? 'checked' : '' }}>
                                    <label for="notify_coordinator">Notify Coordinator</label>
                                </div>

                                <div id="coordinator-to-notify-container" class="{{ $training->notify_coordinator ? '' : 'd-none' }}" style="margin-top: 1rem;">
                                    <div class="training-form-group">
                                        <label for="coordinator_to_notify_select">Driver Coordinator <span class="required"></span></label>
                                        <div style="display: flex; gap: 0.5rem;">
                                            <select id="coordinator_to_notify_select" class="training-select" style="flex: 1;">
                                                <option value="" selected disabled>Select Coordinator</option>
                                                @foreach ($facilitators as $user)
                                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                                @endforeach
                                            </select>
                                            <button type="button" id="coordinator_add_btn" class="training-btn training-btn-secondary-blue">ADD</button>
                                        </div>
                                        <div id="coordinator_list_container" style="margin-top: 0.75rem; display: flex; flex-wrap: wrap; gap: 0.75rem; align-items: center; min-height: 0;">
                                            @if($training->coordinator_to_notify_users->count())
                                                @foreach($training->coordinator_to_notify_users as $coordinator)
                                                    <div class="coordinator-item" data-id="{{ $coordinator->id }}" style="background: #dbeafe; border: 1px solid #bfdbfe; color: #1e40af; padding: 0.5rem 0.75rem; border-radius: 9999px; display: inline-flex; align-items: center; gap: 0.5rem; font-size: 0.875rem; font-weight: 500; white-space: nowrap;">
                                                        <span>{{ $coordinator->name }}</span>
                                                        <button type="button" class="remove-coordinator" data-id="{{ $coordinator->id }}" style="background: transparent; border: none; color: #1e40af; cursor: pointer; font-size: 1.1rem; font-weight: bold; padding: 0; display: flex; align-items: center; justify-content: center; width: 1.1rem; height: 1.1rem; margin-left: 0.25rem; line-height: 1;">×</button>
                                                    </div>
                                                @endforeach
                                            @endif
                                        </div>
                                        <input type="hidden" id="coordinator_to_notify_list" value="{{ $training->coordinator_to_notify_users->count() ? implode(', ', $training->coordinator_to_notify_users->pluck('id')->toArray()) : '' }}">
                                        <div class="training-helper-text">Select one coordinator and click Add to include multiple coordinators.</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Buttons -->
                    <div class="d-flex flex-stack mt-5">
                        <div class="me-2 d-flex gap-2">
                            <a href="{{ route('calendar') }}" class="btn btn-light btn-active-light-primary">CANCEL</a>
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
@endsection

@push('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script>
        window.isEditMode = true;
        window.trainingId = {{ $training->id }};
        window.existingOutboundTrips = @json($training->outbound_trips_json ?? []);
        window.existingReturnTrips = @json($training->return_trips_json ?? []);
    </script>
    <script src="{{ asset('js/add_training.js') }}"></script>
    <script src="{{ asset('plugins/custom/formrepeater/formrepeater.bundle.js') }}"></script>
@endpush
