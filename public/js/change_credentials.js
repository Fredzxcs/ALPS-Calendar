// Validation
document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('change_credentials_form');
    const usernameInput = document.getElementById('username');
    const passwordInput = document.getElementById('password');
    const colorInput = document.getElementById('color');

    // Form submission event
    form.addEventListener('submit', (event) => {
        event.preventDefault();

        let isValid = true;

        // Validate username
        if (!usernameInput.value.trim()) {
            usernameInput.classList.add('border-danger'); // Add Bootstrap danger border
            isValid = false;
        } else {
            usernameInput.classList.remove('border-danger');
        }

        // Validate password
        if (!passwordInput.value.trim()) {
            passwordInput.classList.add('border-danger'); // Add Bootstrap danger border
            isValid = false;
        } else {
            passwordInput.classList.remove('border-danger');
        }

        // Validate color
        if (!colorInput.value) {
            colorInput.classList.add('border-danger'); // Add Bootstrap danger border
            isValid = false;
        } else {
            colorInput.classList.remove('border-danger');
        }

        // If all fields are valid
        if (isValid) {
            Swal.fire({
                title: 'Are you sure?',
                text: "You are about to update these credentials.",
                icon: 'warning',
                buttonsStyling: false,
                showCancelButton: true,
                confirmButtonText: 'Yes, Update it',
                cancelButtonText: 'Cancel',
                customClass: {
                    confirmButton: "btn btn-success",
                    cancelButton: 'btn btn-secondary'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire(
                        'Updated!',
                        'The credentials have been updated.',
                        'success'
                    );
                    // TODO: Add logic to perform credential changes here
                }
            });
        }
    });

    // Remove danger border on input change
    [usernameInput, passwordInput, colorInput].forEach(input => {
        input.addEventListener('input', () => {
            if (input.value.trim()) {
                input.classList.remove('border-danger');
            }
        });
    });
});



//Toggle password visibility
$('.togglePassword').on('click', function () {
    const input = $($(this).data('target'));
    const icons = $(this).find('i');
    
    if (input.attr('type') === 'password') {
        input.attr('type', 'text');
        icons.first().addClass('d-none');
        icons.last().removeClass('d-none');
    } else {
        input.attr('type', 'password');
        icons.first().removeClass('d-none');
        icons.last().addClass('d-none');
    }
});



