document.addEventListener('DOMContentLoaded', function () {
    var calendarEl = document.getElementById('calendar');
    var loaderWrapper = document.getElementById('loader-wrapper');
    var popoverState = false; // Track if a popover is active
    var popover = null; // Reference to the active popover
    var currentHoveredEvent = null; // Track the currently hovered event
    var calendar;

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
            try {
                popover.dispose(); // Ensure popover exists and is attached before disposing
            } catch (error) {
                console.error("Error disposing popover:", error);
            }
            popoverState = false;
            popover = null; // Reset the popover reference
            currentHoveredEvent = null;
        }
    };


    const clearCalendarEvents = (calendar) => {
        if (calendar) {
            calendar.removeAllEvents();
            console.log("All events have been removed from the calendar.");
        } else {
            console.error("Calendar instance is not initialized.");
        }
    };

    //on load, check local storage if calendar_setting is existing

    //IF NOT, set blank calendar as value and initialize training calendar

    //IF YES, check value and set corresponding calendar

    //ON CHANGE, destroy calendar and set as training or unavailability

    const getPopulation = (route) => {
        let backend_request_route;

        if (route == 'trainings') {
            backend_request_route = '/calendar/api/get/training';
        } else if (route == 'unavailability') {
            backend_request_route = '/calendar/api/get/unavailability';
        }

        // Fetch events dynamically using jQuery AJAX
        $.ajax({
            url: backend_request_route,
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
                    // Clear all existing events
                    clearCalendarEvents(calendar);

                    // Add new events to the calendar
                    if (route == 'trainings') {
                        response.data.forEach(function (training) {
                            if (training.schedule) {
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
                    } else if (route == 'unavailability') {
                        response.data.forEach(function (unavailability) {
                            if (unavailability.from_date && unavailability.to_date) {
                                var fromDateTime = moment(unavailability.from_date).toISOString();
                                var toDateTime = moment(unavailability.to_date).add(1, 'days').toISOString();

                                calendar.addEvent({
                                    id: unavailability.id,
                                    title: unavailability.reason || 'Unavailable',
                                    start: fromDateTime,
                                    end: toDateTime,
                                    allDay: true,
                                    backgroundColor: '#FF5E5E',
                                    borderColor: unavailability.user.color || '#FF5E5E',
                                    extendedProps: {
                                        user: unavailability.user ? unavailability.user.name : 'Unknown User',
                                    },
                                });
                            }
                        });
                    }

                    // Rebind event listeners for popovers
                    bindEventListeners();

                    loaderWrapper.classList.add('d-none');
                    $('#calendar').removeClass('blur-effect');
                } else {
                    console.error('Failed to load events:', response.message);
                    loaderWrapper.classList.add('d-none');
                }
            },
            error: function (xhr, status, error) {
                console.error('Error fetching events:', error);
                loaderWrapper.classList.add('d-none');
            },
            complete: function () {
                loaderWrapper.classList.add('d-none');
                $('#calendar').removeClass('blur-effect');
            },
        });
    };

    // Function to bind event listeners to FullCalendar events
    const bindEventListeners = () => {
        calendar.setOption('eventMouseEnter', function (info) {
            // Safely dispose of any existing popover
            if (popover) {
                try {
                    popover.dispose();
                } catch (error) {
                    console.error("Error disposing popover:", error);
                }
                popoverState = false;
                popover = null;
            }

            // Extract event data and initialize a new popover
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
                id: info.event.id || '',
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

            initPopovers(info.el, eventData);
        });

        calendar.setOption('eventMouseLeave', function () {
            // hidePopovers(); // Ensure safe disposal of the popover
        });
    };


    // Initialize the calendar and its initial population
    if (calendarEl) {
        let initial = 'trainings';

        hidePopovers();

        calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay',
            },
            dayMaxEvents: 5,
            height: 1500,
            events: [],
        });

        calendar.render();

        // Load initial data
        getPopulation(initial);

        // Bind filter change to update events
        $('#applyFilter').click(function (e) {
            e.preventDefault();

            let filter = $('#filters').find('option:selected').val();

            hidePopovers();

            getPopulation(filter);
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
