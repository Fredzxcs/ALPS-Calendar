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
                {
                    title: 'V - Fujifilm - Effective Project Management',
                    start: '2024-12-20T09:00:00',
                    end: '2024-12-23T17:00:00',
                    backgroundColor: '#FF5733' // Orange color for Virtual Fujifilm event
                },
                {
                    title: 'F - DOST - Data Science for Beginners',
                    start: '2024-12-21T08:30:00',
                    end: '2024-12-22T16:30:00',
                    backgroundColor: '#33FF57' // Green color for Face-to-Face DOST event
                },
                {
                    title: 'PC - PhilHealth - Health Policy Management',
                    start: '2024-12-22T10:00:00',
                    end: '2024-12-24T15:00:00',
                    backgroundColor: '#3357FF' // Blue color for Public Course PhilHealth event
                },
                {
                    title: 'V - Hyundai - Leadership Skills Workshop',
                    start: '2024-12-23T14:00:00',
                    end: '2024-12-25T18:00:00',
                    backgroundColor: '#FF33A6' // Pink color for Virtual Hyundai event
                },
                {
                    title: 'F - Fujifilm - Agile Project Management',
                    start: '2024-12-24T08:00:00',
                    end: '2024-12-25T12:00:00',
                    backgroundColor: '#FFD700' // Yellow color for Face-to-Face Fujifilm event
                },
                {
                    title: 'PC - DOST - Advanced Robotics',
                    start: '2024-12-25T13:30:00',
                    end: '2024-12-27T17:30:00',
                    backgroundColor: '#800080' // Purple color for Public Course DOST event
                },
                // Events from December 1-13
                {
                    title: 'V - DOST - Introduction to AI',
                    start: '2024-12-01T10:00:00',
                    end: '2024-12-03T14:00:00',
                    backgroundColor: '#FF8C00' // Dark orange color for Virtual DOST event
                },
                {
                    title: 'F - Fujifilm - Digital Marketing Strategies',
                    start: '2024-12-04T09:00:00',
                    end: '2024-12-06T16:00:00',
                    backgroundColor: '#7CFC00' // Lawn green color for Face-to-Face Fujifilm event
                },
                {
                    title: 'PC - Hyundai - Project Management Fundamentals',
                    start: '2024-12-06T11:00:00',
                    end: '2024-12-08T15:00:00',
                    backgroundColor: '#4682B4' // Steel blue color for Public Course Hyundai event
                },
                {
                    title: 'V - PhilHealth - Healthcare System Analysis',
                    start: '2024-12-08T13:00:00',
                    end: '2024-12-10T17:00:00',
                    backgroundColor: '#DA70D6' // Orchid color for Virtual PhilHealth event
                },
                {
                    title: 'F - DOST - Cloud Computing Essentials',
                    start: '2024-12-10T08:00:00',
                    end: '2024-12-12T12:00:00',
                    backgroundColor: '#32CD32' // Lime green color for Face-to-Face DOST event
                },
                {
                    title: 'PC - Fujifilm - Marketing in the Digital Age',
                    start: '2024-12-12T14:00:00',
                    end: '2024-12-14T18:00:00',
                    backgroundColor: '#8A2BE2' // Blue violet color for Public Course Fujifilm event
                }
            ],
            dateClick: function (info) {
                // alert('Date clicked: ' + info.dateStr);
            }
        });
        calendar.render();
    }
});
