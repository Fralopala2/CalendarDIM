<?php
include('PHP/config.php');
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
	<meta http-equiv="Pragma" content="no-cache">
	<meta http-equiv="Expires" content="0">
	<title>Calendario - Versión Final</title>
	<link rel="icon" type="image/svg+xml" href="IMAGES/ImagenAgenda.svg">
	<link rel="stylesheet" type="text/css" href="css/fullcalendar.min.css">
	<link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="css/fullcalendar-fix.css">
    <link rel="stylesheet" type="text/css" href="css/home.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" type="text/css" href="css/home-tablet.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" type="text/css" href="css/home-mobile.css?v=<?php echo time(); ?>">
</head>
<body>

<div class="main-container">

    <div id="calendar-container" class="calendar-container">
        <div id="calendar"></div>
    </div>
    
    <div id="sidebar-container" class="sidebar-expanded">
        <div id="sidebar-header">
            <h3 id="selected-date" class="sidebar-title">Hoy</h3>
            <span class="sidebar-toggle-indicator">▼</span>
        </div>
        <div id="sidebar-content" class="sidebar-content">
            <div id="timeline-container" class="timeline-container">
                <div class="no-events-message">Selecciona un dia para ver los eventos</div>
            </div>
        </div>
    </div>
</div>

<script src="js/jquery-3.0.0.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/moment.min.js"></script>
<script src="js/fullcalendar.min.js"></script>
<script src="locales/es.js"></script>

<?php include('PHP/modalUnifiedEvent.php'); ?>

