
//No matching Searches - in progress
// document.addEventListener('DOMContentLoaded', () => {
//     const searchInput = document.getElementById('searchInput');
//     const tableBody = document.querySelector('#companies_table tbody');
//     const tableRows = tableBody.querySelectorAll('tr');
//     const noResultsRow = document.createElement('tr');
    

//     noResultsRow.id = 'noResultsRow';
//     // noResultsRow.innerHTML = `
//     //     <td colspan="5" class="text-center">No matching courses found.</td>
//     // `;
//     noResultsRow.style.display = 'none';
//     tableBody.appendChild(noResultsRow);

//     searchInput.addEventListener('keyup', () => {
//         const searchValue = searchInput.value.toLowerCase();
//         let visibleRowCount = 0;

//         tableRows.forEach(row => {
//             if (row.id !== 'noResultsRow') {
//                 const cells = row.querySelectorAll('td');
//                 const rowText = Array.from(cells).map(cell => cell.textContent.toLowerCase()).join(' ');

//                 if (rowText.includes(searchValue)) {
//                     row.style.display = ''; // Show row if it matches search
//                     visibleRowCount++;
//                 } else {
//                     row.style.display = 'none'; // Hide row if it doesn't match search
//                 }
//             }
//         });

//         // Show or hide the "No Results" row
//         noResultsRow.style.display = visibleRowCount === 0 ? '' : 'none';
//     });
// });

