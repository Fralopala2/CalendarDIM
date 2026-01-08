<?php
/**
 * PHP Endpoint for Sidebar Content
 * Serves sidebar timeline content via AJAX for selected dates
 * 
 * Requirements: 1.2, 1.3, 1.4, 1.7, 1.8, 1.9
 */

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');
header('Access-Control-Allow-Headers: Content-Type');

require_once 'config.php';
require_once 'EventManager.php';
require_once 'BirthdayManager.php';

// Initialize managers
global $con;
$eventManager = new EventManager($con);
$birthdayManager = new BirthdayManager($con);

// Get the selected date from request
$selectedDate = isset($_GET['date']) ? $_GET['date'] : date('Y-m-d');

// Validate date format
if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $selectedDate)) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid date format. Use YYYY-MM-DD']);
    exit;
}

try {
    // Get events for the selected date
    $events = $eventManager->getEventsForDate($selectedDate);
    
    // Get birthdays for the selected date
    $birthdays = $birthdayManager->getBirthdaysForDate($selectedDate);
    
    // Organize events chronologically
    usort($events, function($a, $b) {
        $timeA = $a['hora_inicio'] ?? '00:00:00';
        $timeB = $b['hora_inicio'] ?? '00:00:00';
        return strcmp($timeA, $timeB);
    });
    
    // Generate timeline content for 24 hours
    $timelineContent = [];
    
    for ($hour = 0; $hour < 24; $hour++) {
        $hourSlot = [
            'hour' => $hour,
            'hour_label' => sprintf('%02d:00', $hour),
            'events' => [],
            'birthdays' => []
        ];
        
        // Add events for this hour
        foreach ($events as $event) {
            $hora_inicio = $event['hora_inicio'] ?? '00:00:00';
            if ($hora_inicio) {
                $eventHour = (int)explode(':', $hora_inicio)[0];
                if ($eventHour === $hour) {
                    $hourSlot['events'][] = [
                        'id' => $event['id'],
                        'title' => $event['evento'],
                        'time' => substr($hora_inicio, 0, 5), // HH:MM format
                        'description' => $event['descripcion'] ?? '',
                        'color' => $event['color_evento'] ?? '#007bff'
                    ];
                }
            } else {
                // If no time specified, show at hour 0
                if ($hour === 0) {
                    $hourSlot['events'][] = [
                        'id' => $event['id'],
                        'title' => $event['evento'],
                        'time' => '00:00',
                        'description' => $event['descripcion'] ?? '',
                        'color' => $event['color_evento'] ?? '#007bff'
                    ];
                }
            }
        }
        
        // Add birthdays (show at top of timeline, hour 0)
        if ($hour === 0) {
            foreach ($birthdays as $birthday) {
                $hourSlot['birthdays'][] = [
                    'id' => $birthday['id'],
                    'name' => $birthday['nombre'],
                    'display' => '🎂 ' . $birthday['nombre']
                ];
            }
        }
        
        $timelineContent[] = $hourSlot;
    }
    
    // Prepare response
    $response = [
        'success' => true,
        'date' => $selectedDate,
        'formatted_date' => formatDateForDisplay($selectedDate),
        'timeline' => $timelineContent,
        'summary' => [
            'total_events' => count($events),
            'total_birthdays' => count($birthdays),
            'has_activities' => (count($events) > 0 || count($birthdays) > 0)
        ]
    ];
    
    echo json_encode($response, JSON_UNESCAPED_UNICODE);
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => 'Server error: ' . $e->getMessage()
    ]);
}

/**
 * Format date for display in sidebar header
 */
function formatDateForDisplay($date) {
    $dateObj = new DateTime($date);
    $today = new DateTime();
    $tomorrow = new DateTime('+1 day');
    $yesterday = new DateTime('-1 day');
    
    if ($dateObj->format('Y-m-d') === $today->format('Y-m-d')) {
        return 'Hoy - ' . $dateObj->format('d/m/Y');
    } elseif ($dateObj->format('Y-m-d') === $tomorrow->format('Y-m-d')) {
        return 'Mañana - ' . $dateObj->format('d/m/Y');
    } elseif ($dateObj->format('Y-m-d') === $yesterday->format('Y-m-d')) {
        return 'Ayer - ' . $dateObj->format('d/m/Y');
    } else {
        // Set locale for Spanish day names
        $dayNames = [
            'Sunday' => 'Domingo',
            'Monday' => 'Lunes', 
            'Tuesday' => 'Martes',
            'Wednesday' => 'Miércoles',
            'Thursday' => 'Jueves',
            'Friday' => 'Viernes',
            'Saturday' => 'Sábado'
        ];
        
        $englishDay = $dateObj->format('l');
        $spanishDay = $dayNames[$englishDay] ?? $englishDay;
        
        return $spanishDay . ', ' . $dateObj->format('d/m/Y');
    }
}
?>