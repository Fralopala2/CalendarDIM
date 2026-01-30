<?php

// Database configuration
$usuario  = "root";
$password = "root";
$servidor = "localhost";
$basededatos = "calendario";

// Database connection with better error handling
$con = mysqli_connect($servidor, $usuario, $password);

if (!$con) {
    die("Error de conexion: No se pudo conectar a la base de datos MySQL. 
         Verifica que MySQL este corriendo y las credenciales sean correctas.
         Error: " . mysqli_connect_error());
}

$db = @mysqli_select_db($con, $basededatos);
if (!$db) {
    die("Error: No se pudo seleccionar la base de datos '$basededatos'. 
         La base de datos podria no existir. Ejecuta el script de instalacion.");
}

// Enhanced SQL query to include new fields for event display
$SqlEventos = ("SELECT id, evento, fecha_inicio, fecha_fin, color_evento, hora_inicio, descripcion FROM eventoscalendar ORDER BY fecha_inicio ASC, hora_inicio ASC");
$resulEventos = mysqli_query($con, $SqlEventos);

// Create a copy of the result for multiple iterations
$eventosArray = [];
if ($resulEventos) {
    while($row = mysqli_fetch_assoc($resulEventos)) {
        $eventosArray[] = $row;
    }
}

// Load birthdays for current year to display in calendar
$currentYear = date('Y');
$SqlBirthdays = ("SELECT id, nombre, dia_nacimiento, mes_nacimiento, color_cumpleanos FROM cumpleañoscalendar ORDER BY mes_nacimiento ASC, dia_nacimiento ASC");
$resulBirthdays = mysqli_query($con, $SqlBirthdays);

// Create a copy of the birthday result for multiple iterations
$birthdaysArray = [];
if ($resulBirthdays) {
    while($row = mysqli_fetch_assoc($resulBirthdays)) {
        $birthdaysArray[] = $row;
    }
}
?>