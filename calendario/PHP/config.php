<?php
/**
 * Database Configuration and Event Loading
 * 
 * Enhanced to include new fields (hora_inicio, descripcion) in event queries
 * and birthday loading for calendar display
 * Requirements: 3.6, 4.4, 2.3, 2.4
 */

$usuario  = "root";
$password = "";
$servidor = "localhost";
$basededatos = "calendario";

// Database connection
$con = mysqli_connect($servidor, $usuario, $password) or die("No se ha podido conectar al Servidor");
$db = mysqli_select_db($con, $basededatos) or die("Upps! Error en conectar a la Base de Datos");

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
$SqlBirthdays = ("SELECT id, nombre, dia_nacimiento, mes_nacimiento FROM cumpleanos ORDER BY mes_nacimiento ASC, dia_nacimiento ASC");
$resulBirthdays = mysqli_query($con, $SqlBirthdays);

// Create a copy of the birthday result for multiple iterations
$birthdaysArray = [];
if ($resulBirthdays) {
    while($row = mysqli_fetch_assoc($resulBirthdays)) {
        $birthdaysArray[] = $row;
    }
}
?>

