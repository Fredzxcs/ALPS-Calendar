@extends('global.layout')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">


@section('maincontent')
    <div class="d-flex justify-content-center align-items-center mt-20 ">
        <div class="container mt-5 ">
            <!-- Card -->
            <div class="card shadow-sm rounded-3">
                <!-- Title -->
                <div class="d-flex justify-content-center align-items-center mb-0 bg-primary rounded-top h-80px" >
                    <h2 class="text-white fw-boldest m-0 fs-1">ADD UNAVAILABILITY</h2>
                </div>
                <!-- Form -->
                <div class="p-20 pt-10 pb-6 ">

                    <div class="text-center mb-10">
                        <h3 class="text-center" style="color: #7c0101;">{{ $user->name }}</h3>

                        @if ($user->usertype === "admin")

                            <span class="badge badge-light-warning">SYSTEM ADMIN</span>

                        @elseif ($user->usertype === "facilitator")

                            <span class="badge badge-light-info">FACILITATOR</span>

                        @elseif ($user->usertype === "coordinator")

                            <span class="badge badge-light-primary">COORDINATOR</span>

                        @endif

                    </div>

                    <form id="add_unavailability_form">
                    <!--Select Date-->
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <label for="add_unavailable_date" class="fw-bold mb-2 required">Date Range</label>
                            <div class="position-relative">
                                <input type="text" id="add_unavailable_date" class="form-control form-control-solid pe-5" placeholder="Select Date">
                                <span class="position-absolute top-50 end-0 translate-middle-y me-3 text-muted">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-calendar-heart-fill" viewBox="0 0 16 16">
                                        <path d="M4 .5a.5.5 0 0 0-1 0V1H2a2 2 0 0 0-2 2v1h16V3a2 2 0 0 0-2-2h-1V.5a.5.5 0 0 0-1 0V1H4zM16 14V5H0v9a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2M8 7.993c1.664-1.711 5.825 1.283 0 5.132-5.825-3.85-1.664-6.843 0-5.132"/>
                                    </svg>
                                </span>
                            </div>
                        </div>
                        <div class="col-md-8 mb-5">
                            <label for="add_unavailable_purpose" class="fw-bold mb-2 required">Purpose</label>
                            <input type="text" id="add_unavailable_purpose" class="form-control form-control-solid pe-5" placeholder="Enter Purpose of Unavailability">
                        </div>

                    </div>

                    <!-- Buttons -->
                    <div class="d-flex justify-content-center gap-5 ">
                        <a href="{{ route('calendar') }}">
                            <button type="button" class="btn btn-light fw-boldest">CANCEL</button>
                        </a>
                        <button type="submit" id="add_unavailability_submit" class="btn btn-success fw-boldest">SAVE</button>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script>

        let user = {{ $user->id }}

    </script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="{{ asset('js/add_unavailability.js') }}"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
@endpush
