<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>ALPs Calendar</title>
    <link rel="icon" href="{{ asset('img/ALPs_Logo.png') }}" type="image/x-icon">
    <!--begin::Fonts-->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" />
    <!--end::Fonts-->
    <!--begin::Page Vendor Stylesheets(used by this page)-->
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.css" rel="stylesheet">
    <link href="{{ asset('assets/plugins/custom/fullcalendar/fullcalendar.bundle.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('plugins/custom/fullcalendar/fullcalendar.bundle.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('plugins/custom/datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <!--end::Page Vendor Stylesheets-->
    <!--begin::Global Stylesheets Bundle(used by all pages)-->
    <link href="{{ asset('plugins/global/plugins.bundle.css') }}" rel="stylesheet" type="text/css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="{{ asset('css/style.bundle.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('css/alps-modern.css') }}" rel="stylesheet" type="text/css" />
    <!--end::Global Stylesheets Bundle-->
{{--
    <style>
        body {
            background-color:rgb(223, 246, 255);
        }

        .navbar {
            background-color: #f8f9fa;
        }
    </style> --}}
</head>

<body>
    <!-- Navbar -->
    <header>
        @include('global.navbar')
    </header>
    <!-- Main Content -->
    <main>
        <div class="container-fluid mb-5">
            @yield('maincontent')
        </div>
    </main>

</body>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="assets/js/plugins/formrepeater.bundle.js"></script>
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>
<script src="{{ asset('plugins/global/plugins.bundle.js') }}"></script>
<script src="{{ asset('js/scripts.bundle.js') }}"></script>
<script src="{{ asset('plugins/bootstrap-sweetalert/sweetalert.min.js') }}"></script>
<script src="{{ asset('assets/plugins/custom/fullcalendar/fullcalendar.bundle.js') }}"></script>
<script src="{{ asset('js/alps-time-sky.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@stack('scripts')

<script>
    // Move modal elements to <body> to avoid stacking-context issues
    (function(){
        if (typeof jQuery !== 'undefined') {
            jQuery(function($){
                $('.modal').each(function(){
                    var $m = $(this);
                    if (!$m.parent().is('body')) {
                        $m.appendTo('body');
                    }
                });
            });
        }
    })();
</script>

</html>
