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

// Example usage: Fetch user data when any row's button is clicked
$(document).ready(function () {
    // Listen for clicks on the edit button in each row
    $('.edit-user-btn').on('click', function () {
        // Get the userId from the clicked row's data-user-id attribute
        const userId = $(this).closest('tr').data('user-id');
        fetchUserData(userId); // Fetch data for that user
    });
});

$(document).ready(function () {
    if (userId) {
        fetchUserData(userId); // Fetch data for the user
    } else {
        console.error("No user ID provided.");
    }
});

// Populate Existing Entry by ID
function fetchUserData(userId) {
    $.ajax({
        url: `/access/api/get/user/${userId}`,  // Use the correct API endpoint
        method: 'GET',
        success: function (response) {
            if (response.user) {
                let fullName = response.user.name;
                let suffix = '';
                let nameParts = fullName.split(", ");
                let mainName = nameParts[0];
                if (nameParts.length > 1) suffix = nameParts[1];

                nameParts = mainName.split(" ");
                let firstName = nameParts[0] || '';
                let lastName = nameParts[nameParts.length - 1] || '';
                let middleName = nameParts.length > 2 ? nameParts.slice(1, -1).join(" ") : '';

                // Populate form fields
                $('#edit_first_name').val(firstName);
                $('#edit_middle_name').val(middleName);
                $('#edit_last_name').val(lastName);
                $('#edit_suffix').val(suffix);
                $('#edit_email').val(response.user.email);
                $('#edit_contact_number').val(response.user.contact_number);

                // Set radio button for user role
                $(`input[name="radio_buttons_2"][value="${response.user.usertype}"]`).prop('checked', true);

                // Update image preview
                if (response.user.image) {
                    $('.image-input-wrapper').css('background-image', `url(/storage/${response.user.image})`);
                } else {
                    $('.image-input-wrapper').css('background-image', 'none');
                }

                console.log('User data loaded successfully', response);
            }
        },
        error: function (error) {
            console.error('Error fetching user data:', error);
            alert('Failed to fetch user data.');
        }
    });
}
// function fetchUserData(userData) {
//     if (userData) {
//         let fullName = userData.name;
//         let suffix = '';
//         let nameParts = fullName.split(", ");
//         let mainName = nameParts[0];
//         if (nameParts.length > 1) suffix = nameParts[1];

//         nameParts = mainName.split(" ");
//         let firstName = nameParts[0] || '';
//         let lastName = nameParts[nameParts.length - 1] || '';
//         let middleName = nameParts.length > 2 ? nameParts.slice(1, -1).join(" ") : '';

//         // Populate form fields with the decrypted data
//         $('#edit_first_name').val(firstName);
//         $('#edit_middle_name').val(middleName);
//         $('#edit_last_name').val(lastName);
//         $('#edit_suffix').val(suffix);
//         $('#edit_email').val(userData.email);
//         $('#edit_contact_number').val(userData.contact_number);

//         // Set the radio button for user role
//         $(`input[name="radio_buttons_2"][value="${userData.usertype}"]`).prop('checked', true);

//         // Update image preview
//         if (userData.image) {
//             $('.image-input-wrapper').css('background-image', `url(/storage/${userData.image})`);
//         } else {
//             $('.image-input-wrapper').css('background-image', 'none');
//         }

//         console.log('User data loaded successfully', userData);
//     }
// }
// $(document).ready(function () {
//     // Listen for clicks on the edit button in each row
//     $('.edit-btn').on('click', function () {
//         // Get the userId from the clicked row's data-user-id attribute
//         const userId = $(this).closest('tr').data('row-id');
//         fetchUserData(userId); // Fetch data for that user
//     });
// });


// Validation
// document.addEventListener('DOMContentLoaded', () => {
//     const form = document.getElementById('edit_user_form');
//     const firstnameInput = document.getElementById('edit_first_name');
//     const lastnameInput = document.getElementById('edit_last_name');
//     const emailInput = document.getElementById('edit_email');
//     const contactNumberInput = document.getElementById('edit_contact_number');
//     const idPictureInput = document.getElementById('edit_id_picture');

//     // Form submission event
//     form.addEventListener('submit', (event) => {
//         event.preventDefault();

//         let isValid = true;

//         // Validate first name
//         if (!firstnameInput.value.trim()) {
//             firstnameInput.classList.add('border-danger'); // Add Bootstrap danger border
//             isValid = false;
//         } else {
//             firstnameInput.classList.remove('border-danger');
//         }

//         // Validate last name
//         if (!lastnameInput.value.trim()) {
//             lastnameInput.classList.add('border-danger'); // Add Bootstrap danger border
//             isValid = false;
//         } else {
//             lastnameInput.classList.remove('border-danger');
//         }

//         // Validate email
//         if (!emailInput.value) {
//             emailInput.classList.add('border-danger'); // Add Bootstrap danger border
//             isValid = false;
//         } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(emailInput.value)) {
//             emailInput.classList.add('border-danger'); // Add Bootstrap danger border
//             isValid = false;
//         } else {
//             emailInput.classList.remove('border-danger');
//         }

//         // Validate contact number
//         if (!contactNumberInput.value.trim()) {
//             contactNumberInput.classList.add('border-danger'); // Add Bootstrap danger border
//             isValid = false;
//         } else if (!/^\d{11,}$/.test(contactNumberInput.value.trim())) { // Check if it's numeric and at least 11 characters
//             contactNumberInput.classList.add('border-danger'); // Add Bootstrap danger border
//             isValid = false;
//         } else {
//             contactNumberInput.classList.remove('border-danger');
//         }

