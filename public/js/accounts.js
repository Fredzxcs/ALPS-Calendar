//Validation
document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('modal_add_account_form');
    const emailInput = document.getElementById('add_account_email');
    const passwordInput = document.getElementById('add_account_password');
    const passwordFeedback = passwordInput.nextElementSibling; // Locate the "Required field" div

    // Form submission event
    form.addEventListener('submit', (event) => {
        event.preventDefault();

        let isValid = true;

        // Validate email
        if (!emailInput.value.trim()) {
            emailInput.classList.add('is-invalid');
            isValid = false;
        } else {
            emailInput.classList.remove('is-invalid');
        }

        // Validate password (do not add is-invalid class)
        if (!passwordInput.value.trim()) {
            passwordFeedback.style.display = 'block'; // Show the "Required field" message
            isValid = false;
        } else {
            passwordFeedback.style.display = 'none'; // Hide the message if valid
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

    // Remove invalid class on input change and manage password message visibility
    [emailInput, passwordInput].forEach(input => {
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

document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('modal_edit_account_form');
    const emailInput = document.getElementById('edit_account_email');
    const passwordInput = document.getElementById('edit_account_password');
    const passwordFeedback = passwordInput.nextElementSibling; // Locate the "Required field" div

    // Form submission event
    form.addEventListener('submit', (event) => {
        event.preventDefault();

        let isValid = true;

        // Validate email
        if (!emailInput.value.trim()) {
            emailInput.classList.add('is-invalid');
            isValid = false;
        } else {
            emailInput.classList.remove('is-invalid');
        }

        // Validate password (do not add is-invalid class)
        if (!passwordInput.value.trim()) {
            passwordFeedback.style.display = 'block'; // Show the "Required field" message
            isValid = false;
        } else {
            passwordFeedback.style.display = 'none'; // Hide the message if valid
        }

        // If all fields are valid
        if (isValid) {
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
                    Swal.fire(
                        'Edited!',
                        'The account has been edited.',
                        'success'
                    );
                    // TODO: Add logic to perform edit acc here
                }
            });
        }
    });

    // Remove invalid class on input change and manage password message visibility
    [emailInput, passwordInput].forEach(input => {
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

//Delete account button
document.querySelectorAll('.deleteBtn').forEach(button => {
    button.addEventListener('click', (event) => {
        event.preventDefault(); // Prevent default action (e.g., form submission)

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
                // Proceed with the delete
                Swal.fire(
                    'Deleted!',
                    'The account has been deleted.',
                    'success'
                );

                // TODO: Add logic to perform delete
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








