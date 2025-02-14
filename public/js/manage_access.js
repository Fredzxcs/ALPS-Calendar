document.addEventListener('DOMContentLoaded', () => {
    const searchInput = document.getElementById('searchInput');
    const accessFilter = document.getElementById('accessFilterSelect');
    const tableRows = document.querySelectorAll('#access_table tbody tr');
    const resetButton = document.getElementById('accessFilterReset');

    // Ensure the elements exist before adding event listeners
    if (searchInput) {
        searchInput.addEventListener('keyup', filterTable);
    }

    if (accessFilter) {
        accessFilter.addEventListener('change', filterTable);
    }

    if (resetButton) {
        resetButton.addEventListener('click', () => {
            searchInput.value = "";
            accessFilter.value = "Show All";
            filterTable();
        });
    }

    function filterTable() {
        const searchValue = searchInput ? searchInput.value.toLowerCase() : "";
        const selectedRole = accessFilter ? accessFilter.value.toLowerCase() : ""; // Ensure accessFilter exists

        //console.log('Selected Role:', selectedRole); // Debugging log

        tableRows.forEach(row => {
            const rowText = row.textContent.toLowerCase();
            const roleValue = row.getAttribute('data-role') ? row.getAttribute('data-role').toLowerCase() : "";

            //console.log('Row Role Value:', roleValue); // Debugging log

            // Match both search & role filter
            const matchesSearch = rowText.includes(searchValue);
            const matchesRole = selectedRole === "show all" || roleValue.includes(selectedRole);

            row.style.display = matchesSearch && matchesRole ? '' : 'none';
        });
    }
});

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
// document.addEventListener("DOMContentLoaded", function () {
//     const rowsPerPage = 5; // Number of rows per page
//     const pagesPerBatch = 5; // Number of pages per batch
//     const table = document.querySelector("#access_table tbody");
//     const rows = Array.from(table.rows);
//     const pagination = document.querySelector(".pagination");
//     const totalPages = Math.ceil(rows.length / rowsPerPage);

//     let currentPage = 1; // Track the current page
//     let currentBatch = 1; // Track the current batch

//     function displayPage(page) {
//         const start = (page - 1) * rowsPerPage;
//         const end = start + rowsPerPage;

//         // Show rows for the current page
//         rows.forEach((row, index) => {
//             row.style.display = index >= start && index < end ? "" : "none";
//         });

//         currentPage = page; // Update the current page
//         updatePagination(); // Update pagination UI
//     }

//     function updatePagination() {
//         pagination.innerHTML = ""; // Clear existing pagination

//         const totalBatches = Math.ceil(totalPages / pagesPerBatch);
//         const startPage = (currentBatch - 1) * pagesPerBatch + 1;
//         const endPage = Math.min(startPage + pagesPerBatch - 1, totalPages);

//         // Previous Batch Button
//         const prevBatchButton = document.createElement("li");
//         prevBatchButton.className = `page-item prev-batch ${currentBatch === 1 ? "disabled" : ""}`;
//         prevBatchButton.innerHTML = `<a class="page-link" href="#">«</a>`;
//         prevBatchButton.addEventListener("click", (e) => {
//             e.preventDefault();
//             if (currentBatch > 1) {
//                 currentBatch--;
//                 updatePagination();
//                 displayPage((currentBatch - 1) * pagesPerBatch + 1);
//             }
//         });
//         pagination.appendChild(prevBatchButton);

//         // Page Number Buttons
//         for (let i = startPage; i <= endPage; i++) {
//             const li = document.createElement("li");
//             li.className = `page-item ${i === currentPage ? "active" : ""}`;
//             li.innerHTML = `<a class="page-link" href="#">${i}</a>`;
//             li.addEventListener("click", (e) => {
//                 e.preventDefault();
//                 displayPage(i); // Navigate to selected page
//             });
//             pagination.appendChild(li);
//         }

