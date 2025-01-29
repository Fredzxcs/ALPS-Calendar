$(document).ready(function () {
    // Reference to FullCalendar element
    var calendarEl = document.getElementById('calendar');

    if (!window.isCalendarInitialized) {
        window.isCalendarInitialized = true;
    
        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay',
            },
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
            eventClick: function (info) {
                $('#modal-date').text(info.event.start.toISOString().split('T')[0]);
                $('#modal-purpose').text(info.event.extendedProps.purpose || 'No Purpose Provided');
                var modal = new bootstrap.Modal(document.getElementById('kt_modal_view_unavailability'));
                modal.show();
            },
        });
        calendar.render();
    }
    
});
