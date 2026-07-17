var csrfToken = $('meta[name="csrf-token"]').attr('content');
// Global assistants list so it is accessible across handlers
window.assistantsList = window.assistantsList || [];

// Stub functions for draft persistence (removed functionality)
function saveTrainingDraft() {
    // Draft persistence functionality has been removed
}

function restoreTrainingDraft() {
    // Draft persistence functionality has been removed
}

function updateAccountFieldState() {
    const selectedPlatform = ($('#platform').val() || '').trim();
    const credentialsContainer = $('#credentials-container');

    if (selectedPlatform === 'Zoom') {
        credentialsContainer.removeClass('d-none');
    } else {
        credentialsContainer.addClass('d-none');
        $('#credentials').val('');
    }
}

document.addEventListener("DOMContentLoaded", function () {
    const modeRadios = document.querySelectorAll('input[name="mode"]');
    const locationContainer = document.getElementById("location-container");
    const inpersonCheckbox = document.getElementById("inperson-training");
    const companyCourseContainer = document.getElementById("company-course-container");
    const publicCourseContainer = document.getElementById("public-course-container");
    const platformContainer = document.getElementById("platform-container");
    const conferenceLinkContainer = document.getElementById("conference-link-container");
    const conferenceLinkRequired = document.getElementById("conference-link-required");
    const conferenceLinkInput = document.getElementById("conference_link");

    function shouldHidePlatformField() {
        const selectedMode = document.querySelector('input[name="mode"]:checked')?.id;
        return selectedMode === 'face-to-face' || (selectedMode === 'public-course' && inpersonCheckbox?.checked);
    }

    function updatePlatformState() {
        if (!platformContainer) {
            return;
        }

        if (shouldHidePlatformField()) {
            platformContainer.classList.add('d-none');
            $('#platform').val('').trigger('change');
            $('#platform_other').addClass('d-none').val('');
        } else {
            platformContainer.classList.remove('d-none');
        }

        updateAccountFieldState();
    }

    // Update conference link visibility and requirement based on mode and in-person status
    function updateConferenceLinkState() {
        const selectedMode = document.querySelector('input[name="mode"]:checked')?.id;
        
        if (selectedMode === "face-to-face") {
            // Hide for Face-to-Face
            conferenceLinkContainer.classList.add("d-none");
            conferenceLinkRequired.classList.add("d-none");
            conferenceLinkInput.removeAttribute("required");
        } else if (selectedMode === "virtual") {
            // Show for Virtual, not required
            conferenceLinkContainer.classList.remove("d-none");
            conferenceLinkRequired.classList.add("d-none");
            conferenceLinkInput.removeAttribute("required");
        } else if (selectedMode === "public-course") {
            // For Public Course, show/hide based on in-person checkbox
            const isInPerson = inpersonCheckbox.checked;
            if (isInPerson) {
                // In-person training is checked, hide conference link
                conferenceLinkContainer.classList.add("d-none");
                conferenceLinkRequired.classList.add("d-none");
                conferenceLinkInput.removeAttribute("required");
            } else {
                // In-person training is unchecked, show conference link (not required, no asterisk)
                conferenceLinkContainer.classList.remove("d-none");
                conferenceLinkRequired.classList.add("d-none");
                conferenceLinkInput.removeAttribute("required");
            }
        }

        updatePlatformState();
        updateAccountFieldState();
    }

    // Disable driver arrangement for unsupported modes (Virtual OR Public Course without in-person)
    function updateDriverArrangementSupport() {
        const mode = $('input[name="mode"]:checked').val();
        const inperson = $('#inperson-training').is(':checked');
        const supported = !(mode === 'virtual' || (mode === 'public-course' && !inperson));

        if (!supported) {
            // Disable transportation radios and hide driver fields
            $('input[name="need_transportation"]').prop('disabled', true).prop('checked', false);
            $('#driver-arrangement-fields').addClass('d-none');

            const msg = 'Driver arrangement is not supported for online events. Please select a different event type to enable driver arrangement.';
            if ($('#driver-arrangement-disabled-message').length === 0) {
                // Insert message at top of Step 2 container
                $('#training-step-2').prepend(`<div id="driver-arrangement-disabled-message" class="training-helper-text" style="color:#6b7280; margin-bottom:1rem;">${msg}</div>`);
            } else {
                $('#driver-arrangement-disabled-message').text(msg).show();
            }
        } else {
            $('input[name="need_transportation"]').prop('disabled', false);
            $('#driver-arrangement-disabled-message').hide();
        }
    }

    // Mode of Training Logic
    modeRadios.forEach(radio => {
        radio.addEventListener("change", function () {
            if (radio.id === "virtual") {
                // Virtual: Show Email/Password, hide others
                locationContainer.classList.add("d-none");
                publicCourseContainer.classList.add("d-none");
                companyCourseContainer.classList.remove("d-none");
            } else if (radio.id === "face-to-face") {
                // Face-to-Face: Show Location, hide Email/Password
                locationContainer.classList.remove("d-none");
                publicCourseContainer.classList.add("d-none");
                companyCourseContainer.classList.remove("d-none");
            } else if (radio.id === "public-course") {
                // Public Course: Show Public Course layout, hide Company/Course
                publicCourseContainer.classList.remove("d-none");
                companyCourseContainer.classList.add("d-none");
                locationContainer.classList.add("d-none");
            }
            updateConferenceLinkState();
            updateDriverArrangementSupport();
            updatePlatformState();
            updateAccountFieldState();
        });
    });

    // In-person Checkbox Logic
    inpersonCheckbox.addEventListener("change", function () {
        const selectedMode = document.querySelector('input[name="mode"]:checked')?.id;
        
        if (inpersonCheckbox.checked) {
            locationContainer.classList.remove("d-none");
        } else {
            locationContainer.classList.add("d-none");
        }
        
        // Update conference link when in-person status changes
        if (selectedMode === "public-course") {
            updateConferenceLinkState();
            updateDriverArrangementSupport();
        }
        updatePlatformState();
        updateAccountFieldState();
    });

    // Initialize conference link state
    updateConferenceLinkState();
    updateDriverArrangementSupport();
    updatePlatformState();
    updateAccountFieldState();
});

