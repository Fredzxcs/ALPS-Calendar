@extends('layout.layout')

@section('maincontent')
    <div class="d-flex justify-content-center align-items-center mt-20">
        <div class="container mt-5">
            <!-- Card -->
            <div class="card shadow-sm rounded-3">
                <!-- Title -->
                <div class="d-flex justify-content-center align-items-center mb-4 rounded-top"
                    style="background-color: #007bff; height: 80px;">
                    <h2 class="text-white fw-boldest m-0 fs-1">ADD TRAINING</h2>
                </div>

                <!-- Form -->
                <div class="p-5">
                    <form>
                        <!-- Mode of Training -->
                        <div class="mb-4">
                            <label class="fw-bold mb-2">Mode of Training <span class="text-danger">*</span></label>
                            <div class="d-flex gap-4">
                                <div class="form-check">
                                    <input type="radio" id="virtual" name="mode" class="form-check-input">
                                    <label for="virtual" class="form-check-label">Virtual</label>
                                </div>
                                <div class="form-check">
                                    <input type="radio" id="face-to-face" name="mode" class="form-check-input">
                                    <label for="face-to-face" class="form-check-label">Face-to-Face</label>
                                </div>
                                <div class="form-check">
                                    <input type="radio" id="public-course" name="mode" class="form-check-input">
                                    <label for="public-course" class="form-check-label">Public Course</label>
                                </div>
                            </div>
                        </div>

                        <!-- In-person Training and Course Row -->
                        <div class="row mb-4 align-items-center">
                            <!-- In-person Training Checkbox -->
                            <div class="col-md-6 d-flex align-items-center">
                                <div class="form-check">
                                    <input type="checkbox" id="in-person" class="form-check-input">
                                    <label for="in-person" class="form-check-label fw-bold ms-2">In-person training?</label>
                                </div>
                            </div>

                            <!-- Course Field with Add Button -->
                            <div class="col-md-6 d-flex align-items-center">
                                <div class="flex-grow-1 me-2">
                                    <label for="course" class="fw-bold mb-2">Course <span
                                            class="text-danger">*</span></label>
                                    <select id="course" class="form-select form-select-solid">
                                        <option>Select Course</option>
                                    </select>
                                </div>
                                <button type="button" class="btn btn-primary btn-sm" style="height: 35px; width: 35px;">
                                    <i class="bi bi-plus-lg text-white"></i>
                                </button>
                            </div>
                        </div>


                        <!-- Location -->
                        <div class="col-md-12">
                            <label for="location" class="fw-bold mb-2">Location <span class="text-danger">*</span></label>
                            <input type="text" id="location" class="form-control form-control-solid" placeholder="Enter Location">
                        </div>

                        <!-- Date and Time -->
                        <div class="row mb-4">
                            <div class="col-md-4">
                                <label for="date" class="fw-bold mb-2">Date <span class="text-danger">*</span></label>
                                <input type="date" id="date" class="form-control form-control-solid">
                            </div>
                            <div class="col-md-4">
                                <label for="time-start" class="fw-bold mb-2">Time Start <span
                                        class="text-danger">*</span></label>
                                <input type="time" id="time-start" class="form-control form-control-solid">
                            </div>
                            <div class="col-md-4">
                                <label for="time-end" class="fw-bold mb-2">Time End <span
                                        class="text-danger">*</span></label>
                                <input type="time" id="time-end" class="form-control form-control-solid">
                            </div>
                        </div>

                        <!-- Facilitator and Assistant -->
                        <div class="row mb-5">
                            <div class="col-md-6 d-flex align-items-center">
                                <div class="flex-grow-1">
                                    <label for="facilitator" class="fw-bold mb-2">Facilitator <span
                                            class="text-danger">*</span></label>
                                    <select id="facilitator" class="form-select form-select-solid">
                                        <option>Select Facilitator</option>
                                    </select>
                                </div>
                                <button type="button" class="btn btn-primary ms-2" style="height: 40px; width: 40px;">
                                    <i class="bi bi-plus-square text-white"></i>
                                </button>
                            </div>
                            <div class="col-md-6 d-flex align-items-center">
                                <div class="flex-grow-1">
                                    <label for="assistant" class="fw-bold mb-2">Assistant <span
                                            class="text-danger">*</span></label>
                                    <select id="assistant" class="form-select form-select-solid">
                                        <option>Select Assistant</option>
                                    </select>
                                </div>
                                <button type="button" class="btn btn-primary ms-2" style="height: 40px; width: 40px;">
                                    <i class="bi bi-plus-square text-white"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Buttons -->
                        <div class="d-flex justify-content-end gap-3">
                            <button type="button" class="btn btn-light fw-bold">CANCEL</button>
                            <button type="submit" class="btn btn-success fw-bold">SAVE</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
