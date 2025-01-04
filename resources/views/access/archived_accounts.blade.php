@extends('global.layout')

@section('maincontent')
<div class="container mt-20 d-flex justify-content-center align-items-center bg-white shadow-sm rounded-3 p-5">
    <div class="container mt-5">
        <!-- Title -->
        <div class="mb-4">
            <h3 class="card-header fw-boldest fs-1 " style="color: #7c0101; ">ARCHIVED ACCOUNTS</h3>
        </div>

        <!-- Top Actions -->
        <div class="d-flex justify-content-between align-items-center mb-8">
            <div class="position-relative" style="max-width: 300px;">
                <!-- Input Field -->
                <input type="text" class="form-control form-control-solid ps-5 fw-boldest rounded-3 w-300px"
                    placeholder="&#xF52A; Search..." style="font-family: 'Bootstrap-icons', sans-serif;">
            </div>
            <div class="d-flex align-items-center justify-content-end gap-2">
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
                        <th class="w-100px">ACTIONS</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Row -->
                    <tr>
                        <td class="d-flex align-items-center text-start">
                            <div class="symbol symbol-50px me-3">
                                <img src="assets/media/avatars/300-1.jpg" alt="Profile Picture">
                            </div>
                            <div class="fs-5">
                                <span class="fw-bold d-block">John Doe</span>
                                <small class="text-muted">johndoe</small>
                            </div>
                        </td>
                        <td>johndoe@example.com</td>
                        <td>
                            <span class="badge badge-light-warning">SYSTEM ADMIN</span>
                        </td>
                        <td>
                            <div class="d-flex justify-content-center">
                                <div class=" w-80px h-30px border border-2 border-dark" style="background-color: pink;"></div>
                            </div>
                        </td>

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
                                    <a class="menu-link px-3 restoreBtn">
                                        <i class="bi bi-arrow-counterclockwise text-success me-2"></i> Restore
                                    </a>
                                </div>
                            </div>
                            <!--end::Menu-->
                        </td>
                    </tr>
                    
                    <tr>
                        <td class="d-flex align-items-center text-start">
                            <div class="symbol symbol-50px me-3">
                                <img src="assets/media/avatars/300-2.jpg" alt="Profile Picture">
                            </div>
                            <div class="fs-5">
                                <span class="fw-bold d-block">Jane Smith</span>
                                <small class="text-muted">janesmith</small>
                            </div>
                        </td>
                        <td>janesmith@example.com</td>
                        <td>
                            <span class="badge badge-light-primary">COORDINATOR</span>
                        </td>
                        <td>
                            <div class="d-flex justify-content-center">
                                <div class=" w-80px h-30px border border-2 border-dark" style="background-color: yellow";></div>
                            </div>
                        </td>
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
                                    <a class="menu-link px-3 restoreBtn">
                                        <i class="bi bi-arrow-counterclockwise text-success me-2"></i> Restore
                                    </a>
                                </div>
                            </div>
                            <!--end::Menu-->
                        </td>
                    </tr>
                    
                    <tr>
                        <td class="d-flex align-items-center text-start">
                            <div class="symbol symbol-50px me-3">
                                <img src="assets/media/avatars/300-3.jpg" alt="Profile Picture">
                            </div>
                            <div class="fs-5">
                                <span class="fw-bold d-block">Sam Wilson</span>
                                <small class="text-muted">samwilson</small>
                            </div>
                        </td>
                        <td>samwilson@example.com</td>
                        <td>
                            <span class="badge badge-light-info">FACILITATOR</span>
                        </td>
                        <td>
                            <div class="d-flex justify-content-center">
                                <div class=" w-80px h-30px border border-2 border-dark" style="background-color: gray;"></div>
                            </div>
                        </td>
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
                                    <a class="menu-link px-3 restoreBtn">
                                        <i class="bi bi-arrow-counterclockwise text-success me-2"></i> Restore
                                    </a>
                                </div>
                            </div>
                            <!--end::Menu-->
                        </td>
                    </tr>

                </tbody>
            </table>
        </div>

    </div>
</div>
@endsection

@push('scripts')
<script>
    //Restore button
    document.querySelectorAll('.restoreBtn').forEach(button => {
        button.addEventListener('click', (event) => {
            event.preventDefault(); // Prevent default action (e.g., form submission)

            Swal.fire({
                title: 'Are you sure?',
                text: "You are about to restore this user.",
                icon: 'warning',
                buttonsStyling: false,
                showCancelButton: true,
                confirmButtonText: 'Yes, Restore',
                cancelButtonText: 'Cancel',
                customClass: {
                confirmButton: "btn btn-success",
                cancelButton: 'btn btn-secondary'
            }
            }).then((result) => {
                if (result.isConfirmed) {
                    // Proceed with the reactivation
                    Swal.fire(
                        'Restored!',
                        'The user has been restored.',
                        'success'
                    );

                    // TODO: Add logic to perform reactivation
                }
            });
        });
    });
</script>
@endpush
