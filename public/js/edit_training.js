document.addEventListener('DOMContentLoaded', function () {
    // Handle Cancel Button
    document.getElementById('cancel_training_button').addEventListener('click', function (e) {
        e.preventDefault(); // Prevent navigation
        window.location.href = '/calendar'; // Redirect to the calendar
    });

    // Handle Save/Edit Training
    document.getElementById('edit_training_submit').addEventListener('click', function (e) {
        e.preventDefault(); // Prevent default submission

        // Validate the form fields
        const mode = document.querySelector('input[name="mode"]:checked');
        const publicCourse = document.getElementById('public-course-select');
        const credentials = document.getElementById('credentials');
        const platform = document.getElementById('platform');
        const location = document.getElementById('location');
        const company = document.getElementById('company');
        const course = document.getElementById('course');
        const dateRange = document.getElementById('date-range');
        const timeStart = document.getElementById('time-start');
        const timeEnd = document.getElementById('time-end');
        const facilitator = document.getElementById('facilitator');
        const assistants = document.querySelectorAll('.assistant');

        let isValid = true;

        // Helper function to add validation classes
        const validateField = (field, condition) => {
            if (!condition) {
                field.classList.add('is-invalid');
                isValid = false;
            } else {
                field.classList.remove('is-invalid');
            }
        };

        // Validate fields
        validateField(mode, mode !== null);
        validateField(publicCourse, !publicCourse.classList.contains('d-none') ? publicCourse.value.trim() : true);
        validateField(credentials, credentials.value.trim());
        validateField(platform, platform.value.trim());
        validateField(location, !location.classList.contains('d-none') ? location.value.trim() : true);
        validateField(company, company.value.trim());
        validateField(course, course.value.trim());
        validateField(dateRange, dateRange.value.trim());
        validateField(timeStart, timeStart.value.trim());
        validateField(timeEnd, timeEnd.value.trim());
        validateField(facilitator, facilitator.value.trim());

        // Validate assistants if repeater is used
        assistants.forEach((assistant) => {
            validateField(assistant, assistant.value.trim());
        });

        // If the form is invalid, show an error message
        if (!isValid) {
            Swal.fire({
                icon: 'error',
                title: 'Validation Error',
                text: 'Please fill out all required fields before saving.',
            });
            return;
        }

        // Show confirmation before saving
        Swal.fire({
            title: 'Are you sure?',
            text: "You are about to save this training.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, Save Training',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                // Show success message
                Swal.fire({
                    icon: 'success',
                    title: 'Saved!',
                    text: 'The training has been successfully saved.',
                    timer: 1500,
                    showConfirmButton: false
                }).then(() => {
                    // Submit the form (or perform AJAX call)
                    document.querySelector('form').submit();
                });
            }
        });
    });
});
