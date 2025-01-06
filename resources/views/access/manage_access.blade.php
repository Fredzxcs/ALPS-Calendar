@extends('global.layout')

@section('maincontent')
<div class="container mt-20 d-flex justify-content-center align-items-center bg-white shadow-sm rounded-3 p-5">
    <div class="container mt-5">
        <!-- Title -->
        <div class="mb-4">
            <h3 class="card-header fw-boldest fs-1 " style="color: #7c0101; ">MANAGE ACCESS</h3>
        </div>

        <!-- Top Actions -->
        <div class="d-flex justify-content-between align-items-center mb-8">
            <div class="position-relative" style="max-width: 300px;">
                <!-- Input Field -->
                <input type="text" class="form-control form-control-solid ps-5 fw-boldest rounded-3 w-300px"
                    placeholder="&#xF52A; Search..." style="font-family: 'Bootstrap-icons', sans-serif;">
            </div>
            <div class="d-flex align-items-center justify-content-end gap-2">
                <!-- ADD USER Button -->
                 <a href="{{ route('add_user') }}" class="btn btn-primary rounded-3 fw-boldest btn-hover-rise">
                    <span class="svg-icon svg-icon-1">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                            <rect opacity="0.5" x="11.364" y="20.364" width="16" height="2" rx="1" transform="rotate(-90 11.364 20.364)" fill="black" />
                            <rect x="4.36396" y="11.364" width="16" height="2" rx="1" fill="black" />
                        </svg>
                    </span> 
                    ADD USER
                </a>
                
                <!-- FILTER Button with Menu -->
                <div>
                    <!-- Filter Button -->
                    <button class="btn rounded-3 fw-boldest d-flex align-items-center btn-hover-rise text-white"
                        style="background-color: #052a43;"
                        data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                        <i class="bi bi-funnel me-1 text-white"></i> FILTER
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
                            <!-- Role Dropdown -->
                            <div class="mb-10">
                                <label class="form-label fw-bold">Role:</label>
                                <select class="form-select form-select-solid" data-placeholder="Select option"
                                    data-allow-clear="true">
                                    <option>Show All</option>
                                    <option value="1">System Admin</option>
                                    <option value="2">Coordinator</option>
                                    <option value="3">Facilitator</option>
                                    <option value="4">Assistant</option>
                                </select>
                            </div>

                            <!-- Status Dropdown -->
                            <div class="mb-10">
                                <label class="form-label fw-bold">Status:</label>
                                <select class="form-select form-select-solid" data-placeholder="Select option"
                                    data-allow-clear="true">
                                    <option>Show All</option>
                                    <option value="1">Active</option>
                                    <option value="2">Inactive</option>
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
        <div class="table-responsive" style="padding: 0; margin: 0;">
            <table class="table table-striped align-middle text-center gy-7 gs-7 w-100" style="margin: auto;">
                <thead>
                    <tr class="fw-boldest text-gray-800 fs-5">
                        <th class="w-150px">PROFILE</th>
                        <th class="w-100px">EMAIL</th>
                        <th class="w-100px">ROLE</th>
                        <th class="w-100px">COLOR</th>
                        <th class="w-100px">STATUS</th>
                        <th class="w-100px">ACTIONS</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Row -->

                    @foreach ($users as $user)

                        <tr>
                            <td class="d-flex align-items-center text-start">
                                <div class="symbol symbol-50px me-3">
                                    @isset($user->image)
                                        <img src="{{ asset('storage/' . $user->image) }}" alt="Profile Picture">
                                    @else
                                        <img src="{{ asset('img/avatar.jpg') }}" alt="default-image">
                                    @endisset
                                </div>
                                <div class="fs-5">
                                    <span class="fw-bold d-block">{{ $user->name }}</span>
                                    <small class="text-muted">{{ $user->username }}</small>
                                </div>
                            </td>
                            <td>{{ $user->email }}</td>
                            <td>

                                @switch($user->usertype)
                                    @case("admin")
                                        <span class="badge badge-light-warning">SYSTEM ADMIN</span>
                                        @break
                                    @case("coordinator")
                                        <span class="badge badge-light-primary">COORDINATOR</span>
                                        @break
                                    @case("facilitator")
                                        <span class="badge badge-light-info">FACILITATOR</span>
                                        @break
                                    @case("assistant")
                                        <span class="badge badge-light-success">ASSISTANT</span>
                                        @break
                                    @default
                                        <span class="badge badge-light-secondary">-</span>
                                @endswitch

                            </td>
                            <td>
                                <div class="d-flex justify-content-center">
                                    <!--if not facilitator, put N/A (tama ba? -kim)-->
                                    @if ($user->usertype === 'facilitator')
                                        <div class=" w-80px h-30px border border-2 border-dark" style="background-color: pink;"></div>
                                    @else
                                        N/A
                                    @endif
                                </div>
                            </td>
                            <td><span class="badge badge-success">ACTIVE</span></td>
                            <td>
                                <!--begin::Menu-->
                                <button type="button" class="btn btn-secondary btn-sm dropdown-toggle" 
                                    data-kt-menu-trigger="click"
                                    data-kt-menu-placement="bottom-start">
                                    MENU
                                </button>
                                <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-bold fs-7 w-200px py-4" 
                                    data-kt-menu="true">

                                    <div class="menu-item px-3">
                                        <a class="menu-link px-3" data-bs-toggle="modal" data-bs-target="#modal_view_user">
                                            <i class="bi bi-pencil-square text-primary me-2"></i> View & Edit Details
                                        </a>
                                    </div>

                                    @if ($user->status === 'active')
                                        <div class="menu-item px-3">
                                            <a class="menu-link px-3 deactBtn">
                                                <i class="bi bi-x-circle text-danger me-2"></i> Deactivate
                                            </a>
                                        </div>
                                    @else
                                        <div class="menu-item px-3">
                                            <a class="menu-link px-3 reactBtn">
                                                <i class="bi bi-check-circle text-success me-2"></i> Reactivate
                                            </a>
                                        </div>
                                    @endif

                                    <div class="menu-item px-3">
                                        <a class="menu-link px-3 archiveBtn">
                                            <i class="bi bi-archive text-warning me-2"></i> Archive
                                        </a>
                                    </div>
                                </div>
                                <!--end::Menu-->
                            </td>
                        </tr>

                    @endforeach

                    {{-- End Row --}}
                </tbody>
            </table>
        </div>
    </div>
    <!--begin::Modals-->
    <!--begin::Modal - View User-->
    <div class="modal fade" id="modal_view_user" tabindex="-1" aria-hidden="true">
        <!--begin::Modal dialog-->
        <div class="modal-dialog modal-dialog-centered mw-650px">
            <!--begin::Modal content-->
            <div class="modal-content">
                <!--begin::Form-->
                <form class="form" action="#" id="modal_view_user_form">
                    <input class="event-id" type="hidden">
                    <!--begin::Modal header-->
                    <div class="modal-header">
                        <h2 class="fw-boldest" data-kt-calendar="title" style="color: #7c0101;">VIEW USER</h2>
                        <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-toggle="tooltip" title="Close" data-bs-dismiss="modal">
                            <span class="svg-icon svg-icon-1">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                    <rect opacity="0.5" x="6" y="17.3137" width="16" height="2" rx="1" transform="rotate(-45 6 17.3137)" fill="black" />
                                    <rect x="7.41422" y="6" width="16" height="2" rx="1" transform="rotate(45 7.41422 6)" fill="black" />
                                </svg>
                            </span>
                        </div>
                    </div>
                    <!--end::Modal header-->
                    <!--begin::Modal body-->
                    <div class="modal-body py-10 px-lg-17">

                        <!--begin::Role-->
                        <div class="row mb-5 justify-content-between align-items-center">
                            <div class="col-5">
                                <div class="fv-row">
                                    <label class="fs-6 fw-bold mb-2">
                                        <i class="bi bi-gear-wide-connected fs-3 me-5" style="color: #7c0101;"></i>Role
                                    </label>
                                </div>
                            </div>
                            <div class="col-7">
                                <div class="fv-row d-flex justify-content-end align-items-center">
                                    <p class="lead fs-6 mb-0" id="role">
                                        System Admin
                                    </p>
                                </div>
                            </div>
                        </div>                            
                        <!--end::Role-->

                        <!--begin::Full Name-->
                        <div class="row mb-5 justify-content-between align-items-center">
                            <div class="col-5">
                                <div class="fv-row">
                                    <label class="fs-6 fw-bold mb-2">
                                        <i class="bi bi-person-fill fs-3 me-5" style="color: #7c0101;"></i>Full Name
                                    </label>
                                </div>
                            </div>
                            <div class="col-7">
                                <div class="fv-row d-flex justify-content-end align-items-center">
                                    <p class="lead fs-6 mb-0" id="fullname"> 
                                        Kimberly Mae Maglaque Kho II
                                    </p>
                                </div>
                            </div>
                        </div>                            
                        <!--end::Full Name-->
                        
                        <!--begin::Email Address-->
                        <div class="row mb-5 justify-content-between align-items-center">
                            <div class="col-5">
                                <div class="fv-row">
                                    <label class="fs-6 fw-bold mb-2">
                                        <i class="bi bi-envelope-fill fs-3 me-5" style="color: #7c0101;"></i>Email Address
                                    </label>
                                </div>
                            </div>
                            <div class="col-7">
                                <div class="fv-row d-flex justify-content-end align-items-center"> 
                                    <p class="lead fs-6 mb-0" id="email"> 
                                        kimlykho27@gmail.com
                                    </p>
                                </div>
                            </div>
                        </div>                            
                        <!--end::Email Address-->

                        <!--begin::Contact Number-->
                        <div class="row mb-5 justify-content-between align-items-center">
                            <div class="col-5">
                                <div class="fv-row">
                                    <label class="fs-6 fw-bold mb-2">
                                        <i class="bi bi-telephone-fill fs-3 me-5" style="color: #7c0101;"></i>Contact Number
                                    </label>
                                </div>
                            </div>
                            <div class="col-7">
                                <div class="fv-row d-flex justify-content-end align-items-center"> 
                                    <p class="lead fs-6 mb-0" id="num"> 
                                        09205119555
                                    </p>
                                </div>
                            </div>
                        </div>                            
                        <!--end::Contact Number-->

                        <!--begin::1x1 ID-->
                        <div class="form-group row m-b-10 mt-8 mb-8">
                            <label class="col-5 col-form-label fs-6 fw-bold text-md-right">
                                <i class="bi bi-image-fill fs-3 me-5" style="color: #7c0101;"></i>1x1 ID Picture
                            </label>
                            <div class="col-7 fv-row d-flex justify-content-end align-items-center">
                                <!--begin::Image input-->
                                <div class="image-input image-input-outline border border-2" data-kt-image-input="true" style="">
                                    <!--begin::Preview existing avatar-->
                                    <div class="image-input-wrapper w-125px h-125px" id="idpic" style=""></div>
                                    <!--end::Preview existing avatar-->
                                </div>
                                <!--end::Image input-->
                            </div>
                        </div>
                        <!--end::1x1 ID-->

                        <!--begin::Username-->
                        <div class="row mb-5 justify-content-between align-items-center mt-5"> 
                            <div class="col-5">
                                <div class="fv-row">
                                    <label class="fs-6 fw-bold mb-2">
                                        <i class="bi bi-person-video3 fs-3 me-5" style="color: #7c0101;"></i>Assigned Username
                                    </label>
                                </div>
                            </div>
                            <div class="col-7">
                                <div class="fv-row d-flex justify-content-end align-items-center"> 
                                    <p class="lead fs-6 mb-0" id="username"> 
                                        kim.admin
                                    </p>
                                </div>
                            </div>
                        </div>                            
                        <!--end::Username-->

                        <!--begin::Password-->
                        <div class="row mb-5 justify-content-between align-items-center"> 
                            <div class="col-5">
                                <div class="fv-row">
                                    <label class="fs-6 fw-bold mb-2">
                                        <i class="bi bi-shield-lock-fill fs-3 me-5" style="color: #7c0101;"></i>Assigned Password
                                    </label>
                                </div>
                            </div>
                            <div class="col-7">
                                <div class="fv-row d-flex justify-content-end align-items-center"> 
                                    <p class="lead fs-6 mb-0" id="pass"> 
                                        *******
                                    </p>
                                </div>
                            </div>
                        </div>                            
                        <!--end::Password-->

                        <!--begin::Color (IF FACILITATOR)-->
                        <div class="row mb-5 justify-content-between align-items-center mt-5"> 
                            <div class="col-5">
                                <div class="fv-row">
                                    <label class="fs-6 fw-bold mb-2">
                                        <i class="bi bi-palette-fill fs-3 me-5" style="color: #7c0101;"></i>Assigned Color
                                    </label>
                                </div>
                            </div>
                            <div class="col-7">
                                <div class="fv-row d-flex justify-content-end align-items-center"> 
                                    <div class="w-80px h-30px border border-2 border-dark" style="background-color: pink;"></div>
                                </div>
                            </div>
                        </div>                            
                        <!--end::Color-->
                    </div>
                    <!--end::Modal body-->
                    <!--begin::Modal footer-->
                    <div class="modal-footer justify-content-end">
                        <!--begin::Button-->
                        <a href="{{ route('edit_user') }}" class="btn btn-primary me-2">
                            <i class="bi bi-pencil-fill me-2"></i>EDIT
                        </a>
                        <button type="reset" class="btn btn-light" data-bs-dismiss="modal">CLOSE</button>
                        <!--end::Button-->
                    </div>
                    <!--end::Modal footer-->
                </form>
                <!--end::Form-->
            </div>
        </div>
    </div>
    <!--end::Modal - View User-->
    <!--end::Modals-->
