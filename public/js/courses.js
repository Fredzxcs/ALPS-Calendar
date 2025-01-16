//Validation for add
document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('modal_add_course_form');
    const courseNameInput = document.getElementById('add_course_name');

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

        if (!courseNameInput.value.trim()) {
            setInvalid(courseNameInput);
            isValid = false;
        } else {
            setValid(courseNameInput);
        }

        // If all fields are valid
        if (isValid) {
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
                    Swal.fire(
                        'Added!',
                        'The course has been added.',
                        'success'
                    );
                    // TODO: Add logic to perform add course here
                }
            });
        }
    });

    // Remove invalid border on input change
    [courseNameInput].forEach(input => {
        input.addEventListener('input', () => {
            if (input.value.trim()) {
                setValid(input);
            }
        });
    });
});

//Validation for edit
document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('modal_edit_course_form');
    const courseNameInput = document.getElementById('edit_course_name');

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

        if (!courseNameInput.value.trim()) {
            setInvalid(courseNameInput);
            isValid = false;
        } else {
            setValid(courseNameInput);
        }

        // If all fields are valid
        if (isValid) {
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
                    Swal.fire(
                        'Edited!',
                        'The course has been edited.',
                        'success'
                    );
                    // TODO: Add logic to perform edit course here
                }
            });
        }
    });

    // Remove invalid border on input change
    [courseNameInput].forEach(input => {
        input.addEventListener('input', () => {
            if (input.value.trim()) {
                setValid(input);
            }
        });
    });
});

//Delete course button
document.querySelectorAll('.deleteBtn').forEach(button => {
    button.addEventListener('click', (event) => {
        event.preventDefault(); // Prevent default action (e.g., form submission)

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
                // Proceed with the delete
                Swal.fire(
                    'Deleted!',
                    'The course has been deleted.',
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




