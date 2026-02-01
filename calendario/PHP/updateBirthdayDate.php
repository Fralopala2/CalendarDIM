<?php

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'error' => 'Metodo no permitido']);
    exit;
}

$id = isset($_POST['id']) ? intval($_POST['id']) : 0;
$day = isset($_POST['day']) ? intval($_POST['day']) : 0;
$month = isset($_POST['month']) ? intval($_POST['month']) : 0;

if ($id <= 0 || $day <= 0 || $day > 31 || $month <= 0 || $month > 12) {
    echo json_encode(['success' => false, 'error' => 'Datos invalidos']);
    exit;
}

try {
    global $con;
    
    $sql = "UPDATE cumpleanoscalendar SET dia_nacimiento = ?, mes_nacimiento = ? WHERE id = ?";
    $stmt = mysqli_prepare($con, $sql);
    mysqli_stmt_bind_param($stmt, 'iii', $day, $month, $id);
    
    if (mysqli_stmt_execute($stmt)) {
        echo json_encode(['success' => true, 'message' => 'Cumpleanos actualizado']);
    } else {
        echo json_encode(['success' => false, 'error' => 'Error al actualizar']);
    }
    
    mysqli_stmt_close($stmt);
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'Error del servidor']);
}
?>
