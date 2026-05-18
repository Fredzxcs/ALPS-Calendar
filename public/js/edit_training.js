document.addEventListener("DOMContentLoaded", function () {

    // Define elements used by the legacy vanilla JS block if present on the page
    const inpersonCheckbox = document.getElementById("inperson-training");
    const modeRadios = document.querySelectorAll('input[name="mode"]');
    const credentialsContainer = document.getElementById('credentials-container');
    const locationContainer = document.getElementById('location-container');
    const publicCourseContainer = document.getElementById('public-course-container');
    const companyCourseContainer = document.getElementById('company-course-container');

    // Only run this vanilla logic when the expected elements exist to avoid errors
    if (modeRadios && modeRadios.length > 0 && credentialsContainer) {
        if (localStorage.getItem("inpersonChecked") === "true" && inpersonCheckbox) {
            inpersonCheckbox.checked = true;
        }

        // Mode change logic
        modeRadios.forEach(radio => {
            radio.addEventListener("change", function () {
                if (!credentialsContainer || !locationContainer || !publicCourseContainer || !companyCourseContainer) return;
                if (radio.id === "virtual") {
                    credentialsContainer.classList.remove("d-none");
                    locationContainer.classList.add("d-none");
                    publicCourseContainer.classList.add("d-none");
                    companyCourseContainer.classList.remove("d-none");
                } else if (radio.id === "face-to-face") {
                    credentialsContainer.classList.add("d-none");
                    locationContainer.classList.remove("d-none");
                    publicCourseContainer.classList.add("d-none");
                    companyCourseContainer.classList.remove("d-none");
                } else if (radio.id === "public-course") {
                    credentialsContainer.classList.remove("d-none");
                    publicCourseContainer.classList.remove("d-none");
                    companyCourseContainer.classList.add("d-none");
                    locationContainer.classList.add("d-none");

                    if (inpersonCheckbox && inpersonCheckbox.checked) {
                        credentialsContainer.classList.add("d-none");
                        locationContainer.classList.remove("d-none");
                    } else {
                        credentialsContainer.classList.remove("d-none");
                        locationContainer.classList.add("d-none");
                    }
                }
            });
        });

        // In-person Checkbox Logic
        if (inpersonCheckbox) {
            inpersonCheckbox.addEventListener("change", function () {
                // Update location visibility safely
                if (typeof updateLocationVisibility === 'function') {
                    updateLocationVisibility();
                }
                localStorage.setItem("inpersonChecked", inpersonCheckbox.checked);
            });
        }

        function updateLocationVisibility() {
            const mode = document.querySelector('input[name="mode"]:checked')?.id;

            if (inpersonCheckbox && inpersonCheckbox.checked) {
                credentialsContainer.classList.add("d-none");
                locationContainer.classList.remove("d-none");
            } else {
                credentialsContainer.classList.remove("d-none");

                if (mode === 'face-to-face' || (mode === 'public-course' && inpersonCheckbox && inpersonCheckbox.checked)) {
                    locationContainer.classList.remove("d-none");
                } else {
                    locationContainer.classList.add("d-none");
                }
            }
        }

        // initialize once
        if (typeof updateLocationVisibility === 'function') {
            updateLocationVisibility();
        }
    }

    // //  Force trigger checkbox logic after all scripts are loaded
    // setTimeout(function () {
    //     if (inpersonCheckbox.checked) {
    //         inpersonCheckbox.dispatchEvent(new Event('change'));
    //     }
    // }, 300);
});

const csrfToken = $('meta[name="csrf-token"]').attr('content') || '';

