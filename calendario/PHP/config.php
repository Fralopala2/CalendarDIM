<?php
/**
 * Database Configuration and Event Loading
 * 
 * Enhanced to include new fields (hora_inicio, descripcion) in event queries
 * Requirements: 3.6, 4.4
 */

$usuario  = "root";
$password = "root";
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
?>

