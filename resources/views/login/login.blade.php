<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>ALPs Calendar</title>
    <link rel="icon" href="{{ asset('img/ALPs_Logo.png') }}" type="image/x-icon">
    <!--begin::Fonts-->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" />
    <!--end::Fonts-->
    <!--begin::Page Vendor Stylesheets(used by this page)-->
    <link href="{{ asset('plugins/custom/datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css" />
    <!--end::Page Vendor Stylesheets-->
    <!--begin::Global Stylesheets Bundle(used by all pages)-->
    <link href="{{ asset('plugins/global/plugins.bundle.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('css/style.bundle.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('css/alps-modern.css') }}" rel="stylesheet" type="text/css" />
    <!--end::Global Stylesheets Bundle-->
</head>
<div class="bg-image alps-login-shell">
    <div class="d-flex justify-content-center align-items-center vh-100 alps-login-stage">

        <!-- Logo -->
        <div class="position-absolute text-center alps-login-logo-wrap">
            <img src="{{ asset('img/ALPs_Logo.png') }}" class="mb-10 alps-login-logo" alt="ALPs Logo">
        </div>

        <div class="card shadow-sm mx-auto font-Poppins alps-login-card">
            <div class="text-center alps-login-card-header">
                <h3 class="fs-1 fw-bold text-white mt-6 fw-boldest ">
                    ALPs Calendar
                </h3>
                <p class="fs-6 fw-normal mb-1 text-white">
                    It's Good to See You!
                </p>
            </div>

            <x-auth-session-status class="mb-4" :status="session('status')" />

            <form method="POST" action="{{ route('loginPOST') }}">
                @csrf
                <div class="card-body">
                    <!-- Username -->
                    <div class="mb-3">
                        <label for="username" class="form-label text-white required">Username</label>
                        <input type="text" id="username" name="username" class="form-control"
                            placeholder="Enter your username" required>
                            <x-input-error :messages="$errors->get('username')" class="mt-2" />

                    </div>


                    <!-- Password -->
                    <div class="mb-3">
                        <label for="password" class="form-label text-white required">Password</label>
                        <div class="position-relative">
                            <input type="password" class="form-control"
                                placeholder="Enter your password"
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
                            <!-- <x-input-error :messages="$errors->get('password')" class="mt-2" />
                            <a href="#" class="d-block mt-1 text-muted text-hover-primary alps-forgot-text">Forgot Password?</a> -->
                        </div>
                    </div>


                    <!-- Submit Button -->
                    <div class="d-flex justify-content-center">
                        <a href="/calendar">
                                                    <button type="submit" class="btn btn-lg btn-hover-scale btn-danger">Login</button>
                        </a>
                    </div>
                </div>

            </form>

        </div>
    </div>
</div>

</html>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    //Toggle password visibility
    $('.togglePassword').on('click', function () {
        const input = $($(this).data('target'));
        const icons = $(this).find('i');

        if (input.attr('type') === 'password') {
            input.attr('type', 'text');
            icons.first().addClass('d-none');
            icons.last().removeClass('d-none');
        } else {
            input.attr('type', 'password');
            icons.first().removeClass('d-none');
            icons.last().addClass('d-none');
        }
    });
</script>
