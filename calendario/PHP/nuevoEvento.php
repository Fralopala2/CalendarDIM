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

// Check if this is a recurring event
$esRecurrente = isset($_POST['es_recurrente']) && $_POST['es_recurrente'] == 1;

if ($esRecurrente) {
    // Handle recurring event
    $diasSemana = isset($_POST['dias']) ? $_POST['dias'] : [];
    $fechaFinRecurrencia = isset($_POST['fecha_fin_recurrencia']) ? $_POST['fecha_fin_recurrencia'] : '';
    
    if (empty($diasSemana)) {
        header('Content-Type: application/json');
        http_response_code(400);
        echo json_encode(['success' => false, 'error' => 'Debes seleccionar al menos un día de la semana']);
        exit;
    }
    
    if (empty($fechaFinRecurrencia)) {
        header('Content-Type: application/json');
        http_response_code(400);
        echo json_encode(['success' => false, 'error' => 'Debes especificar hasta qué fecha se repetirá el evento']);
        exit;
    }
    
    // Generate a unique group ID for this recurring event series
    $recurringGroupId = uniqid('recurring_', true);
    
    // Create base event data
    $baseEventData = [
        'evento' => isset($_POST['evento']) ? $_POST['evento'] : '',
        'color_evento' => isset($_POST['color_evento']) ? $_POST['color_evento'] : '#007bff',
        'hora_inicio' => isset($_POST['hora_inicio']) ? $_POST['hora_inicio'] : null,
        'descripcion' => isset($_POST['descripcion']) ? $_POST['descripcion'] : null,
        'es_recurrente' => 1,
        'recurring_group_id' => $recurringGroupId
    ];
    
    $fechaInicio = isset($_POST['fecha_inicio']) ? $_POST['fecha_inicio'] : '';
    
    // Generate recurring instances
    $createdCount = 0;
    $errors = [];
    
    $currentDate = new DateTime($fechaInicio);
    $endDate = new DateTime($fechaFinRecurrencia);
    
    // Limit to 1 year to prevent excessive instances
    $maxDate = (clone $currentDate)->modify('+1 year');
    if ($endDate > $maxDate) {
        $endDate = $maxDate;
    }
    
    while ($currentDate <= $endDate) {
        $dayOfWeek = $currentDate->format('w'); // 0 = Sunday, 1 = Monday, etc.
        
        if (in_array($dayOfWeek, $diasSemana)) {
            // Create instance for this day
            $instanceData = $baseEventData;
            $instanceData['fecha_inicio'] = $currentDate->format('Y-m-d');
            $instanceData['fecha_fin'] = $currentDate->format('Y-m-d'); // Same day, not +1
            
            $result = $eventManager->saveEvent($instanceData);
            
            if ($result['success']) {
                $createdCount++;
            } else {
                $errors[] = $result['error'];
            }
        }
        
        $currentDate->modify('+1 day');
    }
    
    // Send response
    header('Content-Type: application/json');
    
    if ($createdCount > 0) {
        http_response_code(200);
        echo json_encode([
            'success' => true, 
            'message' => "Se crearon $createdCount instancias del evento recurrente",
            'created_count' => $createdCount
        ]);
    } else {
        http_response_code(400);
        echo json_encode([
            'success' => false, 
            'error' => 'No se pudo crear ninguna instancia del evento',
            'details' => $errors
        ]);
    }
    
} else {
    // Handle normal event (existing code)
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
        http_response_code(200);
        echo json_encode(['success' => true, 'message' => 'Evento creado correctamente', 'event_id' => $result['event_id']]);
    } else {
        http_response_code(400);
        $response = ['success' => false, 'error' => $result['error']];
        if (isset($result['details']) && !empty($result['details'])) {
            $response['details'] = $result['details'];
        }
        echo json_encode($response);
    }
}

exit;
?>