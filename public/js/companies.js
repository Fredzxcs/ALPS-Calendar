//Validation
document.addEventListener('DOMContentLoaded', () => {
    //Validation for Add Company form
    document.getElementById('modal_add_company_form').addEventListener('submit', (event) => {
        event.preventDefault();
        
        const companyName = document.getElementById('add_company_name');
        const contactPerson = document.getElementById('add_company_contact_person');
        const contactNumber = document.getElementById('add_company_contact_number');
        const email = document.getElementById('add_company_email');
        const submitButton = document.getElementById('add_company_submit');

        if (!companyName.value.trim()) {
            companyName.classList.add('is-invalid');
            return;
        } else {
            companyName.classList.remove('is-invalid');
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
                confirmButton: "btn btn-success",
                cancelButton: 'btn btn-secondary'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                submitButton.disabled = true;

                const formData = {
                    company_name: companyName.value.trim(),
                    contact_person: contactPerson.value.trim(),
                    contact_number: contactNumber.value.trim(),
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
                                'The course has been added successfully.', 
                                'success')
                                .then(() => location.reload());
                        } else {
                            Swal.fire('Error!', 
                                'There was an issue adding the course.', 
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
    
                // Optionally, store the company ID in the modal form for later use (for saving)
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

        // Validate input
        if (!companyName.value.trim()) {
            companyName.classList.add('is-invalid');
            return;
        } else {
            companyName.classList.remove('is-invalid');
        }

        if (!contactNumber.value.trim()) {
            contactNumber.classList.add('is-invalid');
            return;
        } else {
            contactNumber.classList.remove('is-invalid');
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
                confirmButton: "btn btn-success",
                cancelButton: 'btn btn-secondary'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                submitButton.disabled = true;

                // Prepare the data to send
                const formData = {
                    company_name: companyName.value.trim(),
                    contact_person: contactPerson.value.trim(),
                    contact_number: contactNumber.value.trim(),
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
                input.classList.remove('is-invalid');
            }
        });
    });
});

//Delete company button
document.querySelectorAll('.deleteBtn').forEach(button => {
    button.addEventListener('click', (event) => {
        event.preventDefault(); // Prevent default action (e.g., form submission)

        Swal.fire({
            title: 'Are you sure?',
            text: "You are about to delete this company.",
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
                    'The company has been deleted.',
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
    const table = document.querySelector("#companies_table tbody");
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




