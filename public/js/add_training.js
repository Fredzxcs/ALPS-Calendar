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

        // Validation Logic
        let requiredFields = [
            'input[name="mode"]:checked',   // Mode of Training (Radio)
            '#credentials',                 // Account (Dropdown)
            '#company',                     // Company (Dropdown/Input)
            '#course',                      // Course (Dropdown)
            '#date-range',                  // Date Range (Input)
            '#time-start',                  // Time Start (Input)
            '#time-end',                    // Time End (Input)
            '#facilitator'
        ];

        let isValid = true;

        requiredFields.forEach(function (selector) {
            let element = $(selector);

            if (selector === 'input[name="mode"]:checked') {
                if ($('input[name="mode"]:checked').length === 0) {
                    $('input[name="mode"]').closest('.form-group').addClass('border-danger');
                    isValid = false;
                } else {
                    $('input[name="mode"]').closest('.form-group').removeClass('border-danger');
                }
            } else if (element.is('select')) { // Handle dropdown validation
                if (element.val() === '' || element.val() === null) {
                    element.addClass('border-danger');
                    isValid = false;
                } else {
                    element.removeClass('border-danger');
                }
            } else { // Handle input fields
                if (element.val().trim() === '') {
                    element.addClass('border-danger');
                    isValid = false;
                } else {
                    element.removeClass('border-danger');
                }
            }
        });

        // Facilitator Validation
        let facilitator = $('#facilitator').val();
        if (facilitator === '' || facilitator === null) {
            // If no facilitator is selected, mark as invalid
            $('#facilitator').addClass('border-danger');
            isValid = false;
        } else {
            // If 'No Facilitator Yet' or any other valid option is selected, mark as valid
            $('#facilitator').removeClass('border-danger');
        }


        if (!isValid) {
            Swal.fire({
                title: 'Missing Fields!',
                text: 'Please fill in all required fields.',
                icon: 'warning',
                confirmButtonText: 'OK'
            });
            return; // Stop submission if validation fails
        }

        // Form Data Collection
        let mode = $('input[name="mode"]:checked').val();
        let facilitator_id = (facilitator === 'no_facilitator') ? '' : facilitator;
        let assistant_id = '';

        $('div[data-repeater-list="asst_repeat"] .assistant').each(function () {
            const value = $(this).val().trim();
            if (value) {
                assistant_id += (assistant_id.length > 0 ? ', ' : '') + value;
            }
        });

        let from_date = startDateFormatted;
        let to_date = endDateFormatted;
        let from_time = $('#time-start').val();
        let to_time = $('#time-end').val();
        let course = $('#course').find('option:selected').val() || $('#public-course-select').find('option:selected').val();
        let platform = $('#platform').val();
        let account_id = $('#credentials').find('option:selected').val();
        let company = $('#company').val().trim(); // Updated to get text input value
        let location = $('#location').val();

        let formData = new FormData();
        formData.append('course_id', course);
        formData.append('platform', platform);
        formData.append('location', location);
        formData.append('facilitator_id', facilitator_id);
        formData.append('assistant', assistant_id);
        formData.append('account_id', account_id);
        formData.append('mode', mode);
        formData.append('from_date', from_date);
        formData.append('to_date', to_date);
        formData.append('from_time', from_time);
        formData.append('to_time', to_time);

        // Check if the company is numeric (dropdown) or text (new company)
        if ($.isNumeric(company)) {
            formData.append('company_id', company);
        } else {
            formData.append('company_name', company);
        }

        for (let [key, value] of formData.entries()) {
            console.log(`${key}: ${value}`);
        }

        //ajax
        $.ajax({
            url: '',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            cache: false,
            headers: {
                'X-CSRF-TOKEN': csrfToken // Add CSRF token to headers
            },
            success: function(response) {
                if (response.message === '200') {
                    Swal.fire({
                        title: 'Success!',
                        text: 'Training has been added.',
                        icon: 'success',
                        confirmButtonText: 'OK'
                    }).then((result) => {
                        if(result.isConfirmed)
                        {
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
            error: function(xhr, status, error, response) {
                console.log('AJAX Error Details:');
                console.log('Status:', status);
                console.log('Error:', error);
                console.log('Response Text:', xhr.responseText);
                console.log('ReadyState:', xhr.readyState);
                console.log('Response Status:', xhr.status);
                Swal.fire({
                    title: 'Error!',
                    text: response.message || 'There was an error adding the user.',
                    icon: 'error',
                    confirmButtonText: 'OK'
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
