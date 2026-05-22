<?php $__env->startSection('maincontent'); ?>
<div class="container mt-5 d-flex justify-content-center align-items-center">
    <div class="container mt-5 alps-card">
        <!-- Title -->
        <div class="mt- mb-4">
            <h2 class="alps-type-h2">Manage Access</h2>
        </div>

        <!-- Top Actions -->
        <div class="d-flex justify-content-between align-items-center mb-8">
            <div class="alps-search-filter-group">
                <div class="position-relative alps-search-wrap">
                    <!-- Input Field -->
                    <input type="text" id="searchInput" class="form-control form-control-solid ps-5 fw-boldest rounded-3 w-300px alps-icon-input"
                        placeholder="&#xF52A; Search...">
                </div>

                <!-- FILTER Button with Menu (secondary orange) -->
                <div class="position-relative">
                    <button class="btn btn-secondary btn-orange rounded-3 fw-boldest d-flex align-items-center btn-hover-rise"
                        data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                        <i class="bi bi-funnel me-1"></i> FILTER
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
                            <form id="accessFilterForm">
                                <!-- Role Dropdown -->
                                <div class="mb-10">
                                    <label class="form-label fw-bold">Role:</label>
                                    <select class="form-select form-select-solid" data-placeholder="Select option"
                                        data-allow-clear="true" id="accessFilterSelect">
                                        <option value="show all">Show All</option>
                                        <option value="admin">System Admin</option>
                                        <option value="coordinator">Coordinator</option>
                                        <option value="facilitator">Facilitator</option>
                                    </select>
                                </div>
                            </form>
                            <div class="d-flex justify-content-end">
                                <button type="reset" class="btn btn-sm btn-light btn-active-light-primary me-2" id="accessFilterReset"
                                    data-kt-menu-dismiss="true">RESET</button>
                            </div>
                        </div>
                    </div>
                    <!-- End Filter Menu -->
                </div>
            </div>

            <div>
                <!-- ADD USER Button (primary orange) -->
                 <a href="<?php echo e(route('add_user')); ?>" class="btn btn-primary btn-orange rounded-3 fw-boldest btn-hover-rise">
                    <span class="svg-icon svg-icon-1">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                            <rect opacity="0.5" x="11.364" y="20.364" width="16" height="2" rx="1" transform="rotate(-90 11.364 20.364)" fill="black" />
                            <rect x="4.36396" y="11.364" width="16" height="2" rx="1" fill="black" />
                        </svg>
                    </span>
                    ADD USER
                </a>
            </div>
        </div>


        <!-- Table -->
        <div class="table-responsive alps-table-wrap">
            <table class="table align-middle text-center gy-7 gs-7 w-100" id="access_table">
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

                    <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                        <tr row-id="<?php echo e($user->id); ?>" data-role="<?php echo e(strtolower($user->usertype)); ?>">
                            <td class="d-flex align-items-center text-start">
                                <div class="symbol symbol-50px alps-avatar-frame-sm me-3 border border-2 border-dark">
                                    <img src="<?php echo e(route('access.avatar', $user->id)); ?>" alt="Profile Picture" style="object-fit: cover; width: 100%; height: 100%;">
                                </div>
                                <div class="fs-5">
                                    <span class="fw-bold d-block"><?php echo e($user->name); ?></span>
                                    <small class="text-muted"><?php echo e($user->username); ?></small>
                                </div>
                            </td>
                            <td><?php echo e($user->email); ?></td>

                            <td class="user-role" data-role="<?php echo e(strtolower($user->usertype)); ?>"> 
                                <?php switch($user->usertype):
                                    case ("admin"): ?>
                                        <span class="badge badge-light-warning">SYSTEM ADMIN</span>
                                        <?php break; ?>
                                    <?php case ("coordinator"): ?>
                                        <span class="badge badge-light-primary">COORDINATOR</span>
                                        <?php break; ?>
                                    <?php case ("facilitator"): ?>
                                        <span class="badge badge-light-info">FACILITATOR</span>
                                        <?php break; ?>
                                    <!-- <?php case ("assistant"): ?>
                                        <span class="badge badge-light-success">ASSISTANT</span>
                                        <?php break; ?> -->
                                    <?php default: ?>
                                        <span class="badge badge-light-secondary">-</span>
                                <?php endswitch; ?>

                            </td>
                            <td>
                                <div class="d-flex justify-content-center">

                                    <?php if($user->color): ?>

                                    <div class="alps-user-color-chip user-color-chip" data-color="<?php echo e($user->color); ?>"></div>

                                    <?php else: ?>

                                    <p class="mb-0">No Color Assigned</p>

                                    <?php endif; ?>

                                </div>
                            </td>
                            <td>
                                <div class="position-relative d-inline-block">
                                    <!--begin::Menu-->
                                    <button type="button" class="btn btn-secondary btn-sm dropdown-toggle"
                                        data-kt-menu-trigger="click"
                                        data-kt-menu-placement="right-start">
                                        MENU
                                    </button>

                                    <div class="menu menu-sub menu-sub-dropdown alps-action-menu" data-kt-menu="true">

                                        <div class="menu-item px-0">
                                            <a class="menu-link px-4 py-3 alps-action-link" data-bs-toggle="modal" data-bs-target="#modal_view_user">
                                                <i class="bi bi-pencil-square text-primary me-3 fs-5"></i>
                                                <span class="alps-action-text">View & Edit Details</span>
                                            </a>
                                        </div>

                                        <div class="menu-item px-0">
                                            <a href="<?php echo e(route('change_credential', [$user->id])); ?>" id="edit-credential-btn" class="menu-link px-4 py-3 alps-action-link">
                                                <i class="bi bi-lock-fill me-3 fs-5" style="color: #804AC0;"></i>
                                                <span class="alps-action-text">Change Credentials</span>
                                            </a>
                                        </div>

                                        <div class="menu-item px-0">
                                            <a class="menu-link px-4 py-3 alps-action-link deleteBtn" data-id="<?php echo e($user->id); ?>">
                                                <i class="bi bi-trash text-danger me-3 fs-5"></i>
                                                <span class="alps-action-text">Delete</span>
                                            </a>
                                        </div>
                                    </div>
                                    <!--end::Menu-->
                                </div>
                            </td>
                        </tr>

                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                    
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <nav aria-label="Page navigation" class="mt-3">
            <ul class="pagination justify-content-center mt-5">
                <!-- Buttons are dynamically added by JavaScript -->
            </ul>
        </nav>

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
                        <h2 class="fw-boldest alps-modal-heading" data-kt-calendar="title">VIEW USER</h2>
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
                                        <i class="bi bi-gear-wide-connected fs-3 me-5 alps-icon-accent"></i>Role
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
                                        <i class="bi bi-person-fill fs-3 me-5 alps-icon-accent"></i>Full Name
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
                                        <i class="bi bi-envelope-fill fs-3 me-5 alps-icon-accent"></i>Email Address
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
                                        <i class="bi bi-telephone-fill fs-3 me-5 alps-icon-accent"></i>Contact Number
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
                                <i class="bi bi-image-fill fs-3 me-5 alps-icon-accent"></i>1x1 ID Picture
                            </label>
                            <div class="col-7 fv-row d-flex justify-content-end align-items-center">
                                <!--begin::Image input-->
                                <div class="image-input image-input-outline alps-avatar-frame-lg border border-2" data-kt-image-input="true">
                                    <!--begin::Preview existing avatar-->
                                    <div class="image-input-wrapper alps-avatar-frame-lg-inner" id="idpic">

                                    </div>
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
                                        <i class="bi bi-person-video3 fs-3 me-5 alps-icon-accent"></i>Assigned Username
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
                                        <i class="bi bi-shield-lock-fill fs-3 me-5 alps-icon-accent"></i>Assigned Password
                                    </label>
                                </div>
                            </div>
                            <div class="col-7">
                                <div class="fv-row d-flex justify-content-end align-items-center">
                                    <p class="lead fs-6 mb-0" id="pass">
                                        ********
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
                                        <i class="bi bi-palette-fill fs-3 me-5 alps-icon-accent"></i>Assigned Color
                                    </label>
                                </div>
                            </div>
                            <div class="col-7">
                                <div class="fv-row d-flex justify-content-end align-items-center">
                                    <div id="color" class="alps-user-color-chip"></div>
                                </div>
                            </div>
                        </div>
                        <!--end::Color-->
                    </div>
                    <!--end::Modal body-->
                    
                    <!--begin::Modal footer-->
                    <div class="modal-footer justify-content-end">
                        <!--begin::Button-->
                        <a href="#" id="edit-user-btn" class="btn btn-primary me-2">
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
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script src="<?php echo e(asset('js/manage_access.js')); ?>"></script>
<script src="<?php echo e(asset('js/edit_user.js')); ?>"></script>


