<?php

// Database configuration
$usuario  = "calendario";
$password = "calendario123";
$servidor = "localhost";
$basededatos = "calendario";

// Database connection with better error handling
$con = mysqli_connect($servidor, $usuario, $password);

if (!$con) {
    // If custom user fails, try root (for development)
    $con = @mysqli_connect($servidor, "root", "");
    if (!$con) {
        die("❌ Error de conexión: No se pudo conectar a la base de datos MySQL. 
             Verifica que MySQL esté corriendo y las credenciales sean correctas.
             Error: " . mysqli_connect_error());
    }
}

$db = @mysqli_select_db($con, $basededatos);
if (!$db) {
    die("❌ Error: No se pudo seleccionar la base de datos '$basededatos'. 
         La base de datos podría no existir. Ejecuta el script de instalación.");
}

// Enhanced SQL query to include new fields for event display
// Requirement 3.6: Modify event queries to include new fields
// Requirement 4.4: Show both time and title in calendar display
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
// Requirement 2.3: Load birthdays for calendar month display
// Requirement 2.4: Display birthdays with name and cake emoji
$currentYear = date('Y');
$SqlBirthdays = ("SELECT id, nombre, dia_nacimiento, mes_nacimiento, color_evento, fecha_cumpleanios FROM cumpleanos ORDER BY mes_nacimiento ASC, dia_nacimiento ASC");
$resulBirthdays = mysqli_query($con, $SqlBirthdays);

// Create a copy of the birthday result for multiple iterations
$birthdaysArray = [];
if ($resulBirthdays) {
    while($row = mysqli_fetch_assoc($resulBirthdays)) {
        $birthdaysArray[] = $row;
    }
}
?>