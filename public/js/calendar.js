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
            const popoverHtml =
            '<div class="fw-bolder mb-2">' + data.eventName + '</div>' +
            '<div class="fs-7 mb-2"><span class="badge badge-primary fw-bold">Virtual</span><span class="badge badge-info fw-bold">Face-to-Face</span><span class="badge badge-danger fw-bold">Public Course</span></div>' +
            '<div class="fs-7"><span class="fw-bold">Start:</span> ' + startDate + '</div>' +
            '<div class="fs-7 mb-2"><span class="fw-bold">End:</span> ' + endDate + '</div>' +
            '<div class="fs-7"><span class="fw-bold">Facilitator:</span> John Loyd </div>' +
            '<div class="fs-7 mb-4"><span class="fw-bold">Assistant:</span> Not Yet Assigned </div>' +
            '<div id="kt_calendar_event_view" type="button" class="btn btn-sm btn-light-primary mt-2" data-bs-toggle="modal" data-bs-target="#kt_modal_view_training" data-dismiss="modal" >VIEW MORE</div>';
        // Popover options
        var options = {
            container: 'body',
            trigger: 'manual',
            boundary: 'window',
            placement: 'auto',
            dismiss: true, // X button sa event summary
            html: true,
            title: 'Training Summary',
            content: popoverHtml,
        };

        // Initialize popover
        popover = KTApp.initBootstrapPopover(element, options);
        popover.show();
        popoverState = true;
    };


    // Function to hide active popovers
    const hidePopovers = () => {
        if (popoverState && popover) {
            popover.dispose();
            popoverState = false;
            currentHoveredEvent = null; // Reset the currently hovered event
        }

        document.addEventListener('click', function (event) {
            if (event.target && event.target.id === 'kt_calendar_event_view') {
                // Clean up any existing backdrops before showing the modal
                const existingBackdrops = document.querySelectorAll('.modal-backdrop');
                existingBackdrops.forEach(backdrop => backdrop.remove());

                // Remove the 'modal-open' class from the body to reset
                document.body.classList.remove('modal-open');

                // Show the modal
                const modalElement = document.getElementById('kt_modal_view_training');
                const modal = new bootstrap.Modal(modalElement);
                modal.show();

                // Add an event listener to clean up backdrops when the modal is hidden
                modalElement.addEventListener('hidden.bs.modal', function () {
                    const backdrops = document.querySelectorAll('.modal-backdrop');
                    backdrops.forEach(backdrop => backdrop.remove());
                    document.body.classList.remove('modal-open');
                });
            }

            // Check if the modal instance is valid
            if (typeof bootstrapModal !== 'undefined' && bootstrapModal) {
                bootstrapModal.hide(); // Hide the modal using Bootstrap's built-in method
            }
        });


    };
    document.getElementById('kt_modal_view_training').addEventListener('hidden.bs.modal', function () {
        document.body.style.overflow = 'auto'; // Restore scrolling
    });

    if (calendarEl) {
        // Initialize the calendar
        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay',
            },
            events: [], // Initially empty
            eventMouseEnter: function (info) {
                if (currentHoveredEvent === info.event.id) {
                    // If already hovered, do nothing
                    return;
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
                    assistant: info.event.extendedProps.assistant
                };


                // Track the currently hovered event
                currentHoveredEvent = info.event.id;

                // Initialize popover
                initPopovers(info.el, eventData);
            },
            eventMouseLeave: function (info) {
                // Do nothing on mouse leave for the current event
                // Popover will only close when another event is hovered
            },
        });
        calendar.render();

        // Fetch events dynamically using jQuery AJAX
        $.ajax({
            url: '/calendar/api/get/training', // Replace with your actual endpoint
            method: 'GET',
            dataType: 'json',
            beforeSend: function () {
                $('#calendar').addClass('blur-effect');
                loaderWrapper.style.display = 'flex';
            },
            success: function (response) {
                if (response.success) {
                    // Add events to the calendar
                    response.data.forEach(function (training) {
                        if (training.schedule) {
                            // Extract the schedule details
                            var fromDateTime = `${training.schedule.from_date}T${training.schedule.from_time}`;
                            var toDateTime = `${training.schedule.to_date}T${training.schedule.to_time}`;

                            calendar.addEvent({
                                id: training.id, // Add a unique ID to the event
                                title: training.course + ' (' + training.company + ')',
                                start: fromDateTime,
                                end: toDateTime,
                                backgroundColor: training.mode === 'virtual' ? '#3788d8' : '#34c38f',
                            });
                        }
                    });
                    loaderWrapper.classList.add('d-none');
                    $('#calendar').removeClass('blur-effect');
                } else {
                    console.error('Failed to load trainings:', response.message);
                    loaderWrapper.style.display = 'none';
                }
            },
            error: function (xhr, status, error) {
                console.error('Error fetching trainings:', error);
                loaderWrapper.style.display = 'none';
            },
            complete: function () {
                loaderWrapper.style.display = 'none';
                $('#calendar').removeClass('blur-effect');
            },
        });
    }
});
