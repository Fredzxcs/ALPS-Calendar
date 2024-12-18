@extends('global.layout')
@section('maincontent')
    <div class="d-flex justify-content-center align-items-center">
        <div class="container mt-5">
            <div class="card shadow-sm  pt-0 pb-1 mt-20">
                <!-- Title -->
                <div class="d-flex justify-content-center align-items-center mb-4 rounded-top bg-primary"
                    style="height: 80px;">
                    <h2 class="text-white fw-boldest m-0 fs-1">ADD USER</h2>
                </div>
                <!--begin::Stepper-->
                <div class="stepper stepper-pills" id="kt_stepper_example_basic">
                    <!--begin::Nav-->
                    <div class="stepper-nav flex-center flex-wrap mb-10">
                        <!--begin::Step 1-->
                        <div class="stepper-item mx-8 my-4 current" data-kt-stepper-element="nav">
                            <!--begin::Wrapper-->
                            <div class="stepper-wrapper d-flex align-items-center">
                                <!--begin::Icon-->
                                <div class="stepper-icon w-40px h-40px">
                                    <i class="stepper-check fas fa-check"></i>
                                    <span class="stepper-number">1</span>
                                </div>
                                <!--end::Icon-->

                                <!--begin::Label-->
                                <div class="stepper-label">
                                    <h3 class="stepper-title">
                                        Step 1
                                    </h3>

                                    <div class="stepper-desc">
                                        Assign Role
                                    </div>
                                </div>
                                <!--end::Label-->
                            </div>
                            <!--end::Wrapper-->

                            <!--begin::Line-->
                            <div class="stepper-line h-40px"></div>
                            <!--end::Line-->
                        </div>
                        <!--end::Step 1-->

                        <!--begin::Step 2-->
                        <div class="stepper-item mx-8 my-4" data-kt-stepper-element="nav">
                            <!--begin::Wrapper-->
                            <div class="stepper-wrapper d-flex align-items-center">
                                <!--begin::Icon-->
                                <div class="stepper-icon w-40px h-40px">
                                    <i class="stepper-check fas fa-check"></i>
                                    <span class="stepper-number">2</span>
                                </div>
                                <!--begin::Icon-->

                                <!--begin::Label-->
                                <div class="stepper-label">
                                    <h3 class="stepper-title">
                                        Step 2
                                    </h3>

                                    <div class="stepper-desc">
                                        Information
                                    </div>
                                </div>
                                <!--end::Label-->
                            </div>
                            <!--end::Wrapper-->

                            <!--begin::Line-->
                            <div class="stepper-line h-40px"></div>
                            <!--end::Line-->
                        </div>
                        <!--end::Step 2-->

                        <!--begin::Step 3-->
                        <div class="stepper-item mx-8 my-4" data-kt-stepper-element="nav">
                            <!--begin::Wrapper-->
                            <div class="stepper-wrapper d-flex align-items-center">
                                <!--begin::Icon-->
                                <div class="stepper-icon w-40px h-40px">
                                    <i class="stepper-check fas fa-check"></i>
                                    <span class="stepper-number">3</span>
                                </div>
                                <!--begin::Icon-->

                                <!--begin::Label-->
                                <div class="stepper-label">
                                    <h3 class="stepper-title">
                                        Step 3
                                    </h3>

                                    <div class="stepper-desc">
                                        Account Creation
                                    </div>
                                </div>
                                <!--end::Label-->
                            </div>
                            <!--end::Wrapper-->

                            <!--begin::Line-->
                            <div class="stepper-line h-40px"></div>
                            <!--end::Line-->
                        </div>
                        <!--end::Step 3-->
                    </div>
                    <!--end::Nav-->

                    <!--begin::Form-->
                    <form class="form w-lg-500px mx-auto" novalidate="novalidate" id="kt_stepper_example_basic_form">
                        <!--begin::Group-->
                        <div class="mb-5">
                            <!--begin::Step 1-->
                            <div class="flex-column current" data-kt-stepper-element="content">
                                <!--begin::Input group-->
                                <div class="fv-row mb-10">
                                    <!--begin::Label-->
                                    <div class="row justify-content-center align-center">
                                        <div class="col-md-10">
                                            <label for="role" class="fw-bold mb-2">Select Role <span
                                                    class="text-danger">*</span></label>
                                            <!-- Training Coordinator -->
                                            <div class="role-list">
                                                <div class="card border border-dashed border-primary shadow-sm p-3 mb-3"
                                                    style="background-color: #f3faff;">
                                                    <div class="d-flex align-items-center">
                                                        <div class="symbol symbol-50px me-4">
                                                            <span class="symbol-label bg-light-primary">
                                                                <i class="bi bi-briefcase-fill fs-2x text-primary"></i>
                                                            </span>
                                                        </div>
                                                        <div>
                                                            <label for="coordinator" class="fw-bold m-0 text-dark">Training
                                                                Coordinator</label>
                                                            <p class="text-gray-500 mb-0">Description</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- Training Facilitator -->
                                            <div class="role-list">
                                                <div class="card border border-dashed border-gray-500 shadow-sm p-3 mb-3 bg-light"
                                                    style="background-color: #f3faff;">
                                                    <div class="d-flex align-items-center">
                                                        <div class="symbol symbol-50px me-4">
                                                            <span class="symbol-label bg-gray-100">
                                                                <i class="bi bi-person-fill fs-2x text-gray-500"></i>
                                                            </span>
                                                        </div>
                                                        <div>
                                                            <label for="coordinator" class="fw-bold m-0 text-dark">Training
                                                                Facilitator</label>
                                                            <p class="text-gray-500 mb-0">Description</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- Training Assistant -->
                                            <div class="card border border-dashed border-gray-500 shadow-sm p-3 mb-3 bg-light"
                                                style="background-color: #f3faff;">
                                                <div class="d-flex align-items-center">
                                                    <div class="symbol symbol-50px me-4">
                                                        <span class="symbol-label bg-gray-100">
                                                            <i class="bi bi-laptop-fill fs-2x text-gray-500"></i>
                                                        </span>
                                                    </div>
                                                    <div>
                                                        <label for="facilitator" class="fw-bold m-0 text-dark">Training
                                                            Assistant</label>
                                                        <p class="text-gray-500 mb-0">Description</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!--end::Input Group Step 1-->

                            <!--begin::Input Group Step 2-->
                            <div class="flex-column" data-kt-stepper-element="content">
                                <div class="row g-3">
                                    <!-- First Name -->
                                    <div class="col-md-6">
                                        <label for="first_name" class="form-label fw-bold">First Name <span
                                                class="text-danger">*</span></label>
                                        <input type="text" class="form-control form-control-solid" id="first_name"
                                            placeholder="Enter First Name" required>
                                    </div>
                                    <!-- Middle Name -->
                                    <div class="col-md-6">
                                        <label for="middle_name" class="form-label fw-bold">Middle Name</label>
                                        <input type="text" class="form-control form-control-solid" id="middle_name"
                                            placeholder="Enter Middle Name (Optional)">
                                    </div>
                                    <!-- Last Name -->
                                    <div class="col-md-6">
                                        <label for="last_name" class="form-label fw-bold">Last Name <span
                                                class="text-danger">*</span></label>
                                        <input type="text" class="form-control form-control-solid" id="last_name"
                                            placeholder="Enter Last Name" required>
                                    </div>
                                    <!-- Suffix -->
                                    <div class="col-md-6">
                                        <label for="suffix" class="form-label fw-bold">Suffix</label>
                                        <input type="text" class="form-control form-control-solid" id="suffix"
                                            placeholder="Enter Suffix (Optional)">
                                    </div>
                                    <!-- Email Address -->
                                    <div class="col-md-6">
                                        <label for="email" class="form-label fw-bold">Email Address <span
                                                class="text-danger">*</span></label>
                                        <input type="email" class="form-control form-control-solid" id="email"
                                            placeholder="Enter Email Address" required>
                                    </div>
                                    <!-- Contact Number -->
                                    <div class="col-md-6">
                                        <label for="contact_number" class="form-label fw-bold">Contact Number <span
                                                class="text-danger">*</span></label>
                                        <input type="text" class="form-control form-control-solid" id="contact_number"
                                            placeholder="Enter Contact Number" required>
                                    </div>
                                    <!-- 1x1 ID Picture -->
                                    <div class="col-md-12">
                                        <label for="id_picture" class="form-label fw-bold">1x1 ID Picture <span
                                                class="text-danger">*</span></label>
                                        <input type="file" class="form-control form-control-solid" id="id_picture"
                                            required>
                                    </div>
                                </div>
                            </div>
                            <!--end::Input Group Step 2-->

                            <!--begin::Input Group Step 3-->
                            <div class="flex-column" data-kt-stepper-element="content">
                                <div class="d flex-center px-16">
                                    <div class="row mb-4">
                                        <!-- Assign Username -->
                                        <div class="col-md-6">
                                            <label for="username" class="form-label fw-bold">Assign Username <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" id="username" class="form-control form-control-solid"
                                                placeholder="Enter Username" required>
                                        </div>
                                        <!-- Assign Password -->
                                        <div class="col-md-6">
                                            <label for="password" class="form-label fw-bold">Assign Password <span
                                                    class="text-danger">*</span></label>
                                            <input type="password" id="password" class="form-control form-control-solid"
                                                placeholder="Enter Password" required>
                                        </div>
                                    </div>
                                    <!-- Assign Color -->
                                    <div class="row mb-4">
                                        <div class="col-md-6">
                                            <label for="color" class="form-label fw-bold">Assign Color <span
                                                    class="text-danger">*</span></label>
                                            <input type="color" id="color" class="form-control form-control-solid"
                                                value="#F1C40F" style="height: 50px; width: 100px;">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!--end::Input Group Step 3-->

                            <!--begin::Actions/Buttons-->
                            <div class="d-flex flex-stack mt-5 ">
                                <!--begin::Wrapper-->
                                <div class="me-2">
                                    <button type="button" class="btn btn-light btn-active-light-primary"
                                        data-kt-stepper-action="previous">
                                        Back
                                    </button>
                                </div>
                                <!--end::Wrapper-->

                                <!--begin::Wrapper-->
                                <div>
                                    <button type="button" class="btn btn-success" data-kt-stepper-action="submit">
                                        <span class="indicator-label">
                                            Save
                                        </span>
                                        <span class="indicator-progress">
                                            Please wait... <span
                                                class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                        </span>
                                    </button>

                                    <button type="button" class="btn btn-primary" data-kt-stepper-action="next">
                                        Continue
                                    </button>
                                </div>
                                <!--end::Wrapper-->
                            </div>
                            <!--end::Actions-->
                    </form>
                    <!--end::Form-->
                </div>
                <!--end::Stepper-->


            </div>
        @endsection
        @push('scripts')
            <script src="{{ asset('js/add_user.js') }}"></script>
        @endpush
