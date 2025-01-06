@extends('global.layout')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">


@section('maincontent')
    <div class="d-flex justify-content-center align-items-center mt-20 ">
        <div class="container mt-5 ">
            <!-- Card -->
            <div class="card shadow-sm rounded-3">
                <!-- Title -->
                <div class="d-flex justify-content-center align-items-center mb-0 bg-primary rounded-top h-80px" >
                    <h2 class="text-white fw-boldest m-0 fs-1">ADD TRAINING</h2>
                </div>
                <!-- Form -->
                <div class="p-20 pt-10 pb-6 ">
                    <form>
                        <!-- Mode of Training -->
                        <div class="mb-4">
                            <label class="fw-bold mb-2 required">Mode of Training</label>
                            <div class="d-flex gap-4">
                                <div>
                                    <input type="radio" id="virtual" value="virtual" name="mode" class="form-check-input" checked="checked">
                                    <label for="virtual" class="form-check-label">Virtual</label>
                                </div>
                                <div>
                                    <input type="radio" id="face-to-face" value="face-to-face" name="mode" class="form-check-input">
                                    <label for="face-to-face" class="form-check-label">Face-to-Face</label>
                                </div>
                                <div>
                                    <input type="radio" id="public-course" value="public-course" name="mode" class="form-check-input">
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
                                    <label for="inperson-training" class="form-check-label fw-bold">In-person
                                        training?</label>
                                </div>
                                <!-- Course -->
                                {{-- need form repeater --}}
                                <div class="d-flex align-items-center" style="width: 49%;">
                                    <div class="flex-grow-1">
                                        <label for="public-course-select" class="fw-bold mb-2 required">Course</label>
                                        <select id="public-course-select" class="form-select form-select-solid">
                                            <option value="" disabled selected>Select Course</option>
                                            <option value="Advanced Excel Training">Advanced Excel Training</option>
                                            <option value="Advanced MS Powerpoint Course">Advanced MS Powerpoint Course</option>
                                            <option value="Advanced Project Management Training Course">Advanced Project Management Training Course</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Email and Password -->
                        <div class="row mb-4" id="credentials-container">
                            <div class="flex-grow-1">
                                <label for="credentials" class="fw-bold mb-2 required">Account</label>
                                <select id="credentials" class="form-select form-select-solid">
                                    <option value="" disabled selected>Select Account to Host Training</option>
                                    <option value="samplezoomaccountpassword">alpszoomaccount1@gmail.com</option>
                                </select>
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
                        {{-- need form repeater --}}
                        <div class="col-md-6 d-flex align-items-center">
                            <div class="flex-grow-1">
                                <label for="company" class="fw-bold mb-2 required">Company</label>
                                <select id="company" class="form-select form-select-solid">
                                    <option value="" disabled selected>Select Company</option>
                                    <option value="PhilHealth">PhilHealth</option>
                                    <option value="Pag-ibig">Pag-ibig</option>
                                    <option value="DOST">DOST</option>
                                </select>
                            </div>
                        </div>

                        <!-- Course -->
                        {{-- need form repeater --}}
                        <div class="col-md-6 d-flex align-items-center">
                            <div class="flex-grow-1">
                                <label for="course" class="fw-bold mb-2 required">Course</label>
                                <select id="course" class="form-select form-select-solid">
                                    <option value="" disabled selected>Select Course</option>
                                    <option value="Advanced Excel Training">Advanced Excel Training</option>
                                    <option value="Advanced MS Powerpoint Course">Advanced MS Powerpoint Course</option>
                                    <option value="Advanced Project Management Training Course">Advanced Project Management Training Course</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Date and Time -->
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <label for="date-range" class="fw-bold mb-2 required">Date Range</label>
                            <div class="position-relative">
                                <input type="text" id="date-range" class="form-control form-control-solid pe-5">
                                <span class="position-absolute top-50 end-0 translate-middle-y me-3 text-muted">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-calendar-heart-fill" viewBox="0 0 16 16">
                                        <path d="M4 .5a.5.5 0 0 0-1 0V1H2a2 2 0 0 0-2 2v1h16V3a2 2 0 0 0-2-2h-1V.5a.5.5 0 0 0-1 0V1H4zM16 14V5H0v9a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2M8 7.993c1.664-1.711 5.825 1.283 0 5.132-5.825-3.85-1.664-6.843 0-5.132"/>
                                    </svg>
                                </span>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label for="time-start" class="fw-bold mb-2 required">Time Start</label>
                            <input type="time" id="time-start" class="form-control form-control-solid">
                        </div>
                        <div class="col-md-4">
                            <label for="time-end" class="fw-bold mb-2 required">Time End</label>
                            <input type="time" id="time-end" class="form-control form-control-solid">
                        </div>
                    </div>

                    <!-- Facilitator and Assistant -->
                    <div class="row mb-5 align-items-start">
                        <!-- Facilitator -->
                        <div class="col-md-6">
                            <label for="facilitator" class="fw-bold mb-2 required">Facilitator</label>
                            <select id="facilitator" class="form-select form-select-solid">
                                <option disabled selected>Select Facilitator</option>
                                <option value="">No Facilitator Yet</option>
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Assistant -->
                        <div class="col-md-6">
                            <!-- Repeater -->
                            <div id="asst_repeat">
                                <div class="form-group">
                                    <!-- Label for the Assistant field -->
                                    <label class="form-label required">Assistant:</label>

                                    <!-- Repeater List -->
                                    <div data-repeater-list="asst_repeat">
                                        <div data-repeater-item>
                                            <div class="form-group row align-items-center">
                                                <div class="col-md-9">
                                                    <input type="text" class="form-control form-control-solid mb-3" id="assistant" placeholder="Enter Assistant's Name" />
                                                </div>
                                                <div class="col-md-3">
                                                    <a href="javascript:;" data-repeater-delete class="btn btn-sm btn-light-danger mb-3">
                                                        <i class="la la-trash-o"></i> DELETE
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Add Button -->
                                <div class="form-group mt-3">
                                    <a href="javascript:;" data-repeater-create class="btn btn-light-primary btn-sm">
                                        <i class="la la-plus"></i> ADD
                                    </a>
                                </div>
                            </div>
                            <!-- End Repeater -->

                        </div>
                    </div>


                    <!-- Buttons -->
                    <div class="d-flex justify-content-center gap-5 ">
                        <button type="button" class="btn btn-light fw-boldest">CANCEL</button>
                        <button type="button" id="add_training_submit" class="btn btn-success fw-boldest">SAVE</button>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="{{ asset('js/add_training.js') }}"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="{{ asset('plugins/custom/formrepeater/formrepeater.bundle.js') }}"></script>


    <script>
        $('#asst_repeat').repeater({
            initEmpty: false,

            defaultValues: {
                'text-input': 'foo'
            },

            show: function () {
                $(this).slideDown();
            },

            hide: function (deleteElement) {
                $(this).slideUp(deleteElement);
            }
        });
    </script>
@endpush
