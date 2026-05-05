// Search Filter
// document.addEventListener('DOMContentLoaded', () => {
//     const searchInput = document.getElementById('searchInput');
//     const tableRows = document.querySelectorAll('#courses_table tbody tr');

//     searchInput.addEventListener('keyup', () => {
//         const searchValue = searchInput.value.toLowerCase();

//         tableRows.forEach(row => {
//             const cells = row.querySelectorAll('td');
//             const rowText = Array.from(cells).map(cell => cell.textContent.toLowerCase()).join(' ');

//             if (rowText.includes(searchValue)) {
//                 row.style.display = ''; // Show row if it matches search
//             } else {
//                 row.style.display = 'none'; // Hide row if it doesn't match search
//             }
//         });
//     });
// });
// document.addEventListener("DOMContentLoaded", function () {
//     const searchInput = document.getElementById("searchInput");
//     const table = document.querySelector("#courses_table tbody");
//     const rows = Array.from(table.rows);
//     const pagination = document.querySelector(".pagination");

//     let rowsPerPage = 5; // Number of rows per page
//     let currentPage = 1;

//     function filterRows() {
//         const searchValue = searchInput.value.toLowerCase();

//         // Show only rows that match the search
//         rows.forEach(row => {
//             const rowText = row.textContent.toLowerCase();
//             row.style.display = rowText.includes(searchValue) ? "" : "none";
//         });

//         updatePagination(); // Recalculate pagination based on visible rows
//         displayPage(1); // Always start from the first page
//     }

//     function displayPage(page) {
//         const visibleRows = rows.filter(row => row.style.display !== "none");
//         const start = (page - 1) * rowsPerPage;
//         const end = start + rowsPerPage;

//         // Hide all first
//         rows.forEach(row => (row.style.display = "none"));

//         // Show only the rows for the selected page
//         visibleRows.slice(start, end).forEach(row => (row.style.display = ""));

//         currentPage = page;
//         updatePagination();
//     }

//     function updatePagination() {
//         pagination.innerHTML = ""; // Clear existing pagination

//         const visibleRows = rows.filter(row => row.style.display !== "none");
//         const totalPages = Math.ceil(visibleRows.length / rowsPerPage);

//         if (totalPages <= 1) return; // Hide pagination if only one page

//         // Previous Button
//         const prevButton = document.createElement("li");
//         prevButton.className = `page-item ${currentPage === 1 ? "disabled" : ""}`;
//         prevButton.innerHTML = `<a class="page-link" href="#">«</a>`;
//         prevButton.addEventListener("click", (e) => {
//             e.preventDefault();
//             if (currentPage > 1) displayPage(currentPage - 1);
//         });
//         pagination.appendChild(prevButton);

//         // Page Number Buttons
//         for (let i = 1; i <= totalPages; i++) {
//             const li = document.createElement("li");
//             li.className = `page-item ${i === currentPage ? "active" : ""}`;
//             li.innerHTML = `<a class="page-link" href="#">${i}</a>`;
//             li.addEventListener("click", (e) => {
//                 e.preventDefault();
//                 displayPage(i);
//             });
//             pagination.appendChild(li);
//         }

//         // Next Button
//         const nextButton = document.createElement("li");
//         nextButton.className = `page-item ${currentPage === totalPages ? "disabled" : ""}`;
//         nextButton.innerHTML = `<a class="page-link" href="#">»</a>`;
//         nextButton.addEventListener("click", (e) => {
//             e.preventDefault();
//             if (currentPage < totalPages) displayPage(currentPage + 1);
//         });
//         pagination.appendChild(nextButton);
//     }

//     // Event Listeners
//     searchInput.addEventListener("keyup", filterRows);

//     // Initial Load
//     updatePagination();
//     displayPage(1);
// });

function setValid(input) {
    input.classList.remove('border-danger');
}

document.addEventListener('DOMContentLoaded', () => {

    document.getElementById('modal_add_course_form').addEventListener('submit', (event) => {
        event.preventDefault();

        const courseName = document.getElementById('add_course_name');
        const courseCode = document.getElementById('add_course_code');
        const submitButton = document.getElementById('add_course_submit');
        const courseNameInput = document.getElementById('edit_course_name');

        let isValid = true;

        // Basic Validation for Course Name
        if (!courseName.value.trim()) {
            courseName.classList.add('border-danger');
            isValid = false;
        } else {
            courseName.classList.remove('border-danger');
        }

        if (!courseCode.value.trim()) {
            courseCode.classList.add('border-danger');
            isValid = false;
        } else {
            courseCode.classList.remove('border-danger');
        }

        if (!isValid) {
            Swal.fire({
                title: 'Missing Fields!',
                text: 'Please fill in all required fields.',
                icon: 'warning',
                confirmButtonText: 'OK'
            });
            return;
        } // Stop submission if validation fails

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
    $(document).on('click', '.editCourseBtn', function () {
        const courseId = $(this).data('id');

        $.ajax({
            url: `/config/courses/${courseId}`,
            method: 'GET',
            success: function (response) {
                $('#edit_course_name').val(response.course_name);
                $('#edit_course_code').val(response.course_code);
                $('#modal_edit_course_form').data('id', courseId);
                $('#modal_edit_course').modal('show');
            },
            error: function (error) {
                console.error('Failed to fetch course details:', error);
                alert('An error occurred while fetching the course details.');
            }
        });
    });
});

document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('modal_edit_course_form');
    const courseNameInput = document.getElementById('edit_course_name');

    document.getElementById('modal_edit_course_form').addEventListener('submit', (event) => {
        event.preventDefault();

        const courseId = $('#modal_edit_course_form').data('id');
        const courseName = document.getElementById('edit_course_name');
        const courseCode = document.getElementById('edit_course_code');
        const submitButton = document.getElementById('edit_course_submit');

        let isValid = true;

        if (!courseName.value.trim()) {
            courseName.classList.add('border-danger');
            isValid = false;
        } else {
            courseName.classList.remove('border-danger');
        }

        if (!courseCode.value.trim()) {
            courseCode.classList.add('border-danger');
            isValid = false;
        } else {
            courseCode.classList.remove('border-danger');
        }


        if (!isValid) {
            Swal.fire({
                title: 'Missing Fields!',
                text: 'Please fill in all required fields.',
                icon: 'warning',
                confirmButtonText: 'OK'
            });
            return;
        } // Stop submission if validation fails

        Swal.fire({
            title: 'Are you sure?',
            text: "You are about to edit this course.",
            icon: 'warning',
            buttonsStyling: false,
            showCancelButton: true,
            confirmButtonText: 'Yes, Edit Course',
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

                const formData = {
                    course_name: courseName.value.trim(),
                    course_code: courseCode.value.trim() || null
                };

                const routeUrl = `/config/courses/update/${courseId}`;

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

    [courseNameInput].forEach(input => {
        input.addEventListener('input', () => {
            if (input.value.trim()) {
                input.classList.remove('border-danger');
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
// document.addEventListener("DOMContentLoaded", function () {
//     const rowsPerPage = 5; // Number of rows per page
//     const pagesPerBatch = 5; // Number of pages per batch
//     const table = document.querySelector("#courses_table tbody");
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
    const table = document.querySelector("#courses_table tbody");
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





