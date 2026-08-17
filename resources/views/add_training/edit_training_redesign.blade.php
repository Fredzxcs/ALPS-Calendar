@extends('global.layout')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<link rel="stylesheet" href="{{ asset('css/training-ui-redesign.css') }}">

@section('maincontent')
    <div class="training-page-wrapper">
        <div class="training-card-container training-form-shell training-form-shell--loading">
            <!-- Header -->
            <div class="training-header training-header-edit">
                Edit Training
            </div>

            <!-- Step Indicators -->
            <div class="training-step-indicators">
                <div class="training-step active" id="step-1-indicator">
                    <div class="training-step-badge">1</div>
                    <span>Step 1<br><small style="font-size: 0.8rem;">Training Details</small></span>
                </div>
                <div class="training-step" id="step-2-indicator">
                    <div class="training-step-badge">2</div>
                    <span>Step 2<br><small style="font-size: 0.8rem;">Account Creation</small></span>
                </div>
            </div>

            <!-- Form Content -->
            <div class="training-form-content">
                <form>
                    @php
                        $currentUser = auth()->user();
                        $googleConnected = ($currentUser instanceof \App\Models\User && !empty($currentUser->google_refresh_token)) || session('google_connected', false);
                    @endphp

                    <!-- STEP 1: Training Details -->
                    <div id="training-step-1">
                        <!-- Training Details Section -->
                        <div style="margin-bottom: 1.5rem;">
                            <div class="training-section-heading">Training Details</div>
                            <div class="training-section-subheading">Review and modify training information.</div>
                        </div>

                        <!-- Mode of Training -->
                        <div class="training-form-row full">
                            <div class="training-form-group">
                                <label>Mode of Training <span class="required">*</span></label>
                                <div class="training-radio-group">
                                    <div class="training-radio">
                                        <input type="radio" id="virtual" value="virtual" name="mode" 
                                            {{ $training->mode == 'virtual' ? 'checked' : '' }}>
                                        <label for="virtual">Virtual</label>
                                    </div>
                                    <div class="training-radio">
                                        <input type="radio" id="face-to-face" value="face-to-face" name="mode"
                                            {{ $training->mode == 'face-to-face' ? 'checked' : '' }}>
                                        <label for="face-to-face">Face-to-Face</label>
                                    </div>
                                    <div class="training-radio">
                                        <input type="radio" id="public-course" value="public-course" name="mode"
                                            {{ $training->mode == 'public-course' ? 'checked' : '' }}>
                                        <label for="public-course">Public Course</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Account and Platform -->
                        <div class="training-form-row" id="credentials-container">
                            <div class="training-form-group">
                                <label for="credentials">Account <span class="required">*</span></label>
                                <select id="credentials" class="training-select">
                                    <option value="" disabled>Select Host Email Account</option>
                                    @foreach ($accounts as $account)
                                        <option value="{{ $account->id }}" 
                                            {{ $training->account_id == $account->id ? 'selected' : '' }}>
                                            {{ $account->account_email }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="training-form-group">
                                <label for="platform">Platform</label>
                                <select id="platform" class="training-select">
                                    <option value="" disabled selected>Select Platform</option>
                                    <option value="Zoom" {{ $training->platform == 'Zoom' ? 'selected' : '' }}>Zoom</option>
                                    <option value="Google Meet" {{ $training->platform == 'Google Meet' ? 'selected' : '' }}>Google Meet</option>
                                    <option value="MS Teams" {{ $training->platform == 'MS Teams' ? 'selected' : '' }}>MS Teams</option>
                                    <option value="other" {{ $training->platform && !in_array($training->platform, ['Zoom', 'Google Meet', 'MS Teams']) ? 'selected' : '' }}>Other</option>
                                </select>
                                <input type="text" name="platform_other" id="platform_other" class="training-input" 
                                    style="margin-top: 0.5rem;"
                                    placeholder="Enter platform name"
                                    value="{{ $training->platform && !in_array($training->platform, ['Zoom', 'Google Meet', 'MS Teams']) ? $training->platform : '' }}"
                                    {{ $training->platform && !in_array($training->platform, ['Zoom', 'Google Meet', 'MS Teams']) ? '' : 'class=d-none' }}>
                            </div>
                        </div>

                        <!-- Virtual Training Link -->
                        <div class="training-form-row full" id="conference-link-container">
                            <div class="training-form-group">
                                <label for="conference_link"><span id="conference-link-label">Virtual Training Link</span><span id="conference-link-required" class="required d-none">*</span></label>
                                <input type="url" name="conference_link" id="conference_link" class="training-input"
                                    placeholder="Enter the virtual training link (e.g., https://zoom.us/j/...)"
                                    value="{{ $training->conference_link ?? '' }}">
                            </div>
                        </div>

                        <!-- Location: Face-to-Face -->
                        <div class="training-form-row full" id="location-container">
                            <div class="training-form-group">
                                <label for="location">Location <span class="required">*</span></label>
                                <input type="text" id="location" class="training-input"
                                    placeholder="Enter Location"
                                    value="{{ $training->location ?? '' }}">
                            </div>
                        </div>

                        <!-- Company and Course -->
                        <div class="training-form-row" id="company-course-container">
                            <div class="training-form-group">
                                <label for="company">Company <span class="required">*</span></label>
                                <select id="company" class="training-select">
                                    <option value="" disabled>Select Company</option>
                                    @foreach ($companies as $company)
                                        <option value="{{ $company->id }}"
                                            {{ $training->company_id == $company->id ? 'selected' : '' }}>
                                            {{ $company->company_name }}
                                        </option>
                                    @endforeach
                                    <option value="other" {{ $training->company_id && !$companies->contains($training->company_id) ? 'selected' : '' }}>Other</option>
                                </select>
                                <input type="text" id="enter-company" class="training-input" 
                                    style="margin-top: 0.5rem;" placeholder="Enter Company"
                                    value="{{ $training->company_id && !$companies->contains($training->company_id) ? $training->company_name : '' }}"
                                    {{ $training->company_id && !$companies->contains($training->company_id) ? '' : 'class=d-none' }}>
                            </div>

                            <div class="training-form-group">
                                <label for="course">Course <span class="required">*</span></label>
                                <select id="course" class="training-select">
                                    <option value="" disabled>Select Course</option>
                                    @foreach ($courses as $course)
                                    <option value="{{ $course->id }}"
                                        {{ $training->course_id == $course->id ? 'selected' : '' }}>
                                        {{ $course->course_code ? $course->course_code . ' - ' : '' }}{{ $course->course_name }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- Date and Time -->
                        <div class="training-form-row triple">
                            <div class="training-form-group">
                                <label for="date-range">Date Range <span class="required">*</span></label>
                                <input type="text" id="date-range" class="training-input" placeholder="Select Date" readonly 
                                    value="{{ isset($training) ? \Carbon\Carbon::parse($training->from_date)->format('m-d-Y') . ' to ' . \Carbon\Carbon::parse($training->to_date)->format('m-d-Y') : '' }}">
                            </div>
                            <div class="training-form-group">
                                <label for="time-start">Time Start <span class="required">*</span></label>
                                <input type="time" id="time-start" class="training-input"
                                    value="{{ isset($training->from_time) ? date('H:i', strtotime($training->from_time)) : '' }}">
                            </div>
                            <div class="training-form-group">
                                <label for="time-end">Time End <span class="required">*</span></label>
                                <input type="time" id="time-end" class="training-input"
                                    value="{{ isset($training->to_time) ? date('H:i', strtotime($training->to_time)) : '' }}">
                            </div>
                        </div>

                        <!-- Google Calendar Card -->
                        <div class="google-calendar-card">
                            <h3>Google Calendar Interaction (Active)</h3>
                            <p>All invitations for this session will be sent and managed from:</p>
                            <div style="background: #f3f4f6; padding: 1rem; border-radius: 0.5rem; margin: 1rem 0; font-size: 0.9rem;">
                                <strong>{{ $training->account_email ?? 'admin.user@alprograms.local' }}</strong><br>
                                <small style="color: #64748b;">(Signed-in Google Account)</small>
                            </div>
                            <div style="font-size: 0.85rem; color: #64748b;">Receipts will see this account as the event organizer</div>
                        </div>

                        <!-- Facilitator and Assistant -->
                        <div class="training-form-row">
                            <div class="training-form-group">
                                <label for="facilitator">Facilitator</label>
                                <select id="facilitator" class="training-select">
                                    <option selected>Select Facilitator</option>
                                    <option value="">No Facilitator Yet</option>
                                    @foreach ($users as $user)
                                        <option value="{{ $user->id }}"
                                            {{ $training->facilitator_id == $user->id ? 'selected' : '' }}>
                                            {{ $user->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="training-form-group">
                                <label for="assistant_select">Assistant</label>
                                <div style="display: flex; gap: 0.5rem;">
                                    <select id="assistant_select" class="training-select" style="flex: 1;">
                                        <option value="" selected disabled>Select Assistant</option>
                                        @foreach ($users as $user)
                                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                                        @endforeach
                                    </select>
                                    <button type="button" id="assistant_add_btn" class="training-btn training-btn-secondary-blue">ADD</button>
                                </div>
                                <div id="assistant_list_container" style="margin-top: 0.75rem;">
                                    @if($training->assistants)
                                        @foreach($training->assistants as $assistant)
                                            <div class="d-flex align-items-center mb-2">
                                                <span style="padding: 0.5rem 1rem; background: #dbeafe; border-radius: 0.5rem; font-size: 0.9rem; flex: 1;">
                                                    {{ $assistant->name }} <span class="remove-assistant-badge" style="cursor: pointer; margin-left: 0.5rem;">✕</span>
                                                </span>
                                            </div>
                                        @endforeach
                                    @endif
                                </div>
                                <input type="hidden" id="assistant_list" value="{{ $training->assistants ? implode(', ', $training->assistants->pluck('id')->toArray()) : '' }}">
                                <div class="training-helper-text">Select one assistant and click Add to include multiple assistants.</div>
                            </div>
                        </div>
                    </div>

                    <!-- STEP 2: Account Creation (for Edit) -->
                    <div id="training-step-2" class="d-none">
                        <div style="margin-bottom: 1.5rem;">
                            <div class="training-section-heading">Account Creation</div>
                            <div class="training-section-subheading">Finalize your training setup.</div>
                        </div>

                        <div class="training-form-row full">
                            <div class="training-form-group">
                                <label>Confirm all details and save changes</label>
                                <p style="color: #64748b; font-size: 0.95rem;">Review your training information above and click "Save" to update the training event.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Buttons -->
                    <div class="training-button-group">
                        <a href="{{ route('calendar') }}" class="training-btn training-btn-secondary">CANCEL</a>
                        <button type="button" id="add_training_back" class="training-btn training-btn-secondary-blue d-none">BACK</button>
                        <button type="button" id="add_training_submit" class="training-btn training-btn-primary">CONTINUE</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script>
        window.isEditMode = true;
        window.trainingId = {{ $training->id }};
    </script>
    <script src="{{ asset('js/add_training.js') }}"></script>
@endpush
