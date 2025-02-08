$(document).ready(function () {
    // Get the user ID from the URL path
    var pathArray = window.location.pathname.split('/'); // Split path by '/'
    var userId = pathArray[pathArray.length - 1]; // Get the last part (which should be the ID)

    if (userId) {
        fetchUserData(userId); // Fetch data for the user if ID is found
    } else {
        console.error("No user ID provided.");
    }
});

// Function to fetch user data and populate the form
function fetchUserData(userId) {
    $.ajax({
        url: `/access/api/get/user/${userId}`,  // API endpoint to get user data
        method: 'GET',
        success: function (response) {
            console.log("Fetched User Data:", response);
            if (response.user) {
                // Populate form fields with user data
                $('#username').val(response.user.username);
                $('#password').val(response.user.password);
                $('#color').val(response.user.color);
            }
        },
        error: function (error) {
            console.error("Error fetching user data:", error);
            alert("Failed to fetch user data.");
        }
    });
}

// Validation
// document.addEventListener('DOMContentLoaded', () => {
//     const form = document.getElementById('change_credentials_form');
//     const usernameInput = document.getElementById('username');
//     const passwordInput = document.getElementById('password');
//     const colorInput = document.getElementById('color');

//     // Form submission event
//     form.addEventListener('submit', (event) => {
//         event.preventDefault();

//         let isValid = true;

//         // Validate username
//         if (!usernameInput.value.trim()) {
//             usernameInput.classList.add('border-danger'); // Add Bootstrap danger border
//             isValid = false;
//         } else {
//             usernameInput.classList.remove('border-danger');
//         }

//         // Validate password
//         if (!passwordInput.value.trim()) {
//             passwordInput.classList.add('border-danger'); // Add Bootstrap danger border
//             isValid = false;
//         } else {
//             passwordInput.classList.remove('border-danger');
//         }
//         // Validate color
//         if (!colorInput.value) {
//             colorInput.classList.add('border-danger'); // Add Bootstrap danger border
//             isValid = false;
//         } else {
//             colorInput.classList.remove('border-danger');
//         }

//         if (!isValid) {
//             Swal.fire({
//                 title: 'Missing Fields!',
//                 text: 'Please fill in all required fields.',
//                 icon: 'warning',
//                 confirmButtonText: 'OK'
//             });
//             return;
//         } // Stop submission if validation fails

//         // If all fields are valid
//         if (isValid) {
//             Swal.fire({
//                 title: 'Are you sure?',
//                 text: "You are about to update these credentials.",
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
//                         'The credentials have been updated.',
//                         'success'
//                     );
//                     // TODO: Add logic to perform credential changes here
//                 }
//             });
//         }
//     });

//     // Remove danger border on input change
//     [usernameInput, passwordInput, colorInput].forEach(input => {
//         input.addEventListener('input', () => {
//             if (input.value.trim()) {
//                 input.classList.remove('border-danger');
//             }
//         });
//     });
// });

document.addEventListener('DOMContentLoaded', () => {

    // Get User ID from URL
    var pathArray = window.location.pathname.replace(/\/$/, "").split('/');
    var userId = pathArray[pathArray.length - 1];
    console.log('Id detected',userId);

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
            usernameInput.classList.add('border-danger');
            isValid = false;
        } else {
            usernameInput.classList.remove('border-danger');
        }

        // Validate password 
        if (passwordInput.value.trim().length > 0 && passwordInput.value.trim().length < 8) {
            passwordInput.classList.add('border-danger');
            Swal.fire({
                title: 'Warning!', 
                text: 'Password must be at least 8 characters.', 
                icon: 'warning',
            });
            return;
        } else {
            passwordInput.classList.remove('border-danger');
        }

        //Validate color
        if (!colorInput.value) {
            colorInput.classList.add('border-danger');
            isValid = false;
        } 

        if (!isValid) {
            Swal.fire({
                title: 'Missing Fields!',
                text: 'Please fill in all required fields.',
                icon: 'warning',
            });
            return;
        }

        // Confirmation before submitting
        Swal.fire({
            title: 'Are you sure?',
            text: "You are about to update these credentials.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, Update it',
            cancelButtonText: 'Cancel',
            customClass: {
                confirmButton: "btn btn-success",
                cancelButton: 'btn btn-secondary'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                // Send AJAX Request
                let formData = new FormData();
                formData.append('username', usernameInput.value.trim());
                if (passwordInput.value.trim()) { // Only send password if it's not empty
                    formData.append('password', passwordInput.value.trim());
                }
                formData.append('color', colorInput.value.trim());
                formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));

                $.ajax({
                    url: `/access/update_credentials/${userId}`,
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function (response, textStatus, xhr) {
                        if (xhr.status === 200) {
                            Swal.fire({
                                title: 'Success!',
                                text: 'User has been added.',
                                icon: 'success',
                                confirmButtonText: 'OK'
                            }).then(() => {
                                window.location.href = '/access';
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
                    error: function (xhr, status, error) {
                        if (xhr.status === 422) {
                            let errors = xhr.responseJSON.errors;
                            let errorMessages = Object.values(errors).flat().join("\n");
        
                            Swal.fire({
                                title: 'Validation Error!',
                                text: errorMessages,
                                icon: 'warning',
                                confirmButtonText: 'OK'
                            });
                        } else {
                            Swal.fire({
                                title: 'Error!',
                                text: 'There was an error adding the user.',
                                icon: 'error',
                                confirmButtonText: 'OK'
                            });
                        }
                    }
                });
                
            }
        });
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



