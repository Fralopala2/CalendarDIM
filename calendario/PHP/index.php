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
<p align="center"><img src="../IMAGES/ImagenAgenda.svg" width="100%"></p>
<?php
include('config.php');
?>

<div id="calendar"></div>
<?php  
  include('modalNuevoEvento.php');
  include('modalUpdateEvento.php');
?>

<script src ="../js/jquery-3.0.0.min.js"> </script>
<script src="../js/popper.min.js"></script>
<script src="../js/bootstrap.min.js"></script>
<script type="text/javascript" src="../js/moment.min.js"></script>	
<script type="text/javascript" src="../js/fullcalendar.min.js"></script>
<script src='../locales/es.js'></script>
<script type="text/javascript">
$(document).ready(function() {
  $("#calendar").fullCalendar({
    header: {
      left: "prev,next today",
      center: "title",
      right: "month" // Removed agendaWeek,agendaDay as per requirements 4.2
    },

    locale: 'es',

    defaultView: "month",
    navLinks: true, 
    editable: true,
    eventLimit: true, 
    selectable: true,
    selectHelper: false,

//Nuevo Evento
  select: function(start, end){
      $("#exampleModal").modal();
      $("input[name=fecha_inicio]").val(start.format('DD-MM-YYYY'));
       
      var valorFechaFin = end.format("DD-MM-YYYY");
      var F_final = moment(valorFechaFin, "DD-MM-YYYY").subtract(1, 'days').format('DD-MM-YYYY'); //Le resto 1 dia
      $('input[name=fecha_fin').val(F_final);
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


//Eliminar Evento - Removed X icon as per requirements 4.1
eventRender: function(event, element) {
    // X icon functionality removed - deletion will be handled in modal
  },


//Moviendo Evento Drag - Drop
eventDrop: function (event, delta) {
  var idEvento = event._id;
  var start = (event.start.format('DD-MM-YYYY'));
  var end = (event.end.format("DD-MM-YYYY"));

    $.ajax({
        url: 'drag_drop_evento.php',
        data: 'start=' + start + '&end=' + end + '&idEvento=' + idEvento,
        type: "POST",
        success: function (response) {
         // $("#respuesta").html(response);
        }
    });
},

//Modificar Evento del Calendario 
eventClick:function(event){
    var idEvento = event._id;
    $('input[name=idEvento').val(idEvento);
    $('input[name=evento').val(event.title);
    $('input[name=fecha_inicio').val(event.start.format('DD-MM-YYYY'));
    $('input[name=fecha_fin').val(event.end.format("DD-MM-YYYY"));

    $("#modalUpdateEvento").modal();
  },


  });
//Oculta mensajes de Notificacion
  setTimeout(function () {
    $(".alert").slideUp(300);
  }, 3000); 
});
</script>
</body>
</html>