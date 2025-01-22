// Validation
document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('change_credentials_form');
    const usernameInput = document.getElementById('username');
    const passwordInput = document.getElementById('password');
    const colorInput = document.getElementById('color');
    const passwordFeedback = passwordInput.nextElementSibling; // Locate the "Required field" div

    // Form submission event
    form.addEventListener('submit', (event) => {
        event.preventDefault();

        let isValid = true;

        // Validate username
        if (!usernameInput.value.trim()) {
            usernameInput.classList.add('is-invalid');
            isValid = false;
        } else {
            usernameInput.classList.remove('is-invalid');
        }

        // Validate password (do not add is-invalid class)
        if (!passwordInput.value.trim()) {
            passwordFeedback.style.display = 'block'; // Show the "Required field" message
            isValid = false;
        } else {
            passwordFeedback.style.display = 'none'; // Hide the message if valid
        }

        // Validate color
        if (!colorInput.value) {
            colorInput.classList.add('is-invalid');
            isValid = false;
        } else {
            colorInput.classList.remove('is-invalid');
        }

        // If all fields are valid
        if (isValid) {
            Swal.fire({
                title: 'Are you sure?',
                text: "You are about to change these credentials.",
                icon: 'warning',
                buttonsStyling: false,
                showCancelButton: true,
                confirmButtonText: 'Yes, Change it',
                cancelButtonText: 'Cancel',
                customClass: {
                    confirmButton: "btn btn-success",
                    cancelButton: 'btn btn-secondary'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire(
                        'Edited!',
                        'The credentials have been changed.',
                        'success'
                    );
                    // TODO: Add logic to perform credential changes here
                }
            });
        }
    });

    // Remove invalid class on input change and manage password message visibility
    [usernameInput, passwordInput, colorInput].forEach(input => {
        input.addEventListener('input', () => {
            if (input.value.trim()) {
                input.classList.remove('is-invalid');
                if (input === passwordInput) {
                    passwordFeedback.style.display = 'none';
                }
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



