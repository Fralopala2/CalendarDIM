<?php
/**
 * Enhanced Event Update Handler
 * 
 * Handles updating existing events with time and description support
 * Uses EventManager class for unified save/update logic
 * 
 * Requirements: 3.4, 3.6, 4.4
 */

date_default_timezone_set("Europe/Madrid");
setlocale(LC_ALL,"es_ES");

include('config.php');
require("EventManager.php");

// Initialize EventManager
$eventManager = new EventManager($con);

// Collect form data including ID for update operation
$eventData = [
    'id' => $_POST['event_id'], // ID indicates this is an update (changed from idEvento to event_id)
    'evento' => $_REQUEST['evento'],
    'fecha_inicio' => $_REQUEST['fecha_inicio'],
    'fecha_fin' => $_REQUEST['fecha_fin'],
    'color_evento' => $_REQUEST['color_evento'],
    'hora_inicio' => isset($_REQUEST['hora_inicio']) ? $_REQUEST['hora_inicio'] : null,
    'descripcion' => isset($_REQUEST['descripcion']) ? $_REQUEST['descripcion'] : null
];

// Use EventManager unified save logic (will update because ID is provided)
$result = $eventManager->saveEvent($eventData);

if ($result['success']) {
    // Success - return JSON response for AJAX
    echo json_encode(['success' => true, 'message' => 'Evento actualizado correctamente']);
} else {
    // Error - return JSON error for AJAX with details
    http_response_code(400);
    $response = ['success' => false, 'error' => $result['error']];
    if (isset($result['details']) && !empty($result['details'])) {
        $response['details'] = $result['details'];
    }
    echo json_encode($response);
}

exit;
?>