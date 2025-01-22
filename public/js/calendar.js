document.addEventListener('DOMContentLoaded', function () {
    var calendarEl = document.getElementById('calendar');
    var loaderWrapper = document.getElementById('loader-wrapper');
    var popoverState = false; // Track if a popover is active
    var popover = null; // Reference to the active popover
    var currentHoveredEvent = null; // Track the currently hovered event

    // Function to initialize popovers
    const initPopovers = (element, data) => {
        hidePopovers(); // Hide any active popovers

        console.log(data);

        // Generate popover content
        const startDate = data.allDay
            ? moment(data.startDate).format('MMM DD, YYYY')
            : moment(data.startDate).format('MMM DD, YYYY - h:mm a');
        const endDate = data.allDay
            ? moment(data.endDate).format('MMM DD, YYYY')
            : moment(data.endDate).format('MMM DD, YYYY - h:mm a');

        const modeBadges = [];
        if (data.modeType === 'virtual') {
            modeBadges.push('<span class="badge badge-primary fw-bold">Virtual</span>');
        }
        if (data.modeType === 'face-to-face') {
            modeBadges.push('<span class="badge badge-info fw-bold">Face-to-Face</span>');
        }
        if (data.modeType === 'public-course') {
            modeBadges.push('<span class="badge badge-danger fw-bold">Public Course</span>');
        }

        const popoverHtml = `
            <div class="fw-bolder mb-2">${data.eventName} - ${data.company || "Public Course"}</div>
            <div class="fs-7 mb-2">${modeBadges.join(' ')}</div>
            <div class="fs-7"><span class="fw-bold">Start:</span> ${startDate}</div>
            <div class="fs-7 mb-2"><span class="fw-bold">End:</span> ${endDate}</div>
            <div class="fs-7"><span class="fw-bold">Facilitator:</span> ${(data.facilitator || data.facilitator.name) || 'No Facilitator Yet'}</div>
            <div class="fs-7 mb-4"><span class="fw-bold">Assistant:</span> ${data.assistant}</div>
            <a id="kt_calendar_event_view" type="button" class="btn btn-sm btn-light-primary mt-2" data-event-id="${data.id}" data-bs-dismiss="modal">
                VIEW MORE
            </a>
        `;

        var options = {
            container: 'body',
            trigger: 'manual',
            boundary: 'window',
            placement: 'auto',
            dismiss: true, // X button in event summary
            html: true,
            title: 'Training Summary',
            content: popoverHtml,
        };

        // Initialize popover
        popover = KTApp.initBootstrapPopover(element, options);
        popover.show();
        popoverState = true;

        // Attach the modal event listener after popover HTML is inserted
        document.getElementById('kt_calendar_event_view').addEventListener('click', function (e) {
            e.preventDefault(); // Prevent default link action

            const modalElement = document.getElementById('kt_modal_view_training');
            const modal = new bootstrap.Modal(modalElement);
            modal.show();
        });
    };

    // Function to hide active popovers
    const hidePopovers = () => {
        if (popoverState && popover) {
            popover.dispose();
            popoverState = false;
            currentHoveredEvent = null; // Reset the currently hovered event
        }
    };

    if (calendarEl) {
        // Initialize the calendar
        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay',
            },
            dayMaxEvents: 5,
            dayMaxEvents: true,
            dayMaxEventRows: true,
            height: 1500,
            events: [],
            moreLinkClick: function (info) {
                // Slice the excess events, excluding the first 3 visible ones
                const excessEvents = info.allSegs.slice(3);

                // Generate content for the modal
                let modalContent = `<h5>Excess Events on ${info.date.toLocaleDateString()}</h5><ul>`;
                excessEvents.forEach((seg) => {
                    modalContent += `<li>${seg.event.title}</li>`;
                });
                modalContent += '</ul>';
                // Update modal content dynamically here
            },
           eventMouseEnter: function (info) {
                // Dispose of the current popover if it exists
                if (popover) {
                    popover.dispose();
                    popoverState = false;
                }

                // Safely extract and validate event data
                const eventData = {
                    eventName: info.event.title || 'No Title',
                    startDate: info.event.start || null,
                    endDate: info.event.end || null,
                    allDay: info.event.allDay || false,
                    modeType: info.event.extendedProps.modeType || 'N/A',
                    company: info.event.extendedProps.company || 'N/A',
                    facilitator: info.event.extendedProps.facilitator?.name || 'No Facilitator Yet',
                    assistant: info.event.extendedProps.assistant || 'No Assistant Yet',
                    account: info.event.extendedProps.account || { account_email: 'N/A' },
                    location: info.event.extendedProps.location || 'N/A',
                    id: info.event.id || ''
                };

                const $modalElement = $('#kt_modal_view_training');
                $modalElement.find('#modal-title').text('VIEW TRAINING');

                const baseUrl = $('#edit-training-link').data('base-url');
                const editUrl = `${baseUrl}${eventData.id}`;
                $('#edit-training-link').attr('href', editUrl);

                const mode = eventData.modeType;
                $modalElement.find('#modal-mode-of-training').text(mode.toUpperCase());

                $modalElement.find('#modal-location').text(eventData.location);

                const accountEmail = eventData.account.account_email;
                $modalElement.find('#modal-credentials').text(accountEmail || 'N/A');

                let inPersonText = mode === 'virtual' ? 'No' : 'Yes';
                if (mode === 'public-course' && accountEmail) {
                    inPersonText = 'No';
                }
                $modalElement.find('#modal-in-person').text(inPersonText);

                $modalElement.find('#modal-company').text(eventData.company);
                $modalElement.find('#modal-course').text(eventData.eventName);
                $modalElement.find('#modal-facilitator').text(eventData.facilitator);
                $modalElement.find('#modal-assistant').text(eventData.assistant);

                const formattedStartDate = eventData.startDate ? moment(eventData.startDate).format('MMM DD, YYYY') : 'N/A';
                const formattedEndDate = eventData.endDate ? moment(eventData.endDate).format('MMM DD, YYYY') : 'N/A';
                $modalElement.find('#modal-date').text(`${formattedStartDate} to ${formattedEndDate}`);

                const formattedStartTime = eventData.startDate ? moment(eventData.startDate).format('h:mm A') : 'N/A';
                const formattedEndTime = eventData.endDate ? moment(eventData.endDate).format('h:mm A') : 'N/A';
                $modalElement.find('#modal-time').text(`${formattedStartTime} to ${formattedEndTime}`);

                // Initialize popover
                initPopovers(info.el, eventData);
            },

            eventMouseLeave: function (info) {
                // Popover will only close when another event is hovered
            },
        });

        calendar.render();

        // Fetch events dynamically using jQuery AJAX
        $.ajax({
            url: '/calendar/api/get/training', // Replace with your actual endpoint
            method: 'GET',
            dataType: 'json',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            beforeSend: function () {
                $('#calendar').addClass('blur-effect');
                loaderWrapper.style.display = 'flex';
            },
            success: function (response) {

                console.log(response);

                if (response.success) {

                    // Add events to the calendar
                    response.data.forEach(function (training) {

                        if (training.schedule) {
                            // Extract the schedule details
                            var fromDateTime = `${training.schedule.from_date}T${training.schedule.from_time}`;
                            var toDateTime = `${training.schedule.to_date}T${training.schedule.to_time}`;

                            calendar.addEvent({
                                id: training.id,
                                facilitator: training.facilitator || 'N/A',
                                assistant: training.assistant,
                                modeType: training.mode,
                                account: training.account,
                                title: training.course.course_name,
                                company: training.company ? training.company.company_name : 'N/A',
                                start: fromDateTime,
                                end: toDateTime,
                                location: training.location,
                                allDay: false,
                                backgroundColor: training.facilitator ? training.facilitator.color : '#808080',

                            });
                        }
                    });
                    loaderWrapper.classList.add('d-none');
                    $('#calendar').removeClass('blur-effect');
                } else {
                    console.error('Failed to load trainings:', response.message);
                    loaderWrapper.classList.add('d-none');
                }
            },
            error: function (xhr, status, error) {
                console.error('Error fetching trainings:', error);
                loaderWrapper.classList.add('d-none');
            },
            complete: function () {
                loaderWrapper.classList.add('d-none');
                $('#calendar').removeClass('blur-effect');
            },
        });
    }



});

//Delete training button
document.querySelectorAll('.deleteBtn').forEach(button => {
    button.addEventListener('click', (event) => {
        event.preventDefault(); // Prevent default action (e.g., form submission)

        Swal.fire({
            title: 'Are you sure?',
            text: "You are about to delete this training.",
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
                    'The training has been deleted.',
                    'success'
                );

                // TODO: Add logic to perform delete
            }
        });
    });
});

//Password reveal in view modal
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