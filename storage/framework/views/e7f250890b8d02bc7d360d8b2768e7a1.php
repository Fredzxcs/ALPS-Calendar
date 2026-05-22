<?php $__env->startSection('maincontent'); ?>
    <div class="d-flex justify-content-center align-items-center mt-20">
    <div class="container mt-5">
        <div class="shadow-sm border-0">
            <div class="alps-header-edit rounded-top d-flex justify-content-center align-items-center py-4">
                <h2 class="text-white fw-boldest m-0 fs-1">Change Credentials</h2>
            </div>
            <!--begin::Form-->
            <form class="form w-100 px-5 alps-card-glass-body p-10" novalidate="novalidate" id="change_credentials_form">
                <?php echo csrf_field(); ?>
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
                                        <input type="hidden" id="color" name="color" value="<?php echo e(old('color', $currentColor ?? '')); ?>">
                                        <button type="button" id="openColorPicker" class="btn alps-color-trigger w-100 mt-2">
                                            <span class="alps-color-trigger-swatch" id="colorPreviewSwatch"></span>
                                            <span class="d-flex flex-column text-start">
                                                <span class="fw-bold">Update color</span>
                                                <span class="small text-muted" id="colorPreviewHex">Tap to open the color modal</span>
                                            </span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--end::Input-->

                    <!-- Buttons -->
                    <div class="d-flex justify-content-center gap-5 mt-5">
                        <a href="<?php echo e(route('manage_access')); ?>">
                            <button type="button" class="btn btn-light fw-boldest">CANCEL</button>
                        </a>
                        <button type="submit" id="change_credentials_submit" class="btn btn-success fw-boldest">SAVE</button>
                    </div>
                </div>
            <?php echo $__env->make('access.partials.color_picker_modal', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            </form>
            <!--end::Form-->
            <script>
                window.ALPS_ACCESS_COLOR_STATE = <?php echo json_encode([
                    'takenColors' => $takenColors ?? [], 'currentColor' => $currentColor ?? null, ]) ?>;
            </script>
            <script src="<?php echo e(asset('js/access_color_picker.js')); ?>"></script>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php $__env->startPush('scripts'); ?>
<script src="<?php echo e(asset('js/manage_access.js')); ?>"></script>
<script src="<?php echo e(asset('js/change_credentials.js')); ?>"></script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('global.layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Lagman\Desktop\Codes\ALPs Calendar\ALPS-Calendar\resources\views/access/change_credentials.blade.php ENDPATH**/ ?>