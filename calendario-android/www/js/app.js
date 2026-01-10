class CalendarioApp {
    constructor() {
        this.eventManager = null;
        this.birthdayManager = null;
        this.calendarInstance = null;
        this.isMobileDevice = false;
    }

    init() {
        document.addEventListener('deviceready', () => {
            this.onDeviceReady();
        }, false);
    }

    onDeviceReady() {
        this.isMobileDevice = /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) || window.innerWidth <= 768;
        
        window.databaseManager.init()
            .then((db) => {
                this.eventManager = new EventManager(db);
                this.birthdayManager = new BirthdayManager(db);
                
                window.unifiedModal.init(this.eventManager, this.birthdayManager);
                
                this.initializeCalendar();
                this.generateTimeline();
                this.bindSidebarEvents();
            })
            .catch((error) => {
                alert('Error inicializando la base de datos: ' + error);
            });
    }

    initializeCalendar() {
        $('#calendar').fullCalendar({
            header: {
                left: "prev,next today",
                center: "title",
                right: "month,sidebarToggle"
            },
            
            customButtons: {
                sidebarToggle: {
                    text: '☰',
                    click: () => {
                        this.toggleSidebar();
                    }
                }
            },
            
            locale: 'es',
            firstDay: 1,
            editable: true,
            eventLimit: true,
            selectable: true,
            selectHelper: true,
            
            events: (start, end, timezone, callback) => {
                this.loadAllEvents(callback);
            },
            
            dayClick: (date, jsEvent, view) => {
                const dateStr = date.format('YYYY-MM-DD');
                this.updateSidebarForDate(dateStr);
                this.selectDate(dateStr);
            },
            
            select: (start, end) => {
                const dateStr = start.format('YYYY-MM-DD');
                window.unifiedModal.openModal(dateStr);
                $('#calendar').fullCalendar('unselect');
            },
            
            eventClick: (calEvent, jsEvent, view) => {
                this.editEvent(calEvent);
            },
            
            eventDrop: (event, delta, revertFunc) => {
                this.updateEventDate(event, revertFunc);
            },
            
            eventResize: (event, delta, revertFunc) => {
                this.updateEventDate(event, revertFunc);
            }
        });

        this.calendarInstance = $('#calendar');
        window.calendarInstance = this.calendarInstance;
        
        const today = moment().format('YYYY-MM-DD');
        this.updateSidebarForDate(today);
    }

    loadAllEvents(callback) {
        Promise.all([
            this.eventManager.getAllEvents(),
            this.birthdayManager.getBirthdaysForCalendar()
        ])
        .then(([events, birthdays]) => {
            const allEvents = [...events, ...birthdays];
            callback(allEvents);
        })
        .catch((error) => {
            callback([]);
        });
    }

    editEvent(calEvent) {
        if (calEvent.type === 'birthday') {
            const birthdayData = {
                type: 'birthday',
                birthday_id: calEvent.birthday_id,
                nombre: calEvent.nombre,
                dia_nacimiento: calEvent.dia_nacimiento,
                mes_nacimiento: calEvent.mes_nacimiento
            };
            window.unifiedModal.openModal(null, birthdayData);
        } else {
            this.eventManager.getEventById(calEvent.id)
                .then((eventData) => {
                    if (eventData) {
                        eventData.color = calEvent.color;
                        eventData.start = calEvent.start.format('YYYY-MM-DD');
                        eventData.end = calEvent.end ? calEvent.end.format('YYYY-MM-DD') : calEvent.start.format('YYYY-MM-DD');
                        window.unifiedModal.openModal(null, eventData);
                    }
                })
                .catch((error) => {
                    alert('Error cargando el evento: ' + error);
                });
        }
    }

    updateEventDate(event, revertFunc) {
        if (event.type === 'birthday') {
            revertFunc();
            return;
        }

        const eventData = {
            id: event.id,
            evento: event.evento || event.title,
            fecha_inicio: event.start.format('YYYY-MM-DD'),
            fecha_fin: event.end ? event.end.format('YYYY-MM-DD') : event.start.format('YYYY-MM-DD'),
            color_evento: event.color,
            hora_inicio: event.hora_inicio,
            descripcion: event.descripcion
        };

        this.eventManager.saveEvent(eventData)
            .then(() => {
                this.refreshCalendar();
            })
            .catch((error) => {
                revertFunc();
                alert('Error actualizando el evento: ' + error.error);
            });
    }

    generateTimeline() {
        const timelineContainer = $('#timeline-container');
        timelineContainer.empty();
        
        for (let hour = 0; hour < 24; hour++) {
            const hourSlot = $(`
                <div class="hour-slot" data-hour="${hour}">
                    <div class="hour-label">
                        ${hour.toString().padStart(2, '0')}:00
                    </div>
                    <div class="hour-content"></div>
                </div>
            `);
            timelineContainer.append(hourSlot);
        }
    }

    updateSidebarForDate(dateStr) {
        const date = moment(dateStr);
        const formattedDate = date.format('dddd, D [de] MMMM [de] YYYY');
        $('#selected-date').text(formattedDate);

        Promise.all([
            this.eventManager.getEventsForDate(dateStr),
            this.birthdayManager.getBirthdaysForDate(dateStr)
        ])
        .then(([events, birthdays]) => {
            this.populateTimeline(events, birthdays);
        })
        .catch((error) => {
            $('.hour-content').empty();
        });
    }

    populateTimeline(events, birthdays) {
        $('.hour-content').empty();

        birthdays.forEach(birthday => {
            const birthdayElement = $(`
                <div class="timeline-event birthday-event">
                    🎂 ${birthday.nombre}
                </div>
            `);
            $('.hour-slot[data-hour="0"] .hour-content').append(birthdayElement);
        });

        events.forEach(event => {
            let hour = 0;
            if (event.hora_inicio) {
                const timeParts = event.hora_inicio.split(':');
                hour = parseInt(timeParts[0]);
            }

            const eventElement = $(`
                <div class="timeline-event regular-event" style="border-left: 4px solid ${event.color_evento || '#007bff'}">
                    ${event.evento}
                    ${event.hora_inicio ? `<br><small>${event.hora_inicio}</small>` : ''}
                </div>
            `);

            $(`.hour-slot[data-hour="${hour}"] .hour-content`).append(eventElement);
        });
    }

    selectDate(dateStr) {
        this.selectedDate = dateStr;
    }

    toggleSidebar() {
        const sidebar = $('#sidebar-container');
        const indicator = $('.sidebar-toggle-indicator');
        
        if (sidebar.hasClass('sidebar-expanded')) {
            sidebar.removeClass('sidebar-expanded').addClass('sidebar-collapsed');
            indicator.text('▲');
        } else {
            sidebar.removeClass('sidebar-collapsed').addClass('sidebar-expanded');
            indicator.text('▼');
        }
    }

    bindSidebarEvents() {
        $('#sidebar-header').on('click', () => {
            if (this.isMobileDevice) {
                this.toggleSidebar();
            }
        });

        $(document).on('click', '.timeline-event', (e) => {
            const dateStr = this.selectedDate || moment().format('YYYY-MM-DD');
            window.unifiedModal.openModal(dateStr);
        });
    }

    refreshCalendar() {
        if (this.calendarInstance) {
            this.calendarInstance.fullCalendar('refetchEvents');
            
            const currentDate = this.selectedDate || moment().format('YYYY-MM-DD');
            this.updateSidebarForDate(currentDate);
        }
    }
}

const app = new CalendarioApp();
app.init();