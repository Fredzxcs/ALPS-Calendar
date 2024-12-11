@extends('layout.layout')

@section('maincontent')
<div class="container-fluid mt-4 d-flex flex-wrap gap-4">
    <!-- Left Side: Search Course and Search Trainer -->
    <div class="d-flex flex-column" style="flex: 1; max-width: 30%; gap: 20px;">
        <!-- Search Course Card -->
        <div class="card shadow-sm">
            <div class="card-body">
                <div class="mb-3">
                    <input type="text" class="form-control form-control-solid" placeholder="Search course" />
                </div>
                <button class="btn btn-sm mb-3" style="background-color: #7c0101; color: #ffffff;">+ Add Course</button>
                <ul class="list-unstyled">
                    <li>
                        <input type="checkbox" id="course1" /> <label for="course1">Project Management</label>
                    </li>
                    <li>
                        <input type="checkbox" id="course2" /> <label for="course2">Agile Scrum</label>
                    </li>
                    <li>
                        <input type="checkbox" id="course3" /> <label for="course3">Sample if mahabang cour...</label>
                    </li>
                    <li>
                        <input type="checkbox" id="course3" /> <label for="course3">Course 1</label>
                    </li>
                    <li>
                        <input type="checkbox" id="course3" /> <label for="course3">Course 2</label>
                    </li>
                    <li>
                        <input type="checkbox" id="course3" /> <label for="course3">Course 3</label>
                    </li>
                    <li>
                        <input type="checkbox" id="course3" /> <label for="course3">Course 3</label>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Search Trainer Card -->
        <div class="card shadow-sm">
            <div class="card-body">
                <div class="mb-3">
                    <input type="text" class="form-control form-control-solid" placeholder="Search facilitator" />
                </div>
                <button class="btn btn-sm mb-3" style="background-color: #7c0101; color: #ffffff;">+ Add Facilitator</button>
                <ul class="list-unstyled">
                    <li>
                        <input type="checkbox" id="trainer1" /> <label for="trainer1">Rechelle Salas</label>
                    </li>
                    <li>
                        <input type="checkbox" id="trainer2" /> <label for="trainer2">Kimberly Mae Kho</label>
                    </li>
                    <li>
                        <input type="checkbox" id="trainer3" /> <label for="trainer3">Rafael Joar Parungo</label>
                    </li>
                    <li>
                        <input type="checkbox" id="trainer4" /> <label for="trainer3">John Loyd Cabral</label>
                    </li>
                    <li>
                        <input type="checkbox" id="trainer5" /> <label for="trainer3">Daniel Del Rosario</label>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Right Side: Calendar -->
    <div class="card shadow-sm" style="flex: 2;">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div>
            <input type="text" class="form-control form-control-solid" placeholder="Search..." />
            </div>
            <button class="btn btn-primary">+ Add</button>
        </div>
        <div class="card-body" style="height: 80vh;">
            <div id="calendar" class="bg-light border" style="height: 100%; border-radius: 5px;">
                <!-- FullCalendar will be rendered here -->
            </div>
        </div>
    </div>

</div>
@endsection
@push('scripts')
<script src="{{ asset('js/calendar.js') }}"></script>
@endpush
