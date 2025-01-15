document.addEventListener('DOMContentLoaded', function () {
    // Handle Cancel Button
    document.getElementById('cancel_training_button').addEventListener('click', function (e) {
        e.preventDefault(); // Prevent navigation
        window.location.href = '/calendar'; // Redirect to the calendar
    });

    // Handle Save/Edit Training
    document.getElementById('edit_training_submit').addEventListener('click', function (e) {
        e.preventDefault(); // Prevent default submission

        // Show SweetAlert for successful training edit
        Swal.fire({
            title: 'Success!',
            text: 'Training has been successfully edited.',
            icon: 'success',
            confirmButtonText: 'OK'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = '/calendar'; // Redirect to calendar
            }
        });
    });
});
