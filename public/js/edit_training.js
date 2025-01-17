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

var csrfToken = $('meta[name="csrf-token"]').attr('content');

document.addEventListener('DOMContentLoaded', function () {
    // Handle Cancel Button
    document.getElementById('cancel_training_button').addEventListener('click', function (e) {
        e.preventDefault(); // Prevent navigation
        window.location.href = '/calendar'; // Redirect to the calendar
    });
    //     // Show SweetAlert confirmation for cancel
    //     Swal.fire({
    //         title: 'Are you sure?',
    //         text: "Any unsaved changes will be lost. Do you want to proceed?",
    //         icon: 'warning',
    //         showCancelButton: true,
    //         confirmButtonColor: '#d33',
    //         cancelButtonColor: '#3085d6',
    //         confirmButtonText: 'Yes, cancel',
    //         cancelButtonText: 'Stay on page'
    //     }).then((result) => {
    //         if (result.isConfirmed) {
    //             // Redirect back to the calendar page
    //             window.location.href = "/calendar"; //Note: can't access blade functions in javascript file. Type out the whole route
    //         }
    //     }
    // });
    document.getElementById('edit_training_submit').addEventListener('click', function (e) {
        e.preventDefault(); // Prevent default submission

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

        // Get the mode of training
        const mode = document.querySelector('input[name="mode"]:checked')?.value;
        const location = document.getElementById('location');
        const facilitator = document.getElementById('facilitator');

        // Validate location only for "face-to-face" mode
        if (mode === 'face-to-face') {
            if (!location.value.trim()) {
                setInvalid(location);
                isValid = false;
            } else {
                setValid(location);
            }
        } else {
            setValid(location); // Reset validation for other modes
        }

        // Validate facilitator
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

        // Show confirmation SweetAlert if validation passes
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
                // Existing logic for gathering data and making AJAX request

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

                let from_date = startDateFormatted;
                let to_date = endDateFormatted;
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
                    location: location,
                    facilitator_id: facilitator_id,
                    company: company,
                    assistant_id: assistant_id,
                    credentials_email: credentials_email,
                    credentials_password: credentials_password,
                    mode: mode,
                    from_date: from_date,
                    to_date: to_date,
                    from_time: from_time,
                    to_time: to_time
                };

                var jsonData = JSON.stringify(data);

                console.log(jsonData);

                //ajax request

                // Get the current URL
                const url = window.location.href;

                // Use a regular expression to extract the ID (assuming it's the last part of the URL)
                const match = url.match(/\/edit_training\/(\d+)$/);

                // If a match is found, the ID will be in the first capturing group
                let trainingId = '';

                if (match) {
                    trainingId = match[1];
                    console.log(trainingId);
                }

                $.ajax({
                    url: '/calendar/edit_training/' + trainingId,
                    type: 'PUT',
                    data: jsonData,
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
