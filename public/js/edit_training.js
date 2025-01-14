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
                window.location.href = "{{ route('calendar') }}";
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
                // Show success message after save
                Swal.fire({
                    icon: 'success',
                    title: 'Saved!',
                    text: 'Your changes have been successfully saved.',
                    timer: 1500,
                    showConfirmButton: false
                }).then(() => {
                    // Submit the form
                    document.getElementById('editTrainingForm').submit();
                });
            }
        });
    });
});