//         // Validate ID Picture
//         if (!idPictureInput.value.trim()) {
//             idPictureInput.classList.add('border-danger'); // Add Bootstrap danger border
//             isValid = false;
//         } else {
//             idPictureInput.classList.remove('border-danger');
//         }

//         // If all fields are valid
//         if (isValid) {
//             Swal.fire({
//                 title: 'Are you sure?',
//                 text: "You are about to update this user.",
//                 icon: 'warning',
//                 buttonsStyling: false,
//                 showCancelButton: true,
//                 confirmButtonText: 'Yes, Update it',
//                 cancelButtonText: 'Cancel',
//                 customClass: {
//                     confirmButton: "btn btn-success",
//                     cancelButton: 'btn btn-secondary'
//                 }
//             }).then((result) => {
//                 if (result.isConfirmed) {
//                     Swal.fire(
//                         'Updated!',
//                         'The user has been updated.',
//                         'success'
//                     );
//                     // TODO: Add logic to perform edit user here
//                 }
//             });
//         }
//     });

//     // Remove danger border on input change
//     [firstnameInput, lastnameInput, emailInput].forEach(input => {
//         input.addEventListener('input', () => {
//             if (input.value.trim()) {
//                 input.classList.remove('border-danger');
//             }
//         });
//     });
// });

//Contact number allows only numbers

    document.addEventListener('DOMContentLoaded', () => {
        const form = document.getElementById('edit_user_form');

        if (!form || userId === 'null') return; // Ensure form exists and userId is valid

        const firstnameInput = document.getElementById('edit_first_name');
        const middlenameInput = document.getElementById('edit_middle_name');
        const lastnameInput = document.getElementById('edit_last_name');
        const suffixInput = document.getElementById('edit_suffix');
        const emailInput = document.getElementById('edit_email');
        const contactNumberInput = document.getElementById('edit_contact_number');
        const idPictureInput = document.getElementById('edit_id_picture');

        // Remove error border when user starts typing
        [firstnameInput, lastnameInput, emailInput, contactNumberInput].forEach(input => {
            input.addEventListener('input', () => {
                if (input.value.trim()) {
                    input.classList.remove('border-danger');
                }
            });
        });

        // Form Submission Event
        form.addEventListener('submit', (event) => {
            event.preventDefault();

            let isValid = true;

            // Validate First Name
            if (!firstnameInput.value.trim()) {
                firstnameInput.classList.add('border-danger');
                isValid = false;
            } else {
                firstnameInput.classList.remove('border-danger');
            }

            // Validate Last Name
            if (!lastnameInput.value.trim()) {
                lastnameInput.classList.add('border-danger');
                isValid = false;
            } else {
                lastnameInput.classList.remove('border-danger');
            }

            // Validate Email
            if (!emailInput.value.trim()) {
                emailInput.classList.add('border-danger');
                isValid = false;
            } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(emailInput.value)) {
                emailInput.classList.add('border-danger');
                isValid = false;
            } else {
                emailInput.classList.remove('border-danger');
            }

            // Validate Contact Number
            if (!contactNumberInput.value.trim()) {
                contactNumberInput.classList.add('border-danger');
                isValid = false;
            } else if (!/^\d{11,15}$/.test(contactNumberInput.value.trim())) { // Ensure it's numeric and 11-15 digits
                contactNumberInput.classList.add('border-danger');
                isValid = false;
            } else {
                contactNumberInput.classList.remove('border-danger');
            }

            // Validate ID Picture (Optional)
            if (idPictureInput.files.length > 0) {
                let file = idPictureInput.files[0];
                let allowedExtensions = ["image/jpeg", "image/png", "image/jpg", "image/gif"];
                if (!allowedExtensions.includes(file.type)) {
                    alert("Invalid file type. Only JPG, JPEG, PNG, and GIF are allowed.");
                    isValid = false;
                }
            }

            if (!isValid) {
                Swal.fire({
                    title: 'Missing Fields!',
                    text: 'Please fill in all required fields.',
                    icon: 'warning',
                    confirmButtonText: 'OK'
                });
                return;
            } // Stop submission if validation fails

            // Confirmation Dialog
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
                    updateUser();
                }
            });
        });

        function updateUser() {

            let usertype = $('input[name="radio_buttons_2"]:checked').val();

            let formData = new FormData();
            formData.append('usertype', usertype);
            formData.append('first_name', firstnameInput.value.trim());
            formData.append('middle_name', middlenameInput.value.trim());
            formData.append('last_name', lastnameInput.value.trim());
            formData.append('suffix', suffixInput.value.trim());
            formData.append('email', emailInput.value.trim());
            formData.append('contact_number', contactNumberInput.value.trim());


            if (idPictureInput.files.length > 0) {
                formData.append('id_picture', idPictureInput.files[0]);
            }

            formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));

            $.ajax({
                url: `/access/update_user/${userId}`,  // Use the userId from the script tag
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function (response) {
                    Swal.fire({
                        title: 'Updated!',
                        text: 'User updated successfully.',
                        icon: 'success'
                    }).then(() => {
                        window.location.reload(); // Reload the page after update
                    });
                },
                error: function (xhr) {
                    Swal.fire({
                        title: 'Error!',
                        text: xhr.responseJSON?.error || 'There was an issue updating the user.',
                        icon: 'error'
                    });
                }
            });
        }
    });




const contactNumberInput = document.getElementById('edit_contact_number');

// Prevent non-numeric input
contactNumberInput.addEventListener('input', function(event) {
    // Replace any non-digit characters with an empty string
    this.value = this.value.replace(/\D/g, '');
});
