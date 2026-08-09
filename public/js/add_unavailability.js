// Validation
var csrfToken = $('meta[name="csrf-token"]').attr('content');

// Date Range
let startDateFormatted;
let endDateFormatted;

function formatDate(date) {
    const day = date.getDate().toString().padStart(2, '0'); // Add leading zero for day
    const month = (date.getMonth() + 1).toString().padStart(2, '0'); // Get month, adjust by +1 (months are 0-based)
    const year = date.getFullYear(); // Get full year

    return `${year}-${month}-${day}`; // Return in YYYY-MM-DD format
}


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

document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('add_unavailability_form');
    const dateInput = document.getElementById('add_unavailable_date');
    const purposeInput = document.getElementById('add_unavailable_purpose');

    // Form submission event
    form.addEventListener('submit', (event) => {
        event.preventDefault();

        let isValid = true;

        let formData = new FormData();

        formData.append('reason', purposeInput.value.trim());
        formData.append('from_date', startDateFormatted);
        formData.append('user_id', user);
        formData.append('to_date', endDateFormatted || '');

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

        if (!isValid) {
             Swal.fire({
                title: 'Missing Fields!',
                text: 'Please fill in all required fields.',
                icon: 'warning',
                confirmButtonText: 'OK'
            });
            return; // Stop submission if validation fails
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

                    $.ajax({
                        url: '/calendar/add_unavailability/store',
                        type: 'POST',
                        data: formData,
                        processData: false,
                        contentType: false,
                        cache: false,
                        headers: {
                            'X-CSRF-TOKEN': csrfToken // Add CSRF token to headers
                        },
                        success: function(response) {
                            if (response.message === 200) {
                                Swal.fire({
                                    title: 'Added!',
                                    text: 'The unavailability has been scheduled.',
                                    icon: 'success'
                                }).then((result) =>{

                                    window.location.href = '/calendar';

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
                            handleAjaxError(xhr);
                        }
                    });
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

