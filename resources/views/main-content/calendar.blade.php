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
    <!--begin::Modal - New Product-->
    <div class="modal fade" id="kt_modal_view_event" tabindex="-1" aria-hidden="true">
        <!--begin::Modal dialog-->
        <div class="modal-dialog modal-dialog-centered mw-650px ">
            <!--begin::Modal content-->
            <div class="modal-content">
                <!--begin::Modal header-->
                <div class="modal-header border-0 justify-content-between align-items-center">
                    <h1 class="modal-title fw-boldest text-start" style="color:#7c0101;" id="viewTrainingLabel">VIEW TRAINING</h1>
                    <!--begin::Close-->
                    <div class="btn btn-icon btn-sm btn-color-gray-500 btn-active-icon-primary" data-bs-toggle="tooltip" title="Hide Event" data-bs-dismiss="modal">
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
                <div class="modal-body pt-0 pb-0 px-lg-17">
                    <!-- Data Rows -->
                    <div class="container text-black d-flex flex-column justify-content-center">
                        <!-- Row: Mode of Training -->
                        <div class="row mb-3 text-start align-items-center justify-content-end">
                            <div class="col-4 d-flex align-items-center">
                                <i class="bi bi-chat-left-text-fill me-2" style="color: #7c0101"></i>
                                <span class="fw-bold ">Mode of Training</span>
                            </div>
                            <div class="col-8 text-end ps-4">Public Course</div> <!-- Added ps-4 for left padding -->
                        </div>

                        <!-- Row: In-person -->
                        <div class="row mb-3 text-start align-items-center justify-content-end">
                            <div class="col-4 d-flex align-items-center">
                                <i class="bi bi-person-fill  me-2" style="color: #7c0101"></i>
                                <span class="fw-bold ">In-person?</span>
                            </div>
                            <div class="col-8 text-end ps-4">Yes</div> <!-- Added ps-4 for left padding -->
                        </div>

                        <!-- Row: Location -->
                        <div class="row mb-3 text-start align-items-center justify-content-end">
                            <div class="col-4 d-flex align-items-center">
                                <i class="bi bi-geo-alt-fill  me-2" style="color: #7c0101"></i>
                                <span class="fw-bold ">Location</span>
                            </div>
                            <div class="col-8 text-end ps-4">PUP - Quezon City Campus</div> <!-- Added ps-4 for left padding -->
                        </div>

                        <!-- Row: Company -->
                        <div class="row mb-3 text-start align-items-center justify-content-end">
                            <div class="col-4 d-flex align-items-center">
                                <i class="bi bi-building  me-2" style="color: #7c0101"></i>
                                <span class="fw-bold ">Company</span>
                            </div>
                            <div class="col-8 text-end ps-4">PUPQC</div> <!-- Added ps-4 for left padding -->
                        </div>

                        <!-- Row: Course -->
                        <div class="row mb-3 text-start align-items-center justify-content-end">
                            <div class="col-4 d-flex align-items-center">
                                <i class="bi bi-book  me-2" style="color: #7c0101"></i>
                                <span class="fw-bold ">Course</span>
                            </div>
                            <div class="col-8 text-end ps-4">Project Management</div> <!-- Added ps-4 for left padding -->
                        </div>

                        <!-- Row: Date -->
                        <div class="row mb-3 text-start align-items-center justify-content-end">
                            <div class="col-4 d-flex align-items-center">
                                <i class="bi bi-calendar3  me-2" style="color: #7c0101"></i>
                                <span class="fw-bold ">Date</span>
                            </div>
                            <div class="col-8 text-end ps-4">Dec 09, 2024 to Dec 12, 2024</div> <!-- Added ps-4 for left padding -->
                        </div>

                        <!-- Row: Time -->
                        <div class="row mb-3 text-start align-items-center justify-content-end">
                            <div class="col-4 d-flex align-items-center">
                                <i class="bi bi-clock-fill  me-2" style="color: #7c0101"></i>
                                <span class="fw-bold ">Time</span>
                            </div>
                            <div class="col-8 text-end ps-4">9:00 AM to 10:00 PM</div> <!-- Added ps-4 for left padding -->
                        </div>

                        <!-- Row: Facilitator -->
                        <div class="row mb-3 text-start align-items-center justify-content-end">
                            <div class="col-4 d-flex align-items-center">
                                <i class="bi bi-person-square  me-2" style="color: #7c0101"></i>
                                <span class="fw-bold ">Facilitator</span>
                            </div>
                            <div class="col-8 text-end ps-4">Kimberly Mae M. Kho</div> <!-- Added ps-4 for left padding -->
                        </div>

                        <!-- Row: Assistant -->
                        <div class="row mb-3 text-start align-items-center justify-content-end">
                            <div class="col-4 d-flex align-items-center">
                                <i class="bi bi-person-dash-fill  me-2" style="color: #7c0101"></i>
                                <span class="fw-bold ">Assistant</span>
                            </div>
                            <div class="col-8 text-end  ps-4">No Assistant Yet</div> <!-- Added ps-4 for left padding -->
                        </div>
                    </div>
                </div>
                <!--end::Modal body-->

                <!-- Begin: Modal buttons -->
                <div class="modal-footer justify-content-end py-2">
                    <button type="button" class="btn btn-primary">
                        <i class="bi bi-pencil"></i> EDIT
                    </button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        CANCEL
                    </button>
                </div>
                <!-- end: Modal buttons -->
            </div>
        </div>
    </div>
    <!--end::Modal - New Product-->
@endsection
@push('scripts')
    <script src="{{ asset('js/calendar.js') }}"></script>
@endpush
