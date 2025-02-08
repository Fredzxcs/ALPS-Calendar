document.addEventListener("DOMContentLoaded", function () {

    if (localStorage.getItem("inpersonChecked") === "true") {
        inpersonCheckbox.checked = true;
    }
    updateLocationVisibility();

    //  Mode of Training Logic
    modeRadios.forEach(radio => {
        radio.addEventListener("change", function () {
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

                if (inpersonCheckbox.checked) {
                    credentialsContainer.classList.add("d-none");
                    locationContainer.classList.remove("d-none");
                } else {
                    credentialsContainer.classList.remove("d-none");
                    locationContainer.classList.add("d-none");
                }
            }
        });
    });

    //  In-person Checkbox Logic
    inpersonCheckbox.addEventListener("change", function () {
        updateLocationVisibility();
        // Save the checkbox state so it stays checked after refresh
        localStorage.setItem("inpersonChecked", inpersonCheckbox.checked);
    });

    function updateLocationVisibility() {

        const mode = document.querySelector('input[name="mode"]:checked')?.id;

        if (inpersonCheckbox.checked) {
            credentialsContainer.classList.add("d-none");
            locationContainer.classList.remove("d-none");
        } else {
            credentialsContainer.classList.remove("d-none");

            // Ensure locationContainer is only shown when needed
            if (mode === 'face-to-face' || (mode === 'public-course' && inpersonCheckbox.checked)) {
                locationContainer.classList.remove("d-none");
            } else {
                locationContainer.classList.add("d-none");
            }
        }
    }

    // //  Force trigger checkbox logic after all scripts are loaded
    // setTimeout(function () {
    //     if (inpersonCheckbox.checked) {
    //         inpersonCheckbox.dispatchEvent(new Event('change'));
    //     }
    // }, 300);
});

var csrfToken = $('meta[name="csrf-token"]').attr('content');

