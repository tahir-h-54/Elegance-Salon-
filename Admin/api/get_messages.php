<?php
include "../../Database/connect_to_db.php";

session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role_id'] != '1') {
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

$messages = [];

// Check if consultations table exists
$table_check = mysqli_query($conn, "SHOW TABLES LIKE 'consultations'");
if(mysqli_num_rows($table_check) > 0) {
    // Get consultation messages
    $consultations_query = "SELECT * FROM consultations 
                           WHERE status = 'pending'
                           ORDER BY created_at DESC
                           LIMIT 20";
    $consultations_result = mysqli_query($conn, $consultations_query);
    while($row = mysqli_fetch_assoc($consultations_result)) {
        $messages[] = [
            'id' => $row['consultation_id'],
            'name' => $row['name'],
            'procedure' => $row['procedure_type'],
            'message' => $row['message'],
            'date' => $row['created_at'],
            'status' => $row['status']
        ];
    }
} else {
    // Fallback to feedback table
    $feedback_query = "SELECT * FROM feedback 
                      ORDER BY submitted_at DESC
                      LIMIT 20";
    $feedback_result = mysqli_query($conn, $feedback_query);
    while($row = mysqli_fetch_assoc($feedback_result)) {
        $messages[] = [
            'id' => $row['feedback_id'],
            'name' => $row['name'],
            'procedure' => '',
            'message' => $row['message'],
            'date' => $row['submitted_at'],
            'status' => 'pending'
        ];
    }
}

header('Content-Type: application/json');
echo json_encode([
    'messages' => $messages,
    'count' => count($messages)
]);
?>

