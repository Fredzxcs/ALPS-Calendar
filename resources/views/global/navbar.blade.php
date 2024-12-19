<style>
    .nav-link {
        color: #052a43;
    }
    .nav-link:hover {
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
            {{-- <img src="your-logo-path.png" alt="ALPS Logo" class="me-2" style="height: 40px;"> --}}
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
                <li class="nav-item">
                    <a class="nav-link fw-medium fw-bolder"
                    href="/access"
                    id="access-link">
                        ACCESS
                    </a>
                </li>

                <!-- CALENDAR Button -->
                <li class="nav-item">
                    <a class="nav-link fw-medium text-primary-hover fw-bolder"
                    href="/calendar">
                        CALENDAR
                    </a>
                </li>
                <!-- CONFIGURATION Dropdown -->
                <li class="nav-item dropdown">
                    <a class="nav-link fw-medium text-primary-hover fw-bolder dropdown-toggle" 
                       href="/configuration" 
                       id="configurationDropdown" 
                       role="button" 
                       data-bs-toggle="dropdown" 
                       aria-expanded="false">
                        CONFIGURATION
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="configurationDropdown">
                        <li><a class="dropdown-item" href="/configuration/company">Company</a></li>
                        <li><a class="dropdown-item" href="/configuration/course">Course</a></li>
                        <li><a class="dropdown-item" href="/configuration/credentials">Credentials</a></li>
                    </ul>
                </li>
                <!-- Dropdown Menu -->
                <li class="nav-item dropdown">
                    <a class="nav-link text-primary-hover fw-bold text-dark"
                    href="#" id="navbarDropdown"
                    role="button"
                    data-bs-toggle="dropdown">
                        <i class="fas fa-cog fs-1 text-dark"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item fw-medium" href="#">Settings</a></li>
                        <li><a class="dropdown-item fw-medium" href="#">Profile</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item text-danger fw-medium" href="#">Logout</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>
@push('scripts')
<script src="{{ asset('js/navbar.js') }}">
</script>
@endpush
