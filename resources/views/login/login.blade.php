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
<body class="alps-login-page">
<img src="{{ asset('img/alps_border_tl.png') }}" class="alps-login-deco tl" alt="">
<img src="{{ asset('img/alps_border_br.png') }}" class="alps-login-deco br" alt="">
<div class="bg-image alps-login-shell">
    <div class="d-flex justify-content-center align-items-center vh-100 alps-login-stage">

        <div class="position-relative">

            <!-- Logo (FULLY VISIBLE ABOVE CARD) -->
            <div class="alps-login-logo-badge">
                <img src="{{ asset('img/ALPs_Logo.png') }}" alt="ALPs Logo">
            </div>

            <!-- Card -->
            <div class="card alps-login-card text-center px-14 pb-10 pt-20">

                <h1 class="alps-type-h1 alps-text-ink mt-12 mb-2">ALPs Calendar</h1>
                <p class="alps-subtitle alps-text-muted mb-10">It's Good to See You!</p>

                <form method="POST" action="{{ route('loginPOST') }}">
                    @csrf

                    <!-- Username -->
                    <div class="text-start mb-5">
                        <label class="form-label alps-type-label required">Username</label>
                        <input type="text" name="username" class="form-control"
                            placeholder="Enter Username" required>
                    </div>

                    <!-- Password -->
                    <div class="text-start mb-8">
                        <label class="form-label alps-type-label required">Password</label>
                        <div class="position-relative">
                            <input type="password" name="password" id="password"
                                class="form-control alps-type-input"
                                placeholder="Enter Password" required>

                            <span class="togglePassword position-absolute top-50 end-0 translate-middle-y me-3"
                                data-target="#password">
                                <i class="bi bi-eye-slash"></i>
                                <i class="bi bi-eye d-none"></i>
                            </span>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-lg w-50 btn-primary btn-orange">
                        LOGIN
                    </button>
                </form>

            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="{{ asset('js/alps-time-sky.js') }}"></script>
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
</body>
</html>
