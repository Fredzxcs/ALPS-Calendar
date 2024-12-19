@extends('global.layout')

@section('maincontent')
    <div class="mt-4 d-flex flex-wrap gap-4 mt-20">
        <!-- Left Side: Search Course and Search Trainer -->
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
        </div>
        <!-- Right Side: Calendar -->
        <div class="card shadow-sm" style="flex: 2;">
            <div class="card-header d-flex justify-content-end align-items-center">
                <!--begin::Add Button-->
                <button type="button" class="btn btn-primary btn-hover-rise dropdown-toggle"
                    data-kt-menu-trigger="click"
                    data-kt-menu-placement="bottom-start">
                    Add
                </button>
                <!--end::Add Button-->

                <!--begin::Links-->
                <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-bold fs-7 w-200px py-4"
                    data-kt-menu="true">
                    
                    <!--begin::Link item-->
                    <div class="menu-item px-3">
                        <a href="/add_training" class="menu-link px-3">
                            <i class="bi bi-pencil-square text-primary fs-6 me-2"></i>Training
                        </a>
                    </div>
                    <!--end::Link item-->

                    <!--begin::Link item-->
                    <div class="menu-item px-3">
                        <a href="#" class="menu-link px-3" id="event_view">
                            <i class="bi bi-calendar-event text-info fs-6 me-2"></i>Unavailability
                        </a>
                    </div>
                    <!--end::Link item-->
                </div>
                <!--end::Links-->
            </div>
            <div class="card" style="height: auto;">
                <div class="card-body">
                    <div id="calendar" class="border border-3 border-gray-200 p-10" style="height: 100%; border-radius: 5px;">
                        <!-- FullCalendar will be rendered here -->
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection
@push('scripts')
    <script src="{{ asset('js/calendar.js') }}"></script>
@endpush
