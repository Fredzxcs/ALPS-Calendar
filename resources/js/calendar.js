document.addEventListener('DOMContentLoaded', function () {
    var calendarEl = document.getElementById('calendar');
    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay',
        },
        events: [
            {
                title: 'Meeting',
                start: '2024-12-14T10:30:00',
                end: '2024-12-14T12:30:00',
            },
            {
                title: 'Conference',
                start: '2024-12-18',
                end: '2024-12-20',
            },
        ],
    });
    calendar.render();
});
