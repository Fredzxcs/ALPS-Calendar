@extends('global.layout')

@section('maincontent')
<div class="d-flex justify-content-center align-items-center" >
    <div class="container mt-5">
        <div class="card shadow-sm  pt-0 pb-1 mt-20 ">
            <!-- Title -->
            <div class="d-flex justify-content-center align-items-center mb-4 rounded-top"
                style="background-color: #007bff; height: 80px;">
                <h2 class="text-white fw-boldest m-0 fs-1">ADD USER</h2>
            </div>

<!-- Stepper Wrapper -->
<div class="stepper stepper-pills stepper-column d-flex flex-column flex-xl-row flex-row-fluid" id="kt_modal_create_app_stepper">
    <!-- Stepper Navigation -->
    <div class="d-flex justify-content-center justify-content-xl-start flex-row-auto w-100 w-xl-300px">
        <div class="stepper-nav ps-lg-10">
            <!-- Step 1 -->
            <div class="stepper-item current" data-kt-stepper-element="nav">
                <div class="stepper-line w-40px"></div>
                <div class="stepper-icon w-40px h-40px">
                    <i class="stepper-check fas fa-check"></i>
                    <span class="stepper-number">1</span>
                </div>
                <div class="stepper-label">
                    <h3 class="stepper-title">Step 1</h3>
                    <div class="stepper-desc">Assign Role</div>
                </div>
            </div>
            <!-- Step 2 -->
            <div class="stepper-item" data-kt-stepper-element="nav">
                <div class="stepper-line w-40px"></div>
                <div class="stepper-icon w-40px h-40px">
                    <i class="stepper-check fas fa-check"></i>
                    <span class="stepper-number">2</span>
                </div>
                <div class="stepper-label">
                    <h3 class="stepper-title">Step 2</h3>
                    <div class="stepper-desc">Information</div>
                </div>
            </div>
            <!-- Step 3 -->
            <div class="stepper-item" data-kt-stepper-element="nav">
                <div class="stepper-line w-40px"></div>
                <div class="stepper-icon w-40px h-40px">
                    <i class="stepper-check fas fa-check"></i>
                    <span class="stepper-number">3</span>
                </div>
                <div class="stepper-label">
                    <h3 class="stepper-title">Step 3</h3>
                    <div class="stepper-desc">Account Creation</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Stepper Content -->
    <div class="d-flex flex-row-fluid justify-content-center px-10">
        <div class="stepper-content">
            <!-- Step 1 Content -->
            <div class="stepper-item current" data-kt-stepper-element="content">
                <h4 class="fw-bold mb-5">Step 1: Assign Role</h4>
                <!-- Include Step 1 Code Here -->
                <div class="row justify-content-center align-center">
                    <div class="col-md-10">
                        <label for="role" class="fw-bold mb-2">Select Role <span class="text-danger">*</span></label>
                        <div class="role-list">
                            <!-- Your Step 1 Code -->
                        </div>
                    </div>
                </div>
            </div>

            <!-- Step 2 Content -->
            <div class="stepper-item" data-kt-stepper-element="content">
                <h4 class="fw-bold mb-5">Step 2: Information</h4>
                <!-- Include Step 2 Code Here -->
                <div class="container mt-5">
                    <div class="row g-3">
                        <!-- Your Step 2 Code -->
                    </div>
                </div>
            </div>

            <!-- Step 3 Content -->
            <div class="stepper-item" data-kt-stepper-element="content">
                <h4 class="fw-bold mb-5">Step 3: Account Creation</h4>
                <!-- Include Step 3 Code Here -->
                <div class="d flex-center px-16">
                    <div class="row mb-4">
                        <!-- Your Step 3 Code -->
                    </div>
                </div>
            </div>

            <!-- Navigation Buttons -->
            <div class="d-flex justify-content-between pt-10">
                <button type="button" class="btn btn-light-primary" data-kt-stepper-action="previous">Back</button>
                <button type="button" class="btn btn-primary" data-kt-stepper-action="next">Next</button>
            </div>
        </div>
    </div>
</div>


</div>
@endsection
