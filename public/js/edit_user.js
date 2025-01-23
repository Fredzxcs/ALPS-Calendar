// Stepper lement
var element = document.querySelector("#kt_stepper_example_basic");

// Initialize Stepper
var stepper = new KTStepper(element);

// Handle next step
stepper.on("kt.stepper.next", function (stepper) {
    stepper.goNext(); // go next step
});

// Handle previous step
stepper.on("kt.stepper.previous", function (stepper) {
    stepper.goPrevious(); // go previous step
});

// Validation
// Validation
document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('edit_user_form');
    const firstnameInput = document.getElementById('edit_first_name');
    const lastnameInput = document.getElementById('edit_last_name');
    const emailInput = document.getElementById('edit_email');
    const contactNumberInput = document.getElementById('edit_contact_number');
    const idPictureInput = document.getElementById('edit_id_picture');

    // Form submission event
    form.addEventListener('submit', (event) => {
        event.preventDefault();

        let isValid = true;

        // Validate first name
        if (!firstnameInput.value.trim()) {
            firstnameInput.classList.add('border-danger'); // Add Bootstrap danger border
            isValid = false;
        } else {
            firstnameInput.classList.remove('border-danger');
        }

        // Validate last name
        if (!lastnameInput.value.trim()) {
            lastnameInput.classList.add('border-danger'); // Add Bootstrap danger border
            isValid = false;
        } else {
            lastnameInput.classList.remove('border-danger');
        }

        // Validate email
        if (!emailInput.value) {
            emailInput.classList.add('border-danger'); // Add Bootstrap danger border
            isValid = false;
        } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(emailInput.value)) {
            emailInput.classList.add('border-danger'); // Add Bootstrap danger border
            isValid = false;
        } else {
            emailInput.classList.remove('border-danger');
        }

        // Validate contact number
        if (!contactNumberInput.value.trim()) {
            contactNumberInput.classList.add('border-danger'); // Add Bootstrap danger border
            isValid = false;
        } else if (!/^\d{11,}$/.test(contactNumberInput.value.trim())) { // Check if it's numeric and at least 11 characters
            contactNumberInput.classList.add('border-danger'); // Add Bootstrap danger border
            isValid = false;
        } else {
            contactNumberInput.classList.remove('border-danger');
        }


        // Validate ID Picture
        if (!idPictureInput.value.trim()) {
            idPictureInput.classList.add('border-danger'); // Add Bootstrap danger border
            isValid = false;
        } else {
            idPictureInput.classList.remove('border-danger');
        }

        // If all fields are valid
        if (isValid) {
            Swal.fire({
                title: 'Are you sure?',
                text: "You are about to update this user.",
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
                        'The user has been updated.',
                        'success'
                    );
                    // TODO: Add logic to perform edit user here
                }
            });
        }
    });

    // Remove danger border on input change
    [firstnameInput, lastnameInput, emailInput].forEach(input => {
        input.addEventListener('input', () => {
            if (input.value.trim()) {
                input.classList.remove('border-danger');
            }
        });
    });
});



//Contact number allows only numbers
const contactNumberInput = document.getElementById('edit_contact_number');

// Prevent non-numeric input
contactNumberInput.addEventListener('input', function(event) {
    // Replace any non-digit characters with an empty string
    this.value = this.value.replace(/\D/g, '');
});
