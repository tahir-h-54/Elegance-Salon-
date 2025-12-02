<?php
include 'Database/connect_to_db.php';

$success = "";
$error = "";

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = mysqli_real_escape_string($conn, $_POST['name'] ?? '');
    $surname = mysqli_real_escape_string($conn, $_POST['surname'] ?? '');
    $full_name = trim($name . ' ' . $surname);
    $procedure = mysqli_real_escape_string($conn, $_POST['procedure'] ?? '');
    $message = mysqli_real_escape_string($conn, $_POST['message'] ?? '');
    
    if(empty($full_name) || empty($message)) {
        $error = "Name and message are required.";
    } else {
        // Check if consultations table exists, if not use feedback table
        $table_check = mysqli_query($conn, "SHOW TABLES LIKE 'consultations'");
        
        if(mysqli_num_rows($table_check) > 0) {
            // Use consultations table
            $insert_query = "INSERT INTO consultations (name, procedure_type, message, status, created_at) 
                           VALUES ('$full_name', '$procedure', '$message', 'pending', NOW())";
        } else {
            // Use feedback table as fallback
            $insert_query = "INSERT INTO feedback (name, email, message, submitted_at) 
                           VALUES ('$full_name', '', '$message', NOW())";
        }
        
        if(mysqli_query($conn, $insert_query)) {
            $success = "Thank you! Your consultation request has been submitted. We'll contact you soon.";
        } else {
            $error = "Failed to submit consultation. Please try again.";
        }
    }
}

// Redirect back to home page
if($success) {
    header("Location: src/Elegance_Salon.php?consultation=success");
} else {
    header("Location: src/Elegance_Salon.php?consultation=error&msg=" . urlencode($error));
}
exit;
?>

