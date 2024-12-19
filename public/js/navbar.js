document.addEventListener("DOMContentLoaded", function () {
        // Get the current URL path
        const currentPath = window.location.pathname;

        // Find all navigation links
        const navLinks = document.querySelectorAll('.nav-link');

        // Loop through each link to check if the href matches the current path
        navLinks.forEach(link => {
            if (link.href.includes(currentPath)) {
                link.style.color = "#7c0101"; // Set the color to maroon
                link.classList.add("active"); // Optionally, add an "active" class
            }
        });
    });