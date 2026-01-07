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
    <link rel="stylesheet" type="text/css" href="../css/home.css">
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
    <div id="sidebar-container" class="sidebar-expanded">
        <div id="sidebar-header">
            <h3 id="selected-date" class="sidebar-title">Hoy</h3>
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
<?php  
  include('../PHP/modalNuevoEvento.php');
  include('../PHP/modalUpdateEvento.php');
?>

<script src ="../js/jquery-3.0.0.min.js"> </script>
<script src="../js/popper.min.js"></script>
<script src="../js/bootstrap.min.js"></script>
<script type="text/javascript" src="../js/moment.min.js"></script>	
<script type="text/javascript" src="../js/fullcalendar.min.js"></script>
<script src='../locales/es.js'></script>
<script type="text/javascript">
$(document).ready(function() {

  // Enhanced sidebar date display with better formatting
  function updateSidebarDate(date) {
    var formattedDate;
    var today = moment();
    
    if (date) {
      var selectedMoment = moment(date);
      if (selectedMoment.isSame(today, 'day')) {
        formattedDate = 'Hoy - ' + selectedMoment.format('DD/MM/YYYY');
      } else if (selectedMoment.isSame(today.clone().add(1, 'day'), 'day')) {
        formattedDate = 'Mañana - ' + selectedMoment.format('DD/MM/YYYY');
      } else if (selectedMoment.isSame(today.clone().subtract(1, 'day'), 'day')) {
        formattedDate = 'Ayer - ' + selectedMoment.format('DD/MM/YYYY');
      } else {
        formattedDate = selectedMoment.format('dddd, DD/MM/YYYY');
      }
    } else {
      formattedDate = 'Hoy - ' + today.format('DD/MM/YYYY');
    }
    
    $('#selected-date').text(formattedDate);
  }

  // Function to load and display events in timeline
  function loadTimelineEvents(date) {
    // Clear existing timeline content
    $('.hour-content').empty();
    
    // TODO: This will be implemented when we create the PHP endpoint
    // For now, we'll add placeholder functionality
    console.log('Loading events for date:', date ? date.format('YYYY-MM-DD') : 'today');
  }

  // Initialize sidebar with today's date
  updateSidebarDate();

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
    selectHelper: false,
    height: 'auto', // Better responsive behavior
    contentHeight: 'auto',
    fixedWeekCount: false, // Show only 5 weeks instead of 6
    showNonCurrentDates: true, // Show dates from other months

    // Custom button for sidebar toggle
    customButtons: {
      sidebarToggle: {
        text: 'Sidebar',
        click: function() {
          var $sidebar = $('#sidebar-container');
          
          // Add smooth transition class if not present
          if (!$sidebar.hasClass('transitioning')) {
            $sidebar.addClass('transitioning');
          }
          
          // Toggle sidebar state
          if ($sidebar.hasClass('sidebar-collapsed')) {
            $sidebar.removeClass('sidebar-collapsed');
          } else {
            $sidebar.addClass('sidebar-collapsed');
          }
          
          // Force calendar resize after sidebar animation completes
          setTimeout(function() {
            $('#calendar').fullCalendar('rerenderEvents');
          }, 400);
        }
      }
    },

    // Enhanced select functionality
    select: function(start, end){
      $("#exampleModal").modal();
      $("input[name=fecha_inicio]").val(start.format('DD-MM-YYYY'));
       
      var valorFechaFin = end.format("DD-MM-YYYY");
      var F_final = moment(valorFechaFin, "DD-MM-YYYY").subtract(1, 'days').format('DD-MM-YYYY');
      $('input[name=fecha_fin').val(F_final);
      
      // Update sidebar for selected date with animation
      updateSidebarDate(start);
      loadTimelineEvents(start);
    },

    // Enhanced day click handler for sidebar update
    dayClick: function(date, jsEvent, view) {
      // Add visual feedback to clicked day
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
       while($dataEvento = mysqli_fetch_array($resulEventos)){ ?>
          {
          _id: '<?php echo $dataEvento['id']; ?>',
          title: '<?php echo $dataEvento['evento']; ?>',
          start: '<?php echo $dataEvento['fecha_inicio']; ?>',
          end:   '<?php echo $dataEvento['fecha_fin']; ?>',
          color: '<?php echo $dataEvento['color_evento']; ?>'
          },
        <?php } ?>
    ],

    // Event rendering with enhanced display
    eventRender: function(event, element) {
      // X icon functionality removed - deletion will be handled in modal
      // Add enhanced tooltip or click behavior here if needed
      element.attr('title', event.title);
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

    // Enhanced event click with sidebar integration
    eventClick: function(event){
      var idEvento = event._id;
      $('input[name=idEvento').val(idEvento);
      $('input[name=evento').val(event.title);
      $('input[name=fecha_inicio').val(event.start.format('DD-MM-YYYY'));
      $('input[name=fecha_fin').val(event.end.format("DD-MM-YYYY"));

      // Update sidebar to show the event's date
      updateSidebarDate(event.start);
      loadTimelineEvents(event.start);

      $("#modalUpdateEvento").modal();
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
      $('#sidebar-toggle').click();
    }
  });
});
</script>
</body>
</html>