//Validation
document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('modal_add_company_form');
    const companyNameInput = document.getElementById('add_company_name');

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
        
        const companyName = document.getElementById('add_company_name');
        const contactPerson = document.getElementById('add_company_contact_person');
        const contactNumber = document.getElementById('add_company_contact_number');
        const email = document.getElementById('add_company_email');
        const submitButton = document.getElementById('add_company_submit');

        let isValid = true;

        if (!companyName.value.trim()) {
            companyName.classList.add('border-danger');
            isValid = false;
        } else {
            companyName.classList.remove('border-danger');
        }

        if (!isValid) {
             Swal.fire({
                title: 'Missing Fields!',
                text: 'Please fill in all required fields.',
                icon: 'warning',
                confirmButtonText: 'OK'
            });
            return; // Stop submission if validation fails
        }

        Swal.fire({
            title: 'Are you sure?',
            text: "You are about to add this company.",
            icon: 'warning',
            buttonsStyling: false,
            showCancelButton: true,
            confirmButtonText: 'Yes, Add Company',
            cancelButtonText: 'Cancel',
            customClass: {
                popup: 'alps-swal-glass',
                confirmButton: "btn btn-primary btn-green",
                cancelButton: 'btn btn-tertiary btn-blue'
            },
            didOpen: (modal) => {
                modal.classList.add('alps-swal-glass');
            }
        }).then((result) => {
            if (result.isConfirmed) {
                submitButton.disabled = true;

                const formData = {
                    company_name: companyName.value.trim(),
                    contact_person: contactPerson.value.trim() || '',
                    contact_number: contactNumber.value.trim() || '',
                    email: email.value.trim() || ''
                };

                const routeUrl = document.getElementById('route-config-com').dataset.url;
                console.log("Route URL:", routeUrl);

                $.ajax({
                    url: routeUrl,
                    method: 'POST',
                    data: formData,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response){
                        if (response.success) {
                            Swal.fire('Added!', 
                                'The company has been added successfully.', 
                                'success')
                                .then(() => location.reload());
                        } else {
                            Swal.fire('Error!', 
                                'There was an issue adding the company.', 
                                'error');
                        }
                    },
                    error: function (xhr, status, error) {
                        const csrfToken = $('meta[name="csrf-token"]').attr('content');
                        console.error('AJAX Error Status:', xhr.status);
                        console.error('AJAX Error Response:', xhr.responseText);
                        console.error('CSRF Token Present:', !!csrfToken);
                        
                        let errorMsg = 'An unexpected error occurred.';
                        if (xhr.status === 419) {
                            errorMsg = 'Session expired. Please refresh the page and try again.';
                        } else if (xhr.status === 401 || xhr.status === 403) {
                            errorMsg = 'Authentication failed. Please log in again.';
                        }
                        
                        Swal.fire('Error!', errorMsg, 'error');
                    },
                    complete: function () {
                        submitButton.disabled = false;
                    }
                });
            }
        });
    });

    //Populate Existing Entry by id
    // Event listener to open the modal and populate it with data
    $(document).on('click', '.editCompanyBtn', function () {
        // Get the company ID from the button's data-id attribute
        const companyId = $(this).data('id');
    
        // Fetch company details using AJAX
        $.ajax({
            url: `/config/companies/${companyId}`, // Make sure this is the correct endpoint
            method: 'GET',
            success: function (response) {
                // Populate the modal with company data
                $('#edit_company_name').val(response.company_name);
                $('#edit_company_contact_person').val(response.contact_person);
                $('#edit_company_contact_number').val(response.contact_number);
                $('#edit_company_email').val(response.email);
    
                // Store company ID in the modal form (for saving)
                $('#modal_edit_company_form').data('id', companyId);
    
                // Open the modal
                $('#modal_edit_company').modal('show');
            },
            error: function (error) {
                console.error('Failed to fetch company details:', error);
                alert('An error occurred while fetching the company details.');
            }
        });
    });
    


    // Validation for Edit Company form
    document.getElementById('modal_edit_company_form').addEventListener('submit', (event) => {
        event.preventDefault();

        const companyId = $('#modal_edit_company_form').data('id');
        const companyName = document.getElementById('edit_company_name');
        const contactPerson = document.getElementById('edit_company_contact_person');
        const contactNumber = document.getElementById('edit_company_contact_number');
        const email = document.getElementById('edit_company_email');
        const submitButton = document.getElementById('edit_company_submit');

        let isValid = true;

        // Validate input
        if (!companyName.value.trim()) {
            companyName.classList.add('border-danger');
            isValid = false;
        } else {
            companyName.classList.remove('border-danger');
        }

        if (!isValid) {
             Swal.fire({
                title: 'Missing Fields!',
                text: 'Please fill in all required fields.',
                icon: 'warning',
                confirmButtonText: 'OK'
            });
            return; // Stop submission if validation fails
        }

        // Confirmation dialog
        Swal.fire({
            title: 'Are you sure?',
            text: "You are about to update this company.",
            icon: 'warning',
            buttonsStyling: false,
            showCancelButton: true,
            confirmButtonText: 'Yes, Update Company',
            cancelButtonText: 'Cancel',
            customClass: {
                popup: 'alps-swal-glass',
                confirmButton: "btn btn-primary btn-blue",
                cancelButton: 'btn btn-tertiary btn-blue'
            },
            didOpen: (modal) => {
                modal.classList.add('alps-swal-glass');
            }
        }).then((result) => {
            if (result.isConfirmed) {
                submitButton.disabled = true;

                // Prepare the data to send
                const formData = {
                    company_name: companyName.value.trim(),
                    contact_person: contactPerson.value.trim() || null,
                    contact_number: contactNumber.value.trim() || null,
                    email: email.value.trim() || null // Send null if email is empty
                };

                // Use the proper URL for the update action
                const routeUrl = `/config/companies/update/${companyId}`; // Ensure this is the correct endpoint

                console.log("Route URL:", routeUrl);

                $.ajax({
                    url: routeUrl,
                    method: 'PATCH', // Using PATCH to update the company
                    data: formData,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.success) {
                            Swal.fire('Updated!', 
                                'The company has been updated successfully.', 
                                'success')
                                .then(() => location.reload()); // Reload the page or update UI accordingly
                        } else {
                            Swal.fire('Error!', 
                                'There was an issue updating the company.', 
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

    

    //Remove invalid class on input change
    document.querySelectorAll('#add_company_name, #edit_company_name').forEach(input => {
        input.addEventListener('input', () => {
            if (input.value.trim()) {
                setValid(input);
            }
        });
    });
});


//Delete company button
document.querySelectorAll('.deleteBtn').forEach(button => {
    button.addEventListener('click', (event) => {
        event.preventDefault(); // Prevent default action (e.g., form submission)

        // Get the company ID from a data attribute (e.g., data-id)
        const companyId = button.getAttribute('data-id');

        if (!companyId) {
            console.error("Company ID not provided.");
            return;
        }

        Swal.fire({
            title: 'Are you sure?',
            text: "You are about to delete this company.",
            icon: 'warning',
            buttonsStyling: false,
            showCancelButton: true,
            confirmButtonText: 'Yes, Delete',
            cancelButtonText: 'Cancel',
            customClass: {
                popup: 'alps-swal-glass',
                confirmButton: "btn btn-primary btn-red",
                cancelButton: 'btn btn-tertiary btn-blue'
            },
            didOpen: (modal) => {
                modal.classList.add('alps-swal-glass');
            }
        }).then((result) => {
            if (result.isConfirmed) {
                // Perform the delete action
                fetch(`/config/companies/delete/${companyId}`, {
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
                            data.message || 'The company has been deleted.',
                            'success'
                        );

                        // Optionally remove the company row from the table or reload the page
                        document.querySelector(`#company-row-${companyId}`).remove();
                    } else {
                        Swal.fire(
                            'Error!',
                            data.message || 'There was an error deleting the company.',
                            'error'
                        );
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire(
                        'Error!',
                        'There was an error deleting the company.',
                        'error'
                    );
                });
            }
        });
    });
});


//Pagination
// document.addEventListener("DOMContentLoaded", function () {
//     const rowsPerPage = 5; // Number of rows per page
//     const pagesPerBatch = 5; // Number of pages per batch
//     const table = document.querySelector("#companies_table tbody");
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
document.addEventListener("DOMContentLoaded", function () {
    const rowsPerPage = 5; // Number of rows per page
    const pagesPerBatch = 5; // Number of pages per batch
    const table = document.querySelector("#companies_table tbody");
    const rows = Array.from(table.rows);
    const pagination = document.querySelector(".pagination");
    const searchInput = document.getElementById("searchInput"); // Search input

    let filteredRows = [...rows]; // Tracks visible (filtered) rows
    let currentPage = 1;
    let currentBatch = 1;

    function filterRows() {
        const searchValue = searchInput.value.toLowerCase();
        filteredRows = rows.filter(row => row.textContent.toLowerCase().includes(searchValue));

        // Reset pagination to match new filtered results
        currentPage = 1;
        currentBatch = 1;
        updatePagination();
        displayPage(1);
    }

    function displayPage(page) {
        const start = (page - 1) * rowsPerPage;
        const end = start + rowsPerPage;

        // Hide all rows first
        rows.forEach(row => row.style.display = "none");

        // Show only the rows for the selected page
        filteredRows.slice(start, end).forEach(row => row.style.display = "");

        currentPage = page;
        updatePagination();
    }

    function updatePagination() {
        pagination.innerHTML = ""; // Clear pagination

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

    // Event Listener for Search
    searchInput.addEventListener("keyup", filterRows);

    // Initial Load
    filterRows();
});







