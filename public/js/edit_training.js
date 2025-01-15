document.addEventListener('DOMContentLoaded', function () {
    // Handle Cancel Button
    document.getElementById('cancel_training_button').addEventListener('click', function (e) {
        e.preventDefault(); // Prevent navigation
        window.location.href = '/calendar'; // Redirect to the calendar
    });

    // Handle Save/Edit Training
    document.getElementById('edit_training_submit').addEventListener('click', function (e) {
        e.preventDefault(); // Prevent default submission

        // Collect form fields
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

        // Helper function for validation
        const validateField = (field, condition) => {
            if (!condition) {
                field?.classList.add('is-invalid');
                isValid = false;
            } else {
                field?.classList.remove('is-invalid');
            }
        };

        // Validate fields
        validateField(mode, mode !== null); // Ensure mode is selected
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

        assistants.forEach((assistant) => {
            validateField(assistant, assistant.value.trim());
        });

        // If the form is invalid, show validation error
        if (!isValid) {
            Swal.fire({
                icon: 'error',
                title: 'Validation Error',
                text: 'Please fill out all required fields before saving.',
            });
            return;
        }

        // Confirmation prompt before saving
        Swal.fire({
            title: 'Are you sure?',
            text: 'You are about to save this training.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, Save Training',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                // Success message
                Swal.fire({
                    icon: 'success',
                    title: 'Saved!',
                    text: 'The training has been successfully saved.',
                    timer: 1500,
                    showConfirmButton: false
                }).then(() => {
                    // Submit the form
                    document.querySelector('form').submit();
                });
            }
        });
    });

    // Remove 'is-invalid' class on input change
    document.querySelectorAll('#public-course-select, #credentials, #platform, #location, #company, #course, #date-range, #time-start, #time-end, #facilitator, .assistant').forEach(input => {
        input.addEventListener('input', () => {
            if (input.value.trim()) {
                input.classList.remove('is-invalid');
            }
        });
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