document.addEventListener('DOMContentLoaded', function () {
    // Handle Cancel Button
    document.getElementById('cancel_training_button').addEventListener('click', function (e) {
        e.preventDefault(); // Prevent navigation
        // Show SweetAlert confirmation for cancel
        Swal.fire({
            title: 'Are you sure?',
            text: "Any unsaved changes will be lost. Do you want to proceed?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, cancel',
            cancelButtonText: 'Stay on page',
            customClass: {
                confirmButton: "btn btn-success",
                cancelButton: 'btn btn-secondary'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                // Redirect back to the calendar page
                window.location.href = "/calendar"; //Note: can't access blade functions in javascript file. Type out the whole route
            }
        });
    });


    $(document).ready(function () {
        $('input[name="mode"]').change(function () {
            const mode = $(this).val();
            clearFields(mode);
            updateLocationVisibility();
        });

        $('#inperson-training').change(function () {
            updateLocationVisibility();
        });

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
            } else {
                $('#credentials-container').remove('d-none');
                if (mode === 'face-to-face' || (mode === 'public-course' && isInPerson)) {
                    $('#location-container').removeClass('d-none');
                } else {
                    $('#location-container').addClass('d-none');
                }
            }
        }

        function clearFields(mode) {
            // Clear common fields
            $('#credentials, #company, #course, #public-course-select, #platform, #location')
                .val('')
                .trigger('change');

            const isInPerson = $('#inperson-training').is(':checked');
            // Hide/Show based on the mode
            if (mode === 'virtual') {
                $('#credentials-container').removeClass('d-none');
                $('#public-course-container, #company-container').addClass('d-none');
            } else if (mode === 'face-to-face') {
                $('#location-container').removeClass('d-none');
                $('#credentials-container, #public-course-container').addClass('d-none');
            } else if (mode === 'public-course') {
                $('#public-course-container, #credentials-container').removeClass('d-none');
                $('#location-container, #company-container').addClass('d-none');

                if (isInPerson) {
                    $('#location-container').removeClass('d-none');
                } else {
                    $('#location-container').addClass('d-none');
                }
            }
        }
    });

    document.getElementById('edit_training_submit').addEventListener('click', function (e) {
        e.preventDefault(); // Prevent default form submission

        // Identify selected mode of training
        let mode = $('input[name="mode"]:checked').val();
        let isValid = true;

        // Validation Logic
        let requiredFields = [
            'input[name="mode"]:checked', // Mode of Training (Radio)
            '#credentials',               // Account (Dropdown)
            '#company',                   // Company (Dropdown)
            '#course',                    // Course (Dropdown)
            '#date-range',                // Date Range (Input)
            '#time-start',                // Time Start (Input)
            '#time-end',                  // Time End (Input)
            '#facilitator',               // Facilitator
            '#location'                   // Location
        ];

        // Add mode-specific required fields
        if (mode === 'virtual') {
            requiredFields.push('#credentials'); // Virtual-specific
        } else if (mode === 'face-to-face') {
            requiredFields.push('#location');    // Face-to-Face-specific
        } else if (mode === 'public-course') {
            requiredFields.push('#public-course-select'); // Public Course-specific
        }

        requiredFields.forEach(function (selector) {
            let element = $(selector);

            // Skip hidden fields for other modes
            if (element.length === 0) {
                console.warn(`Element not found for selector: ${selector}`); // Debugging line
                return;
            }

            if (!element.is(':visible')) {
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
                if (element.val().trim() === '') {
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

        // Facilitator validation
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

        // Show SweetAlert if validation fails
        if (!isValid) {
            Swal.fire({
                title: 'Missing Fields!',
                text: 'Please fill in all required fields.',
                icon: 'warning',
                confirmButtonText: 'OK'
            });
            return; // Stop submission if validation fails
        }

        // Date handling
        let from_date = $('#date-range').data('start-date');
        let to_date = $('#date-range').data('end-date');

        // Fallback if data attributes are missing
        if (!from_date || !to_date) {
            const dateRange = $('#date-range').val().split(' to ');

            from_date = moment(dateRange[0], 'MM-DD-YYYY').format('YYYY-MM-DD');

            // If the date range has only one date, set to_date equal to from_date
            if (dateRange.length === 1 || !dateRange[1]) {
                to_date = from_date;
            } else {
                to_date = moment(dateRange[1], 'MM-DD-YYYY').format('YYYY-MM-DD');
            }
        }
        let facilitator_id = $('#facilitator').find('option:selected').val(); // Get facilitator ID

        // Step 1: Check if facilitator is provided
        if (!facilitator_id || facilitator_id === "") {
            // Skip checking availability and proceed

            console.log('1');

            handleCompanyAndStoreTraining(company);
        } else {
            // Check facilitator availability
            checkAvailability(facilitator_id, from_date, to_date, function (isAvailable) {
                if (isAvailable) {
                    console.log('2');
                    handleCompanyAndStoreTraining(company);
                } else {
                    console.log('3');
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
                editTraining(company);
            }
        }
            // Confirmation before submission
            Swal.fire({
                title: 'Are you sure?',
                text: "You are about to edit this training.",
                icon: 'warning',
                buttonsStyling: false,
                showCancelButton: true,
                confirmButtonText: 'Yes, Edit Training',
                cancelButtonText: 'Cancel',
                customClass: {
                    confirmButton: "btn btn-success",
                    cancelButton: 'btn btn-secondary'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    // Gathering data for submission
                    let facilitator_id = $('#facilitator').find('option:selected').val();
                    let assistant_id = '';

                    $('div[data-repeater-list="asst_repeat"] .assistant').each(function () {
                        const value = $(this).val().trim();
                        if (value) {
                            assistant_id += (assistant_id.length > 0 ? ', ' : '') + value;
                        }
                    });

                    console.log('From Date:', from_date);
                    console.log('To Date:', to_date);

                    let from_time = $('#time-start').val();
                    let to_time = $('#time-end').val();
                    let course = $('#course').find('option:selected').val() || $('#public-course-select').find('option:selected').val();
                    let platform = $('#platform').val();
                    let account_id = $('#credentials').find('option:selected').val();
                    let company = $('#company').find('option:selected').val();
                    let location = $('#location').val();


                    // Clear fields based on mode
                    if (mode === "virtual") {
                        location = ''; // Clear location for virtual mode
                    } else if (mode === "face-to-face") {
                        account_id = ''; // Clear account for face-to-face mode
                    } else if (mode === "public-course") {
                        if ($('#inperson-training').is(':checked')) {
                            account_id = ''; // In-person public course
                            location = $('#location').val();
                        } else {
                            location = ''; // Online public course
                        }
                    }

                    let data = {
                        course_id: course,
                        platform: platform,
                        location: location,
                        facilitator_id: facilitator_id,
                        company_id: company,
                        assistant: assistant_id,
                        account_id: account_id,
                        mode: mode,
                        from_date: from_date,
                        to_date: to_date,
                        from_time: from_time,
                        to_time: to_time
                    };

                    // Submit the form using AJAX
                    const url = window.location.href;
                    const match = url.match(/\/edit_training\/(\d+)$/);
                    const trainingId = match ? match[1] : '';
                    console.log(data);

                    $.ajax({
                        url: `/calendar/edit_training/${trainingId}`,
                        type: 'PUT',
                        data: JSON.stringify(data),
                        contentType: 'application/json',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken
                        },
                        success: function (response) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Saved!',
                                text: response.message,
                            }).then(() => {
                                window.location.href = '/calendar';
                            });
                        },
                        error: function (xhr, status, error, response) {
                            console.log('AJAX Error Details:');
                            console.log('Status:', status);
                            console.log('Error:', error);
                            console.log('Response Text:', xhr.responseText);
                            console.log('ReadyState:', xhr.readyState);
                            console.log('Response Status:', xhr.status);
                            Swal.fire({
                                title: 'Error!',
                                text: 'There was an error updating the training.',
                                icon: 'error',
                                confirmButtonText: 'OK'
                            });
                        }
                    });
                }
            });
        });
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
