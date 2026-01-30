<?php
require_once('config.php');
require_once('EventManager.php');

header('Content-Type: application/json');

$id = isset($_REQUEST['id']) ? (int)$_REQUEST['id'] : 0;

if ($id <= 0) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'ID de evento inválido']);
    exit;
}

// Initialize EventManager
$eventManager = new EventManager($con);

// Use the deleteRecurringEvent method which handles both regular and recurring events
$result = $eventManager->deleteRecurringEvent($id);

if ($result['success']) {
    http_response_code(200);
    echo json_encode($result);
} else {
    http_response_code(400);
    echo json_encode($result);
}

exit;
?>