document.addEventListener('DOMContentLoaded', function () {
    const cancelBtn = document.getElementById('cancel_training_button');
    const editSubmitBtn = document.getElementById('edit_training_submit');
    const companyDropdown = document.getElementById('company');
    const companyInput = document.getElementById('enter-company');

    function updateLocationVisibility() {
        const mode = $('input[name="mode"]:checked').val();
        const isInPerson = $('#inperson-training').is(':checked');

        if (mode === 'virtual') {
            $('#location-container').addClass('d-none');
            $('#credentials-container').removeClass('d-none');
            return;
        }

        if (isInPerson) {
            $('#location-container').removeClass('d-none');
            $('#credentials-container').addClass('d-none');
            return;
        }

        $('#credentials-container').removeClass('d-none');
        if (mode === 'face-to-face' || mode === 'public-course') {
            $('#location-container').removeClass('d-none');
        } else {
            $('#location-container').addClass('d-none');
        }
    }

    function clearFields(mode, previousMode) {
        const isInPerson = $('#inperson-training').is(':checked');

        if (mode === 'virtual') {
            $('#credentials, #platform').val('').trigger('change');
            $('#credentials-container').removeClass('d-none');
            $('#public-course-container, #company-container, #location-container').addClass('d-none');

            if (previousMode === 'public-course') {
                return;
            }
        } else if (mode === 'face-to-face') {
            $('#credentials, #platform').val('').trigger('change');
            $('#location-container').removeClass('d-none');
            $('#credentials-container, #public-course-container').addClass('d-none');
            $('#account').val('').trigger('change');
        } else if (mode === 'public-course') {
            $('#public-course-container, #credentials-container').removeClass('d-none');
            $('#company').val('').trigger('change');
            $('#company-container').addClass('d-none');

            if (isInPerson) {
                $('#location-container').removeClass('d-none');
            } else {
                $('#location-container').addClass('d-none');
            }

            if (previousMode === 'virtual') {
                return;
            }
        }
    }

    if (cancelBtn) {
        cancelBtn.addEventListener('click', function (e) {
            e.preventDefault();
            Swal.fire({
                title: 'Are you sure?',
                text: 'Any unsaved changes will be lost. Do you want to proceed?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, cancel',
                cancelButtonText: 'Stay on page',
                customClass: {
                    confirmButton: 'btn btn-success',
                    cancelButton: 'btn btn-secondary'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = '/calendar';
                }
            });
        });
    }

    if (companyDropdown && companyInput) {
        companyDropdown.addEventListener('change', function () {
            if (companyDropdown.value === 'other') {
                companyDropdown.classList.add('d-none');
                companyInput.classList.remove('d-none');
                companyInput.focus();
            }
        });

        companyInput.addEventListener('blur', function () {
            if (companyInput.value.trim() === '') {
                companyInput.classList.add('d-none');
                companyDropdown.classList.remove('d-none');
                companyDropdown.value = '';
            }
        });
    }

    updateLocationVisibility();

    let previousMode = $('input[name="mode"]:checked').val();

    $('input[name="mode"]').on('change', function () {
        const newMode = $(this).val();
        clearFields(newMode, previousMode);
        updateLocationVisibility();
        previousMode = newMode;
    });

    $('#inperson-training').on('change', function () {
        updateLocationVisibility();
    });

    if (!editSubmitBtn) {
        console.warn('Edit training submit button not found; skipping handler attach.');
        return;
    }

    // Only listen for the custom event that the blade dispatches on SAVE (step 2)
    editSubmitBtn.addEventListener('submitStep2', function (e) {
        console.log('submitStep2 custom event received - user clicked SAVE on step 2');
        submitTrainingViaAjax();
    });

    function submitTrainingViaAjax() {
        console.log('=== submitTrainingViaAjax() started ===');
        
        let mode = $('input[name="mode"]:checked').val();
        let isValid = true;
        
        console.log('Mode:', mode);

        let requiredFields = [
            'input[name="mode"]:checked',
            '#credentials',
            '#company',
            '#course',
            '#date-range',
            '#time-start',
            '#time-end',
            '#facilitator',
            '#location'
        ];

        if (mode === 'virtual') {
            requiredFields.push('#credentials');
        } else if (mode === 'face-to-face') {
            requiredFields.push('#location');
        } else if (mode === 'public-course') {
            requiredFields.push('#public-course-select');
        }

        requiredFields.forEach(function (selector) {
            let element = $(selector);

            if (element.length === 0 || !element.is(':visible')) {
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
            } else if (element.is('input') || element.is('textarea')) {
                if ((element.val() || '').trim() === '') {
                    element.addClass('border-danger');
                    isValid = false;
                } else {
                    element.removeClass('border-danger');
                }
            }
        });

        let course = $('#course').find('option:selected').val() || $('#public-course-select').find('option:selected').val();
        if (!course) {
            course = $('#course').val() || $('#public-course-select').val();
        }

        let facilitator = $('#facilitator').find('option:selected').val();
        let facilitatorText = $('#facilitator').find('option:selected').text().trim();
        if ($('#facilitator').is(':visible')) {
            if (facilitatorText === 'Select Facilitator' || (facilitator === '' && facilitatorText !== 'No Facilitator Yet') || facilitator === null) {
                $('#facilitator').addClass('border-danger');
                isValid = false;
            } else {
                $('#facilitator').removeClass('border-danger');
            }
        }

        if (!isValid) {
            Swal.fire({
                title: 'Missing Fields!',
                text: 'Please fill in all required fields.',
                icon: 'warning',
                confirmButtonText: 'OK'
            });
            return;
        }

        let from_date = $('#date-range').data('start-date');
        let to_date = $('#date-range').data('end-date');
        if (!from_date || !to_date) {
            const dateRange = ($('#date-range').val() || '').split(' to ');
            from_date = moment(dateRange[0], 'MM-DD-YYYY').format('YYYY-MM-DD');
            to_date = dateRange.length === 1 || !dateRange[1] ? from_date : moment(dateRange[1], 'MM-DD-YYYY').format('YYYY-MM-DD');
        }

        let facilitator_id = $('#facilitator').find('option:selected').val();
        let company = $('#company').find('option:selected').val();

        function checkAvailability(userId, fromDate, toDate, callback) {
            $.ajax({
                url: `/calendar/api/check-unavailability/${userId}`,
                method: 'POST',
                data: JSON.stringify({ from_date: fromDate, to_date: toDate }),
                contentType: 'application/json',
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                },
                success: function (response) {
                    callback(response.available);
                },
                error: function () {
                    Swal.fire('Error!', 'Could not check facilitator availability.', 'error');
                    callback(false);
                }
            });
        }

        function handleCompanyAndStoreTraining(companyValue) {
            const companyField = $('#enter-company');
            const enteredCompany = (companyField.val() || '').trim().toLowerCase();
            let isDuplicate = false;

            $('#company option').each(function () {
                if ($(this).text().trim().toLowerCase() === enteredCompany) {
                    isDuplicate = true;
                    return false;
                }
            });

            if (isDuplicate) {
                Swal.fire({
                    title: 'Duplicate Company!',
                    text: 'The company already exists in the list.',
                    icon: 'warning',
                    confirmButtonText: 'OK'
                });
                return;
            }

            if (companyValue === 'other') {
                let companyData = new FormData();
                companyData.append('company_name', companyField.val());
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
                        'X-CSRF-TOKEN': csrfToken
                    },
                    success: function (response) {
                        if (response.success) {
                            editTraining(response.company.id);
                        } else {
                            Swal.fire('Error!', 'Failed to create company.', 'error');
                        }
                    },
                    error: function () {
                        Swal.fire('Error!', 'An unexpected error occurred.', 'error');
                    }
                });
            } else {
                editTraining(companyValue);
            }
        }

        function editTraining(companyId) {
            console.log('editTraining() called with companyId:', companyId);
            Swal.fire({
                title: 'Are you sure?',
                text: 'You are about to edit this training.',
                icon: 'warning',
                buttonsStyling: false,
                showCancelButton: true,
                confirmButtonText: 'Yes, Edit Training',
                cancelButtonText: 'Cancel',
                customClass: {
                    confirmButton: 'btn btn-success',
                    cancelButton: 'btn btn-secondary'
                }
            }).then((result) => {
                if (!result.isConfirmed) {
                    console.log('User cancelled the confirmation modal');
                    return;
                }

                console.log('User confirmed - collecting form data...');

                let assistant_id = '';
                const assistantRawValue = $('#assistant_raw').val();
                const assistantInput = $('#assistant');
                const assistantInitialValue = assistantInput.data('assistant-raw') || '';

                $('div[data-repeater-list="asst_repeat"] .assistant').each(function () {
                    const value = $(this).val().trim();
                    if (value) {
                        assistant_id += (assistant_id.length > 0 ? ', ' : '') + value;
                    }
                });

                let from_time = $('#time-start').val();
                let to_time = $('#time-end').val();
                let platform = $('#platform').val();
                let platformOther = $('#platform_other').val();
                let account_id = $('#credentials').find('option:selected').val();
                let location = $('#location').val();
                let conference_link = $('#conference_link').val();
                let need_transportation = $('input[name="need_transportation"]:checked').val() === 'yes' ? 1 : 0;
                let outbound_pickup_time = $('#outbound_pickup_time').val();
                let outbound_contact_number = $('#outbound_contact_number').val();
                let outbound_pickup_location = $('#outbound_pickup_location').val();
                let outbound_dropoff_location = $('#outbound_dropoff_location').val();
                let return_trip_needed = $('#return_trip_needed').is(':checked') ? 1 : 0;
                let return_pickup_time = $('#return_pickup_time').val();
                let return_contact_number = $('#return_contact_number').val();
                let return_pickup_location = $('#return_pickup_location').val();
                let return_dropoff_location = $('#return_dropoff_location').val();
                let notify_coordinator = $('#notify_coordinator').is(':checked') ? 1 : 0;
                // Handle multiple coordinator IDs - join them with commas
                const coordinatorIds = $('#coordinator_to_notify').val();
                let coordinator_to_notify = coordinatorIds ? coordinatorIds.join(',') : '';

                if (mode === 'virtual') {
                    location = '';
                } else if (mode === 'face-to-face') {
                    account_id = '';
                    platform = '';
                    conference_link = '';
                } else if (mode === 'public-course') {
                    if ($('#inperson-training').is(':checked')) {
                        account_id = '';
                        location = $('#location').val();
                    } else {
                        location = '';
                    }
                }

                let finalPlatform = platform === 'other' ? platformOther : platform;
                // For face-to-face training, ensure platform is empty
                if (mode === 'face-to-face') {
                    finalPlatform = '';
                }
                let data = {
                    course_id: course,
                    platform: finalPlatform,
                    conference_link: conference_link,
                    location: location,
                    facilitator_id: facilitator_id,
                    company_id: companyId,
                    assistant: assistant_id || (assistantInput.val().trim() === assistantInitialValue ? assistantRawValue : assistantInput.val().trim()),
                    account_id: account_id,
                    mode: mode,
                    from_date: from_date,
                    to_date: to_date,
                    from_time: from_time,
                    to_time: to_time,
                    need_transportation: need_transportation,
                    outbound_pickup_time: outbound_pickup_time,
                    outbound_contact_number: outbound_contact_number,
                    outbound_pickup_location: outbound_pickup_location,
                    outbound_dropoff_location: outbound_dropoff_location,
                    return_trip_needed: return_trip_needed,
                    return_pickup_time: return_pickup_time,
                    return_contact_number: return_contact_number,
                    return_pickup_location: return_pickup_location,
                    return_dropoff_location: return_dropoff_location,
                    notify_coordinator: notify_coordinator,
                    coordinator_to_notify: coordinator_to_notify
                };

                const url = window.location.href;
                const match = url.match(/\/edit_training\/(\d+)$/);
                const trainingId = match ? match[1] : '';

                Swal.fire({
                    title: 'Updating Training...',
                    text: 'Please wait while the training is being updated.',
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                console.log('Sending AJAX PUT request to /calendar/edit_training/' + trainingId);
                console.log('Data payload:', JSON.stringify(data, null, 2));

                $.ajax({
                    url: `/calendar/edit_training/${trainingId}`,
                    type: 'PUT',
                    data: JSON.stringify(data),
                    contentType: 'application/json',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    },
                    success: function (response) {
                        console.log('AJAX success response:', response);
                        Swal.close();

                        if (response && response.code === '200') {
                            Swal.fire({
                                title: 'Success!',
                                text: 'Training has been updated.',
                                icon: 'success',
                                confirmButtonText: 'OK',
                                allowOutsideClick: false,
                                allowEscapeKey: false
                            }).then(() => {
                                // Hard refresh to clear cache and reload calendar with fresh data
                                window.location.href = '/calendar?t=' + Date.now();
                            });
                            return;
                        }

                        let html = 'Unknown server response';
                        if (response && response.errors) {
                            const keys = Object.keys(response.errors);
                            html = keys.map(k => `<strong>${k}:</strong> ${response.errors[k].join(', ')}`).join('<br/>');
                        } else if (response && (response.message || response.error)) {
                            html = response.message || response.error;
                        } else if (response) {
                            html = JSON.stringify(response);
                        }

                        Swal.fire({
                            title: 'Update did not complete',
                            html: html,
                            icon: 'warning',
                            confirmButtonText: 'OK'
                        });
                    },
                    error: function (xhr) {
                        console.log('AJAX error - Status:', xhr?.status);
                        console.log('AJAX error - Response:', xhr?.responseJSON || xhr?.responseText);
                        Swal.close();

                        if (xhr && xhr.status === 422 && xhr.responseJSON && xhr.responseJSON.errors) {
                            const errors = xhr.responseJSON.errors;
                            const html = Object.keys(errors).map(k => `<strong>${k}:</strong> ${errors[k].join(', ')}`).join('<br/>');
                            Swal.fire({
                                title: 'Validation error',
                                html: html,
                                icon: 'error',
                                confirmButtonText: 'OK'
                            });
                            return;
                        }

                        Swal.fire({
                            title: 'Error!',
                            text: 'There was an error updating the training. See console/network for details.',
                            footer: xhr && xhr.responseText ? xhr.responseText : 'No response body',
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                    }
                });
            });
        }

        if (!facilitator_id || facilitator_id === '') {
            handleCompanyAndStoreTraining(company);
            return;
        }

        checkAvailability(facilitator_id, from_date, to_date, function (isAvailable) {
            if (isAvailable) {
                handleCompanyAndStoreTraining(company);
                return;
            }

            Swal.fire({
                title: 'Facilitator Unavailable',
                text: 'The selected facilitator is unavailable on the selected date(s). Do you want to proceed anyway?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Proceed',
                cancelButtonText: 'Cancel',
                customClass: {
                    confirmButton: 'btn btn-success',
                    cancelButton: 'btn btn-secondary'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    handleCompanyAndStoreTraining(company);
                }
            });
        });
    }
});
