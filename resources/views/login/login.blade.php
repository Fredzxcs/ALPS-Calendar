<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>ALPs Calendar</title>
    <!--begin::Fonts-->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" />
    <!--end::Fonts-->
    <!--begin::Page Vendor Stylesheets(used by this page)-->
    <link href="{{ asset('plugins/custom/datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css" />
    <!--end::Page Vendor Stylesheets-->
    <!--begin::Global Stylesheets Bundle(used by all pages)-->
    <link href="{{ asset('plugins/global/plugins.bundle.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('css/style.bundle.css') }}" rel="stylesheet" type="text/css" />
    <!--end::Global Stylesheets Bundle-->
</head>
<div class="bg-image"
    style="background-image: url('{{ asset('img/ALPs_LoginBG.png') }}'); background-repeat: no-repeat; background-size: cover; background-position: center; height: 100vh; width: 100vw;">
    {{-- <div class="text-left">
        <img src="{{ asset('img/ALPs_LOGO.jpg') }}" alt="ALPs Logo" style="width: 100px; height: auto;">
    </div> --}}
    <div class="d-flex justify-content-center align-items-center vh-100">
        <div class="card shadow-sm mx-auto font-Poppins" style="width:35rem; background-color: #052a43; color: #ffffff; border-radius: 30px;">
            <div class="text-center" style="background: none; border-bottom: none;">
                <h3 class="fs-1 fw-bold text-white mt-6 fw-boldest ">
                    ALPs Training Hub
                </h3>
                <p class="fs-6 fw-normal mb-1 text-white">
                    It's Good to See You!
                </p>
            </div>

            <div class="card-body">
                <!-- Username -->
                <div class="mb-3">
                    <label for="username" class="form-label text-white required">Username</label>
                    <input type="text" id="username" name="username" class="form-control"
                        placeholder="Enter your username" required>
                </div>
                <!-- Password -->
                <div class="mb-3">
                    <label for="password" class="form-label text-white required">Password</label>
                    <input type="password" id="password" name="password" class="form-control" placeholder="Enter your password" required>
                    <span class="btn btn-sm btn-icon position-absolute translate-middle-y" id="togglePassword" style="top: 65%; right: 10%; cursor: pointer;">
                        <i class="fas fa-eye-slash fs-3" id="eyeSlash"></i>
                        <i class="fas fa-eye d-none fs-3" id="eye"></i>
                    </span>
                    <a href="#" class="d-block mt-1 text-muted text-hover-primary" style="font-size: 0.9rem;">Forgot Password?</a>
                </div>
                <!-- Submit Button -->
                <div class="d-flex justify-content-center">
                    <a href="/calendar">
                      <button type="submit" class="btn btn-lg btn-hover-scale" style="background-color: #7c0101; color: #ffffff;">Login</button>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

</html>

<script>
    // Select elements
    const passwordInput = document.getElementById('password');
    const togglePassword = document.getElementById('togglePassword');
    const eyeSlash = document.getElementById('eyeSlash');
    const eye = document.getElementById('eye');

    // Toggle password visibility
    togglePassword.addEventListener('click', () => {
        const isPasswordVisible = passwordInput.type === 'text';

        // Toggle input type
        passwordInput.type = isPasswordVisible ? 'password' : 'text';

        // Toggle icon visibility
        eyeSlash.classList.toggle('d-none', !isPasswordVisible);
        eye.classList.toggle('d-none', isPasswordVisible);
    });
</script>
