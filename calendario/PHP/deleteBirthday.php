<?php
/**
 * Delete Birthday - Handle birthday deletion
 * Requirements: 2.1
 */

require_once('config.php');

// Validate input
if (!isset($_REQUEST['id']) || empty($_REQUEST['id'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Birthday ID is required']);
    exit;
}

$id = intval($_REQUEST['id']);

try {
    // Delete birthday from database
    $sqlDeleteBirthday = "DELETE FROM cumpleanos WHERE id = ?";
    $stmt = mysqli_prepare($con, $sqlDeleteBirthday);
    
    if (!$stmt) {
        throw new Exception("Database prepare failed: " . mysqli_error($con));
    }
    
    mysqli_stmt_bind_param($stmt, "i", $id);
    
    if (!mysqli_stmt_execute($stmt)) {
        throw new Exception("Database execute failed: " . mysqli_stmt_error($stmt));
    }
    
    $affected_rows = mysqli_stmt_affected_rows($stmt);
    mysqli_stmt_close($stmt);
    
    if ($affected_rows > 0) {
        echo json_encode(['success' => true, 'message' => 'Birthday deleted successfully']);
    } else {
        echo json_encode(['error' => 'Birthday not found or already deleted']);
    }
    
} catch (Exception $e) {
    error_log("Birthday deletion error: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(['error' => 'Database error occurred']);
}
?>