//         // Next Batch Button
//         const nextBatchButton = document.createElement("li");
//         nextBatchButton.className = `page-item next-batch ${currentBatch === totalBatches ? "disabled" : ""}`;
//         nextBatchButton.innerHTML = `<a class="page-link" href="#">»</a>`;
//         nextBatchButton.addEventListener("click", (e) => {
//             e.preventDefault();
//             if (currentBatch < totalBatches) {
//                 currentBatch++;
//                 updatePagination();
//                 displayPage((currentBatch - 1) * pagesPerBatch + 1);
//             }
//         });
//         pagination.appendChild(nextBatchButton);
//     }

//     if (totalPages > 0) {
//         updatePagination();
//         displayPage(1); // Show the first page initially
//     }
// });

// Pagination and Search Function 
// document.addEventListener("DOMContentLoaded", function () {
//     const rowsPerPage = 5; // Number of rows per page
//     const pagesPerBatch = 5; // Number of pages per batch
//     const table = document.querySelector("#access_table tbody");
//     const rows = Array.from(table.rows);
//     const pagination = document.querySelector(".pagination");
//     const searchInput = document.getElementById("searchInput"); // Search input

//     let filteredRows = [...rows]; // Tracks visible (filtered) rows
//     let currentPage = 1;
//     let currentBatch = 1;

//     function filterRows() {
//         const searchValue = searchInput.value.toLowerCase();
//         filteredRows = rows.filter(row => row.textContent.toLowerCase().includes(searchValue));

//         // Reset pagination to match new filtered results
//         currentPage = 1;
//         currentBatch = 1;
//         updatePagination();
//         displayPage(1);
//     }

//     function displayPage(page) {
//         const start = (page - 1) * rowsPerPage;
//         const end = start + rowsPerPage;

//         // Hide all rows first
//         rows.forEach(row => row.style.display = "none");

//         // Show only the rows for the selected page
//         filteredRows.slice(start, end).forEach(row => row.style.display = "");

//         currentPage = page;
//         updatePagination();
//     }

//     function updatePagination() {
//         pagination.innerHTML = ""; // Clear pagination

//         const totalPages = Math.ceil(filteredRows.length / rowsPerPage);
//         const totalBatches = Math.ceil(totalPages / pagesPerBatch);
//         const startPage = (currentBatch - 1) * pagesPerBatch + 1;
//         const endPage = Math.min(startPage + pagesPerBatch - 1, totalPages);

//         if (totalPages <= 1) return; // Hide pagination if only one page

//         // Previous Batch Button
//         const prevBatchButton = document.createElement("li");
//         prevBatchButton.className = `page-item prev-batch ${currentBatch === 1 ? "disabled" : ""}`;
//         prevBatchButton.innerHTML = `<a class="page-link" href="#">«</a>`;
//         prevBatchButton.addEventListener("click", (e) => {
//             e.preventDefault();
//             if (currentBatch > 1) {
//                 currentBatch--;
//                 updatePagination();
//                 displayPage((currentBatch - 1) * pagesPerBatch + 1);
//             }
//         });
//         pagination.appendChild(prevBatchButton);

//         // Page Number Buttons
//         for (let i = startPage; i <= endPage; i++) {
//             const li = document.createElement("li");
//             li.className = `page-item ${i === currentPage ? "active" : ""}`;
//             li.innerHTML = `<a class="page-link" href="#">${i}</a>`;
//             li.addEventListener("click", (e) => {
//                 e.preventDefault();
//                 displayPage(i);
//             });
//             pagination.appendChild(li);
//         }

//         // Next Batch Button
//         const nextBatchButton = document.createElement("li");
//         nextBatchButton.className = `page-item next-batch ${currentBatch === totalBatches ? "disabled" : ""}`;
//         nextBatchButton.innerHTML = `<a class="page-link" href="#">»</a>`;
//         nextBatchButton.addEventListener("click", (e) => {
//             e.preventDefault();
//             if (currentBatch < totalBatches) {
//                 currentBatch++;
//                 updatePagination();
//                 displayPage((currentBatch - 1) * pagesPerBatch + 1);
//             }
//         });
//         pagination.appendChild(nextBatchButton);
//     }
//     // Event Listener for Search
//     searchInput.addEventListener("keyup", filterRows);

