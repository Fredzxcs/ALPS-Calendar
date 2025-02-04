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
                <form class="form mx-auto w-75 px-5" novalidate="novalidate" id="kt_stepper_example_basic_form">
                    @csrf
                    <!--begin::Group-->
                    <div class="mb-5">
                        <!--begin::Step 1-->
                        <div class="flex-column current" data-kt-stepper-element="content">
                            <!--begin::Option-->
                            <input type="radio" class="btn-check" name="radio_buttons_2" value="admin" checked="checked"  id="kt_radio_buttons_2_option_0"/>
                            <label class="btn btn-outline btn-outline-dashed btn-outline-default p-7 d-flex align-items-center mb-5" for="kt_radio_buttons_2_option_0">
                                <!--begin::Svg Icon | path: icons/duotune/coding/cod001.svg-->
                                <span class="svg-icon svg-icon-4x me-4">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-person-fill-gear" viewBox="0 0 16 16">
                                        <path d="M11 5a3 3 0 1 1-6 0 3 3 0 0 1 6 0m-9 8c0 1 1 1 1 1h5.256A4.5 4.5 0 0 1 8 12.5a4.5 4.5 0 0 1 1.544-3.393Q8.844 9.002 8 9c-5 0-6 3-6 4m9.886-3.54c.18-.613 1.048-.613 1.229 0l.043.148a.64.64 0 0 0 .921.382l.136-.074c.561-.306 1.175.308.87.869l-.075.136a.64.64 0 0 0 .382.92l.149.045c.612.18.612 1.048 0 1.229l-.15.043a.64.64 0 0 0-.38.921l.074.136c.305.561-.309 1.175-.87.87l-.136-.075a.64.64 0 0 0-.92.382l-.045.149c-.18.612-1.048.612-1.229 0l-.043-.15a.64.64 0 0 0-.921-.38l-.136.074c-.561.305-1.175-.309-.87-.87l.075-.136a.64.64 0 0 0-.382-.92l-.148-.045c-.613-.18-.613-1.048 0-1.229l.148-.043a.64.64 0 0 0 .382-.921l-.074-.136c-.306-.561.308-1.175.869-.87l.136.075a.64.64 0 0 0 .92-.382zM14 12.5a1.5 1.5 0 1 0-3 0 1.5 1.5 0 0 0 3 0"/>
                                        </svg>
                                </span>
                                <!--end::Svg Icon-->

                                <span class="d-block fw-bold text-start">
                                    <span class="text-dark fw-bolder d-block fs-3">Admin</span>
                                    <span class="text-muted fw-bold fs-6">Responsible for overall system access.</span>
                                </span>
                            </label>
                            <!--end::Option-->
                            <!--begin::Option-->
                            <input type="radio" class="btn-check" name="radio_buttons_2" value="coordinator"  id="kt_radio_buttons_2_option_1"/>
                            <label class="btn btn-outline btn-outline-dashed btn-outline-default p-7 d-flex align-items-center mb-5" for="kt_radio_buttons_2_option_1">
                                <!--begin::Svg Icon | path: icons/duotune/coding/cod001.svg-->
                                <span class="svg-icon svg-icon-4x me-4">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-briefcase-fill" viewBox="0 0 16 16">
                                        <path d="M6.5 1A1.5 1.5 0 0 0 5 2.5V3H1.5A1.5 1.5 0 0 0 0 4.5v1.384l7.614 2.03a1.5 1.5 0 0 0 .772 0L16 5.884V4.5A1.5 1.5 0 0 0 14.5 3H11v-.5A1.5 1.5 0 0 0 9.5 1zm0 1h3a.5.5 0 0 1 .5.5V3H6v-.5a.5.5 0 0 1 .5-.5"/>
                                        <path d="M0 12.5A1.5 1.5 0 0 0 1.5 14h13a1.5 1.5 0 0 0 1.5-1.5V6.85L8.129 8.947a.5.5 0 0 1-.258 0L0 6.85z"/>
                                    </svg>
                                </span>
                                <!--end::Svg Icon-->

                                <span class="d-block fw-bold text-start">
                                    <span class="text-dark fw-bolder d-block fs-3">Training Coordinator</span>
                                    <span class="text-muted fw-bold fs-6">Handles planning, organizing, and overseeing trainings.</span>
                                </span>
                            </label>
                            <!--end::Option-->

                            <!--begin::Option-->
                            <input type="radio" class="btn-check" name="radio_buttons_2" value="facilitator" id="kt_radio_buttons_2_option_2"/>
                            <label class="btn btn-outline btn-outline-dashed btn-outline-default p-7 d-flex align-items-center" for="kt_radio_buttons_2_option_2">
                                <!--begin::Svg Icon | path: icons/duotune/communication/com003.svg-->
                                <span class="svg-icon svg-icon-4x me-4">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-clipboard-fill" viewBox="0 0 16 16">
                                        <path fill-rule="evenodd" d="M10 1.5a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h3a.5.5 0 0 0 .5-.5zm-5 0A1.5 1.5 0 0 1 6.5 0h3A1.5 1.5 0 0 1 11 1.5v1A1.5 1.5 0 0 1 9.5 4h-3A1.5 1.5 0 0 1 5 2.5zm-2 0h1v1A2.5 2.5 0 0 0 6.5 5h3A2.5 2.5 0 0 0 12 2.5v-1h1a2 2 0 0 1 2 2V14a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V3.5a2 2 0 0 1 2-2"/>
                                    </svg>
                                </span>
                                <!--end::Svg Icon-->

                                <span class="d-block fw-bold text-start">
                                    <span class="text-dark fw-bolder d-block fs-3">Facilitator</span>
                                    <span class="text-muted fw-bold fs-6">Leads discussions or training sessions.</span>
                                </span>
                            </label>
                            <!--end::Option-->

                            <!-- <div class="mt-5">
                                <input type="radio" class="btn-check" name="radio_buttons_2" value="assistant" id="kt_radio_buttons_2_option_3"/>
                                <label class="btn btn-outline btn-outline-dashed btn-outline-default p-7 d-flex align-items-center" for="kt_radio_buttons_2_option_3">
                                    <span class="svg-icon svg-icon-4x me-4">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-display" viewBox="0 0 16 16">
                                            <path d="M0 4s0-2 2-2h12s2 0 2 2v6s0 2-2 2h-4q0 1 .25 1.5H11a.5.5 0 0 1 0 1H5a.5.5 0 0 1 0-1h.75Q6 13 6 12H2s-2 0-2-2zm1.398-.855a.76.76 0 0 0-.254.302A1.5 1.5 0 0 0 1 4.01V10c0 .325.078.502.145.602q.105.156.302.254a1.5 1.5 0 0 0 .538.143L2.01 11H14c.325 0 .502-.078.602-.145a.76.76 0 0 0 .254-.302 1.5 1.5 0 0 0 .143-.538L15 9.99V4c0-.325-.078-.502-.145-.602a.76.76 0 0 0-.302-.254A1.5 1.5 0 0 0 13.99 3H2c-.325 0-.502.078-.602.145"/>
                                        </svg>
                                    </span>

                                    <span class="d-block fw-bold text-start">
                                        <span class="text-dark fw-bolder d-block fs-3">Assistant</span>
                                        <span class="text-muted fw-bold fs-6">Supports the team by handling tasks and ensuring that all operations run efficiently.</span>
                                    </span>
                                </label>
                            </div> -->
                        </div>
                        <!--end::Input Group Step 1-->

                        <!--begin::Input Group Step 2-->
                        <div class="flex-column" data-kt-stepper-element="content">
                            <div class="row g-5">
                                <!-- First Name -->
                                <div class="col-md-6">
                                    <label for="first_name" class="form-label fw-bold">Full Name <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control form-control-solid" id="full_name"
                                        placeholder="Enter Full Name" required>
                                </div>

                                <!-- Middle Name -->
                                <!-- <div class="col-md-6">
                                    <label for="middle_name" class="form-label fw-bold">Middle Name</label>
                                    <input type="text" class="form-control form-control-solid" id="middle_name"
                                        placeholder="Enter Middle Name (Optional)">
                                </div> -->
                                <!-- Last Name -->
                                <!-- <div class="col-md-6">
                                    <label for="last_name" class="form-label fw-bold">Last Name <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control form-control-solid" id="last_name"
                                        placeholder="Enter Last Name" required>
                                </div> -->
                                <!-- Suffix -->
                                <!-- <div class="col-md-6">
                                    <label for="suffix" class="form-label fw-bold">Suffix</label>
                                    <input type="text" class="form-control form-control-solid" id="suffix"
                                        placeholder="Enter Suffix (Optional)">
                                </div> -->

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
                                <div class="col-md-12 text-center mt-10">
                                    <label for="id_picture" class="form-label fw-bold required">1x1 ID Picture</label>
                                    <div>
                                        <!--begin::Image input-->
                                        <div class="image-input image-input-outline" data-kt-image-input="true" style="background-image: url('assets/media/svg/avatars/blank.svg')">
                                            <!--begin::Preview existing avatar-->
                                            <div class="image-input-wrapper w-125px h-125px" style="background-image: url(assets/media/avatars/300-1.jpg)"></div>
                                            <!--end::Preview existing avatar-->
                                            <!--begin::Label-->
                                            <label class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="change" data-bs-toggle="tooltip" title="Select Image">
                                                <i class="bi bi-pencil-fill fs-7"></i>
                                                <!--begin::Inputs-->
                                                <input type="file" name="avatar" accept=".png, .jpg, .jpeg" />
                                                <input type="hidden" name="avatar_remove" />
                                                <!--end::Inputs-->
                                            </label>
                                            <!--end::Label-->
                                            <!--begin::Cancel-->
                                            <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="cancel" data-bs-toggle="tooltip" title="Remove Image">
                                                <i class="bi bi-x fs-2"></i>
                                            </span>
                                            <!--end::Cancel-->
                                        </div>
                                        <!--end::Image input-->
                                        <!--begin::Hint-->
                                        <div class="form-text">Allowed file types: png, jpg, jpeg.</div>
                                        <!--end::Hint-->
                                    </div>
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
                                    <!-- <div class="col-md-6">
                                        <label for="password" class="form-label fw-bold">Assign Password <span
                                                class="text-danger">*</span></label>
                                        <input type="password" id="password" class="form-control form-control-solid"
                                            placeholder="Enter Password" required>
                                    </div> -->

                                    <!--Assign Password-->
                                    <div class="col-md-6">
                                    <label for="password" class="form-label fw-bold required">Assign Password</label>
                                    <div class="position-relative">
                                        <input type="password" class="form-control form-control-solid" 
                                            placeholder="Enter Password" 
                                            id="password"
                                            name="password"
                                        />
                                        <!-- Visibility toggle -->
                                        <span class="btn btn-sm btn-icon position-absolute translate-middle top-50 end-0 me-n2 togglePassword"
                                            data-kt-password-meter-control="visibility" 
                                            data-target="#password" 
                                            aria-label="Toggle Password Visibility">
                                            <i class="bi bi-eye-slash fs-2"></i>
                                            <i class="bi bi-eye fs-2 d-none"></i>
                                        </span>
                                    </div>
                                </div>

                                </div>
                                <!-- Assign Color -->
                                <div class="row mb-4">
                                    <div class="col-md-6">
                                        <div id="assignColorSection" class="mt-5">
                                            <label for="color" class="form-label fw-bold required">Assign Color</label>
                                            <input type="color" id="color" class="form-control form-control-solid w-100px h-50px" />
                                        </div>
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
                                    BACK
                                </button>
                            </div>
                            <!--end::Wrapper-->

                            <!--begin::Wrapper-->
                            <div>
                                <button type="button" class="btn btn-success" id="add_user_submit" data-kt-stepper-action="submit">
                                    <span class="indicator-label">
                                        SAVE
                                    </span>
                                    <span class="indicator-progress">
                                        Please wait... <span
                                            class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                    </span>
                                </button>

                                <button type="button" class="btn btn-primary" data-kt-stepper-action="next">
                                    CONTINUE
                                </button>
                            </div>
                            <!--end::Wrapper-->
                        </div>
                        <!--end::Actions-->
                    </div>
                </form>
                <!--end::Form-->
            </div>
            <!--end::Stepper-->
        </div>
    </div>
</div>
@endsection
@push('scripts')
    <script src="{{ asset('js/add_user.js') }}"></script>
    <!-- <script>
        const roleRadios = document.querySelectorAll('input[name="radio_buttons_2"]');
        const assignColorSection = document.getElementById('assignColorSection');

        assignColorSection.style.display = 'none';

        roleRadios.forEach(radio => {
            radio.addEventListener('change', function() {
                if (this.value === 'facilitator' && this.id === 'kt_radio_buttons_2_option_2') {
                    // If the role is 'Facilitator' show the 'Assign Color' section
                    assignColorSection.style.display = 'block';
                } else {
                    // Otherwise hide the 'Assign Color' section
                    assignColorSection.style.display = 'none';
                }
            });
        });
    </script> -->
@endpush
