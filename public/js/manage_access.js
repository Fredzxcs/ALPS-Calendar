//Reactivate button
document.querySelectorAll('.reactBtn').forEach(button => {
    button.addEventListener('click', (event) => {
        event.preventDefault(); // Prevent default action (e.g., form submission)

        Swal.fire({
            title: 'Are you sure?',
            text: "You are about to reactivate this user.",
            icon: 'warning',
            buttonsStyling: false,
            showCancelButton: true,
            confirmButtonText: 'Yes, Reactivate',
            cancelButtonText: 'Cancel',
            customClass: {
            confirmButton: "btn btn-success",
            cancelButton: 'btn btn-secondary'
        }
        }).then((result) => {
            if (result.isConfirmed) {
                // Proceed with the reactivation
                Swal.fire(
                    'Reactivated!',
                    'The user has been reactivated.',
                    'success'
                );

                // TODO: Add logic to perform reactivation
            }
        });
    });
});

//Deactivate button
document.querySelectorAll('.deactBtn').forEach(button => {
    button.addEventListener('click', (event) => {
        event.preventDefault(); // Prevent default action (e.g., form submission)

        Swal.fire({
            title: 'Are you sure?',
            text: "You are about to deactivate this user.",
            icon: 'warning',
            buttonsStyling: false,
            showCancelButton: true,
            confirmButtonText: 'Yes, Deactivate',
            cancelButtonText: 'Cancel',
            customClass: {
            confirmButton: "btn btn-danger",
            cancelButton: 'btn btn-secondary'
        }
        }).then((result) => {
            if (result.isConfirmed) {
                // Proceed with the deactivation
                Swal.fire(
                    'Deactivated!',
                    'The user has been deactivated.',
                    'success'
                );

                // TODO: Add logic to perform deactivation
            }
        });
    });
});

// Delete button
document.querySelectorAll('.deleteBtn').forEach(button => {
    button.addEventListener('click', (event) => {
        event.preventDefault(); // Prevent default action (e.g., link navigation)

        const userId = button.getAttribute('data-id'); // Get the user ID from data-id

        Swal.fire({
            title: 'Are you sure?',
            text: "You are about to delete this user.",
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
                // Send AJAX request to delete the user
                fetch(`/access/delete_user/${userId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Content-Type': 'application/json'
                    }
                })
                .then(response => {
                    if (response.ok) {
                        return response.json();
                    } else {
                        throw new Error('Failed to delete user');
                    }
                })
                .then(data => {
                    Swal.fire(
                        'Deleted!',
                        data.message || 'The user has been deleted.',
                        'success'
                    );
                    // Optionally, remove the user's row from the table or update the UI
                    const userRow = document.querySelector(`[row-id="${userId}"]`);
                        if (userRow) userRow.remove();
                })
                .catch(error => {
                    Swal.fire(
                        'Error!',
                        'An error occurred while deleting the user. Please try again.',
                        'error'
                    );
                    console.error(error);
                });
            }
        });
    });
});


//Pagination
document.addEventListener("DOMContentLoaded", function () {
    const rowsPerPage = 5; // Number of rows per page
    const table = document.querySelector("#access_table tbody");
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