@extends('global.layout')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">


@section('maincontent')
    <div class="d-flex justify-content-center align-items-center mt-20 ">
        <div class="container mt-5 ">
            <!-- Card -->
            <div class="card shadow-sm rounded-3">
                <!-- Title -->
                <div class="d-flex justify-content-center align-items-center mb-0 bg-primary rounded-top h-80px" >
                    <h2 class="text-white fw-boldest m-0 fs-1">ADD TRAINING</h2>
                </div>
                <!-- Form -->
                <div class="p-20 pt-10 pb-6 ">
                    <form>
                        @php
                            $currentUser = auth()->user();
                            $googleConnected = ($currentUser instanceof \App\Models\User && !empty($currentUser->google_refresh_token)) || session('google_connected', false);
                        @endphp

                        <div class="alert {{ $googleConnected ? 'alert-success' : 'alert-warning' }} d-flex align-items-center justify-content-between mb-6">
                            <div>
                                <div class="fw-bold mb-1">Google Calendar</div>
                                <div class="fs-7">
                                    {{ $googleConnected ? 'This account is connected and can sync training events.' : 'Connect your Google account to sync trainings and send invites.' }}
                                </div>
                            </div>
                            <div class="ms-3">
                                @if ($googleConnected)
                                    <span class="badge badge-light-success">Connected</span>
                                @else
                                    <a href="{{ route('google.redirect', ['from' => 'add_training']) }}" class="btn btn-sm btn-primary fw-boldest">
                                        + Connect Google Calendar
                                    </a>
                                @endif
                            </div>
                        </div>

                        <div id="training-step-1">
                        <!-- Mode of Training -->
                        <div class="mb-4">
                            <label class="fw-bold mb-2 required">Mode of Training</label>
                            <div class="d-flex gap-4">
                                <div>
                                    <input type="radio" id="virtual" value="virtual" name="mode" class="form-check-input" checked="checked">
                                    <label for="virtual" class="form-check-label">Virtual</label>
                                </div>
                                <div>
                                    <input type="radio" id="face-to-face" value="face-to-face" name="mode" class="form-check-input">
                                    <label for="face-to-face" class="form-check-label">Face-to-Face</label>
                                </div>
                                <div>
                                    <input type="radio" id="public-course" value="public-course" name="mode" class="form-check-input">
                                    <label for="public-course" class="form-check-label">Public Course</label>
                                </div>
                            </div>
                        </div>

                        <!-- Public Course: Course and In-person Training -->
                        <div class="row mb-4 d-none" id="public-course-container">
                            <div class="col-md-12 d-flex align-items-center justify-content-between">
                                <!-- In-person Checkbox -->
                                <div class="form-check me-3">
                                    <input type="checkbox" id="inperson-training" class="form-check-input">
                                    <label for="inperson-training" class="form-check-label fw-bold">In-person
                                        training?</label>
                                </div>
                                <!-- Course -->
                                <div class="d-flex align-items-center alps-half-width">
                                    <div class="flex-grow-1">
                                        <label for="public-course-select" class="fw-bold mb-2 required">Course</label>
                                        <select id="public-course-select" class="form-select form-select-solid">
                                            <option value="" disabled selected>Select Course</option>
                                            @foreach ($courses as $course)
                                            <option value="{{ $course->id }}">
                                                {{ $course->course_code ? $course->course_code . ' - ' : '' }}{{ $course->course_name }}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Account and Platform -->
                        <div class="row mb-4" id="credentials-container">
                            <div class="col-md-6">
                                <label for="credentials" class="fw-bold mb-2 required">Account</label>
                                <select id="credentials" class="form-select form-select-solid">
                                    <option value="" disabled selected>Select Host Email Account</option>
                                    @foreach ($accounts as $account)
                                        <option value="{{ $account->id }}">
                                            {{ $account->account_email }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label for="platform" class="fw-bold mb-2">Platform</label>
                                <select id="platform" class="form-select form-select-solid">
                                    <option value="" selected disabled>Select Platform</option>
                                    <option value="Zoom">Zoom</option>
                                    <option value="Google Meet">Google Meet</option>
                                    <option value="MS Teams">MS Teams</option>
                                    <option value="other">Other</option>
                                </select>
                                <input type="text" name="platform_other" id="platform_other" class="form-control form-control-solid d-none mt-2"
                                    placeholder="Enter platform name">
                            </div>
                        </div>

                        <!-- Conference Call Link -->
                        <div class="row mb-4" id="conference-link-container">
                            <div class="col-md-12">
                                <label for="conference_link" class="fw-bold mb-2"><span id="conference-link-label">Conference Call Link</span><span id="conference-link-required" class="text-danger d-none"> *</span></label>
                                <input type="url" name="conference_link" id="conference_link" class="form-control form-control-solid"
                                    placeholder="Enter the conference call link (e.g., https://zoom.us/j/...)">
                            </div>
                        </div>
                        <!-- Location: Face-to-Face and In-Person -->
                        <div class="mb-4 d-none" id="location-container">
                            <label for="location" class="fw-bold mb-2 required">Location </label>
                            <input type="text" id="location" class="form-control form-control-solid"
                                placeholder="Enter Location">
                        </div>
                    <div class="row mb-4" id="company-course-container">
                        <!-- Company -->
                        <div class="col-md-6 d-flex align-items-center">
                            <div class="flex-grow-1">
                                <label for="company" class="fw-bold mb-2 required">Company</label>
                                <select id="company" class="form-select form-select-solid">
                                    <option value="" disabled selected>Select Company</option>
                                    @foreach ($companies as $company)
                                        <option value="{{ $company->id }}">
                                            {{ $company->company_name }}
                                        </option>
                                    @endforeach
                                    <option value="other">Other</option>
                                </select>
                                <input type="text" id="enter-company" class="form-control form-control-solid d-none" placeholder="Enter Company"
                                {{-- onfocus="showCloseIcon()" onblur="hideCloseIcon()" --}}>
                            </div>
                        </div>

                        <!-- Course -->
                        {{-- need form repeater --}}
                        <div class="col-md-6 d-flex align-items-center">
                            <div class="flex-grow-1">
                                <label for="course" class="fw-bold mb-2 required">Course</label>
                                <select id="course" class="form-select form-select-solid">
                                    <option value="" disabled selected>Select Course</option>
                                    @foreach ($courses as $course)
                                    <option value="{{ $course->id }}">
                                        {{ $course->course_code ? $course->course_code . ' - ' : '' }}{{ $course->course_name }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Date and Time -->
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <label for="date-range" class="fw-bold mb-2 required">Date Range</label>
                            <div class="position-relative">
                                <input type="text" id="date-range" class="form-control form-control-solid pe-5" placeholder="Select Date">
                                <span class="position-absolute top-50 end-0 translate-middle-y me-3 text-muted">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-calendar-heart-fill" viewBox="0 0 16 16">
                                        <path d="M4 .5a.5.5 0 0 0-1 0V1H2a2 2 0 0 0-2 2v1h16V3a2 2 0 0 0-2-2h-1V.5a.5.5 0 0 0-1 0V1H4zM16 14V5H0v9a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2M8 7.993c1.664-1.711 5.825 1.283 0 5.132-5.825-3.85-1.664-6.843 0-5.132"/>
                                    </svg>
                                </span>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label for="time-start" class="fw-bold mb-2 required">Time Start</label>
                            <input type="time" id="time-start" class="form-control form-control-solid">
                        </div>
                        <div class="col-md-4">
                            <label for="time-end" class="fw-bold mb-2 required">Time End</label>
                            <input type="time" id="time-end" class="form-control form-control-solid">
                        </div>
                    </div>

                    <!-- Facilitator and Assistant -->
                    <div class="row mb-5 align-items-start">
                        <!-- Facilitator -->
                        <div class="col-md-6">
                            <label for="facilitator" class="fw-bold mb-2 required">Facilitator</label>
                            <select id="facilitator" class="form-select form-select-solid">
                                <option disabled selected>Select Facilitator</option>
                                <option value="">No Facilitator Yet</option>
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Assistant -->
                        <div class="col-md-6">
                            <label for="assistant_select" class="form-label">Assistant</label>
                            <div class="d-flex gap-2">
                                <select id="assistant_select" class="form-select form-select-solid">
                                    <option value="" selected disabled>Select Assistant</option>
                                    @foreach ($users as $user)
                                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                                    @endforeach
                                </select>
                                <button type="button" id="assistant_add_btn" class="btn btn-light-primary">Add</button>
                            </div>
                            <div id="assistant_list_container" class="mt-3"></div>
                            <input type="hidden" id="assistant_list" value="">
                            <div class="form-text">Select one assistant and click Add to include multiple assistants.</div>
                        </div>
                    </div>
                    </div>

                    <div id="training-step-2" class="d-none">
                        <div class="d-flex align-items-center justify-content-between mb-4">
                            <div>
                                <div class="fw-bold fs-4">Driver Arrangement</div>
                                <div class="text-muted fs-7">Configure transportation only if needed.</div>
                            </div>
                            <span class="badge badge-light-primary">Step 2</span>
                        </div>

                        <div class="mb-4">
                            <label class="fw-bold mb-2 required">Do you need a transportation?</label>
                            <div class="d-flex flex-column gap-2">
                                <label class="form-check form-check-custom form-check-solid">
                                    <input class="form-check-input" type="radio" name="need_transportation" id="need_transportation_yes" value="yes">
                                    <span class="form-check-label">Yes. I need a driver</span>
                                </label>
                                <label class="form-check form-check-custom form-check-solid">
                                    <input class="form-check-input" type="radio" name="need_transportation" id="need_transportation_no" value="no" checked>
                                    <span class="form-check-label">No transportation needed</span>
                                </label>
                            </div>
                        </div>

                        <div id="driver-arrangement-fields" class="d-none">
                            <div class="mb-5">
                                <div class="fw-bold mb-3">Outbound Trip</div>
                                <div class="row g-4">
                                    <div class="col-md-3">
                                        <label for="outbound_pickup_time" class="fw-bold mb-2 required">Pickup Time</label>
                                        <input type="time" id="outbound_pickup_time" class="form-control form-control-solid">
                                    </div>
                                    <div class="col-md-3">
                                        <label for="outbound_contact_number" class="fw-bold mb-2 required">Contact Number</label>
                                        <input type="text" id="outbound_contact_number" class="form-control form-control-solid" placeholder="Contact number">
                                    </div>
                                    <div class="col-md-3">
                                        <label for="outbound_pickup_location" class="fw-bold mb-2 required">Pickup Location</label>
                                        <input type="text" id="outbound_pickup_location" class="form-control form-control-solid" placeholder="Pickup location">
                                    </div>
                                    <div class="col-md-3">
                                        <label for="outbound_dropoff_location" class="fw-bold mb-2 required">Drop-off Location</label>
                                        <input type="text" id="outbound_dropoff_location" class="form-control form-control-solid" placeholder="Drop-off location">
                                    </div>
                                </div>
                            </div>

                            <div class="mb-5">
                                <label class="form-check form-check-custom form-check-solid mb-3">
                                    <input class="form-check-input" type="checkbox" id="return_trip_needed" value="1">
                                    <span class="form-check-label fw-bold">Return trip needed</span>
                                </label>

                                <div id="return-trip-fields" class="d-none">
                                    <div class="fw-bold mb-3">Return Trip</div>
                                    <div class="row g-4">
                                        <div class="col-md-3">
                                            <label for="return_pickup_time" class="fw-bold mb-2 required">Return Time</label>
                                            <input type="time" id="return_pickup_time" class="form-control form-control-solid">
                                        </div>
                                        <div class="col-md-3">
                                            <label for="return_contact_number" class="fw-bold mb-2 required">Contact Number</label>
                                            <input type="text" id="return_contact_number" class="form-control form-control-solid" placeholder="Contact number">
                                        </div>
                                        <div class="col-md-3">
                                            <label for="return_pickup_location" class="fw-bold mb-2 required">Pickup Location</label>
                                            <input type="text" id="return_pickup_location" class="form-control form-control-solid" placeholder="Pickup location">
                                        </div>
                                        <div class="col-md-3">
                                            <label for="return_dropoff_location" class="fw-bold mb-2 required">Drop-off Location</label>
                                            <input type="text" id="return_dropoff_location" class="form-control form-control-solid" placeholder="Drop-off location">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-5">
                                <div class="fw-bold mb-3">Notify Heads</div>
                                <label class="form-check form-check-custom form-check-solid mb-4">
                                    <input class="form-check-input" type="checkbox" id="notify_coordinator" value="1">
                                    <span class="form-check-label fw-bold">Notify Coordinator</span>
                                </label>

                                <div id="coordinator-to-notify-container" class="d-none">
                                    <label for="coordinator_to_notify" class="fw-bold mb-2 required">Select coordinator to notify the driver</label>
                                    <select id="coordinator_to_notify" class="form-select form-select-solid">
                                        <option value="" disabled selected>Select Coordinator</option>
                                        @foreach ($users as $user)
                                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>


                    <!-- Buttons -->
                    <div class="d-flex justify-content-center gap-5 ">
                        <a href="{{ route('calendar') }}">
                            <button type="button" class="btn btn-light fw-boldest">CANCEL</button>
                        </a>
                        <button type="button" id="add_training_back" class="btn btn-light-primary fw-boldest d-none">BACK</button>
                        <button type="button" id="add_training_submit" class="btn btn-success fw-boldest">CONTINUE</button>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="{{ asset('js/add_training.js') }}"></script>
    <script src="{{ asset('plugins/custom/formrepeater/formrepeater.bundle.js') }}"></script>


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
@endpush
