@extends('global.layout')

@section('maincontent')
    <div class="alps-calendar-shell d-flex flex-wrap gap-4 mt-20">
        <!-- Right Side: Calendar -->
        <div class="card shadow-sm alps-calendar-card">
            <div class="card-header d-flex justify-content-between align-items-center">

                <!-- Filter Button -->
                <div class="dropdown alps-calendar-filter-dropdown">
                <button class="btn btn-lg fw-boldest d-flex align-items-center btn-hover-rise text-white alps-filter-btn dropdown-toggle"
                    data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false">
                    <i class="bi bi-funnel me-1 text-white"></i> FILTER
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
                                    <option value="trainings" selected>Trainings</option>
                                    <option value="unavailability">Unavailability</option>
                                </select>
                            </div>
                        </form>

                        <!-- Actions -->
                        <div class="d-flex justify-content-end">
                            <!-- <button type="reset" class="btn btn-sm btn-light btn-active-light-primary me-2" id="calendarFilterReset"
                                data-kt-menu-dismiss="true">RESET</button> -->
                            <button id="applyFilter" type="button" class="btn btn-sm btn-primary" id="calendarFilterApply">APPLY</button>
                        </div>
                    </div>
                </div>
                </div>
                <!-- End Filter Menu -->



                     <!--begin::Add Button-->
                    <button type="button" class="btn btn-primary btn-lg fw-boldest btn-hover-rise text-white dropdown-toggle" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                        <span class="p-5">ADD</span>
                    </button>
                    <!--end::Add Button-->

                <ul class="dropdown-menu px-2" aria-labelledby="dropdownMenuButton">
                    @if (Auth::check() && in_array(Auth::user()->usertype, ['admin', 'coordinator']))
                        <!--begin::Link item-->
                        <li class="py-2">
                            <a href="{{ route('add_training') }}" class="dropdown-item">
                                <i class="bi bi-pencil-square text-primary fs-6 me-2"></i>
                                <span class="text-gray-700 fw-bold">Training</span>
                            </a>
                        </li>
                        <!--end::Link item-->
                    @endif
                    <!--begin::Link item-->
                    <li>
                        <a href="{{ route('add_unavailability') }}" class="dropdown-item" id="event_view">
                            <i class="bi bi-calendar-event text-info fs-6 me-2"></i>
                            <span class="text-gray-700 fw-bold">Unavailability</span>
                        </a>
                    </li>
                    <!--end::Link item-->
                </ul>
            </div>
            <div class="card alps-calendar-inner-card">
                <div class="card-body">
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

        {{-- <!--begin:: Modal for displaying excess events -->
        <div class="modal fade" id="kt_modal_view_training" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Event Details</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body" id="kt_modal_view_training_content">
                        <!-- Content will be dynamically added here -->
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div> --}}

    <!--begin::Modal - View Training-->
    <div class="modal fade" id="kt_modal_view_training" tabindex="-1" aria-hidden="true">
        <!--begin::Modal dialog-->
        <div class="modal-dialog modal-dialog-centered mw-650px">
            <!--begin::Modal content-->
            <div class="modal-content">
                <!--begin::Modal header-->
                <div class="modal-header border-0 justify-content-between align-items-center">

                    <h1 class="modal-title fw-boldest text-start alps-modal-title" id="">VIEW TRAINING</h1>
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
                        <h1 class="fs-1 fw-boldest text-primary" id="modal-course">Course</h1>
                        <h1 class="fs-4 fw-boldest" id="modal-company">Company</h1>
                        <p class="lead fs-5 ">happening on <span id="modal-date" class="fw-bold"></span>
                        <br>
                        from <span id="modal-time" class="fw-bold"></span></p>
                    </div>
                    <!-- Company -->
                    <!-- <div class="row mb-5 justify-content-between align-items-center">
                        <div class="col-5">
                            <div class="fv-row">
                                <label class="fs-6 fw-bold mb-2">
                                    <i class="bi bi-building fs-3 me-5 alps-icon-accent"></i>Company
                                </label>
                            </div>
                        </div>
                        <div class="col-7">
                            <div class="fv-row d-flex justify-content-end align-items-center">
                                <p class="lead fs-6" id="modal-company">PUPQC</p>
                            </div>
                        </div>
                    </div> -->
                    <!-- Facilitator -->
                    <div class="row mb-5 justify-content-between align-items-center">
                        <div class="col-5">
                            <div class="fv-row">
                                <label class="fs-6 fw-bold mb-2">
                                    <i class="bi bi-person-workspace fs-3 me-5 alps-icon-accent"></i>Facilitator
                                </label>
                            </div>
                        </div>
                        <div class="col-7">
                            <div class="fv-row d-flex justify-content-end align-items-center">
                                <p class="lead fs-6" id="modal-facilitator">Kimberly Mae M. Kho</p>
                            </div>
                        </div>
                    </div>
                    <!-- Assistant -->
                    <div class="row mb-5 justify-content-between align-items-center">
                        <div class="col-5">
                            <div class="fv-row">
                                <label class="fs-6 fw-bold mb-2">
                                    <i class="bi bi-person-plus fs-3 me-5 alps-icon-accent"></i>Assistant
                                </label>
                            </div>
                        </div>
                        <div class="col-7">
                            <div class="fv-row d-flex justify-content-end align-items-center">
                                <p class="lead fs-6" id="modal-assistant">Daniel A. Del Rosario</p>
                            </div>
                        </div>
                    </div>
                    <!-- Date -->
                    <!-- <div class="row mb-5 justify-content-between align-items-center">
                        <div class="col-5">
                            <div class="fv-row">
                                <label class="fs-6 fw-bold mb-2">
                                    <i class="bi bi-calendar3 fs-3 me-5 alps-icon-accent"></i>Date
                                </label>
                            </div>
                        </div>
                        <div class="col-7">
                            <div class="fv-row d-flex justify-content-end align-items-center">
                                <p class="lead fs-6" id="modal-date">Dec 9, 2024 to Dec 12, 2024</p>
                            </div>
                        </div>
                    </div> -->
                    <!-- Time -->
                    <!-- <div class="row mb-5 justify-content-between align-items-center">
                        <div class="col-5">
                            <div class="fv-row">
                                <label class="fs-6 fw-bold mb-2">
                                    <i class="bi bi-clock-fill fs-3 me-5 alps-icon-accent"></i>Time
                                </label>
                            </div>
                        </div>
                        <div class="col-7">
                            <div class="fv-row d-flex justify-content-end align-items-center">
                                <p class="lead fs-6" id="modal-time">9:00 AM to 10:00 PM</p>
                            </div>
                        </div>
                    </div> -->
                    <!-- Mode of Training -->
                    <div class="row mb-5 justify-content-between align-items-center">
                        <div class="col-5">
                            <div class="fv-row">
                                <label class="fs-6 fw-bold mb-2">
                                    <i class="bi bi-chat-left-text-fill fs-3 me-5 alps-icon-accent"></i>Mode of Training
                                </label>
                            </div>
                        </div>
                        <div class="col-7">
                            <div class="fv-row d-flex justify-content-end align-items-center">
                                <p class="lead fs-6" id="modal-mode-of-training">Public Course</p>
                            </div>
                        </div>
                    </div>
                    <!-- Credentials -->
                    <div class="row mb-5 justify-content-between align-items-center">
                        <div class="col-5">
                            <div class="fv-row">
                                <label class="fs-6 fw-bold mb-2">
                                    <i class="bi bi-easel-fill fs-3 me-5 alps-icon-accent"></i>Hosting Account
                                </label>
                            </div>
                        </div>
                        <div class="col-7">
                            <div class="fv-row d-flex justify-content-end align-items-center">
                                <p class="lead fs-6" id="modal-credentials">sample@gmail.com</p>
                            </div>
                            <div id="password-container" class="fv-row d-flex justify-content-end align-items-center">
                                <p class="lead fs-6 password-display alps-password-toggle">********</p>
                                <p class="password-actual d-none alps-password-toggle" id="modal-password"></p>
                            </div>
                            <!-- <div class="fv-row d-flex justify-content-end align-items-center">
                                <p class="lead fs-6 password-display alps-password-toggle">********</p>
                                <span class="password-actual d-none alps-password-toggle" id="modal-password">password</span>
                            </div> -->
                        </div>
                    </div>
                    <!-- In-person -->
                    <div class="row mb-5 justify-content-between align-items-center">
                        <div class="col-5">
                            <div class="fv-row">
                                <label class="fs-6 fw-bold mb-2">
                                    <i class="bi bi-person-fill fs-3 me-5 alps-icon-accent"></i>In-person?
                                </label>
                            </div>
                        </div>
                        <div class="col-7">
                            <div class="fv-row d-flex justify-content-end align-items-center">
                                <p class="lead fs-6" id="modal-in-person">Yes</p>
                            </div>
                        </div>
                    </div>
                    <!-- Location -->
                    <div class="row mb-5 justify-content-between align-items-center">
                        <div class="col-5">
                            <div class="fv-row">
                                <label class="fs-6 fw-bold mb-2">
                                    <i class="bi bi-geo-alt-fill fs-3 me-5 alps-icon-accent"></i>Location
                                </label>
                            </div>
                        </div>
                        <div class="col-7">
                            <div class="fv-row d-flex justify-content-end align-items-center">
                                <p class="lead fs-6" id="modal-location"></p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Modal Fo   er -->
                <div class="modal-footer w-100">

                    @if (Auth::check() && in_array(Auth::user()->usertype, ['admin', 'coordinator']))
                    <!-- Delete Button (Left) -->
                    <button type="button" class="btn btn-danger deleteBtn me-auto">
                        <i class="bi bi-trash me-2"></i>DELETE
                    </button>
                    @endif

                    <!-- Edit and Close Buttons (Right) -->
                    @if (Auth::check() && in_array(Auth::user()->usertype, ['admin', 'coordinator']))
                        <a href="#" id="edit-training-link" data-base-url="{{ url('calendar/edit_training') }}/" class="btn btn-primary me-2">
                            <i class="bi bi-pencil-fill me-2"></i>EDIT
                        </a>
                    @endif
                    <button type="reset" class="btn btn-light" data-bs-dismiss="modal">CLOSE</button>
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
            <div class="modal-header border-0 justify-content-between align-items-center">
                <h1 class="modal-title fw-boldest text-start alps-modal-title"
                {{-- id="modal-title" --}}
                >VIEW UNAVAILABILITY</h1>
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
                    {{-- <p class="lead fs-5 ">happening on <span id="modal-date" class="fw-bold"></span>
                    <br>
                    from <span id="modal-time" class="fw-bold"></span></p> --}}
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
                            <p class="lead fs-6" id="modal-purpose">Team Building</p>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Modal Footer -->
            <div class="modal-footer w-100">
                <!-- Delete Button (Left) -->
                <button type="button" class="btn btn-danger deleteBtnUnavailability me-auto">
                    <i class="bi bi-trash me-2"></i>DELETE
                </button>
                <!-- Close Buttons (Right) -->
                <button type="reset" class="btn btn-light" data-bs-dismiss="modal">CLOSE</button>
            </div>

        </div>
    </div>
</div>
@endsection

    @push('scripts')

        @if (Auth::check() && in_array(Auth::user()->usertype, ['admin', 'coordinator', 'facilitator']))
            <script>

               let authenticated_user = {{ Auth::user()->id }};
               let authenticated_usertype = "{{ Auth::user()->usertype }}";

            </script>
            <script src="{{ asset('js/calendar.js') }}"></script>
        @else
            <script src="{{ asset('js/unavailability_calendar.js') }}"></script>
        @endif

    @endpush
