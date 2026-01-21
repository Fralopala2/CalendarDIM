<?php

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');
header('Access-Control-Allow-Headers: Content-Type');

require_once 'config.php';

$selectedDate = isset($_GET['date']) ? $_GET['date'] : date('Y-m-d');

if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $selectedDate)) {
    http_response_code(400);
    echo json_encode(['error' => 'Formato de fecha invalido']);
    exit;
}

try {
    global $con;
    
    $events = [];
    
    $sql = "SELECT id, evento, fecha_inicio, fecha_fin, color_evento, hora_inicio, descripcion 
            FROM eventoscalendar 
            WHERE fecha_inicio <= ? AND fecha_fin > ?
            ORDER BY hora_inicio ASC, evento ASC";
    
    $stmt = mysqli_prepare($con, $sql);
    mysqli_stmt_bind_param($stmt, 'ss', $selectedDate, $selectedDate);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    while ($row = mysqli_fetch_assoc($result)) {
        $events[] = [
            'id' => $row['id'],
            'title' => $row['evento'],
            'time' => $row['hora_inicio'] ? substr($row['hora_inicio'], 0, 5) : null,
            'description' => $row['descripcion'] ?? '',
            'color' => $row['color_evento'] ?? '#007bff',
            'type' => 'event'
        ];
    }
    
    $dateObj = new DateTime($selectedDate);
    $day = (int)$dateObj->format('d');
    $month = (int)$dateObj->format('m');
    
    $sqlBirthdays = "SELECT id, nombre, color_cumpleanos 
                     FROM cumpleanoscalendar 
                     WHERE dia_nacimiento = ? AND mes_nacimiento = ?";
    
    $stmtBirthdays = mysqli_prepare($con, $sqlBirthdays);
    mysqli_stmt_bind_param($stmtBirthdays, 'ii', $day, $month);
    mysqli_stmt_execute($stmtBirthdays);
    $resultBirthdays = mysqli_stmt_get_result($stmtBirthdays);
    
    $birthdays = [];
    while ($row = mysqli_fetch_assoc($resultBirthdays)) {
        $birthdays[] = [
            'id' => $row['id'],
            'name' => $row['nombre'],
            'color' => $row['color_cumpleanos'] ?? '#FF69B4',
            'type' => 'birthday'
        ];
    }
    
    $response = [
        'success' => true,
        'date' => $selectedDate,
        'events' => $events,
        'birthdays' => $birthdays
    ];
    
    echo json_encode($response, JSON_UNESCAPED_UNICODE);
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => 'Error del servidor: ' . $e->getMessage()
    ]);
}
?>
