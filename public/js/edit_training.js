document.addEventListener('DOMContentLoaded', function () {
    // Handle Cancel Button
    document.getElementById('cancel_training_button').addEventListener('click', function (e) {
        e.preventDefault(); // Prevent navigation
        window.location.href = '/calendar'; // Redirect to the calendar
    });

    // Handle Save/Edit Training
    document.getElementById('edit_training_submit').addEventListener('click', function (e) {
        e.preventDefault(); // Prevent default submission

        Swal.fire({
            title: 'Are you sure?',
            text: "You are about to edit this training.",
            icon: 'warning',
            buttonsStyling: false,
            showCancelButton: true,
            confirmButtonText: 'Yes, Edit Training',
            cancelButtonText: 'Cancel',
            customClass: {
                confirmButton: 'btn btn-success',
                cancelButton: 'btn btn-secondary'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    icon: 'success',
                    title: 'Edited!',
                    text: 'The training has been edited.',
                    timer: 1500,
                    showConfirmButton: false
                }).then(() => {
                    // TODO: Add logic to perform edit course
                    console.log('Perform the edit course logic here.');
                });
            }
        });
    });
});
