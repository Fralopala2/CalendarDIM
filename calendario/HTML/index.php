<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>.:: Calendario ::.</title>
	<meta http-equiv="Content-Type" content="text/html" charset="UTF-8"/>
	<meta name="description" content=""/>
	<meta name="Author" content=""/>
	<meta name="Email" content=""/>
	<meta name="Copyright" content=""/>
	<meta name="keywords" content=""/>
	<link rel="shortcut icon" href="../IMAGES/ImagenAgenda.svg" type="image/svg+xml"/>
	<link rel="stylesheet" type="text/css" href="../css/fullcalendar.min.css">
	<!-- External dependencies removed for offline functionality -->
	<link rel="stylesheet" type="text/css" href="../css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="../css/home.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" type="text/css" href="../css/emoji-support.css">
</head>
<body>
<div class="banner-container">
    <img src="../IMAGES/ImagenAgenda.svg" alt="Calendar Banner" class="banner-image">
</div>
<?php
include('../PHP/config.php');
?>

<!-- Main Container with Flexbox Layout -->
<div class="main-container">
    <!-- Calendar Container -->
    <div id="calendar-container" class="calendar-container">
        <div id="calendar"></div>
    </div>
    
    <!-- Collapsible Sidebar (Right side) -->
    <div id="sidebar-container" class="sidebar-expanded" style="display: flex !important; width: 320px !important; background: white !important; border-left: 2px solid #ccc !important; flex-direction: column !important; transition: all 0.4s ease !important;">
        <div id="sidebar-header" style="display: flex !important; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important; padding: 20px !important; color: white !important; align-items: center !important; justify-content: center !important;">
            <h3 id="selected-date" class="sidebar-title" style="margin: 0 !important; color: white !important; text-align: center !important;">Hoy</h3>
        </div>
        <div id="sidebar-content" class="sidebar-content">
            <div id="timeline-container" class="timeline-container">
                <!-- 24-hour timeline will be generated here -->
                <?php for($hour = 0; $hour < 24; $hour++): ?>
                    <div class="hour-slot" data-hour="<?php echo $hour; ?>">
                        <div class="hour-label">
                            <?php echo sprintf('%02d:00', $hour); ?>
                        </div>
                        <div class="hour-content">
                            <!-- Events and birthdays for this hour will be displayed here -->
                        </div>
                    </div>
                <?php endfor; ?>
            </div>
        </div>
    </div>
</div>

<script src ="../js/jquery-3.0.0.min.js"> </script>
<script src="../js/popper.min.js"></script>
<script src="../js/bootstrap.min.js"></script>
<script type="text/javascript" src="../js/moment.min.js"></script>	
<script type="text/javascript" src="../js/fullcalendar.min.js"></script>
<script src='../locales/es.js'></script>

<?php  
  include('../PHP/modalUnifiedEvent.php');
?>

