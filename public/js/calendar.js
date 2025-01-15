document.addEventListener('DOMContentLoaded', function () {
    var calendarEl = document.getElementById('calendar');
    var loaderWrapper = document.getElementById('loader-wrapper');
    var popoverState = false; // Track if a popover is active
    var popover = null; // Reference to the active popover
    var currentHoveredEvent = null; // Track the currently hovered event

    // Function to initialize popovers
    const initPopovers = (element, data) => {
        hidePopovers(); // Hide any active popovers
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
            <div class="fs-7"><span class="fw-bold">Facilitator:</span> ${(data.facilitator && data.facilitator.name) || 'No Facilitator Yet'}</div>
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
            dayMaxEvents: 2,
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

                // Data for popover
                const eventData = {
                    eventName: info.event.title,
                    startDate: info.event.start,
                    endDate: info.event.end || null,
                    allDay: info.event.allDay,
                    modeType: info.event.extendedProps.modeType,
                    company: info.event.extendedProps.company,
                    facilitator: info.event.extendedProps.facilitator,
                    assistant: info.event.extendedProps.assistant,
                    email: info.event.extendedProps.credentials_email,
                    password: info.event.extendedProps.credentials_password,
                    location: info.event.extendedProps.location,
                    id: info.event.id
                };

                const $modalElement = $('#kt_modal_view_training');
                $modalElement.find('#modal-title').text('VIEW TRAINING');

                let mode = eventData.modeType;

                $modalElement.find('#modal-mode-of-training').text(`${mode.toUpperCase() || 'N/A'}`);
                $modalElement.find('#modal-location').text(`${eventData.location || 'N/A'}`);

                if(eventData.email === "Select Account to Host Training")
                {
                    $modalElement.find('#modal-credentials').text('N/A');
                }
                else{
                    $modalElement.find('#modal-credentials').text(`${eventData.email || 'N/A'}`);
                }

                let inpersontext = 'Yes';

                //if public course and credentials == No

                if(eventData.modeType === "virtual")
                {
                    inpersontext = 'No';
                }
                else if (eventData.modeType === "public-course")
                {
                    if(eventData.email !== "Select Account to Host Training")
                    {
                        inpersontext = 'No';
                    }
                }

                $modalElement.find('#modal-in-person').text(inpersontext);

                $modalElement.find('#modal-company').text(`${eventData.company || 'N/A'}`);

                $modalElement.find('#modal-course').text(`${eventData.eventName || ''}`);

                $modalElement.find('#modal-facilitator').text(`${eventData.facilitator?.name || 'No Facilitator Yet'}`);
                $modalElement.find('#modal-assistant').text(`${eventData.assistant || 'No Assistant Yet'}`);

                $modalElement.find('#modal-date').text(
                    `${moment(eventData.startDate).format('MMM DD, YYYY')} to ${moment(eventData.endDate).format('MMM DD, YYYY')}`
                );
                $modalElement.find('#modal-time').text(
                    `${moment(eventData.startDate).format('h:mm A')} to ${moment(eventData.endDate).format('h:mm A')}`
                );
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
                if (response.success) {

                    console.log(response);
                    // Add events to the calendar
                    response.data.forEach(function (training) {
                        if (training.schedule) {
                            // Extract the schedule details
                            var fromDateTime = `${training.schedule.from_date}T${training.schedule.from_time}`;
                            var toDateTime = `${training.schedule.to_date}T${training.schedule.to_time}`;

                            calendar.addEvent({
                                id: training.id,
                                facilitator: training.facilitator,
                                assistant: training.assistant_id,
                                modeType: training.mode,
                                credentials_email: training.credentials_email,
                                credentials_password: training.credentials_password,
                                title: training.course,
                                company: training.company,
                                start: fromDateTime,
                                end: toDateTime,
                                location: training.location,
                                backgroundColor: training.facilitator.color ? training.facilitator.color : '#808080',

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
