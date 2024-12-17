document.addEventListener('DOMContentLoaded', function () {
    var calendarEl = document.getElementById('calendar');

    if (calendarEl) {
        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay'
            },
            events: [
                { title: 'Event 1', start: '2024-06-20' },
                { title: 'Event 2', start: '2024-06-25' }
            ],
            dateClick: function (info) {
                alert('Date clicked: ' + info.dateStr);
            }
        });

        calendar.render();
    }
});
