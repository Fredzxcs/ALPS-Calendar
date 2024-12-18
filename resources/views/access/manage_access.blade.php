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
                <div>
                    <button class="btn btn-primary me-2 rounded-3 fw-boldest  ">
                        <i class="bi bi-plus-lg "></i> ADD USER
                    </button>
                    <button class="btn btn-dark rounded-3 fw-boldest">
                        <i class="bi bi-funnel"></i> FILTER
                    </button>
                </div>
            </div>

            <!-- Table -->
            <div class="table-responsive">
                <table class="table table-striped align-middle text-center">
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
                            <td class="d-flex align-items-center justify-content-center text-center">
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
                            <td class="d-flex align-items-center justify-content-center text-center">
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
                            <td class="d-flex align-items-center justify-content-center text-center">
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
                            <td class="d-flex align-items-center justify-content-center text-center">
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
                            <td class="d-flex align-items-center justify-content-center text-center">
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