<script type="text/javascript">
$(document).ready(function() {
  console.log('Document ready - checking sidebar...');
  
  // Check if sidebar exists
  var $sidebar = $('#sidebar-container');
  console.log('Sidebar element found:', $sidebar.length > 0);
  console.log('Sidebar classes:', $sidebar.attr('class'));
  console.log('Sidebar computed style display:', window.getComputedStyle($sidebar[0]).display);
  console.log('Sidebar computed style width:', window.getComputedStyle($sidebar[0]).width);

  // DEFINE MODAL FUNCTIONS FIRST - BEFORE FULLCALENDAR INITIALIZATION
  // Initialize the unified modal
  if (typeof window.initializeUnifiedModal === 'function') {
    window.initializeUnifiedModal();
    console.log('Unified modal initialized');
  } else {
    console.error('initializeUnifiedModal function not found');
  }

  // Enhanced sidebar date display with better formatting
  function updateSidebarDate(date, formattedDate) {
    var displayText;
    
    if (formattedDate) {
      // Use server-provided formatted date
      displayText = formattedDate;
    } else if (date) {
      var selectedMoment = moment(date);
      var today = moment();
      
      if (selectedMoment.isSame(today, 'day')) {
        displayText = 'Hoy - ' + selectedMoment.format('DD/MM/YYYY');
      } else if (selectedMoment.isSame(today.clone().add(1, 'day'), 'day')) {
        displayText = 'Mañana - ' + selectedMoment.format('DD/MM/YYYY');
      } else if (selectedMoment.isSame(today.clone().subtract(1, 'day'), 'day')) {
        displayText = 'Ayer - ' + selectedMoment.format('DD/MM/YYYY');
      } else {
        displayText = selectedMoment.format('dddd, DD/MM/YYYY');
      }
    } else {
      var today = moment();
      displayText = 'Hoy - ' + today.format('DD/MM/YYYY');
    }
    
    $('#selected-date').text(displayText);
  }

  // Function to load and display events in timeline
  function loadTimelineEvents(date) {
    // Show loading state
    $('.hour-content').html('<div class="timeline-loading">Cargando...</div>');
    
    var dateString = date ? date.format('YYYY-MM-DD') : moment().format('YYYY-MM-DD');
    
    // Make AJAX request to get sidebar content
    $.ajax({
      url: '../PHP/getSidebarContent.php',
      method: 'GET',
      data: { date: dateString },
      dataType: 'json',
      success: function(response) {
        if (response.success) {
          renderTimelineContent(response.timeline);
          updateSidebarDate(null, response.formatted_date);
        } else {
          showTimelineError('Error al cargar eventos: ' + response.error);
        }
      },
      error: function(xhr, status, error) {
        console.error('AJAX Error:', error);
        console.error('Response:', xhr.responseText);
        showTimelineError('Error de conexión. Inténtalo de nuevo.');
      }
    });
  }

  // Render timeline content from server response
  function renderTimelineContent(timelineData) {
    // Clear all hour content first
    $('.hour-content').empty();
    
    timelineData.forEach(function(hourSlot) {
      var $hourContent = $('.hour-slot[data-hour="' + hourSlot.hour + '"] .hour-content');
      
      // Add birthdays (only for hour 0)
      if (hourSlot.hour === 0 && hourSlot.birthdays.length > 0) {
        hourSlot.birthdays.forEach(function(birthday) {
          var $birthdayElement = $('<div class="timeline-birthday">')
            .text(birthday.display)
            .attr('data-birthday-id', birthday.id);
          $hourContent.append($birthdayElement);
        });
      }
      
      // Add events for this hour
      hourSlot.events.forEach(function(event) {
        var $eventElement = $('<div class="timeline-event">')
          .attr('data-event-id', event.id)
          .css('border-left-color', event.color);
        
        var $eventTime = $('<div class="event-time">').text(event.time);
        var $eventTitle = $('<div class="event-title">').text(event.title);
        
        $eventElement.append($eventTime, $eventTitle);
        
        if (event.description) {
          var $eventDescription = $('<div class="event-description">').text(event.description);
          $eventElement.append($eventDescription);
        }
        
        // Add click handler for event editing
        $eventElement.click(function() {
          // Get the current date from the sidebar
          var currentDateText = $('#selected-date').text();
          var datePart = currentDateText.split(' - ')[1] || currentDateText;
          var currentDate = moment(datePart, 'DD/MM/YYYY');
          
          // Prepare event data for edit mode
          var eventData = {
            id: event.id,
            title: event.title,
            start_date: currentDate.format('DD-MM-YYYY'),
            end_date: currentDate.format('DD-MM-YYYY'),
            color: event.color,
            time: event.time,
            description: event.description
          };
          
          // Use the new unified modal function for edit mode
          window.openUnifiedModalForEdit(eventData);
        });
        
        $hourContent.append($eventElement);
      });
    });
  }

  // Show timeline error message
  function showTimelineError(message) {
    $('.hour-content').html('<div class="timeline-error">' + message + '</div>');
  }

  // Initialize sidebar with today's date and load content
  updateSidebarDate();
  loadTimelineEvents(); // Load today's events

  // Simplified sidebar toggle functionality with inline styles
  window.toggleSidebar = function() {
    console.log('=== TOGGLE SIDEBAR FUNCTION CALLED ===');
    
    var $sidebar = $('#sidebar-container');
    
    if ($sidebar.length === 0) {
      console.error('ERROR: Sidebar element not found!');
      return false;
    }
    
    console.log('Sidebar element found');
    console.log('Current classes before toggle:', $sidebar.attr('class'));
    
    // Simple toggle logic with inline styles for maximum override
    if ($sidebar.hasClass('sidebar-collapsed')) {
      $sidebar.removeClass('sidebar-collapsed').addClass('sidebar-expanded');
      // Force expanded styles
      $sidebar.css({
        'width': '320px',
        'opacity': '1',
        'border-left': '2px solid #ccc',
        'min-width': '320px',
        'max-width': '320px'
      });
      console.log('ACTION: Expanding sidebar');
    } else {
      $sidebar.removeClass('sidebar-expanded').addClass('sidebar-collapsed');
      // Force collapsed styles
      $sidebar.css({
        'width': '0px',
        'opacity': '0',
        'border': 'none',
        'min-width': '0px',
        'max-width': '0px'
      });
      console.log('ACTION: Collapsing sidebar');
    }
    
    console.log('New classes after toggle:', $sidebar.attr('class'));
    
    // Force calendar resize
    setTimeout(function() {
      var $calendar = $('#calendar');
      if ($calendar.length > 0) {
        $calendar.fullCalendar('rerenderEvents');
        console.log('Calendar rerendered after toggle');
      }
    }, 500);
    
    return true;
  };

  // Ensure sidebar is visible on page load
  $(document).ready(function() {
    var $sidebar = $('#sidebar-container');
    if (!$sidebar.hasClass('sidebar-expanded') && !$sidebar.hasClass('sidebar-collapsed')) {
      $sidebar.addClass('sidebar-expanded');
    }
    console.log('Sidebar initialized with class:', $sidebar.attr('class'));
  });

  // Initialize FullCalendar with enhanced functionality
  $("#calendar").fullCalendar({
    header: {
      left: "prev,next today",
      center: "title",
      right: "month sidebarToggle" // Separated buttons with space
    },

    locale: 'es',
    defaultView: "month",
    navLinks: true, 
    editable: true,
    eventLimit: true, 
    selectable: true,
    selectHelper: false, // Disable select helper to avoid conflicts
    unselectAuto: true, // Auto-unselect when clicking elsewhere
    height: 'auto', // Better responsive behavior
    contentHeight: 'auto',
    fixedWeekCount: false, // Show only 5 weeks instead of 6
    showNonCurrentDates: true, // Show dates from other months

    // Custom button for sidebar toggle - Fixed approach
    customButtons: {
      sidebarToggle: {
        text: '☰',
        click: function(event) {
          console.log('FullCalendar button clicked - calling toggleSidebar');
          event.preventDefault();
          event.stopPropagation();
          
          // Call the working function directly
          if (typeof window.toggleSidebar === 'function') {
            window.toggleSidebar();
          } else {
            console.error('toggleSidebar function not available');
          }
        }
      }
    },

    // Enhanced select functionality - only for empty areas
    select: function(start, end, jsEvent, view){
      console.log('Select triggered - creating new event');
      
      // Use the new unified modal function for create mode
      window.openUnifiedModalForCreate();
      
      // Set the dates after modal opens
      setTimeout(function() {
        $("#fecha_inicio").val(start.format('DD-MM-YYYY'));
         
        var valorFechaFin = end.format("DD-MM-YYYY");
        var F_final = moment(valorFechaFin, "DD-MM-YYYY").subtract(1, 'days').format('DD-MM-YYYY');
        $('#fecha_fin').val(F_final);
        
        console.log('Dates set - start:', start.format('DD-MM-YYYY'), 'end:', F_final);
      }, 100);
      
      // Update sidebar for selected date with animation
      updateSidebarDate(start);
      loadTimelineEvents(start);
    },

    // Enhanced day click handler for sidebar update (but not modal opening)
    dayClick: function(date, jsEvent, view) {
      console.log('Day click - updating sidebar only');
      
      // Only update sidebar, don't open modal (modal is handled by select)
      $('.fc-day').removeClass('selected-day');
      $(jsEvent.target).closest('.fc-day').addClass('selected-day');
      
      // Update sidebar with smooth transition
      updateSidebarDate(date);
      loadTimelineEvents(date);
      
      // Ensure sidebar is visible on mobile when day is selected
      if (window.innerWidth <= 768) {
        $('#sidebar-container').removeClass('sidebar-collapsed');
      }
    },

    // Enhanced view render for better responsive behavior
    viewRender: function(view, element) {
      // Adjust calendar height based on sidebar state
      var $sidebar = $('#sidebar-container');
      if (window.innerWidth <= 768 && !$sidebar.hasClass('sidebar-collapsed')) {
        $('#calendar').fullCalendar('option', 'height', 'auto');
      }
    },
      
    events: [
      <?php
       // Load regular events
       while($dataEvento = mysqli_fetch_array($resulEventos)){ ?>
          {
          _id: 'event_<?php echo $dataEvento['id']; ?>',
          title: '<?php echo addslashes($dataEvento['evento']); ?>',
          start: '<?php echo $dataEvento['fecha_inicio']; ?>',
          end:   '<?php echo $dataEvento['fecha_fin']; ?>',
          color: '<?php echo $dataEvento['color_evento']; ?>',
          type: 'event'
          },
        <?php } ?>
        
        <?php
        // Load birthdays for current year
        while($dataBirthday = mysqli_fetch_array($resulBirthdays)){ 
          $birthdayDate = $currentYear . '-' . sprintf('%02d', $dataBirthday['mes_nacimiento']) . '-' . sprintf('%02d', $dataBirthday['dia_nacimiento']);
        ?>
          {
          _id: 'birthday_<?php echo $dataBirthday['id']; ?>',
          title: '🎂 <?php echo addslashes($dataBirthday['nombre']); ?>',
          start: '<?php echo $birthdayDate; ?>',
          end: '<?php echo $birthdayDate; ?>',
          color: '#FF69B4',
          type: 'birthday',
          allDay: true
          },
        <?php } ?>
    ],

    // Event rendering with enhanced display and click handling
    eventRender: function(event, element) {
      // Enhanced tooltip and click behavior
      if (event.type === 'birthday') {
        element.attr('title', 'Cumpleaños: ' + event.title.replace('🎂 ', ''));
        element.css('cursor', 'pointer');
      } else {
        element.attr('title', 'Evento: ' + event.title + ' (Haz clic para editar)');
        element.css('cursor', 'pointer');
      }
      
      // Add a class to make events more clickable
      element.addClass('clickable-event');
      
      // Ensure event has proper z-index to be clickable
      element.css('z-index', '999');
    },

    // Enhanced drag and drop with better feedback
    eventDrop: function (event, delta) {
      var idEvento = event._id;
      var start = (event.start.format('DD-MM-YYYY'));
      var end = (event.end.format("DD-MM-YYYY"));

      // Add visual feedback during drag
      $(event.element).addClass('dragging');

      $.ajax({
          url: '../PHP/drag_drop_evento.php',
          data: 'start=' + start + '&end=' + end + '&idEvento=' + idEvento,
          type: "POST",
          success: function (response) {
            $(event.element).removeClass('dragging');
            // Update sidebar if the moved event affects the selected date
            var selectedDate = moment($('#selected-date').text().split(' - ')[1], 'DD/MM/YYYY');
            if (event.start.isSame(selectedDate, 'day')) {
              loadTimelineEvents(selectedDate);
            }
          },
          error: function() {
            $(event.element).removeClass('dragging');
            // Revert the event if the update failed
            $('#calendar').fullCalendar('rerenderEvents');
          }
      });
    },

    // Enhanced event click with sidebar integration - PRIORITY OVER SELECT
    eventClick: function(event, jsEvent, view){
      console.log('Event clicked:', event);
      
      // Prevent event bubbling to avoid triggering select
      jsEvent.stopPropagation();
      jsEvent.preventDefault();
      
      // Check if it's a birthday or regular event
      if (event.type === 'birthday') {
        // Handle birthday click - open modal for editing
        var birthdayId = event._id.replace('birthday_', ''); // Remove 'birthday_' prefix
        var birthdayName = event.title.replace('🎂 ', ''); // Remove cake emoji
        
        // Extract day and month from the event date
        var birthdayDate = moment(event.start);
        var day = birthdayDate.date();
        var month = birthdayDate.month() + 1; // moment months are 0-based
        
        var birthdayData = {
          id: birthdayId,
          name: birthdayName,
          day: day,
          month: month,
          date: birthdayDate.format('YYYY-MM-DD')
        };
        
        console.log('Opening edit modal for birthday:', birthdayData);
        
        // Use the new unified modal function for birthday edit mode
        window.openUnifiedModalForBirthdayEdit(birthdayData);
        
        return false; // Prevent further event handling
      }
      
      // Handle regular event click
      var eventId = event._id.replace('event_', ''); // Remove 'event_' prefix
      var eventData = {
        id: eventId,
        title: event.title,
        start_date: event.start.format('DD-MM-YYYY'),
        end_date: event.end ? event.end.format('DD-MM-YYYY') : event.start.format('DD-MM-YYYY'),
        color: event.color
      };
      
      console.log('Opening edit modal for event:', eventData);
      
      // Use the new unified modal function for edit mode
      window.openUnifiedModalForEdit(eventData);

      // Update sidebar to show the event's date
      updateSidebarDate(event.start);
      loadTimelineEvents(event.start);
      
      return false; // Prevent further event handling
    }
  });

  // Handle window resize for responsive behavior
  $(window).resize(function() {
    var $sidebar = $('#sidebar-container');
    
    // Auto-collapse sidebar on small screens
    if (window.innerWidth <= 768 && !$sidebar.hasClass('sidebar-collapsed')) {
      // Don't auto-collapse, let user control it
    }
    
    // Rerender calendar after resize
    setTimeout(function() {
      $('#calendar').fullCalendar('rerenderEvents');
    }, 300);
  });

  // Hide notification messages with enhanced animation
  setTimeout(function () {
    $(".alert").slideUp(300);
  }, 3000);

  // Add smooth scrolling to timeline
  $('.sidebar-content').on('scroll', function() {
    // Add scroll position indicator or other enhancements here
  });

  // Add keyboard navigation support
  $(document).keydown(function(e) {
    // Toggle sidebar with Ctrl+B
    if (e.ctrlKey && e.keyCode === 66) {
      e.preventDefault();
      window.toggleSidebar();
    }
  });
  
  // Ensure sidebar toggle button works after FullCalendar initialization
  setTimeout(function() {
    console.log('=== INITIALIZING SIDEBAR TOGGLE ===');
    
    // Test if sidebar exists
    var $sidebar = $('#sidebar-container');
    console.log('Sidebar exists:', $sidebar.length > 0);
    console.log('Initial sidebar classes:', $sidebar.attr('class'));
    
    // Test if toggle function exists
    console.log('toggleSidebar function exists:', typeof window.toggleSidebar === 'function');
    
    // Find and test the button
    var $toggleBtn = $('.fc-sidebarToggle-button');
    console.log('Toggle button found:', $toggleBtn.length > 0);
    
    if ($toggleBtn.length > 0) {
      console.log('Adding direct click handler to toggle button');
      
      // Remove any existing handlers and add new one
      $toggleBtn.off('click.sidebar').on('click.sidebar', function(e) {
        console.log('=== DIRECT FULLCALENDAR BUTTON CLICK ===');
        e.preventDefault();
        e.stopPropagation();
        e.stopImmediatePropagation();
        
        // Call the working function
        window.toggleSidebar();
        
        return false;
      });
      
      // Also try with mousedown event as backup
      $toggleBtn.off('mousedown.sidebar').on('mousedown.sidebar', function(e) {
        console.log('=== MOUSEDOWN ON FULLCALENDAR BUTTON ===');
        e.preventDefault();
        window.toggleSidebar();
        return false;
      });
    }
    
    // Remove test button since the function works
    $('#test-toggle').remove();
    
  }, 3000); // Wait 3 seconds for everything to load completely
});
</script>

<style>
/* Estilos adicionales para mejorar la interacción con eventos */
.clickable-event {
  cursor: pointer !important;
  transition: transform 0.1s ease, box-shadow 0.1s ease;
}

.clickable-event:hover {
  transform: scale(1.02);
  box-shadow: 0 2px 8px rgba(0,0,0,0.2) !important;
  z-index: 1000 !important;
}

.fc-event {
  border: 1px solid rgba(255,255,255,0.3) !important;
  font-weight: 500;
}

.fc-event:hover {
  opacity: 0.9;
}

/* Mejorar la selección de días */
.selected-day {
  background-color: rgba(0, 123, 255, 0.1) !important;
}

/* Asegurar que los eventos sean clickeables en móvil */
@media (max-width: 768px) {
  .fc-event {
    min-height: 20px;
    padding: 2px 4px;
  }
  
  .clickable-event {
    touch-action: manipulation;
  }
}
</style>
</body>
</html>