<style>
    .nav-link {
        color: #052a43;
    }

    .nav-link:hover {
        color: #7c0101 !important;
    }

    .nav-link:hover i {
        color: #7c0101 !important;
    }

    .nav-link.active {
        color: #7c0101 !important;
    }

</style>


<nav class="navbar navbar-expand-lg bg-gray shadow-sm py-6">
    <div class="container-fluid">
        <!-- Brand Logo and Name -->
        <a class="navbar-brand d-flex align-items-center"  href="#">
            <img src="{{ asset('img/ALPs_Logo.png') }}" alt="ALPS Logo" class="me-2 w-50px">
            <span class="fw-boldest fs-1" style="color: #052a43;">Advanced Learning Programs</span>
        </a>
        <!-- Toggler for Mobile -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"  style="color: #052a43;"></span>
        </button>
        <!-- Navigation Buttons -->
        <div class="collapse navbar-collapse justify-content-end hover-scale fs-3" style="color: #052a43;" id="navbarNav">
            <ul class="navbar-nav ">
                <!-- ACCESS Button -->

                @if (Auth::check() && Auth::user()->usertype === "admin")
                        <li class="nav-item me-5">
                            <a class="nav-link fw-medium text-primary-hover fw-bolder"
                            href="{{ route('manage_access') }}">
                                ACCESS
                            </a>
                        </li>
                @endif


                <!-- CALENDAR Button -->
                <li class="nav-item me-5">
                    <a class="nav-link fw-medium text-primary-hover fw-bolder"
                    href="/calendar">
                        CALENDAR
                    </a>
                </li>

                @if (Auth::check() && in_array(Auth::user()->usertype, ['admin', 'coordinator']))
                <!-- CONFIGURATION Dropdown -->
                <li class="nav-item dropdown me-5">
                    <a class="nav-link fw-medium text-primary-hover fw-bolder dropdown-toggle"
                        style="cursor:pointer;"
                        id="configurationDropdown"
                        data-kt-menu-trigger="click"
                        data-kt-menu-placement="bottom-start">
                        CONFIGURATION
                    </a>

                    <!--begin::Links-->
                    <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-bold fs-5 w-200px py-4"
                        data-kt-menu="true">

                        <!--begin::Link item-->
                        <div class="menu-item px-3">
                            <a href="{{ route('config_courses') }}" class="menu-link px-3">
                                ALPs Courses
                            </a>
                        </div>
                        <!--end::Link item-->

                        <!--begin::Link item-->
                        <div class="menu-item px-3">
                            <a href="{{ route('config_companies') }}" class="menu-link px-3" id="event_view">
                                List of Companies
                            </a>
                        </div>
                        <!--end::Link item-->

                        <!--begin::Link item-->
                        <div class="menu-item px-3">
                            <a href="#" class="menu-link px-3" id="event_view">
                                Account Credentials
                            </a>
                        </div>
                        <!--end::Link item-->
                    </div>
                    <!--end::Links-->
                </li>
            @endif


                <!-- SETTINGS -->
                <li class="nav-item dropdown me-5">
                    <a class="nav-link text-primary-hover fw-bold text-dark"
                        style="cursor: pointer;"
                        id="navbarDropdown"
                        data-kt-menu-trigger="click"
                        data-kt-menu-placement="bottom-start">
                        <i class="fas fa-cog fs-1 text-dark"></i>
                    </a>
                    <!--begin::Links-->
                    <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-bold fs-5 w-150px py-4"
                        data-kt-menu="true">

                        <!--begin::Link item-->
                        <div class="menu-item px-3">
                            <a href="#" class="menu-link px-3">
                                Profile
                            </a>
                        </div>
                        <!--end::Link item-->

                        <!--begin::Link item-->
                        <div class="menu-item px-3">
                            <a href="#" class="menu-link px-3" id="event_view">
                                Settings
                            </a>
                        </div>
                        <!--end::Link item-->

                        <!--begin::Link item-->
                        <div class="separator"></div>
                        <div class="menu-item px-3">

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <a href="{{ route('logout') }}" onclick="event.preventDefault();
                                        this.closest('form').submit();" class="menu-link px-3 text-danger" id="event_view">
                                    Log Out
                                </a>

                            </form>

                        </div>
                        <!--end::Link item-->
                    </div>
                    <!--end::Links-->
                </li>
            </ul>
        </div>
    </div>
</nav>
@push('scripts')
<script src="{{ asset('js/navbar.js') }}"></script>
@endpush
