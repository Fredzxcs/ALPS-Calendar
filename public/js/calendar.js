document.addEventListener('DOMContentLoaded', function () {
    var calendarEl = document.getElementById('calendar');
    var loaderWrapper = document.getElementById('loader-wrapper');

    if (calendarEl) {
        // Initialize the calendar
        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay'
            },
            events: [], // Initially empty
            dateClick: function (info) {
                // Action for date click
            }
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
            }
        });
    }
});
