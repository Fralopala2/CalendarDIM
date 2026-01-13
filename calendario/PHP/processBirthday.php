<?php

include('config.php');

// Habilitar headers JSON
header('Content-Type: application/json');

// Logging para debugging
error_log("=== processBirthday.php called ===");
error_log("POST data: " . json_encode($_POST));

// Validate input
if (!isset($_POST['birthday_name']) || !isset($_POST['birthday_date'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Missing required fields: birthday_name or birthday_date']);
    error_log("Missing required fields");
    exit;
}

$birthday_name = trim($_POST['birthday_name']);
$birthday_date = $_POST['birthday_date'];
$birthday_color = isset($_POST['birthday_color']) ? $_POST['birthday_color'] : '#FF69B4';

// Validate birthday name
if (empty($birthday_name)) {
    http_response_code(400);
    echo json_encode(['error' => 'Birthday name is required']);
    exit;
}

// Validate and parse birthday date
$date_parts = date_parse($birthday_date);
if ($date_parts === false || !checkdate($date_parts['month'], $date_parts['day'], $date_parts['year'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid birthday date']);
    exit;
}

$dia_nacimiento = $date_parts['day'];
$mes_nacimiento = $date_parts['month'];
$año_nacimiento = $date_parts['year'];

// Crear fecha completa en formato YYYY-MM-DD
$fecha_cumpleanios = sprintf('%04d-%02d-%02d', $año_nacimiento, $mes_nacimiento, $dia_nacimiento);

try {
    // Check if updating existing birthday (if birthday_id or event_id is provided)
    $birthday_id = null;
    if (isset($_POST['birthday_id']) && !empty($_POST['birthday_id'])) {
        $birthday_id = intval($_POST['birthday_id']);
    } elseif (isset($_POST['event_id']) && !empty($_POST['event_id'])) {
        $birthday_id = intval($_POST['event_id']);
    }
    
    if ($birthday_id) {
        // Update existing birthday
        $sql = "UPDATE cumpleanos SET nombre = ?, dia_nacimiento = ?, mes_nacimiento = ?, fecha_cumpleanios = ?, color_evento = ? WHERE id = ?";
        $stmt = mysqli_prepare($con, $sql);
        
        if (!$stmt) {
            throw new Exception("Database prepare failed: " . mysqli_error($con));
        }
        
        mysqli_stmt_bind_param($stmt, "siissi", $birthday_name, $dia_nacimiento, $mes_nacimiento, $fecha_cumpleanios, $birthday_color, $birthday_id);
        
        if (!mysqli_stmt_execute($stmt)) {
            throw new Exception("Database execute failed: " . mysqli_stmt_error($stmt));
        }
        
        mysqli_stmt_close($stmt);
        
        echo json_encode(['success' => true, 'message' => 'Birthday updated successfully', 'id' => $birthday_id]);
        
    } else {
        // Create new birthday
        $sql = "INSERT INTO cumpleanos (nombre, dia_nacimiento, mes_nacimiento, fecha_cumpleanios, color_evento) VALUES (?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($con, $sql);
        
        if (!$stmt) {
            throw new Exception("Database prepare failed: " . mysqli_error($con));
        }
        
        mysqli_stmt_bind_param($stmt, "siiss", $birthday_name, $dia_nacimiento, $mes_nacimiento, $fecha_cumpleanios, $birthday_color);
        
        if (!mysqli_stmt_execute($stmt)) {
            throw new Exception("Database execute failed: " . mysqli_stmt_error($stmt));
        }
        
        $birthday_id = mysqli_insert_id($con);
        mysqli_stmt_close($stmt);
        
        echo json_encode(['success' => true, 'message' => 'Birthday created successfully', 'id' => $birthday_id]);
    }
    
} catch (Exception $e) {
    error_log("Birthday processing error: " . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        'error' => 'Database error occurred',
        'message' => $e->getMessage()
    ]);
}
exit;
?>