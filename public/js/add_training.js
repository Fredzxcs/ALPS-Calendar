var csrfToken = $('meta[name="csrf-token"]').attr('content');
document.addEventListener("DOMContentLoaded", function () {
    const modeRadios = document.querySelectorAll('input[name="mode"]');
    const companyContainer = document.getElementById("company-container");
    const credentialsContainer = document.getElementById("credentials-container");
    const locationContainer = document.getElementById("location-container");
    const inpersonCheckbox = document.getElementById("inperson-training");
    const companyCourseContainer = document.getElementById("company-course-container");
    const publicCourseContainer = document.getElementById("public-course-container");

    // Mode of Training Logic
    modeRadios.forEach(radio => {
        radio.addEventListener("change", function () {
            if (radio.id === "virtual") {
                // Virtual: Show Email/Password, hide others
                credentialsContainer.classList.remove("d-none");
                locationContainer.classList.add("d-none");
                publicCourseContainer.classList.add("d-none");
                companyCourseContainer.classList.remove("d-none");
            } else if (radio.id === "face-to-face") {
                // Face-to-Face: Show Location, hide Email/Password
                credentialsContainer.classList.add("d-none");
                locationContainer.classList.remove("d-none");
                publicCourseContainer.classList.add("d-none");
                companyCourseContainer.classList.remove("d-none");
            } else if (radio.id === "public-course") {
                // Public Course: Show Public Course layout, hide Company/Course
                credentialsContainer.classList.remove("d-none");
                publicCourseContainer.classList.remove("d-none");
                companyCourseContainer.classList.add("d-none");
                locationContainer.classList.add("d-none");
            }
        });
    });

    // In-person Checkbox Logic
    inpersonCheckbox.addEventListener("change", function () {
        if (inpersonCheckbox.checked) {
            credentialsContainer.classList.add("d-none");
            locationContainer.classList.remove("d-none");
        } else {
            credentialsContainer.classList.remove("d-none");
            locationContainer.classList.add("d-none");
        }
    });
});

function formatDate(date) {
    const day = date.getDate().toString().padStart(2, '0'); // Add leading zero for day
    const month = (date.getMonth() + 1).toString().padStart(2, '0'); // Get month, adjust by +1 (months are 0-based)
    const year = date.getFullYear(); // Get full year

    return `${year}-${month}-${day}`; // Return in YYYY-MM-DD format
}

let startDateFormatted;
let endDateFormatted;

const fp = flatpickr("#date-range", {
    mode: "range",
    dateFormat: "m-d-Y",
    onChange: function(selectedDates) {
        if (selectedDates.length >= 2) {
            const initalStartDate = selectedDates[0];
            const initialEndDate = selectedDates[1];
            startDateFormatted = formatDate(initalStartDate);
            endDateFormatted = formatDate(initialEndDate);
            console.log("Start Date:", startDateFormatted);
            console.log("End Date:", endDateFormatted);
        }
    }
});

$(document).ready(function (e) {
    $('#add_training_submit').click(function (e) {
        e.preventDefault();

        let mode = $('input[name="mode"]:checked').val();
        let facilitator_id = $('#facilitator').find('option:selected').val(); // Get facilitator ID

        let assistant_id = '';
        $('div[data-repeater-list="asst_repeat"] .assistant').each(function () {
            const value = $(this).val().trim();
            if (value) {
                if (assistant_id.length > 0) {
                    assistant_id += ', ';
                }
                assistant_id += value;
            }
        });

        let from_date = startDateFormatted;
        let to_date = endDateFormatted;
        let from_time = $('#time-start').val();
        let to_time = $('#time-end').val();
        let course = $('#course').find('option:selected').val() || $('#public-course-select').find('option:selected').val();
        let platform = $('#platform').val();
        let account_id = $('#credentials').find('option:selected').val();
        let location = $('#location').val();
        let company = $('#company').find('option:selected').val();

        // Step 1: Check if facilitator is provided
        if (!facilitator_id || facilitator_id === "") {
            // Skip checking availability and proceed
            handleCompanyAndStoreTraining(company);
        } else {
            // Check facilitator availability
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
                            confirmButton: "btn btn-primary",
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
        if (company === "other") {
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
            createTraining(company);
        }
    }

    // Step 4: Function to create the training session
    function createTraining(companyId) {
        let formData = new FormData();
        formData.append('course_id', $('#course').find('option:selected').val());
        formData.append('platform', $('#platform').val());
        formData.append('location', $('#location').val());
        formData.append('facilitator_id', $('#facilitator').find('option:selected').val() || ''); // Allow empty facilitator
        formData.append('company_id', companyId);
        formData.append('assistant', $('div[data-repeater-list="asst_repeat"] .assistant').map(function () { return $(this).val().trim(); }).get().join(', '));
        formData.append('account_id', $('#credentials').find('option:selected').val());
        formData.append('mode', $('input[name="mode"]:checked').val());
        formData.append('from_date', startDateFormatted);
        formData.append('to_date', endDateFormatted);
        formData.append('from_time', $('#time-start').val());
        formData.append('to_time', $('#time-end').val());

        $.ajax({
            url: '/calendar/add_training',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            cache: false,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function (response) {
                if (response.message === '200') {
                    Swal.fire({
                        title: 'Success!',
                        text: 'Training has been added.',
                        icon: 'success',
                        confirmButtonText: 'OK'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = '/calendar';
                        }
                    });
                } else {
                    Swal.fire({
                        title: 'Wait!',
                        text: response.message,
                        icon: 'warning',
                        confirmButtonText: 'OK'
                    }).then(() => {
                        location.reload();
                    });
                }
            },
            error: function (xhr, status, error) {
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
