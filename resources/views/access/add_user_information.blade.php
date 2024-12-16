@extends('layout.layout')

@section('maincontent')
<div class="d-flex justify-content-center align-items-center" >
    <div class="container mt-5">
        <div class="card shadow-sm  pt-0 pb-1 mt-20">
            <!-- Title -->
            <div class="d-flex justify-content-center align-items-center mb-4 rounded-top"
                style="background-color: #007bff; height: 80px;">
                <h2 class="text-white fw-boldest m-0 fs-1">ADD USER</h2>
            </div>

            <div class="d-flex justify-content-around align-items-center mb-5 p-5">
                <div class="d-flex align-items-center">
                    <!-- Left Side: Number Icon -->
                    <div class="symbol symbol-50px me-3">
                        <span class="symbol-label bg-light-primary">
                            <i class="bi bi-check2 text-primary fs-2"></i>
                        </span>
                    </div>
                    <!-- Right Side: Text -->
                    <div>
                        <p class="fw-boldest m-0 text-dark">Step 1</p>
                        <small class="text-gray-500">Assign Role</small>
                    </div>
                </div>



                <!-- Step 2 -->
                <div class="d-flex align-items-center">
                    <!-- Left Side: Number Icon -->
                    <div class="symbol symbol-50px me-3">
                        <span class="symbol-label bg-primary text-light fw-bold fs-4">
                            2
                        </span>
                    </div>
                    <!-- Right Side: Text -->
                    <div>
                        <p class="fw-boldest m-0 text-dark">Step 2</p>
                        <small class="text-gray-500">Information</small>
                    </div>
                </div>


                <!-- Step 3 -->
                <div class="d-flex align-items-center">
                    <!-- Left Side: Number Icon -->
                    <div class="symbol symbol-50px me-3">
                        <span class="symbol-label bg-light-primary text-primary fw-bold fs-4">
                            3
                        </span>
                    </div>
                    <!-- Right Side: Text -->
                    <div>
                        <p class="fw-boldest m-0 text-dark">Step 3</p>
                        <small class="text-gray-500">Account Creation</small>
                    </div>
                </div>

            </div>


            <!-- Information -->

            <div class="container mt-5">
                <!-- Form Section -->
                {{-- <form action="#" method="POST" enctype="multipart/form-data"> --}}
                {{-- @csrf --}}
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
                        <input type="file" class="form-control form-control-solid" id="id_picture" required>
                    </div>
                </div>

                <!-- Buttons -->
                <div class="d-flex justify-content-between mt-4 me-0 mb-6">
                    <button type="button" class="btn btn-light-primary px-5">Back</button>
                    <button type="submit" class="btn btn-primary px-5">Continue</button>
                </div>
                {{-- </form> --}}
            </div>
        </div>
    </div>
</div>

@endsection
