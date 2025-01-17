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

$(document).ready(function (e){

    $('#add_training_submit').click(function (e){

        e.preventDefault();
        //get radio val to get mode of training
        let mode = $('input[name="mode"]:checked').val();

        console.log(mode);

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

        console.log(assistant_id);

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

        let formData = new FormData();

        formData.append('course_id', course);
        formData.append('platform', platform);
        formData.append('location', location);
        formData.append('facilitator_id', facilitator_id);
        formData.append('company_id', company);
        formData.append('assistant', assistant_id);
        formData.append('credentials_email', credentials_email);
        formData.append('credentials_password', credentials_password);
        formData.append('mode', mode);

        formData.append('from_date', from_date);
        formData.append('to_date', to_date);
        formData.append('from_time', from_time);
        formData.append('to_time', to_time);

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
