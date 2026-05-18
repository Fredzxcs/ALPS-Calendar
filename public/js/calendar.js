var csrfToken = $('meta[name="csrf-token"]').attr('content');
document.addEventListener('DOMContentLoaded', function () {
    var calendarEl = document.getElementById('calendar');
    var loaderWrapper = document.getElementById('loader-wrapper');
    var popoverState = false; // Track if a popover is active
    var popover = null; // Reference to the active popover
    var currentHoveredEvent = null; // Track the currently hovered event
    var calendar;

    const clampColorChannel = (value) => Math.max(0, Math.min(255, Math.round(value)));

    const parseHexColor = (hex) => {
        if (!hex || typeof hex !== 'string') {
            return null;
        }

        let normalized = hex.trim().replace('#', '');
        if (normalized.length === 3) {
            normalized = normalized.split('').map((char) => char + char).join('');
        }

        if (!/^[0-9a-fA-F]{6}$/.test(normalized)) {
            return null;
        }

        return {
            r: parseInt(normalized.slice(0, 2), 16),
            g: parseInt(normalized.slice(2, 4), 16),
            b: parseInt(normalized.slice(4, 6), 16),
        };
    };

    const parseRgbColor = (rgbColor) => {
        if (!rgbColor || typeof rgbColor !== 'string') {
            return null;
        }

        const match = rgbColor.match(/rgba?\(([^)]+)\)/i);
        if (!match || !match[1]) {
            return null;
        }

        const channels = match[1].split(',').slice(0, 3).map((part) => Number(part.trim()));
        if (channels.some((channel) => Number.isNaN(channel))) {
            return null;
        }

        return {
            r: clampColorChannel(channels[0]),
            g: clampColorChannel(channels[1]),
            b: clampColorChannel(channels[2]),
        };
    };

    const parseColor = (color) => parseHexColor(color) || parseRgbColor(color);

    const lightenColor = (color, amount) => ({
        r: clampColorChannel(color.r + (255 - color.r) * amount),
        g: clampColorChannel(color.g + (255 - color.g) * amount),
        b: clampColorChannel(color.b + (255 - color.b) * amount),
    });

    const darkenColor = (color, amount) => ({
        r: clampColorChannel(color.r * (1 - amount)),
        g: clampColorChannel(color.g * (1 - amount)),
        b: clampColorChannel(color.b * (1 - amount)),
    });

    const toRgbString = (color) => `rgb(${color.r}, ${color.g}, ${color.b})`;

    const getAccessibleEventTextColor = (color) => {
        const brightness = ((0.299 * color.r) + (0.587 * color.g) + (0.114 * color.b)) / 255;
        return brightness > 0.62 ? '#1E4A8A' : '#FFFFFF';
    };

    const applyEventPalette = (eventEl, color) => {
        const colorTop = lightenColor(color, 0.22);
        const colorBottom = darkenColor(color, 0.12);
        const textColor = getAccessibleEventTextColor(color);

        eventEl.style.setProperty('--event-color-top', toRgbString(colorTop));
        eventEl.style.setProperty('--event-color-bottom', toRgbString(colorBottom));
        eventEl.style.setProperty('--event-text-color', textColor);
        eventEl.style.setProperty('--event-glow-color', `rgba(${color.r}, ${color.g}, ${color.b}, 0.47)`);
        // Base RGB for use in CSS backgrounds
        eventEl.style.setProperty('--event-base-rgb', `${color.r}, ${color.g}, ${color.b}`);

        // Choose an overlay color slightly darker by default
        let overlayColorObj = darkenColor(color, 0.14);
        // If base color is very dark, use a slightly lighter overlay instead
        const baseBrightness = ((0.299 * color.r) + (0.587 * color.g) + (0.114 * color.b)) / 255;
        if (baseBrightness < 0.18) {
            overlayColorObj = lightenColor(color, 0.14);
        }
        eventEl.style.setProperty('--event-overlay-rgb', `${overlayColorObj.r}, ${overlayColorObj.g}, ${overlayColorObj.b}`);
        eventEl.style.setProperty('--event-overlay-alpha', '0.28');
        eventEl.style.color = textColor;
    };

    const addTrainingEvents = (trainings) => {
        trainings.forEach(function (training) {
            if (training.schedule) {
                const fromDateTime = `${training.schedule.from_date}T${training.schedule.from_time}`;
                const toDateTime = `${training.schedule.to_date}T${training.schedule.to_time}`;

                calendar.addEvent({
                    id: training.id,
                    title: `${training.course.course_code} - ${training.company ? training.company.company_name : 'Public Course'} - ${training.facilitator ? training.facilitator.name.split(' ')[0] : 'No Facilitator Yet'}`,
                    start: fromDateTime,
                    end: toDateTime,
                    allDay: false,
                    backgroundColor: training.facilitator ? training.facilitator.color : '#808080',
                    extendedProps: {
                        facilitator: training.facilitator || 'N/A',
                        assistant: training.assistant_names || training.assistant || 'No Assistant Yet',
                        assistant_names: training.assistant_names || training.assistant || 'No Assistant Yet',
                        modeType: training.mode,
                        account: training.account,
                        course: training.course?.course_name || 'N/A',
                        company: training.company ? training.company.company_name : 'No Company (Public Course)',
                        location: training.location,
                        platform: training.platform,
                        conference_link: training.conference_link,
                        need_transportation: training.need_transportation,
                        outbound_pickup_time: training.outbound_pickup_time,
                        outbound_contact_number: training.outbound_contact_number,
                        outbound_pickup_location: training.outbound_pickup_location,
                        outbound_dropoff_location: training.outbound_dropoff_location,
                        return_trip_needed: training.return_trip_needed,
                        return_pickup_time: training.return_pickup_time,
                        return_contact_number: training.return_contact_number,
                        return_pickup_location: training.return_pickup_location,
                        return_dropoff_location: training.return_dropoff_location,
                        notify_coordinator: training.notify_coordinator,
                        coordinator_to_notify: training.coordinator_names || training.coordinator_to_notify || 'No Driver Coordinator Yet',
                    },
                });
            }
        });
    };

    const addUnavailabilityEvents = (unavailabilities) => {
        unavailabilities.forEach(function (unavailability) {
            if (unavailability.from_date && unavailability.to_date) {
                // FullCalendar treats all-day events differently: use date-only strings
                // and make `end` exclusive by adding one day so the range includes the
                // provided `to_date`.
                const startDate = moment(unavailability.from_date).format('YYYY-MM-DD');
                const endDateExclusive = moment(unavailability.to_date).add(1, 'day').format('YYYY-MM-DD');

                calendar.addEvent({
                    id: unavailability.id,
                    user_id: unavailability.user.id,
                    title: unavailability.reason || 'Unavailable',
                    start: startDate,
                    end: endDateExclusive,
                    allDay: true,
                    backgroundColor: unavailability.user.color || '#FF5E5E',
                    borderColor: unavailability.user.color || '#FF5E5E',
                    classNames: ['unavailability-event'],
                    extendedProps: {
                        eventType: 'unavailability',
                        user: unavailability.user ? unavailability.user.name : 'Unknown User',
                        reason: unavailability.reason || 'Unavailable'
                    },
                });
            }
        });
    };

    const showCalendarLoader = () => {
        $('#calendar').addClass('blur-effect');
        loaderWrapper.classList.remove('d-none');
        loaderWrapper.style.display = 'flex';
    };

    const hideCalendarLoader = () => {
        loaderWrapper.classList.add('d-none');
        loaderWrapper.style.display = '';
        $('#calendar').removeClass('blur-effect');
    };

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
            <div class="fw-bolder mb-2">${data.eventName || "Public Course"}</div>
            <div class="fs-7 mb-2">${modeBadges.join(' ')}</div>
            <div class="fs-7"><span class="fw-bold">Start:</span> ${startDate}</div>
            <div class="fs-7 mb-2"><span class="fw-bold">End:</span> ${endDate}</div>
            <div class="fs-7"><span class="fw-bold">Facilitator:</span> ${(data.facilitator || data.facilitator.name) || 'No Facilitator Yet'}</div>
                <div class="fs-7 mb-4"><span class="fw-bold">Assistant:</span> ${data.assistant_names || data.assistant || 'No Assistant Yet'}</div>
            <a id="kt_calendar_event_view" type="button" class="btn btn-sm btn-primary btn-blue mt-2" data-event-id="${data.id}" data-bs-dismiss="modal">
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

        //Delete training button
        document.querySelectorAll('.deleteBtn').forEach(button => {
            button.addEventListener('click', (event) => {
                event.preventDefault();
                event.stopPropagation();

                // Avoid keeping focus inside hidden modal/popover ancestors.
                if (document.activeElement && typeof document.activeElement.blur === 'function') {
                    document.activeElement.blur();
                }

                const modalElement = document.getElementById('kt_modal_view_training');
                if (modalElement && modalElement.classList.contains('show')) {
                    const modalInstance = bootstrap.Modal.getInstance(modalElement);
                    if (modalInstance) {
                        modalInstance.hide();
                    }
                }

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

                            // Show Swal loader
                            Swal.fire({
                                title: 'Deleting Training...',
                                text: 'Please wait while we process your request.',
                                allowOutsideClick: false,
                                allowEscapeKey: false,
                                didOpen: () => {
                                    Swal.showLoading();
                                }
                            });

                            $.ajax({
                                url: '/calendar/delete_training/'+data.id,
                                type: 'DELETE',
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                },
                                success: function (response) {
                                    if (response) {
                                        Swal.close(); // Close loader
                                        Swal.fire({
                                            title: 'Success!',
                                            text: 'The training has been deleted.',
                                            icon: 'success',
                                            confirmButtonText: 'OK'
                                        }).then((result) => {
                                            if (result.isConfirmed) {
                                                window.location.href = '/calendar';
                                            }
                                        });
                                    } else {
                                        Swal.close(); // Close loader
                                        Swal.fire({
                                            title: 'Wait!',
                                            text: response.message,
                                            icon: 'warning',
                                            confirmButtonText: 'OK'
                                        }).then(() => {
                                            location.reload();
                                        });
                                    }
                                },
                                error: function (xhr, status, error) {
                                    Swal.close(); // Close loader
                                    console.log('AJAX Error Details:', xhr.responseText);
                                    Swal.fire({
                                        title: 'Error!',
                                        text: 'There was an error deleting the training.',
                                        icon: 'error',
                                        confirmButtonText: 'OK'
                                    });
                                }
                            });
                    }
                });
            });
        });


        // Attach the modal event listener after popover HTML is inserted
        document.getElementById('kt_calendar_event_view').addEventListener('click', function (e) {
            e.preventDefault(); // Prevent default link action

            const modalElement = document.getElementById('kt_modal_view_training');
            const modal = new bootstrap.Modal(modalElement);
            modal.show();
        });
    };

    // popovers for Unavailability
    const initUnavailabilityPopover = (element, data) => {
        hidePopovers(); // Hide any active popovers

        console.log("Unavailability Data:", data);

        const popoverHtml = `
            <div class="fw-bolder mb-2">Unavailability - ${data.user || 'Unknown User'}</div>
            <div class="fs-7"><span class="fw-bold">Start:</span> ${moment(data.startDate).format('MMM DD, YYYY')}</div>
            <div class="fs-7 mb-2"><span class="fw-bold">End:</span> ${moment(data.endDate).format('MMM DD, YYYY')}</div>
            <div class="fs-7"><span class="fw-bold">Reason:</span> ${data.reason || 'No reason provided'}</div>
            <a id="kt_calendar_unavailability_view" type="button" class="btn btn-sm btn-primary btn-blue mt-2" data-event-id="${data.id}">
                VIEW MORE
            </a>
        `;
        var options = {
            container: 'body',
            trigger: 'manual',
            boundary: 'window',
            placement: 'auto',
            dismiss: true,
            html: true,
            title: 'Unavailability Summary',
            content: popoverHtml,

        };
        // Initialize popover
        popover = KTApp.initBootstrapPopover(element, options);
        popover.show();
        popoverState = true;



        // Attach the modal event listener after popover HTML is inserted
        document.getElementById('kt_calendar_unavailability_view').addEventListener('click', function (e) {
            e.preventDefault();
            const modalElement = document.getElementById('kt_modal_view_unavailability');
            const modal = new bootstrap.Modal(modalElement);

            let date_unavailable = ` ${moment(data.startDate).format('MMM DD, YYYY')} to ${moment(data.endDate).format('MMM DD, YYYY')} `

            $('h1[id="modal-user"]').text(data.user);
            $('p[id="modal-date-unavailable"]').text(date_unavailable);
            $('p[id="modal-purpose"]').text(data.reason);

            if (data.user_id !== authenticated_user) {
                if (!(authenticated_usertype == "admin")) {
                    $('.deleteBtnUnavailability').addClass('d-none');
            }
        }
            //Delete unavailability button
            document.querySelectorAll('.deleteBtnUnavailability').forEach(button => {
                button.addEventListener('click', (event) => {
                    event.preventDefault(); // Prevent default action (e.g., form submission)

                    Swal.fire({
                        title: 'Are you sure?',
                        text: "You are about to delete this unavailability.",
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

                            $.ajax({
                                url: '/calendar/delete_unavailability/'+data.id,
                                type: 'DELETE',
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                },
                                success: function (response) {
                                    if (response) {
                                        Swal.fire({
                                            title: 'Success!',
                                            text: 'The unavailability has been deleted.',
                                            icon: 'success',
                                            confirmButtonText: 'OK'
                                        }).then((result) => {
                                            if (result.isConfirmed) {
                                                window.location.href = '/calendar';
                                            }
                                        });
                                    } else {
                                        Swal.fire({
                                            title: 'Wait!',
                                            text: response.message,
                                            icon: 'warning',
                                            confirmButtonText: 'OK'
                                        }).then(() => {
                                            location.reload();
                                        });
                                    }
                                },
                                error: function (xhr, status, error) {
                                    console.log('AJAX Error Details:', xhr.responseText);
                                    Swal.fire({
                                        title: 'Error!',
                                        text: 'There was an error deleting the unavailability.',
                                        icon: 'error',
                                        confirmButtonText: 'OK'
                                    });
                                }
                            });
                        }
                    });
                });
            });

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
        const fetchEvents = (backendRequestRoute) => $.ajax({
            url: backendRequestRoute,
            method: 'GET',
            dataType: 'json',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
        });

        clearCalendarEvents(calendar);
        showCalendarLoader();

        if (route === 'all') {
            let completedRequests = 0;

            const finishLoading = () => {
                completedRequests += 1;

                if (completedRequests === 2) {
                    bindEventListeners();
                    hideCalendarLoader();
                }
            };

            fetchEvents('/calendar/api/get/training')
                .done(function (response) {
                    console.log(response);

                    if (response.success) {
                        addTrainingEvents(response.data);
                    } else {
                        console.error('Failed to load trainings:', response.message);
                    }
                })
                .fail(function (xhr, status, error) {
                    console.error('Error fetching trainings:', error);
                })
                .always(finishLoading);

            fetchEvents('/calendar/api/get/unavailability')
                .done(function (response) {
                    console.log(response);

                    if (response.success) {
                        addUnavailabilityEvents(response.data);
                    } else {
                        console.error('Failed to load unavailabilities:', response.message);
                    }
                })
                .fail(function (xhr, status, error) {
                    console.error('Error fetching unavailabilities:', error);
                })
                .always(finishLoading);

            return;
        }

        let backend_request_route;

        if (route == 'trainings') {
            backend_request_route = '/calendar/api/get/training';
        } else if (route == 'unavailability') {
            backend_request_route = '/calendar/api/get/unavailability';
        }

        fetchEvents(backend_request_route)
            .done(function (response) {
                console.log(response);

                if (response.success) {
                    if (route == 'trainings') {
                        addTrainingEvents(response.data);
                    } else if (route == 'unavailability') {
                        addUnavailabilityEvents(response.data);
                    }

                    bindEventListeners();
                } else {
                    console.error('Failed to load events:', response.message);
                }
            })
            .fail(function (xhr, status, error) {
                console.error('Error fetching events:', error);
            })
            .always(function () {
                hideCalendarLoader();
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

            // Check if unavailability event
            if (info.event.extendedProps.user) {
                const unavailabilityData = {
                    id: info.event.id,
                    user: info.event.extendedProps.user || 'Unknown User',
                    user_id: info.event.extendedProps.user_id,
                    reason: info.event.title || 'Unavailable',
                    startDate: info.event.start,
                    endDate: info.event.end,
                };
                initUnavailabilityPopover(info.el, unavailabilityData);
            }

            // check if training
            if (info.event.extendedProps.modeType) {
                const eventData = {
                    eventName: info.event.title || 'No Title',
                    startDate: info.event.start || null,
                    endDate: info.event.end || null,
                    allDay: info.event.allDay || false,
                    course: info.event.extendedProps.course,
                    modeType: info.event.extendedProps.modeType || 'N/A',
                    company: info.event.extendedProps.company || 'N/A',
                    facilitator: info.event.extendedProps.facilitator?.name || 'No Facilitator Yet',
                    assistant: info.event.extendedProps.assistant || 'No Assistant Yet',
                    account: info.event.extendedProps.account || { account_email: 'N/A' },
                    location: info.event.extendedProps.location || 'N/A',
                    platform: info.event.extendedProps.platform || 'N/A',
                    conference_link: info.event.extendedProps.conference_link || 'N/A',
                    need_transportation: info.event.extendedProps.need_transportation,
                    outbound_pickup_time: info.event.extendedProps.outbound_pickup_time || 'N/A',
                    outbound_contact_number: info.event.extendedProps.outbound_contact_number || 'N/A',
                    outbound_pickup_location: info.event.extendedProps.outbound_pickup_location || 'N/A',
                    outbound_dropoff_location: info.event.extendedProps.outbound_dropoff_location || 'N/A',
                    return_trip_needed: info.event.extendedProps.return_trip_needed,
                    return_pickup_time: info.event.extendedProps.return_pickup_time || 'N/A',
                    return_contact_number: info.event.extendedProps.return_contact_number || 'N/A',
                    return_pickup_location: info.event.extendedProps.return_pickup_location || 'N/A',
                    return_dropoff_location: info.event.extendedProps.return_dropoff_location || 'N/A',
                    notify_coordinator: info.event.extendedProps.notify_coordinator,
                    coordinator_to_notify: info.event.extendedProps.coordinator_to_notify || 'N/A',
                    id: info.event.id || '',
                };
                initPopovers(info.el, eventData);

                const $modalElement = $('#kt_modal_view_training');
                $modalElement.find('#modal-title').text('VIEW TRAINING');

                const baseUrl = $('#edit-training-link').data('base-url');
                const editUrl = `${baseUrl}${eventData.id}`;
                $('#edit-training-link').attr('href', editUrl);

                const mode = eventData.modeType;
                $modalElement.find('#modal-mode-of-training').text(mode.toUpperCase());

                $modalElement.find('#modal-location').text(eventData.location);

                const accountEmail = eventData.account.account_email;
                console.log(accountEmail);
                $modalElement.find('#modal-credentials').text(accountEmail || 'N/A');

                const inPerson = $modalElement.find('#modal-in-person').text().trim().toLowerCase(); // Get in-person status
                const accountPassword = eventData.account?.account_password || 'N/A';

                // Ensure Hosting Account section is only shown for Virtual or Public (Not In-Person)
                if (mode === "face-to-face" || (mode === "public-course" && inPerson === "yes")) {
                    $modalElement.find('#hosting-account-row').addClass('d-none');
                    $modalElement.find('#password-container').addClass('d-none');
                } else {
                    $modalElement.find('#hosting-account-row').removeClass('d-none');
                    $modalElement.find('#modal-credentials').text(accountEmail);

                    if (accountEmail !== "N/A" && accountPassword !== "N/A") {
                        $modalElement.find('#password-container').removeClass('d-none');
                        $modalElement.find('#modal-password').text(accountPassword);
                    } else {
                        $modalElement.find('#password-container').addClass('d-none');
                    }
                }


                let inPersonText = mode === 'virtual' ? 'No' : 'Yes';
                if (mode === 'public-course' && accountEmail !== 'N/A' || mode === "virtual") {
                    inPersonText = 'No';
                    $modalElement.find('#modal-password').html(eventData.account.account_password);
                    $modalElement.find('#password-container').removeClass('d-none'); // Ensure it's visible
                }
                else if (mode === 'public-course' && accountEmail === 'N/A' || mode === "face-to-face")
                {
                    $modalElement.find('#password-container').addClass('d-none'); // Remove container
                }

                console.log(eventData.account.account_password);

                $modalElement.find('#modal-in-person').text(inPersonText);

                $modalElement.find('#modal-company').text(eventData.company);
                $modalElement.find('#modal-course').text(eventData.course);
                $modalElement.find('#modal-facilitator').text(eventData.facilitator);
                $modalElement.find('#modal-assistant').text(eventData.assistant_names || eventData.assistant || 'No Assistant Yet');
                $modalElement.find('#modal-platform').text(eventData.platform);
                $modalElement.find('#modal-conference-link').text(eventData.conference_link);
                $modalElement.find('#modal-transportation-needed').text(eventData.need_transportation ? 'Yes' : 'No');
                $modalElement.find('#modal-outbound-pickup-time').text(eventData.outbound_pickup_time);
                $modalElement.find('#modal-outbound-contact-number').text(eventData.outbound_contact_number);
                $modalElement.find('#modal-outbound-pickup-location').text(eventData.outbound_pickup_location);
                $modalElement.find('#modal-outbound-dropoff-location').text(eventData.outbound_dropoff_location);
                $modalElement.find('#modal-return-trip-needed').text(eventData.return_trip_needed ? 'Yes' : 'No');
                $modalElement.find('#modal-return-pickup-time').text(eventData.return_pickup_time);
                $modalElement.find('#modal-return-contact-number').text(eventData.return_contact_number);
                $modalElement.find('#modal-return-pickup-location').text(eventData.return_pickup_location);
                $modalElement.find('#modal-return-dropoff-location').text(eventData.return_dropoff_location);
                $modalElement.find('#modal-notify-coordinator').text(eventData.notify_coordinator ? 'Yes' : 'No');
                $modalElement.find('#modal-coordinator-to-notify').text(eventData.coordinator_to_notify);

                const formattedStartDate = eventData.startDate ? moment(eventData.startDate).format('MMM DD, YYYY') : 'N/A';
                const formattedEndDate = eventData.endDate ? moment(eventData.endDate).format('MMM DD, YYYY') : 'N/A';
                $modalElement.find('#modal-date').text(`${formattedStartDate} to ${formattedEndDate}`);

                const formattedStartTime = eventData.startDate ? moment(eventData.startDate).format('h:mm A') : 'N/A';
                const formattedEndTime = eventData.endDate ? moment(eventData.endDate).format('h:mm A') : 'N/A';
                $modalElement.find('#modal-time').text(`${formattedStartTime} to ${formattedEndTime}`);

                initPopovers(info.el, eventData);
            }
        });
        calendar.setOption('eventMouseLeave', function () {
            setTimeout(() => {
                hidePopovers();
            }, 3000); // Small delay before hiding popovers
        });
    };

    // const getHolidays = () => {
    //     $.ajax({
    //         url: '/calendar/api/get/holidays',
    //         method: 'GET',
    //         dataType: 'json',
    //         headers: {
    //             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    //         },
    //         beforeSend: function () {
    //             $('#calendar').addClass('blur-effect');
    //             loaderWrapper.style.display = 'flex';
    //         },
    //         success: function (response) {
    //             console.log(response);

    //             if (response.response && response.response.holidays) {
    //                 // Clear all existing background events
    //                 calendar.getEvents().forEach(event => {
    //                     if (event.extendedProps.isHoliday) {
    //                         event.remove();
    //                     }
    //                 });

    //                 // Add holidays as background events
    //                 response.response.holidays.forEach(function (holiday) {
    //                     calendar.addEvent({
    //                         title: holiday.name,
    //                         start: holiday.date.iso,
    //                         display: 'background',         // Keeps the event as a background highlight
    //                         backgroundColor: '#FFCCCC',    // Light red background
    //                         borderColor: '#FFCCCC',
    //                         textColor: '#FF0000',          // Bright red text
    //                         allDay: true,
    //                         extendedProps: {
    //                             isHoliday: true
    //                         }
    //                     });
    //                 });
    //             }

    //             // Rebind other event listeners if needed
    //             bindEventListeners();

    //             loaderWrapper.classList.add('d-none');
    //             $('#calendar').removeClass('blur-effect');
    //         },
    //         error: function (xhr, status, error) {
    //             console.error('Error fetching holidays:', error);
    //             loaderWrapper.classList.add('d-none');
    //         },
    //         complete: function () {
    //             loaderWrapper.classList.add('d-none');
    //             $('#calendar').removeClass('blur-effect');
    //         },
    //     });
    // };

        // Initialize the calendar and its initial population
        if (calendarEl) {
            let initial = 'all';

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

                eventDidMount: function(info) {
                    // Check if the event is a holiday (using extendedProps)
                    if (info.event.extendedProps.isHoliday) {
                        info.el.style.color = '#FF0000';  // Bright red
                        info.el.style.fontWeight = 'bold';
                        return;
                    }

                    const eventEl = info.el;
                    const eventColor = parseColor(info.event.backgroundColor || info.event.borderColor || '#808080') || parseColor('#808080');
                    eventEl.classList.add('alps-pop-event');
                    applyEventPalette(eventEl, eventColor);

                    // Remove FullCalendar's default day-grid dot so training events
                    // keep a consistent card-like appearance across view switches.
                    const dot = eventEl.querySelector('.fc-daygrid-event-dot');
                    if (dot) {
                        dot.remove();
                    }

                    eventEl.addEventListener('mousedown', function () {
                        eventEl.classList.add('is-pressed');
                    });

                    eventEl.addEventListener('mouseup', function () {
                        eventEl.classList.remove('is-pressed');
                    });

                    eventEl.addEventListener('mouseleave', function () {
                        eventEl.classList.remove('is-pressed');
                    });

                    const start = info.event.start;
                    const end = info.event.end;

                    if (!end || start.toDateString() === end.toDateString()) {
                        // Add missing class to make it behave like a date range
                        eventEl.classList.add('fc-h-event');

                        // Ensure full-width styling
                        eventEl.style.width = '100%';
                    }
                },
                eventClick: function(info) {
                    info.jsEvent.preventDefault();
                    info.el.classList.add('is-pressed');
                    setTimeout(function () {
                        info.el.classList.remove('is-pressed');
                    }, 120);

                    if (popover) {
                        try {
                            popover.dispose();
                        } catch (error) {
                            console.error("Error disposing popover:", error);
                        }
                        popover = null;
                        popoverState = false;
                    }

                    const eventData = info.event.extendedProps;


                    // 1. Check for Holiday Event
                    if (eventData.isHoliday) {

                        const holidayDate = info.event.start ? moment(info.event.start).format('MMMM DD, YYYY') : 'N/A';

                        Swal.fire({
                            icon: 'info',
                            title: info.event.title,
                            html: `<strong>Date:</strong> ${holidayDate}`,
                            confirmButtonText: 'Close',
                            customClass: {
                                confirmButton: "btn btn-light",
                            }
                        });
                        return;
                    }

                    //  2. Check for Unavailability Event
                    if (eventData.eventType === 'unavailability' || eventData.reason) {

                        const $modalElement = $('#kt_modal_view_unavailability');

                        $modalElement.find('#modal-title').text(info.event.title || 'Unavailability');
                        $modalElement.find('#modal-user').text(eventData.user || 'Unknown User');


                        const formattedStartDate = info.event.start ? moment(info.event.start).format('MMM DD, YYYY') : 'N/A';
                        const formattedEndDate = info.event.end ? moment(info.event.end).format('MMM DD, YYYY') : 'N/A';

                        const dateUnavailable = `${formattedStartDate} to ${formattedEndDate}`;

                        $modalElement.find('#modal-date-unavailable').text(dateUnavailable);
                        $modalElement.find('#modal-reason').text(eventData.reason || 'No reason provided');

                        const modal = new bootstrap.Modal(document.getElementById('kt_modal_view_unavailability'));
                        modal.show();

                        return;
                    }

                    // 3. Default: Handle as Training Event
                    const $modalElement = $('#kt_modal_view_training');

                    $modalElement.find('#modal-title').text(info.event.title || 'No Title');
                    $modalElement.find('#modal-company').text(eventData.company || 'N/A');
                    $modalElement.find('#modal-facilitator').text(eventData.facilitator?.name || 'No Facilitator Yet');
                    $modalElement.find('#modal-assistant').text(eventData.assistant_names || eventData.assistant || 'No Assistant Yet');

                    const formattedStartDate = info.event.start ? moment(info.event.start).format('MMM DD, YYYY') : 'N/A';
                    const formattedEndDate = info.event.end ? moment(info.event.end).format('MMM DD, YYYY') : 'N/A';
                    $modalElement.find('#modal-date').text(`${formattedStartDate} to ${formattedEndDate}`);

                    const formattedStartTime = info.event.start ? moment(info.event.start).format('h:mm A') : 'N/A';
                    const formattedEndTime = info.event.end ? moment(info.event.end).format('h:mm A') : 'N/A';
                    $modalElement.find('#modal-time').text(`${formattedStartTime} to ${formattedEndTime}`);

                    const modal = new bootstrap.Modal(document.getElementById('kt_modal_view_training'));
                    modal.show();
                }
            });

            calendar.setOption('eventMouseLeave', function () {
                setTimeout(() => {
                    hidePopovers();
                }, 4000); // Small delay before hiding popovers
            });

            calendar.render();

            // Load initial data
            getPopulation(initial);
            // getHolidays();

            // Bind filter change to update events
            $('#applyFilter').click(function (e) {
                e.preventDefault();

                let filter = $('#filters').find('option:selected').val();

                hidePopovers();

                getPopulation(filter);
                // getHolidays();
            });
        }
});

//Password reveal in view modal
$(document).ready(function () {
    $(".password-display").click(function () {
        var actualPassword = $(this).next(".password-actual");
        $(this).addClass("d-none");
        actualPassword.removeClass("d-none");
    });

    $(".password-actual").click(function () {
        var passwordDisplay = $(this).prev(".password-display");
        $(this).addClass("d-none");
        passwordDisplay.removeClass("d-none");
    });
});
