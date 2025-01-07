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
    <div class="modal fade" id="kt_modal_view_event" tabindex="-1" aria-hidden="true" >
        <!--begin::Modal dialog-->
        <div class="modal-dialog modal-dialog-centered mw-650px">
            <!--begin::Modal content-->
            <div class="modal-content">
                <!--begin::Modal body-->
                <div class="modal-body pt-0 pb-20 px-lg-17">
                    <!--begin::Row-->
                    <div class="d-flex">
                        <!--begin::Icon-->
                        <!--begin::Svg Icon | path: icons/duotune/general/gen014.svg-->
                        <span class="svg-icon svg-icon-1 svg-icon-muted me-5">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                <path opacity="0.3" d="M21 22H3C2.4 22 2 21.6 2 21V5C2 4.4 2.4 4 3 4H21C21.6 4 22 4.4 22 5V21C22 21.6 21.6 22 21 22Z" fill="black" />
                                <path d="M6 6C5.4 6 5 5.6 5 5V3C5 2.4 5.4 2 6 2C6.6 2 7 2.4 7 3V5C7 5.6 6.6 6 6 6ZM11 5V3C11 2.4 10.6 2 10 2C9.4 2 9 2.4 9 3V5C9 5.6 9.4 6 10 6C10.6 6 11 5.6 11 5ZM15 5V3C15 2.4 14.6 2 14 2C13.4 2 13 2.4 13 3V5C13 5.6 13.4 6 14 6C14.6 6 15 5.6 15 5ZM19 5V3C19 2.4 18.6 2 18 2C17.4 2 17 2.4 17 3V5C17 5.6 17.4 6 18 6C18.6 6 19 5.6 19 5Z" fill="black" />
                                <path
                                    d="M8.8 13.1C9.2 13.1 9.5 13 9.7 12.8C9.9 12.6 10.1 12.3 10.1 11.9C10.1 11.6 10 11.3 9.8 11.1C9.6 10.9 9.3 10.8 9 10.8C8.8 10.8 8.59999 10.8 8.39999 10.9C8.19999 11 8.1 11.1 8 11.2C7.9 11.3 7.8 11.4 7.7 11.6C7.6 11.8 7.5 11.9 7.5 12.1C7.5 12.2 7.4 12.2 7.3 12.3C7.2 12.4 7.09999 12.4 6.89999 12.4C6.69999 12.4 6.6 12.3 6.5 12.2C6.4 12.1 6.3 11.9 6.3 11.7C6.3 11.5 6.4 11.3 6.5 11.1C6.6 10.9 6.8 10.7 7 10.5C7.2 10.3 7.49999 10.1 7.89999 10C8.29999 9.90003 8.60001 9.80003 9.10001 9.80003C9.50001 9.80003 9.80001 9.90003 10.1 10C10.4 10.1 10.7 10.3 10.9 10.4C11.1 10.5 11.3 10.8 11.4 11.1C11.5 11.4 11.6 11.6 11.6 11.9C11.6 12.3 11.5 12.6 11.3 12.9C11.1 13.2 10.9 13.5 10.6 13.7C10.9 13.9 11.2 14.1 11.4 14.3C11.6 14.5 11.8 14.7 11.9 15C12 15.3 12.1 15.5 12.1 15.8C12.1 16.2 12 16.5 11.9 16.8C11.8 17.1 11.5 17.4 11.3 17.7C11.1 18 10.7 18.2 10.3 18.3C9.9 18.4 9.5 18.5 9 18.5C8.5 18.5 8.1 18.4 7.7 18.2C7.3 18 7 17.8 6.8 17.6C6.6 17.4 6.4 17.1 6.3 16.8C6.2 16.5 6.10001 16.3 6.10001 16.1C6.10001 15.9 6.2 15.7 6.3 15.6C6.4 15.5 6.6 15.4 6.8 15.4C6.9 15.4 7.00001 15.4 7.10001 15.5C7.20001 15.6 7.3 15.6 7.3 15.7C7.5 16.2 7.7 16.6 8 16.9C8.3 17.2 8.6 17.3 9 17.3C9.2 17.3 9.5 17.2 9.7 17.1C9.9 17 10.1 16.8 10.3 16.6C10.5 16.4 10.5 16.1 10.5 15.8C10.5 15.3 10.4 15 10.1 14.7C9.80001 14.4 9.50001 14.3 9.10001 14.3C9.00001 14.3 8.9 14.3 8.7 14.3C8.5 14.3 8.39999 14.3 8.39999 14.3C8.19999 14.3 7.99999 14.2 7.89999 14.1C7.79999 14 7.7 13.8 7.7 13.7C7.7 13.5 7.79999 13.4 7.89999 13.2C7.99999 13 8.2 13 8.5 13H8.8V13.1ZM15.3 17.5V12.2C14.3 13 13.6 13.3 13.3 13.3C13.1 13.3 13 13.2 12.9 13.1C12.8 13 12.7 12.8 12.7 12.6C12.7 12.4 12.8 12.3 12.9 12.2C13 12.1 13.2 12 13.6 11.8C14.1 11.6 14.5 11.3 14.7 11.1C14.9 10.9 15.2 10.6 15.5 10.3C15.8 10 15.9 9.80003 15.9 9.70003C15.9 9.60003 16.1 9.60004 16.3 9.60004C16.5 9.60004 16.7 9.70003 16.8 9.80003C16.9 9.90003 17 10.2 17 10.5V17.2C17 18 16.7 18.4 16.2 18.4C16 18.4 15.8 18.3 15.6 18.2C15.4 18.1 15.3 17.8 15.3 17.5Z"
                                    fill="black" />
                            </svg>
                        </span>
                        <!--end::Svg Icon-->
                        <!--end::Icon-->
                        <div class="mb-9">
                            <!--begin::Event name-->
                            <div class="d-flex align-items-center mb-2">
                                <span class="fs-3 fw-bolder me-3" data-kt-calendar="event_name"></span>
                                <span class="badge badge-light-success" data-kt-calendar="all_day"></span>
                            </div>
                            <!--end::Event name-->
                            <!--begin::Event description-->
                            <div class="fs-6" data-kt-calendar="event_description"></div>
                            <!--end::Event description-->
                        </div>
                    </div>
                    <!--end::Row-->
                    <!--begin::Row-->
                    <div class="d-flex align-items-center mb-2">
                        <!--begin::Icon-->
                        <!--begin::Svg Icon | path: icons/duotune/abstract/abs050.svg-->
                        <span class="svg-icon svg-icon-1 svg-icon-success me-5">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                <circle fill="#000000" cx="12" cy="12" r="8" />
                            </svg>
                        </span>
                        <!--end::Svg Icon-->
                        <!--end::Icon-->
                        <!--begin::Event start date/time-->
                        <div class="fs-6">
                            <span class="fw-bolder">Starts</span>
                            <span data-kt-calendar="event_start_date"></span>
                        </div>
                        <!--end::Event start date/time-->
                    </div>
                    <!--end::Row-->
                    <!--begin::Row-->
                    <div class="d-flex align-items-center mb-9">
                        <!--begin::Icon-->
                        <!--begin::Svg Icon | path: icons/duotune/abstract/abs050.svg-->
                        <span class="svg-icon svg-icon-1 svg-icon-danger me-5">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                <circle fill="#000000" cx="12" cy="12" r="8" />
                            </svg>
                        </span>
                        <!--end::Svg Icon-->
                        <!--end::Icon-->
                        <!--begin::Event end date/time-->
                        <div class="fs-6">
                            <span class="fw-bolder">Ends</span>
                            <span data-kt-calendar="event_end_date"></span>
                        </div>
                        <!--end::Event end date/time-->
                    </div>
                    <!--end::Row-->
                    <!--begin::Row-->
                    <div class="d-flex align-items-center">
                        <!--begin::Icon-->
                        <!--begin::Svg Icon | path: icons/duotune/general/gen018.svg-->
                        <span class="svg-icon svg-icon-1 svg-icon-muted me-5">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                <path opacity="0.3" d="M18.0624 15.3453L13.1624 20.7453C12.5624 21.4453 11.5624 21.4453 10.9624 20.7453L6.06242 15.3453C4.56242 13.6453 3.76242 11.4453 4.06242 8.94534C4.56242 5.34534 7.46242 2.44534 11.0624 2.04534C15.8624 1.54534 19.9624 5.24534 19.9624 9.94534C20.0624 12.0453 19.2624 13.9453 18.0624 15.3453Z" fill="black" />
                                <path d="M12.0624 13.0453C13.7193 13.0453 15.0624 11.7022 15.0624 10.0453C15.0624 8.38849 13.7193 7.04535 12.0624 7.04535C10.4056 7.04535 9.06241 8.38849 9.06241 10.0453C9.06241 11.7022 10.4056 13.0453 12.0624 13.0453Z" fill="black" />
                            </svg>
                        </span>
                        <!--end::Svg Icon-->
                        <!--end::Icon-->
                        <!--begin::Event location-->
                        <div class="fs-6" data-kt-calendar="event_location"></div>
                        <!--end::Event location-->
                    </div>
                    <!--end::Row-->
                </div>
                <!--end::Modal body-->
            </div>
        </div>
    </div>
    <!--end::Modal - New Product-->
    <!--end::Modals-->
@endsection
@push('scripts')
    <script src="{{ asset('js/calendar.js') }}"></script>
@endpush
