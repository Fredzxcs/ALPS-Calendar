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

$(document).ready(function (e){

    $('#add_user_submit').click(function (e) {
        e.preventDefault();

        // Get the CSRF token from the meta tag
        var csrfToken = $('meta[name="csrf-token"]').attr('content');

        let usertype = $('input[name="radio_buttons_2"]:checked').val();
        let name = $('#first_name').val() + ' ' + $('#middle_name').val() + ' ' + $('#last_name').val() + ' ' + $('#suffix').val();
        let email = $('#email').val();
        let contact_number = $('#contact_number').val();
        let image = $('input[name="avatar"]')[0].files[0];
        let username = $('#username').val();
        let password = $('#password').val();
        let color = $('#color').val();

        let formData = new FormData();
        formData.append('usertype', usertype);
        formData.append('name', name);
        formData.append('color', color);
        formData.append('email', email);
        formData.append('contact_number', contact_number);
        formData.append('image', image);
        formData.append('username', username);
        formData.append('password', password);

        $.ajax({
            url: 'add_user',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            cache: false,
            headers: {
                'X-CSRF-TOKEN': csrfToken // Add CSRF token to headers
            },
            success: function(response) {
                if (response.message === '200') {
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
            error: function(xhr, status, error) {
                console.log('AJAX Error Details:');
                console.log('Status:', status);
                console.log('Error:', error);
                console.log('Response Text:', xhr.responseText);
                console.log('ReadyState:', xhr.readyState);
                console.log('Response Status:', xhr.status);
                Swal.fire({
                    title: 'Error!',
                    text: 'There was an error adding the user.',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            }
        });
    });


});
