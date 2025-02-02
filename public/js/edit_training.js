document.addEventListener("DOMContentLoaded", function () {
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
        // Show SweetAlert confirmation for cancel
        Swal.fire({
            title: 'Are you sure?',
            text: "Any unsaved changes will be lost. Do you want to proceed?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, cancel',
            cancelButtonText: 'Stay on page'
        }).then((result) => {
            if (result.isConfirmed) {
                // Redirect back to the calendar page
                window.location.href = "/calendar"; //Note: can't access blade functions in javascript file. Type out the whole route
            }
    });
});


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
            // Reset location validation for other modes
            setValid(location);
        }

        // // Validate facilitator (common for all modes)
        // if (!facilitator.value.trim()) {
        //     setInvalid(facilitator);
        //     isValid = false;
        // } else {
        //     setValid(facilitator);
        // }

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

                let from_date = $('#date-range').data('start-date');
                let to_date = $('#date-range').data('end-date');
                let from_time = $('#time-start').val();
                let to_time = $('#time-end').val();
                let course = $('#course').find('option:selected').val() || $('#public-course-select').find('option:selected').val();
                let platform = $('#platform').val();

                //account
                let account_id = $('#credentials').find('option:selected').val();

                //company
                let company = $('#company').find('option:selected').val();

                //location
                let location = $('#location').val();

                //clear other values

                let mode = $('input[name="mode"]:checked').val();

                if(mode === "virtual") //virtual - clear location
                {
                    console.log('virtual mode');
                    location = '';
                }
                else if (mode === "face-to-face") //f2f - clear email and platform
                {
                    console.log('f2f mode');
                    account_id = '';
                }
                else if (mode === "public-course")
                {
                    //check if in-person
                    if(inpersonCheckbox.checked) //if f2f - clear email and platform
                    {
                        console.log('public mode - inperson');
                        account_id = '';
                    }
                    else //if virtual - clear location
                    {
                        console.log('public mode - online');
                        location = '';
                    }
                }

                var data = {
                    course_id: course,
                    platform: platform,
                    location: location.value,
                    facilitator_id: facilitator_id,
                    company_id: company,
                    assistant: assistant_id,
                    account_id: account_id,
                    mode: mode,
                    from_date: startDateFormatted,
                    to_date: endDateFormatted,
                    from_time: from_time,
                    to_time: to_time
                };

                const url = window.location.href;
                const match = url.match(/\/edit_training\/(\d+)$/);
                const trainingId = match ? match[1] : '';

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
