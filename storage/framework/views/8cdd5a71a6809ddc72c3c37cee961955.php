<?php $__env->startSection('maincontent'); ?>
<div class="container mt-5 d-flex justify-content-center align-items-center">
    <div class="container mt-5 alps-card">
        <!-- Title -->
        <div class="mb-4">
            <h2 class="alps-type-h2">List of Courses</h2>
        </div>

        <!-- Top Actions -->
        <div class="d-flex justify-content-between align-items-center mb-8">
            <div class="position-relative alps-search-wrap">
                <!-- Input Field -->
                <input type="text" id="searchInput"
                    class="form-control form-control-solid ps-5 fw-boldest rounded-3 w-300px alps-icon-input"
                    placeholder="&#xF52A; Search...">
            </div>
            <div class="d-flex align-items-center justify-content-end gap-2">
                <!-- ADD USER Button -->
                 <a class="btn btn-primary btn-orange rounded-3 fw-boldest btn-hover-rise" data-bs-toggle="modal" data-bs-target="#modal_add_course">
                    <span class="svg-icon svg-icon-1">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                            <rect opacity="0.5" x="11.364" y="20.364" width="16" height="2" rx="1" transform="rotate(-90 11.364 20.364)" fill="black" />
                            <rect x="4.36396" y="11.364" width="16" height="2" rx="1" fill="black" />
                        </svg>
                    </span>
                    ADD COURSE
                </a>
            </div>
        </div>

        <!-- Table -->
        <div class="table-responsive alps-table-wrap">
            <table class="table align-middle text-center gy-7 gs-7" id="courses_table">
                <thead>
                    <tr class="fw-boldest text-gray-800 fs-5">
                        <th class="w-250px">COURSE NAME</th>
                        <th class="w-100px">COURSE CODE</th>
                        <th class="w-100px">ACTIONS</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if($courses->isEmpty()): ?>
                    <tr>
                        <td colspan="3">No courses found.</td>
                    </tr>
                    <?php else: ?>
                    <!-- Row -->
                    <?php $__currentLoopData = $courses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $course): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr id="course-row-<?php echo e($course->id); ?>">
                        <td class="d-flex align-items-center text-start"><?php echo e($course->course_name); ?></td>
                        <td ><?php echo e($course->course_code); ?></td>
                        <td >
                            <div class="position-relative d-inline-block">
                                <!--begin::Menu-->
                                <button type="button" class="btn btn-secondary btn-sm dropdown-toggle"
                                    data-kt-menu-trigger="click"
                                    data-kt-menu-placement="bottom-start"
                                    data-kt-menu-attach="parent"
                                    data-kt-menu-overflow="true">
                                    MENU
                                </button>
                                <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-bold fs-7 w-225px py-4"
                                    data-kt-menu="true">

                                    <div class="menu-item px-3">
                                        <a class="menu-link px-3 editCourseBtn"
                                            data-id="<?php echo e($course->id); ?>"
                                            data-bs-toggle="modal"
                                            data-bs-target="#modal_edit_course">
                                            <i class="bi bi-pencil-square text-primary me-2"></i> View & Edit Details
                                        </a>
                                    </div>

                                    <div class="menu-item px-3">
                                        <a class="menu-link px-3 deleteBtn"
                                            data-id="<?php echo e($course->id); ?>">
                                            <i class="bi bi-trash text-danger me-2"></i> Delete
                                        </a>
                                    </div>
                                </div>
                                <!--end::Menu-->
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php endif; ?>
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
    <!--begin::Modal - Add Course-->
    <div class="modal fade" id="modal_add_course" tabindex="-1" aria-hidden="true">
        <!--begin::Modal dialog-->
        <div class="modal-dialog modal-dialog-centered mw-650px">
            <!--begin::Modal content-->
            <div class="modal-content">
                <!--begin::Form-->
                <form class="form" id="modal_add_course_form">

                    <input class="" type="hidden" name="_token" value="<?php echo e(csrf_token()); ?>">

                    <!--begin::Modal header-->
                    <div class="modal-header">
                        <h3 class="alps-type-h3 alps-modal-heading" data-kt-calendar="title">Add Course</h3>
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
                        <div class="row mb-5">

                            <label for="course_name" class="form-label fw-bold required">Course Name</label>
                            <input type="text"
                                class="form-control form-control-solid"
                                id="add_course_name"
                                placeholder="Enter Course Name">

                            <div class="invalid-feedback">Required field</div>
                        </div>

                        <div class="row mb-5">
                            <label for="course_code" class="form-label fw-bold required">Course Code</label>
                            <input type="text"
                                class="form-control form-control-solid"
                                id="add_course_code"
                                placeholder="Enter Course Code">
                        </div>
                    </div>
                    <!--end::Modal body-->

                    <!-- DOM element to store the route URL -->
                    <div id="route-config" data-url="<?php echo e(route('add_course')); ?>"></div>

                    <!--begin::Modal footer-->
                    <div class="modal-footer justify-content-end">
                        <!--begin::Button-->
                        <button type="submit"
                            class="btn btn-primary btn-orange me-2 addBtn"
                            id="add_course_submit">
                            SAVE
                        </button>
                        <button type="reset"
                            class="btn btn-tertiary btn-blue"
                            data-bs-dismiss="modal">
                            CANCEL
                        </button>
                        <!--end::Button-->
                    </div>
                    <!--end::Modal footer-->
                </form>
                <!--end::Form-->
            </div>
        </div>
    </div>
    <!--end::Modal - Add Course-->

    <!--begin::Modal - Edit Course-->
    <div class="modal fade" id="modal_edit_course" tabindex="-1" aria-hidden="true">
        <!--begin::Modal dialog-->
        <div class="modal-dialog modal-dialog-centered mw-650px">
            <!--begin::Modal content-->
            <div class="modal-content">
                <!--begin::Form-->
                <form class="form" action="#" id="modal_edit_course_form">
                    <input class="" type="hidden">
                    <!--begin::Modal header-->
                    <div class="modal-header">
                        <h3 class="alps-type-h3 alps-modal-heading" data-kt-calendar="title">Edit Course</h3>
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
                        <div class="row mb-5">
                            <label for="course_name" class="form-label fw-bold required">Course Name</label>
                            <input type="text" class="form-control form-control-solid" id="edit_course_name" placeholder="Enter Course Name">
                        </div>

                        <div class="row mb-5">
                            <label for="course_code" class="form-label fw-bold required">Course Code</label>
                            <input type="text" class="form-control form-control-solid" id="edit_course_code" placeholder="Enter Course Code"">
                        </div>
                    </div>
                    <!--end::Modal body-->
                    <!--begin::Modal footer-->
                    <div class="modal-footer justify-content-end">
                        <!--begin::Button-->
                        <button type="submit" class="btn btn-primary btn-orange me-2 editBtn" id="edit_course_submit">
                            SAVE
                        </button>
                        <button type="reset" class="btn btn-tertiary btn-blue" data-bs-dismiss="modal">CANCEL</button>
                        <!--end::Button-->
                    </div>
                    <!--end::Modal footer-->
                </form>
                <!--end::Form-->
            </div>
        </div>
    </div>
    <!--end::Modal - Edit Course-->
    <!--end::Modals-->
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script src="<?php echo e(asset('js/courses.js')); ?>"></script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('global.layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Lagman\Desktop\Codes\ALPs Calendar\ALPS-Calendar\resources\views\configuration\courses.blade.php ENDPATH**/ ?>