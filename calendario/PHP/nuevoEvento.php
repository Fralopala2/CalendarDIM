<?php

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
    // Success - return JSON response for AJAX
    echo json_encode(['success' => true, 'message' => 'Evento creado correctamente']);
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