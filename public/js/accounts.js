
// Search Filter
document.addEventListener('DOMContentLoaded', () => {
    const searchInput = document.getElementById('searchInput');
    const tableRows = document.querySelectorAll('#accounts_table tbody tr');

    searchInput.addEventListener('keyup', () => {
        const searchValue = searchInput.value.toLowerCase();

        tableRows.forEach(row => {
            const cells = row.querySelectorAll('td');
            const rowText = Array.from(cells).map(cell => cell.textContent.toLowerCase()).join(' ');

            if (rowText.includes(searchValue)) {
                row.style.display = ''; // Show row if it matches search
            } else {
                row.style.display = 'none'; // Hide row if it doesn't match search
            }
        });
    });
});

//No matching Searches - in progress
document.addEventListener('DOMContentLoaded', () => {
    const searchInput = document.getElementById('searchInput');
    const tableBody = document.querySelector('#accounts_table tbody');
    const tableRows = Array.from(tableBody.querySelectorAll('tr'));
    const noResultsRow = document.getElementById('noResultsRow');

    // Function to check and update visibility of rows
    const updateNoResultsRow = () => {
        let visibleRowCount = 0;

        tableRows.forEach(row => {
            if (row.id !== 'noResultsRow' && row.style.display !== 'none') {
                visibleRowCount++;
            }
        });

        noResultsRow.style.display = visibleRowCount === 0 ? '' : 'none';
    };

    // Initial check: Hide server-rendered "noResultsRow" if accounts exist
    if (tableRows.length > 1 || (tableRows.length === 1 && tableRows[0].id !== 'noResultsRow')) {
        noResultsRow.style.display = 'none';
    }

    // Add search functionality
    searchInput.addEventListener('keyup', () => {
        const searchValue = searchInput.value.toLowerCase();
        let visibleRowCount = 0;

        tableRows.forEach(row => {
            if (row.id !== 'noResultsRow') {
                const cells = row.querySelectorAll('td');
                const rowText = Array.from(cells).map(cell => cell.textContent.toLowerCase()).join(' ');

                if (rowText.includes(searchValue)) {
                    row.style.display = ''; // Show matching rows
                    visibleRowCount++;
                } else {
                    row.style.display = 'none'; // Hide non-matching rows
                }
            }
        });

        // Update visibility of the "No Results" row
        noResultsRow.style.display = visibleRowCount === 0 ? '' : 'none';
    });
});


//Validation (Initial)
// document.addEventListener('DOMContentLoaded', () => {
//     //Validation for Add Account form
//     document.getElementById('modal_add_account_form').addEventListener('submit', (event) => {
//         event.preventDefault();

//         const accountEmail = document.getElementById('add_account_email');
//         const accountPassword = document.getElementById('add_account_password');
//         const submitButton = document.getElementById('add_account_submit');

//         if (!accountEmail.value.trim()) {
//             accountEmail.classList.add('is-invalid');
//             return;
//         } else {
//             accountEmail.classList.remove('is-invalid');
//         }

//         Swal.fire({
//             title: 'Are you sure?',
//             text: "You are about to add this account.",
//             icon: 'warning',
//             buttonsStyling: false,
//             showCancelButton: true,
//             confirmButtonText: 'Yes, Add Account',
//             cancelButtonText: 'Cancel',
//             customClass: {
//                 confirmButton: "btn btn-success",
//                 cancelButton: 'btn btn-secondary'
//             }
//         }).then((result) => {
//             if (result.isConfirmed) {
//                 submitButton.disabled = true;
    
//                 const formData = {
//                     account_email: accountEmail.value.trim(),
//                     account_password: accountPassword.value.trim()
//                 };
    
//                 // FETCH ROUTE URL FROM BLADE DATA ATTRIBUTE
//                 const routeUrl = document.getElementById('route-config').dataset.url;
//                 console.log("Route URL:", routeUrl);
                
