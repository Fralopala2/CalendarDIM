<?php
include('config.php');

header('Content-Type: application/json');

if (!isset($_GET['date'])) {
    echo json_encode(['error' => 'Fecha no proporcionada']);
    exit;
}

$date = $_GET['date'];

try {
    // Obtener eventos regulares
    $sql = "SELECT id, evento, fecha_inicio, fecha_fin, color_evento, hora_inicio, descripcion, 'event' as type
            FROM eventoscalendar 
            WHERE fecha_inicio = ? 
            ORDER BY hora_inicio ASC";
    
    $stmt = mysqli_prepare($con, $sql);
    mysqli_stmt_bind_param($stmt, 's', $date);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    $events = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $events[] = $row;
    }
    
    // Obtener cumpleaños para este día
    $dateObj = new DateTime($date);
    $day = $dateObj->format('d');
    $month = $dateObj->format('m');
    
    $sql = "SELECT id, nombre, dia_nacimiento, mes_nacimiento, color_cumpleanos, 'birthday' as type
            FROM cumpleanos 
            WHERE dia_nacimiento = ? AND mes_nacimiento = ?";
    
    $stmt = mysqli_prepare($con, $sql);
    mysqli_stmt_bind_param($stmt, 'ii', $day, $month);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    while ($row = mysqli_fetch_assoc($result)) {
        // Formatear cumpleaños como evento para la sidebar
        $birthday = [
            'id' => $row['id'],
            'evento' => '🎂 ' . $row['nombre'],
            'fecha_inicio' => $date,
            'fecha_fin' => $date,
            'color_evento' => isset($row['color_cumpleanos']) ? $row['color_cumpleanos'] : '#FF69B4',
            'hora_inicio' => '00:00', // Los cumpleaños van al inicio del día
            'descripcion' => '', // Eliminar "Cumpleaños" - solo emoji + nombre
            'type' => 'birthday'
        ];
        $events[] = $birthday;
    }
    
    // Ordenar por hora (cumpleaños primero, luego eventos por hora)
    usort($events, function($a, $b) {
        if ($a['type'] === 'birthday' && $b['type'] !== 'birthday') {
            return -1; // Cumpleaños primero
        }
        if ($a['type'] !== 'birthday' && $b['type'] === 'birthday') {
            return 1; // Cumpleaños primero
        }
        return strcmp($a['hora_inicio'], $b['hora_inicio']);
    });
    
    echo json_encode($events);
    
} catch (Exception $e) {
    echo json_encode(['error' => 'Error al obtener eventos: ' . $e->getMessage()]);
}
?>