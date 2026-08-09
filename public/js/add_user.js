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

$(document).ready(function () {
    $('#add_user_submit').click(function (e) {
        e.preventDefault();

        // Get the CSRF token from the meta tag
        var csrfToken = $('meta[name="csrf-token"]').attr('content');

        // Required fields (excluding image)
        let requiredFields = ['#full_name', '#email', '#contact_number', '#username', '#password', '#color'];
        let isValid = true;

        requiredFields.forEach(function (selector) {
            let element = $(selector);
        
            if (!element.val().trim()) { // Check for empty text fields
                element.addClass('border-danger');
                isValid = false;
            } else {
                element.removeClass('border-danger'); // Only remove if the field is valid
            }
        });

        if (!isValid) {
            Swal.fire({
                title: 'Missing Fields!',
                text: 'Please fill in all required fields.',
                icon: 'warning',
                confirmButtonText: 'OK'
            });
            return;
        }

        // Proceed with backend request if validation passes
        let usertype = $('input[name="radio_buttons_2"]:checked').val();
        let fullname = $('#full_name').val();
        let email = $('#email').val();
        let contact_number = $('#contact_number').val();
        let imageInput = $('input[name="avatar"]')[0];
        let username = $('#username').val();
        let password = $('#password').val();
        let color = $('#color').val();

        let formData = new FormData();
        formData.append('usertype', usertype);
        formData.append('name', fullname);
        formData.append('color', color);
        formData.append('email', email);
        formData.append('contact_number', contact_number);
        formData.append('username', username);
        formData.append('password', password);

        // Append image only if a file is selected
        if (imageInput.files.length > 0) {
            formData.append('image', imageInput.files[0]);
        }

        $.ajax({
            url: 'add_user',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            cache: false,
            headers: {
                'X-CSRF-TOKEN': csrfToken
            },
            success: function (response, textStatus, xhr) {
                if (xhr.status === 201) {
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
            error: handleAjaxError
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