//                 $.ajax({
//                     url: routeUrl,
//                     method: 'POST',
//                     data: formData,
//                     headers: {
//                         'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
//                     },
//                     success: function (response) {
//                         if (response.success) {
//                             Swal.fire('Added!', 
//                                 'The account has been added successfully.', 
//                                 'success')
//                                 .then(() => location.reload());
//                         } else {
//                             Swal.fire('Error!', 
//                                 'There was an issue adding the account.', 
//                                 'error');
//                         }
//                     },
//                     error: function (xhr, status, error) {
//                         console.error('AJAX Error:', xhr.responseText);
//                         Swal.fire('Error!', 
//                             'An unexpected error occurred.', 
//                             'error');
//                     },
//                     complete: function () {
//                         submitButton.disabled = false;
//                     }
//                 });
//             }
//         });
//     });

//     //Populate Existing Entry by id
//     // Event listener to open the modal and populate it with data
//     $(document).on('click', '.editAccountBtn', function () {
//         // Get the account ID from the button's data-id attribute
//         const accountId = $(this).data('id');
//         console.log(accountId);

//         // Ensure accountId is not undefined
//         if (accountId === undefined) {
//             console.error('Account ID is undefined');
//             return; // Stop the AJAX request if ID is undefined
//         }
    
//         // Fetch account details using AJAX
//         $.ajax({
//             url: `/config/accounts/${accountId}`, // Make sure this is the correct endpoint
//             method: 'GET',
//             success: function (response) {
//                 // Populate the modal with account data
//                 $('#edit_account_email').val(response.account_email);
//                 $('#edit_account_password').val(response.account_password);
    
//                 // Optionally, store the account ID in the modal form for later use (for saving)
//                 $('#modal_edit_account_form').data('id', accountId);
    
//                 // Open the modal
//                 $('#modal_edit_account').modal('show');
//             },
//             error: function (error) {
//                 console.error('Failed to fetch account details:', error);
//                 alert('An error occurred while fetching the account details.');
//             }
//         });
//     });


//     //Validation for Edit Account form
//     document.getElementById('modal_edit_account_form').addEventListener('submit', (event) => {
//         event.preventDefault();

//         const accountId = $('#modal_edit_account_form').data('id');
//         const accountEmail = document.getElementById('edit_account_email');
//         const accountPassword = document.getElementById('edit_account_password');
//         const submitButton = document.getElementById('edit_account_submit');
        
//         if (!accountEmail.value.trim()) {
//             accountEmail.classList.add('is-invalid');
//             return;
//         } else {
//             accountEmail.classList.remove('is-invalid');
//         }

//         Swal.fire({
//             title: 'Are you sure?',
//             text: "You are about to edit this account.",
//             icon: 'warning',
//             buttonsStyling: false,
//             showCancelButton: true,
//             confirmButtonText: 'Yes, Edit Account',
//             cancelButtonText: 'Cancel',
//             customClass: {
//                 confirmButton: "btn btn-success",
//                 cancelButton: 'btn btn-secondary'
//             }
//         }).then((result) => {
//             if (result.isConfirmed) {
//                 submitButton.disabled = true;

//                 const formData = {
//                     account_email: accountEmail.value.trim(),
//                     account_password: accountPassword.value.trim()  
//                 };

//                 const routeUrl = `/config/accounts/update/${accountId}`;
                
//                 console.log("Route URL:", routeUrl);

//                 $.ajax({
//                     url: routeUrl,
//                     method: 'PATCH',
//                     data: formData,
//                     headers: {
//                         'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
//                     },
//                     success: function(response){
//                         if (response.success) {
//                             Swal.fire('Updated!',
//                                 'The account has been updated successfully.',
//                                 'success')
//                                 .then(() => location.reload());
//                         } else {
//                             Swal.fire('Error!',
//                                 'There was an issue updating the account.',
//                                 'error');
//                         }
//                     },
//                     error: function (xhr, status, error) {
//                         console.error('AJAX Error:', xhr.responseText);
                    