function formatDate(date) {
    const day = date.getDate().toString().padStart(2, '0'); // Add leading zero for day
    const month = (date.getMonth() + 1).toString().padStart(2, '0'); // Get month, adjust by +1 (months are 0-based)
    const year = date.getFullYear(); // Get full year

    return `${year}-${month}-${day}`; // Return in YYYY-MM-DD format
}

let startDateFormatted = '';
let endDateFormatted = '';

function syncDateRangeStateFromInput(datePickerInstance) {
    if (datePickerInstance && Array.isArray(datePickerInstance.selectedDates) && datePickerInstance.selectedDates.length >= 2) {
        startDateFormatted = formatDate(datePickerInstance.selectedDates[0]);
        endDateFormatted = formatDate(datePickerInstance.selectedDates[1]);
        return;
    }

    const rawValue = ($('#date-range').val() || '').trim();
    if (!rawValue) {
        return;
    }

    const parts = rawValue.split(' to ');
    if (parts.length !== 2) {
        return;
    }

    const startDate = flatpickr.parseDate(parts[0].trim(), 'm-d-Y');
    const endDate = flatpickr.parseDate(parts[1].trim(), 'm-d-Y');

    if (startDate && endDate) {
        startDateFormatted = formatDate(startDate);
        endDateFormatted = formatDate(endDate);
    }
}

const isEditMode = Boolean(window.isEditMode && window.trainingId);

// 1. Grab the existing value from the input field
const existingDateString = document.getElementById('date-range').value;

const dateRangeOptions = {
    mode: "range",
    dateFormat: "m-d-Y",
    // 2. Add this line: tell Flatpickr to use the existing dates as the default
    defaultDate: existingDateString ? existingDateString.split(' to ') : null, 
    onChange: function (selectedDates) {
        if (selectedDates.length >= 2) {
            const initialStartDate = selectedDates[0];
            const initialEndDate = selectedDates[1];
            startDateFormatted = formatDate(initialStartDate);
            endDateFormatted = formatDate(initialEndDate);
            console.log("Start Date:", startDateFormatted);
            console.log("End Date:", endDateFormatted);
        }

        saveTrainingDraft();
    }
};

