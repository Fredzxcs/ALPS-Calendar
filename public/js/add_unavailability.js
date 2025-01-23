// Validation
document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('add_unavailability_form');
    const dateInput = document.getElementById('add_unavailable_date');
    const purposeInput = document.getElementById('add_unavailable_purpose');

    // Form submission event
    form.addEventListener('submit', (event) => {
        event.preventDefault();

        let isValid = true;

        // Validate date
        if (!dateInput.value.trim()) {
            dateInput.classList.add('border-danger'); // Add Bootstrap danger border
            isValid = false;
        } else {
            dateInput.classList.remove('border-danger');
        }

        // Validate purpose
        if (!purposeInput.value.trim()) {
            purposeInput.classList.add('border-danger'); // Add Bootstrap danger border
            isValid = false;
        } else {
            purposeInput.classList.remove('border-danger');
        }

        // If all fields are valid
        if (isValid) {
            Swal.fire({
                title: 'Are you sure?',
                text: "You are about to schedule unavailability.",
                icon: 'warning',
                buttonsStyling: false,
                showCancelButton: true,
                confirmButtonText: 'Yes, Schedule it',
                cancelButtonText: 'Cancel',
                customClass: {
                    confirmButton: "btn btn-success",
                    cancelButton: 'btn btn-secondary'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire(
                        'Added!',
                        'The unavailability has been scheduled.',
                        'success'
                    );
                    // TODO: Add logic to perform add here
                }
            });
        }
    });

    // Remove danger border on input change
    [dateInput, purposeInput].forEach(input => {
        input.addEventListener('input', () => {
            if (input.value.trim()) {
                input.classList.remove('border-danger');
            }
        });
    });
});


// Date Range
let startDateFormatted;
let endDateFormatted;

// Get tomorrow's date
const tomorrow = new Date();
tomorrow.setDate(tomorrow.getDate() + 1);

// Format tomorrow's date as "m-d-Y"
const formattedTomorrow = flatpickr.formatDate(tomorrow, "m-d-Y");

const fp = flatpickr("#add_unavailable_date", {
    mode: "range",
    dateFormat: "m-d-Y",
    minDate: formattedTomorrow, // Set tomorrow as the minimum date
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