//     // Initial Load
//     filterRows();
// });
document.addEventListener("DOMContentLoaded", function () {
    const rowsPerPage = 5; // Number of rows per page
    const pagesPerBatch = 5; // Number of pages per batch
    const table = document.querySelector("#access_table tbody");
    const rows = Array.from(table.rows);
    const pagination = document.querySelector(".pagination");
    const searchInput = document.getElementById("searchInput"); 
    const roleFilter = document.getElementById("accessFilterSelect");
    const resetButton = document.getElementById("accessFilterReset");
    const applyButton = document.getElementById("accessFilterApply");

    let filteredRows = [...rows]; // Tracks currently visible rows
    let currentPage = 1;
    let currentBatch = 1;

    /** 🔹 APPLY SEARCH & ROLE FILTERS */
    function applyFilters() {
        const searchValue = searchInput.value.toLowerCase();
        const selectedRole = roleFilter.value.toLowerCase();

        filteredRows = rows.filter(row => {
            const rowText = row.textContent.toLowerCase();
            const roleValue = row.getAttribute("data-role")?.toLowerCase() || "";

            const matchesSearch = rowText.includes(searchValue);
            const matchesRole = selectedRole === "show all" || roleValue === selectedRole;

            return matchesSearch && matchesRole;
        });

        currentPage = 1;
        currentBatch = 1;
        updatePagination();
        displayPage(1);
    }

    /** 🔹 PAGINATION FUNCTION */
    function displayPage(page) {
        const start = (page - 1) * rowsPerPage;
        const end = start + rowsPerPage;

        rows.forEach(row => row.style.display = "none");
        filteredRows.slice(start, end).forEach(row => row.style.display = "");

        currentPage = page;
        updatePagination();
    }

    /** 🔹 UPDATE PAGINATION UI */
    function updatePagination() {
        pagination.innerHTML = "";
        const totalPages = Math.ceil(filteredRows.length / rowsPerPage);
        const totalBatches = Math.ceil(totalPages / pagesPerBatch);
        const startPage = (currentBatch - 1) * pagesPerBatch + 1;
        const endPage = Math.min(startPage + pagesPerBatch - 1, totalPages);

        if (totalPages <= 1) return; // Hide pagination if only one page

        // Previous Batch Button
        const prevBatchButton = document.createElement("li");
        prevBatchButton.className = `page-item prev-batch ${currentBatch === 1 ? "disabled" : ""}`;
        prevBatchButton.innerHTML = `<a class="page-link" href="#">«</a>`;
        prevBatchButton.addEventListener("click", (e) => {
            e.preventDefault();
            if (currentBatch > 1) {
                currentBatch--;
                updatePagination();
                displayPage((currentBatch - 1) * pagesPerBatch + 1);
            }
        });
        pagination.appendChild(prevBatchButton);

        // Page Number Buttons
        for (let i = startPage; i <= endPage; i++) {
            const li = document.createElement("li");
            li.className = `page-item ${i === currentPage ? "active" : ""}`;
            li.innerHTML = `<a class="page-link" href="#">${i}</a>`;
            li.addEventListener("click", (e) => {
                e.preventDefault();
                displayPage(i);
            });
            pagination.appendChild(li);
        }

        // Next Batch Button
        const nextBatchButton = document.createElement("li");
        nextBatchButton.className = `page-item next-batch ${currentBatch === totalBatches ? "disabled" : ""}`;
        nextBatchButton.innerHTML = `<a class="page-link" href="#">»</a>`;
        nextBatchButton.addEventListener("click", (e) => {
            e.preventDefault();
            if (currentBatch < totalBatches) {
                currentBatch++;
                updatePagination();
                displayPage((currentBatch - 1) * pagesPerBatch + 1);
            }
        });
        pagination.appendChild(nextBatchButton);
    }

    /** 🔹 RESET FILTERS */
    resetButton.addEventListener("click", () => {
        searchInput.value = "";
        roleFilter.value = "show all";
        applyFilters();
    });

    /** 🔹 EVENT LISTENERS */

    searchInput.addEventListener("keyup", applyFilters);
    roleFilter.addEventListener("change", applyFilters);

    /** 🔹 INITIALIZE */
    applyFilters();
});
