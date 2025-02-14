@extends('global.layout')

@section('maincontent')
    <div class="d-flex justify-content-center align-items-center mt-20 ">
        <div class="container mt-5 ">
            <!-- Card -->
            <div class="card shadow-sm rounded-3">
                <!-- Title -->
                <div class="d-flex justify-content-center align-items-center mb-0 bg-primary rounded-top h-80px">
                    <h2 class="text-white fw-boldest m-0 fs-1">EDIT TRAINING</h2>
                </div>
                <!-- Form -->
                <div class="p-20 pt-10 pb-6 ">
                    <form>
                        @csrf
                        <!-- Mode of Training -->
                        <div class="mb-4">
                            <label class="fw-bold mb-2 required">Mode of Training</label>
                            <div class="d-flex gap-4">
                                <div>
                                    <input type="radio" id="virtual" value="virtual" name="mode" class="form-check-input"
                                        {{ $training->mode == 'virtual' ? 'checked' : '' }}>
                                    <label for="virtual" class="form-check-label">Virtual</label>
                                </div>
                                <div>
                                    <input type="radio" id="face-to-face" value="face-to-face" name="mode" class="form-check-input"
                                        {{ $training->mode == 'face-to-face' ? 'checked' : '' }}>
                                    <label for="face-to-face" class="form-check-label">Face-to-Face</label>
                                </div>
                                <div>
                                    <input type="radio" id="public-course" value="public-course" name="mode" class="form-check-input"
                                        {{ $training->mode == 'public-course' ? 'checked' : '' }}>
                                    <label for="public-course" class="form-check-label">Public Course</label>
                                </div>
                            </div>
                        </div>


                        <!-- Public Course: Course and In-person Training -->
                        <div class="row mb-4 d-none" id="public-course-container">
                            <div class="col-md-12 d-flex align-items-center justify-content-between">
                                <!-- In-person Checkbox -->
                                <div class="form-check me-3">
                                    <input
                                        type="checkbox"
                                        id="inperson-training"
                                        class="form-check-input"
                                        >
                                    <label for="inperson-training" class="form-check-label fw-bold">In-person training?</label>
                                </div>

                                <!-- Course -->
                                <div class="d-flex align-items-center" style="width: 49%;">
                                    <div class="flex-grow-1">
                                        <label for="public-course-select" class="fw-bold mb-2 required">Course</label>
                                        <select id="public-course-select" class="form-select form-select-solid">
                                            <option value="" disabled selected>Select Course</option>
                                            @foreach ($courses as $course)
                                            <option value="{{ $course->id }}"
                                                {{ $training->course && $course->id === $training->course->id ? 'selected' : '' }}>
                                                {{ $course->course_code ? $course->course_code . ' - ' : '' }}{{ $course->course_name }}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Email and Password -->
                        <div class="row mb-4" id="credentials-container">
                            <div class="col-md-6">
                                <label for="credentials" class="fw-bold mb-2 required">Account</label>
                                <select id="credentials" class="form-select form-select-solid">
                                    <option value="" disabled selected>Select Host Email Account</option>
                                    @foreach ($accounts as $account)
                                        <option value="{{ $account->id }}"

                                            @isset($training->account->id)

                                                {{ $account->id === $training->account->id ? 'selected' : '' }}

                                            @endisset

                                            >
                                            {{ $account->account_email }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6" id="platform-container">
                                <label for="platform" class="fw-bold mb-2">Platform</label>
                                <input type="text" value="{{ $training->platform }}" name="platform" id="platform" class="form-control form-control-solid" placeholder="Enter Platform (e.g. Zoom)">
                            </div>
                        </div>

                        <!-- Location: Face-to-Face and In-Person -->
                        <div class="mb-4 d-none" id="location-container">
                            <label for="location" class="fw-bold mb-2 required">Location </label>
                            <input value="{{ $training->location }}" type="text" id="location" class="form-control form-control-solid" placeholder="Enter Location">
                        </div>
                    </form>
                    <div class="row mb-4" id="company-course-container">
                        <!-- Company -->
                        {{-- need form repeater --}}
                        <div class="col-md-6 d-flex align-items-center">
                            <div class="flex-grow-1">
                                <label for="company" class="fw-bold mb-2 required">Company</label>
                                <select id="company" class="form-select form-select-solid">
                                    <option value="" disabled {{ !$training->company ? 'selected' : '' }}>Select Company</option>
                                    @foreach ($companies as $company)
                                    <option value="{{ $company->id }}"
                                        {{ $training->company && $company->id === $training->company->id ? 'selected' : '' }}>
                                        {{ $company->company_name }}
                                    </option>
                                @endforeach
                                    <option value="other">Other</option>
                                </select>
                                <input type="text" id="enter-company" class="form-control form-control-solid d-none" placeholder="Enter Company">
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
                                    <option value="{{ $course->id }}"
                                        {{ $training->course && $training->course->id === $course->id ? 'selected' : '' }}>
                                        {{ $course->course_name }}
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
                                <input type="text" id="date-range" class="form-control form-control-solid pe-5"
                                    value="{{ $training->start_date && $training->end_date ? $training->start_date . ' to ' . $training->end_date : '' }}">
                                <span class="position-absolute top-50 end-0 translate-middle-y me-3 text-muted">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-calendar-heart-fill" viewBox="0 0 16 16">
                                        <path d="M4 .5a.5.5 0 0 0-1 0V1H2a2 2 0 0 0-2 2v1h16V3a2 2 0 0 0-2-2h-1V.5a.5.5 0 0 0-1 0V1H4zM16 14V5H0v9a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2M8 7.993c1.664-1.711 5.825 1.283 0 5.132-5.825-3.85-1.664-6.843 0-5.132"/>
                                    </svg>
                                </span>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label for="time-start" class="fw-bold mb-2 required">Time Start</label>
                            <input
                                type="time"
                                id="time-start"
                                class="form-control form-control-solid"
                                value="{{ $training->schedule->from_time ? \Carbon\Carbon::parse($training->schedule->from_time)->format('H:i') : '' }}">
                        </div>
                        <div class="col-md-4">
                            <label for="time-end" class="fw-bold mb-2 required">Time End</label>
                            <input
                                type="time"
                                id="time-end"
                                class="form-control form-control-solid"
                                value="{{ $training->schedule->to_time ? \Carbon\Carbon::parse($training->schedule->to_time)->format('H:i') : '' }}">
                        </div>

                    </div>


                    <!-- Facilitator and Assistant -->
                    <div class="row mb-5 align-items-start">
                        <!-- Facilitator -->
                        <div class="col-md-6">
                            <label for="facilitator" class="fw-bold mb-2 required">Facilitator</label>
                            <select id="facilitator" class="form-select form-select-solid">
                                <option disabled selected>Select Facilitator</option>
                                <option value="" {{ is_null(optional($training->facilitator)->id) ? 'selected' : '' }}>No Facilitator Yet</option>
                                @foreach ($facilitators as $user)
                                    <option value="{{ $user->id }}" {{ $user->id == optional($training->facilitator)->id ? 'selected' : '' }}>
                                        {{ $user->name }}
                                    </option>
                                @endforeach
                            </select>

                            <div class="invalid-feedback">Required field</div>

                        </div>
                        <!-- Assistant -->
                        <div class="col-md-6">
                            <!-- Repeater -->
                            <div id="asst_repeat">
                                <div class="form-group">
                                    <!-- Label for the Assistant field -->
                                    <label class="form-label">Assistant:</label>

                                    <!-- Repeater List -->
                                    <div data-repeater-list="asst_repeat">
                                        <div data-repeater-item>
                                            <div class="form-group row align-items-center">
                                                <div class="col-md-9">
                                                    <input type="text" value="{{ $training->assistant }}" class="form-control form-control-solid mb-3 assistant" id="assistant" placeholder="Enter Assistant's Name" />
                                                </div>
                                                <div class="col-md-3">
                                                    <a href="javascript:;" data-repeater-delete class="btn btn-sm btn-light-danger mb-3">
                                                        <i class="la la-trash-o"></i> DELETE
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Add Button -->
                                <div class="form-group mt-3">
                                    <a href="javascript:;" data-repeater-create class="btn btn-light-primary btn-sm">
                                        <i class="la la-plus"></i> ADD
                                    </a>
                                </div>
                            </div>
                            <!-- End Repeater -->
                        </div>
                    </div>

                    <!-- Buttons -->
                    <div class="d-flex justify-content-center gap-5 ">
                        <a href="#">
                            <button type="button" id="cancel_training_button" class="btn btn-light fw-boldest">CANCEL</button>
                        </a>
                        <button type="button" id="edit_training_submit" class="btn btn-success fw-boldest">SAVE</button>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="{{ asset('plugins/custom/formrepeater/formrepeater.bundle.js') }}"></script>
    <script>
        const modeRadios = document.querySelectorAll('input[name="mode"]');
        const companyContainer = document.getElementById("company-container");
        const credentialsContainer = document.getElementById("credentials-container");
        const locationContainer = document.getElementById("location-container");
        const inpersonCheckbox = document.getElementById("inperson-training");
        const companyCourseContainer = document.getElementById("company-course-container");
        const publicCourseContainer = document.getElementById("public-course-container");

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

            $(document).ready(function (){

                // if (inpersonCheckbox.checked) {
                //     credentialsContainer.classList.add("d-none");
                //     locationContainer.classList.remove("d-none");
                // } else {
                //     credentialsContainer.classList.remove("d-none");
                //     locationContainer.classList.add("d-none");
                // }

                //Initally check and display the correct fields
                if ("{{ $training->mode }}" === "virtual") {
                    // Virtual: Show Email/Password, hide others
                    credentialsContainer.classList.remove("d-none");
                    locationContainer.classList.add("d-none");
                    publicCourseContainer.classList.add("d-none");
                    companyCourseContainer.classList.remove("d-none");
                } else if ("{{ $training->mode }}" === "face-to-face") {
                    // Face-to-Face: Show Location, hide Email/Password
                    credentialsContainer.classList.add("d-none");
                    locationContainer.classList.remove("d-none");
                    publicCourseContainer.classList.add("d-none");
                    companyCourseContainer.classList.remove("d-none");
                } else if ("{{ $training->mode }}" === "public-course") {
                    // Public Course: Show Public Course layout, hide Company/Course
                    credentialsContainer.classList.remove("d-none");
                    publicCourseContainer.classList.remove("d-none");
                    companyCourseContainer.classList.add("d-none");
                    locationContainer.classList.add("d-none");
                }

            });

        function formatDate(date) {
            const day = date.getDate().toString().padStart(2, '0'); // Add leading zero for day
            const month = (date.getMonth() + 1).toString().padStart(2, '0'); // Get month, adjust by +1 (months are 0-based)
            const year = date.getFullYear(); // Get full year

            return `${year}-${month}-${day}`; // Return in YYYY-MM-DD format
        }

        let startDateFormatted;
        let endDateFormatted;

        const fp = flatpickr("#date-range", {
            mode: "range",
            dateFormat: "m-d-Y",
            minDate: "today",
            defaultDate: [
                '{{ \Carbon\Carbon::parse($training->schedule->from_date)->format('m-d-Y') }}',
                '{{ \Carbon\Carbon::parse($training->schedule->to_date)->format('m-d-Y') }}'
            ],
            onChange: function(selectedDates) {
                if (selectedDates.length >= 2) {
                    const initialStartDate = selectedDates[0];
                    const initialEndDate = selectedDates[1];
                    startDateFormatted = formatDate(initialStartDate);
                    endDateFormatted = formatDate(initialEndDate);
                    console.log("Start Date:", startDateFormatted);
                    console.log("End Date:", endDateFormatted);
                }
            }
        });

        startDateFormatted = startDateFormatted || '{{ \Carbon\Carbon::parse($training->schedule->from_date)->format('Y-m-d') }}';
        endDateFormatted = endDateFormatted || '{{ \Carbon\Carbon::parse($training->schedule->to_date)->format('Y-m-d') }}';

    </script>
    <script src="{{ asset('js/edit_training.js') }}"></script>
@endpush