//                         // Check if there are validation errors in the response
//                         if (xhr.responseJSON && xhr.responseJSON.errors) {
//                             // If the account_email error exists, display it in the Swal alert
//                             if (xhr.responseJSON.errors.account_email) {
//                                 Swal.fire(
//                                     'Error!',
//                                     xhr.responseJSON.errors.account_email[0],  // Display the error message for account_email
//                                     'error'
//                                 );
//                             } else {
//                                 // Handle other validation or general errors
//                                 Swal.fire(
//                                     'Error!',
//                                     'Pleas try again.',
//                                     'error'
//                                 );
//                             }
//                         } else {
//                             // For any other errors that aren't related to validation
//                             Swal.fire(
//                                 'Error!',
//                                 'An unexpected error occurred.',
//                                 'error'
//                             );
//                         }
//                     },                 
//                     complete: function () {
//                         submitButton.disabled = false;
//                     }
//                 });

//             }
//         });
//     });

//     //Remove invalid class on input change
//     document.querySelectorAll('#add_account_email, #edit_account_email').forEach(input => {
//         input.addEventListener('input', () => {
//             if (input.value.trim()) {
//                 input.classList.remove('is-invalid');
//             }
//         });
//     });
// });


//Validation (Updated)
document.addEventListener('DOMContentLoaded', () => {
    // Validation for Add Account form
    document.getElementById('modal_add_account_form').addEventListener('submit', (event) => {
        event.preventDefault();

        const accountEmail = document.getElementById('add_account_email');
        const accountPassword = document.getElementById('add_account_password');
        const submitButton = document.getElementById('add_account_submit');

        let isValid = true;

        // Validate email
        if (!accountEmail.value.trim()) {
            accountEmail.classList.add('border-danger');
            isValid = false;
        } else {
            accountEmail.classList.remove('border-danger');
        }

        // Validate password
        if (!accountPassword.value.trim()) {
            accountPassword.classList.add('border-danger');
            isValid = false;
        } else {
            accountPassword.classList.remove('border-danger');
        }

        if (!isValid) {
            return; // Stop form submission if validation fails
        }

        Swal.fire({
            title: 'Are you sure?',
            text: "You are about to add this account.",
            icon: 'warning',
            buttonsStyling: false,
            showCancelButton: true,
            confirmButtonText: 'Yes, Add Account',
            cancelButtonText: 'Cancel',
            customClass: {
                confirmButton: "btn btn-success",
                cancelButton: 'btn btn-secondary'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                submitButton.disabled = true;

                const formData = {
                    account_email: accountEmail.value.trim(),
                    account_password: accountPassword.value.trim()
                };

                const routeUrl = document.getElementById('route-config').dataset.url;

                $.ajax({
                    url: routeUrl,
                    method: 'POST',
                    data: formData,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (response) {
                        if (response.success) {
                            Swal.fire('Added!',
                                'The account has been added successfully.',
                                'success')
                                .then(() => location.reload());
                        } else {
                            Swal.fire('Error!',
                                'There was an issue adding the account.',
                                'error');
                        }
                    },
                    error: function (xhr, status, error) {
                        console.error('AJAX Error:', xhr.responseText);
                        Swal.fire('Error!',
                            'An unexpected error occurred.',
                            'error');
                    },
                    complete: function () {
                        submitButton.disabled = false;
                    }
                });
//Validation for add
document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('modal_add_account_form');
    const emailInput = document.getElementById('add_account_email');
    const passwordInput = document.getElementById('add_account_password');

    // Function to add the Bootstrap 'border-danger' class
    function setInvalid(input) {
        input.classList.add('border-danger');
    }

    // Function to remove the Bootstrap 'border-danger' class
    function setValid(input) {
        input.classList.remove('border-danger');
    }

    // Form submission event
    form.addEventListener('submit', (event) => {
        event.preventDefault();

        let isValid = true;

        // Validate email
        if (!emailInput.value.trim()) {
            setInvalid(emailInput);
            isValid = false;
        } else {
            setValid(emailInput);
        }

        // Validate password
        if (!passwordInput.value.trim()) {
            setInvalid(passwordInput);
            isValid = false;
        } else {
            setValid(passwordInput);
        }

        // If all fields are valid
        if (isValid) {
            Swal.fire({
                title: 'Are you sure?',
                text: "You are about to add this account.",
                icon: 'warning',
                buttonsStyling: false,
                showCancelButton: true,
                confirmButtonText: 'Yes, Add Account',
                cancelButtonText: 'Cancel',
                customClass: {
                    confirmButton: "btn btn-success",
                    cancelButton: 'btn btn-secondary'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire(
                        'Added!',
                        'The account has been added.',
                        'success'
                    );
                    // TODO: Add logic to perform add acc here
                }
            });
        }
    });

    // Remove invalid border on input change
    [emailInput, passwordInput].forEach(input => {
        input.addEventListener('input', () => {
            if (input.value.trim()) {
                setValid(input);
            }
        });
    });
});

    // Validation for Edit Account form
    document.getElementById('modal_edit_account_form').addEventListener('submit', (event) => {
        event.preventDefault();

        const accountId = $('#modal_edit_account_form').data('id');
        const accountEmail = document.getElementById('edit_account_email');
        const accountPassword = document.getElementById('edit_account_password');
        const submitButton = document.getElementById('edit_account_submit');

        let isValid = true;

        // Validate email
        if (!accountEmail.value.trim()) {
            accountEmail.classList.add('border-danger');
            isValid = false;
        } else {
            accountEmail.classList.remove('border-danger');
        }

        // Validate password
        if (!accountPassword.value.trim()) {
            accountPassword.classList.add('border-danger');
            isValid = false;
        } else {
            accountPassword.classList.remove('border-danger');
        }

        if (!isValid) {
            return; // Stop form submission if validation fails
        }

        Swal.fire({
            title: 'Are you sure?',
            text: "You are about to edit this account.",
            icon: 'warning',
            buttonsStyling: false,
            showCancelButton: true,
            confirmButtonText: 'Yes, Edit Account',
            cancelButtonText: 'Cancel',
            customClass: {
                confirmButton: "btn btn-success",
                cancelButton: 'btn btn-secondary'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                submitButton.disabled = true;

                const formData = {
                    account_email: accountEmail.value.trim(),
                    account_password: accountPassword.value.trim()
                };

                const routeUrl = `/config/accounts/update/${accountId}`;

                $.ajax({
                    url: routeUrl,
                    method: 'PATCH',
                    data: formData,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (response) {
                        if (response.success) {
                            Swal.fire('Updated!',
                                'The account has been updated successfully.',
                                'success')
                                .then(() => location.reload());
                        } else {
                            Swal.fire('Error!',
                                'There was an issue updating the account.',
                                'error');
                        }
                    },
                    error: function (xhr, status, error) {
                        console.error('AJAX Error:', xhr.responseText);
                        Swal.fire('Error!',
                            'An unexpected error occurred.',
                            'error');
                    },
                    complete: function () {
                        submitButton.disabled = false;
                    }
                });
            }
        });
    });

    // Remove danger border class on input change
    document.querySelectorAll('#add_account_email, #add_account_password, #edit_account_email, #edit_account_password').forEach(input => {
        input.addEventListener('input', () => {
            if (input.value.trim()) {
                input.classList.remove('border-danger');
            }
        });
    });

    // Populate Existing Entry by ID
    $(document).on('click', '.editAccountBtn', function () {
        const accountId = $(this).data('id');

        if (accountId === undefined) {
            console.error('Account ID is undefined');
            return;
        }

        $.ajax({
            url: `/config/accounts/${accountId}`,
            method: 'GET',
            success: function (response) {
                $('#edit_account_email').val(response.account_email);
                $('#edit_account_password').val(response.account_password);
                $('#modal_edit_account_form').data('id', accountId);
                $('#modal_edit_account').modal('show');
            },
            error: function (error) {
                console.error('Failed to fetch account details:', error);
                alert('An error occurred while fetching the account details.');
            }
        });
    });
});


//Delete account button
document.querySelectorAll('.deleteBtn').forEach(button => {
    button.addEventListener('click', (event) => {
        event.preventDefault(); // Prevent default action (e.g., form submission)

        // Get the account ID from a data attribute (e.g., data-id)
        const accountId = button.getAttribute('data-id');

        if (!accountId) {
            console.error("account ID not provided.");
            return;
        }

        Swal.fire({
            title: 'Are you sure?',
            text: "You are about to delete this account.",
            icon: 'warning',
            buttonsStyling: false,
            showCancelButton: true,
            confirmButtonText: 'Yes, Delete',
            cancelButtonText: 'Cancel',
            customClass: {
                confirmButton: "btn btn-danger",
                cancelButton: 'btn btn-secondary'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                // Perform the delete action
                fetch(`/config/accounts/delete/${accountId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire(
                            'Deleted!',
                            data.message || 'The account has been deleted.',
                            'success'
                        );

                        // Optionally remove the account row from the table or reload the page
                        document.querySelector(`#account-row-${accountId}`).remove();
                    } else {
                        Swal.fire(
                            'Error!',
                            data.message || 'There was an error deleting the account.',
                            'error'
                        );
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire(
                        'Error!',
                        'There was an error deleting the account.',
                        'error'
                    );
                });
            }
        });
    });
});

//Pagination
document.addEventListener("DOMContentLoaded", function () {
    const rowsPerPage = 5; // Number of rows per page
    const table = document.querySelector("#accounts_table tbody");
    const rows = Array.from(table.rows);
    const pagination = document.querySelector(".pagination");
    const totalPages = Math.ceil(rows.length / rowsPerPage);

    let currentPage = 1; // Track the current page

    function displayPage(page) {
        const start = (page - 1) * rowsPerPage;
        const end = start + rowsPerPage;

        // Show rows for the current page
        rows.forEach((row, index) => {
            row.style.display = index >= start && index < end ? "" : "none";
        });

        // Update active state for pagination buttons
        Array.from(pagination.querySelectorAll(".page-item")).forEach((item, idx) => {
            item.classList.toggle("active", idx === page);
        });

        // Enable/disable "Previous" and "Next" buttons
        pagination.querySelector(".prev").classList.toggle("disabled", page === 1);
        pagination.querySelector(".next").classList.toggle("disabled", page === totalPages);

        currentPage = page; // Update the current page
    }

    // Create pagination buttons
    function createPaginationButtons() {
        // Add "Previous" button
        const prevButton = document.createElement("li");
        prevButton.className = "page-item prev disabled";
        prevButton.innerHTML = `<a class="page-link" href="#">Previous</a>`;
        prevButton.addEventListener("click", (e) => {
            e.preventDefault();
            if (currentPage > 1) displayPage(currentPage - 1);
        });
        pagination.appendChild(prevButton);

        // Add page number buttons
        for (let i = 1; i <= totalPages; i++) {
            const li = document.createElement("li");
            li.className = "page-item" + (i === 1 ? " active" : "");
            li.innerHTML = `<a class="page-link" href="#">${i}</a>`;
            li.addEventListener("click", (e) => {
                e.preventDefault();
                displayPage(i);
            });
            pagination.appendChild(li);
        }

        // Add "Next" button
        const nextButton = document.createElement("li");
        nextButton.className = "page-item next" + (totalPages === 1 ? " disabled" : "");
        nextButton.innerHTML = `<a class="page-link" href="#">Next</a>`;
        nextButton.addEventListener("click", (e) => {
            e.preventDefault();
            if (currentPage < totalPages) displayPage(currentPage + 1);
        });
        pagination.appendChild(nextButton);
    }

    createPaginationButtons();
    displayPage(1); // Show the first page initially
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

//Password reveal in table
$(document).ready(function() {
    $(".password-display").click(function() {
        var actualPassword = $(this).next(".password-actual");
        $(this).addClass("d-none");
        actualPassword.removeClass("d-none");
    });

    $(".password-actual").click(function() {
        var passwordDisplay = $(this).prev(".password-display");
        $(this).addClass("d-none");
        passwordDisplay.removeClass("d-none");
    });
});








