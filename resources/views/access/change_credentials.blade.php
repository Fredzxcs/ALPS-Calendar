@extends('global.layout')
@section('maincontent')
<div class="d-flex justify-content-center align-items-center">
    <div class="container mt-5">
        <div class="card shadow-sm  pt-0 pb-1 mt-20">
            <!-- Title -->
            <div class="d-flex justify-content-center align-items-center mb-4 rounded-top bg-primary"
                style="height: 80px;">
                <h2 class="text-white fw-boldest m-0 fs-1">CHANGE CREDENTIALS</h2>
            </div>
            <!--begin::Form-->
            <form class="form mx-auto w-75 px-5" novalidate="novalidate" id="change_credentials_form">
                @csrf
                <!--begin::Group-->
                <div class="mb-5 my-10">
                    <!--begin::Input Group-->
                    <div class="flex-column">
                        <div class="d flex-center px-16">
                            <div class="row mb-4">
                                <!-- Assign Username -->
                                <div class="col-md-6">
                                    <label for="username" class="form-label fw-bold required">Assign Username</label>
                                    <input type="text" id="username" class="form-control form-control-solid"
                                        placeholder="Enter Username" required>
                                </div>
                                <!-- Assign Password -->
                                <!-- <div class="col-md-6">
                                    <label for="password" class="form-label fw-bold required">Assign Password</label>
                                    <input type="password" id="password" class="form-control form-control-solid"
                                        placeholder="Enter Password" required>
                                    <div class="invalid-feedback">Required field</div>
                                </div> -->

                                <!-- Assign Password -->
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
                    <!--end::Input-->

                    <!-- Buttons -->
                    <div class="d-flex justify-content-center gap-5 mt-5">
                        <a href="{{ route('manage_access') }}">
                            <button type="button" class="btn btn-light fw-boldest">CANCEL</button>
                        </a>
                        <button type="submit" id="change_credentials_submit" class="btn btn-success fw-boldest">SAVE</button>
                    </div>
                </div>
            </form>
            <!--end::Form-->
        </div>
    </div>
</div>
@endsection
@push('scripts')

<script src="{{ asset('js/manage_access.js') }}"></script>
<script src="{{ asset('js/change_credentials.js') }}"></script>
@endpush