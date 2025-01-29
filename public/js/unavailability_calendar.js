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
            events: [
                {
                    title: 'Unavailable - Team Building',
                    start: '2025-01-07T09:00:00',
                    end: '2025-01-07T17:00:00',
                    extendedProps: {
                        purpose: 'Team Building',
                    },
                    color: '#ff0000',
                },
            ],

            // When user clicks an event, determine if it's an unavailability event
            eventClick: function (info) {
                if (info.event.title.includes("Unavailable")) {
                    // Extract details
                    let startDate = moment(info.event.start).format('MMMM DD, YYYY');
                    let purpose = info.event.extendedProps.purpose || 'No Purpose Provided';

                    // Populate the modal fields
                    document.getElementById("modal-date").textContent = startDate;
                    document.getElementById("modal-purpose").textContent = purpose;

                    // Show the modal
                    var modalElement = new bootstrap.Modal(document.getElementById('kt_modal_view_unavailability'));
                    modalElement.show();
                }
            }
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
