<?php
/**
 * Process Birthday - Handle birthday creation and updates
 * Requirements: 2.1, 2.2
 */

include('config.php');

// Validate input
if (!isset($_POST['birthday_name']) || !isset($_POST['birthday_date'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Missing required fields']);
    exit;
}

$birthday_name = trim($_POST['birthday_name']);
$birthday_date = $_POST['birthday_date'];

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

try {
    // Check if this is an update (if birthday_id or event_id is provided)
    $birthday_id = null;
    if (isset($_POST['birthday_id']) && !empty($_POST['birthday_id'])) {
        $birthday_id = intval($_POST['birthday_id']);
    } elseif (isset($_POST['event_id']) && !empty($_POST['event_id'])) {
        $birthday_id = intval($_POST['event_id']);
    }
    
    if ($birthday_id) {
        // Update existing birthday
        $sql = "UPDATE cumpleañoscalendar SET nombre = ?, dia_nacimiento = ?, mes_nacimiento = ? WHERE id = ?";
        $stmt = mysqli_prepare($con, $sql);
        
        if (!$stmt) {
            throw new Exception("Database prepare failed: " . mysqli_error($con));
        }
        
        mysqli_stmt_bind_param($stmt, "siii", $birthday_name, $dia_nacimiento, $mes_nacimiento, $birthday_id);
        
        if (!mysqli_stmt_execute($stmt)) {
            throw new Exception("Database execute failed: " . mysqli_stmt_error($stmt));
        }
        
        mysqli_stmt_close($stmt);
        
        echo json_encode(['success' => true, 'message' => 'Birthday updated successfully', 'id' => $birthday_id]);
        
    } else {
        // Create new birthday
        $sql = "INSERT INTO cumpleañoscalendar (nombre, dia_nacimiento, mes_nacimiento) VALUES (?, ?, ?)";
        $stmt = mysqli_prepare($con, $sql);
        
        if (!$stmt) {
            throw new Exception("Database prepare failed: " . mysqli_error($con));
        }
        
        mysqli_stmt_bind_param($stmt, "sii", $birthday_name, $dia_nacimiento, $mes_nacimiento);
        
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
    echo json_encode(['error' => 'Database error occurred']);
}
?>