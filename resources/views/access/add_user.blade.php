@extends('global.layout')
@section('maincontent')
<div class="d-flex justify-content-center align-items-center" >
    <div class="container mt-5">
        <div class="card shadow-sm  pt-0 pb-1 mt-20">
            <!-- Title -->
            <div class="d-flex justify-content-center align-items-center mb-4 rounded-top bg-primary"
                style="height: 80px;">
                <h2 class="text-white fw-boldest m-0 fs-1">ADD USER</h2>
            </div>

            <div class="d-flex justify-content-around align-items-center mb-5 p-5">
                <div class="d-flex align-items-center">
                    <!-- Left Side: Number Icon -->
                    <div class="symbol symbol-50px me-3">
                        <span class="symbol-label bg-primary text-white fw-boldest fs-4">
                            1
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
                        <span class="symbol-label bg-light-primary text-primary fw-boldest fs-4">
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
                        <span class="symbol-label bg-light-primary text-primary fw-boldest fs-4">
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


            <!-- Role Selection -->
            {{-- <form action="{{ route('access.add_user.submit') }}" method="POST">
            @csrf --}}
            <div class="row justify-content-center align-center">
                <div class="col-md-10">
                    <label for="role" class="fw-bold mb-2">Select Role <span class="text-danger">*</span></label>
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
                                    <label for="coordinator" class="fw-bold m-0 text-dark">Training Coordinator</label>
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
                                    <label for="coordinator" class="fw-bold m-0 text-dark">Training Facilitator</label>
                                    <p class="text-gray-500 mb-0">Description</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Training Assistant -->
                    <div class="card border border-dashed border-gray-500 shadow-sm p-3 mb-3"
                        style="background-color: #f3faff;">
                        <div class="d-flex align-items-center">
                            <div class="symbol symbol-50px me-4">
                                <span class="symbol-label bg-gray-100">
                                    <i class="bi bi-laptop-fill fs-2x text-gray-500"></i>
                                </span>
                            </div>
                            <div>
                                <label for="facilitator" class="fw-bold m-0 text-dark">Training Assistant</label>
                                <p class="text-gray-500 mb-0">Description</p>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Submit Button -->
                <div class="d-flex justify-content-end ml-3 me-20 mb-6">
                    <button type="submit" class="btn btn-primary px-5 ">Continue</button>
                </div>
                {{-- </form> --}}
            </div>
        </div>
</div>
    @endsection
