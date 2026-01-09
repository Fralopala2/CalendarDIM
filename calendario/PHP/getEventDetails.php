<?php

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');
header('Access-Control-Allow-Headers: Content-Type');

require_once 'config.php';
require_once 'EventManager.php';

// Initialize EventManager
global $con;
$eventManager = new EventManager($con);

// Get event ID from request
$eventId = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($eventId <= 0) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'error' => 'Invalid event ID'
    ]);
    exit;
}

try {
    // Get event details
    $event = $eventManager->getEventById($eventId);
    
    if (!$event) {
        http_response_code(404);
        echo json_encode([
            'success' => false,
            'error' => 'Event not found'
        ]);
        exit;
    }
    
    // Return complete event data
    echo json_encode([
        'success' => true,
        'event' => $event
    ], JSON_UNESCAPED_UNICODE);
    
} catch (Exception $e) {
    error_log("getEventDetails.php Error: " . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => 'Server error occurred'
    ]);
}
?>