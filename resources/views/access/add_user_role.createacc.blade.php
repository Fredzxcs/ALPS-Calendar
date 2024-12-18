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
                        <span class="symbol-label bg-light-primary">
                            <i class="bi bi-check2 text-primary fs-2"></i>
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
                        <span class="symbol-label bg-primary text-light fw-bold fs-4">
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



            <!-- Form Section -->
            {{-- <form action="#" method="POST">
            @csrf --}}
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
                        <input type="color" id="color" class="form-control form-control-solid" value="#F1C40F"
                            style="height: 50px; width: 100px;">
                    </div>
                </div>

                <!-- Buttons -->
                <div class="d-flex align-center justify-content-between p-10">
                    <button type="button" class="btn btn-light-primary px-5">Back</button>
                    <button type="submit" class="btn btn-success px-5">Save</button>
                </div>
            </div>
            {{-- </form> --}}
        </div>
    </div>
</div>
@endsection