<!-- VIEW USER DATA  -->
<script>
    $(document).ready(function (){

        $('.user-color-chip').each(function() {
            const swatchColor = $(this).data('color');
            if (swatchColor) {
                $(this).css('background-color', swatchColor);
            }
        });

        $('a[data-bs-target="#modal_view_user"]').click(function (e){
            let userId = $(this).closest('tr').attr('row-id');  // Get user ID

            $.ajax({
                url: `/access/api/get/user/${userId}`,
                type: 'GET',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    console.log('User Data:', response);

                    // Role
                    let role = '-';
                    if(response.user.usertype === "admin") {
                        role = 'System Admin';
                    } else if(response.user.usertype === "coordinator") {
                        role = 'Training Coordinator';
                    } else if(response.user.usertype === "facilitator") {
                        role = 'Facilitator';
                    }

                    $('#role').text(role);
                    $('#fullname').text(response.user.name);
                    $('#email').text(response.user.email);
                    $('#num').text(response.user.contact_number);
                    
                    let picture = `<img class="alps-avatar-preview" src="<?php echo e(url('/access/avatar')); ?>/${response.user.id}" alt="Profile Picture">`;
                    $('#idpic').html(picture);

                    $('#username').text(response.user.username);
                    $('#pass').text(response.user.password);
                    $('#color').css('background-color', response.user.color);

                    // Set the correct edit URL dynamically
                    let editUrl = `/access/edit_user/${userId}`; 
                    $('#edit-user-btn').attr('href', editUrl);

                    console.log("Edit Button Updated:", $('#edit-user-btn').attr('href'));

                },
                error: function(xhr) {
                    console.error('Error:', xhr.responseJSON?.error || 'An error occurred');
                }
            });

        });

    });
</script>

<?php $__env->stopPush(); ?>

<?php echo $__env->make('global.layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Lagman\Desktop\Codes\ALPs Calendar\ALPS-Calendar\resources\views\access\manage_access.blade.php ENDPATH**/ ?>