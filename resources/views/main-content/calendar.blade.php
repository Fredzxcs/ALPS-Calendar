@extends('global.layout')

@section('maincontent')


    <div class="mt-4 d-flex flex-wrap gap-4 mt-20">
        {{-- <!-- Left Side: Search Course and Search Trainer -->
    <div class="d-flex flex-column" style="flex: 1; max-width: 30%; gap: 20px;">
        <!-- Search Course Card -->
        <div class="card shadow-sm">
            <div class="card-body">
                <div class="mb-3 position-relative">
                    <input type="text" class="form-control form-control-solid ps-5" placeholder="Search course" />
                </div>
                <button class="btn btn-sm mb-3 btn-hover-rise text-white" style="background-color: #7c0101;">+ Add Course</button>
                <table class="table table-sm table-bordered table-hover align-middle">
                    <thead>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="text-center py-1">
                                <input type="checkbox" id="course1" />
                            </td>
                            <td class="py-1"><label for="course1" class="mb-0">Project Management</label></td>
                        </tr>
                        <tr>
                            <td class="text-center py-1">
                                <input type="checkbox" id="course2" />
                            </td>
                            <td class="py-1"><label for="course2" class="mb-0">Agile Scrum</label></td>
                        </tr>
                        <tr>
                            <td class="text-center py-1">
                                <input type="checkbox" id="course3" />
                            </td>
                            <td class="py-1"><label for="course3" class="mb-0">Excel</label>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-center py-1">
                                <input type="checkbox" id="course4" />
                            </td>
                            <td class="py-1"><label for="course4" class="mb-0">Course 1</label></td>
                        </tr>
                        <tr>
                            <td class="text-center py-1">
                                <input type="checkbox" id="course5" />
                            </td>
                            <td class="py-1"><label for="course5" class="mb-0">Course 2</label></td>
                        </tr>
                        <tr>
                            <td class="text-center py-1">
                                <input type="checkbox" id="course6" />
                            </td>
                            <td class="py-1"><label for="course6" class="mb-0">Course 3</label></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <!-- Search Trainer Card -->
        <div class="card shadow-sm">
            <div class="card-body">
                <div class="mb-3">
                    <input type="text" class="form-control form-control-solid" placeholder="Search facilitator" />
                </div>
                <button class="btn btn-sm mb-3 btn-hover-rise text-white" style="background-color: #7c0101;;">+ Add
                    Facilitator</button>
                <table class="table table-sm table-bordered table-hover align-middle">
                    <thead>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="text-center py-1">
                                <input type="checkbox" id="trainer1" />
                            </td>
                            <td class="py-1"><label for="trainer1" class="mb-0">Rechelle Salas</label></td>
                        </tr>
                        <tr>
                            <td class="text-center py-1">
                                <input type="checkbox" id="trainer2" />
                            </td>
                            <td class="py-1"><label for="trainer2" class="mb-0">Kimberly Mae Kho</label></td>
                        </tr>
                        <tr>
                            <td class="text-center py-1">
                                <input type="checkbox" id="trainer3" />
                            </td>
                            <td class="py-1"><label for="trainer3" class="mb-0">Rafael Joar Parungo</label></td>
                        </tr>
                        <tr>
                            <td class="text-center py-1">
                                <input type="checkbox" id="trainer4" />
                            </td>
                            <td class="py-1"><label for="trainer4" class="mb-0">John Loyd Cabral</label></td>
                        </tr>
                        <tr>
                            <td class="text-center py-1">
                                <input type="checkbox" id="trainer5" />
                            </td>
                            <td class="py-1"><label for="trainer5" class="mb-0">Daniel Del Rosario</label></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div> --}}
        <!-- Right Side: Calendar -->
        <div class="card shadow-sm" style="flex: 2;">
            <div class="card-header d-flex justify-content-end align-items-center">
                <!--begin::Add Button-->
                <button type="button" class="btn btn-primary btn-lg m-3 btn-hover-rise dropdown-toggle" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-start">
                    <span class="p-5 fs-4">Add</span>
                </button>
                <!--end::Add Button-->
                <!--begin::Links-->
                <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-bold fs-7 w-200px py-4" data-kt-menu="true">
                    <!--begin::Link item-->
                    <div class="menu-item px-3">
                        <a href="{{ route('add_training') }}" class="menu-link px-3">
                            <i class="bi bi-pencil-square text-primary fs-6 me-2"></i>Training
                        </a>
                    </div>
                    <!--end::Link item-->
                    <!--begin::Link item-->
                    <div class="menu-item px-3">
                        <a href="" class="menu-link px-3" id="event_view">
                            <i class="bi bi-calendar-event text-info fs-6 me-2"></i>Unavailability
                        </a>
                    </div>
                    <!--end::Link item-->
                </div>
                <!--end::Links-->
            </div>
            <div class="card" style="height: auto;">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-center">
                        <!-- Loader wrapper positioned above the calendar -->
                        <div id="loader-wrapper" class="position-absolute top-0 start-0 end-0 bottom-0 d-flex justify-content-center align-items-center mb-10" style="z-index: 1050;  display: none;">
                            <div class="spinner-border" style="width: 5rem; height: 5rem;" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </div>
                    </div>
                    <div id="calendar" class="border border-3 border-gray-200 p-10" style="height: 100%; border-radius: 5px;">
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
                    <h1 class="modal-title fw-boldest text-start" style="color:#7c0101;" id="modal-title">VIEW TRAINING</h1>
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
                        <p class="lead fs-5 ">happening on <span id="modal-date" class="fw-bold"></span> 
                        <br>
                        from <span id="modal-time" class="fw-bold"></span></p>
                    </div>
                    <!-- Company -->
                    <div class="row mb-5 justify-content-between align-items-center">
                        <div class="col-5">
                            <div class="fv-row">
                                <label class="fs-6 fw-bold mb-2">
                                    <i class="bi bi-building fs-3 me-5" style="color: #7c0101;"></i>Company
                                </label>
                            </div>
                        </div>
                        <div class="col-7">
                            <div class="fv-row d-flex justify-content-end align-items-center">
                                <p class="lead fs-6" id="modal-company">PUPQC</p>
                            </div>
                        </div>
                    </div>
                    <!-- Facilitator -->
                    <div class="row mb-5 justify-content-between align-items-center">
                        <div class="col-5">
                            <div class="fv-row">
                                <label class="fs-6 fw-bold mb-2">
                                    <i class="bi bi-person-workspace fs-3 me-5" style="color: #7c0101;"></i>Facilitator
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
                                    <i class="bi bi-person-plus fs-3 me-5" style="color: #7c0101;"></i>Assistant
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
                                    <i class="bi bi-calendar3 fs-3 me-5" style="color: #7c0101;"></i>Date
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
                                    <i class="bi bi-clock-fill fs-3 me-5" style="color: #7c0101;"></i>Time
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
                                    <i class="bi bi-chat-left-text-fill fs-3 me-5" style="color: #7c0101;"></i>Mode of Training
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
                                    <i class="bi bi-easel-fill fs-3 me-5" style="color: #7c0101;"></i>Hosting Account
                                </label>
                            </div>
                        </div>
                        <div class="col-7">
                            <div class="fv-row d-flex justify-content-end align-items-center">
                                <p class="lead fs-6" id="modal-credentials">sample@gmail.com</p>
                            </div>
                        </div>
                    </div>
                    <!-- In-person -->
                    <div class="row mb-5 justify-content-between align-items-center">
                        <div class="col-5">
                            <div class="fv-row">
                                <label class="fs-6 fw-bold mb-2">
                                    <i class="bi bi-person-fill fs-3 me-5" style="color: #7c0101;"></i>In-person?
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
                                    <i class="bi bi-geo-alt-fill fs-3 me-5" style="color: #7c0101;"></i>Location
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

                <!-- Modal Footer -->
                <div class="modal-footer d-flex justify-content-between">
                    <!-- Delete -->
                    <div>
                        <button type="button" class="btn btn-danger deleteBtn">
                            <i class="bi bi-trash me-2"></i>DELETE
                        </button>
                    </div>

                    <!-- Edit and Close -->
                    <div>
                        <a href="{{route ('edit_training')}}" class="btn btn-primary me-2">
                            <i class="bi bi-pencil-fill me-2"></i>EDIT
                        </a>
                        <button type="reset" class="btn btn-light" data-bs-dismiss="modal">CLOSE</button>
                    </div>
                </div>
            </div>
        </div>
    </div>


        @endsection
    @push('scripts')
        <script src="{{ asset('js/calendar.js') }}"></script>
    @endpush
