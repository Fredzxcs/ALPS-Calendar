@extends('global.layout')

@section('maincontent')
    <div class="d-flex justify-content-center align-items-center mt-20">
        <div class="container mt-5">
            <!-- Card -->
            <div class="card shadow-sm rounded-3">
                <!-- Title -->
                <div class="d-flex justify-content-center align-items-center mb-4 bg-primary rounded-top h-80px">
                    <h2 class="text-white fw-boldest m-0 fs-1">ADD TRAINING</h2>
                </div>
                <!-- Form -->
                <div class="p-5">
                    <form>
                        <!-- Mode of Training -->
                        <div class="mb-4">
                            <label class="fw-bold mb-2 required">Mode of Training</label>
                            <div class="d-flex gap-4">
                                <div>
                                    <input type="radio" id="virtual" name="mode" class="form-check-input" checked>
                                    <label for="virtual" class="form-check-label">Virtual</label>
                                </div>
                                <div>
                                    <input type="radio" id="face-to-face" name="mode" class="form-check-input">
                                    <label for="face-to-face" class="form-check-label">Face-to-Face</label>
                                </div>
                                <div>
                                    <input type="radio" id="public-course" name="mode" class="form-check-input">
                                    <label for="public-course" class="form-check-label">Public Course</label>
                                </div>
                            </div>
                        </div>

                        <!-- Public Course: Course and In-person Training -->
                        <div class="row mb-4 d-none" id="public-course-container">
                            <div class="col-md-12 d-flex align-items-center justify-content-between">
                                <!-- In-person Checkbox -->
                                <div class="form-check me-3">
                                    <input type="checkbox" id="inperson-training" class="form-check-input">
                                    <label for="inperson-training" class="form-check-label fw-bold">In-person training?</label>
                                </div>
                                <!-- Course -->
                                <div class="d-flex align-items-center" style="width: 49%;">
                                    <div class="flex-grow-1">
                                        <label for="public-course-select" class="fw-bold mb-2 required">Course</label>
                                        <select id="public-course-select" class="form-select form-select-solid">
                                            <option>Select Course</option>
                                        </select>
                                    </div>
                                    {{-- form repeater --}}
                                    <button type="button" class="btn btn-primary ms-3 mt-8">
                                        <i class="bi bi-plus-lg"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Email and Password -->
                        <div class="row mb-4" id="credentials-container">
                            <div class="col-md-6">
                                <label for="email" class="fw-bold mb-2 required">Email Credentials </label>
                                <input type="email" id="email" class="form-control form-control-solid"
                                    placeholder="Enter Email">
                            </div>
                            <div class="col-md-6">
                                <label for="password" class="fw-bold mb-2 required">Password Credentials </label>
                                <input type="password" id="password" class="form-control form-control-solid"
                                    placeholder="Enter Password">
                            </div>
                        </div>
                        <!-- Location: Face-to-Face and In-Person -->
                        <div class="mb-4 d-none" id="location-container">
                            <label for="location" class="fw-bold mb-2 required">Location </label>
                            <input type="text" id="location" class="form-control form-control-solid"
                                placeholder="Enter Location">
                        </div>
                    </form>
                    <div class="row mb-4" id="company-course-container">
                        <!-- Company -->
                        <div class="col-md-6 d-flex align-items-center">
                            <div class="flex-grow-1">
                                <label for="company" class="fw-bold mb-2 required">Company</label>
                                <select id="company" class="form-select form-select-solid">
                                    <option>Select Company</option>
                                </select>
                            </div>
                            {{-- form repeater --}}
                            <button type="button" class="btn btn-primary ms-3 mt-8">
                                <i class="bi bi-plus-lg"></i>
                            </button>
                        </div>

                        <!-- Course -->
                        <div class="col-md-6 d-flex align-items-center">
                            <div class="flex-grow-1">
                                <label for="course" class="fw-bold mb-2 required">Course</label>
                                <select id="course" class="form-select form-select-solid">
                                    <option>Select Course</option>
                                </select>
                            </div>
                            {{-- form repeater --}}
                            <button type="button" class="btn btn-primary ms-3 mt-8">
                                <i class="bi bi-plus-lg"></i>
                            </button>
                        </div>
                    </div>





                    <!-- Date and Time -->
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <label for="date" class="fw-bold mb-2 required">Date</label>
                            <input type="date" id="date" class="form-control form-control-solid">
                        </div>
                        <div class="col-md-4">
                            <label for="time-start" class="fw-bold mb-2 required">Time Start </label>
                            <input type="time" id="time-start" class="form-control form-control-solid">
                        </div>
                        <div class="col-md-4">
                            <label for="time-end" class="fw-bold mb-2 required">Time End</label>
                            <input type="time" id="time-end" class="form-control form-control-solid">
                        </div>
                    </div>

                    <!-- Facilitator and Assistant -->
                    <div class="row mb-5">
                        <div class="col-md-6 d-flex align-items-lg-end">
                            <div class="flex-grow-1">
                                <label for="facilitator" class="fw-bold mb-2 required">Facilitator</label>
                                <select id="facilitator" class="form-select form-select-solid">
                                    <option>Select Facilitator</option>
                                </select>
                            </div>

                            {{-- form repeater --}}
                            <button type="button" class="btn btn-primary ms-3 mt-6">
                                <i class="bi bi-plus-lg"></i>
                            </button>
                        </div>

                        <div class="col-md-6 d-flex align-items-lg-end">
                            <div class="flex-grow-1">
                                <label for="assistant" class="fw-bold mb-2 required">Assistant </label>
                                <select id="assistant" class="form-select form-select-solid">
                                    <option>Select Assistant</option>
                                </select>
                            </div>
                            {{-- form repeater --}}
                            <button type="button" class="btn btn-primary ms-3 mt-4">
                                <i class="bi bi-plus-lg"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Buttons -->
                    <div class="d-flex justify-content-center gap-5 ">
                        <button type="button" class="btn btn-light fw-boldest">CANCEL</button>
                        <button type="submit" class="btn btn-success fw-boldest">SAVE</button>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script src="{{ asset('js/add_training.js') }}"></script>
@endpush
