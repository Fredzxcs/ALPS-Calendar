@extends('global.layout')

@section('maincontent')
    <div class="container mt-20 d-flex justify-content-center align-items-center bg-white shadow-sm rounded-3 p-5">
        <div class="container mt-5">
            <!-- Title -->
            <div class="mb-4">
                <h3 class="fw-boldest fs-1 " style="color: #7c0101; ">MANAGE ACCESS</h3>
            </div>

            <!-- Top Actions -->
            <div class="d-flex justify-content-between align-items-center mb-8">
                <div class="position-relative" style="max-width: 300px;">
                    <!-- Input Field -->
                    <input type="text" class="form-control form-control-solid ps-5 fw-boldest rounded-3"
                        placeholder="&#xF52A; Search..." style="font-family: 'Bootstrap-icons', sans-serif;">
                </div>
                <div class="d-flex align-items-center justify-content-end gap-2">
                    <!-- ADD USER Button -->
                    <a href="{{ url('/add_user') }}" class="btn btn-primary rounded-3 fw-boldest">
                        <i class="bi bi-plus-lg"></i> ADD USER
                    </a>

                    <!-- FILTER Button with Menu -->
                    <div>
                        <!-- Filter Button -->
                        <button class="btn btn-dark rounded-3 fw-boldest d-flex align-items-center"
                            data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                            <i class="bi bi-funnel me-1"></i> FILTER
                        </button>

                        <!-- Filter Menu -->
                        <div class="menu menu-sub menu-sub-dropdown w-250px w-md-300px" data-kt-menu="true">
                            <!-- Menu Header -->
                            <div class="px-7 py-5">
                                <div class="fs-5 text-dark fw-bolder">Filter Options</div>
                            </div>

                            <!-- Separator -->
                            <div class="separator border-gray-200"></div>

                            <!-- Filter Form -->
                            <div class="px-7 py-5">
                                <!-- Status Dropdown -->
                                <div class="mb-10">
                                    <label class="form-label fw-bold">Status:</label>
                                    <select class="form-select form-select-solid" data-placeholder="Select option"
                                        data-allow-clear="true">
                                        <option></option>
                                        <option value="1">System Admin</option>
                                        <option value="2">Coordinator</option>
                                        <option value="3">Facilitator</option>
                                    </select>
                                </div>

                                <!-- Actions -->
                                <div class="d-flex justify-content-end">
                                    <button type="reset" class="btn btn-sm btn-light btn-active-light-primary me-2"
                                        data-kt-menu-dismiss="true">Reset</button>
                                    <button type="submit" class="btn btn-sm btn-primary"
                                        data-kt-menu-dismiss="true">Apply</button>
                                </div>
                            </div>
                        </div>
                        <!-- End Filter Menu -->
                    </div>
                </div>
            </div>


                <!-- Table -->
                <div class="table-responsive">
                    <table class="table table-striped align-middle text-center gy-7 gs-7">
                        <thead>
                            <tr class="bg-active-lighten  fw-boldest text-gray-800">
                                <th>PROFILE</th>
                                <th>EMAIL</th>
                                <th>ROLE</th>
                                <th>ACTIONS</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Row 1 -->
                            <tr>
                                <td class="d-flex align-items-center justify-content-center text-start">
                                    <div class="symbol symbol-50px me-3">
                                        <img src="{{ asset('img/profile.png') }}" alt="Profile Picture">
                                    </div>
                                    <div>
                                        <span class="fw-bold d-block">Kimberly M. Kho</span>
                                        <small class="text-muted">kimberlykho27</small>
                                    </div>
                                </td>
                                <td>kimlykho27@gmail.com</td>
                                <td><span class="badge badge-light-warning">SYSTEM ADMIN</span></td>
                                <td>
                                    <button class="btn btn-light btn-sm dropdown-toggle" type="button"
                                        data-bs-toggle="dropdown">
                                        MENU
                                    </button>
                                </td>
                            </tr>

                            <!-- Row 2 -->
                            <tr>
                                <td class="d-flex align-items-center justify-content-center text-start">
                                    <div class="symbol symbol-50px me-3">
                                        <img src="{{ asset('img/profile.png') }}" alt="Profile Picture">
                                    </div>
                                    <div>
                                        <span class="fw-bold d-block">Kimberly M. Kho</span>
                                        <small class="text-muted">kimberlykho27</small>
                                    </div>
                                </td>
                                <td>kimlykho27@gmail.com</td>
                                <td><span class="badge badge-light-info">COORDINATOR</span></td>
                                <td>
                                    <button class="btn btn-light btn-sm dropdown-toggle" type="button"
                                        data-bs-toggle="dropdown">
                                        MENU
                                    </button>
                                </td>
                            </tr>

                            <!-- Row 3 -->
                            <tr>
                                <td class="d-flex align-items-center justify-content-center text-start">
                                    <div class="symbol symbol-50px me-3">
                                        <img src="{{ asset('img/profile.png') }}" alt="Profile Picture">
                                    </div>
                                    <div>
                                        <span class="fw-bold d-block">Kimberly M. Kho</span>
                                        <small class="text-muted">kimberlykho27</small>
                                    </div>
                                </td>
                                <td>kimlykho27@gmail.com</td>
                                <td><span class="badge badge-light-success">FACILITATOR</span></td>
                                <td>
                                    <button class="btn btn-light btn-sm dropdown-toggle" type="button"
                                        data-bs-toggle="dropdown">
                                        MENU
                                    </button>
                                </td>
                            </tr>

                            <!-- Row 4 -->
                            <tr>
                                <td class="d-flex align-items-center justify-content-center text-start">
                                    <div class="symbol symbol-50px me-3">
                                        <img src="{{ asset('img/profile.png') }}" alt="Profile Picture">
                                    </div>
                                    <div>
                                        <span class="fw-bold d-block">Kimberly M. Kho</span>
                                        <small class="text-muted">kimberlykho27</small>
                                    </div>
                                </td>
                                <td>kimlykho27@gmail.com</td>
                                <td><span class="badge badge-light-purple">ASSISTANT</span></td>
                                <td>
                                    <button class="btn btn-light btn-sm dropdown-toggle" type="button"
                                        data-bs-toggle="dropdown">
                                        MENU
                                    </button>
                                </td>
                            </tr>

                            <!-- Row 5 -->
                            <tr>
                                <td class="d-flex align-items-center justify-content-center text-start">
                                    <div class="symbol symbol-50px me-3">
                                        <img src="{{ asset('img/profile.png') }}" alt="Profile Picture">
                                    </div>
                                    <div>
                                        <span class="fw-bold d-block">Kimberly M. Kho</span>
                                        <small class="text-muted">kimberlykho27</small>
                                    </div>
                                </td>
                                <td>kimlykho27@gmail.com</td>
                                <td><span class="badge badge-light-purple">ASSISTANT</span></td>
                                <td>
                                    <button class="btn btn-light btn-sm dropdown-toggle" type="button"
                                        data-bs-toggle="dropdown">
                                        MENU
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endsection
