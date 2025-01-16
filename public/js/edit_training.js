document.addEventListener('DOMContentLoaded', function () {
    // Handle Cancel Button
    document.getElementById('cancel_training_button').addEventListener('click', function (e) {
        e.preventDefault(); // Prevent navigation
        window.location.href = '/calendar'; // Redirect to the calendar
    });

    // Helper function to format dates
    function formatDate(date) {
        const day = date.getDate().toString().padStart(2, '0'); // Add leading zero for day
        const month = (date.getMonth() + 1).toString().padStart(2, '0'); // Get month, adjust by +1 (months are 0-based)
        const year = date.getFullYear(); // Get full year

        return `${year}-${month}-${day}`; // Return in YYYY-MM-DD format
    }

    let startDateFormatted;
    let endDateFormatted;

    // Initialize flatpickr for date range
    const fp = flatpickr("#date-range", {
        mode: "range",
        dateFormat: "m-d-Y",
        onChange: function (selectedDates) {
            if (selectedDates.length >= 2) {
                const initialStartDate = selectedDates[0];
                const initialEndDate = selectedDates[1];
                startDateFormatted = formatDate(initialStartDate);
                endDateFormatted = formatDate(initialEndDate);
                console.log("Start Date:", startDateFormatted);
                console.log("End Date:", endDateFormatted);
            }
        }
    });

    // Handle Save/Edit Training Button
    document.getElementById('edit_training_submit').addEventListener('click', function (e) {
        e.preventDefault(); // Prevent default submission

        // Show confirmation SweetAlert
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
                // Show success SweetAlert after confirmation
                Swal.fire({
                    icon: 'success',
                    title: 'Edited!',
                    text: 'The training has been successfully edited.',
                    timer: 1500,
                    showConfirmButton: false
                }).then(() => {
                    console.log("Start Date:", startDateFormatted);
                    console.log("End Date:", endDateFormatted);
                    console.log("Submit training logic goes here.");
                    // TODO: Add logic to perform edit training
                    // Submit form or redirect
                    window.location.href = '/calendar'; // Example redirection
                });
            }
        });
    });
});
