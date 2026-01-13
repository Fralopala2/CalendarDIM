<?php

date_default_timezone_set("Europe/Madrid");
setlocale(LC_ALL,"es_ES");

require("config.php");
require("EventManager.php");

// Log para debugging
error_log("=== nuevoEvento.php llamado ===");
error_log("POST data: " . json_encode($_POST));

// Initialize EventManager
$eventManager = new EventManager($con);

// Collect form data including new fields
$eventData = [
    'evento' => isset($_REQUEST['evento']) ? $_REQUEST['evento'] : '',
    'fecha_inicio' => isset($_REQUEST['fecha_inicio']) ? $_REQUEST['fecha_inicio'] : '',
    'fecha_fin' => isset($_REQUEST['fecha_fin']) ? $_REQUEST['fecha_fin'] : '',
    'color_evento' => isset($_REQUEST['color_evento']) ? $_REQUEST['color_evento'] : '#007bff',
    'hora_inicio' => isset($_REQUEST['hora_inicio']) ? $_REQUEST['hora_inicio'] : null,
    'descripcion' => isset($_REQUEST['descripcion']) ? $_REQUEST['descripcion'] : null
];

error_log("Event data prepared: " . json_encode($eventData));

// Use EventManager to save the event
$result = $eventManager->saveEvent($eventData);

error_log("Save result: " . json_encode($result));

// Send response
header('Content-Type: application/json');

if ($result['success']) {
    // Success - return JSON response for AJAX
    http_response_code(200);
    echo json_encode(['success' => true, 'message' => 'Evento creado correctamente', 'event_id' => $result['event_id']]);
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