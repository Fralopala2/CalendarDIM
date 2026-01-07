<?php
/**
 * Enhanced Event Creation Handler
 * 
 * Handles creation of new events with time and description support
 * Uses EventManager class for unified CRUD operations
 * 
 * Requirements: 3.4, 3.6, 4.4
 */

date_default_timezone_set("Europe/Madrid");
setlocale(LC_ALL,"es_ES");

require("config.php");
require("EventManager.php");

// Initialize EventManager
$eventManager = new EventManager($con);

// Collect form data including new fields
$eventData = [
    'evento' => $_REQUEST['evento'],
    'fecha_inicio' => $_REQUEST['fecha_inicio'],
    'fecha_fin' => $_REQUEST['fecha_fin'],
    'color_evento' => $_REQUEST['color_evento'],
    'hora_inicio' => isset($_REQUEST['hora_inicio']) ? $_REQUEST['hora_inicio'] : null,
    'descripcion' => isset($_REQUEST['descripcion']) ? $_REQUEST['descripcion'] : null
];

// Use EventManager to save the event
$result = $eventManager->saveEvent($eventData);

if ($result['success']) {
    // Success - redirect to main page
    header("Location:index.php?e=1");
} else {
    // Error - redirect with error parameter
    error_log("Event creation failed: " . $result['error']);
    header("Location:index.php?error=1");
}

exit;
?>