<script>
$(document).ready(function() {
    var isMobileDevice = /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) || window.innerWidth <= 768;
    
    // Función para cargar eventos en la sidebar
    window.loadEventsForSidebar = function(date) {
        var dateFormatted = moment(date).format('YYYY-MM-DD');
        var displayDate = moment(date).format('DD/MM/YYYY');
        $('#selected-date').text(displayDate);

        $.ajax({
            url: 'PHP/getSidebarEvents.php',
            method: 'GET',
            data: { date: dateFormatted },
            dataType: 'json',
            success: function(response) {
                var container = $('#timeline-container');
                container.empty();
                
                if (response.success && (response.birthdays.length > 0 || response.events.length > 0)) {
                    if (response.birthdays.length > 0) {
                        response.birthdays.forEach(function(birthday) {
                            var birthdayColor = birthday.color || '#FF69B4';
                            var birthdayHtml = '<div class="birthday-item" data-birthday-id="' + birthday.id + '" ' +
                                              'style="background: linear-gradient(135deg, ' + birthdayColor + ' 0%, ' + birthdayColor + 'CC 100%); ' +
                                              'border-left-color: ' + birthdayColor + ';">' +
                                              '🎂 ' + birthday.name +
                                              '</div>';
                            container.append(birthdayHtml);
                        });
                        
                        if (response.events.length > 0) {
                            container.append('<div class="event-separator"></div>');
                        }
                    }
                    
                    if (response.events.length > 0) {
                        response.events.forEach(function(event, index) {
                            var eventColor = event.color || '#007bff';
                            var eventHtml = '<div class="event-item" data-event-id="' + event.id + '" ' +
                                           'style="background: linear-gradient(135deg, ' + eventColor + ' 0%, ' + eventColor + 'CC 100%); ' +
                                           'border-left-color: ' + eventColor + ';">';
                            
                            if (event.time) {
                                eventHtml += '<div class="event-time">' + event.time + '</div>';
                            }
                            
                            eventHtml += '<div class="event-title">' + event.title + '</div>';
                            
                            if (event.description && event.description.trim() !== '') {
                                eventHtml += '<div class="event-description">' + event.description + '</div>';
                            }
                            
                            eventHtml += '</div>';
                            
                            if (index < response.events.length - 1) {
                                eventHtml += '<div class="event-separator"></div>';
                            }
                            
                            container.append(eventHtml);
                        });
                    }
                    
                    // Rebind events for the items just added
                    $('.event-item').off('click').on('click', function(e) {
                        e.stopPropagation();
                        var eventId = $(this).data('event-id');
                        
                        $.ajax({
                            url: 'PHP/getEventDetails.php',
                            method: 'GET',
                            data: { id: eventId },
                            dataType: 'json',
                            success: function(response) {
                                if (response.success && response.event) {
                                    var eventData = {
                                        id: response.event.id,
                                        title: response.event.evento,
                                        start_date: moment(response.event.fecha_inicio).format('DD-MM-YYYY'),
                                        end_date: moment(response.event.fecha_fin).subtract(1, 'day').format('DD-MM-YYYY'),
                                        color: response.event.color_evento,
                                        time: response.event.hora_inicio ? response.event.hora_inicio.substring(0, 5) : '',
                                        description: response.event.descripcion || ''
                                    };
                                    
                                    window.openUnifiedModalForEdit(eventData);
                                }
                            }
                        });
                    });
                    
                    $('.birthday-item').off('click').on('click', function(e) {
                        e.stopPropagation();
                        var birthdayId = $(this).data('birthday-id');
                        var birthdayName = $(this).text().replace('🎂 ', '').trim();
                        var selectedDateMoment = moment($('#selected-date').text(), 'DD/MM/YYYY');
                        
                        var birthdayColor = '#FF69B4';
                        if (response.birthdays) {
                            response.birthdays.forEach(function(b) {
                                if (b.id == birthdayId) {
                                    birthdayColor = b.color;
                                }
                            });
                        }
                        
                        var birthdayData = {
                            id: birthdayId,
                            name: birthdayName,
                            day: selectedDateMoment.date(),
                            month: selectedDateMoment.month() + 1,
                            date: selectedDateMoment.format('YYYY-MM-DD'),
                            color: birthdayColor
                        };
                        
                        window.openUnifiedModalForBirthdayEdit(birthdayData);
                    });
                } else {
                    container.html('<div class="no-events-message">No hay eventos para este día</div>');
                }
            },
            error: function() {
                $('#timeline-container').html('<div class="no-events-message">Error al cargar eventos</div>');
            }
        });
    };

    // Inicializar el modal unificado
    setTimeout(function() {
        if (typeof $ === 'undefined') {
            return;
        }
        
        if (typeof $.fn.modal === 'undefined') {
            return;
        }
        
        if (typeof window.initializeUnifiedModal === 'function') {
            window.initializeUnifiedModal();
        }
    }, 500);
    
    // Initialize FullCalendar with proper height
    setTimeout(function() {
        $("#calendar").fullCalendar({
        header: {
            left: "prev,next today",
            center: "title",
            right: "newEvent,month,sidebarToggle"
        },
        
        customButtons: {
            newEvent: {
                text: '+ Nuevo',
                click: function(event) {
                    if (typeof window.openUnifiedModalForCreate === 'function') {
                        // Pass currently viewed month's 1st day or today
                        var view = $('#calendar').fullCalendar('getView');
                        var defaultDate = moment().format('YYYY-MM-DD');
                        window.openUnifiedModalForCreate(defaultDate);
                    } else {
                        alert('Error: No se pudo abrir el modal. Intenta recargar la pagina.');
                    }
                }
            },
            today: {
                text: 'Hoy',
                click: function() {
                    $('#calendar').fullCalendar('today');
                    window.loadEventsForSidebar(moment());
                }
            },
            sidebarToggle: {
                text: '☰',
                click: function(event) {
                    event.preventDefault();
                    window.toggleSidebar();
                }
            }
        },
        locale: 'es',
        defaultView: "month",
        height: 'parent',
        selectable: true,
        selectHelper: true,
        editable: true,
        fixedWeekCount: false,
        showNonCurrentDates: true,
        eventLimit: true, // show "more" link when too many events
        
        dayClick: function(date, jsEvent, view) {
            if (jsEvent.target.classList.contains('fc-day-number') || 
                jsEvent.target.classList.contains('fc-day-top') ||
                jsEvent.target.classList.contains('fc-more')) {
                window.loadEventsForSidebar(date);
                return false;
            }
        },
        
        select: function(start, end, jsEvent, view){
            var target = jsEvent.target || jsEvent.srcElement;
            
            // Si el clic es en el número del día o en el enlace "+ más", no abrir modal
            if (target && (target.classList.contains('fc-day-number') || 
                target.classList.contains('fc-day-top') ||
                target.classList.contains('fc-more') ||
                $(target).closest('.fc-more').length > 0)) {
                return false;
            }
            
            if (typeof window.openUnifiedModalForCreate === 'function') {
                var startDate = start.format('YYYY-MM-DD');
                window.openUnifiedModalForCreate(startDate);
                
                setTimeout(function() {
                    $("#fecha_inicio").val(startDate);
                    var endDate = moment(end).subtract(1, 'days');
                    $('#fecha_fin').val(endDate.format('YYYY-MM-DD'));
                }, 100);
            } else {
                alert('Error: No se pudo abrir el modal. Intenta recargar la pagina.');
            }
        },
        
        viewRender: function(view, element) {
            // Cambiar color del encabezado según el mes
            // Usamos view.intervalStart para obtener el inicio del rango mostrado (el mes)
            var month = view.intervalStart.month(); // 0-11
            
            // Paleta de colores suaves para cada mes
            var monthColors = [
                '#5C6BC0', // Enero - Índigo
                '#EC407A', // Febrero - Rosa
                '#66BB6A', // Marzo - Verde
                '#FFA726', // Abril - Naranja
                '#26C6DA', // Mayo - Cian
                '#AB47BC', // Junio - Púrpura
                '#FFEE58', // Julio - Amarillo
                '#FF7043', // Agosto - Naranja profundo
                '#8D6E63', // Septiembre - Marrón
                '#78909C', // Octubre - Gris azulado
                '#546E7A', // Noviembre - Azul gris
                '#42A5F5'  // Diciembre - Azul
            ];
            
            var selectedColor = monthColors[month];
            $('.fc-toolbar, #sidebar-header').css({
                'background-color': selectedColor + ' !important',
                'background-image': 'none !important', // Remove any gradient if exists
                'color': 'white !important',
                'padding': '15px !important',
                'border-radius': '12px 12px 0 0 !important',
                'margin-bottom': '0 !important',
                'border': 'none !important',
                'transition': 'background-color 0.5s ease'
            });
            $('.fc-toolbar h2, #sidebar-header h3').css('color', 'white !important');
            $('.fc-button').css({
                'background': 'rgba(255,255,255,0.2) !important',
                'border': 'none !important',
                'color': 'white !important',
                'text-shadow': 'none !important',
                'box-shadow': 'none !important'
            });
            
            // Forzar actualización de estilos manual si jQuery .css() falla con !important
            var styleTag = document.getElementById('dynamic-header-style');
            if (!styleTag) {
                styleTag = document.createElement('style');
                styleTag.id = 'dynamic-header-style';
                document.head.appendChild(styleTag);
            }
            styleTag.innerHTML = `
                .fc-toolbar, #sidebar-header { background-color: ${selectedColor} !important; background-image: none !important; color: white !important; transition: background-color 0.5s ease; }
                .fc-toolbar h2, #sidebar-header h3 { color: white !important; }
                .fc-button { background: rgba(255,255,255,0.2) !important; color: white !important; border: none !important; }
                .fc-button:hover { background: rgba(255,255,255,0.3) !important; }
                .fc-state-active { background: rgba(255,255,255,0.4) !important; }
            `;
        },
        
        events: [
            <?php
            $sql = "SELECT id, evento, fecha_inicio, fecha_fin, color_evento, hora_inicio, descripcion FROM eventoscalendar ORDER BY fecha_inicio ASC";
            $result = mysqli_query($con, $sql);
            
            if ($result) {
                while($row = mysqli_fetch_assoc($result)) {
                    $eventTitle = $row['evento'];
                    if (!empty($row['hora_inicio'])) {
                        $timeFormatted = date('H:i', strtotime($row['hora_inicio']));
                        $eventTitle = $timeFormatted . ' - ' . $eventTitle;
                    }
                    
                    echo "{\n";
                    echo "  _id: 'event_" . $row['id'] . "',\n";
                    echo "  title: '" . addslashes($eventTitle) . "',\n";
                    echo "  start: '" . $row['fecha_inicio'] . "',\n";
                    
                    // Only add end date if it's different from start date
                    if ($row['fecha_fin'] !== $row['fecha_inicio']) {
                        echo "  end: '" . $row['fecha_fin'] . "',\n";
                    }
                    
                    echo "  color: '" . $row['color_evento'] . "',\n";
                    echo "  type: 'event'\n";
                    echo "},\n";
                }
            }
            
            // Load birthday configuration
            require_once('PHP/birthday_config.php');
            $yearRange = getBirthdayYearRange();
            $startYear = $yearRange['start'];
            $endYear = $yearRange['end'];
            
            $sql = "SELECT id, nombre, dia_nacimiento, mes_nacimiento, color_cumpleanos FROM cumpleanoscalendar";
            $result = mysqli_query($con, $sql);
            
            if ($result) {
                while($row = mysqli_fetch_assoc($result)) {
                    $birthdayColor = isset($row['color_cumpleanos']) && !empty($row['color_cumpleanos']) ? $row['color_cumpleanos'] : '#FF69B4';
                    
                    // Generate birthday for each year in the range
                    for ($year = $startYear; $year <= $endYear; $year++) {
                        $birthdayDate = $year . '-' . sprintf('%02d', $row['mes_nacimiento']) . '-' . sprintf('%02d', $row['dia_nacimiento']);
                        
                        echo "{\n";
                        echo "  _id: 'birthday_" . $row['id'] . "_" . $year . "',\n";
                        echo "  title: '🎂 " . addslashes($row['nombre']) . "',\n";
                        echo "  start: '" . $birthdayDate . "',\n";
                        echo "  color: '" . $birthdayColor . "',\n";
                        echo "  type: 'birthday',\n";
                        echo "  allDay: true\n";
                        echo "},\n";
                    }
                }
            }
            ?>
        ],
        
        eventClick: function(event, jsEvent, view){
            jsEvent.stopPropagation();
            jsEvent.preventDefault();
            
            if (event.type === 'birthday') {
                var birthdayIdParts = event._id.split('_');
                var birthdayId = birthdayIdParts[1];
                var birthdayName = event.title.replace('🎂 ', '');
                var birthdayDate = moment(event.start);
                
                var birthdayData = {
                    id: birthdayId,
                    name: birthdayName,
                    day: birthdayDate.date(),
                    month: birthdayDate.month() + 1,
                    date: birthdayDate.format('YYYY-MM-DD'),
                    color: event.color || '#FF69B4'
                };
                
                window.openUnifiedModalForBirthdayEdit(birthdayData);
            } else {
                var eventId = event._id.replace('event_', '');
                
                $.ajax({
                    url: 'PHP/getEventDetails.php',
                    method: 'GET',
                    data: { id: eventId },
                    dataType: 'json',
                    success: function(response) {
                        if (response.success && response.event) {
                            var eventData = {
                                id: response.event.id,
                                title: response.event.evento,
                                start_date: moment(response.event.fecha_inicio).format('DD-MM-YYYY'),
                                end_date: moment(response.event.fecha_fin).subtract(1, 'day').format('DD-MM-YYYY'),
                                color: response.event.color_evento,
                                time: response.event.hora_inicio ? response.event.hora_inicio.substring(0, 5) : '',
                                description: response.event.descripcion || ''
                            };
                            
                            window.openUnifiedModalForEdit(eventData);
                        }
                    }
                });
            }
            
            return false;
        },
        
        eventDrop: function (event, delta) {
            if (event.type === 'birthday') {
                var birthdayIdParts = event._id.split('_');
                var birthdayId = birthdayIdParts[1];
                var newDate = event.start;
                var day = newDate.date();
                var month = newDate.month() + 1;
                
                $.ajax({
                    url: 'PHP/updateBirthdayDate.php',
                    data: { id: birthdayId, day: day, month: month },
                    type: "POST",
                    dataType: 'json',
                    success: function (response) {
                        if (response.success) {
                            var currentDate = $('#selected-date').text();
                            if (currentDate && currentDate !== 'Hoy') {
                                var selectedMoment = moment(currentDate, 'DD/MM/YYYY');
                                window.loadEventsForSidebar(selectedMoment);
                            } else {
                                window.loadEventsForSidebar(moment());
                            }
                        }
                    },
                    error: function() {
                        $('#calendar').fullCalendar('refetchEvents');
                        alert('Error al mover el cumpleanos');
                    }
                });
            } else {
                var idEvento = event._id.replace('event_', '');
                var start = event.start.format('DD-MM-YYYY');
                var end = event.end ? event.end.format('DD-MM-YYYY') : event.start.format('DD-MM-YYYY');
                
                $.ajax({
                    url: 'PHP/drag_drop_evento.php',
                    data: 'start=' + start + '&end=' + end + '&idEvento=' + idEvento,
                    type: "POST",
                    success: function (response) {
                    },
                    error: function() {
                        $('#calendar').fullCalendar('refetchEvents');
                        alert('Error al mover el evento');
                    }
                });
            }
        }
    });
    
        // Cargar eventos de hoy automáticamente en la sidebar
        window.loadEventsForSidebar(moment());
        
        // Trigger resize to fix layout
        setTimeout(function() {
            $(window).trigger('resize');
        }, 500);
    }, 100);
    
    var resizeTimeout;
    $(window).on('resize', function() {
        clearTimeout(resizeTimeout);
        resizeTimeout = setTimeout(function() {
            if ($('#calendar').length) {
                $('#calendar').fullCalendar('render');
            }
        }, 250);
    });
    
    let devtoolsOpen = false;
    const threshold = 160;
    let lastHeight = window.innerHeight;
    
    const checkDevTools = setInterval(function() {
        const currentHeight = window.innerHeight;
        if (Math.abs(currentHeight - lastHeight) > threshold) {
            $('#calendar').fullCalendar('render');
            lastHeight = currentHeight;
        }
    }, 500);
    
    window.toggleSidebar = function() {
        var sidebar = $('#sidebar-container');
        var mainContainer = $('.main-container');
        var indicator = $('.sidebar-toggle-indicator');
        
        if (sidebar.hasClass('sidebar-expanded')) {
            sidebar.removeClass('sidebar-expanded').addClass('sidebar-collapsed');
            mainContainer.removeClass('sidebar-expanded').addClass('sidebar-collapsed');
            if (indicator.length) {
                indicator.text('▶');
            }
        } else {
            sidebar.removeClass('sidebar-collapsed').addClass('sidebar-expanded');
            mainContainer.removeClass('sidebar-collapsed').addClass('sidebar-expanded');
            if (indicator.length) {
                indicator.text('▼');
            }
        }
        
        // Trigger resize to fix layout immediately
        setTimeout(function() {
            if ($('#calendar').length) {
                $('#calendar').fullCalendar('render');
            }
        }, 300);
    };
    
    $('#sidebar-header').on('click', function() {
        if (window.innerWidth <= 1024) {
            window.toggleSidebar();
        }
    });
    
    $(window).on('resize', function() {
        var sidebar = $('#sidebar-container');
        var mainContainer = $('.main-container');
        var indicator = $('.sidebar-toggle-indicator');
        var screenWidth = window.innerWidth;
        
        if (screenWidth <= 768) {
            if (!sidebar.hasClass('sidebar-collapsed')) {
                sidebar.removeClass('sidebar-expanded').addClass('sidebar-collapsed');
                mainContainer.removeClass('sidebar-expanded').addClass('sidebar-collapsed');
                if (indicator.length) indicator.text('▶');
            }
        } else if (screenWidth > 768 && screenWidth <= 1024) {
            if (!sidebar.hasClass('sidebar-collapsed')) {
                sidebar.removeClass('sidebar-expanded').addClass('sidebar-collapsed');
                mainContainer.removeClass('sidebar-expanded').addClass('sidebar-collapsed');
                if (indicator.length) indicator.text('▶');
            }
        } else {
            if (!sidebar.hasClass('sidebar-expanded')) {
                sidebar.removeClass('sidebar-collapsed').addClass('sidebar-expanded');
                mainContainer.removeClass('sidebar-collapsed').addClass('sidebar-expanded');
                if (indicator.length) indicator.text('▼');
            }
        }
    });
    
    $(document).ready(function() {
        var sidebar = $('#sidebar-container');
        var mainContainer = $('.main-container');
        var indicator = $('.sidebar-toggle-indicator');
        var screenWidth = window.innerWidth;
        
        if (screenWidth <= 1024) {
            sidebar.removeClass('sidebar-expanded').addClass('sidebar-collapsed');
            mainContainer.removeClass('sidebar-expanded').addClass('sidebar-collapsed');
            if (indicator.length) indicator.text('▶');
        } else {
            sidebar.removeClass('sidebar-collapsed').addClass('sidebar-expanded');
            mainContainer.removeClass('sidebar-collapsed').addClass('sidebar-expanded');
            if (indicator.length) indicator.text('▼');
        }
    });
    
    $(window).trigger('resize');
    
    var isMobileDevice = /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) || window.innerWidth <= 768;
    var isTabletDevice = /iPad/i.test(navigator.userAgent) || (window.innerWidth > 768 && window.innerWidth <= 1024);
    var isTouchDevice = isMobileDevice || isTabletDevice;
    
    if (isTouchDevice) {
        var touchStartTime = 0;
        var touchMoved = false;
        var touchStartX = 0;
        var touchStartY = 0;
        var minSwipeDistance = 50;
        
        $('#calendar').on('touchstart', '.fc-day, .fc-day-top, .fc-day-number, .fc-content-skeleton td', function(e) {
            touchStartTime = Date.now();
            touchMoved = false;
            touchStartX = e.originalEvent.touches[0].clientX;
            touchStartY = e.originalEvent.touches[0].clientY;
        });
        
        $('#calendar').on('touchmove', '.fc-day, .fc-day-top, .fc-day-number, .fc-content-skeleton td', function(e) {
            touchMoved = true;
        });
        
        $('#calendar').on('touchend', '.fc-day, .fc-day-top, .fc-day-number, .fc-content-skeleton td', function(e) {
            var touchDuration = Date.now() - touchStartTime;
            if (touchMoved || touchDuration > 500) {
                return;
            }
            
            var target = e.originalEvent.changedTouches[0].target || e.target;
            
            // Check if clicking an event
            if ($(target).closest('.fc-event').length) {
                return;
            }
            
            e.preventDefault();
            
            var $el = $(this);
            var dateStr = $el.data('date') || $el.closest('[data-date]').data('date');
            
            if (!dateStr) {
                // Fallback for empty skeleton cells
                var index = $el.index();
                var $row = $el.closest('.fc-row');
                dateStr = $row.find('.fc-bg .fc-day').eq(index).data('date');
            }
            
            if (dateStr) {
                var $dayEl = $('.fc-day[data-date="' + dateStr + '"]');
                if ($dayEl.hasClass('fc-other-month')) {
                    return;
                }
                
                if (typeof window.openUnifiedModalForCreate === 'function') {
                    window.openUnifiedModalForCreate(dateStr);
                    setTimeout(function() {
                        $("#fecha_inicio").val(dateStr);
                        $('#fecha_fin').val(dateStr);
                    }, 200);
                }
            }
        });
        
        // Consolidate click handler for devices that emulate mouse but are touch-enabled
        $('#calendar').on('click', '.fc-day, .fc-day-top, .fc-day-number, .fc-content-skeleton td', function(e) {
            // Only handle if it wasn't handled by touch (check time since touchstart)
            if (Date.now() - touchStartTime > 500) {
                var target = e.target || e.srcElement;
                
                if ($(target).closest('.fc-event').length) {
                    return;
                }
                
                var $el = $(this);
                var dateStr = $el.data('date') || $el.closest('[data-date]').data('date');
                
                if (!dateStr) {
                    var index = $el.index();
                    var $row = $el.closest('.fc-row');
                    dateStr = $row.find('.fc-bg .fc-day').eq(index).data('date');
                }
                
                if (dateStr) {
                    var $dayEl = $('.fc-day[data-date="' + dateStr + '"]');
                    if ($dayEl.hasClass('fc-other-month')) {
                        return;
                    }
                    
                    if (typeof window.openUnifiedModalForCreate === 'function') {
                        window.openUnifiedModalForCreate(dateStr);
                        setTimeout(function() {
                            $("#fecha_inicio").val(dateStr);
                            $('#fecha_fin').val(dateStr);
                        }, 200);
                    }
                }
            }
        });
        
        var calendarSwipeStartX = 0;
        var calendarSwipeStartY = 0;
        var calendarSwipeEndX = 0;
        var calendarSwipeEndY = 0;
        
        $('#calendar').on('touchstart', '.fc-view-container, .fc-toolbar', function(e) {
            calendarSwipeStartX = e.originalEvent.touches[0].clientX;
            calendarSwipeStartY = e.originalEvent.touches[0].clientY;
        });
        
        $('#calendar').on('touchend', '.fc-view-container, .fc-toolbar', function(e) {
            calendarSwipeEndX = e.originalEvent.changedTouches[0].clientX;
            calendarSwipeEndY = e.originalEvent.changedTouches[0].clientY;
            
            var swipeDistanceX = calendarSwipeStartX - calendarSwipeEndX;
            var swipeDistanceY = Math.abs(calendarSwipeStartY - calendarSwipeEndY);
            
            if (Math.abs(swipeDistanceX) > minSwipeDistance && swipeDistanceY < 100) {
                if (swipeDistanceX > 0) {
                    $('#calendar').fullCalendar('next');
                } else {
                    $('#calendar').fullCalendar('prev');
                }
            }
        });
    }
    
    
});
</script>

</body>
</html>