</div>
@endsection

@push('scripts')
<script>
    //Reactivate button
    document.querySelectorAll('.reactBtn').forEach(button => {
        button.addEventListener('click', (event) => {
            event.preventDefault(); // Prevent default action (e.g., form submission)

            Swal.fire({
                title: 'Are you sure?',
                text: "You are about to reactivate this user.",
                icon: 'warning',
                buttonsStyling: false,
                showCancelButton: true,
                confirmButtonText: 'Yes, Reactivate',
                cancelButtonText: 'Cancel',
                customClass: {
                confirmButton: "btn btn-success",
                cancelButton: 'btn btn-secondary'
            }
            }).then((result) => {
                if (result.isConfirmed) {
                    // Proceed with the reactivation
                    Swal.fire(
                        'Reactivated!',
                        'The user has been reactivated.',
                        'success'
                    );

                    // TODO: Add logic to perform reactivation
                }
            });
        });
    });

    //Deactivate button
    document.querySelectorAll('.deactBtn').forEach(button => {
        button.addEventListener('click', (event) => {
            event.preventDefault(); // Prevent default action (e.g., form submission)

            Swal.fire({
                title: 'Are you sure?',
                text: "You are about to deactivate this user.",
                icon: 'warning',
                buttonsStyling: false,
                showCancelButton: true,
                confirmButtonText: 'Yes, Deactivate',
                cancelButtonText: 'Cancel',
                customClass: {
                confirmButton: "btn btn-danger",
                cancelButton: 'btn btn-secondary'
            }
            }).then((result) => {
                if (result.isConfirmed) {
                    // Proceed with the deactivation
                    Swal.fire(
                        'Deactivated!',
                        'The user has been deactivated.',
                        'success'
                    );

                    // TODO: Add logic to perform deactivation
                }
            });
        });
    });

    //Archive button
    document.querySelectorAll('.archiveBtn').forEach(button => {
        button.addEventListener('click', (event) => {
            event.preventDefault(); // Prevent default action (e.g., form submission)

            Swal.fire({
                title: 'Are you sure?',
                text: "You are about to archive this user.",
                icon: 'warning',
                buttonsStyling: false,
                showCancelButton: true,
                confirmButtonText: 'Yes, Archive',
                cancelButtonText: 'Cancel',
                customClass: {
                confirmButton: "btn btn-danger",
                cancelButton: 'btn btn-secondary'
            }
            }).then((result) => {
                if (result.isConfirmed) {
                    // Proceed with the archive
                    Swal.fire(
                        'Archived!',
                        'The user has been archived.',
                        'success'
                    );

                    // TODO: Add logic to perform archive
                }
            });
        });
    });
</script>
@endpush
