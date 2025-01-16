// Search Filter
document.addEventListener('DOMContentLoaded', () => {
    const searchInput = document.getElementById('searchInput');
    const tableRows = document.querySelectorAll('#courses_table tbody tr');

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

// No matching Searches
document.addEventListener('DOMContentLoaded', () => {
    const searchInput = document.getElementById('searchInput');
    const tableBody = document.querySelector('#courses_table tbody');
    const tableRows = tableBody.querySelectorAll('tr');
    const noResultsRow = document.createElement('tr');

    noResultsRow.id = 'noResultsRow';
    noResultsRow.innerHTML = `
        <td colspan="3" class="text-center">No matching courses found.</td>
    `;
    noResultsRow.style.display = 'none';
    tableBody.appendChild(noResultsRow);

    searchInput.addEventListener('keyup', () => {
        const searchValue = searchInput.value.toLowerCase();
        let visibleRowCount = 0;

        tableRows.forEach(row => {
            if (row.id !== 'noResultsRow') {
                const cells = row.querySelectorAll('td');
                const rowText = Array.from(cells).map(cell => cell.textContent.toLowerCase()).join(' ');

                if (rowText.includes(searchValue)) {
                    row.style.display = ''; // Show row if it matches search
                    visibleRowCount++;
                } else {
                    row.style.display = 'none'; // Hide row if it doesn't match search
                }
            }
        });

        // Show or hide the "No Results" row
        noResultsRow.style.display = visibleRowCount === 0 ? '' : 'none';
    });
});



//Validation
document.addEventListener('DOMContentLoaded', () => {

    document.getElementById('modal_add_course_form').addEventListener('submit', (event) => {
        event.preventDefault();
    
        const courseName = document.getElementById('add_course_name');
        const courseCode = document.getElementById('add_course_code');
        const submitButton = document.getElementById('add_course_submit');
    
        // Basic Validation for Course Name
        if (!courseName.value.trim()) {
            courseName.classList.add('is-invalid');
            return;
        } else {
            courseName.classList.remove('is-invalid');
        }
    
        // Display Confirmation Dialog
        Swal.fire({
            title: 'Are you sure?',
            text: "You are about to add this course.",
            icon: 'warning',
            buttonsStyling: false,
            showCancelButton: true,
            confirmButtonText: 'Yes, Add Course',
            cancelButtonText: 'Cancel',
            customClass: {
                confirmButton: "btn btn-success",
                cancelButton: 'btn btn-secondary'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                submitButton.disabled = true;
    
                const formData = {
                    course_name: courseName.value.trim(),
                    course_code: courseCode.value.trim() || ''
                };
    
                // FETCH ROUTE URL FROM BLADE DATA ATTRIBUTE
                const routeUrl = document.getElementById('route-config').dataset.url;
                console.log("Route URL:", routeUrl);
                
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
    $(document).on('click', '.editCourseBtn', function () {
        // Get the course ID from the button's data-id attribute
        const courseId = $(this).data('id');
    
        // Fetch course details using AJAX
        $.ajax({
            url: `/config/courses/${courseId}`, // Make sure this is the correct endpoint
            method: 'GET',
            success: function (response) {
                // Populate the modal with course data
                $('#edit_course_name').val(response.course_name);
                $('#edit_course_code').val(response.course_code);
    
                // Optionally, store the course ID in the modal form for later use (for saving)
                $('#modal_edit_course_form').data('id', courseId);
    
                // Open the modal
                $('#modal_edit_course').modal('show');
            },
            error: function (error) {
                console.error('Failed to fetch course details:', error);
                alert('An error occurred while fetching the course details.');
            }
        });
    });

    //Validation for Edit Course form
    
    document.getElementById('modal_edit_course_form').addEventListener('submit', (event) => {
        event.preventDefault();

        const courseId = $('#modal_edit_course_form').data('id');
        const courseName = document.getElementById('edit_course_name');
        const courseCode = document.getElementById('edit_course_code');
        const submitButton = document.getElementById('edit_course_submit');

        if (!courseName.value.trim()) {
            courseName.classList.add('is-invalid');
            return;
        } else {
            courseName.classList.remove('is-invalid');
        }

        Swal.fire({
            title: 'Are you sure?',
            text: "You are about to edit this course.",
            icon: 'warning',
            buttonsStyling: false,
            showCancelButton: true,
            confirmButtonText: 'Yes, Edit Course',
            cancelButtonText: 'Cancel',
            customClass: {
                confirmButton: "btn btn-success",
                cancelButton: 'btn btn-secondary'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                submitButton.disabled = true;

                const formData = {
                    course_name: courseName.value.trim(),
                    course_code: courseCode.value.trim() || null  
                };

                const routeUrl = `/config/courses/update/${courseId}`;
                
                console.log("Route URL:", routeUrl);

                $.ajax({
                    url: routeUrl,
                    method: 'PATCH',
                    data: formData,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response){
                        if (response.success) {
                            Swal.fire('Updated!',
                                'The course has been updated successfully.',
                                'success')
                                .then(() => location.reload());
                        } else {
                            Swal.fire('Error!',
                                'There was an issue updating the course.',
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
    document.querySelectorAll('#add_course_name, #edit_course_name').forEach(input => {
        input.addEventListener('input', () => {
            if (input.value.trim()) {
                input.classList.remove('is-invalid');
            }
        });
    });
});

//Delete course button
document.querySelectorAll('.deleteBtn').forEach(button => {
    button.addEventListener('click', (event) => {
        event.preventDefault(); // Prevent default action (e.g., form submission)

        // Get the course ID from a data attribute (e.g., data-id)
        const courseId = button.getAttribute('data-id');

        if (!courseId) {
            console.error("course ID not provided.");
            return;
        }

        Swal.fire({
            title: 'Are you sure?',
            text: "You are about to delete this course.",
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
                fetch(`/config/courses/delete/${courseId}`, {
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
                            data.message || 'The course has been deleted.',
                            'success'
                        );

                        // Optionally remove the course row from the table or reload the page
                        document.querySelector(`#course-row-${courseId}`).remove();
                    } else {
                        Swal.fire(
                            'Error!',
                            data.message || 'There was an error deleting the course.',
                            'error'
                        );
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire(
                        'Error!',
                        'There was an error deleting the course.',
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
    const table = document.querySelector("#courses_table tbody");
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





