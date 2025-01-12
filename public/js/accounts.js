//Validation
document.addEventListener('DOMContentLoaded', () => {
    //Validation for Add Account form
    document.getElementById('modal_add_account_form').addEventListener('submit', (event) => {
        event.preventDefault();
        const accountEmail = document.getElementById('add_account_email');
        
        if (!accountEmail.value.trim()) {
            accountEmail.classList.add('is-invalid');
            return;
        } else {
            accountEmail.classList.remove('is-invalid');
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
                Swal.fire(
                    'Added!',
                    'The account has been added.',
                    'success'
                );
                // TODO: Add logic to perform add account
            }
        });
    });

    //Validation for Edit Account form
    document.getElementById('modal_edit_account_form').addEventListener('submit', (event) => {
        event.preventDefault();
        const accountEmail = document.getElementById('edit_account_email');
        
        if (!accountEmail.value.trim()) {
            accountEmail.classList.add('is-invalid');
            return;
        } else {
            accountEmail.classList.remove('is-invalid');
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
                Swal.fire(
                    'Edited!',
                    'The account has been edited.',
                    'success'
                );
                // TODO: Add logic to perform edit account
            }
        });
    });

    //Remove invalid class on input change
    document.querySelectorAll('#add_account_email, #edit_account_email').forEach(input => {
        input.addEventListener('input', () => {
            if (input.value.trim()) {
                input.classList.remove('is-invalid');
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

//Eye Password
// Select all password toggle elements
const togglePasswordButtons = document.querySelectorAll('.togglePassword');

// Add event listener to each toggle button
togglePasswordButtons.forEach(button => {
    button.addEventListener('click', () => {
        const passwordInput = button.previousElementSibling; // Target the password input field
        const eyeSlash = button.querySelector('.fa-eye-slash');
        const eye = button.querySelector('.fa-eye');

        const isPasswordVisible = passwordInput.type === 'text';

        // Toggle input type
        passwordInput.type = isPasswordVisible ? 'password' : 'text';

        // Toggle icon visibility
        eyeSlash.classList.toggle('d-none', !isPasswordVisible);
        eye.classList.toggle('d-none', isPasswordVisible);
    });
});







