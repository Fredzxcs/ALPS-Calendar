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
    // Handle Save Button
    document.getElementById('edit_training_submit').addEventListener('click', function (e) {
        e.preventDefault(); // Prevent default submission

        // Show confirmation if changes were made
        Swal.fire({
            title: 'Are you sure?',
            text: "Do you want to save the changes?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, save it!',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                //faci
                let facilitator_id = $('#facilitator').find('option:selected').val();

                //asst
                let assistant_id = '';

                $('div[data-repeater-list="asst_repeat"] .assistant').each(function() {
                    const value = $(this).val().trim();

                    if (value) {
                        if (assistant_id.length > 0) {
                            assistant_id += ', ';
                        }
                        assistant_id += value;
                    }
                });

                //dates
                let from_date = startDateFormatted;
                let to_date = endDateFormatted;

                //time
                let from_time = $('#time-start').val();
                let to_time = $('#time-end').val();

                //course
                let course = $('#course').find('option:selected').val() || $('#public-course-select').find('option:selected').val();

                let platform = $('#platform').val();

                //account
                let credentials_email = $('#credentials').find('option:selected').text();
                let credentials_password = $('#credentials').find('option:selected').val();

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
                    credentials_email = '';
                    credentials_password = '';
                }
                else if (mode === "public-course")
                {
                    //check if in-person
                    if(inpersonCheckbox.checked) //if f2f - clear email and platform
                    {
                        console.log('public mode - inperson');
                        credentials_email = '';
                        credentials_password = '';
                    }
                    else //if virtual - clear location
                    {
                        console.log('public mode - online');
                        location = '';
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
                    contentType: 'application/json', // Set content type to JSON
                    headers: {
                        'X-CSRF-TOKEN': csrfToken // Add CSRF token to headers
                    },
                    success: function(response, xhr) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Saved!',
                                text: response.message,
                            }).then((result) => {
                                if(result.isConfirmed)
                                {
                                    window.location.href = '/calendar';
                                }
                            });
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
                            text: 'There was an error updating the training .',
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                    }
                });

            }
        });
    });
});
