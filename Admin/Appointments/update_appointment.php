<?php
session_start();
include '../../Database/connect_to_db.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id']) || $_SESSION['role_id'] != 1) {
    echo json_encode(['success' => false, 'error' => 'Unauthorized']);
    exit();
}

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $appointment_id = intval($_POST['appointment_id']);
    $new_date = mysqli_real_escape_string($conn, $_POST['new_date']);
    $new_time = mysqli_real_escape_string($conn, $_POST['new_time']);

    // Check for conflicts
    $check_conflict = "SELECT appointment_id FROM appointments 
                      WHERE appointment_id != $appointment_id 
                      AND appointment_date = '$new_date' 
                      AND appointment_time = '$new_time' 
                      AND status != 'cancelled'";
    $conflict_result = mysqli_query($conn, $check_conflict);

    if(mysqli_num_rows($conflict_result) > 0) {
        echo json_encode(['success' => false, 'error' => 'Time slot already booked']);
        exit();
    }

    $update_query = "UPDATE appointments SET appointment_date = '$new_date', appointment_time = '$new_time' WHERE appointment_id = $appointment_id";
    
    if(mysqli_query($conn, $update_query)) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Database error']);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Invalid request']);
}
?>