if (!isEditMode) {
    dateRangeOptions.minDate = "today";
}

const fp = flatpickr("#date-range", dateRangeOptions);

syncDateRangeStateFromInput(fp);

let trainingStepper = null;

// Global variable to track current step
let trainingWizardStep = 1;

$(document).ready(function (e) {
    const stepperElement = document.querySelector('#kt_stepper_example_basic');
    if (stepperElement) {
        trainingStepper = new KTStepper(stepperElement);
        console.log('KTStepper initialized:', trainingStepper);

        // Let KTStepper handle next button natively - just validate
        trainingStepper.on('kt.stepper.next', function (stepper) {
            console.log('kt.stepper.next event triggered, current step:', stepper.getCurrentStepIndex());
            
            if (stepper.getCurrentStepIndex() === 1 && !validateStep1()) {
                Swal.fire({
                    title: 'Missing Fields!',
                    text: 'Please fill in all required training details first.',
                    icon: 'warning',
                    confirmButtonText: 'OK'
                });
                return false; // Prevent step change
            }
        });

        trainingStepper.on('kt.stepper.previous', function (stepper) {
            console.log('kt.stepper.previous event triggered');
        });

        trainingStepper.on('kt.stepper.changed', function (stepper) {
            trainingWizardStep = stepper.getCurrentStepIndex();
            console.log('Step changed to:', trainingWizardStep);
        });
    } else {
        console.error('Stepper element not found!');
    }

    // Stacked assistant selects: add a new select field above existing ones and keep hidden input synced
    function updateAssistantHidden() {
        const vals = [];
        $('#assistant_list_container .assistant-item').each(function () {
            const id = $(this).data('id');
            if (id) vals.push(id);
        });
        $('#assistant_list').val(vals.join(', '));
    }

    function updateCoordinatorHidden() {
        const vals = [];
        $('#coordinator_list_container .coordinator-item').each(function () {
            const id = $(this).data('id');
            if (id) vals.push(id);
        });
        $('#coordinator_to_notify_list').val(vals.join(', '));
    }

    // Platform dropdown: show/hide "Other" text input
    $(document).on('change', '#platform', function () {
        const selectedVal = $(this).val();
        if (selectedVal === 'other') {
            $('#platform_other').removeClass('d-none');
        } else {
            $('#platform_other').addClass('d-none').val('');
        }
        updateAccountFieldState();
    });

    // Add assistant button handler: create pill and add to container
    $(document).on('click', '#assistant_add_btn', function (ev) {
        ev.preventDefault();
        console.log('=== ADD ASSISTANT BUTTON CLICKED ===');
        const val = $('#assistant_select').val();
        const selectedText = $('#assistant_select option:selected').text();
        console.log('Selected value:', val, 'Selected text:', selectedText);
        
        if (!val) {
            console.log('No value selected, returning');
            return;
        }

        // Check for duplicates
        const existing = [];
        $('#assistant_list_container .assistant-item').each(function () { 
            existing.push($(this).data('id')); 
        });
        console.log('Existing assistants:', existing);
        if (existing.includes(val)) {
            console.log('Assistant already added, skipping');
            return;
        }

        // Create pill
        console.log('Creating pill for:', selectedText, 'with id:', val);
        const pill = $(`<div class="assistant-item" data-id="${val}" style="background: #dbeafe; border: 1px solid #bfdbfe; color: #1e40af; padding: 0.5rem 0.75rem; border-radius: 9999px; display: inline-flex; align-items: center; gap: 0.5rem; font-size: 0.875rem; font-weight: 500; white-space: nowrap;">
            <span>${selectedText}</span>
            <button type="button" class="remove-assistant" data-id="${val}" style="background: transparent; border: none; color: #1e40af; cursor: pointer; font-size: 1.1rem; font-weight: bold; padding: 0; display: flex; align-items: center; justify-content: center; width: 1.1rem; height: 1.1rem; margin-left: 0.25rem; line-height: 1;">×</button>
        </div>`);
        
        console.log('Pill HTML:', pill.html());
        $('#assistant_list_container').append(pill);
        console.log('Container html after append:', $('#assistant_list_container').html());
        
        $('#assistant_select').val('').trigger('change');
        updateAssistantHidden();
        saveTrainingDraft();
        console.log('Assistant list value:', $('#assistant_list').val());
    });

    // Remove assistant stacked field
    $(document).on('click', '.remove-assistant', function (ev) {
        ev.preventDefault();
        $(this).closest('.assistant-item').remove();
        updateAssistantHidden();
        saveTrainingDraft();
    });

    $(document).on('click', '#coordinator_add_btn', function (ev) {
        ev.preventDefault();
        const val = $('#coordinator_to_notify_select').val();
        const selectedText = $('#coordinator_to_notify_select option:selected').text();

        if (!val) {
            return;
        }

        const existing = [];
        $('#coordinator_list_container .coordinator-item').each(function () {
            existing.push(String($(this).data('id')));
        });

        if (existing.includes(String(val))) {
            return;
        }

        const pill = $(`<div class="coordinator-item" data-id="${val}" style="background: #dbeafe; border: 1px solid #bfdbfe; color: #1e40af; padding: 0.5rem 0.75rem; border-radius: 9999px; display: inline-flex; align-items: center; gap: 0.5rem; font-size: 0.875rem; font-weight: 500; white-space: nowrap;">
            <span>${selectedText}</span>
            <button type="button" class="remove-coordinator" data-id="${val}" style="background: transparent; border: none; color: #1e40af; cursor: pointer; font-size: 1.1rem; font-weight: bold; padding: 0; display: flex; align-items: center; justify-content: center; width: 1.1rem; height: 1.1rem; margin-left: 0.25rem; line-height: 1;">×</button>
        </div>`);

        $('#coordinator_list_container').append(pill);
        $('#coordinator_to_notify_select').val('').trigger('change').removeClass('border-danger');
        updateCoordinatorHidden();
        saveTrainingDraft();
    });

    $(document).on('click', '.remove-coordinator', function (ev) {
        ev.preventDefault();
        $(this).closest('.coordinator-item').remove();
        updateCoordinatorHidden();
        saveTrainingDraft();
    });

    let trainingWizardStep = 1;

    function showTrainingStep(step) {
        trainingWizardStep = step;
        if (trainingStepper) {
            trainingStepper.goTo(step);
        }
    }

    function handleTrainingSubmit(e) {
        e.preventDefault();

        const currentStep = trainingStepper ? trainingStepper.getCurrentStepIndex() : trainingWizardStep;

        if (currentStep === 1) {
            return;
        }

        if (!validateStep1() || !validateStep2()) {
            Swal.fire({
                title: 'Missing Fields!',
                text: 'Please fill in all required fields.',
                icon: 'warning',
                confirmButtonText: 'OK'
            });
            return;
        }

        // Form Data Collection
        let facilitator_id = $('#facilitator').find('option:selected').val();
        let assistant_id = $('#assistant_list').val() || '';
        let from_date = startDateFormatted;
        let to_date = endDateFormatted;
        let from_time = $('#time-start').val();
        let to_time = $('#time-end').val();
        let course = $('#course').find('option:selected').val() || $('#public-course-select').find('option:selected').val();
        let platform = $('#platform').val();
        let account_id = $('#credentials-container').is(':visible') ? ($('#credentials').find('option:selected').val() || '') : '';
        let location = $('#location').val();
        let company = $('#company').find('option:selected').val();

        if (!facilitator_id || facilitator_id === "") {
            handleCompanyAndStoreTraining(company);
        } else {
            checkAvailability(facilitator_id, from_date, to_date, function (isAvailable) {
                if (isAvailable) {
                    handleCompanyAndStoreTraining(company);
                } else {
                    Swal.fire({
                        title: 'Facilitator Unavailable',
                        text: 'The selected facilitator is unavailable on the selected date(s). Do you want to proceed anyway?',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Proceed',
                        cancelButtonText: 'Cancel',
                        customClass: {
                            confirmButton: "btn btn-success",
                            cancelButton: 'btn btn-secondary'
                        }
                    }).then((result) => {
                        if (result.isConfirmed) {
                            handleCompanyAndStoreTraining(company);
                        }
                    });
                }
            });
        }
    }

    function toggleDriverArrangementFields() {
        const needsTransportation = $('#need_transportation_yes').is(':checked');
        if (needsTransportation) {
            $('#driver-arrangement-fields').removeClass('d-none');
        } else {
            $('#driver-arrangement-fields').addClass('d-none');
            $('#return_trip_needed').prop('checked', false);
            $('#return-trip-fields').addClass('d-none');
            $('#notify_coordinator').prop('checked', false);
            $('#coordinator_to_notify_select').val('').trigger('change');
            $('#coordinator_list_container').empty();
            $('#coordinator_to_notify_list').val('');
            $('#coordinator-to-notify-container').addClass('d-none');
        }
    }

    function validateStep1() {
        let isValid = true;
        let mode = $('input[name="mode"]:checked').val();

        const requiredFields = [
            'input[name="mode"]:checked',
            '#company',
            '#course',
            '#date-range',
            '#time-start',
            '#time-end',
            '#facilitator',
            '#location'
        ];

        if (mode === 'virtual') {
        } else if (mode === 'face-to-face') {
            requiredFields.push('#location');
        } else if (mode === 'public-course') {
            requiredFields.push('#public-course-select');
        }

        requiredFields.forEach(function (selector) {
            let element = $(selector);
            if (element.length && !element.is(':visible')) {
                return;
            }
            if (selector === '#facilitator') {
                return;
            }
            if (selector === 'input[name="mode"]:checked') {
                if ($('input[name="mode"]:checked').length === 0) {
                    $('input[name="mode"]').closest('.form-group').addClass('border-danger');
                    isValid = false;
                } else {
                    $('input[name="mode"]').closest('.form-group').removeClass('border-danger');
                }
            } else if (element.is('select')) {
                if (element.val() === '' || element.val() === null) {
                    element.addClass('border-danger');
                    isValid = false;
                } else {
                    element.removeClass('border-danger');
                }
            } else {
                if ((element.val() || '').trim() === '') {
                    element.addClass('border-danger');
                    isValid = false;
                } else {
                    element.removeClass('border-danger');
                }
            }
        });

        let facilitator = $('#facilitator').find('option:selected').val();
        let facilitatorText = $('#facilitator').find('option:selected').text().trim();
        if ($('#facilitator').is(':visible')) {
            if (
                facilitatorText === 'Select Facilitator' ||
                (facilitator === '' && facilitatorText !== 'No Facilitator Yet') ||
                facilitator === null
            ) {
                $('#facilitator').addClass('border-danger');
                isValid = false;
            } else {
                $('#facilitator').removeClass('border-danger');
            }
        }

        return isValid;
    }

    function validateStep2() {
        let isValid = true;
        const needsTransportation = $('#need_transportation_yes').is(':checked');

        if (!needsTransportation) {
            return true;
        }

        // --- OUTBOUND VALIDATION ---
        
        // 1. Check Outbound Date specifically
        if ($('#outbound_pickup_date').val() === '' && !$('#outbound_date_na').is(':checked')) {
            $('#outbound_pickup_date').addClass('border-danger');
            isValid = false;
        } else {
            $('#outbound_pickup_date').removeClass('border-danger');
        }

        // 2. Check other Outbound fields
        const outboundFields = [
            '#outbound_pickup_time',
            '#outbound_contact_number',
            '#outbound_pickup_location',
            '#outbound_dropoff_location'
        ];

        outboundFields.forEach(function (selector) {
            const element = $(selector);
            if ((element.val() || '').trim() === '') {
                element.addClass('border-danger');
                isValid = false;
            } else {
                element.removeClass('border-danger');
            }
        });

        // --- RETURN TRIP VALIDATION ---
        if ($('#return_trip_needed').is(':checked')) {
            
            // 1. Check Return Date specifically
            if ($('#return_pickup_date').val() === '' && !$('#return_date_na').is(':checked')) {
                $('#return_pickup_date').addClass('border-danger');
                isValid = false;
            } else {
                $('#return_pickup_date').removeClass('border-danger');
            }

            // 2. Check other Return fields
            ['#return_pickup_time', '#return_contact_number', '#return_pickup_location', '#return_dropoff_location'].forEach(function (selector) {
                const element = $(selector);
                if ((element.val() || '').trim() === '') {
                    element.addClass('border-danger');
                    isValid = false;
                } else {
                    element.removeClass('border-danger');
                }
            });
        }

        // --- COORDINATOR VALIDATION ---
        if ($('#notify_coordinator').is(':checked')) {
            const coordinator = $('#coordinator_to_notify_list').val();
            if (!coordinator) {
                $('#coordinator_to_notify_select').addClass('border-danger');
                isValid = false;
            } else {
                $('#coordinator_to_notify_select').removeClass('border-danger');
            }
        }

        return isValid;
    }

    $(document).on('change', 'input[name="need_transportation"]', toggleDriverArrangementFields);
    $(document).on('change', '#return_trip_needed', function () {
        if ($(this).is(':checked')) {
            $('#return-trip-fields').removeClass('d-none');
        } else {
            $('#return-trip-fields').addClass('d-none');
        }
    });
    $(document).on('change', '#notify_coordinator', function () {
        if ($(this).is(':checked')) {
            $('#coordinator-to-notify-container').removeClass('d-none');
        } else {
            $('#coordinator-to-notify-container').addClass('d-none');
            $('#coordinator_to_notify_select').val('').trigger('change');
            $('#coordinator_list_container').empty();
            $('#coordinator_to_notify_list').val('');
        }
    });

    $(document).on('click', '#add_training_back', function (ev) {
        ev.preventDefault();
        if (trainingStepper) {
            trainingStepper.goPrevious();
        }
    });

    toggleDriverArrangementFields();
    restoreTrainingDraft();

    $(document).on('click', '[data-kt-stepper-action="next"]', function (ev) {
        ev.preventDefault();
        if (!trainingStepper) {
            return;
        }

        if (trainingStepper.getCurrentStepIndex() === 1 && !validateStep1()) {
            Swal.fire({
                title: 'Missing Fields!',
                text: 'Please fill in all required training details first.',
                icon: 'warning',
                confirmButtonText: 'OK'
            });
            return;
        }

        trainingStepper.goNext();
    });

    $(document).on('click', '#add_training_submit', handleTrainingSubmit);
    $(document).ready(function () {
        $('input[name="mode"]').change(function () {
            const mode = $(this).val();
            clearFields(mode);
            // Ensure driver arrangement state updates after mode change
            updateDriverArrangementSupport();
        });

        function clearFields(mode) {
            // Clear all input fields, dropdowns, and date pickers
            $('#credentials, #company, #course, #public-course-select, #platform, #location, #facilitator, #assistant_select, #date-range, #time-start, #time-end, #platform_other, #conference_link')
                .val('')
                .trigger('change');

            $('#need_transportation_no').prop('checked', true);
            $('#need_transportation_yes').prop('checked', false);
            $('#return_trip_needed').prop('checked', false);
            $('#notify_coordinator').prop('checked', false);
            $('#outbound_pickup_date, #outbound_pickup_time, #outbound_contact_number, #outbound_pickup_location, #outbound_dropoff_location, #return_pickup_date, #return_pickup_time, #return_contact_number, #return_pickup_location, #return_dropoff_location').val('');
            $('#coordinator_to_notify_select').val('').trigger('change');
            $('#coordinator_list_container').empty();
            $('#coordinator_to_notify_list').val('');

            // Hide platform_other input
            $('#platform_other').addClass('d-none');
            $('#driver-arrangement-fields, #return-trip-fields, #coordinator-to-notify-container').addClass('d-none');

            // Clear assistants list UI and state
            window.assistantsList = [];
            $('#assistant_list_container').empty();
            $('#assistant_list').val('');
            $('#assistant_select').val('').trigger('change');

            if (trainingStepper) {
                trainingStepper.goFirst();
            }

            // Clear datepicker selections if used
            $('#date-range').datepicker('clearDates');

            // Uncheck checkboxes (like In-person training)
            $('input[type="checkbox"]').prop('checked', false);

            // Hide/Show based on the selected mode
            if (mode === 'virtual') {
                $('#credentials-container').show();
                $('#location-container, #public-course-container, #company-container').hide();
            } else if (mode === 'face-to-face') {
                $('#location-container').show();
                $('#credentials-container, #public-course-container').hide();
            } else if (mode === 'public-course') {
                $('#public-course-container, #credentials-container').show();
                $('#location-container, #company-container').hide();
            }
        }
    });

    $(function () {
        restoreTrainingDraft();
    });



    // Step 2: Function to check if a facilitator is available
    function checkAvailability(userId, fromDate, toDate, callback) {
        $.ajax({
            url: `/calendar/api/check-unavailability/${userId}`,
            method: 'POST',
            data: JSON.stringify({
                from_date: fromDate,
                to_date: toDate
            }),
            contentType: 'application/json',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function (response) {
                console.log('Availability Response:', response);
                callback(response.available); // Pass result to callback
            },
            error: function (xhr, status, error) {
                console.error('Error checking availability:', xhr.responseText);
                Swal.fire('Error!', 'Could not check facilitator availability.', 'error');
                callback(false); // Assume unavailable if error occurs
            }
        });
    }

    // Step 3: Function to handle company creation and training storage
    function handleCompanyAndStoreTraining(company) {
        // Check for duplicate company
        const enteredCompany = $('#enter-company').val().trim().toLowerCase();
        let isDuplicate = false;

        $('#company option').each(function () {
            if ($(this).text().trim().toLowerCase() === enteredCompany) {
                isDuplicate = true;
                return false; // Exit loop if duplicate found
            }
        });

        if (isDuplicate) {
            console.log('4');
            Swal.fire({
                title: 'Duplicate Company!',
                text: 'The company already exists in the list.',
                icon: 'warning',
                confirmButtonText: 'OK'
            });
            return; // Stop the submission if duplicate
        }

        if (company === "other") {
            console.log('5');
            let companyData = new FormData();
            companyData.append('company_name', $('#enter-company').val());
            companyData.append('contact_person', '');
            companyData.append('contact_number', '');
            companyData.append('email', '');

            $.ajax({
                url: '/config/companies/add',
                method: 'POST',
                data: companyData,
                contentType: false,
                processData: false,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (response) {
                    if (response.success) {
                        console.log('Company Created:', response.company.id);
                        createTraining(response.company.id);
                    } else {
                        Swal.fire('Error!', 'Failed to create company.', 'error');
                    }
                },
                error: function (xhr, status, error) {
                    console.error('AJAX Error:', xhr.responseText);
                    Swal.fire('Error!', 'An unexpected error occurred.', 'error');
                }
            });
        } else {
            console.log('6');
            createTraining(company);
        }
    }

    // Step 4: Function to create the training session
    function createTraining(companyId) {
        // Get platform: if "other" is selected, use the custom text; otherwise use the dropdown value
        let platformValue = $('#platform').val();
        const platformHidden = $('#platform-container').hasClass('d-none');
        if (platformHidden) {
            platformValue = '';
        } else if (platformValue === 'other') {
            platformValue = $('#platform_other').val();
        }

        syncDateRangeStateFromInput(fp);

        let formData = new FormData();
        formData.append('course_id', $('#course').find('option:selected').val() || $('#public-course-select').find('option:selected').val());
        formData.append('platform', platformValue);
        formData.append('conference_link', $('#conference_link').val());
        formData.append('location', $('#location').val());
        formData.append('company_id', companyId);
        formData.append('assistant', $('#assistant_list').val() || '');
        formData.append('account_id', $('#credentials').find('option:selected').val());
        formData.append('mode', $('input[name="mode"]:checked').val());
        formData.append('from_date', startDateFormatted);
        formData.append('to_date', endDateFormatted);
        formData.append('from_time', $('#time-start').val());
        formData.append('to_time', $('#time-end').val());
        
        // Get facilitator and account_manager values
        const facilitatorValue = $('#facilitator').find('option:selected').val();
        const accountManagerValue = $('#account_manager').find('option:selected').val();
        
        formData.append('facilitator_id', facilitatorValue || '');
        formData.append('account_manager_id', accountManagerValue || '');
        formData.append('need_transportation', $('#need_transportation_yes').is(':checked') ? 'yes' : 'no');
        formData.append('outbound_pickup_date', $('#outbound_pickup_date').val());
        formData.append('outbound_pickup_time', $('#outbound_pickup_time').val());
        formData.append('outbound_contact_number', $('#outbound_contact_number').val());
        formData.append('outbound_pickup_location', $('#outbound_pickup_location').val());
        formData.append('outbound_dropoff_location', $('#outbound_dropoff_location').val());
        formData.append('return_trip_needed', $('#return_trip_needed').is(':checked') ? '1' : '0');
        formData.append('return_pickup_date', $('#return_pickup_date').val());
        formData.append('return_pickup_time', $('#return_pickup_time').val());
        formData.append('return_contact_number', $('#return_contact_number').val());
        formData.append('return_pickup_location', $('#return_pickup_location').val());
        formData.append('return_dropoff_location', $('#return_dropoff_location').val());
        formData.append('notify_coordinator', $('#notify_coordinator').is(':checked') ? '1' : '0');
        formData.append('coordinator_to_notify_list', $('#coordinator_to_notify_list').val() || '');

        const isEditMode = Boolean(window.isEditMode && window.trainingId);
        if (isEditMode) {
            formData.append('_method', 'PUT');
        }

        for (let [key, value] of formData.entries()) {
            console.log(`${key}: ${value}`);
        }

        console.log('sending request');

        // Show Swal loader
        Swal.fire({
            title: isEditMode ? 'Updating Training...' : 'Adding Training...',
            text: 'Please wait while we process your request.',
            allowOutsideClick: false,
            allowEscapeKey: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        $.ajax({
            url: isEditMode ? `/calendar/edit_training/${window.trainingId}` : '/calendar/add_training',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            cache: false,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function (response) {
                Swal.close(); // Close loader

                // Consider success when controller returns a training object or explicit success flag
                if ((response && response.training && response.training.id) || response.success === true) {
                    Swal.fire({
                        title: 'Success!',
                        text: isEditMode ? 'Training has been updated.' : 'Training has been added.',
                        icon: 'success',
                        confirmButtonText: 'OK',
                        allowOutsideClick: false,
                        allowEscapeKey: false
                    }).then(() => {
                        window.location.href = '/calendar';
                    });
                } else {
                    // Fallback: show message from server if present
                    Swal.fire({
                        title: 'Notice',
                        text: response && response.message ? response.message : 'Unexpected response from server',
                        icon: 'warning',
                        confirmButtonText: 'OK'
                    }).then(() => {
                        window.location.href = '/calendar';
                    });
                }
            },
            error: function (xhr, status, error) {
                Swal.close(); // Close loader

                console.log('AJAX Error Details:', xhr.responseText);
                Swal.fire({
                    title: 'Error!',
                    text: 'There was an error adding the training.',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            }
        });
    }


});


// Add a company input field
document.addEventListener("DOMContentLoaded", function () {
    const companyDropdown = document.getElementById("company");
    const companyInput = document.getElementById("enter-company");

    companyDropdown.addEventListener("change", function () {
        if (companyDropdown.value === "other") {
            // Show input field, hide dropdown
            companyDropdown.classList.add("d-none");
            companyInput.classList.remove("d-none");
            companyInput.focus();
        }
    });

    companyInput.addEventListener("blur", function () {
        if (companyInput.value.trim() === "") {
            companyInput.classList.add("d-none");
            companyDropdown.classList.remove("d-none");
            companyDropdown.value = "";
        }
    });
});

$(document).ready(function() {
    // Handle Outbound Date N/A
    $('#outbound_date_na').on('change', function() {
        if ($(this).is(':checked')) {
            // Disable, clear value, and remove any red validation borders
            $('#outbound_pickup_date').val('').prop('disabled', true).removeClass('is-invalid border-danger');
        } else {
            $('#outbound_pickup_date').prop('disabled', false);
        }
    });

    // Handle Return Date N/A
    $('#return_date_na').on('change', function() {
        if ($(this).is(':checked')) {
            // Disable, clear value, and remove any red validation borders
            $('#return_pickup_date').val('').prop('disabled', true).removeClass('is-invalid border-danger');
        } else {
            $('#return_pickup_date').prop('disabled', false);
        }
    });
});