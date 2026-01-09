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
	<link rel="stylesheet" type="text/css" href="css/fullcalendar.min.css">
	<link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
    <!-- CSS para Desktop (pantallas grandes) -->
    <link rel="stylesheet" type="text/css" href="css/home.css?v=<?php echo time(); ?>" media="(min-width: 1025px)">
    <!-- CSS para Tablets (pantallas medianas) -->
    <link rel="stylesheet" type="text/css" href="css/home-tablet.css?v=<?php echo time(); ?>" media="(min-width: 769px) and (max-width: 1024px)">
    <!-- CSS para Móvil (pantallas pequeñas) -->
    <link rel="stylesheet" type="text/css" href="css/home-mobile.css?v=<?php echo time(); ?>" media="(max-width: 768px)">>
</head>
<body>
<div class="banner-container">
    <img src="IMAGES/ImagenAgenda.svg" alt="Calendar Banner" class="banner-image">
</div>

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
                <?php for($hour = 0; $hour < 24; $hour++): ?>
                    <div class="hour-slot" data-hour="<?php echo $hour; ?>">
                        <div class="hour-label">
                            <?php echo sprintf('%02d:00', $hour); ?>
                        </div>
                        <div class="hour-content"></div>
                    </div>
                <?php endfor; ?>
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
    // Debug: Verificar que estamos en móvil
    var isMobileDevice = /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) || window.innerWidth <= 768;
    console.log('Dispositivo móvil detectado:', isMobileDevice);
    console.log('Ancho de ventana:', window.innerWidth);
    
    // Initialize modal con verificación
    if (typeof window.initializeUnifiedModal === 'function') {
        window.initializeUnifiedModal();
        console.log('Modal inicializado correctamente');
    } else {
        console.error('Función initializeUnifiedModal no encontrada');
    }
    
    // Verificar que las funciones del modal existen después de la inicialización
    setTimeout(function() {
        console.log('openUnifiedModalForCreate existe:', typeof window.openUnifiedModalForCreate === 'function');
        console.log('openUnifiedModalForEdit existe:', typeof window.openUnifiedModalForEdit === 'function');
    }, 500);
    
    $("#calendar").fullCalendar({
        header: {
            left: "prev,next today",
            center: "title",
            right: "month,sidebarToggle"
        },
        
        customButtons: {
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
        height: 'auto',
        selectable: true,
        selectHelper: true,
        editable: true,
        fixedWeekCount: false,
        showNonCurrentDates: false,
        
        dayClick: function(date, jsEvent, view) {
            // Solo actualizar sidebar si es click directo en numero de dia
            if (jsEvent.target.classList.contains('fc-day-number') || 
                jsEvent.target.classList.contains('fc-day-top')) {
                
                // Actualizar sidebar con el dia seleccionado
                $('#selected-date').text(date.format('DD/MM/YYYY'));
                
                // Limpiar timeline
                $('.hour-content').empty();
                
                // Buscar eventos para este dia y mostrarlos en timeline
                var selectedDate = date.format('YYYY-MM-DD');
                
                // Obtener eventos del dia via AJAX
                $.ajax({
                    url: 'PHP/getEventsForDay.php',
                    method: 'GET',
                    data: { date: selectedDate },
                    dataType: 'json',
                    success: function(events) {
                        if (events && events.length > 0) {
                            events.forEach(function(event) {
                                var eventHtml = '';
                                
                                if (event.type === 'birthday') {
                                    // Cumpleaños van en la hora 00:00 con color personalizado
                                    var birthdayColor = event.color_evento || '#FF69B4';
                                    eventHtml = '<div class="timeline-birthday clickable-sidebar-birthday" data-birthday-id="' + event.id + '" style="background: linear-gradient(135deg, ' + birthdayColor + ' 0%, ' + birthdayColor + 'CC 100%); border-left: 4px solid ' + birthdayColor + ';">' +
                                              '<div class="event-title">' + event.evento + '</div>' +
                                              '</div>';
                                    $('.hour-slot[data-hour="0"] .hour-content').append(eventHtml);
                                } else if (event.hora_inicio) {
                                    // Eventos regulares con color personalizado
                                    var hour = parseInt(event.hora_inicio.split(':')[0]);
                                    var eventColor = event.color_evento || '#007bff';
                                    eventHtml = '<div class="timeline-event clickable-sidebar-event" data-event-id="' + event.id + '" style="background: linear-gradient(135deg, ' + eventColor + ' 0%, ' + eventColor + 'CC 100%); border-left: 4px solid ' + eventColor + ';">' +
                                              '<div class="event-time">' + event.hora_inicio.substring(0,5) + '</div>' +
                                              '<div class="event-title">' + event.evento + '</div>';
                                    
                                    // Agregar descripcion si existe
                                    if (event.descripcion && event.descripcion.trim() !== '') {
                                        eventHtml += '<div class="event-description">' + event.descripcion + '</div>';
                                    }
                                    
                                    eventHtml += '</div>';
                                    $('.hour-slot[data-hour="' + hour + '"] .hour-content').append(eventHtml);
                                }
                            });
                            
                            // Hacer clickeables los eventos de la sidebar
                            $('.clickable-sidebar-event').off('click').on('click', function(e) {
                                e.stopPropagation();
                                var eventId = $(this).data('event-id');
                                
                                // Obtener detalles completos del evento
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
                                    },
                                    error: function() {
                                        alert('Error al cargar detalles del evento');
                                    }
                                });
                            });
                            
                            // Hacer clickeables los cumpleaños de la sidebar
                            $('.clickable-sidebar-birthday').off('click').on('click', function(e) {
                                e.stopPropagation();
                                var birthdayId = $(this).data('birthday-id');
                                var birthdayName = $(this).find('.event-title').text().replace('🎂 ', '');
                                var selectedDateMoment = moment($('#selected-date').text(), 'DD/MM/YYYY');
                                
                                // Buscar el color del cumpleaños en los datos cargados
                                var birthdayColor = '#FF69B4'; // Default
                                events.forEach(function(evt) {
                                    if (evt.type === 'birthday' && evt.id == birthdayId) {
                                        birthdayColor = evt.color_evento;
                                    }
                                });
                                
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
                        }
                    },
                    error: function() {
                        // Error loading events for day
                    }
                });
                
                // Prevenir que se active el select
                return false;
            }
        },
        
        select: function(start, end, jsEvent, view){
            // Mejorar detección para móvil - incluir eventos touch
            var target = jsEvent.target || jsEvent.srcElement;
            
            // Solo crear evento si NO es click en numero de dia
            if (target && (target.classList.contains('fc-day-number') || 
                target.classList.contains('fc-day-top'))) {
                return false; // No crear evento si es click en numero
            }
            
            // Verificar que la función existe antes de llamarla
            if (typeof window.openUnifiedModalForCreate === 'function') {
                window.openUnifiedModalForCreate();
                setTimeout(function() {
                    // Convert to YYYY-MM-DD format for date inputs
                    $("#fecha_inicio").val(start.format('YYYY-MM-DD'));
                    var endDate = moment(end).subtract(1, 'days');
                    $('#fecha_fin').val(endDate.format('YYYY-MM-DD'));
                }, 100);
            } else {
                console.error('openUnifiedModalForCreate function not found');
                alert('Error: No se pudo abrir el modal. Recarga la página.');
            }
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
                    echo "  end: '" . $row['fecha_fin'] . "',\n";
                    echo "  color: '" . $row['color_evento'] . "',\n";
                    echo "  type: 'event'\n";
                    echo "},\n";
                }
            }
            
            $currentYear = date('Y');
            $sql = "SELECT id, nombre, dia_nacimiento, mes_nacimiento, color_cumpleanos FROM cumpleanos";
            $result = mysqli_query($con, $sql);
            
            if ($result) {
                while($row = mysqli_fetch_assoc($result)) {
                    $birthdayDate = $currentYear . '-' . sprintf('%02d', $row['mes_nacimiento']) . '-' . sprintf('%02d', $row['dia_nacimiento']);
                    $birthdayColor = !empty($row['color_cumpleanos']) ? $row['color_cumpleanos'] : '#FF69B4';
                    
                    echo "{\n";
                    echo "  _id: 'birthday_" . $row['id'] . "',\n";
                    echo "  title: '🎂 " . addslashes($row['nombre']) . "',\n";
                    echo "  start: '" . $birthdayDate . "',\n";
                    echo "  color: '" . $birthdayColor . "',\n";
                    echo "  type: 'birthday',\n";
                    echo "  allDay: true\n";
                    echo "},\n";
                }
            }
            ?>
        ],
        
        eventClick: function(event, jsEvent, view){
            jsEvent.stopPropagation();
            jsEvent.preventDefault();
            
            if (event.type === 'birthday') {
                var birthdayId = event._id.replace('birthday_', '');
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
        
        // Drag and drop functionality
        eventDrop: function (event, delta) {
            var idEvento = event._id.replace('event_', '');
            var start = event.start.format('DD-MM-YYYY');
            var end = event.end ? event.end.format('DD-MM-YYYY') : event.start.format('DD-MM-YYYY');
            
            $.ajax({
                url: 'PHP/drag_drop_evento.php',
                data: 'start=' + start + '&end=' + end + '&idEvento=' + idEvento,
                type: "POST",
                success: function (response) {
                    // Event moved successfully
                },
                error: function() {
                    // Revert the event if there was an error
                    $('#calendar').fullCalendar('refetchEvents');
                    alert('Error al mover el evento');
                }
            });
        }
    });
    
    // Funcion para alternar sidebar mejorada para móvil
    window.toggleSidebar = function() {
        var sidebar = $('#sidebar-container');
        var isMobile = window.innerWidth <= 768;
        
        if (sidebar.hasClass('sidebar-expanded')) {
            sidebar.removeClass('sidebar-expanded').addClass('sidebar-collapsed');
        } else {
            sidebar.removeClass('sidebar-collapsed').addClass('sidebar-expanded');
        }
    };
    
    // Hacer clickeable el header del sidebar en móvil
    $('#sidebar-header').on('click', function() {
        if (window.innerWidth <= 768) {
            window.toggleSidebar();
        }
    });
    
    // Inicializar estado del sidebar según el tamaño de pantalla
    $(window).on('resize', function() {
        var sidebar = $('#sidebar-container');
        var isMobile = window.innerWidth <= 768;
        
        if (isMobile) {
            // En móvil, empezar colapsado
            if (!sidebar.hasClass('sidebar-collapsed')) {
                sidebar.removeClass('sidebar-expanded').addClass('sidebar-collapsed');
            }
        } else {
            // En desktop, empezar expandido
            if (!sidebar.hasClass('sidebar-expanded')) {
                sidebar.removeClass('sidebar-collapsed').addClass('sidebar-expanded');
            }
        }
    });
    
    // Ejecutar al cargar la página
    $(window).trigger('resize');
    
    // Mejorar soporte para móvil y tablet - eventos touch más robustos
    var isMobileDevice = /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) || window.innerWidth <= 768;
    var isTabletDevice = /iPad/i.test(navigator.userAgent) || (window.innerWidth > 768 && window.innerWidth <= 1024);
    var isTouchDevice = isMobileDevice || isTabletDevice;
    
    if (isTouchDevice) {
        // Variables para controlar touch events
        var touchStartTime = 0;
        var touchMoved = false;
        
        // Añadir eventos touch específicos para móvil
        $('#calendar').on('touchstart', '.fc-day:not(.fc-other-month)', function(e) {
            touchStartTime = Date.now();
            touchMoved = false;
        });
        
        $('#calendar').on('touchmove', '.fc-day:not(.fc-other-month)', function(e) {
            touchMoved = true;
        });
        
        $('#calendar').on('touchend', '.fc-day:not(.fc-other-month)', function(e) {
            e.preventDefault();
            
            // Solo procesar si fue un tap rápido (no scroll)
            var touchDuration = Date.now() - touchStartTime;
            if (touchMoved || touchDuration > 500) {
                return;
            }
            
            var target = e.originalEvent.target || e.target;
            
            // Solo si no es click en número de día o eventos existentes
            if (!$(target).hasClass('fc-day-number') && 
                !$(target).hasClass('fc-day-top') && 
                !$(target).closest('.fc-event').length) {
                
                var dateStr = $(this).data('date');
                if (dateStr) {
                    // Verificar que la función existe
                    if (typeof window.openUnifiedModalForCreate === 'function') {
                        console.log('Abriendo modal para fecha:', dateStr);
                        window.openUnifiedModalForCreate();
                        setTimeout(function() {
                            $("#fecha_inicio").val(dateStr);
                            $('#fecha_fin').val(dateStr);
                        }, 200);
                    } else {
                        console.error('Función openUnifiedModalForCreate no encontrada');
                        alert('Error: No se pudo abrir el modal. Recarga la página.');
                    }
                }
            }
        });
        
        // Fallback para dispositivos que no detectan touch correctamente
        $('#calendar').on('click', '.fc-day:not(.fc-other-month)', function(e) {
            // Solo ejecutar si no hubo evento touch reciente
            if (Date.now() - touchStartTime > 1000) {
                var target = e.target || e.srcElement;
                
                if (!$(target).hasClass('fc-day-number') && 
                    !$(target).hasClass('fc-day-top') && 
                    !$(target).closest('.fc-event').length) {
                    
                    var dateStr = $(this).data('date');
                    if (dateStr && typeof window.openUnifiedModalForCreate === 'function') {
                        console.log('Abriendo modal via click fallback para fecha:', dateStr);
                        window.openUnifiedModalForCreate();
                        setTimeout(function() {
                            $("#fecha_inicio").val(dateStr);
                            $('#fecha_fin').val(dateStr);
                        }, 200);
                    }
                }
            }
        });
    }
    
    // Fallback adicional para tablets que no se detectan como touch
    if (window.innerWidth > 768 && window.innerWidth <= 1024) {
        $('#calendar').on('click', '.fc-day:not(.fc-other-month)', function(e) {
            var target = e.target || e.srcElement;
            
            if (!$(target).hasClass('fc-day-number') && 
                !$(target).hasClass('fc-day-top') && 
                !$(target).closest('.fc-event').length) {
                
                var dateStr = $(this).data('date');
                if (dateStr && typeof window.openUnifiedModalForCreate === 'function') {
                    console.log('Abriendo modal para tablet en fecha:', dateStr);
                    window.openUnifiedModalForCreate();
                    setTimeout(function() {
                        $("#fecha_inicio").val(dateStr);
                        $('#fecha_fin').val(dateStr);
                    }, 200);
                }
            }
        });
    }
    
});
</script>

</body>
</html>