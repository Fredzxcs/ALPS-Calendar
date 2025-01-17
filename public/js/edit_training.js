document.addEventListener("DOMContentLoaded", function () {
    let startDateFormatted = '';
    let endDateFormatted = '';

    // Initialize Flatpickr for Date Range
    const dateRangePicker = flatpickr("#date-range", {
        mode: "range",
        dateFormat: "Y-m-d", // Ensure the format matches your backend format
        onChange: function (selectedDates) {
            if (selectedDates.length > 0) {
                startDateFormatted = selectedDates[0].toISOString().split('T')[0]; // Convert to YYYY-MM-DD
                endDateFormatted = selectedDates[selectedDates.length - 1].toISOString().split('T')[0]; // Handle single-day or multi-day
                console.log("Start Date:", startDateFormatted);
                console.log("End Date:", endDateFormatted);
            } else {
                startDateFormatted = ''; // Reset if no date selected
                endDateFormatted = '';
            }
        }
    });

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

    inpersonCheckbox.addEventListener("change", function () {
        if (inpersonCheckbox.checked) {
            credentialsContainer.classList.add("d-none");
            locationContainer.classList.remove("d-none");
        } else {
            credentialsContainer.classList.remove("d-none");
            locationContainer.classList.add("d-none");
        }
    });

    document.getElementById('cancel_training_button').addEventListener('click', function (e) {
        e.preventDefault();
        window.location.href = '/calendar';
    });

    document.getElementById('edit_training_submit').addEventListener('click', function (e) {
        e.preventDefault();

        function setInvalid(input) {
            input.classList.add('border-danger');
            const feedback = input.nextElementSibling;
            if (feedback && feedback.classList.contains('invalid-feedback')) {
                feedback.classList.add('d-block');
            }
        }

        function setValid(input) {
            input.classList.remove('border-danger');
            const feedback = input.nextElementSibling;
            if (feedback && feedback.classList.contains('invalid-feedback')) {
                feedback.classList.remove('d-block');
            }
        }

        let isValid = true;

        // Validate date range
        if (!startDateFormatted || !endDateFormatted) {
            setInvalid(document.getElementById('date-range'));
            Swal.fire({
                icon: 'error',
                title: 'Validation Error',
                text: 'Please select a valid date range.',
            });
            isValid = false;
        } else {
            setValid(document.getElementById('date-range'));
        }

        const mode = document.querySelector('input[name="mode"]:checked')?.value;
        const location = document.getElementById('location');
        const facilitator = document.getElementById('facilitator');

        if (mode === 'face-to-face') {
            if (!location.value.trim()) {
                setInvalid(location);
                isValid = false;
            } else {
                setValid(location);
            }
        } else {
            setValid(location);
        }

        if (!facilitator.value.trim()) {
            setInvalid(facilitator);
            isValid = false;
        } else {
            setValid(facilitator);
        }

        if (!isValid) {
            Swal.fire({
                icon: 'error',
                title: 'Validation Error',
                text: 'Please fill out all required fields before proceeding.',
            });
            return;
        }

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
                let facilitator_id = $('#facilitator').find('option:selected').val();
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

                let from_time = $('#time-start').val();
                let to_time = $('#time-end').val();
                let course = $('#course').find('option:selected').val() || $('#public-course-select').find('option:selected').val();
                let platform = $('#platform').val();
                let credentials_email = $('#credentials').find('option:selected').text();
                let credentials_password = $('#credentials').find('option:selected').val();
                let company = $('#company').find('option:selected').val();

                if (mode === "virtual") {
                    location.value = '';
                } else if (mode === "face-to-face") {
                    credentials_email = '';
                    credentials_password = '';
                } else if (mode === "public-course") {
                    if (inpersonCheckbox.checked) {
                        credentials_email = '';
                        credentials_password = '';
                    } else {
                        location.value = '';
                    }
                }

                var data = {
                    course: course,
                    platform: platform,
                    location: location.value,
                    facilitator_id: facilitator_id,
                    company: company,
                    assistant_id: assistant_id,
                    credentials_email: credentials_email,
                    credentials_password: credentials_password,
                    mode: mode,
                    from_date: startDateFormatted,
                    to_date: endDateFormatted,
                    from_time: from_time,
                    to_time: to_time
                };

                console.log(data);

                const url = window.location.href;
                const match = url.match(/\/edit_training\/(\d+)$/);
                let trainingId = match ? match[1] : '';

                $.ajax({
                    url: '/calendar/edit_training/' + trainingId,
                    type: 'PUT',
                    data: JSON.stringify(data),
                    contentType: 'application/json',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    },
                    success: function (response, xhr) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Saved!',
                            text: response.message,
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.href = '/calendar';
                            }
                        });
                    },
                    error: function (xhr, status, error) {
                        console.error('AJAX Error:', error);
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
